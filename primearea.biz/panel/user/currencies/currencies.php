<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/currencies.class.php";
		$currencies = new currencies();
		$content = $currencies->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Курсы валют и способы оплаты';
	}else $page = 'Необходимо авторизоваться';
?>