<?php
if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

$tplt = new tpl('pages/info');
$page = $tplt->content;
$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Информация';
