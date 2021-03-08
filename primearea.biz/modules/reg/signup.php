<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include 'func/auth.class.php';
	
	$auth = new auth();
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Регистрация';
	$page = $auth->signuppage();
?>