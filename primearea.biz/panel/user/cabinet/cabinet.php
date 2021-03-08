<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/cabinet.class.php";
		$cabinet = new cabinet();
		$content = $cabinet->page();
		$page = $content['content'];
		$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Личный кабинет';
	}else $page = 'Необходимо авторизоваться';
