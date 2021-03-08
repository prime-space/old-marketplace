<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['id'])close('{"status": "error", "message": "Недостаточно данных"}');
	if(!isset($data['protect']))close('{"status": "error", "message": "Недостаточно данных"}');
	if(!$data['protect'] && !$data['code'])close('{"status": "error", "message": "Введите код протекции"}');
	
	$id = (int)$data['id'];
	
	$protect = $data['protect'] ? 'Без протекции' : $bd->prepare($data['code'], 16);
	
	$bd->write("
		UPDATE `cashout` 
		SET 
			`protect` = '".$protect."',
			`status` = 'performed'
		WHERE
			`id` = '".$id."' AND
			`status` IN ('process', 'error', 'new')
	");
	$bd->write("
		UPDATE `yandex_autopayments`
		SET 
			result = 'ok'
		WHERE
			cashout_id = '".$id."'
	");

	close('{"status": "ok"}');
?>
