<?php
//      exit('0');
if(!in_array($_SERVER["HTTP_X_FORWARDED_FOR"], ['176.14.121.83','81.24.208.100'], true))exit('1');
if(!$_GET['order_id'])exit('2');

include 'config.php';
include 'mysql.php';
include 'main.php';
include '../modules/currency/currclass.php';

include "db.class.php";

$bd = new mysql();

$db = new db();

$order = new order();
$order_id = (int)$_GET['order_id'];
$order->confirm_order($order_id);
close($_GET['order_id'].' OK');
