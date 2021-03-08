<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	
	$tplt = new tpl('pages/garant');
	
	global $bd;
	$request = $bd->read("SELECT COUNT(`id`) FROM `order` WHERE `status` = 'sended' OR `status` = 'review'");

	$tplt->set('{order}', number_format(mysql_result($request,0,0), 0, ',', '.'));

	$request = $bd->read("SELECT COUNT(`id`) FROM `review`");

	$tplt->set('{review}', number_format(mysql_result($request,0,0), 0, ',', '.'));
	
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Гарант';
	$page = $tplt->content;
?>