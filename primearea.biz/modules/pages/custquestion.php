<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/custquestion');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Вопросы покупателей';
	$page = $tplt->content;
?>