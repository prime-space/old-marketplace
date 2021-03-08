<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');

	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$error = array();
	if(!$data['promocode_id'])$error[] = 'Отсутствует идентификатор промокода';
	else{
		$promocode_id = (int)$data['promocode_id'];
		$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
		if(!$bd->rows)$error[] = 'Промокод не найден или нет доступа';
	}
	if(!$data['percent'])$error[] = 'Введите процент скидки';
	elseif(!preg_match('/^\d{1,2}$/', $data['percent']) || $data['percent'] < 1 || $data['percent'] > 99)$error[] = 'Неверный формат процента скидки';

	if(!$data['products'])$error[] = 'Товары не выбраны';

	$productIds = [];
	foreach ($data['products'] as $productId) {
        $productIds[] = (int)$productId;
    }
	$request = $bd->read("SELECT id FROM product WHERE idUser = ".$user->id." AND id IN(".implode(',', $productIds).")");
	if($bd->rows !== count($productIds))$error[] = 'Товары не найдены или нет доступа';
	else{
		$request = $bd->read("SELECT id FROM promocode_products WHERE product_id IN(".implode(',', $productIds).") AND promocode_id = ".$promocode_id);
		if($bd->rows)$error[] = 'Товары уже добавлены';
	}

	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));

	$percent = (int)$data['percent'];

	foreach ($productIds as $productId) {
		$values[] = "(NULL, ".$promocode_id.", ".$productId.", ".$percent.", NOW())";
	}

	$bd->write("INSERT INTO promocode_products VALUES ".implode(',', $values).";");

	close(json_encode(array('status' => 'ok', 'message' => 'Успешно')));
?>
