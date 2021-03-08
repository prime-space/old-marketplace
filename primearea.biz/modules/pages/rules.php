<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/rules');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Правила покупки товаров';
	$page = $tplt->content;
?>