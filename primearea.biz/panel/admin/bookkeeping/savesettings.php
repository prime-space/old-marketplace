<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$error = array();
	if(!$data['fee'])$error[] = 'Введите процент отчислений в систему';
	elseif(!preg_match('/^\d{1,2}$/', $data['fee']))$error[] = 'Некорректный формат отчислений в систему';
	if(!$data['time_retention'])$error[] = 'Введите время удержания средств';
	elseif(!preg_match('/^\d{1,3}$/', $data['time_retention']))$error[] = 'Некорректный формат времени удержания средств';
	if(!$data['graphic_ad_price'])$error[] = 'Введите стоимость графического объявления';
	elseif(!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $data['graphic_ad_price']) || (float)$data['graphic_ad_price'] == 0)$error[] = 'Некорректный формат стоимости графического объявления';
	if(!$data['text_ad_price'])$error[] = 'Введите стоимость текстового объявления';
	elseif(!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $data['text_ad_price']) || (float)$data['text_ad_price'] == 0)$error[] = 'Некорректный формат стоимости текстового объявления';
	if(!isset($data['wmx2']))$error[] = 'Отсутствуют данные по автовыплатам';
	if(!isset($data['yandex_autopayments']))$error[] = 'Отсутствуют данные по автовыплатам яндексам';
	if(!$data['wmx2_fee'])$error[] = 'Введите коммиссию автовыплат';
	elseif(!preg_match('/^\d{1,2}(\.\d)?$/', $data['wmx2_fee']) || (float)$data['wmx2_fee'] == 0)$error[] = 'Некорректный формат коммиссии автовыплат';
	if(!$data['yandex_autopayments_fee'])$error[] = 'Введите коммиссию автовыплат';
	elseif(!preg_match('/^\d{1,2}(\.\d)?$/', $data['yandex_autopayments_fee']) || (float)$data['yandex_autopayments_fee'] == 0)$error[] = 'Некорректный формат коммиссии автовыплат яндекса';
	if(!$data['rate_moder'])$error[] = 'Введите значение баллов';
	elseif(!preg_match('/^\d{1,3}$/', $data['rate_moder']))$error[] = 'Некорректный формат баллов';
	if(!$data['paypal_fee_percent'])$error[] = 'Введите процент наценки';
	elseif(!preg_match('/^\d{1,3}(\.\d)?$/', $data['paypal_fee_percent']))$error[] = 'Некорректный формат наценки';
	if(!isset($data['qiwi_fee_percent']) || $data['qiwi_fee_percent'] === '')$error[] = 'Введите комиссию платежей qiwi';
	elseif(!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $data['qiwi_fee_percent']))$error[] = 'Некорректный формат комиссии платежей qiwi';
	if(!$data['paypal_fee_val'])$error[] = 'Введите значение добавочной стоимости';
	elseif(!preg_match('/^\d{1,3}$/', $data['paypal_fee_val']))$error[] = 'Некорректный формат добавочной стоимости';
	if(!isset($data['qiwi_autopayments']))$error[] = 'Отсутствуют данные по автовыплатам qiwi';
	if(!$data['qiwi_autopayments_fee'])$error[] = 'Введите коммиссию автовыплат qiwi';
	elseif(!preg_match('/^\d{1,2}(\.\d)?$/', $data['qiwi_autopayments_fee']) || (float)$data['yandex_autopayments_fee'] == 0)$error[] = 'Некорректный формат коммиссии автовыплат qiwi';
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	

	$wmx2 = $data['wmx2'] ? 1 : 0;
	$yandex_autopayments = $data['yandex_autopayments'] ? 1 : 0;
	$qiwi_autopayments = $data['qiwi_autopayments'] ? 1 : 0;

	$result = [
		'fee' => $db->safesql($data['fee']),
		'time_retention' => $db->safesql($data['time_retention']),
		'graphic_ad_price' => $db->safesql($data['graphic_ad_price']),
		'text_ad_price' => $db->safesql($data['text_ad_price']),
		'wmx2' =>$wmx2,
		'wmx2_fee' => $db->safesql($data['wmx2_fee']),
		'rate_moder' => $db->safesql($data['rate_moder']),
		'paypal_fee_percent' => $db->safesql($data['paypal_fee_percent']),
		'paypal_fee_val' => $db->safesql($data['paypal_fee_val']),
		'yandex_autopayments_fee' => $db->safesql($data['yandex_autopayments_fee']),
		'yandex_autopayments' => $yandex_autopayments,
		'qiwi_autopayments_fee' => $db->safesql($data['qiwi_autopayments_fee']),
		'qiwi_autopayments' => $qiwi_autopayments,
	];

	$db->query("UPDATE setting SET value = '".$result['fee']."' WHERE ids = 2 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['time_retention']."' WHERE ids = 3 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['graphic_ad_price']."' WHERE ids = 4 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['text_ad_price']."' WHERE ids = 5 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['wmx2']."' WHERE ids = 7 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['wmx2_fee']."' WHERE ids = 6 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['rate_moder']."' WHERE ids = 9 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['paypal_fee_percent']."' WHERE ids = 10 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['paypal_fee_val']."' WHERE ids = 11 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['yandex_autopayments']."' WHERE ids = 15 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['yandex_autopayments_fee']."' WHERE ids = 16 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['qiwi_autopayments']."' WHERE ids = 18 LIMIT 1");
	$db->query("UPDATE setting SET value = '".$result['qiwi_autopayments_fee']."' WHERE ids = 19 LIMIT 1");

	$db->query("UPDATE pay_method SET fee = '".$db->safesql($data['qiwi_fee_percent'])."' WHERE id = 37");

	close('{"status": "ok", "message": "Настройки успешно сохранены"}');
?>
