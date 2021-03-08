<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include_once "func/partner.class.php";
		$partner = new partner();
		$content = $partner->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - '.$content['title'];
	}else $page = 'Необходимо авторизоваться';
?>
