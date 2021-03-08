<?php
if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

$tplt = new tpl('pages/contact');
$page = $tplt->content;
$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Контакты';
