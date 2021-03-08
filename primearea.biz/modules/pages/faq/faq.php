<?php
if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

$tplt = new tpl('pages/faq');
$page = $tplt->content;
$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - FAQ';
