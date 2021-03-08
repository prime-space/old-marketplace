<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	if($_GET['code']){
		$code_changed = $db->super_query("SELECT value FROM setting WHERE name = 'yandex_auth_token_changed' LIMIT 1");
		
		if($code_changed['value']){

			include_once $_SERVER['DOCUMENT_ROOT']."/func/lib/api.php";

			// GET access for 3 years
			$access_token_response = API::getAccessToken($CONFIG['yandex']['autopayments']['client_id'], $_GET['code'], $siteAddr, $CONFIG['yandex']['autopayments']['secret']);
			if(property_exists($access_token_response, "error")) {
			    die('Ошибка.'); // process error
			}
			$access_token = $db->safesql($access_token_response->access_token);
		
			$result = $db->query("UPDATE setting SET value = '".$access_token."' WHERE name = 'yandex_auth_token' LIMIT 1");
			if($result){
				$result = $db->query("UPDATE setting SET value = 0 WHERE name = 'yandex_auth_token_changed' LIMIT 1");
				$result ? die('Новый токен сохранен') : die('Токен изменен, счетчик изменения - нет');
			}else{
				die('Не удалось обновить базу');
			}
		}
	}
?>