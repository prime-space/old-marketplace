<?php
include "../../../func/config.php";
include "../../../func/mysql.php";
include "../../../func/main.php";
require_once("../../../func/db.class.php");
require_once("../../../func/PaymentAccountFetcher.php");
require_once('../../../func/lib/external_payment.php');
require_once('../../../func/lib/api.php');

$bd = new mysql();
$db = new db();
$user = new user();
if (!$user->verify($_COOKIE['crypt'], "user,moder,admin")) {
    close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
}
if ($_POST['data']) {
    $data = json_decode($_POST['data'], true);
} else {
    close('{"status": "error", "message": "Нет данных"}');
}
if ($data['token'] !== $user->token) {
    close('{"status": "error", "message": "Ошибка доступа"}');
}

if(!preg_match('/^\d+$/', $data['amount'])) {
    close('{"status": "error", "message": "Неверный формат суммы"}');
}
if($data['amount'] < 15 || $data['amount'] > 1500) {
    close('{"status": "error", "message": "Сумма должна быть от 15 до 1500 рублей"}');
}

$amount = $db->safesql($data['amount']);
$db->query("
    INSERT INTO addmoney
        (user_id, status, money)
    VALUES
        ({$user->id}, 'Ожидается оплата', '$amount')
");
$id = $db->insert_id();

$primePayerData = [
    'shop' => $data['shop'],
    'payment' => $id,
    'amount' => $amount,
    'description' => $data['description'],
    'currency' => $data['currency'],
    'success' => $data['success'],
];
ksort($primePayerData, SORT_STRING);
$sign = hash('sha256', implode(':', $primePayerData).':'.$CONFIG['primePayerAddMoney']['key']);

close(json_encode([
    'status' => 'ok',
    'payment' => $id,
    'sign' => $sign,
]));
