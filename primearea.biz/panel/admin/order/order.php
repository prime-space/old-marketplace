<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif($user->role === 'admin' || $user->role === 'moder'){

		//include "func/order.class.php";
		$order = new order();
		$content = $order->adminpage();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Заказы';

		if($user->role === 'moder' && !$user->checkModerRight('order')){
			$page = 'Доступ запрещён';
		}

	}else $page = 'Доступ запрещён'; 
?>