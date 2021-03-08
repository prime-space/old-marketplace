<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	global $user;
	$user = new user();
	if($user->verify($_COOKIE['crypt'], "user,moder,admin")){
		$userinfo = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/login/logged.tpl");
		$userinfo = str_replace("{login}", $user->login, $userinfo);
		$sellerbuttons = 'block';
		$construction->jsconfig['token'] = $user->token;
		
		$pro = $user->active_privileges() ? 'PRO SELLER' : 'UNKNOWN SELLER';
		$userinfo = str_replace("{pro}", $pro, $userinfo);
	}else{
		$userinfo = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/login/login.tpl");
		$sellerbuttons = 'none';
	}  
?>