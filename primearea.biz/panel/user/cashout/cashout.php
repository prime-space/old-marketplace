<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/cashout.class.php";
		$cashout = new cashout();
		$content = $cashout->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Баланс и вывод средств';
	}else $page = 'Необходимо авторизоваться'; 
?>