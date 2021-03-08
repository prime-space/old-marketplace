<?php
	include '../func/config.php';
	include '../func/mysql.php';
	include '../func/main.php';
	include "../func/cron.class.php";
    include '../func/db.class.php';
	
	$bd = new mysql();
    $db = new db();

	$cron = new cron('min', 60);
	if($cron->stop)close();
	
	shop::cron_showing_product();
	shop::cron_search_hidden_product();
	shop::cron_in_stock_product();
	shop::get_last_sells();

	$orderIpPeriod = 'p'.(floor((date('i') + 2) / 2) % 5 + 1);
	$db->query("DELETE FROM `orderIp` WHERE $orderIpPeriod <> ''");

	$cron->end();
	close();
?>
