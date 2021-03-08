<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/sellquestion');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Вопросы продавцов';
	$page = $tplt->content;
?>