<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include "modules/category/category.php";
	include "modules/yandex/yandex.php";

	$maincontent = new templating(file_get_contents(TPL_DIR.'main.tpl'));
	
	$staticcontent = shop::product_listing_show_static_version($_GET['n']);
	
	$maincontent->set('{staticcontent}', $staticcontent);
	
	if($user->id){
		
	}
	
	$page = $maincontent->content;
?>