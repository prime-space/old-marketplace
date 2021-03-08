<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$tplSearch = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/search/search.tpl");
	$tplSearch = str_replace('{search}', htmlspecialchars($_POST['search']), $tplSearch);
	
	$search .= $tplSearch;
?>