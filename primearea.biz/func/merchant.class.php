<?php

	class merchant{
		private $ajaxdata;
		private $db;
		private $user;
		private $uvar;
		private $engine;
		private $tpl;
		private $config;
		private $mshop;
        public $mpayment;
		public $aggConfig = array();
		function __construct(){
			global $db,$data,$user,$construction,$CONFIG;
			$this->db = $db;
			$this->ajaxdata = $data;
			$this->user = $user;
			$this->uvar = $construction->uvar;
			$this->engine = $construction;
			$this->config = $CONFIG;
			bcscale(2);
		}
		public function ajax($method){

			switch($method){
				case 'createshop':return $this->createshop();
				case 'delshop':return $this->delshop();
				case 'savesettings':return $this->savesettings();
				case 'operationlist':return $this->operationlist();
				case 'shownotif':return $this->shownotif();
				case 'feeeditsave':return $this->feeeditsave();
				case 'payMethodsUserSave':return $this->payMethodsUserSave();
				case 'tochecking':return $this->tochecking();
				case 'showshop':return $this->showshop();
				case 'shopslist':return $this->shopslist();
				case 'acceptshop':return $this->acceptshop();
				default:return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		private function createshop(){
            return array('status' => 'error','message' => 'Создание новых магазинов закрыто');

			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			$secret = hash('sha256',time().$this->user->login);
			$this->db->query("INSERT INTO mshops (userId,secret)VALUES(".$this->user->id.",'".$secret."')");
			$mshopId = $this->db->insert_id();
			
			return array('status' => 'ok','message' => 'Магазин успешно создан','forwarding' => '/merchant/shop/'.$mshopId.'/settings/');
		}
		private function delshop(){
			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			
			$mshopId = (int)$this->ajaxdata['mshopId'];
			
			$mshop = $this->db->super_query("SELECT id,name FROM mshops WHERE id = ".$mshopId." AND userId = ".$this->user->id." LIMIT 1");
			if(!$mshop)return array('status' => 'error','message' => 'Магазин не найден');
			elseif($mshop['name'])return array('status' => 'error','message' => 'Невозможно удалить магазин');
			
			$this->db->query("DELETE FROM mshops WHERE id = ".$mshopId." LIMIT 1");
			
			return array('status' => 'ok','message' => 'Магазин успешно удален','forwarding' => '/merchant/');
		}
		private function savesettings(){
			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			
			$mshopId = $this->db->super_query_value("SELECT id FROM mshops WHERE id = ".(int)$this->ajaxdata['mshopId']." AND userId = ".$this->user->id." LIMIT 1");
			if(!$mshopId)return array('status' => 'error','message' => 'Магазин не найден');
			
			$errors = array();
			if(!$this->ajaxdata['name'])$errors[] = 'Введите название магазина';
			if(!$this->ajaxdata['url'])$errors[] = 'Введите адрес магазина';
			elseif(!preg_match('/^https?\:\/\/.*\./',$this->ajaxdata['url']))$errors[] = 'Неверный формат адреса магазина';
			if(!$this->ajaxdata['description'])$errors[] = 'Введите краткое описание';
			if(!$this->ajaxdata['fee'])$errors[] = 'Выберите кто оплачивает комиссию';
			if($this->ajaxdata['lifetime'] == '')$errors[] = 'Укажите время жизни платежа';
			elseif(!preg_match('/^\d{1,5}$/',$this->ajaxdata['lifetime']))$errors[] = 'Неверный формат времени жизни платежа';
			if($this->ajaxdata['secret'] == '')$errors[] = 'Укажите секретную фразу';
			elseif(!preg_match('/^[a-z0-9]{32}$/',$this->ajaxdata['secret']))$errors[] = 'Неверный формат секретной фразы(только цифры и латинские буквы, 32 символа)';
			if(!$this->ajaxdata['success'])$errors[] = 'Введите адрес перенаправления при успешной оплате';
			elseif(!preg_match('/^https?\:\/\/.*\./',$this->ajaxdata['success']))$errors[] = 'Неверный формат адреса перенаправления при успешной оплате';
			if(!$this->ajaxdata['fail'])$errors[] = 'Введите адрес перенаправления при ошибке оплаты';
			elseif(!preg_match('/^https?\:\/\/.*\./',$this->ajaxdata['fail']))$errors[] = 'Неверный формат адреса перенаправления при ошибке оплаты';
			if(!$this->ajaxdata['result'])$errors[] = 'Введите адрес отправки уведомлений';
			elseif(!preg_match('/^https?\:\/\/.*\./',$this->ajaxdata['result']))$errors[] = 'Неверный формат адреса отправки уведомлений';
			if(!$this->ajaxdata['sendmethod'])$errors[] = 'Выберите метод отправки данных';
			if(count($errors))return array('status' => 'error', 'messages' => $errors);
			
			$name = $this->db->safesql(trim($this->ajaxdata['name']),true);
			$url = $this->db->safesql(trim($this->ajaxdata['url']),true);
			$description = $this->db->safesql(trim($this->ajaxdata['description']),true);
			$fee = $this->ajaxdata['fee'] == 1 ? 0 : 1;
			$lifetime = (int)$this->ajaxdata['lifetime'];
			$secret = $this->db->safesql(trim($this->ajaxdata['secret']),true);
			$checksigncreate = $this->ajaxdata['checksigncreate'] ? 1 : 0;
			$success = $this->db->safesql(trim($this->ajaxdata['success']),true);
			$fail = $this->db->safesql(trim($this->ajaxdata['fail']),true);
			$result = $this->db->safesql(trim($this->ajaxdata['result']),true);
			$sendmethod = $this->ajaxdata['sendmethod'] == 1 ? 0 : 1;
			$overrideurl = $this->ajaxdata['overrideurl'] ? 1 : 0;
			$test = $this->ajaxdata['test'] ? 1 : 0;
			
			$this->db->query("
				UPDATE mshops
				SET
					name = '".$name."',
					url = '".$url."',
					description = '".$description."',
					fee = ".$fee.",
					lifetime = ".$lifetime.",
					secret = '".$secret."',
					checksigncreate = ".$checksigncreate.",
					success = '".$success."',
					fail = '".$fail."',
					result = '".$result."',
					sendmethod = ".$sendmethod.",
					overrideurl = ".$overrideurl.",
					test = ".$test."
				WHERE id = ".$mshopId."
				LIMIT 1
			");
			
			return array('status' => 'ok','message' => 'Данные успешно сохранены');
		}
		private function operationlist(){
			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			require_once 'func/tpl.class.php';
			require_once 'func/pagination.class.php';

			$elements_on_page = (int)$this->ajaxdata['elements_on_page'];
			$current_list = (int)$this->ajaxdata['current_list'];
			$elementLoadInPageStart = !$current_list ? 0 : $current_list * $elements_on_page;

            $id     = isset($this->ajaxdata['id']) ? (int)$this->ajaxdata['id'] : 0;
            $status = isset($this->ajaxdata['status']) ? $this->db->safesql($this->ajaxdata['status']) : 0;
            $payed  = isset($this->ajaxdata['payed']) ? (int)$this->ajaxdata['payed'] : 0;
            $date1  = isset($this->ajaxdata['date1'])
                      ? DateTime::createFromFormat('Y-m-d', $this->ajaxdata['date1']) : false;
            $date2  = isset($this->ajaxdata['date2'])
                      ? DateTime::createFromFormat('Y-m-d', $this->ajaxdata['date2']) : false;

			$where = '';

			//id
            if($id){
				$where .= ' (p.id = '.$id.' OR p.payno = '.$id.') AND ';
			}
			//статус
            if($status){
				$where .= ' p.status = "'.$status.'" AND ';
			}
            if($date1 !== false){
                $where .= ' p.ts > "'.$date1->format('Y-m-d').'" AND ';
            }
            if($date2 !== false){
                $where .= ' p.ts < "'.$date2->format('Y-m-d').'" AND ';
            }
			//метод оплаты
			if($payed){
				$where .= ' (p.viaId = '.$payed.') AND ';
			}

			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS
					p.id,p.payno,p.amount,p.status,p.tsr,p.ts,
					mn.id AS notifications, p.description, CURRENT_TIMESTAMP
				FROM mpayments p
				JOIN mshops s ON s.id = p.mshopId
				LEFT JOIN mnotify mn ON mn.mpaymentId = p.id
				WHERE ".$where." s.id = ".(int)$this->ajaxdata['mshopId']." AND s.userId = ".$this->user->id."
				GROUP BY p.id
				ORDER BY p.id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			$tpl = new tpl('merchant/operationlist');
			
			if(!$this->db->num_rows($request))$tpl->ify('nolist',1);
			else{
				$tpl->ify('nolist',2);
				$tpl->fory('list');
				$statuses = array('new'=>array('Новый'),'pending'=>array('Ожидание'),'success'=>array('Оплачен'),'fail'=>array('Не оплачен'),'cancel'=>array('Отменен'));
				while($row = $this->db->get_row($request)){

					if($row['status'] == 'success'){
						if($row['tsr'] && strtotime($row['CURRENT_TIMESTAMP']) > strtotime($row['tsr'])){
							$access = 'Зачислены';
						}else{
							$access = $row['tsr'];
						}
					}else{
						$access = '';
					}
					

					$tpl->foryCycle(array(
						'paymentId' => $row['id'],
						'id' => $row['payno'],
						'ts' => $row['description'],
						'amount' => $row['amount'],
						'status' => $statuses[$row['status']][0],
						'statusclass' => $row['status'],
						'tsr' => $access,
						'notifications' => array('notifications',$row['notifications'] ? 1 : 2),
						'date' => $row['ts'],
					));
				}
				$tpl->foryEnd();
			}
			
			if($this->ajaxdata['method'] != 'add' && $listCount){
				$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->ajaxdata['func']);
				$tpl->content .= '<tr id="table_pagination" class="table_pagination"><td colspan="8" style="background: #ffffff;">' . $pagination . '</td></tr>';
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function shownotif(){
			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			
			$paymentId = (int)$this->ajaxdata['paymentId'];
			
			if($this->user->role == 'admin' || $this->user->role == 'moder'){
				$mshop = $this->db->super_query("
					SELECT ms.id, mps.name
					FROM mpayments mp
					JOIN mshops ms ON ms.id = mp.mshopId
	                                LEFT JOIN mpaysyss mps ON mps.id = mp.viaId
					WHERE mp.id = ".$paymentId."
					LIMIT 1
				");
			}else{
				$mshop = $this->db->super_query("
					SELECT ms.id, mps.name
					FROM mpayments mp
					JOIN mshops ms ON ms.id = mp.mshopId
	                                LEFT JOIN mpaysyss mps ON mps.id = mp.viaId
					WHERE mp.id = ".$paymentId." AND ms.userId = ".$this->user->id."
					LIMIT 1
				");
			}
			
                        $mshopId = $mshop['id'];
			if(!$mshopId)return array('status' => 'error','message' => 'Нет доступа');
			
			$request = $this->db->query("SELECT senddata,method,result,code,tsr FROM mnotify WHERE mpaymentId = ".$paymentId." ORDER BY id LIMIT 5");
			
			require_once 'func/tpl.class.php';
			$tpl = new tpl('merchant/notifylist');
			
			if(!$this->db->num_rows($request))$tpl->ify('nolist',1);
			else{
				$tpl->ify('nolist',2);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$senddata = json_decode($row['senddata'],true);
					if(!$row['tsr'])$code = 'Ожидание';
					elseif($row['code'] == 0)$code = 'timeout';
					elseif($row['code'] == -1)$code = 'nocode';
					else $code = $row['code'];
					$tpl->foryCycle(array(
						'method' => $row['method'] ? 'GET' : 'POST',
						'senddata' => htmlentities(print_r($senddata,true),ENT_QUOTES,'UTF-8'),
						'tsr' => $row['tsr'],
						'code' => $code,
						'result' => $row['result'],
                        'paymentname' => $mshop['name'],
					));
				}
                                
				$tpl->foryEnd();
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function feeeditsave(){

			if(!in_array($this->user->role,array('admin')))return array('status' => 'error','message' => 'Нет доступа');

			$fee = array();
			$enabled = array();
			foreach($this->ajaxdata as $k => $v){
				$k = explode('_',$k);
				if($k[0] != 'sys')continue;
				if($k[2] == 'fee'){
					if(!preg_match('/\d{1,2}(\.\d{1,2})?/',$v))return array('status' => 'error','message' => 'Неверный формат комиссии');
					$fee[] = "WHEN id = ".(int)$k[1]." THEN ".$v;
				}elseif($k[2] == 'enabled'){
					$v = $v == 2 ? 1 : 0;
					$enabled[] = "WHEN id = ".(int)$k[1]." THEN ".$v;
				}
			}
			
			if(count($fee) && count($enabled))$this->db->query("UPDATE mpaysyss SET fee = (CASE ".implode(' ',$fee)." ELSE fee END),enabled = (CASE ".implode(' ',$enabled)." ELSE enabled END)");
			
			return array('status' => 'ok', 'message' => 'Сохранено');
		}
		private function payMethodsUserSave(){

			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');

			$enabled = '1';
			foreach($this->ajaxdata as $k => $v){

				$k = explode('_',$k);

				if($k[0] != 'sys')continue;
				if($k[2] == 'enabled' && $v && is_numeric($k[1])){

					$enabled .= ','.$k[1];
				}
			}

			if($enabled != ''){
				$enabled = $this->db->safesql($enabled);
				$this->db->query("UPDATE mshops SET available_paysyss_user = '".$enabled."' WHERE id = ".(int)$this->ajaxdata['shopId']." AND userId = ".$this->user->id);
			}
			return array('status' => 'ok', 'message' => 'Сохранено');
		}

		private function tochecking(){
			if(!in_array($this->user->role,array('user','moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			
			$mshop = $this->db->super_query("SELECT id,status FROM mshops WHERE id = ".(int)$this->ajaxdata['mshopId']." AND userId = ".$this->user->id." LIMIT 1");
			if(!$mshop)return array('status' => 'error','message' => 'Магазин не найден');
			
			if($mshop['status'] !== 'new')return array('status' => 'error','message' => 'Магазин уже был отправлен на проверку');
			
			$this->db->query("UPDATE mshops SET status = 'checking' WHERE id = ".$mshop['id']." LIMIT 1");
			
			return array('status' => 'ok');
		}
		private function showshop(){
			if(!in_array($this->user->role,array('admin', 'moder')))return array('status' => 'error','message' => 'Нет доступа');
			
			$mshop = $this->db->super_query("SELECT name,url,description,success,fail,result FROM mshops WHERE id = ".(int)$this->ajaxdata['mshopId']." LIMIT 1");
			if(!$mshop)return array('status' => 'error','message' => 'Магазин не найден');
			
			require_once 'func/tpl.class.php';
			$tpl = new tpl('merchant/showshop');
			
			$tpl->set(array(
				'name' => $mshop['name'],
				'url' => $mshop['url'],
				'description' => $mshop['description'],
				'success' => $mshop['success'],
				'fail' => $mshop['fail'],
				'result' => $mshop['result']
			));
			
			return array('status' => 'ok', 'content' => $tpl->content, 'shopName' => $mshop['name']);
		}
		private function acceptshop(){
			if(!in_array($this->user->role,array('admin', 'moder')))return array('status' => 'error','message' => 'Нет доступа');
			
			if($this->ajaxdata['result'] == '1'){
				$result = 'accepted';
			}else{
				$result = 'new';
			}

			$this->db->query("UPDATE mshops SET status = '".$result."' WHERE id = ".(int)$this->ajaxdata['mshopId']." LIMIT 1");
			
			return array('status' => 'ok');
		}
		private function shopslist(){
			if(!in_array($this->user->role,array('admin', 'moder')))return array('status' => 'error','message' => 'Нет доступа');
			
			require_once 'func/tpl.class.php';
			require_once 'func/pagination.class.php';

			$elements_on_page = (int)$this->ajaxdata['elements_on_page'];
			$current_list = (int)$this->ajaxdata['current_list'];

			$WHERE = ['1'];
			if (!empty($this->ajaxdata['login'])) {
			    $login = $this->db->safesql($this->ajaxdata['login']);
			    $WHERE[] = "AND u.login LIKE '%$login%'";
			}
			if (!empty($this->ajaxdata['status'])) {
			    $status  = $this->db->safesql($this->ajaxdata['status']);
			    $WHERE[] = "AND ms.status = '$status'";
			}

			$elementLoadInPageStart = !$current_list ? 0 : $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS
					ms.id,ms.name,ms.userId,ms.status,
						(CASE WHEN ms.status = 'checking' THEN 1 WHEN ms.status = 'new' THEN 2 WHEN ms.status = 'accepted' THEN 3 END) AS sort,
						u.login
				FROM mshops ms
				JOIN user u ON u.id = ms.userId
				WHERE ".implode(' ', $WHERE)."
				ORDER BY sort
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			$tpl = new tpl('merchant/shopslist');

			$tpl->fory('list');
			$statuses = array('new'=>'Настраивается','checking'=>'Проверяется','accepted'=>'Принят');
			while($row = $this->db->get_row($request)){
				$tpl->foryCycle(array(
					'mshopId' => $row['id'],
					'name' => $row['name'],
					'userId' => $row['userId'],
					'userLogin' => $row['login'],
					'status' => $statuses[$row['status']],
					'checking' => array('checking',$row['status'] == 'checking' ? 1 : 2)
				));
			}
			$tpl->foryEnd();
			
			if($this->ajaxdata['method'] != 'add' && $listCount){
				$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->ajaxdata['func']);
				$tpl->content .= '<tr id="table_pagination" class="table_pagination"><td colspan="5" style="background: #ffffff;">' . $pagination . '</td></tr>';
			}
			$listCount ? $tpl->content .= '' : $tpl->content .= '<tr id="table_pagination" class="table_pagination pagination-noresut-tr"><td colspan="5" class="pagination-noresut-td"><b>Ничего не найдено</b></td></tr>';;

			return array('status' => 'ok', 'content' => $tpl->content);
		}
		
		public function page(){

			if (in_array($this->uvar[1], ['pay', 'cronnotify', 'toreturn', 'paysys'])) {
                if($this->uvar[1] == 'pay')$result = $this->paypage();
				elseif($this->uvar[1] == 'cronnotify')$this->cronnotifypage();
				elseif($this->uvar[1] == 'toreturn')$result = $this->toreturnpage();
				elseif($this->uvar[1] == 'paysys')$this->paysyspage();
			} else {
                if(!in_array($this->user->role,array('user','moder','admin'))) {
                    $result = [
                        'content' => 'Необходимо авторизоваться',
                        'title' => 'Мерчант'
                    ];
                }elseif($this->uvar[1] == 'shop')$result = $this->shoppage();
				elseif($this->uvar[1] == 'admin')$result = $this->adminpage();
				elseif($this->uvar[1] == 'docs')$result = $this->docspage();
				elseif(!$this->uvar[1])$result = $this->panelpage();
                else $this->engine->errorpage();

			}

			if (isset($this->mshop['url'])) {
                $result['shopUrl'] = $this->mshop['url'];
			}

			return $result;
		}
		private function shoppage(){
			$tpl = new tpl('merchant/shop');
			
			$mshop = $this->db->super_query("
				SELECT id,name,url,description,fee,lifetime,secret,checksigncreate,success,fail,result,sendmethod,overrideurl,test, available_paysyss, available_paysyss_user
				FROM mshops 
				WHERE 
					id = ".(int)$this->uvar[2]." AND 
					userId = ".$this->user->id." 
				LIMIT 1
			 ");
			
			if(!$mshop)$this->engine->errorpage();
			
			$mshopName = $mshop['name'] ? $mshop['name'] : 'Не назван';
			$tpl->set('{mshopId}',$mshop['id']);
			$tpl->set('{mshopName}',$mshopName);
			
			if($this->uvar[3] == 'settings'){
				$tpl->switchy('page','settings');
				$tpl->set(array(
					'name' => $mshop['name'],
					'url' => $mshop['url'],
					'description' => $mshop['description'],
					'fee1' => $mshop['fee'] === '0' ? 'checked' : '',
					'fee2' => $mshop['fee'] === '1' ? 'checked' : '',
					'lifetime' => $mshop['lifetime'],
					'secret' => $mshop['secret'],
					'checksigncreate' => $mshop['checksigncreate'] ? 'checked' : '',
					'success' => $mshop['success'],
					'fail' => $mshop['fail'],
					'result' => $mshop['result'],
					'result' => $mshop['result'],
					'sendmethod1' => $mshop['sendmethod'] === '0' ? 'checked' : '',
					'sendmethod2' => $mshop['sendmethod'] === '1' ? 'checked' : '',
					'overrideurl' => $mshop['overrideurl'] ? 'checked' : '',
					'test' => $mshop['test'] ? 'checked' : ''
				));
			}elseif($this->uvar[3] == 'methods'){ //настройки методов оплат

				$available_paysyss_admin 	= explode(',', $mshop['available_paysyss']);
				$available_paysyss_admin 	= $available_paysyss_admin ?  array_flip($available_paysyss_admin) : '';

				$available_paysyss_user 	= explode(',', $mshop['available_paysyss_user']);
				$available_paysyss_user 	= $available_paysyss_user ?  array_flip($available_paysyss_user) : '';

				$allowedAllByUser  			= !isset($mshop['available_paysyss_user']);
				$allowedAllByAdmin 			= !isset($mshop['available_paysyss']);

				$this->db->query("
					SELECT id,name,fee,enabled
					FROM mpaysyss
					WHERE
						id != 1
						AND enabled = 1
						AND visible = 1
					ORDER BY position
				");
				$tpl->fory('list');
				while($row = $this->db->get_row()){

					//показывать все методы, поставив птички на тех методах, которые разрешил админитратор, если из базы приходит значение NULL, то по умолчанию разрешены все методы
					$checkedByAdmin = $allowedAllByAdmin || array_key_exists($row['id'], $available_paysyss_admin) ?  'checked' : '';
					// текущий метод разрешен для пользователя, если: ($allowedAllByUser приходит NULL) или (в общем массиве есть знаение текущего метода) и ( (в массиве, разрешенных админом, есть значение текущего метода) или (админ разрешил все методы) )
					$checkedByUser = $allowedAllByUser || array_key_exists($row['id'], $available_paysyss_user) && ( array_key_exists($row['id'], $available_paysyss_admin) || $allowedAllByAdmin) ? 'checked' : '';

					$methodDisabled =  !$checkedByAdmin ? 'disabled' : '';

					$tpl->foryCycle(array(
						'id' => $row['id'],
						'name' => $row['name'],
						'checked' => $checkedByAdmin,
						'checkedUser' => $checkedByUser,
						'methodDisabled' => $methodDisabled
					));
				}
				$tpl->foryEnd();

				// $this->db->query("SELECT id,available_paysyss_ FROM mpaysyss WHERE id != 1 ORDER BY id");


				$tpl->switchy('page','methods');
				$tpl->set(array(
					'shopId' => $mshop['id']
				));
			}else{
				$methodIds = $mshop['available_paysyss'];
				
				if($methodIds){
					$mshop = $this->db->query("
						SELECT id, name
						FROM mpaysyss 
						WHERE 
							id  IN(".$methodIds.")
							AND enabled = 1
							AND visible = 1
						ORDER BY position
					 ");
				}else{
					$mshop = $this->db->query("
						SELECT id, name
						FROM mpaysyss
						WHERE 
							enabled = 1
							AND visible = 1
						ORDER BY position
					 ");
				}
				
				
				$methods = '';
				while($row = $this->db->get_row()){
					$methods .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
				}


				$tpl->set('{availableMethods}',$methods);
				$tpl->switchy('page','operations');
				$this->engine->jsconfig['sp'] = 'operations';
			}
			
			$this->engine->jsconfig['mshopId'] = (int)$this->uvar[2];
			
			return(array(
				'content' => $tpl->content,
				'title' => 'Мерчант - '.$mshopName
			));
		}
		private function panelpage(){
			$tpl = new tpl('merchant/panel');
			
			$request = $this->db->query("SELECT id,name,status FROM mshops WHERE userId = ".$this->user->id." ORDER BY id DESC");
			if($this->db->num_rows($request)){
				$tpl->ify('shops',1);
				$tpl->fory('shops');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'mshopId' => $row['id'],
						'mshopName' => $row['name'] ? $row['name'] : 'Не назван',
						'mshopDel' => array('mshopDel',$row['name'] ? 2 : 1),
						'mshopToChecking' => array('mshopToChecking',$row['status'] == 'new' && $row['name'] ? 1 : 2)
					));
				}
				$tpl->foryEnd();
			}else $tpl->ify('shops',2);
			
			return(array(
				'content' => $tpl->content,
				'title' => 'Мерчант'
			));
		}
		private function paypage(){
			$this->tpl = new tpl('merchant/pay');

			if(!$_POST){

				parse_str(file_get_contents('php://input'), $post);
				$headers = GetAllHeaders();

				require_once $_SERVER['DOCUMENT_ROOT'].'/func/logs.class.php';
				$logs = new logs();
				$data = [
					'post'    => $post,
					'request' => $_REQUEST,
					'server'  => $_SERVER,
					'headers' => $headers
				];
				$logs->add('merchant_error',NULL,$this->db->safesql(json_encode($data)));

				return $this->paypageerror('Отсутствуют POST данные');
			}

			if($this->uvar[2] == 'continue'){

				if(!$this->setmpayment(array('id'=>(int)$_POST['mpaymentId'])))return $this->paypageerror('Платеж не найден');
				if(!$this->setmshop($this->mpayment['mshopId']))return $this->paypageerror('Магазин не найден');
				if($this->mpayment['hash'] !== $_COOKIE['mpayment_'.$this->mpayment['id']])return $this->paypageerror('Платеж недоступен');
				if(in_array($this->mpayment['status'],array('success','pending')))return $this->toreturn('success');
				if(in_array($this->mpayment['status'], ['fail','cancel']))return $this->toreturn('fail');
				if(!$this->checklifetime())return $this->paypageerror('Время платежа истекло');
				if(!$_POST['via'])return $this->paypageerror('Платежное направление не было выбрано');

				$via = $this->db->safesql($_POST['via']);
				$via_code = false;
				if(explode(' ', $via)[0] == 'skrill'){
					$via_code = explode(' ', $via)[1];
					$via = explode(' ', $via)[0];
				}

				if(!$paysys = $this->paysyss($this->mpayment['mshopId'],$via,$this->mpayment['amount'], false, $via_code))return $this->paypageerror('Платежное направление недоступно');
				
				$this->topay($paysys);
				$this->tpl->ify('head',1);
                $this->tpl->set('{headTitle}','');
			}elseif($this->uvar[2] == 'cancel'){

                if(isset($_POST['mpaymentId'])){
                    $_id = (int)$_POST['mpaymentId'];
                    $this->db->query("UPDATE mpayments SET status = 'cancel' WHERE id = $_id");
                }
	 			
	 			$this->setmpayment(array('id'=>(int)$_POST['mpaymentId']));
	 			$this->setmshop($this->mpayment['mshopId']);
	 			
	 			$url = $this->mshop['overrideurl'] && $this->mpayment['fail'] ? $this->mpayment['fail'] : $this->mshop['fail'];
	 			if($url){
					header("Location: ".$url);
					die();
	 			}
	 			
	            return $this->paypageerror('Отмена платежа. Счет будет закрыт.');
			}else{

				if(!$_POST['shopid'])return $this->paypageerror('Отсутствует параметр shopid');
				if(!$this->setmshop((int)$_POST['shopid']))return $this->paypageerror('Магазин с таким shopid не найден');
				
				if(!$this->mshop['name'])return $this->paypageerror('Магазин не настроен');
				
				if(!$_POST['payno'])return $this->paypageerror('Отсутствует параметр payno');
				if(!preg_match('/^\d{1,10}$/',$_POST['payno']))return $this->paypageerror('Неверный формат параметра payno');
				$payno = (int)$_POST['payno'];
				if($payno > 2147483648)return $this->paypageerror('Неверный формат параметра payno');
				
				if(!$_POST['amount'])return $this->paypageerror('Отсутствует параметр amount');
				if(!preg_match('/^\d{1,10}(\.\d{1,2})?$/',$_POST['amount']))return $this->paypageerror('Неверный формат параметра amount');
				$amount = $_POST['amount'];
				if(bccomp($amount,2147483648) > 0 || bccomp($amount,0, '3') == 0)return $this->paypageerror('Неверный формат параметра amount');
				
				if(!$_POST['description'])return $this->paypageerror('Отсутствует параметр description');
				$description = htmlentities(mb_substr($_POST['description'],0,128,'UTF-8'),ENT_QUOTES,'UTF-8');
				
				if($this->mshop['checksigncreate']){
					if(!$_POST['sign'])return $this->paypageerror('Отсутствует параметр sign');
					$sign = $_POST['sign'];
					unset($_POST['sign']);
					ksort($_POST,SORT_STRING);
					$compsign = hash('sha256',implode(':',$_POST).':'.$this->mshop['secret']);
					if($sign !== $compsign)return $this->paypageerror('Неверный параметр sign');
				}
				
				if(!$paysyss = $this->paysyss($this->mshop['id'],false,$amount))return $this->paypageerror('Для данного магазина нет ни одного платежного направления');
				
				if($this->mshop['overrideurl']){
					if($_POST['success']){
						if(!preg_match('/^https?\:\/\/.*\./',$_POST['success']))return $this->paypageerror('Неверный формат адреса перенаправления при успешной оплате');
						$success = $this->db->safesql($_POST['success'],true);
					}
					if($_POST['fail']){
						if(!preg_match('/^https?\:\/\/.*\./',$_POST['fail']))return $this->paypageerror('Неверный формат адреса перенаправления при ошибке оплаты');
						$fail = $this->db->safesql($_POST['fail'],true);
					}
				}
				$success = $success ? "'".$success."'" : 'NULL';
				$fail = $fail ? "'".$fail."'" : 'NULL';
				
				if($this->setmpayment(array('mshopId'=>$this->mshop['id'],'payno'=>$payno))){
					if($this->mpayment['hash'] !== $_COOKIE['mpayment_'.$this->mpayment['id']])return $this->paypageerror('Платеж недоступен, повторите попытку');

					if($this->mpayment['sign'] !== $sign)return $this->paypageerror('Переопределение платежа невозможно');
						
					if(in_array($this->mpayment['status'],array('success','pending')))return $this->toreturn('success');
					
					if($this->mpayment['status'] == 'fail')return $this->toreturn('fail');
					if(!$this->checklifetime())return $this->paypageerror('Время платежа истекло');
					$mpaymentId = $this->mpayment['id'];

				}else{
					$uvs = array();
					foreach($_POST as $k => $v){
						$kk = explode('_',$k);
						if(count($kk) > 1 && $kk[0] == 'uv')$uvs[$k] = $v;
					}

					$uvs = $this->db->safesql(json_encode($uvs));

					$code = isset($_POST['via']) && $_POST['via'] == 'skrill' ? $this->db->safesql($_POST['code']) : false;
					if($_POST['via'] && $paysys = $this->paysyss($this->mshop['id'],$this->db->safesql($_POST['via']),$amount, false, $code))
						$viaId = $paysys['id'];
					else $viaId = 'NULL';
					$hash = hash('md5',time().$this->mshop['id'].$payno.$amount);
					$this->db->query("INSERT INTO mpayments (mshopId,payno,amount,description,viaId,hash,uvs,sign,success,fail)VALUES(".$this->mshop['id'].",".$payno.",".$amount.",'".$this->db->safesql($description)."',".$viaId.",'".$hash."','".$uvs."','".$compsign."',".$success.",".$fail.")");
					$mpaymentId = $this->db->insert_id();
					SetCookie('mpayment_'.$mpaymentId, $hash, (60 * 60 * 7 * 24 + time()), '/', $this->config['site_domen'], $this->config['cookie_SSL'], true);
				}

				if($this->mpayment && $this->mpayment['status'] == 'cancel'){
					return $this->paypageerror('Платеж отменен.');
				}

				if($paysys){
					$this->setmpayment(array('id'=>$mpaymentId));
					$this->topay($paysys);
                    $this->tpl->ify('head',1);
                    $this->tpl->set('{headTitle}','');
				}else{
					$this->tpl->switchy('page','page');
					
					$methods = $this->db->super_query("
						SELECT available_paysyss, available_paysyss_user
						FROM mshops
						WHERE id = ".$this->mshop['id']."
					");

					if($methods['available_paysyss'] === NULL){
						$methods['available_paysyss'] = $this->db->query("
							SELECT id
							FROM mpaysyss
							WHERE enabled = 1
						");
						while ($row =$this->db->get_row($methods['available_paysyss']) ) {
							$temp .= $row['id'].',';
						}
						$methods['available_paysyss'] = $temp;
					}

					$available_methods = explode(',', $methods['available_paysyss']);
					$available_methods_user = explode(',', $methods['available_paysyss_user']);
					$disabledByUser = array_diff($available_methods, $available_methods_user);

					if($methods['available_paysyss_user'] && $disabledByUser){

						foreach ($disabledByUser as $key => $value) {
							unset($available_methods[$key]);
						}

					}

					//для остновного контента
					$this->tpl->fory('paysyss');
				
					if($available_methods[0]){
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
									'method' => in_array($v['id'], $available_methods) ? '<a data-amount="'.$v['amount'].'" data-fee="'.$v['fee'].'" data-feeamount="'.$v['feeAmount'].'" data-via="'.$via.'"><div class="'.$via.'"></div></a>': '',
									'paysysName' => $v['name'],
								));
							}
					}else{
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
								'method' => '<a data-amount="'.$v['amount'].'" data-fee="'.$v['fee'].'" data-feeamount="'.$v['feeAmount'].'" data-via="'.$via.'"><div class="'.$via.'"></div></a>',
								'paysysName' => $v['name'],
							));
						}
					}
					$this->tpl->foryEnd();

					//для дропдауна
					$this->tpl->fory('option_paysyss');
					if($available_methods[0]){
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
								'dropdown'  => in_array($v['id'], $available_methods) ? '<option data-option_amount="'.$v['amount'].'" data-option_fee="'.$v['fee'].'" data-option_feeamount="'.$v['feeAmount'].'" data-option_via="'.$via.'">'.$v['name'].'</option>':'',
							));
						}
					}else{
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
								'dropdown'  => '<option data-option_amount="'.$v['amount'].'" data-option_fee="'.$v['fee'].'" data-option_feeamount="'.$v['feeAmount'].'" data-option_via="'.$via.'">'.$v['name'].'</option>',
							));
						}
					}
					$this->tpl->foryEnd();

					//для экранов маленького размера
					$this->tpl->fory('paysyss_xs');
					if($available_methods[0]){
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
								'method_xs' => in_array($v['id'], $available_methods) ? '<a data-amount="'.$v['amount'].'" data-fee="'.$v['fee'].'" data-feeamount="'.$v['feeAmount'].'" data-via="'.$via.'"><div class="'.$via.'xs center-block"></div></a>' : '',
								'paysysName_xs' => $v['name'],
							));
						}
					}else{
						foreach($paysyss AS $v){
							$via = $v['via'] == 'skrill' ? 'skrill '.$v['code'] : $v['via'];
							$this->tpl->foryCycle(array(
								'method_xs' => '<a data-amount="'.$v['amount'].'" data-fee="'.$v['fee'].'" data-feeamount="'.$v['feeAmount'].'" data-via="'.$via.'"><div class="'.$via.'xs center-block"></div></a>',
								'paysysName_xs' => $v['name'],
							));
						}
					}
					
					$this->tpl->foryEnd();

					$this->tpl->set(array(
						'mshopName' => $this->mshop['name'],
						'description' => $description,
						'via' => $paysyss[0]['via'],
						'mpaymentId' => $mpaymentId,
						'price' => $amount
					));
					$this->engine->jsconfig['sp'] = 'pay';
					$this->engine->jsconfig['amount'] = $amount;
					$this->engine->jsconfig['mshopFee'] = (int)$this->mshop['fee'];

					$this->tpl->ify('head',2);
				}
			}
			return(array(
				'content' => $this->tpl->content,
				'title' => 'Оплата'
			));
		}
		private function cronnotifypage(){
			$this->sendnotify();
			exit('ok');
		}
		private function toreturnpage(){
			$this->tpl = new tpl('merchant/pay');
			
			if(!$this->setmpayment(array('id'=>$this->uvar[2])))return $this->paypageerror('Платеж не найден');
			if(!$this->setmshop($this->mpayment['mshopId']))return $this->paypageerror('Магазин не найден');
			if($this->mpayment['hash'] !== $_COOKIE['mpayment_'.$this->mpayment['id']])return $this->paypageerror('Платеж недоступен');

			if($this->uvar[3] == 'fail')return $this->toreturn('fail');
          
			return $this->toreturn('success');
		}
		private function paysyspage(){
			header("Content-type: application/json");
			if(!$this->uvar[2])exit(json_encode(array('status' => 'error','message' => 'shopId is missing')));
			if(!$this->setmshop((int)$this->uvar[2]))exit(json_encode(array('status' => 'error','message' => 'Shop not found')));
			exit(json_encode(array(
				'status' => 'ok',
				'list' => $this->paysyss($this->mshop['id'],false,false,true)
			)));
		}
		private function adminpage(){

			if(!in_array($this->user->role,array('admin', 'moder')))return(array(
				'content' => 'Доступ запрещен',
				'title' => 'Мерчант'
			));
			
			if(isset($this->uvar[2]) && $this->uvar[2] && is_numeric($this->uvar[2])){

				if(in_array($this->user->role,array('moder'))){

					return(array(
						'content' => 'Доступ запрещен',
						'title' => 'Мерчант'
					));

				}else{

					$shopId = $this->uvar[2];
					$error = false;

					if(isset($_POST['reserv_time']) ){
						if($_POST['reserv_time'] && is_numeric($_POST['reserv_time'])){
							$this->db->query("
								UPDATE mshops
								SET
									reservation = '".$_POST['reserv_time']."'
								WHERE id = ".$shopId."
								LIMIT 1
							");
						}elseif($_POST['reserv_time'] == ''){
							$this->db->query("
								UPDATE mshops
								SET
									reservation = NULL
								WHERE id = ".$shopId."
								LIMIT 1
							");
						}else{
							$error = 'Введите верное значение времени удержании';
						}	
					}

					$sysarray = array();
					if(isset($_POST)){
						foreach ($_POST as $key => $value) {
							if($key != 'reserv_time' && $key != 'paysyss_override' && is_numeric($key) ){
								$sysarray[] = $key;
							}
						}
						$sysarray = implode(',', $sysarray);
						
					}

					$paysyss_override = $_POST['paysyss_override'] ? 1 : 0;

					$tpl = new tpl('merchant/editshop');
					$title = 'Мерчант';

					$this->db->query("SELECT id,name,fee FROM mpaysyss ORDER BY id");

					$paysyss_values = array();
					while($row = $this->db->get_row()){
							
						if(isset($_POST['sys_'.$row['id'].'_fee'])){
						
							$v = $_POST['sys_'.$row['id'].'_fee'];
							
							if(is_numeric($v) && preg_match('/\d{1,2}(\.\d{1,2})?/',$v)){

								$paysyss_values[$row['id']] = $v;
								
							}else{
								$error = 'Неверный формат комиссии';
							}
						}
					}
					

					if($paysyss_values && !$error){
						$paysyss_values = json_encode($paysyss_values);

						$this->db->query("
							UPDATE mshops
							SET
								paysyss_fee = '".$paysyss_values."', available_paysyss = '".$sysarray."', paysyss_override = ".$paysyss_override."
							WHERE id = ".$shopId."
							LIMIT 1
						");
					}
					
					$paysyss_fee_values = $this->db->super_query("
						SELECT paysyss_fee
						FROM mshops
						WHERE id = ".$shopId."
					");

					if($paysyss_fee_values['paysyss_fee']){
						$paysyss_fee_values = json_decode(html_entity_decode($paysyss_fee_values['paysyss_fee']), true);
					}
					
					$shop = $this->db->super_query("
						SELECT *
						FROM mshops
						WHERE id = ".$shopId."
					");

					$available_methods = $this->db->super_query("
						SELECT available_paysyss
						FROM mshops
						WHERE id = ".$shopId."
					");
					$available_methods = explode(',', $available_methods['available_paysyss']);
					
					$this->db->query("SELECT id,name,fee FROM mpaysyss ORDER BY id");
					$tpl->fory('list');
						while($row = $this->db->get_row()){
							$tpl->foryCycle(array(
								'id'      => $row['id'],
								'paysyss' => $row['name'],
								'isChecked'   => in_array($row['id'], $available_methods) ? "checked": '',
								'fee'     => $paysyss_fee_values[$row['id']] ?  $paysyss_fee_values[$row['id']] : $row['fee'],
							));
						}
					$tpl->foryEnd();

					$tpl->set(array(
						'shopName' => $shop['description'],
						'reservation' => $shop['reservation'],
						'paysyss_override' => $shop['paysyss_override'] ? 'checked' : '',
						'error' => $error
					));

					return(array(
						'content' => $tpl->content,
						'title' => $title
					));

				}
				
			}else{

				$tpl = new tpl('merchant/admin');
				$title = 'Мерчант';

				$moder = $tpl->ify('moder');
				if($this->user->role == 'moder'){
					$tpl->content = str_replace($moder['orig'], $moder['else'], $tpl->content);
				}else{
					$tpl->content = str_replace($moder['orig'], $moder['if'], $tpl->content);
				}

				if($this->uvar[2] == 'feeedit'){

					if(in_array($this->user->role,array('moder'))){
						return(array(
							'content' => 'Доступ запрещен',
							'title' => 'Мерчант'
						));
					}else{
						$tpl->switchy('page','feeedit');
						$title = 'Мерчант - редактирование комиссий';

						$this->db->query("SELECT id,name,fee,enabled FROM mpaysyss WHERE id != 1 ORDER BY id");
						
						$tpl->fory('list');
						while($row = $this->db->get_row()){
							$tpl->foryCycle(array(
								'id' => $row['id'],
								'name' => $row['name'],
								'fee' => $row['fee'],
								'enabled' => $row['enabled'] ? 'checked' : ''
							));
						}
						$tpl->foryEnd();
					}
					
				}elseif($this->uvar[2] == 'statistic'){
					if(in_array($this->user->role,array('moder'))){
						return(array(
							'content' => 'Доступ запрещен',
							'title' => 'Мерчант'
						));
					}else{
						$title = 'Мерчант - Статистика';
						$tpl->switchy('page','statistic');
					}
					
				}elseif($this->uvar[2] == 'orders'){

					if($this->user->role === 'moder' && !$this->user->checkModerRight('merchant_orders')){
						return(array(
							'content' => 'Доступ запрещен',
							'title' => 'Мерчант'
						));
					}else{
						$this->db->query("SELECT id,name FROM mpaysyss WHERE enabled = 1 ORDER BY id");
                        $methods = '';
						while($row = $this->db->get_row()){
							$methods .= "<option value='".$row['id']."'>".$row['name']."</option>";
						}

						$tpl->set('{availableMethods}',$methods);

						$title = 'Мерчант - Заказы';
						$this->engine->jsconfig['curr_page'] = 'orders';
						$this->engine->jsconfig['sp'] = 'orders';
						$tpl->switchy('page','orders');
					}
					
				}else{
					if($this->user->role === 'moder' && !$this->user->checkModerRight('merchant_admin')){
						return(array(
							'content' => 'Доступ запрещен',
							'title' => 'Мерчант'
						));
					}else{
						$title = 'Мерчант - Магазины';
						$tpl->switchy('page','shops');
						$this->engine->jsconfig['sp'] = 'adminshops';
					}
					
				}
				return(array(
					'content' => $tpl->content,
					'title' => $title
				));	
			}			
		}
		private function docspage(){
			$this->tpl = new tpl('merchant/docs');
			
			$this->tpl->set(array(
				'domain' => $this->config['site_domen'],
				'siteaddress' => $this->config['site_address']
			));
			
			return(array(
				'content' => $this->tpl->content,
				'title' => 'Merchant - Документация'
			));
		}
		
		private function topay($paysys){
			global $siteAddr;

            require_once $_SERVER['DOCUMENT_ROOT'].'/func/PaymentAccountFetcher.php';
            $paymentAccountFetcher = new PaymentAccountFetcher($this->db);

			$profit = $this->mshop['fee'] ? bcsub($this->mpayment['amount'],$paysys['add_fee']) : $this->mpayment['amount'];
			$autoSubmitForm = true;

			$this->db->query("
				UPDATE mpayments
				SET
					viaId = '".$paysys['id']."',
					status = 'pending',
					mshopFee = ".$this->mshop['fee'].",
					fee = ".$paysys['fee'].",
					feeAmount = ".$paysys['add_fee'].",
					amountPay = ".$paysys['amount'].",
					amountProfit = ".$profit.",
					curr = ".$paysys['curr']."
				WHERE id = ".$this->mpayment['id']."
				LIMIT 1
			");
			$this->mpayment['amountProfit'] = $profit;
			$this->mpayment['amountPay'] = $paysys['amount'];
			if($paysys['via'] == 'test')$this->paytest();
			$success = $this->mshop['overrideurl'] && $this->mpayment['success'] ? $this->mpayment['success'] : $this->mshop['success'];
            if($paysys['via'] == 'liqpay'){
                    $this->tpl->switchy('page','liqpay');
                    $data = $this->getDataLiqpay($paysys);
                    $this->tpl->set(array(
				'data' => $data,
				'signature' => $this->getSignatureLiqpay($paysys,$data),
			));
		            }

            if($paysys['aggname'] == 'primepayer'){
                $this->tpl->switchy('page','primepayer');
                $description = htmlspecialchars_decode($this->mpayment['description'], ENT_QUOTES);
                $primePayerData = [
                    'shop' => $this->config['primePayerMerchant']['id'],
                    'payment' => $this->mpayment['id'],
                    'amount' => $this->mpayment['amountPay'],
                    'description' => $description,
                    'currency' => 3,
                    'via' => $paysys['code'],
                    'fail' => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/fail/',
                    'success' => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/success/',
                ];
                ksort($primePayerData, SORT_STRING);
                $primePayerSign = hash('sha256', implode(':', $primePayerData).':'.$this->config['primePayerMerchant']['key']);
                $primePayerData['description'] = htmlspecialchars($primePayerData['description'], ENT_QUOTES);

                $this->tpl->set([
                    'primePayerShopId' => $primePayerData['shop'],
                    'primePayerPayment' => $primePayerData['payment'],
                    'primePayerAmount' => $primePayerData['amount'],
                    'primePayerDescription' => $primePayerData['description'],
                    'primePayerCurrency' => $primePayerData['currency'],
                    'primePayerVia' => $primePayerData['via'],
                    'primePayerFail' => $primePayerData['fail'],
                    'primePayerSuccess' => $primePayerData['success'],
                    'primePayerSign' => $primePayerSign,
                ]);
            }elseif($paysys['via'] == 'wmz' || $paysys['via'] == 'wmr' || $paysys['via'] == 'wmu' || $paysys['via'] == 'wme' ){
                $this->tpl->switchy('page','webmoney');
                
            $this->tpl->set(array(
				'LMI_PAYMENT_AMOUNT' => $paysys['amount'], 
                'LMI_PAYMENT_NO' => $this->mpayment['id'],
                'LMI_PAYMENT_DESC_BASE64' => base64_encode($this->mpayment['description']),
                'LMI_PAYEE_PURSE' => $paysys['aggconfig']['purse'],             
			));
            }elseif($paysys['via'] == 'yandexmoney'){
                $this->tpl->switchy('page','yandexmoney');
                try {
                    $paymentAccount = $paymentAccountFetcher->fetchOne(
                        PaymentAccountFetcher::PAYMENT_SYSTEM_YANDEX_ID,
                        PaymentAccountFetcher::ENABLED_FOR_MERCHANT
                    );
                } catch (Exception $e) {
                    close($e->getMessage());
                }
                //@TODO catch exception
                $receiver = $paymentAccount['config']['purse'];
                $this->tpl->set(array(
                    'receiver' => $receiver,
                    'formcomment' => $this->mpayment['description'],
                    'short-dest' => $this->mpayment['description'],
                    'targets' => $this->mpayment['description']. ' [#'.$this->mpayment['id'].', payno: '.$this->mpayment['payno'].']' ,
                    'sum' => $paysys['amount'],
                    'label' => 'merchant_'.$this->mpayment['id'],
                    'successURL' => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/success/'
                ));
            }elseif($paysys['via'] == 'cardpay'){
                require_once(dirname(__FILE__).'/lib/external_payment.php');
                require_once(dirname(__FILE__).'/lib/api.php');

                try {
                    $paymentAccount = $paymentAccountFetcher->fetchOne(
                        PaymentAccountFetcher::PAYMENT_SYSTEM_YANDEX_CARD_ID,
                        PaymentAccountFetcher::ENABLED_FOR_MERCHANT
                    );
                } catch (Exception $e) {
                    close($e->getMessage());
                }
                //@TODO catch exception
                $external_payment = new ExternalPayment($paymentAccount['config']['instance_id']);

                $payment_options = array(
                    'pattern_id' => 'p2p',
                    'to' => $paymentAccount['config']['purse'],
                    'amount_due' =>$paysys['amount'],
                    'paymentType' =>'AC',
                    'message' => $this->mpayment['description'] . ' [#'.$this->mpayment['id'].', payno: '.$this->mpayment['payno'].']'
                );

                $response = $external_payment->request($payment_options);

                if($response->status == "success") {
                    $request_id = $response->request_id;
                }
                else {
                    throw new Exception("Что-то пошло не так.", 1);
                }

                $process_options = array(
                    "request_id" => $request_id,
                    'ext_auth_success_uri' => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/success/',
                    'ext_auth_fail_uri' => $this->config['site_address'].'payment-error/',
                    'paymentType' =>'AC'
                );
                $result = $external_payment->process($process_options);

                $this->db->query("
					UPDATE mpayments
					SET yandex_cart_context_id = '".$result->acs_params->cps_context_id."'
					WHERE id = ".$this->mpayment['id']."
					LIMIT 1
				");

                $this->tpl->switchy('page','cardpay');
                $this->tpl->set(array(
                    'action' =>$result->acs_uri,
                    'cps_context_id' =>$result->acs_params->cps_context_id,
                    'paymentType' => $result->acs_params->paymentType,
                ));
            }
            if(in_array($paysys['id'], [8, 9, 10, 11, 12, 15, 16, 17, 18])){
                $this->tpl->switchy('page','interkassa');

                if($paysys['via'] == 'bitcoin'){
                	$c = 'EUR';
                }else{
                	$c = 'RUB';
                }

                $data = array(
                    'ik_co_id' => $paysys['aggconfig']['merchant'],
					'ik_am' => $paysys['amount'], 
                    'ik_pm_no' => $this->mpayment['id'],
                    'ik_desc' => htmlspecialchars_decode($this->mpayment['description'], ENT_QUOTES),
                    'ik_pw_via' => $paysys['aggconfig']['ik_pw_via'],
                    'ik_cur' => $c,
                    'ik_act' => 'process',
                );
                $data['ik_sign'] = $this->generateSignInterkassa($data, $paysys['aggconfig']['privatekey']);
                $data['ik_desc'] = htmlspecialchars($data['ik_desc'], ENT_QUOTES);
                $this->tpl->set($data);
            }
            if($paysys['via'] == 'paypal'){

                $this->tpl->switchy('page','paypal');
                $this->tpl->set(array(
                    'business' =>$this->config['paypal']['receiver'],
                    'receiver_email' =>$this->config['paypal']['receiver'],
                    'item_name' => $this->mpayment['description'],
                    'item_number'=> $this->mpayment['id'],
                    'amount' => $this->mpayment['amountPay'],
                    'return' => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/success/',
                    'cancel_return' =>  $this->config['site_address'].'payment-error/',
                    'notify_url' => $this->config['site_address'].'merchant/toreturn/paypal/success/',
                ));
            }
            if($paysys['via'] == 'skrill'){

			    $url  = $this->config['skrill']['wallet']['url'];
			    $data = [
			        'transaction_id'        => $this->mpayment['id'],
			        'return_url'            => $this->config['site_address'].'merchant/toreturn/'.$this->mpayment['id'].'/success/',
			        'status_url'            => $this->config['site_address'].'merchant/toreturn/skrill/success',
			        'amount'                => $this->mpayment['amountPay'],
			        'pay_to_email'          => $this->config['skrill']['wallet']['email'],
			        'recipient_description' => $this->mpayment['description']. ' [#'.$this->mpayment['id'].', payno: '.$this->mpayment['payno'].']',
			        'detail1_description'   => 'Покупка:',
			        'detail1_text'          => $this->mpayment['description'],
			        'language'              => 'EN',
			        'logo_url'              => $this->config['siteAddr'].'style/img/logo.png',
			        'currency'              => $this->config['skrill']['wallet']['currency'],
			        'amount2'               => $this->mpayment['amountPay'],
			        'amount2_description'   => 'Цена: ',
			        'payment_methods'       => $paysys['code']
			    ];

			    require_once $_SERVER['DOCUMENT_ROOT'].'/func/logs.class.php';
			    require_once $_SERVER['DOCUMENT_ROOT'].'/func/SkrillProcessErrorException.class.php';

			    $logs = new logs();

			    try {
			    	$request = curlPost($data, $url);

			    	$matches = [];
				    preg_match( '/([0-9a-f]{32})/im', $request, $matches);

				    if(sizeof($matches) != 0){

				    	$this->tpl->switchy('page','skrill');
		                $this->tpl->set(array(
		                    'url' => $url,
		                    'sid' => $matches[1],
		                ));

				    }else{

	        			$logs->add('skrill_process_error_merchant',(int)$this->mpayment['id'], $this->db->safesql($request));
				    	close('Ошибка платежной системы');
				    }

			    } catch (SkrillProcessErrorException $e) {

        			$logs->add('skrill_process_error_merchant_curl',(int)$this->mpayment['id'], $this->db->safesql($e->getMessage()));
			    	close('Ошибка запроса. Повторите через несколько минут');
			    }

            }
            if(in_array($paysys['id'], [41])){
                $autoSubmitForm = false;
                $amountParts = $this->amountParts($this->mpayment['amountPay']);
                $this->tpl->switchy('page','qiwiown');

                try {
                    $paymentAccount = $paymentAccountFetcher->fetchOne(
                        PaymentAccountFetcher::PAYMENT_SYSTEM_QIWI_ID,
                        PaymentAccountFetcher::ENABLED_FOR_MERCHANT
                    );
                } catch (Exception $e) {
                    close($e->getMessage());
                }

                $qiwiUri =
                    "https://qiwi.com/payment/form/99"
                    ."?amountFraction={$amountParts[1]}"
                    ."&extra%5B%27comment%27%5D=%D0%9F%D0%B5%D1%80%D0%B5%D0%B2%D0%BE%D0%B4+%28ID%3AP{$this->mpayment['id']}%29"
                    ."&extra%5B%27account%27%5D=%2B{$paymentAccount['config']['account']}"
                    ."&amountInteger={$amountParts[0]}"
                    ."&currency=643";

                $this->tpl->set([
                    'qiwiUri' => $qiwiUri,
					'headTitle' => '<div class="transition_to_paymentno" style="height: 80px;"><div class="payment-container"><div class="ket-title-border"><div class="payment-costqiwi"><p>В ожидании</p></div></div></div></div>',
                ]);
            }

            if (null !== $paymentAccount) {
                $this->db->query("
                    UPDATE mpayments
                    SET payment_account_id = {$paymentAccount['id']}
                    WHERE id = {$this->mpayment['id']}
                ");
            }

			$this->engine->jsconfig['sp'] = 'sendform';
			$this->engine->jsconfig['autoSubmitForm'] = $autoSubmitForm;
			$this->engine->jsconfig['paymentId'] = $this->mpayment['id'];
			$this->engine->jsconfig['returnUrl'] = '/merchant/toreturn/'.$this->mpayment['id'].'/success/';
		}
        private function generateSignInterkassa($data, $key) {
		    $data['ik_desc'] = html_entity_decode($data['ik_desc'], ENT_QUOTES, 'UTF-8');
		    $data['ik_desc'] = htmlspecialchars($data['ik_desc'], ENT_QUOTES);
            ksort($data, SORT_STRING);
            array_push($data, $key); 
            $signString = implode(':', $data); 
            $sign = base64_encode(hash('sha256',$signString, true)); 
            return $sign;
        }
        private function getDataLiqpay($paysys){
            
            $data = array (
                    'version' =>  3,
                    'public_key' => $paysys['aggconfig']['publickey'],
                    'amount' => $paysys['amount'],
                    'currency' => 'RUB',
                    'description' => $this->mpayment['description'],
                    'order_id' => $this->mpayment['id'],
                    'type' => 'buy',
                    'language' => 'ru',
                    );
            return base64_encode(json_encode($data));
        }
        public function getSignatureLiqpay($paysys,$data){
            $private_key = $paysys['aggconfig']['privatekey'];
            return base64_encode(sha1($private_key.$data.$private_key, 1));
        }
		private function checklifetime(){
			//$this->mshop['lifetime']  &&
			if(time() > strtotime($this->mpayment['ts']) + $this->mshop['lifetime'] * 60)return false;
			else return true;
		}
		private function setmshop($shopId){
			if($this->mshop = $this->db->super_query("SELECT id,name,url,fee,lifetime,secret,checksigncreate,success,fail,sendmethod,overrideurl,test,status,userId FROM mshops WHERE id = ".$shopId." LIMIT 1"))return true;
			else return false;
		}
        private function checkLiqpayReturn(){
              
                if (empty($_REQUEST['data']) || empty($_REQUEST['signature'])) { return '0'; }
                $data = $_REQUEST['data'];
                $parsed_data = json_decode(base64_decode($data), true);
                $received_signature = $_REQUEST['signature'];
                $received_public_key = $parsed_data['public_key'];
                $order_id            = $parsed_data['order_id'];
                $status              = $parsed_data['status'];
                $sender_phone        = $parsed_data['sender_phone'];
                $amount              = $parsed_data['amount'];
                $currency            = $parsed_data['currency'];
                $transaction_id      = $parsed_data['transaction_id'];
                if (empty($order_id)) { return '0'; }
                $config = $this->db->super_query("SELECT * FROM mpayagg WHERE name = 'liqpay'");
                $config = json_decode($config['config']);
                $private_key = $config->privatekey;
                $public_key  = $config->publickey;
                $generated_signature = base64_encode(sha1($private_key.$data.$private_key, 1));
                if ($received_signature != $generated_signature || $public_key != $received_public_key) { return '0'; }
                if ($status == 'success') {
                    $this->uvar[3] = 'success';
                    return $order_id; 
                }
                return '0';
        }
        private function checkWMReturn() {
            if (empty($_REQUEST['LMI_SYS_INVS_NO']) || empty($_REQUEST['LMI_SYS_TRANS_NO'])) { return '0'; }
            return $_REQUEST['LMI_PAYMENT_NO'];
                
        }
         private function checkWMnotifReturn() {
         	global $bd;

         	$pay_no = $bd->prepare((int)$_POST['LMI_PAYMENT_NO'], 16);
			$amount = $bd->prepare($_POST['LMI_PAYMENT_AMOUNT'], 16);
			$payee_purse = $bd->prepare($_POST['LMI_PAYEE_PURSE'], 16);
			$customer_purse = $bd->prepare($_POST['LMI_PAYER_PURSE'], 16);

			if(!preg_match('/^(212\.118\.48\.|212\.158\.173\.|91\.200\.28\.|91\.227\.52\.)\d{1,3}$/', REMOTE_ADDR)){//недопустимый ip
				logs("error:ip|||pay_no:".$pay_no);
				die('ip_error');
			}
			
			if($_POST['LMI_MODE']){//тестовый режим
				logs("error:test_mode|||pay_no:".$pay_no);
				die('testing mode');
			}
			if(!preg_match('/^(R242192621258|E980896039439|U709777381444|Z645264325724)$/', $payee_purse)){//неверный кошелек получения
				logs("error:purse|||pay_no:".$pay_no."|||payee_purse:".$payee_purse);
				die('wrong purse');
			}


			$request = $bd->read("SELECT amountPay FROM `mpayments` WHERE id=".$pay_no." LIMIT 1");
			$totalBuyer = mysql_result($request,0,0);

			if($amount != $totalBuyer){
				logs("error:amount:".$amount."|||pay_no:".$pay_no);
				die('amount error');
			}

            if (empty($_REQUEST['LMI_SYS_INVS_NO']) || empty($_REQUEST['LMI_SYS_TRANS_NO'])) { echo 'OK'; die(); }
            if (empty($_REQUEST['LMI_PAYMENT_NO'])){ echo 'OK'; die(); }
            return $_REQUEST['LMI_PAYMENT_NO'];
                
        }
         private function checkIkassaNotifReturn() {
         	global $bd;
         	include "interkassa.class.php";

         	$order_id = (int)$_POST['ik_pm_no'];

         	if($_POST['ik_co_id'] !== '5bcecf4d3c1eaf5b018b45ad'){
         	 	logs("ikassa|not_verified|pay_no:".$order_id);
				die("Что-то не так ...");
         	}

         	if(!in_array(REMOTE_ADDR,array('151.80.190.97','151.80.190.98','151.80.190.99','151.80.190.100','151.80.190.101','151.80.190.102','151.80.190.103','151.80.190.104'),true)){
				logs("error:interkassa|||IP|||orderId:".$order_id);
				die('IP error');
			}

			$amount =  $_POST['ik_am'];
			$curr = $_POST['ik_cur'];

			if($_POST['ik_pw_via'] == 'bitcoin_advcash_merchant_eur'){
				$need_curr = 'EUR';
			}else{
				$need_curr = 'RUB';
			}
			

			$request = $bd->read("SELECT amountPay FROM `mpayments` WHERE id=".$order_id." LIMIT 1");
			$totalBuyer = mysql_result($request,0,0);

			if($amount != $totalBuyer){
				logs("error:amount:".$amount."|||pay_no:".$order_id);
				die('amount error');
			}
			
			if($curr != $need_curr){
				logs("error:curr:".$curr."|||pay_no:".$order_id);
				die('currency error');
			}


			if (empty($_REQUEST['ik_inv_st']) && $_REQUEST['ik_inv_st'] != 'success') { echo 'OK'; die(); }
			if (empty($_REQUEST['ik_pm_no'])){ echo 'OK'; die(); }
			return $_REQUEST['ik_pm_no'];     
        }
        private function checkIkassaReturn() {
			if (empty($_REQUEST['ik_inv_st']) && $_REQUEST['ik_inv_st'] != 'success') { return '0'; }
			if (empty($_REQUEST['ik_pm_no'])){ return '0'; }
			return $_REQUEST['ik_pm_no'];
        }
        private function checkPaypalReturn() {
			$paypalemail = $this->config['paypal']['receiver'];
			$currency    = "USD";              

			global $bd;
			
			$order_id = $bd->prepare((int)$_POST['item_number'], 64);
			/******** 
			запрашиваем подтверждение транзакции 
			********/ 
			$postdata=""; 
			foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&"; 
			$postdata .= "cmd=_notify-validate"; 
			$curl = curl_init("https://www.paypal.com/cgi-bin/webscr"); 
			curl_setopt ($curl, CURLOPT_HEADER, 0); 
			curl_setopt ($curl, CURLOPT_POST, 1); 
			curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata); 
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: primearea'));
			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 2); 
			$response = curl_exec ($curl); 
			curl_close ($curl); 
			
			if ($response != "VERIFIED"){
				logs("paypal|not_verified|pay_no:".$order_id);
				die("Что-то не так ...");
			} 
			if ($_POST['payment_status'] != "Completed"){
				logs("paypal|waiting|pay_no:".$order_id);
				die("Ожидание поступления ...");
			} 
			/******** 
			проверяем получателя платежа и тип транзакции, и выходим, если не наш аккаунт 
			в $paypalemail - наш  primary e-mail, поэтому проверяем receiver_email 
			********/ 
			if ($_POST['receiver_email'] != $paypalemail){
				logs("paypal|wrong_email|pay_no:".$order_id);
			  	die("Неверный получатель ..."); 
			}

			/******** 
			убедимся в том, что эта транзакция не 
			была обработана ранее 
			********/ 
			$request = $bd->read("SELECT id, amountPay FROM `mpayments` WHERE id=".$order_id." AND status = 'success' LIMIT 1");
			$request2 = $bd->read("SELECT amountPay FROM `mpayments` WHERE id=".$order_id." LIMIT 1");
			$order = mysql_result($request,0,0);
			$totalBuyer = mysql_result($request2,0,0);
			
			if ($order){
				logs("paypal|already_paid|pay_no:".$order_id);
				die ("Уже оплачено ...");
			} 
			/******** 
			проверяем сумму платежа 
			********/ 
			if ($totalBuyer != $_POST["mc_gross"] || $_POST["mc_currency"] != $currency){ 
				logs("paypal|wrong_amount|pay_no:".$order_id);
				die("Неправильная сумма."); 
			}

			return $order_id;
        }

        private function checkSkrillReturn() {
        	require_once $_SERVER['DOCUMENT_ROOT'].'/func/skrill.class.php';

        	$gateway = new skrill();

        	try {
        		throw new Exception();
        	    $response_order_id = $gateway->checkResponse($this->db, $this->config, $_POST, 'merchant');
        	} catch (SkrillValidateErrorException $e) {
        		logs($e->getMessage());
        		close($e->getMessage());
        	}


			return $response_order_id;
        }

		public function setmpayment($condition){
            $conditionMethod = 'none';     

			$clause = array();
            if ($condition['id'] == 'liqpaynotif'){
                $condition['id'] = $this->checkLiqpayReturn();
                $conditionMethod = 'liqpaynotif';
            }
            elseif ($condition['id'] == 'liqpay'){
                $condition['id'] = $this->checkLiqpayReturn();
                
               
            }
            else if ($condition['id'] == 'wm'){
                $condition['id'] = $this->checkWMReturn();
            }
            else if ($condition['id'] == 'wmnotif'){
                $condition['id'] = $this->checkWMnotifReturn();
                $conditionMethod = 'wmnotif';
            }
            else if ($condition['id'] == 'ikassanotif'){
                $condition['id'] = $this->checkIkassaNotifReturn();
                $conditionMethod = 'ikassanotif';
            }
            else if ($condition['id'] == 'ikassa'){

               $condition['id'] = $this->checkIkassaReturn(); 

            }
            else if ($condition['id'] == 'paypal'){

               $condition['id'] = $this->checkPaypalReturn(); 
               $conditionMethod = 'paypal';
            }
            else if ($condition['id'] == 'skrill'){

               $condition['id'] = $this->checkSkrillReturn();
               $conditionMethod = 'skrill';
            }
			foreach($condition as $k=>$v)$clause[] = 'mp.'.$k.' = '.$v;
			$this->mpayment = $this->db->super_query("
				SELECT
					mp.id,mp.mshopId,mp.payno,mp.amount,mp.amountPay,mp.amountProfit,mp.description,mp.hash,mp.uvs,mp.sign,mp.success,mp.fail,mp.status,mp.ts,mp.yandex_cart_context_id,mp.payment_account_id,
					mps.via,
					mpa.config AS aggConfig
				FROM mpayments mp
				LEFT JOIN mpaysyss mps ON mps.id = mp.viaId
				LEFT JOIN mpayagg mpa ON mpa.id = mps.aggId
				WHERE ".implode(' AND ',$clause)."
				LIMIT 1
			");

			if(!$this->mpayment)return false;
			
			if($this->mpayment['aggConfig']){
				$this->mpayment['aggConfig'] = json_decode($this->mpayment['aggConfig'],true);
				$this->aggConfig = $this->mpayment['aggConfig'];
			}
			
                        if ($conditionMethod == 'wmnotif'){
                            
                                $this->confirm();
                                
                                echo 'OK';
                                exit();
                            
                        }
                        if ($conditionMethod == 'ikassanotif'){
                           
                                $this->confirm();
                                
                                echo 'OK';
                                exit();
                           
                        
                        }
                        if ($conditionMethod == 'liqpaynotif'){
                            if ($this->mpayment['amountPay'] == $_REQUEST['ik_am'] && $this->mpayment['aggConfig']['merchant'] == $_REQUEST['ik_co_id']){
                                $this->confirm();
                                $this->toreturn('success');
                                echo 'OK';
                                exit();
                            }
                        
                        }
                        if ($conditionMethod == 'paypal'){
                                $this->confirm();
                                $this->toreturn('success');
                                echo 'OK';
                                exit();
                        }
                        if ($conditionMethod == 'skrill'){
                                $this->confirm();
                                $this->toreturn('success');
                                echo 'OK';
                                exit();
                        }

			return true;
		}
		private function toreturn($status){
			require_once('lib/external_payment.php');
    		require_once('lib/api.php');
            require_once 'PaymentAccountFetcher.php';
            $paymentAccountFetcher = new PaymentAccountFetcher($this->db);

    		if($this->mpayment['id'] == '13'){

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
				}
                try {
                    $paymentAccount = $paymentAccountFetcher->getById($this->mpayment['payment_account_id']);
                } catch (Exception $e) {
                    return $this->paypageerror('Ошибка');
                }
				$external_payment = new ExternalPayment($paymentAccount['config']['instance_id']);
		 		$process_options = array(
			        "request_id" => $context_id,
			        'ext_auth_success_uri' => '/',
			        'ext_auth_fail_uri' => '/payment-fail',
		   		);

		    	$result = $external_payment->process($process_options);
				if($result->status != 'success' || $this->mpayment['yandex_cart_context_id'] != $context_id || $this->mpayment['amount'] > $sum){
					$this->engine->jsconfig['sp'] = 'refresh';
					return $this->paypageerror('Ожидание поступления оплаты.');
				}else{
					$this->confirm();
				}
    			
    		}elseif( ($this->mpayment['via'] == 'paypal' || $this->mpayment['via'] == 'yandexmoney' || $this->mpayment['via'] == 'skrill') && $this->mpayment['status'] != 'success'){
    			$this->engine->jsconfig['sp'] = 'refresh';
				return $this->paypageerror('Ожидание поступления оплаты.');
			}


			$this->tpl->switchy('page','return');	
			if($status == 'success')$url = $this->mshop['overrideurl'] && $this->mpayment['success'] ? $this->mpayment['success'] : $this->mshop['success'];
			else $url = $this->mshop['overrideurl'] && $this->mpayment['fail'] ? $this->mpayment['fail'] : $this->mshop['fail'];
			
			$this->tpl->set(array(
				'method' => $this->mshop['sendmethod'] ? 'GET' : 'POST',
				'url' => $url,
				'shopid' => $this->mshop['id'],
				'payno' => $this->mpayment['payno'],
				'amount' => $this->mpayment['amount'],
				'via' => $this->mpayment['via']
			));
			
			$uvs = json_decode($this->mpayment['uvs'],true);
			foreach($uvs as $k => $v)$data['senddata'][$k] = $v;
			$this->tpl->fory('vars');
			foreach($uvs as $k => $v){
				$this->tpl->foryCycle(array(
					'varname' => $k,
					'varval' => $v
				));
			}

			$this->tpl->foryEnd();
            $this->engine->jsconfig['autoSubmitForm'] = true;
			$this->engine->jsconfig['sp'] = 'sendform';

            $this->tpl->ify('head',1);
            $this->tpl->set('{headTitle}','');

			return(array(
				'content' => $this->tpl->content,
				'title' => 'Оплата'
			));
		}
		private function createnotify($mpaymentId,$noexec = 1,$ts = false){
			$ts = $ts ? 'ADDDATE(CURRENT_TIMESTAMP,INTERVAL 10 MINUTE)' : 'CURRENT_TIMESTAMP';
			$this->db->query("INSERT INTO mnotify (mpaymentId,noexec,ts)VALUES(".$mpaymentId.",".$noexec.",".$ts.")");
			return $this->db->insert_id();
		}
		private function sendnotify($mnotifyId = false){
			$this->db->query("SET autocommit = 0");
			$mi = 5;
			if($mnotifyId){
				$WHERE = "id = ".$mnotifyId." AND tsr IS NULL";
				$mi = 1;
			}else $WHERE = "tsr IS NULL AND noexec = 0 AND ts < CURRENT_TIMESTAMP";
			for($i=0;$i<$mi;$i++){
				$notification = $this->db->super_query("SELECT id,mpaymentId FROM mnotify WHERE ".$WHERE." ORDER BY id LIMIT 1 FOR UPDATE");
				//@TODO break?
				if(!$notification)break;
				$payment = $this->db->super_query("
					SELECT
						mp.id, mp.mshopId,mp.payno,mp.amount,mp.uvs,mp.tsr,mp.mshopFee,mp.amountProfit,
						ms.secret,ms.result,ms.sendmethod,
						mps.via,mps.code,mps.name,
						u.email
					FROM mpayments mp
					JOIN mshops ms ON ms.id = mp.mshopId
					LEFT JOIN mpaysyss mps ON mps.id = mp.viaId
					JOIN user u ON u.id = ms.userId
					WHERE mp.id = ".$notification['mpaymentId']."
					LIMIT 1
				");

				$amount = $payment['amount'];

				$data = array(
					'url' => $payment['result'],
					'sendmethod' => $payment['sendmethod'],
					'senddata' => array(
						'system_payno' => $payment['id'],
						'shopid' => $payment['mshopId'],
						'payno' => $payment['payno'],
						'amount' => $amount,
						'via' => $payment['code'] ? $payment['name'] : $payment['via'],
						'date' => $payment['tsr']
					)
				);
				
				$uvs = json_decode($payment['uvs'],true);
				foreach($uvs as $k => $v)$data['senddata'][$k] = $v;
				ksort($data['senddata'],SORT_STRING);
				$data['senddata']['sign'] = hash('sha256',implode(':',$data['senddata']).':'.$payment['secret']);
				$senddata = $this->db->safesql(json_encode($data['senddata']));
				$data = json_encode($data);
				$sign = hash('sha256',$data.':'.$this->config['merchant']['secret']);
				$post = array(
					'sign' => $sign,
					'data' => $data
				);
				$ctx = stream_context_create(array(
					'http' => array(
						'timeout' => 6,
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query($post)
					)
				));
				$getcontents = @file_get_contents($this->config['merchant']['sendUrl'], 0, $ctx);
				if(!$getcontents = json_decode($getcontents,true)){
					$this->db->query("UPDATE mnotify SET noexec = 0,ts = ADDDATE(CURRENT_TIMESTAMP,INTERVAL 1 MINUTE) WHERE id = ".$notification['id']." LIMIT 1");
					require_once $_SERVER['DOCUMENT_ROOT'].'/func/logs.class.php';
					$logs = new logs();
					$logs->add('merchantSendNotifyError',$notification['id']);
					$this->db->query("COMMIT");
					continue;
				}
				$result = $this->db->safesql($getcontents['result'],true);
				$code = (int)$getcontents['code'];
				$this->db->query("
					UPDATE mnotify
					SET
						senddata = '".$senddata."',
						result = '".$result."',
						code = ".$code.",
						tsr = CURRENT_TIMESTAMP
					WHERE id = ".$notification['id']."
					LIMIT 1
				");
				if($code !== 200){
					if($this->db->super_query_value("SELECT COUNT(*) FROM mnotify WHERE mpaymentId = ".$notification['mpaymentId']) > 4){
						$subject = 'ОШИБКА ОТПРАВКИ УВЕДОМЛЕНИЯ';
						$message = 'Сервис merchant '.$this->config['site_domen'].' не смог отправить уведомление о оплате. Подробнее в вашем личном кабинете.';
						$email = $payment['email'];
                        $message = $this->db->safesql($message);
						$this->db->query("INSERT INTO mail(subject,message,`to`,status)VALUES('".$subject."','".$message."','".$email."','need')");
					}else $this->createnotify($notification['mpaymentId'],0,true);
				}
				$this->db->query("COMMIT");
			}
			$this->db->query("SET autocommit = 1");
		}
		private function paytest(){
			$this->confirm(true);
			$this->toreturn('success');
		}
		public function confirm($test = false){

			$this->db->query("SET autocommit = 0");

			$payment = $this->db->super_query_value("
				SELECT id
				FROM mpayments
				WHERE
					id = ".$this->mpayment['id']." AND
					status = 'pending'
				LIMIT 1
				FOR UPDATE
			");

			if(!$payment){
				$this->db->query("SET autocommit = 1");
				return;
			}

			if(!$this->mshop['userId']){
				$this->setmshop($this->mpayment['mshopId']); 
			}


			global $bd;
			require_once $_SERVER['DOCUMENT_ROOT'].'/func/transactions.class.php';
				
			$shopReservation = $this->db->super_query("
				SELECT reservation
				FROM mshops
				WHERE id = ".$this->mshop['id']."
			");

			if($shopReservation['reservation']){
				$_time = $shopReservation['reservation'];
			}else{
				$_time = 'value';
			}

			$request = $bd->read("
				SELECT ADDDATE(CURRENT_TIMESTAMP, INTERVAL ".$_time." HOUR)
				FROM setting
				WHERE ids = 3
				LIMIT 1
			");
			$executing = mysql_result($request,0,0);

			$this->db->query("UPDATE mpayments SET status = 'success',tsr = '".$executing."' WHERE id = ".$this->mpayment['id']." LIMIT 1");
			$this->db->query("COMMIT");
			$this->db->query("SET autocommit = 1");
			
			if(!$test){

				$transactions = new transactions();
				$transactions->create(array(
					'user_id' => $this->mshop['userId'],
					'type' => 1,
					'method' => 'merchant',
					'method_id' => $this->mpayment['id'],
					'currency' => 4,
					'amount' => $this->mpayment['amountProfit'],
					'executing' => $executing
				));                         
			}
			
			$mnotifyId = $this->createnotify($this->mpayment['id']);
			$this->sendnotify($mnotifyId);
		}
		public function checkamount($amount){
			if($this->mpayment['amountPay'] !== $amount)return false;
			else return true;
		}
		public function externalinit($mpaynmentId){
			if(!$this->setmpayment(array('id'=>$mpaynmentId)))return 'paymentNotFound';
			if(!$this->setmshop($this->mpayment['mshopId']))return 'shopNotFound';
			return true;
		}
		private function paysyss($mshopId,$check = false,$amount = false,$forinfo = false, $via_code = false){
			GLOBAl $bd;

			$WHERE = '';
			$via_code ? $WHERE .= " AND ps.code = '".$this->db->safesql($via_code)."' " : '';
			if($this->mshop['status'] != 'accepted')$WHERE .= " AND ps.fortest = 1";
			if(!$this->mshop['test'])$WHERE .= " AND ps.via != 'test'";
			if($check)$WHERE .= " AND ps.via = '".$check."'";
			$request = $this->db->query("
				SELECT
					ps.id,ps.name,ps.fee,ps.via,
					pa.config AS aggconfig, ps.code, pa.name as aggname
				FROM mpaysyss ps
				LEFT JOIN mpayagg pa ON pa.id = ps.aggId
				WHERE
					ps.enabled = 1
					$WHERE
				ORDER BY position
			");

			if(!$this->db->num_rows($request))return false;

			$paysyss_fee_values = $this->db->super_query("
				SELECT paysyss_fee
				FROM mshops
				WHERE id = ".$mshopId." AND paysyss_override = 1
			");

			$fee_values = $paysyss_fee_values ? json_decode(html_entity_decode($paysyss_fee_values['paysyss_fee']), true) : false;

			$paysyss = array();
            include_once dirname(dirname(__FILE__)).'/modules/currency/currclass.php';
            $currency = new current_convert();
			while($paysys = $this->db->get_row($request)){
				if($forinfo)unset($paysys['aggconfig'], $paysys['aggname']);
				else $paysys['aggconfig'] = $paysys['aggconfig'] ? json_decode($paysys['aggconfig'],true) : array();
				if($amount){
                    $oldamount = $amount;
                    $curr = 4;
                    // huk for WM convert currency ammount
                        /*if ($paysys['via'] == 'wmz'){
                        	$curr = 1;
                            $amount = $currency->curr($oldamount, 4, $curr, false );
                        }
                        else if ($paysys['via'] == 'wme'){
                        	$curr = 3;
                            $amount = $currency->curr($oldamount, 4, $curr, false );
                        }
                        else if ($paysys['via'] == 'wmu'){
                        	$curr = 2;
                            $amount = $currency->curr($oldamount, 4, $curr, false );
                        }else */if ($paysys['via'] == 'paypal'){
                            $curr = 1;

                            $paypal_amount = $currency->curr($oldamount, 4, 1, false );

                            $paypal_settings = $bd->read("SELECT `value` FROM `setting` WHERE `ids` IN (10,11)  ORDER BY id");
                        	$paypal_percent = mysql_result($paypal_settings,0,0); 
						    $paypal_val = mysql_result($paypal_settings,1,0); 
						    
						    $amount = $amount * (100+intval($paypal_percent))/100;
						    $amount = $currency->curr($amount, 4, $curr, false );

						    $price_ad = $currency->curr($paypal_val, 4, $curr,0);
						   
						    $amount += $price_ad;
						    $amount = round($amount, 2);

						    $additional_fee = $amount - $paypal_amount;
                        }else if ($paysys['via'] == 'bitcoin'){
                        	$curr = 3;
                            $amount = $currency->curr($oldamount, 4, $curr, false );
                        }else if($paysys['via'] == 'skrill'){

                        	$skrill_fee_val  = $this->config['skrill']['wallet']['provider_fee'];
    						$curr            = $this->config['skrill']['wallet']['currency_val'];

						    $skrill_amount = $currency->curr($oldamount, 4, $curr, false );

						    $amount = $currency->curr($amount, 4, $curr, false );
						    $amount = bcadd($amount, $skrill_fee_val, 2);
						    $additional_fee = bcsub($amount, $skrill_amount);
                        }
					
					if(isset($fee_values[$paysys['id']])){
						$paysys['fee'] = $fee_values[$paysys['id']];
					}
					
					$paysys['feeAmount'] = bcdiv(bcmul($paysys['fee'],$amount),100);

					if($paysys['via'] == 'paypal'/* || $paysys['via'] == 'wmz'*/){

						$fee = $currency->curr($paysys['feeAmount'], 1, 4, 0);
						if($paysys['via'] == 'paypal' && $this->mshop['fee']){

							$additional_fee = $currency->curr($additional_fee, 1, 4, 0);
							$fee = $fee + $additional_fee;
						}
						

					}elseif(/*$paysys['via'] == 'wme' || */$paysys['via'] == 'bitcoin'){
						$fee = $currency->curr($paysys['feeAmount'], 3, 4, 0);
					}/*elseif($paysys['via'] == 'wmu'){
						$fee = $currency->curr($paysys['feeAmount'], 2, 4, 0);
					}*/elseif($paysys['via'] == 'skrill'){

						$fee = $currency->curr($paysys['feeAmount'], $curr, 4, 0);
						if($this->mshop['fee']){
							$additional_fee = $currency->curr($additional_fee, $curr, 4, 0);
							$fee = bcadd($fee, $additional_fee);
						}

					}else{
						$fee = $paysys['feeAmount'];
					}

					if($paysys['via'] == 'paypal'){
						$paysys['amount'] = $this->mshop['fee'] ? $paypal_amount : bcadd($paysys['feeAmount'],$amount);
					}elseif($paysys['via'] == 'skrill'){
						$paysys['amount'] = $this->mshop['fee'] ? $skrill_amount : bcadd($paysys['feeAmount'],$amount);
					}else{
						$paysys['amount'] = $this->mshop['fee'] ? $amount : bcadd($paysys['feeAmount'],$amount);
					}
					

					$paysys['add_fee'] = $fee;
					$paysys['curr'] = $curr;
                    $amount = $oldamount; 
				}
				$paysyss[] = $paysys;
			}

			if($check){      
				return $paysyss[0];
			}else{
				return $paysyss;
			}
		}
		private function paypageerror($error){
			$this->tpl->ify('head',1);
            $this->tpl->set('{headTitle}','');
			$this->tpl->switchy('page','error');
			$this->tpl->set('{error}',$error);
			return(array(
				'content' => $this->tpl->content,
				'title' => $error
			));
		}
        /**
         * @param $amount
         * @return array
         */
        private function amountParts ($amount)
        {
            $parts = explode('.', $amount);
            if (count($parts) === 1) {
                $parts[1] = 0;
            } else {
                $parts[1] = bcmul("0.$parts[1]", '100', 0);
            }

            return $parts;
        }
	}
