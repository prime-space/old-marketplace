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
	
	if(!$data['name'])close('{"status": "error", "message": "Введите название"}');
	
	$id = (int)$data['id'] ? (int)$data['id'] : 'NULL';
	
	$name = $bd->prepare($data['name'],64);
	$muu = shop::convertToMuu($name);
	$request = $bd->write("INSERT INTO `productgroup` VALUES (NULL, ".$id.", '".$name."', '".$muu."')");

	
	close('{"status": "ok"}');
?>