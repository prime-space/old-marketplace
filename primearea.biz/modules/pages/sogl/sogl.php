<?php
if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

$tplt = new tpl('pages/sogl');
$page = $tplt->content;
$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Соглашение';
