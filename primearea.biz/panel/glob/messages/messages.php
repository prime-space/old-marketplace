<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include_once "func/messages.class.php";
		$messages = new messages();
		$content = $messages->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Сообщения';
	}else $page = 'Необходимо авторизоваться'; 
?>