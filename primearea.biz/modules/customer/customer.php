<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include 'func/customer.class.php';
	include 'func/mail/mail.class.php';
	
	$customer = new customer();
	
	$HEAD['title'] = 'PRIMEAREA.BIZ - Мои покупки';
	$page = $customer->page();
?>
