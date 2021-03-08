<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/promocodes.class.php";
		$promocodes = new promocodes();
		$content = $promocodes->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Промокоды';
	}else $page = 'Необходимо авторизоваться'; 
?>