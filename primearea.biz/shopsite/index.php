<?php
	include '../func/config.php';
	$id_shop = $_GET['id'];

	$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/shopsite/main.js");
	$tpl = str_replace("{id_shop}", $id_shop, $tpl);
	$tpl = str_replace("{site_addr}", $siteAddr, $tpl);
  
	exit($tpl);

?>