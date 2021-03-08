<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$error = array();
	if(!preg_match('/^\d{1,2}$/', $data['percent']) && !empty($data['percent']))$error[] = 'Некорректный формат отчислений в систему';
	if(!preg_match('/^\d{1,3}$/', $data['reserv_time']) && !empty($data['reserv_time']))$error[] = 'Некорректный формат времени удержания средств';
	
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	


	$data['percent'] == NULL ? $percent = 'NULL' : $percent = $data['percent'];
	$data['reserv_time'] == NULL ? $reserv = 'NULL' : $reserv = $data['reserv_time'];
	$user_id = $data['user_id'];


	$bd->write("UPDATE `user` SET `percent_out` = {$percent}, `reservation` = {$reserv} WHERE `id`= {$user_id} ");

	
	close('{"status": "ok", "message": "Настройки успешно сохранены"}');
?>