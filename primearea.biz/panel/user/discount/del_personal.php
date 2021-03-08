<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Отсутствуют данные"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$discount_personal_id = (int)$data['discount_personal_id'];
	
	$bd->write("DELETE FROM discount_personal WHERE id = ".$discount_personal_id." AND user_id = ".$user->id." LIMIT 1");
	
	close(json_encode(array('status' => 'ok')));
?>