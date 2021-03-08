<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/blacklist.class.php";
		$blacklist = new blacklist();
		$content = $blacklist->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Чёрный список';
	}else $page = 'Необходимо авторизоваться';
?>