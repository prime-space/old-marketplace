<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['email'])close(json_encode(array('status' => 'error', 'message' => 'Введите email')));
	
	$email = $bd->prepare($data['email'], 64);
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))close(json_encode(array('status' => 'error', 'message' => 'Неверный формат email')));
	
	$bd->read("SELECT id FROM blacklist WHERE user_id = ".$user->id." AND email = '".$email."' LIMIT 1");
	if($bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Такой email уже есть в вашем списке')));
	
	$bd->write("INSERT INTO blacklist VALUES(NULL, ".$user->id.", '".$email."', NOW())");
	
	close(json_encode(array('status' => 'ok')));
?>
