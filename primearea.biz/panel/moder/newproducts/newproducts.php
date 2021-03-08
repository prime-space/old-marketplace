<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin', 'moder'))){
		include "func/product.class.php";
		$product = new product();
		$content = $product->newproductspage();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Новые товары';
	}else $page = 'Доступ запрещён'; 
?>