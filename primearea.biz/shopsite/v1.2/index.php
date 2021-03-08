<?php
	header('Content-Type:application/x-javascript');

	include '../../func/config.php';
	$id_shop = $_GET['id'];

	$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/shopsite/v1.2/main.js");
	$tpl = str_replace("{shop_id}", $id_shop, $tpl);
	$tpl = str_replace("{site_addr}", $siteAddr, $tpl);
  
	exit($tpl);

?>