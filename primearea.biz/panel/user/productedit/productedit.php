<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/productedit.class.php";
		$productedit = new productedit();
		$content = $productedit->page();
		$page = $content['content'];
		$HEAD['title'] = $content['title'];
	}else $page = 'Необходимо авторизоваться'; 
?>