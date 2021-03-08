<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include '../config.php';
include '../mysql.php';
include '../main.php';
include '../../modules/currency/currclass.php';
include "../db.class.php";
include "../transactions.class.php";

$bd = new mysql();
$db = new db();

if (php_sapi_name() !== 'cli') {
    close('Access deny');
}

if (empty($argv[1])) {
    close('Order ID is missing');
}
$order_id = (int)$argv[1];

$addMoney = $db->super_query("SELECT id, user_id, money FROM addmoney WHERE id = $order_id");
if (null === $addMoney) {
    close($order_id.' NOT FOUND');
}

$bd->write("UPDATE addmoney SET status = 'Пополнено' WHERE id = ".$order_id);

$transactions = new transactions();
$transactions->create(array(
    'user_id' => $addMoney['user_id'],
    'type' => 1,
    'method' => 'addmoney',
    'method_id' => $addMoney['id'],
    'currency' => 4,
    'amount' => $addMoney['money']
));

close($order_id.' OK');
