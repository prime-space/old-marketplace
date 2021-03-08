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
	
	if(!$data['name'])close('{"status": "error", "message": "Введите название"}');
	
	$name = $bd->prepare($data['name'], 32);
	
	$bd->write("INSERT INTO shopsite_category VALUES(NULL, ".$user->id.", '".$name."', CURRENT_TIMESTAMP)");
	
	close(json_encode(array('status' => 'ok')));
?>