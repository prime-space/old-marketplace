<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/addmoney.class.php";
		$addmoney = new addmoney();
		$content = $addmoney->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Пополнить счет';
	}else $page = 'Необходимо авторизоваться'; 
?>