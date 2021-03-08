<?php
	include '../func/config.php';
	include '../func/mysql.php';
	include '../func/main.php';
	include "../func/cron.class.php";

	

	$bd = new mysql();
	
	$cron = new cron('hour', 3600);
	if($cron->stop)close();
	

	shop::cron_group_cache();
	shop::cron_statistic_cache();

	$cron->end();
	close();

?>
