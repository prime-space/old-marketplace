<?php

	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../modules/currency/currclass.php";
	
	$bd = new mysql();
	$db = new db();
	$user = new user();
	$data = json_decode($_POST['data'], true);

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(['status' => 'error', 'message' => 'Ошибка доступа']));
	if($data['token'] !== $user->token)close(json_encode(["status" => "error", "message" => "Ошибка доступа"]));
	if(!$user->active_privileges())close(json_encode(["status" => "error", "message" => "Ошибка доступа"]));

	$request = $db->super_query("SELECT value FROM setting WHERE ids = 12 ORDER BY id LIMIT 3");
	$settings = unserialize($request['value']);
	$product = (int)$data['product'];

	if(!$product)close(json_encode(["status" => "error", "message" => "Выберите товар"]));

	$interval = $settings['up_interval_'.$user->priv_type()];
	$up_count = $settings['up_count_'.$user->priv_type()];

	$count = $user->up_interval($interval, $up_count);

	if($user->up_interval(NULL,NULL, $product) != 0)close(json_encode(['status' => 'error', 'message' => 'Товар уже поднят']));
	
	if($count < $up_count ){
		$request = $db->query("INSERT INTO user_privileges_product_up VALUES(NULL, ".$product.", ".$user->id.", ADDDATE(NOW(), INTERVAL ".$interval." DAY))");
		$request = $db->query("UPDATE product set `date` = NOW() WHERE id = ".$product." AND idUser = ".$user->id." LIMIT 1");

		close(json_encode(['status' => 'ok']));
	}else{
		close(json_encode(["status" => "error", "message" => "Нет доступных поднятий"]));
	}
						

	