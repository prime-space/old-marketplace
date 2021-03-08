<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	$maincontent = new templating(file_get_contents(TPL_DIR.'main.tpl'));
	
	$page = $maincontent->content;
?>
