<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include '../config.php';
include '../mysql.php';
include '../main.php';
include '../../modules/currency/currclass.php';
include "../db.class.php";

$bd = new mysql();
$db = new db();
$order = new order();

if (php_sapi_name() !== 'cli') {
    close('Access deny');
}

if (empty($argv[1])) {
    close('Order ID is missing');
}
$order_id = (int)$argv[1];

$order->confirm_order($order_id);

close($order_id.' OK');
