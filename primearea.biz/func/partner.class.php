<?php
	class partner{
		private $db;
		private $data;
		private $user;
		function __construct(){
			global $db;
			$this->db = $db;
		}

		public function order($productId, $amount){
			//Подсчет комиссии партнера при создании заказа
			$out = array(
				'id' => 'NULL',
				'fee' => 0,
				'percent' => 0
			);
			$partnerUserId = (int)$_COOKIE['partnerUserId'];
			//Есть ли такой партнер
			$partnerId = $this->db->super_query_value("SELECT id FROM partners WHERE userId = ".$partnerUserId." LIMIT 1");
			if(!$partnerId)return $out;
			
			//Комиссия к товару
			$out['percent'] = (int)$this->db->super_query_value("
				SELECT p.partner
				FROM product p
				WHERE id = ".$productId."
				LIMIT 1
			");
			if(!$out['percent'])return $out;
			
			//Дополнительная комиссия
			$out['percent'] += (int)$this->db->super_query_value("
				SELECT ps.percent
				FROM partnerships ps
				JOIN product p ON p.idUser = ps.sellerUserId
				WHERE
					ps.partnerUserId = ".$partnerUserId." AND
					p.id = ".$productId." AND
					ps.active = 1
				LIMIT 1
			");
			
			$out['id'] = $partnerUserId;
			
			bcscale(2);
			
			$out['fee'] = bcmul(bcdiv($out['percent'], 100), $amount);
			
			return $out;
		}
		public function partnerUserId($partnerShipId, $my = false){
			
			$seller = $my ? 'AND ps.sellerUserId = '.$my : '';
			$partnerUserId = $this->db->super_query_value("
				SELECT ps.partnerUserId
				FROM partnerships ps
				WHERE
					ps.id = ".$partnerShipId." AND
					ps.concluded = 1 AND
					ps.active = 1
					".$seller."
				LIMIT 1
			");
			
			return (int)$partnerUserId;
		}
		private function checkPartnerShipStatus($partnerShipId){
			//concluded - подтвержден
			//active - активен
			$active = $this->db->super_query_value("
				SELECT ps.active
				FROM partnerships ps
				WHERE
					id = ".$partnerShipId." AND
					active = 1
				LIMIT 1
			");
			
			return $active ? true : false;
		}
		public function fixClick(){
			global $CONFIG;
			
			$partnerUserId = (int)$_GET['partner'];
			$productId = (int)$_GET['productid'];
			
			$partnerId = $this->db->super_query_value("SELECT id FROM partners WHERE userId = ".$partnerUserId." LIMIT 1");
			if(!$partnerId)return;
			
			SetCookie('partnerUserId', $partnerUserId, time()+86400, '/', $CONFIG['site_domen'], $CONFIG['cookie_SSL'], true);
			
			$this->db->query("
				INSERT INTO partnerclicks
				(partnerUserId, productId)
				VALUES (".$partnerUserId.", ".$productId.")
			");
		}
		private function newNotification($type, $partnerShipId, $recipient, $readed = 0, $data = false, $recipientUserId = 0){
			$types = array(
				'offerPartner' => 'Продавец <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a> предлагает заключить с ним <a href="/panel/partner/become/" target="_blank">партнерское соглашение</a>',
				'autoOfferPartnerSeller' => 'Пользователь системы <a href="/seller/{noPartnerUserId}/" target="_blank">{noPartnerLogin}</a> продал ваш товар и ему было выслано приглашение партнерства.</a>',
				'newPartner' => 'Вы заключили <a href="/panel/partner/partnerships/{partnerShipId}/" target="_blank">новое партнерство</a> с продавцом <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a>',
				'newPartnerSeller' => 'Вы заключили <a href="/panel/partner/mysellers/{partnerShipId}/" target="_blank">новое партнерство</a> с пользователем <a href="/seller/{partnerUserId}/" target="_blank">{partnerLogin}</a>',
				'rejectPartner' => 'Сотрудничество с продавцом <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a> <span class="bad">разорвано</span>',
				'rejectPartnerSell' => 'Сотрудничество с пользователем <a href="/seller/{partnerUserId}/" target="_blank">{partnerLogin}</a> <span class="bad">разорвано</span>',
				'changedFee' => 'Продавец <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a> изменил процентные ставки по некоторым товарам, которые вы продаете (<a href="/panel/partner/mysellers/{partnerShipId}/" target="_blank">партнерство</a> | <a href="/panel/partner/products/" target="_blank">группы товаров</a>)',
				'changedPersonalFee' => 'Продавец <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a> изменил для вас дополнительный процент с {oldFee}% на {newFee}% (<a href="/panel/partner/mysellers/{partnerShipId}/" target="_blank">партнерство</a>)',
				'removeFee' => 'Продавец <a href="/seller/{sellerUserId}/" target="_blank">{seller}</a> <span class="bad">снял</span> дополнительный процент (<a href="/panel/partner/mysellers/{partnerShipId}/" target="_blank">партнерство</a>)',
				'noPartnerSale' => 'Пользователь системы <a href="/seller/{noPartnerUserId}/" target="_blank">{noPartnerLogin}</a> ID: <span class="bold">{noPartnerUserId}</span> продал ваш товар. Не хотите ли вы <a href="/panel/partner/find/" target="_blank">предложить</a> ему сотрудничество и выставить индивидуальный процент?</a>'
			);
			$partnership = $this->db->super_query("
				SELECT ps.sellerUserId, ps.partnerUserId, u.login, uu.login AS partnerLogin
				FROM partnerships ps
				JOIN user u ON u.id = ps.sellerUserId
				JOIN user uu ON uu.id = ps.partnerUserId
				WHERE ps.id = ".$partnerShipId."
				LIMIT 1
			");
			if(!$data)$data = array('k'=>array(),'v'=>array());
			array_push($data['k'], '{partnerShipId}', '{sellerUserId}', '{seller}', '{partnerUserId}', '{partnerLogin}');
			array_push($data['v'], $partnerShipId, $partnership['sellerUserId'], $partnership['login'], $partnership['partnerUserId'], $partnership['partnerLogin']);
			$txt = str_replace($data['k'], $data['v'], $types[$type]);
			
			if(!$recipientUserId)$recipientUserId = $recipient == 'partner' ? $partnership['partnerUserId'] : $partnership['sellerUserId'];
			
			$this->db->query("
				INSERT INTO partnernotifications
				(partnerShipId, type, recipient, recipientUserId, txt, readed)
				VALUES(".$partnerShipId.", '".$type."', '".$recipient."', ".$recipientUserId.", '".$txt."', ".$readed.")
			");
		}
		public function confirmOrder($noPartnerUserId, $sellerUserId){
			//Инкрементируем продажу
			$this->db->query("UPDATE partners SET sales = sales + 1 WHERE userId = ".$noPartnerUserId." LIMIT 1");
			
			//Уведомление продавцу о проданном товаре НЕ партнером
			$partnerShipId = $this->db->super_query_value("
				SELECT id
				FROM partnerships
				WHERE
					sellerUserId = ".$sellerUserId." AND
					partnerUserId = ".$noPartnerUserId." AND
					(active = 1 OR concluded = 0)
				LIMIT 1
			");
			if($partnerShipId)return;
			
			//Настройки уведомлений
			$settings = $this->db->super_query("SELECT noPartnerSale, autoFee FROM partnerSellSett WHERE userId = ".$sellerUserId." LIMIT 1");
			
			if(!$settings['noPartnerSale'])return;
			
			$noPartnerLogin = $this->db->super_query_value("SELECT login FROM user WHERE id = ".$noPartnerUserId." LIMIT 1");
			$data = array(
				'k' => array('{noPartnerUserId}', '{noPartnerLogin}'),
				'v' => array($noPartnerUserId, $noPartnerLogin)
			);
			
			if($settings['noPartnerSale'] === '1'){
				$this->newNotification('noPartnerSale', 0, 'seller', 0, $data, $sellerUserId);
			}elseif($settings['noPartnerSale'] === '2'){
				$this->offerPartnership($noPartnerUserId, $settings['autoFee'], $sellerUserId);
				$this->newNotification('autoOfferPartnerSeller', 0, 'seller', 0, $data, $sellerUserId);
			}
		}
		public function productEdit($productId, $newPartnerFee){
			$oldPartnerFee = $this->db->super_query_value("SELECT partner FROM product WHERE id = ".$productId." LIMIT 1");
			if((int)$newPartnerFee == (int)$oldPartnerFee)return;
			
			$request = $this->db->query("
				SELECT ps.id AS partnerShipId, pn.id AS partnerNotificationsId
				FROM partnerships ps
				JOIN partnerproducts pp ON pp.patrnerShipId = ps.id
				LEFT JOIN partnernotifications pn ON
					pn.partnerShipId = ps.id AND
					pn.readed = 0 AND
					pn.type = 'changedFee'
				WHERE 
					pp.productId = ".$productId." AND
					ps.active = 1
				GROUP BY ps.id
			");
			while($row = $this->db->get_row($request)){
				if($row['partnerNotificationsId'])continue;
				
				$this->newNotification('changedFee', $row['partnerShipId'], 'partner');
			}
		}
		private function offerPartnership($partnerId, $fee, $userId){
			
			$partner = $this->db->super_query("
				SELECT u.id, ps.id AS partnerShipId
				FROM user u
				JOIN partners p ON p.userId = u.id
				LEFT JOIN partnerships ps ON
					ps.sellerUserId = ".$userId." AND
					ps.partnerUserId = u.id
				WHERE
					u.id = ".$partnerId." AND
					p.public = 1 AND
					u.id <> ".$userId." AND
					(
						ps.active IS NULL OR
						(
							ps.active = 0 AND
							ps.concluded = 1
						)
					)
				LIMIT 1
			");
			if(!$partner)return array('status' => 'error', 'message' => 'Партнер не найден или уже добавлен');
			if(
				!preg_match('/^\d{1,2}$/', $fee) ||
				$fee < 0 ||
				$fee > 50
			)return array('status' => 'error', 'message' => 'Неверный формат процента. Только целое число от 0 до 50');


			if($partner['partnerShipId']){
				$this->db->query("
					UPDATE partnerships
					SET
						concluded = 0,
						percent = ".$fee."
					WHERE id = ".$partner['partnerShipId']."
					LIMIT 1
				");
				$partnerShipId = $partner['partnerShipId'];
			}else{
				$this->db->query("
					INSERT INTO partnerships
					(sellerUserId, partnerUserId, percent)
					VALUES(".$userId.", ".$partnerId.", ".$fee.")
				");
				$partnerShipId = $this->db->insert_id();
			}
			
			
			$this->newNotification('offerPartner', $partnerShipId, 'partner');
			
			return array('status' => 'ok', 'partnershipId' => $partnerShipId);
		}
		private function partnerSellSettAuto(){
			global $user;
			if($this->db->super_query_value("SELECT id FROM partnerSellSett WHERE userId = ".$user->id." LIMIT 1"))return;
			$this->db->query("INSERT INTO partnerSellSett (userId, noPartnerSale, autoFee)VALUES(".$user->id.", 1, 0)");
		}
		public function haveMessages($userId){
			return $this->db->super_query_value("
				SELECT pm.id
				FROM partnermessages pm
				JOIN partnerships ps ON ps.id = pm.partnership
				WHERE
					(
						ps.partnerUserId = ".$userId." OR
						ps.sellerUserId = ".$userId."
					) AND
					pm.author <> ".$userId." AND
					pm.readed IS NULL AND
					ps.concluded = 1 AND
					ps.active = 1
				LIMIT 1
			");
		}
		public function numNotifications($userId){
			return $this->db->super_query_value("
				SELECT COUNT(*)
				FROM partnernotifications
				WHERE
					recipientUserId = ".$userId." AND
					readed = 0
			");
		}
		
		public function ajax($method){
			global $data, $user;
			
			$this->data = $data;
			$this->user = $user;
			
			switch($method){
				case 'becomePartner': return $this->becomePartner();
				case 'partnersList': return $this->partnersList();
				case 'partnerInfo': return $this->partnerInfo();
				case 'offersList': return $this->offersList();
				case 'offerPartnership': return $this->offerPartnershipAjax();
				case 'acceptRejectOffer': return $this->acceptRejectOffer();
				case 'rejectPartner': return $this->rejectPartner();
				case 'editPersonalPercent': return $this->editPersonalPercent();
				case 'sendMessage': return $this->sendMessage();
				case 'showMessages': return $this->showMessages();
				case 'addGroup': return $this->addGroup();
				case 'groupList': return $this->groupList();
				case 'delGroup': return $this->delGroup();
				case 'addproductsList': return $this->addproductsList();
				case 'addProducts': return $this->addProducts();
				case 'groupProductsList': return $this->groupProductsList();
				case 'delProduct': return $this->delProduct();
				case 'getGroupSelect': return $this->getGroupSelect();
				case 'getPartnerStatData': return $this->getPartnerStatData();
				case 'partnerSalesList': return $this->partnerSalesList();
				case 'partnerNotifiList': return $this->partnerNotifiList();
				case 'getPartnershipsGraph': return $this->getPartnershipsGraph();
				case 'sellerNotifiList': return $this->sellerNotifiList();
				case 'sellerStatGraph': return $this->sellerStatGraph();
				case 'sellerSettingsSave': return $this->sellerSettingsSave();
				case 'setfeeList': return $this->setfeeList();
				case 'setfeeAll': return $this->setfeeAll();
				case 'setfee': return $this->setfee();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		private function showMessages(){
			global $user, $data;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnerMessagesBlock');
			
			$partnershipId = (int)$data['partnershipId'];
			
			$partnership = $this->db->super_query_value("
				SELECT ps.id
				FROM partnerships ps
				WHERE
					ps.id = ".$partnershipId." AND
					(
						ps.sellerUserId = ".$user->id." OR
						ps.partnerUserId = ".$user->id."
					) AND
					ps.concluded = 1 AND
					ps.active = 1
				LIMIT 1
			");
			if(!$partnership)return array('status' => 'error', 'content' => 'Сообщения не найдены');
			
			$this->db->query("
				UPDATE partnermessages
				SET readed = 1
				WHERE
					partnership = ".$partnershipId." AND
					author <> ".$user->id."
			");
			
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$messages_request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS pm.text, pm.readed, pm.timestamp, u.login
				FROM partnermessages pm
				JOIN user u ON u.id = pm.author
				WHERE pm.partnership = ".$partnershipId."
				ORDER BY pm.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$messagesCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($messages_request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
					$tpl->set($nolist['orig'], $nolist['else']);
					$tpl->fory('MESSAGES');
					while($message = $this->db->get_row($messages_request)){
						$tpl->foryCycle(array(
							'date' => $message['timestamp'],
							'author' => $message['login'],
							'text' => $message['text'],
							'readed' => $message['readed'] ? 'Прочитано' : 'Не прочитано'
						));
					}
					$tpl->foryEnd();
			}
			
			if($data['method'] != 'add' && $this->db->num_rows($messages_request)){
				$pagination_js_func = 'panel.user.partner.showMessages.get';
				$pagination = pagination::getPanel($messagesCount, $elements_on_page, $current_list, $pagination_js_func);
				//$tpl->content .= '<div id="list_add_panel.user.partner.showMessages.get"></div>';
				// Партнерская программа » Продавцы » Сообщения
				$tpl->content .= '<tr id="table_pagination"><td colspan="4" style="background: #ffffff;">' . $pagination . '</td></tr>';
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function sendMessage(){
			global $user;
			
			if(!$this->data['text'])return array('status' => 'error', 'message' => 'Введите текст');
			
			$partnershipId = (int)$this->data['partnershipId'];
			$text =  nl2br($this->db->safesql($this->data['text'], true));
			
			$partnership = $this->db->super_query("
				SELECT ps.id
				FROM partnerships ps
				WHERE
					ps.id = ".$partnershipId." AND
					(
						ps.sellerUserId = ".$user->id." OR
						ps.partnerUserId = ".$user->id."
					) AND
					ps.concluded = 1 AND
					ps.active = 1
				LIMIT 1
			");
			if(!$partnership)return array('status' => 'error', 'message' => 'Ошибка доступа');
			
			$this->db->query("
				INSERT INTO partnermessages
				(partnership, author, text)
				VALUES(".$partnershipId.", ".$user->id.", '".$text."')
			");
			
			return array('status' => 'ok');
		}
		private function editPersonalPercent(){
			global $user;
			
			$partnershipId = (int)$this->data['partnershipId'];
			
			if(
				!$this->data['percent'] &&
				$this->data['percent'] !== '0'
			)return array('status' => 'error', 'message' => 'Введите процент');
			if(
				!preg_match('/^\d{1,2}$/', $this->data['percent']) ||
				$this->data['percent'] < 0 ||
				$this->data['percent'] > 50
			)return array('status' => 'error', 'message' => 'Неверный формат процента. Только целое число от 0 до 50');
			
			$partnership = $this->db->super_query("
				SELECT ps.id
				FROM partnerships ps
				WHERE
					ps.id = ".$partnershipId." AND
					ps.sellerUserId = ".$user->id." AND
					ps.concluded = 1 AND
					ps.active = 1
			");
			if(!$partnership)return array('status' => 'error', 'message' => 'Партнер не найден');
			
			$oldFee = $this->db->super_query_value("SELECT percent FROM partnerships WHERE id = ".$partnershipId." LIMIT 1");
			
			$percent = (int)$this->data['percent'];
			
			$this->db->query("
				UPDATE partnerships
				SET percent = ".$percent."
				WHERE id = ".$partnershipId."
				LIMIT 1
			");
			
			if((int)$oldFee != (int)$this->data['percent']){
				if($this->data['percent'])$this->newNotification('changedPersonalFee', $partnershipId, 'partner', 0, array('k'=>array('{oldFee}','{newFee}'),'v'=>array((int)$oldFee,(int)$this->data['percent'])));
				else $this->newNotification('removeFee', $partnershipId, 'partner');
			}
			
			return array('status' => 'ok', 'message' => 'Данные сохранены');
		}
		private function offerPartnershipAjax(){
			global $user;
			
			$partnerId = (int)$this->data['partnerId'];
			$fee = (int)$this->data['fee'];
			
			return $this->offerPartnership($partnerId, $fee, $user->id);
		}
		private function acceptRejectOffer(){
			global $user;
			
			$partnerShipId = (int)$this->data['partnerShipId'];
			if($this->data['accept'])$set = "concluded = 1, active = 1";
			else $set = "concluded = 1, active = 0";
			
			$this->db->query("
				UPDATE partnerships
				SET ".$set."
				WHERE
					id = ".$partnerShipId." AND
					partnerUserId = ".$user->id."
				LIMIT 1
			");
			
			if(!$this->db->get_affected_rows())return array('status' => 'error', 'message' => 'Предложение не найдено или уже обработано');
			
			if($this->data['accept']){
				$this->newNotification('newPartner', $partnerShipId, 'partner', 1);
				$this->newNotification('newPartnerSeller', $partnerShipId, 'seller');
			}
			
			return array('status' => 'ok');
		}
		private function rejectPartner(){
			global $user;
			
			$partnerShipId = (int)$this->data['partnerShipId'];
			
			$userColumn = $this->data['partner'] ? 'partnerUserId' : 'sellerUserId';
			
			$this->db->query("
				UPDATE partnerships
				SET active = 0
				WHERE
					id = ".$partnerShipId."
					AND ".$userColumn." = ".$user->id."
				LIMIT 1
			");
			
			if(!$this->db->get_affected_rows())return array('status' => 'error', 'message' => 'Партнерство не найдено');
			
			$readed = $this->data['partner'] ? 1 : 0;
			$this->newNotification('rejectPartner', $partnerShipId, 'partner', $readed);
			$readed = $this->data['partner'] ? 0 : 1;
			$this->newNotification('rejectPartnerSell', $partnerShipId, 'seller', $readed);
			
			return array('status' => 'ok');
		}
		private function offersList(){
			global $user;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnerOffersList');
			
			$elements_on_page = (int)$this->data['elements_on_page'];
			$current_list = (int)$this->data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$listRequest = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS ps.id AS partnerShipId, ps.sellerUserId, ps.percent, u.login
				FROM partnerships ps
				JOIN user u ON u.id = ps.sellerUserId
				WHERE
					ps.partnerUserId = ".$user->id." AND
					ps.concluded = 0
				ORDER BY ps.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($listRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($row = $this->db->get_row($listRequest)){
					$tpl->foryCycle(array(
						'sellerUserId' => $row['sellerUserId'],
						'sellerLogin' => $row['login'],
						'partnerShipId' => $row['partnerShipId'],
						'fee' => $row['percent']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($this->data['method'] != 'add'){
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->data['func']);
					//Партнерская программа » Стать партнером » Предложения от продавцов:
					$tpl->content .= '<tr id="table_pagination"><td colspan="4" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function partnerInfo(){
			include 'func/tpl.class.php';
			
			if(!$this->data['partnerId'])return array('status' => 'error', 'message' => 'Введите ID партнера');
			
			$tpl = new tpl('partnerInfo');
			
			$partnerId = (int)$this->data['partnerId'];
			
			$partner = $this->db->super_query("
				SELECT u.login, DATE(u.date) AS registered, p.rating, p.description, p.sales
				FROM user u
				JOIN partners p ON p.userId = u.id
				WHERE
					u.id = ".$partnerId." AND
					p.public = 1
				LIMIT 1
			");
			if(!$partner)return array('status' => 'error', 'message' => 'Партнер не найден');
			
			$salesMonth = $this->db->super_query_value("SELECT COUNT(id) FROM `order` WHERE partner = ".$partnerId." AND confirmed = 1 AND date > ADDDATE(NOW(), INTERVAL -1 MONTH)");
			
			$tpl->set('{login}', $partner['login']);
			$tpl->set('{registered}', $partner['registered']);
			$tpl->set('{rating}', $partner['rating']);
			$tpl->set('{description}', $partner['description']);
			$tpl->set('{partnerId}', $partnerId);
			$tpl->set('{sales}', $partner['sales']);
			$tpl->set('{salesMonth}', $salesMonth);
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function becomePartner(){
			global $data, $user;
			
			if($data['become'] && mb_strlen($data['description'])< 30)return array('status' => 'error', 'message' => 'Введите описание. Не менее 30 символов');
			$description = nl2br($this->db->safesql($data['description'], true));
			$public = $data['become'] ? 1 : 0;
			
			$partnerId = $this->db->super_query_value("
				SELECT id
				FROM partners
				WHERE userId = ".$user->id."
				LIMIT 1
			");
			
			if($partnerId){
				$this->db->query("
					UPDATE partners
					SET
						description = '".$description."',
						public = ".$public."
					WHERE id = ".$partnerId."
					LIMIT 1
				");
			}else{
				$this->db->query("
					INSERT INTO partners
					(userId, description)
					VALUES (".$user->id.", '".$description."')
				");
			}
			
			return array('status' => 'ok', 'message' => 'Данные сохранены');
		}
		private function partnersList(){
			global $user, $data;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnersList');
			
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$partnersRequest = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS p.rating, u.id, u.login, DATE(u.date) AS registered, SUM(CASE WHEN pss.id IS NULL OR pss.active = 0 THEN 0 ELSE 1 END) AS partnersCount, p.sales
				FROM partners p
				JOIN user u ON u.id = p.userId
				LEFT JOIN partnerships ps ON
					ps.sellerUserId = ".$user->id." AND
					ps.partnerUserId = u.id
				LEFT JOIN partnerships pss ON pss.partnerUserId = p.UserId
				WHERE
					p.public = 1 AND
					u.id <> ".$user->id." AND
					(
						ps.active IS NULL OR
						(
							ps.active = 0 AND
							ps.concluded = 1
						)
					)
				GROUP BY p.id
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$partnersCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($partnersRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('PARTNERS');
				while($partner = $this->db->get_row($partnersRequest)){
					$tpl->foryCycle(array(
						'login' => $partner['login'],
						'registered' => $partner['registered'],
						'rating' => $partner['rating'],
						'partnerId' => $partner['id'],
						'partnersCount' => $partner['partnersCount'],
						'sales' => $partner['sales']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($data['method'] != 'add'){
					$pagination_js_func = 'panel.user.partner.find.list.get';
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($partnersCount, $elements_on_page, $current_list, $pagination_js_func);
					// Поиск партнера
					$tpl->content .= '<tr id="table_pagination"><td colspan="6" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function groupList(){
			global $user, $data;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnersGroupList');
			
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS g.id, g.name
				FROM partnergroups g
				WHERE g.userId = ".$user->id."
				ORDER BY tmstmp DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('GROUPS');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'id' => $row['id'],
						'name' => $row['name']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($data['method'] != 'add'){
					$pagination_js_func = 'panel.user.partner.products.group.list.get';
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $pagination_js_func);
					// Группы товаров
					$tpl->content .= '<tr id="table_pagination"><td colspan="3" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
			
		}
		private function addGroup(){
			global $user;
			
			if(!$this->data['name'])return array('status' => 'error', 'message' => 'Введите название группы');
			
			$name = $this->db->safesql($this->data['name'], true);
			
			$this->db->query("
				INSERT INTO partnergroups
				(userId, name)
				VALUES(".$user->id.", '".$name."')
			");
			
			return array('status' => 'ok');
		}
		private function delGroup(){
			global $user;
			
			if(!$this->data['id'])return array('status' => 'error', 'message' => 'No data');
			
			$groupId = (int)$this->data['id'];
			
			$this->db->query("
				DELETE FROM partnergroups
				WHERE 
					id = ".$groupId." AND
					userId = ".$user->id."
				LIMIT 1
			");
			
			if(!$this->db->get_affected_rows())return array('status' => 'Группа не найдена');
			
			$this->db->query("
				DELETE FROM partnerproducts
				WHERE 
					partnerUserId = ".$user->id." AND
					groupId = ".$groupId."
			");
		
			return array('status' => 'ok');
		}
		private function addproductsList(){
			global $user, $data;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			include 'func/currency.class.php';
			
			$currency = new currency();
			$tpl = new tpl('partnerAddproductsList');
			$groupId = (int)$this->data['groupId'];
			
			$where = '';
			if((int)$this->data['seller'])$where .= " AND p.idUser = ".(int)$this->data['seller'];
			if((int)$this->data['productGroup'] && $this->data['productGroup'] !== 'no')$where .= " AND p.group = ".(int)$this->data['productGroup'];
			
			$sortArr = array(
				'p.id DESC', 
				'p.sale DESC', 
				'p.price * CASE
					WHEN curr = 1 THEN '.$currency->c[1].'
					WHEN curr = 2 THEN '.$currency->c[2].'
					WHEN curr = 3 THEN '.$currency->c[3].'
				ELSE 1 END', 
				'p.price * CASE
					WHEN curr = 1 THEN '.$currency->c[1].'
					WHEN curr = 2 THEN '.$currency->c[2].'
					WHEN curr = 3 THEN '.$currency->c[3].'
				ELSE 1 END DESC', 
				'p.name'
			);
			$sort = $sortArr[(int)$this->data['sort']];
			
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$productssRequest = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS p.id AS productId, p.idUser AS sellerId, p.name, p.price, p.curr, p.partner, p.sale, ps.id AS partnerShipId, ps.percent, ps.active, u.login
				FROM product p
				LEFT JOIN partnerships ps ON ps.sellerUserId = p.idUser AND ps.partnerUserId = ".$user->id." 
				LEFT JOIN partnerproducts pp ON pp.groupId = ".$groupId." AND pp.productId = p.id
				JOIN user u ON u.id = p.idUser
				WHERE
					p.showing = 1 AND
					p.partner > 0 AND
					pp.groupId IS NULL AND
					p.idUser <> ".$user->id."
					".$where."
				ORDER BY ".$sort."
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$partnersCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($productssRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($product = $this->db->get_row($productssRequest)){
					$fee = $product['partner'].'%'.($product['percent'] && $product['active'] ? '+'.$product['percent'].'%' : '');
					if($product['active'])$sellerLink = '<a target="_blank" href="/panel/partner/mysellers/'.$product['partnerShipId'].'/"><img src="/stylisation/images/partner24.png" style="vertical-align: middle;"> '.$product['login'].'</a>';
					else $sellerLink = '<a target="_blank" href="/seller/'.$product['sellerId'].'/">'.$product['login'].'</a>';
					$tpl->foryCycle(array(
						'productId' => $product['productId'],
						'name' => $product['name'],
						'sellerLink' => $sellerLink,
						'price' => $currency->convert($product['price'], $product['curr'], $product['curr']),
						'fee' => $fee,
						'sales' => $product['sale']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($data['method'] != 'add'){
					$pagination_js_func = 'panel.user.partner.products.group.addproducts.list.get';
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($partnersCount, $elements_on_page, $current_list, $pagination_js_func);
					// Группы товаров » Добавление товаров
					$tpl->content .= '<tr id="table_pagination"><td colspan="6" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content, 'noItem' => ($partnersCount?false:true));
		}
		private function addProducts(){
			global $user;
			
			$groupId = (int)$this->data['groupId'];
			$products = $this->data['products'];
			
			if(!is_array($products) || !count($products))return array('status' => 'error', 'message' => 'Выберите товары');
			
			foreach($products AS $k => $v)$products[$k] = (int)$v;
			
			$group = $this->db->super_query_value("
				SELECT id
				FROM partnergroups
				WHERE 
					id = ".$groupId." AND 
					userId = ".$user->id." 
				LIMIT 1
			");
			
			if(!$group)return array('status' => 'error', 'message' => 'Ошибка доступа');
			
			$productsRequest = $this->db->query("
				SELECT p.id, ps.id AS patrnerShipId
				FROM product p
				LEFT JOIN partnerships ps ON ps.sellerUserId = p.idUser AND ps.partnerUserId = ".$user->id."
				LEFT JOIN partnerproducts pp ON pp.groupId = ".$groupId." AND pp.productId = p.id
				WHERE
					p.showing = 1 AND
					pp.groupId IS NULL AND
					p.idUser <> ".$user->id." AND
					p.id IN (".implode(',',$products).")
			");
			$products = array();
			while($product = $this->db->get_row($productsRequest)){
				$patrnerShipId = $product['patrnerShipId'] ? $product['patrnerShipId'] : 'NULL';
				$products[] = '('.$patrnerShipId.', '.$user->id.', '.$groupId.', '.$product['id'].')';
			}
			
			if(count($products)){
				$this->db->query("
					INSERT INTO partnerproducts
					(patrnerShipId, partnerUserId, groupId, productId)
					VALUES ".implode(',',$products)."
				");
			}
			
			return array('status' => 'ok');
		}
		private function groupProductsList(){
			global $user, $data, $CONFIG;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			include 'func/currency.class.php';
			
			$currency = new currency();
			$tpl = new tpl('partnerProductsList');
			$groupId = (int)$data['groupId'];
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$productssRequest = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS pp.id AS partnerProductId, p.id AS productId, p.idUser AS sellerId, p.name, p.price, p.curr, ps.id AS partnerShipId, ps.active, p.partner, ps.percent, u.login
				FROM partnerproducts pp
				JOIN product p ON p.id = pp.productId
				LEFT JOIN partnerships ps ON ps.sellerUserId = p.idUser AND ps.partnerUserId = ".$user->id."
				JOIN user u ON u.id = p.idUser
				WHERE
					pp.groupId = ".$groupId."
				ORDER BY pp.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$partnersCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($productssRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($product = $this->db->get_row($productssRequest)){
					if($product['partner'])$fee = $product['partner'].'%'.($product['percent'] && $product['active'] ? '+'.$product['percent'].'%' : '');
					else $fee = '0%';
					if($product['active'])$sellerLink = '<a target="_blank" href="/panel/partner/mysellers/'.$product['partnerShipId'].'/"><img src="/stylisation/images/partner24.png" style="vertical-align: middle;"> '.$product['login'].'</a>';
					else $sellerLink = '<a target="_blank" href="/seller/'.$product['sellerId'].'/">'.$product['login'].'</a>';
					$noPercent = $product['partner'] ?  1 : 0;
					$tpl->foryCycle(array(
						'partnerProductId' => $product['partnerProductId'],
						'productId' => $product['productId'],
						'name' => $product['name'],
						'sellerLink' => $sellerLink,
						'price' => $currency->convert($product['price'], $product['curr'], $product['curr']),
						'fee' => $fee,
						'url' => $CONFIG['site_address'].'buy/'.$product['productId'].'/'.$user->id.'/',
						'active' => $noPercent
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($data['method'] != 'add'){
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($partnersCount, $elements_on_page, $current_list, $data['func']);
					// Группы товаров > Группа
					$tpl->content .= '<tr id="table_pagination"><td colspan="6" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function delProduct(){
			global $user;
			
			$partnerproductsId = (int)$this->data['partnerproductsId'];
			$groupId = (int)$this->data['groupId'];
			
			$this->db->query("
				DELETE FROM partnerproducts
				WHERE 
					id = ".$partnerproductsId." AND
					partnerUserId = ".$user->id." AND
					groupId = ".$groupId."
				LIMIT 1
			");
			
			if(!$this->db->get_affected_rows())return array('status' => 'error', 'message' => 'Товар не найден');
			
			$products = $this->db->super_query_value("
				SELECT pp.id
				FROM partnerproducts pp
				JOIN product p ON p.id = pp.productId
				WHERE
					pp.groupId = ".$groupId."
				LIMIT 1
			"); 
			
			return array('status' => 'ok', 'noproducts' => ($products?false:true));
		}
		private function getGroupSelect(){
			
			$groupId = (int)$this->data['groupId'];
			
			include 'func/group.class.php';
			
			$group = new group();
			$groups = $group->getGroups($groupId);
			
			if(!$groups)return array('status' => 'error', 'message' => 'Группы не найдены');
			
			$deep = $groups['deep'] + 1;
			$content = '
				<select id="groupSelect_'.$deep.'" onchange="panel.user.partner.products.group.addproducts.chooseGroup.action(this.options[this.selectedIndex].value, '.$deep.');">
				<option value="no">-Выберите подгруппу-</option>
			';
			foreach($groups['groups'] as $v)
				$content .= '<option value="'.$v['groupId'].'">'.$v['name'].'</option>';
			$content .= '</select>';
			
			return array('status' => 'ok', 'content' => $content);
		}
		private function getPartnerStatData(){
			global $user;
			
			if($this->data['period'] == 'days'){
				$interval = 'MONTH';
				$format = '%e.%m';
			}else{
				$interval = 'YEAR';
				$format = '%m.%Y';
			}
			
			$clicks = array();
			$clicksRequest = $this->db->query("
				SELECT COUNT(pc.id) AS `count`, DATE_FORMAT(pc.tmstmp, '".$format."') AS `date`
				FROM partnerclicks pc
				WHERE
					pc.tmstmp > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					pc.partnerUserId = ".$user->id."
				GROUP BY DATE_FORMAT(pc.tmstmp, '".$format."')
				ORDER BY pc.id
			");
			while($click = $this->db->get_row($clicksRequest))
				$clicks[$click['date']] = $click['count'];
			
			$sales = array();
			$salesRequest = $this->db->query("
				SELECT COUNT(o.id) AS `count`, DATE_FORMAT(o.`date`, '".$format."') AS `date`
				FROM `order` o
				WHERE 
					o.`date` > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					o.confirmed = 1 AND
					o.partner = ".$user->id."
				GROUP BY DATE_FORMAT(o.`date`, '".$format."')
				ORDER BY o.id
			");
			while($sale = $this->db->get_row($salesRequest))
				$sales[$sale['date']] = $sale['count'];
			
			$profits = array();
			$profitRequest = $this->db->query("
				SELECT sum(t.amount) AS `sum`, DATE_FORMAT(t.created, '".$format."') AS `date`
				FROM transactions t
				WHERE 
					t.created > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					t.user_id = ".$user->id." AND
					t.executed_id IS NOT NULL AND
					t.method = 'partnerFee'
				GROUP BY DATE_FORMAT(t.created, '".$format."')
				ORDER BY t.id
			");
			while($profit = $this->db->get_row($profitRequest))
				$profits[$profit['date']] = $profit['sum'];
			
			return array(
				'status' => 'ok', 
				'clicks' => $clicks,
				'sales' => $sales,
				'profit' => $profits
			);
		}
		private function partnerSalesList(){
			global $user;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnerPartnerStatSalesList');
			
			$elements_on_page = (int)$this->data['elements_on_page'];
			$current_list = (int)$this->data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS o.productId, o.user_id, p.name, t.amount, t.created, u.login
				FROM `order` o
				JOIN transactions t ON t.method_id = o.id
				JOIN product p ON p.id = o.productId
				JOIN user u ON u.id = o.user_id
				WHERE
					o.partner = ".$user->id." AND
					t.method = 'partnerFee'
				ORDER BY o.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'productId' => $row['productId'],
						'name' => $row['name'],
						'sellerId' => $row['user_id'],
						'seller' => $row['login'],
						'fee' => $row['amount'],
						'feeDate' => $row['created']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($this->data['method'] != 'add'){
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->data['func']);
					// Партнерская программа » Статистика » Продажи
					$tpl->content .= '<tr id="table_pagination"><td colspan="2" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function partnerNotifiList(){
			global $user;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnerNotifiList');
			
			$elements_on_page = (int)$this->data['elements_on_page'];
			$current_list = (int)$this->data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS pn.txt, pn.tmstmp, pn.readed, pn.type
				FROM partnernotifications pn
				WHERE
					pn.recipientUserId = ".$user->id." AND
					pn.recipient = 'partner'
				ORDER BY pn.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");

			$getColor = function($type) {
				switch($type) {
					case 'newPartner':
						return '#ff0000';

					case 'offerPartner':
						return '#29AD29';

					case 'rejectPartner':
						return '#0000ff';

					default : 
						return '#000';
				}
			};
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'txt' => $row['txt'],
						'date' => $row['tmstmp'],
						'color' => $getColor($row['type']),
						'notReaded' => $row['readed'] ? '' : 'notReaded'
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($this->data['method'] != 'add'){
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->data['func']);
					$tpl->content .= '<div id="list_add_'.$this->data['func'].'"></div>';
					// Партнерская программа » Статистика » Уведомления
					$tpl->content .= '<tr id="table_pagination"><td colspan="2" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			$this->db->query("
				UPDATE partnernotifications
				SET readed = 1
				WHERE
					recipientUserId = ".$user->id." AND
					readed = 0 AND
					recipient = 'partner'
			");
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function getPartnershipsGraph(){
			global $user;
			
			$partnershipId = (int)$this->data['partnershipId'];
			
			$partnerUserId = $this->partnerUserId($partnershipId, $user->id);
			
			if($this->data['period'] == 'days'){
				$interval = 'MONTH';
				$period = 'DAY';
				$format = '%e.%m';
			}else{
				$interval = 'YEAR';
				$period = 'MONTH';
				$format = '%m.%Y';
			}

			$sales = array();
			$salesRequest = $this->db->query("
				SELECT COUNT(o.id) AS `count`, DATE_FORMAT(o.`date`, '".$format."') AS `date`
				FROM `order` o
				JOIN partnerships ps ON ps.partnerUserId = o.partner
				WHERE
					o.user_id = ".$user->id." AND
					ps.id = ".$partnershipId." AND
					o.`date` > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					o.confirmed = 1
				GROUP BY ".$period."(o.`date`)
				ORDER BY o.id
			");
			while($sale = $this->db->get_row($salesRequest))
				$sales[$sale['date']] = $sale['count'];
			
			return array(
				'status' => 'ok', 
				'sales' => $sales
			);
		}
		private function sellerNotifiList(){
			global $user;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			
			$tpl = new tpl('partnerNotifiList');
			
			$elements_on_page = (int)$this->data['elements_on_page'];
			$current_list = (int)$this->data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS pn.txt, pn.tmstmp, pn.readed, pn.type
				FROM partnernotifications pn
				WHERE
					pn.recipientUserId = ".$user->id." AND
					pn.recipient = 'seller'
				ORDER BY pn.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');

			$getColor = function($type) {
				switch($type) {
					case 'newPartnerSeller':
						return '#ff0000';

					case 'offerPartner':
						return '#29AD29';

					case 'rejectPartnerSel':
						return '#0000ff';

					default : 
						return '#000';
				}
			};

			if(!$this->db->num_rows($request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'txt' => $row['txt'],
						'date' => $row['tmstmp'],
						'color' => $getColor($row['type']),
						'notReaded' => $row['readed'] ? '' : 'notReaded'
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($this->data['method'] != 'add'){
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->data['func']);
					$tpl->content .= '<div id="list_add_'.$this->data['func'].'"></div>';
					//Партнерская программа » Статистика
					$tpl->content .= '<tr id="table_pagination"><td colspan="2" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			$this->db->query("
				UPDATE partnernotifications
				SET readed = 1
				WHERE
					recipientUserId = ".$user->id." AND
					readed = 0 AND
					recipient = 'seller'
			");
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function sellerStatGraph(){
			global $user;
			
			if($this->data['period'] == 'days'){
				$interval = 'MONTH';
				$period = 'DAY';
				$format = '%e.%m';
			}else{
				$interval = 'YEAR';
				$period = 'MONTH';
				$format = '%m.%Y';
			}

			$sales = array();
			$partners = array();
			$partnerSales = array();
			
			$salesRequest = $this->db->query("
				SELECT COUNT(o.id) AS `count`, DATE_FORMAT(o.`date`, '".$format."') AS `date`
				FROM `order` o
				WHERE
					o.user_id = ".$user->id." AND
					o.partner != 0 AND
					o.`date` > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					o.confirmed = 1
				GROUP BY ".$period."(o.`date`)
				ORDER BY o.id
			");
			while($sale = $this->db->get_row($salesRequest))
				$sales[$sale['date']] = $sale['count'];

			$partnersRequest = $this->db->query("
				SELECT COUNT(o.id) AS `count`, u.login
				FROM `order` o
				JOIN user u ON u.id = o.partner
				WHERE
					o.user_id = ".$user->id." AND
					o.partner != 0 AND
					o.`date` > ADDDATE(CURRENT_TIMESTAMP, INTERVAL -1 ".$interval.") AND
					o.confirmed = 1
				GROUP BY o.partner
				ORDER BY `count`
			");
			while($partner = $this->db->get_row($partnersRequest)){
				$partners[] = $partner['login'];
				$partnerSales[] = (int)$partner['count'];
			}
			
			return array(
				'status' => 'ok', 
				'sales' => $sales,
				'partners' => $partners,
				'partnerSales' => $partnerSales
			);
		}
		private function sellerSettingsSave(){
			$noPartnerSale = (int)$this->data['notifi'];
			$fee = (int)$this->data['fee'];
			
			if($fee > 50)return array('status' => 'error', 'message' => 'Комиссия должна быть от 1 до 50%');
			
			$settingId = $this->db->super_query_value("SELECT id FROM partnerSellSett WHERE userId = ".$this->user->id." LIMIT 1");
			
			if($settingId)$query = "
				UPDATE partnerSellSett
				SET
					noPartnerSale = ".$noPartnerSale.",
					autoFee = ".$fee."
				WHERE id = ".$settingId."
				LIMIT 1";
			else $query = "
				INSERT INTO partnerSellSett
				(userId, noPartnerSale, autoFee)VALUES
				(".$this->user->id.", ".$noPartnerSale.", ".$fee.")
			";
			
			$this->db->query($query);
			
			return array('status' => 'ok', 'message' => 'Настройки успешно сохранены');
		}
		private function setfeeList(){
			global $user, $data;
			
			include 'func/tpl.class.php';
			include 'func/pagination.class.php';
			include 'func/currency.class.php';
			
			$tpl = new tpl('partnerSetfeeList');
			$currency = new currency();
			
			$elements_on_page = (int)$data['elements_on_page'];
			$current_list = (int)$data['current_list'];
			if($current_list == 0)$elementLoadInPageStart = 0;
			else $elementLoadInPageStart = $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS p.id, p.name, p.price, p.curr, p.partner
				FROM product p
				WHERE idUser = ".$user->id." AND showing = 1
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$countAll = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($request))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'productId' => $row['id'],
						'name' => $row['name'],
						'price' => $currency->convert($row['price'], $row['curr'], $row['curr']),
						'fee' => $row['partner']
					));
				}
				$tpl->foryEnd();
				
				$add = $tpl->ify('ADD');
				if($data['method'] != 'add'){
					$pagination_js_func = 'panel.user.partner.setfee.list.get';
					$tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);
					$pagination = pagination::getPanel($countAll, $elements_on_page, $current_list, $pagination_js_func);
					// Установка комиссии
					$tpl->content .= '<tr id="table_pagination"><td colspan="3" style="background: #ffffff;">' . $pagination . '</td></tr>';
					//$tpl->content .= '<div class="pagCaption">'.$pagination.'</div>';
				}else $tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function setfeeAll(){
			if(
				!$this->data['fee'] &&
				$this->data['fee'] !== '0'
			)return array('status' => 'error', 'message' => 'Введите процент');
			if(
				!preg_match('/^\d{1,2}$/', $this->data['fee']) ||
				$this->data['fee'] < 0 ||
				$this->data['fee'] > 99
			)return array('status' => 'error', 'message' => 'Неверный формат процента. Только целое число от 0 до 99');
			
			$this->db->query("UPDATE product SET partner = ".(int)$this->data['fee']." WHERE idUser = ".$this->user->id);
			
			return array('status' => 'ok');
		}
		private function setfee(){
			$products = array('ids' => array(), 'fees' => array());
			foreach($this->data AS $k => $fee){
				list($name, $productId) = explode('_',$k);
				if($name != 'product')continue;
				if(!$fee && $fee !== '0')return array('status' => 'error', 'message' => 'Введите процент');
				if(!preg_match('/^\d{1,2}$/', $fee) || $fee < 0 || $fee > 50)
					return array('status' => 'error', 'message' => 'Неверный формат процента. Только целое число от 0 до 50');
				$products['ids'][] = (int)$productId;
				$products['fees'][] = 'WHEN id = '.(int)$productId." THEN ".(int)$fee;
			}
			if(!count($products['ids']))return array('status' => 'error', 'message' => 'Товары отстутствуют');
			$this->db->query("
				UPDATE product
				SET partner = CASE ".implode(' ', $products['fees'])." END
				WHERE
					idUser = ".$this->user->id." AND
					id IN (".implode(',', $products['ids']).")
				LIMIT ".count($products['ids'])."
			");
			return array('status' => 'ok');
		}
		
		public function page(){
			switch($_GET['a']){
				case 'become': $out = $this->becomePartnerPage();break;
				case 'find': $out = $this->findPartnerPage();break;
				case 'partnerships': $out = $this->partnershipsPage();break;
				case 'products': $out = $this->productsPage();break;
				case 'addproducts': $out = $this->addproductsPage();break;
				case 'mysellers': $out = $this->mySellersPage();break;
				case 'partnerstat': $out = $this->partnerStatPage();break;
				case 'sellerstat': $out = $this->sellerstat();break;
				case 'sellersettings': $out = $this->sellersettings();break;
				case 'setfee': $out = $this->setfeePage();break;
				default: $out = $this->mainPage();
			}
			return $out;
		}
		private function mainPage(){
			global $user;
			
			$this->partnerSellSettAuto();
			
			$tpl = new tpl('partner');
			
			$partners = $this->db->super_query_value("
				SELECT COUNT(p.id) AS value
				FROM partners p
				LEFT JOIN partnerships ps ON
					ps.sellerUserId = ".$user->id." AND
					ps.partnerUserId = p.userId
				WHERE
					p.public = 1 AND
					p.userId <> ".$user->id." AND
					(
						ps.active IS NULL OR
						(
							ps.active = 0 AND
							ps.concluded = 1
						)
					)
			");
			$tpl->set('{partnersCount}', $partners ? ' ('.$partners.')' : '');
			
			$myPartners = $this->db->super_query_value("
				SELECT COUNT(ps.id) AS value
				FROM partnerships ps
				JOIN partners p ON p.userId = ps.partnerUserId
				WHERE
					ps.sellerUserId = ".$user->id." AND
					ps.concluded = 1 AND
					ps.active = 1
			");
			$tpl->set('{myPartnersCount}', $myPartners ? ' ('.$myPartners.')' : '');
			
			$mySellers = $this->db->super_query_value("
				SELECT COUNT(ps.id) AS value
				FROM partnerships ps
				WHERE
					ps.partnerUserId = ".$user->id." AND
					ps.concluded = 1 AND
					ps.active = 1
			");
			$tpl->set('{mySellersCount}', $mySellers ? ' ('.$mySellers.')' : '');
			
			$sellerMessage = $this->db->super_query_value("
				SELECT pm.id
				FROM partnermessages pm
				JOIN partnerships ps ON ps.id = pm.partnership
				WHERE
					ps.sellerUserId = ".$user->id." AND
					pm.author <> ".$user->id." AND
					pm.readed IS NULL AND
					ps.concluded = 1 AND
					ps.active = 1
				LIMIT 1
			");
			$tpl->set('{sellerMessage}', $sellerMessage ? '<img src="/stylisation/images/new_message.png">' : '');

			$partnerMessage = $this->db->super_query_value("
				SELECT pm.id
				FROM partnermessages pm
				JOIN partnerships ps ON ps.id = pm.partnership
				WHERE
					ps.partnerUserId = ".$user->id." AND
					pm.author <> ".$user->id." AND
					pm.readed IS NULL AND
					ps.concluded = 1 AND
					ps.active = 1
				LIMIT 1
			");
			$tpl->set('{partnerMessage}', $partnerMessage ? '<img src="/stylisation/images/new_message.png">' : '');
			
			$offersPartnership = $this->db->super_query_value("
				SELECT COUNT(*) AS value
				FROM partnerships ps
				WHERE
					ps.partnerUserId = ".$user->id." AND
					ps.concluded = 0
			");
			$tpl->set('{offersPartnershipCount}', $offersPartnership ? ' ('.$offersPartnership.')' : '');
			
			$notifications = $this->db->super_query_value("
				SELECT COUNT(*) AS value
				FROM partnernotifications
				WHERE
					recipientUserId = ".$user->id." AND
					recipient = 'partner' AND
					readed = 0
			");
			$tpl->set('{notifiCount}', $notifications ? ' ('.$notifications.')' : '');
			
			$sellerNotifications = $this->db->super_query_value("
				SELECT COUNT(*) AS value
				FROM partnernotifications
				WHERE
					recipientUserId = ".$user->id." AND
					recipient = 'seller' AND
					readed = 0
			");
			$tpl->set('{sellerNotifiCount}', $sellerNotifications ? ' ('.$sellerNotifications.')' : '');
			
			return array(
				'content' => $tpl->content,
				'title' => 'Партнерская программа'
			);
		}
		private function partnershipsPage(){
			global $user, $construction;
			
			if($_GET['id']){
				$partnershipId = (int)$_GET['id'];
				
				$construction->jsconfig['partner']['a'] = 'partnerships';
				$construction->jsconfig['partner']['partnershipId'] = $partnershipId;
				
				$tpl = new tpl('partnershipEdit');
				
				$partnership = $this->db->super_query("
					SELECT ps.id AS partnershipId, u.login, ps.percent
					FROM partnerships ps
					JOIN user u ON u.id = ps.partnerUserId
					JOIN partners p ON p.userId = ps.partnerUserId
					WHERE
						ps.id = ".$partnershipId." AND
						ps.sellerUserId = ".$user->id." AND
						ps.concluded = 1 AND
						ps.active = 1
					LIMIT 1
				");
				
				$nolist = $tpl->ify('NOFOUND');
				if(!$partnership){
					$tpl->set($nolist['orig'], $nolist['if']);
					$title = "Партнер не найден";
				}else {
					$tpl->set($nolist['orig'], $nolist['else']);
				
					$tpl->set('{partnershipId}', $partnership['partnershipId']);
					$tpl->set('{percent}', $partnership['percent']);
					
					$messages = new tpl('partnerMessages');
					$tpl->set('{messages}', $messages->content);
					
					$tpl->set('{partnershipId}', $partnershipId);
					
					$title = 'Партнер '.$partnership['login'];
				}
				$tpl->set('{login}', $partnership['login'] ? $partnership['login'] : 'Партнер не найден');
				return array(
					'content' => $tpl->content,
					'title' => $title
				);
			}
			
			
			$tpl = new tpl('partnerships');
			
			$this->db->query("
				SELECT ps.id AS partnershipId, u.login, p.rating, ps.percent, DATE(ps.timestamp) AS assignDate, pm.id AS message, u.id as userId
				FROM partnerships ps
				JOIN user u ON u.id = ps.partnerUserId
				JOIN partners p ON p.userId = ps.partnerUserId
				LEFT JOIN partnermessages pm ON
					pm.partnership = ps.id AND
					pm.author <> ".$user->id." AND
					pm.readed IS NULL 
				WHERE
					ps.sellerUserId = ".$user->id." AND
					ps.concluded = 1 AND
					ps.active = 1
				GROUP BY ps.id
			");
			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($partnersRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('MYPARTNERS');
				while($partner = $this->db->get_row()){
					$tpl->foryCycle(array(
						'login' => $partner['login'],
						'rating' => $partner['rating'],
						'partnerId' => $partner['partnershipId'],
						'percent' => $partner['percent'],
						'assignDate' => $partner['assignDate'],
						'message' => $partner['message'] ? '<img src="/stylisation/images/new_message.png">' : '',
						'userId' => $partner['userId']
					));
				}
				$tpl->foryEnd();
			}
			
			return array(
				'content' => $tpl->content,
				'title' => 'Мои партнеры'
			);
		}
		private function becomePartnerPage(){
			global $user, $construction;
			
			$construction->jsconfig['partner']['a'] = 'become';
			
			$partner = $this->db->super_query("
				SELECT description, public
				FROM partners
				WHERE userId = ".$user->id."
				LIMIT 1
			");
			
			$public = $partner['public'] ? 'checked' : '';
			
			$tpl = new tpl('partnerBecome');

			$tpl->set('{userId}',$user->id);
			$tpl->set('{public}',$public);
			$tpl->set('{description}',$partner['description']);
			return array(
				'content' => $tpl->content,
				'title' => 'Стать партнером'
			);
		}
		private function findPartnerPage(){
			global $user, $construction;
			
			$construction->jsconfig['partner']['a'] = 'find';
			
			$tpl = new tpl('partnerFind');
			
			
			return array(
				'content' => $tpl->content,
				'title' => 'Поиск партнера'
			);
		}
		private function addproductsPage(){
			global $user, $construction;
			
			$groupId = (int)$_GET['id'];
			
			$construction->jsconfig['partner']['a'] = 'addproducts';
			$construction->jsconfig['partner']['groupId'] = $groupId;
			
			$tpl = new tpl('partnerAddProducts');
			
			$group = $this->db->super_query("
				SELECT pg.id, pg.name
				FROM partnergroups pg
				WHERE 
					id = ".$groupId ." AND
					userId = ".$user->id."
				LIMIT 1
			");
			if(!$group)$tpl->switchy('template', 'notFound');
			else{
				$sellersRequest = $this->db->query("
					SELECT p.idUser, u.login, COUNT(u.id) AS `count`
					FROM product p
					JOIN partnerships ps ON ps.sellerUserId = p.idUser
					LEFT JOIN partnerproducts pp ON pp.groupId = ".$groupId." AND pp.productId = p.id
					JOIN user u ON u.id = p.idUser
					WHERE
						ps.concluded = 1 AND
						ps.active = 1 AND
						ps.partnerUserId = ".$user->id." AND
						p.showing = 1 AND
						pp.groupId IS NULL
					GROUP BY u.id
					ORDER BY p.id DESC
				");
				$tpl->fory('userlist');
				while($seller = $this->db->get_row($sellersRequest)){
					$tpl->foryCycle(array(
						'userId' => $seller['idUser'],
						'login' => $seller['login'],
						'count' => $seller['count'],
					));
				}
				$tpl->foryEnd();
				
				$tpl->switchy('template', 'page');
				$tpl->set('{groupName}', $group['name']);
				$tpl->set('{groupId}', $groupId);
			}
			
			return array(
				'content' => $tpl->content,
				'title' => 'Товары продавцов'
			);
		}
		private function productsPage(){
			global $user, $construction, $CONFIG;
			
			if($_GET['id']){
				$groupId = (int)$_GET['id'];
				
				$construction->jsconfig['partner']['a'] = 'products';
				$construction->jsconfig['partner']['groupId'] = $groupId;
				
				$tpl = new tpl('partnerProducts');
			
				
				$group = $this->db->super_query("
					SELECT pg.id, pg.name
					FROM partnergroups pg
					WHERE 
						id = ".$groupId ." AND
						userId = ".$user->id."
					LIMIT 1
				");
				$nogroup = $tpl->ify('NOGROUP');
				if(!$group)$tpl->switchy('template', 'notFound');
				else{
					include 'func/currency.class.php';
					$currency = new currency();
					$tpl->switchy('template', 'page');
					$tpl->set('{groupName}', $group['name']);
					$tpl->set('{groupId}', $groupId);
					
					$htmlCodeBody = new tpl('partnerGroupHTML');
					$htmlCodeBody->switchy('html', 'body');
					$productssRequest = $this->db->query("
						SELECT SQL_CALC_FOUND_ROWS pp.id AS partnerProductId, p.id AS productId, p.name, p.price, p.curr, pi.path
						FROM partnerproducts pp
						JOIN product p ON p.id = pp.productId
						LEFT JOIN picture pi ON pi.id = p.picture
						WHERE pp.groupId = ".$groupId."
						ORDER BY pp.id DESC
					");
					$htmlCodeBody->fory('list');
					while($product = $this->db->get_row($productssRequest)){
						$htmlCodeBody->foryCycle(array(
							'name' => $product['name'],
							'url' => $CONFIG['site_address'].'buy/'.$product['productId'].'/'.$user->id.'/',
							'imgUrl' => $CONFIG['site_address'].($product['path'] ? '/picture/'.$product['path'].'productshow.jpg' : 'stylisation/images/no_img200_125.png'),
							'price' => $currency->convert($product['price'], $product['curr'], $product['curr'])
						));
					}
					$htmlCodeBody->foryEnd();
					$htmlCodeBody = htmlentities(trim($htmlCodeBody->content), ENT_QUOTES, "UTF-8");
					$tpl->set('{htmlCodeBody}', $htmlCodeBody);

					$htmlCodeHead = new tpl('partnerGroupHTML');
					$htmlCodeHead->switchy('html', 'style');
					$htmlCodeHead = htmlentities(trim($htmlCodeHead->content), ENT_QUOTES, "UTF-8");
					$tpl->set('{htmlCodeHead}', $htmlCodeHead);
					
				}
			}else{
				$construction->jsconfig['partner']['a'] = 'productGroups';
				
				$tpl = new tpl('partnerPGroups');
			}
			
			return array(
				'content' => $tpl->content,
				'title' => 'Товары продавцов'
			);
		}
		private function mySellersPage(){
			global $user, $construction;
			
			if($_GET['id']){
				$partnershipId = (int)$_GET['id'];
				
				$tpl = new tpl('partnerMySeller');

				$construction->jsconfig['partner']['a'] = 'mysellers';
				$construction->jsconfig['partner']['partnershipId'] = $partnershipId;
				
				if($this->checkPartnerShipStatus($partnershipId)){
					$partnership = $this->db->super_query("
						SELECT ps.id AS partnershipId, u.login, ps.percent
						FROM partnerships ps
						JOIN user u ON u.id = ps.sellerUserId
						WHERE
							ps.id = ".$partnershipId." AND
							ps.partnerUserId = ".$user->id."
						LIMIT 1
					");
				}
				
				$nolist = $tpl->ify('NOFOUND');
				if(!$partnership){
					$tpl->set($nolist['orig'], $nolist['if']);
					$title = "Продавец не найден";
				}else {
					$tpl->set($nolist['orig'], $nolist['else']);
				
					$tpl->set('{partnershipId}', $partnership['partnershipId']);
					$tpl->set('{percent}', $partnership['percent']);
					
					$messages = new tpl('partnerMessages');
					$tpl->set('{messages}', $messages->content);
					
					$tpl->set('{partnershipId}', $partnershipId);
					
					$title = 'Продавец '.$partnership['login'];
				}
				$tpl->set('{login}', $partnership['login'] ? $partnership['login'] : 'Продавец не найден');
				return array(
					'content' => $tpl->content,
					'title' => $title
				);
			}
			
			$tpl = new tpl('partnerMySellers');
			
			$this->db->query("
				SELECT ps.id AS partnershipId, u.login, ps.percent, DATE(ps.timestamp) AS assignDate, pm.id AS message
				FROM partnerships ps
				JOIN user u ON u.id = ps.sellerUserId
				LEFT JOIN partnermessages pm ON
					pm.partnership = ps.id AND
					pm.author <> ".$user->id." AND
					pm.readed IS NULL
				WHERE
					ps.partnerUserId = ".$user->id." AND
					ps.concluded = 1 AND
					ps.active = 1
				GROUP BY ps.id
			");

			$nolist = $tpl->ify('NOLIST');
			if(!$this->db->num_rows($partnersRequest))$tpl->set($nolist['orig'], $nolist['if']);
			else{
				$tpl->set($nolist['orig'], $nolist['else']);
				$tpl->fory('MYSELLERS');
				while($partner = $this->db->get_row()){
					$tpl->foryCycle(array(
						'login' => $partner['login'],
						'partnerId' => $partner['partnershipId'],
						'percent' => $partner['percent'],
						'assignDate' => $partner['assignDate'],
						'message' => $partner['message'] ? '<img src="/stylisation/images/new_message.png">' : ''
					));
				}
				$tpl->foryEnd();
			}

			return array(
				'content' => $tpl->content,
				'title' => 'Продавцы'
			);
		}
		private function partnerStatPage(){
			global $user, $construction;
			
			$construction->jsconfig['partner']['a'] = 'partnerStat';
			
			$tpl = new tpl('partnerPartnerStat');
			
			$rating = $this->db->super_query_value("SELECT rating FROM partners WHERE userId = ".$user->id." LIMIT 1");
			
			$tpl->set('{rating}', $rating);
			
			return array(
				'content' => $tpl->content,
				'title' => 'Статистика'
			);
		}
		private function sellerstat(){
			global $user, $construction;
			
			$construction->jsconfig['partner']['a'] = 'sellerStat';
			
			$tpl = new tpl('partnerSellerStat');
			
			return array(
				'content' => $tpl->content,
				'title' => 'Статистика'
			);
		}
		private function sellersettings(){
			global $user;
			
			$construction->jsconfig['partner']['a'] = 'sellerStat';
			
			$tpl = new tpl('partnerSellerSettings');
			
			$settings = $this->db->super_query("SELECT noPartnerSale, autoFee FROM partnerSellSett WHERE userId = ".$user->id." LIMIT 1");
			
			$noPartnerSale = $settings['noPartnerSale'] ? $settings['noPartnerSale'] : 0;
			$autoFee = $settings['autoFee'] ? $settings['autoFee'] : 0;
			
			for($i=0;$i<3;$i++){
				$tpl->set('{notifi'.$i.'}', $noPartnerSale == $i ? 'checked' : '');
			}
			$tpl->set('{autoFee}', $autoFee);
			
			return array(
				'content' => $tpl->content,
				'title' => 'Статистика'
			);
		}
		private function setfeePage(){
			global $user,$construction;
			
			$construction->jsconfig['partner']['a'] = 'setfee';
			
			$tpl = new tpl('partnerSetfee');
			
			return array(
				'content' => $tpl->content,
				'title' => 'Статистика'
			);
		}

        public function notification($partnerUserId, $productName, $sellerLogin, $partnerFee)
        {
            global $CONFIG;

            $user = $this->db->super_query("
				SELECT email, emailInforming
				FROM user
				WHERE id = $partnerUserId
			");
            if ('1' === $user['emailInforming']) {
            	$subject = 'ПРОДАН ТОВАР';
                $message = "<p>С вашей помощью продан товар \"$productName\"</p>
						<p>Продавец: $sellerLogin</p>
						<p>Начисления: {$partnerFee}р</p>
						<br>
						<p>Письмо сформировано автоматически и не требует ответа.</p>
						<p>Отключить уведомления вы можете в настройках аккаунта.</p>
						<br>
						<p>C Уважением, команда {$CONFIG['site_domen']}</p>
				";
                $message = $this->db->safesql($message);
                $this->db->query("
					INSERT INTO `mail`
						(subject, message, `to`, `status`)
					VALUES
						('$subject', '$message', '{$user['email']}', 'need')
				");
            }

        }
    }
?>
