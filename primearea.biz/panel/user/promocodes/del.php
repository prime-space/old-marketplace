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
	if(!$data['promocode_id'])close(json_encode(array('status' => 'error', 'message' => 'Отсутствует идентификатор промокода')));
	else{
		$promocode_id = (int)$data['promocode_id'];
		$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
		if(!$bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Промокод не найден или нет доступа')));
	}
	
	
	$bd->write("DELETE FROM promocodes WHERE id = ".$promocode_id." LIMIT 1");
	$bd->write("DELETE FROM promocode_el WHERE promocode_id = ".$promocode_id." AND used = 0 AND issued = 0");
	$bd->write("DELETE FROM promocode_products WHERE promocode_id = ".$promocode_id);
	$bd->write("UPDATE product SET promocode_id = 0 WHERE promocode_id = ".$promocode_id);
	
	
	close(json_encode(array('status' => 'ok', 'message' => 'Удален')));
?>