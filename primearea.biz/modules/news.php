<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include 'func/news.class.php';
	
	$news = new news();
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Новости';
	$page = $news->page();
?>