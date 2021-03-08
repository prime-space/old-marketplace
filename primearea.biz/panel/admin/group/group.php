<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif($user->role === 'admin'){
		include "func/group.class.php";
		$group = new group();
		$content = $group->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Редактирование групп';
	}else $page = 'Доступ запрещён'; 
?>