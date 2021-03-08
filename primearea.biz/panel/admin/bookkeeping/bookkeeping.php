<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif($user->role === 'admin' || $user->role === 'moder'){
		include "func/bookkeeping.class.php";
		$bookkeeping = new bookkeeping();
		$content = $bookkeeping->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Бухгалтерия';
		
		if($user->role === 'moder' && !$user->checkModerRight('bookkeeping')){
			$page = 'Доступ запрещён';
		}
	}else $page = 'Доступ запрещён'; 
?>