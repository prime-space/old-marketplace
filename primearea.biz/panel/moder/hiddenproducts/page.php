<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin')) || ($user->role == 'moder' && $user->checkModerRight('panel_hiddenproducts')) ){
		
		include "func/hiddenproducts.class.php";
		$hiddenproducts = new hiddenproducts();
		
		$content = $hiddenproducts->init();
		
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Скрытые продукты';

	}else $page = 'Доступ запрещён'; 
?>