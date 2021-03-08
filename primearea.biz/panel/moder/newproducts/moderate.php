<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin', 'moder'))){
		include "func/product.class.php";
		$product = new product();
		
		if(isset($_GET['id'])){
			$content = $product->moderateOne();
		}else{
			$content = $product->moderatePage();
		}

		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Модерация товаров';
		
		if($user->role === 'moder' && !$user->checkModerRight('moderate')){
			$page = 'Доступ запрещён';
		}

	}else $page = 'Доступ запрещён'; 
?>