<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/faq-cust');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - FAQ. Читать обязательно';
	$page = $tplt->content;
?>