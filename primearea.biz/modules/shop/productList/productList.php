<?php
	include '../../../func/config.php';
	include '../../../func/main.php';
	
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productList/productList.tpl"));
	
	close($tpl->content);

?>