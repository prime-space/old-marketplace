<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/productadd.class.php";
		$productadd = new productadd();
		$content = $productadd->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Добавление товара';
	}else $page = 'Необходимо авторизоваться'; 
?>