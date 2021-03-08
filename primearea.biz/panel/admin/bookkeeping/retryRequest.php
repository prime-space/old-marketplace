<?php
include "../../../func/config.php";
include "../../../func/mysql.php";
include "../../../func/main.php";
include "../../../func/db.class.php";

$bd = new mysql();
$db = new db();
$user = new user();

if (!$user->verify($_COOKIE['crypt'], "admin,moder")) {
    close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
}

if($_POST['data'])$data = json_decode($_POST['data'], true);
else close('{"status": "error", "message": "Нет данных"}');

if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

if(!$data['id'])close('{"status": "error", "message": "Недостаточно данных"}');

$id = (int)$data['id'];

$cashout = $db->super_query("SELECT currency, status FROM cashout WHERE id = $id");

if (empty($cashout['currency'])) {
    close('{"status": "error", "message": "Запрос не найден"}');
}
if ($cashout['status'] !== 'error') {
    close('{"status": "error", "message": "Запрос должен быть в статусе \"ошибка\""}');
}

foreach ($CONFIG['cashout'] as $type => $parameters) {
    if ($parameters['curr'] === $cashout['currency']) {
        break;
    }
}

$db->query("UPDATE cashout SET status = 'new' WHERE id = $id");
$dbQueue = new db(0, 'queue');
$message = json_encode(['aggregator' => $type, 'id' => $id,]);
$dbQueue->query("CALL sp_create_message('withdraw', '$message')");

close('{"status": "ok"}');
