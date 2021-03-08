<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include "func/merchant.class.php";
	$merchant = new merchant();
	$content = $merchant->page();
	$page = $content['content'];
	$merchantShopUrl = isset($content['shopUrl']) ? $content['shopUrl'] : '';
	$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - '.$content['title'];
?>
