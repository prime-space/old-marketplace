<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplt = new tpl('pages/twitch');
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Twitch';
	$page = $tplt->content;
?>