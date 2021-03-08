<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include '../config.php';
include '../mysql.php';
include '../main.php';
include '../../modules/currency/currclass.php';
include "../db.class.php";
include "../merchant.class.php";

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['PWD'] . '/../..';

$bd = new mysql();
$db = new db();
$merchant = new merchant();

if (php_sapi_name() !== 'cli') {
    close('Access deny');
}

if (empty($argv[1])) {
    close('Order ID is missing');
}
$payment_id = (int)$argv[1];
$merchant->setmpayment(['id' => $payment_id]);
$merchant->confirm();

close($payment_id.' OK');
