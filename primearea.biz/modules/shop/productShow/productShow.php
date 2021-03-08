<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	$content = shop::show_product();

	$page = $content['content'];
	$HEAD['title'] = $content['title'];
	$HEAD['keywords'] = $content['keywords'];
	$HEAD['description'] = $content['description'];
?>