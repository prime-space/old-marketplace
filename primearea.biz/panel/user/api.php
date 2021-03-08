<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/apipanel.class.php";
		$apipanel = new apipanel();
		$content = $apipanel->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - '.$content['title'];
	}else $page = 'Необходимо авторизоваться';
?>

