<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/productobj.class.php";
		$productobj = new productobj();
		$content = $productobj->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Редактирование объектов продажи';
	}else $page = 'Необходимо авторизоваться'; 
?>