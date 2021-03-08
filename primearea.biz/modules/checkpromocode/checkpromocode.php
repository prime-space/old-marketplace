<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include 'func/promocodes.class.php';
	
	$promocodes = new promocodes();
	
	$HEAD['title'] = 'PRIMEREA.BIZ - Проверка промокодов';
	$page = $promocodes->checkpage();
?>