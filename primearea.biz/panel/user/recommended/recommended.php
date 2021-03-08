<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/recommended.class.php";
		$recommended = new recommended();
		$content = $recommended->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Рекомендуемое';
	}else $page = 'Необходимо авторизоваться'; 
?>