<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	include 'func/buy.class.php';
	
	$content = buy::page();	
		
	$HEAD['title'] = $content['title'];
	
	$page =  $content['content'];
?>