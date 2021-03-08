<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$data['priv_amount_1'] = (int)$data['priv_amount_1'];
	$data['priv_amount_2'] = (int)$data['priv_amount_2'];
	$data['priv_amount_3'] = (int)$data['priv_amount_3'];
	$data['priv_amount_4'] = (int)$data['priv_amount_4'];

	$data['up_count_2'] = (int)$data['up_count_2'];
	$data['up_count_3'] = (int)$data['up_count_3'];
	$data['up_count_4'] = (int)$data['up_count_4'];

	$data['up_interval_2'] = (int)$data['up_interval_2'];
	$data['up_interval_3'] = (int)$data['up_interval_3'];
	$data['up_interval_4'] = (int)$data['up_interval_4'];
	
	$data['com_day_3'] = (int)$data['com_day_3'];
	$data['com_day_4'] = (int)$data['com_day_4'];

	$data['system_com_4'] = number_format((float)$data['system_com_4'], 1, '.', '');
	
	$error = array();
	if(!$data['priv_amount_1'] || !$data['priv_amount_2'] || !$data['priv_amount_3'] || !$data['priv_amount_4'])$error[] = 'Введите цену';
	elseif(!preg_match('/^\d{1,6}$/', $data['priv_amount_1']) || !preg_match('/^\d{1,6}$/', $data['priv_amount_2']) || !preg_match('/^\d{1,6}$/', $data['priv_amount_3']) || !preg_match('/^\d{1,6}$/', $data['priv_amount_4']) )$error[] = 'Некорректная цена';
	if(!$data['up_count_2'] || !$data['up_count_3'] || !$data['up_count_4'])$error[] = 'Введите число поднятий';
	elseif(!preg_match('/^\d{1,2}$/', $data['up_count_2']) || !preg_match('/^\d{1,2}$/', $data['up_count_3']) || !preg_match('/^\d{1,2}$/', $data['up_count_4']))$error[] = 'Некорректный формат числа поднятий';
	if(!$data['up_interval_2'] || !$data['up_interval_3'] || !$data['up_interval_3'])$error[] = 'Введите интервал поднятий';
	elseif(!preg_match('/^\d{1,2}$/', $data['up_interval_2']) || !preg_match('/^\d{1,2}$/', $data['up_interval_3']) || !preg_match('/^\d{1,2}$/', $data['up_interval_4']))$error[] = 'Некорректный формат интервала поднятий';
	if(!$data['com_day_3'] || !$data['com_day_4'])$error[] = 'Введите срок удержания средств';
	elseif(!preg_match('/^\d{1,2}$/', $data['com_day_3']) || !preg_match('/^\d{1,2}$/', $data['com_day_4']) )$error[] = 'Некорректный формат удержания средств';
	if(!$data['system_com_4'])$error[] = 'Введите комиссию системы';
	elseif(!preg_match('/^\d{1,2}(\.\d)?$/', $data['system_com_4']) || $data['system_com_4'] == 0)$error[] = 'Некорректный формат комиссии системы';

	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	

	unset($data['token']);
	
	$data = [
		'priv_amount_1' => $data['priv_amount_1'],
		'priv_amount_2' => $data['priv_amount_2'],
		'priv_amount_3' => $data['priv_amount_3'],
		'priv_amount_4' => $data['priv_amount_4'],
		'up_count_2' => $data['up_count_2'],
		'up_count_3' => $data['up_count_3'],
		'up_count_4' => $data['up_count_4'],
		'up_interval_2' => $data['up_interval_2'],
		'up_interval_3' => $data['up_interval_3'],
		'up_interval_4' => $data['up_interval_4'],
		'com_day_3' => $data['com_day_3'],
		'com_day_4' => $data['com_day_4'],
		'system_com_4' => $data['system_com_4']
	];
	$result = $db->safesql(serialize($data));
	
	$db->query("UPDATE setting SET value = '".$result."' WHERE ids = 12 LIMIT 1");

	close('{"status": "ok", "message": "Настройки успешно сохранены"}');
?>