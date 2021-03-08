<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin', 'moder'))){
		include "func/product.class.php";
		$content = $user->newuserspage();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Новые пользователи';
		
		if($user->role === 'moder' && !$user->checkModerRight('newusers')){
			$page = 'Доступ запрещён';
		}
	}else $page = 'Доступ запрещён'; 
?>