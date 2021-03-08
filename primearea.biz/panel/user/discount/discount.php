<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/discount.class.php";
		$discount = new discount();
		$content = $discount->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Добавление товара';
	}else $page = 'Необходимо авторизоваться'; 
?>