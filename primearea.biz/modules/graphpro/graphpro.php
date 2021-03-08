<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';

	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/graphpro/graphpro.tpl"));
	
	
	close($tpl->content);
?>