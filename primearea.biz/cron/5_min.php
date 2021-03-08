<?php
include '../func/config.php';
include '../func/mysql.php';
include '../func/main.php';
include "../func/cron.class.php";
include '../func/db.class.php';

$bd = new mysql();
$db = new db();

$cron = new cron('5_min', 300);
if ($cron->stop) {
    close();
}

//set sidebar static content
shop::get_popular_sells();

$db->query("UPDATE orderIpLong SET isBlocked = 1 WHERE orderNum > 30 AND successNum = 0");

$cron->end();
close();
