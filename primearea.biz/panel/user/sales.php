<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/sales.class.php";
		$sales = new sales();
		$content = $sales->page();
		$page = $content['content'];
		$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Бухгалтерия';
	}else $page = 'Необходимо авторизоваться';
?>
