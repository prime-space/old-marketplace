<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include 'func/seller.class.php';
	$content = seller::page();
	
	$page = $content['content'];
	
	$HEAD['title'] = 'PRIMEAREA.BIZ - Продавец '.$content['seller'];
?>