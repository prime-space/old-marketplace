<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/shopsite.class.php";
		$shopsite = new shopsite();
		$content = $shopsite->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Мой магазин';
	}else $page = 'Необходимо авторизоваться'; 
?>