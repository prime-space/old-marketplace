<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/arbitrage');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Пожаловаться на продавца';
	$page = $tplt->content;
?>