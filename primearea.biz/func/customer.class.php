<?php

	class customer{
		public $email;
		public $seller_id;
		public $order_date;
		public $order_id;//Доступ только к конкретному заказу
		private $data;
		private $db;
		function __construct(){
			global $db;
			$this->db = $db;
		}
		public function ajax($method){
			global $data, $db;
			
			$this->data = $data;
			$this->db = $db;
			
			switch($method){
				case 'unsubscribe': return $this->unsubscribe();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		private function unsubscribe(){
			if(!$this->data['h'] || !$this->data['type'])return array('status' => 'error', 'message' => 'Missing data');
			
			$h = $this->db->safesql($this->data['h']);
			
			if($this->data['type'] == 'common'){
				$unsubscribe = $this->db->super_query("SELECT id FROM unsubscribe WHERE `key` = '".$h."' LIMIT 1");
				if(!$unsubscribe['id'])return array('status' => 'error', 'message' => 'Access dennied');
				$buy = $this->data['buy'] ? 0 : 1;
				$orderUpdate = $this->data['orderUpdate'] ? 0 : 1;
				$this->db->query("
					UPDATE `unsubscribe`
					SET 
						buy = ".$buy.",
						orderUpdate = ".$orderUpdate."
					WHERE id = ".$unsubscribe['id']."
					LIMIT 1
				");
			}else{
				if(!$this->data['orderId'])return array('status' => 'error', 'message' => 'Missing data');
				$orderId = (int)$this->data['orderId'];
				//Проверить существует ли заказ с таким номером
				$productId = $this->db->super_query_value("SELECT productId FROM `order` WHERE id=".$orderId." LIMIT 1");
				if(!$productId)return array('status' => 'error', 'message' => 'Order not exist');
				//Проверить ключ, получить емейл
				if(!$this->checkKey($h))return array('status' => 'error', 'message' => 'Access denied(key)');
				//Если доступ только к конкретному заказу проверить совпадение
				if($this->order_id && (int)$this->order_id !== $orderId)return array('status' => 'error', 'message' => 'Stranger customer access');
				//Проверить ваша ли это покупка
				if(!$this->checkOrder($productId, $orderId))return array('status' => 'error', 'message' => 'Stranger order');
				//Отписываемся
				$this->db->query("
					UPDATE `order`
					SET unsubscribeCustomer = 1
					WHERE id = ".$orderId."
					LIMIT 1
				");
			}
			
			return array('status' => 'ok', 'message' => 'Настройки сохранены');
		}
		public function create_key($email, $order_id = false){
			global $bd;
			$request = $bd->read("SELECT id FROM `order` WHERE userIdEmail='".$email."' LIMIT 1");
			if(!$bd->rows)return FALSE;
			$i = 1;
			while($i > 0){
				$h = hash("sha256", $email . microtime().$i);
				$bd->read("SELECT id FROM customer_session WHERE crypt = '".$h."' LIMIT 1");
				if(!$bd->rows)$i = 0;
				else $i++;
			}
			$order_id = $order_id ? $order_id : 'NULL';
			$bd->write("INSERT INTO customer_session VALUES(NULL, '".$email."', '".$h."', ".$order_id.", '".time()."')");
			return $h;
		}
		public function checkKey($h){

			if(!$h)return FALSE;
			$custSess = $this->db->super_query("SELECT email, order_id FROM customer_session WHERE BINARY crypt='".$h."' LIMIT 1");
			if(!$custSess['email'])return FALSE;

			$this->email = $custSess['email'];
			$this->order_id = $custSess['order_id'];
			return TRUE;
		}
		public function checkOrder($product_id, $i){//Проверить ваша ли это покупка
			$order = $this->db->super_query("
				SELECT 	date, 
						(SELECT idUser FROM product WHERE id = ".$product_id.") AS sellerId
				FROM `order` 
				WHERE 	id=".$i." 
						AND userIdEmail='".$this->email."' 
				LIMIT 1
			");
			if(!$order['date'])return FALSE;
			$this->seller_id = $order['sellerId'];
			$this->order_date = strtotime($order['date']);
			return TRUE;
		}
		public function check_key($h){
			global $bd;
			if(!$h)return FALSE;
			$request = $bd->read("SELECT email, order_id FROM customer_session WHERE BINARY crypt='".$h."' LIMIT 1");
			if(!$bd->rows)return FALSE;
			$this->email = mysql_result($request,0,0);
			$this->order_id = mysql_result($request,0,1);
			return TRUE;
		}
		public function check_order($product_id, $i){//Проверить ваша ли это покупка
			global $bd;
			$request = $bd->read("	SELECT 	date, 
											(SELECT idUser FROM product WHERE id = ".$product_id.") 
									FROM `order` 
									WHERE 	id=".$i." 
											AND userIdEmail='".$this->email."' 
									LIMIT 1");
			if(!$bd->rows)return FALSE;
			$this->seller_id = mysql_result($request,0,1);
			$this->order_date = strtotime(mysql_result($request,0,0));
			return TRUE;
		}
		public function page(){
			global $bd, $db, $construction,$timeReserv, $siteAddr;
			
			$construction->jsconfig['customer'] = array('h' => $_GET['h'], 'i' => $_GET['i'], 'sp' => $_GET['sp']);

			$tpl = new templating(file_get_contents(TPL_DIR.'customer.tpl'));
			
			if(!isset($_GET['h']) || $_GET['h'] === 'undefined' || $_GET['h'] === ''){//Вывод процедуры получения ключа
				$tpl->switchy('CABINET', 'HELLO');
				return $tpl->content;
			}
			
			//Страница отписок по прямой ссылке
			if($_GET['sp'] === 'unsubscribe' && mb_strlen($_GET['h']) === 128){
				$h = $bd->prepare($_GET['h'], 128);
				
				$request = $bd->read("SELECT id, buy, orderUpdate FROM unsubscribe WHERE `key` = '".$h."' LIMIT 1");
				if(!$bd->rows){
					$tpl->switchy('CABINET', 'ERRORCODE');
					return $tpl->content;
				}
				$buyChecked = mysql_result($request,0,1);
				$orderUpdateChecked = mysql_result($request,0,2);
				$tpl->switchy('CABINET', 'UNSUBSCRIBE');
				
				$tpl->set('{buyChecked}', $buyChecked ? '' : 'checked');
				$tpl->set('{orderUpdateChecked}', $orderUpdateChecked ? '' : 'checked');
				
				return $tpl->content;
			}
			
			$curr = new current_convert();
			
			$h = $bd->prepare($_GET['h'], 64);
			$tpl->content = str_replace('{h}', 	$h, 	$tpl->content);
			
			if(!$this->check_key($h)){//Проверка валидности ключа
				$tpl->switchy('CABINET', 'ERRORCODE');
				return $tpl->content;
			}
			
			if($this->order_id && $this->order_id !== $_GET['i']){//Доступ только к конкретному заказу
				$tpl->switchy('CABINET', 'HELLO');
				return $tpl->content;
			}
			
			if($_GET['sp'] === 'promocodes'){
				$tpl->switchy('CABINET', 'PROMOCODES');
				$request = $bd->read("
					SELECT pre.code, u.login, pr.dateend, pr.type, pr.maxuse, u.id
					FROM `order` o
					JOIN promocode_el pre ON pre.id = o.promocode_el_id_issued
					JOIN promocodes pr ON pr.id = pre.promocode_id
					JOIN product p ON p.id = o.productId
					JOIN user u ON u.id = p.idUser
					WHERE 	o.userIdEmail = '".$this->email."' 
						AND o.promocode_el_id_issued <> 0
						AND NOW() < pr.dateend
						AND 0 < (CASE 
									WHEN pr.type = 1 THEN 1 - pre.used
									WHEN pr.type = 2 THEN 1
									WHEN pr.type = 3 THEN pr.maxuse - pre.used
								END)
				");
				$nopromocodeslist = $tpl->ify('NOPROMOCODESLIST');
				if(!$bd->rows)$tpl->content = str_replace($nopromocodeslist['orig'], $nopromocodeslist['if'], $tpl->content);
				else{
					$tpl->content = str_replace($nopromocodeslist['orig'], $nopromocodeslist['else'], $tpl->content);
					$tpl->fory('PROMOCODESLIST');
					$using_arr = array('', 'Однократное', 'Многократное', 'Не более ');
					for($i=0;$i<$bd->rows;$i++){
						$using = $using_arr[mysql_result($request,$i,3)];
						if(mysql_result($request,$i,3) == 3)$using .= mysql_result($request,$i,3).' раз';
						$tpl->fory_cycle(array(	
							'code' => mysql_result($request,$i,0),
							'seller' => mysql_result($request,$i,1),
							'dateend' => mysql_result($request,$i,2),
							'seller_id' => mysql_result($request,$i,5),
							'using' => $using
						));
					}
					$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
				}
				return $tpl->content;
			}
			
			if(!isset($_GET['i']) || $_GET['i'] === 'undefined' || $_GET['i'] === ''){//Вывод листинга товаров
				$tpl->switchy('CABINET', 'PRODUCTLIST');
				return $tpl->content;
			}	
			



			$id = (int)$_GET['i'];
			$tpl->content = str_replace('{i}', 	$id,	$tpl->content);

			$uri = explode('?', $_SERVER['REQUEST_URI']);

			if(isset($uri[1])) {
				$yandex_visa = $uri[1];
				$temp = explode('&', $yandex_visa);

				if(isset($temp[3])){
					$context_id = explode('=', $temp[3]);
					$context_id = $context_id[1];
				}

				if(isset($temp[8])){
					$sum = explode('=', $temp[8]);
					$sum = $sum[1];
				}

				if(isset($temp[6])){
					$temp = explode('=', $temp[6]);
					$temp = $temp[1];
				}

			}

			$res = $bd->read("	SELECT 	`yandex_cart_context_id`, `totalBuyer`, `taken_yandex_request_id`, `taken_yandex_sum`, payment_account_id, `status` FROM `order`
						WHERE id = ".$id." LIMIT 1");

			$db_context_id = mysql_result($res,0,0);
			$db_sum = mysql_result($res,0,1);
			$taken_yandex_request_id = mysql_result($res,0,2);
			$taken_yandex_sum = mysql_result($res,0,3);
			$paymentAccountId = (int)mysql_result($res,0,4);
			$orderStatus = mysql_result($res,0,5);

			if ($paymentAccountId !== 0) {
                require_once dirname(__file__).'/../func/PaymentAccountFetcher.php';
                $paymentAccountFetcher = new PaymentAccountFetcher($db);
                $paymentAccount = $paymentAccountFetcher->getById($paymentAccountId);
			}

			if($orderStatus === 'pay' && $taken_yandex_request_id && $paymentAccountId !== 0){
				require_once dirname(__file__).'/../modules/buy/yandex_cart/result.php';
				require_once('lib/external_payment.php');
				require_once('lib/api.php');

				if($db_context_id == $taken_yandex_request_id && $db_sum <= $taken_yandex_sum){
					receivePayment($id,$db_context_id,$paymentAccount['config']['instance_id']);
				}else{
					logs('yandex_api_error|'.json_encode($uri));
				}

			}
			

			if(isset($temp) && $temp != 'refused'){
				//yandex_visa
    			$bd->write("UPDATE `order` SET taken_yandex_request_id = '".$context_id."', taken_yandex_sum = '".$sum."' WHERE id = ".$id." LIMIT 1");
 				header("Location: ".$siteAddr."customer/".$_GET['i']."/".$_GET['h']."/");
			}

			$request = $bd->read("	SELECT 	o.date, o.productId, o.price, o.curr, p.name, p.descript, p.info, 
											p.idUser, u.login, u.fio, u.email, u.phone, u.skype, u.wm, p.typeObject, p.many, o.discount, o.discountper,
											(CASE 	WHEN p.typeObject = 1 AND p.many = 0 THEN (SELECT text FROM product_text WHERE orderid = ".$id." LIMIT 1)
													WHEN p.typeObject = 0 AND p.many = 0 THEN (SELECT name FROM product_file WHERE orderid = ".$id." LIMIT 1)
													WHEN p.typeObject = 1 AND p.many = 1 THEN (SELECT text FROM product_text WHERE idProduct = o.productId LIMIT 1)
													WHEN p.typeObject = 0 AND p.many = 1 THEN (SELECT name FROM product_file WHERE idProduct = o.productId LIMIT 1) END),
											o.status, o.promocode_el_id_issued, pe.code, o.unsubscribeCustomer, o.pay_method_id 
									FROM `order` o
									INNER JOIN product p ON o.productId = p.id
									INNER JOIN user u ON p.idUser = u.id
									LEFT JOIN promocode_el pe ON pe.id = o.promocode_el_id_issued
									WHERE o.id = ".$id." AND o.userIdEmail = '".$this->email."'
									LIMIT 1");

			if(!$bd->rows){//Если товар чужой
				$tpl->switchy('CABINET', 'ERRORCODE');
				return $tpl->content;
			}


			
			$status = mysql_result($request,0,19);
			$promocode_el_id_issued = mysql_result($request,0,20);
			$promocode_code = mysql_result($request,0,21);
			$unsubscribeCustomer = mysql_result($request,0,22);
			$payMethodId = (int)mysql_result($request,0,23);
			$product_id = mysql_result($request,0,1);
			$seller_id = mysql_result($request,0,7);
			$price = mysql_result($request,0,2);
			$date = mysql_result($request,0,0);
			$typeObject = mysql_result($request,0,14);
			$name = strBaseOut(mysql_result($request,0,4));
			$info = strBaseOut(mysql_result($request,0,6));
			$descript = strBaseOut(mysql_result($request,0,5));
			$login = mysql_result($request,0,8);
			$fio = strBaseOut(mysql_result($request,0,9));
			$email = mysql_result($request,0,10);
			$phone = strBaseOut(mysql_result($request,0,11));
			$skype = mysql_result($request,0,12);
			$wm = mysql_result($request,0,13);
			$discount = mysql_result($request,0,16);
			$discountper = mysql_result($request,0,17);
			$object = strBaseOut(mysql_result($request,0,18));
			$curr_price = mysql_result($request,0,3);
			

			if($status != "paid" && $status != "sended" && $status != "review"){//Ожидание оплаты товара
				$tpl->switchy('CABINET', 'WAITPRODUCT');
				$expired = $tpl->ify('EXPIRED');
				if( (time() - ($timeReserv * 60)) > strtotime($date) )$tpl->content = str_replace($expired['orig'], $expired['else'], $tpl->content);
				else $tpl->content = str_replace($expired['orig'], $expired['if'], $tpl->content);

                $ifyQiwi = $tpl->ify('PAY_METHOD_QIWI');
                if ($payMethodId === 37) {
                    $tpl->content = str_replace($ifyQiwi['orig'], $ifyQiwi['if'], $tpl->content);
                    $qiwiAccount = isset($paymentAccount) ? "+{$paymentAccount['config']['account']}" : 'UNKNOWN';
                    $tpl->set('{qiwi_account}', $qiwiAccount);
                } else {
                    $tpl->content = str_replace($ifyQiwi['orig'], $ifyQiwi['else'], $tpl->content);
                }

				return $tpl->content;
            }

			$curr_price = $curr_price ? $curr_price : 4;//для отображения старых покупок
			$price = $curr->curr($price, $curr_price, $curr_price);	
			$tpl->content = str_replace('{date}', 		$date, 		$tpl->content);
			$tpl->content = str_replace('{price}', 		$price, 	$tpl->content);
			$tpl->content = str_replace('{name}', 		$name, 		$tpl->content);
			
			if(isset($_GET['sp']) && $_GET['sp'] == 'messages'){//Страница связи с продавцом
				
				$mail = new mail();
				$unsubscribe = $mail->unsubscribe($this->email);
				if($unsubscribe['un']['orderUpdate']){
					$tpl->switchy('unsubscribe', 'common');
					$tpl->set('{unsubscribeKey}', $unsubscribe['key']);
				}elseif($unsubscribeCustomer)$tpl->switchy('unsubscribe', 'already');
				else $tpl->switchy('unsubscribe', 'un');
				
				$tpl->switchy('CABINET', 'CONTACT_SELLER');
				
				$message_content = $tpl->ify('MESSAGES');
				$bd->write("UPDATE message SET status = 'read' WHERE order_id = ".$id." AND `status` = 'not_read' AND author != '".$this->email."'");
				$request = $bd->read("	SELECT 	m.author, m.text, m.date, m.person, m.status,
												(CASE WHEN m.person = 'Продавец' THEN 
													(SELECT login FROM user WHERE id = m.author LIMIT 1) END)
										FROM message m
										WHERE m.order_id = ".$id." 
										ORDER BY m.id DESC 
										LIMIT 30");
				if(!$bd->rows)$tpl->content = str_replace($message_content['orig'], $message_content['else'], $tpl->content);
				else{
					$tpl->content = str_replace($message_content['orig'], $message_content['if'], $tpl->content);
					$tpl->fory('MESSAGES');
					for($i=0;$i<$bd->rows;$i++){
						$message_status = mysql_result($request,$i,4) == 'read' ? '<span style="color:#00cd6b;">Прочитано</span>' : '<span style="color:#ff6a6a;">Не прочитано</span>';
						$message_person  = mysql_result($request,$i,3);
						$message_author = $message_person == 'Продавец' ? mysql_result($request,$i,5) : mysql_result($request,$i,0);

						$_text = strBaseOut(mysql_result($request,$i,1));
						$testString = explode('/', $_text);
						if($testString[1] == 'picture'){
							$extTest = explode('.',$testString[6]);
							if(end($extTest) == 'zip' || end($extTest) == 'rar'){
								$_text = '<div><a href="'.$_text.'">Скачать архив</a></div>';
							}else{
								$_text = '<div><a href="'.$_text.'"><img style="max-width: 220px" height="86px;" src="'.$_text.'"></a></div>';
							}							
						}

						$tpl->fory_cycle(array(	'mdate' => mysql_result($request,$i,2),
												'author' =>$message_author,
												'person' => $message_person,
												'status' => $message_status,
												'text' => $_text
						));
					}
					$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
				}
				$tpl->set('{info}', $info);
				$tpl->set('{descript}', $descript);
				return $tpl->content;
			}
			
			$object_content = $tpl->ify('OBJECT');

			if($typeObject)$tpl->content = str_replace($object_content['orig'], $object_content['if'], $tpl->content);
			else $tpl->content = str_replace($object_content['orig'], $object_content['else'], $tpl->content);

			$tpl->content = str_replace('{object}', $object, $tpl->content);

			$discount_ify = $tpl->ify('DISCOUNT');
			if($discount){
				$discount = $curr->curr($discount, $curr_price, $curr_price);
				$discount_ify['if'] = str_replace("{discount}", $discount, $discount_ify['if']);
				$discount_ify['if'] = str_replace("{discountper}", $discountper, $discount_ify['if']);
				$tpl->content = str_replace($discount_ify['orig'], $discount_ify['if'], $tpl->content);
			}else $tpl->content = str_replace($discount_ify['orig'], $discount_ify['else'], $tpl->content);
			
			
			if($status == 'review'){
				$request = $bd->read("	SELECT 	r.id, r.text, r.good, r.date, 
												(SELECT text FROM review_answer WHERE reviewId = r.id), 
												r.del, r.datedel, DATE_ADD(r.date, INTERVAL 1 DAY)
										FROM review r
										WHERE r.orderId = ".$id."
										LIMIT 1");
				if($bd->rows){
					$review_del = mysql_result($request,0,5);
					$review_text = strBaseOut(mysql_result($request,0,1));
					$review_good = mysql_result($request,0,2);
					$tpl->content = str_replace('{review_text}', $review_text, $tpl->content);
					$tpl->content = str_replace('{review_id}', mysql_result($request,0,0), $tpl->content);
					
					if(!$review_del){
						$tpl->switchy('REVIEW', 'REVIEW');
						$review_answer_text = strBaseOut(mysql_result($request,0,4));
						$review_change_text = str_replace('<br />', '', $review_text);
						$tpl->content = str_replace('{evaluation}', 		$review_good,								$tpl->content);
						$tpl->content = str_replace('{review_date}', 		mysql_result($request,0,3), 				$tpl->content);
						$tpl->content = str_replace('{review_change_text}', $review_change_text, 						$tpl->content);
						
						$review_bad = $tpl->ify('REVIEW_BAD');
						if(!$review_good)$tpl->content = str_replace($review_bad['orig'], $review_bad['if'], $tpl->content);
						else $tpl->content = str_replace($review_bad['orig'], $review_bad['else'], $tpl->content);
						
						$review_answer = $tpl->ify('REVIEW_ANSWER');
						if(!$review_answer_text && $review_good){
							$tpl->content = str_replace($review_answer['orig'], '', $tpl->content);
						}
						elseif($review_answer_text){
							$tpl->content = str_replace($review_answer['orig'], $review_answer['if'], $tpl->content);
							$tpl->content = str_replace('{review_answer_text}', $review_answer_text, $tpl->content);
						}else{
							$review_answer_must = mysql_result($request,0,7);
							$review_answer['else'] = str_replace('{review_answer_must}', $review_answer_must, $review_answer['else']);					
							$tpl->content = str_replace($review_answer['orig'], $review_answer['else'], $tpl->content);
						}
					}else{
						$tpl->switchy('REVIEW', 'REVIEWDELETE');
						$review_del_date = mysql_result($request,0,6);
						$review_delete_admin = $tpl->ify('REVIEW_DELETE_ADMIN');
						if($review_good || $review_del == 2)$tpl->content = str_replace($review_delete_admin['orig'], $review_delete_admin['else'], $tpl->content);
						if($review_del == 1){
							$review_del_who = 'вами';
							if(!$review_good)$tpl->content = str_replace($review_delete_admin['orig'], $review_delete_admin['if'], $tpl->content);					
						}else $review_del_who = 'администратором';
						$tpl->content = str_replace('{review_del_date}', $review_del_date, $tpl->content);
						$tpl->content = str_replace('{review_del_who}', $review_del_who, $tpl->content);
						
						
					}
				}else $tpl->switchy('REVIEW', 'REVIEW_NO');
				
			}else $tpl->switchy('REVIEW', 'FORM');
			
			$request = $bd->read("SELECT id FROM message WHERE order_id = ".$id." AND `status` = 'not_read' AND author != '".$this->email."' LIMIT 1");
			$message_icon_product = $bd->rows ? '<div style="display: inline-block;vertical-align: middle;width: 35px;"><img src="/stylisation/images/mailing.svg"></div>' : '';
			
			if($fio)$fio = '<span class="item-seller-info-inner-first">ФИО:</span>&nbsp;'.$fio.'';
			if($phone)$phone = '<span class="item-seller-info-inner-first">телефон:</span> '.$phone;
			if($skype)$skype = '<div style="display:inline-block;vertical-align:middle;">skype:</div>
								<div style="display:inline-block;vertical-align:middle;padding-left:4px;"><img align=absmiddle src="/stylisation/images/skype_small.png" width="16" height="16"></div>
								<div style="display:inline-block;vertical-align:middle;padding-left:4px;">'.$skype.'</div><br>';
			if($wm)$wm = '	<div style="display:inline-block;vertical-align:middle;">WMID:</div>
							<div style="display:inline-block;vertical-align:middle;padding-left:4px;"><img align=absmiddle src="/stylisation/images/wmid.gif" width="16" height="16"></div>
							<div style="display:inline-block;vertical-align:middle;padding-left:4px;"><a href="http://passport.webmoney.ru/asp/certview.asp?wmid='.$wm.'" target="_blank">'.$wm.'</a></div>';
			
			
			
			$mail = new mail();
			$unsubscribe = $mail->unsubscribe($this->email);
			if($unsubscribe['un']['orderUpdate']){
				$tpl->switchy('unsubscribe1', 'common');
				$tpl->set('{unsubscribeKey}', $unsubscribe['key']);
			}elseif($unsubscribeCustomer)$tpl->switchy('unsubscribe1', 'already');
			else $tpl->switchy('unsubscribe1', 'un');
			
			$tpl->switchy('CABINET', 'PRODUCT');
			
			$nopromocode = $tpl->ify('NOPROMOCODE');
			if($promocode_code){
				$tpl->content = str_replace($nopromocode['orig'], $nopromocode['else'], $tpl->content);
				$tpl->content = str_replace('{promocode_code}', 		$promocode_code, 		$tpl->content);
			}else $tpl->content = str_replace($nopromocode['orig'], $nopromocode['if'], $tpl->content);
			
			$tpl->content = str_replace('{info}', 					$info, 					$tpl->content);
			$tpl->content = str_replace('{product_id}', 			$product_id, 			$tpl->content);
			$tpl->content = str_replace('{seller_id}', 				$seller_id, 			$tpl->content);
			$tpl->content = str_replace('{descript}', 				$descript, 				$tpl->content);
			$tpl->content = str_replace('{message_icon_product}', 	$message_icon_product, 	$tpl->content);
			
			$tpl->content = str_replace('{login}', 					$login, 				$tpl->content);
			$tpl->content = str_replace('{fio}', 					$fio, 					$tpl->content);
			$tpl->content = str_replace('{email}',					$email, 				$tpl->content);
			$tpl->content = str_replace('{phone}', 					$phone, 				$tpl->content);
			$tpl->content = str_replace('{skype}', 					$skype, 				$tpl->content);
			$tpl->content = str_replace('{wm}',						$wm, 					$tpl->content);
			
			return $tpl->content;			
		}
		public function numMessages($userId){
			return $this->db->super_query_value("
				SELECT COUNT( m.id ) 
				FROM message m
				INNER JOIN  `order` o ON o.id = m.order_id
				INNER JOIN product p ON p.id = o.productId
				WHERE m.status =  'not_read'
				AND p.idUser = ".$userId."
				AND m.author != ".$userId
			);
		}
		public function numNewReviews($userId){
			global $bd;

			$request = $bd->read("	SELECT 	SQL_CALC_FOUND_ROWS re.id, re.idProduct, re.text, re.good,DATE_FORMAT(re.date,'%e %b %Y') AS dt,DATE_FORMAT(re.date,'%H:%i') AS tm, pr.name,
									(SELECT ra.text FROM review_answer ra WHERE ra.reviewId = re.id LIMIT 1),
									(SELECT ma.id FROM message ma WHERE ma.order_id = re.orderId AND ma.status = 'not_read' AND ma.author != ".$userId." LIMIT 1) as tr, re.orderId,(SELECT count(ma.id) FROM message ma WHERE ma.order_id = re.orderId AND ma.status = 'not_read' AND ma.author != ".$userId." )
							FROM `review` re
							inner join `product` pr
							on pr.id = re.idProduct
							WHERE pr.idUser = ".$userId."  AND re.del = 0 HAVING tr
							LIMIT 0, 25");

			$review = $bd->total_rows;

			return $review;
		}
		public function numNegMessages($userId){
			return $this->db->super_query_value("
				SELECT COUNT( m.id ) 
				FROM review m
				INNER JOIN  `order` o ON o.id = m.orderId
				INNER JOIN product p ON p.id = o.productId
				WHERE m.del = 0
				AND m.good = 0
				AND p.idUser = ".$userId
			);
		}
	}
?>
