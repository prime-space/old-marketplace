<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$maincontent = new templating(file_get_contents(TPL_DIR.'recover.tpl'));
	
	$page = $maincontent->content;
?>