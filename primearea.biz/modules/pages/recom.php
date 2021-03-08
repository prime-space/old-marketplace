<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/recom');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Рекомендации по размещению товаров';
	$page = $tplt->content;
?>