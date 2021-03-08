<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	require_once $_SERVER['DOCUMENT_ROOT']."/func/lib/external_payment.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/func/lib/api.php";

	//get new token
	if($user->role == 'admin'){

		global $siteAddr;
		$scope 	= ['account-info', 'payment-p2p', 'operation-details']; 

		//temporary token
		$url = API::buildObtainTokenUrl($CONFIG['yandex']['autopayments']['client_id'], $siteAddr, $scope);
		$result = $db->query("UPDATE setting SET value = 1 WHERE name = 'yandex_auth_token_changed' LIMIT 1");
		
		$template = new templating(file_get_contents(TPL_DIR.'getYandexToken.tpl'));
		$template->set('{url}', $url);
		$page = $template->content;
	}	
?>