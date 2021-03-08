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
	
	if(!$data['id'])close('{"status": "error", "message": "Отсутствует идентификатор"}');
	
	$id = (int)$data['id'];
	
	$request = $bd->read("SELECT `subgroup` FROM `productgroup` WHERE `id` = ".$id);
	$subgroup = mysql_result($request,0,0);
	if($subgroup == NULL)$subgroup = 0;
  
	$bd->write("DELETE FROM `productgroup` WHERE `id` = '".$id."' LIMIT 1");
	$bd->write("DELETE FROM `productgroup` WHERE `subgroup` = '".$id."'");
	
	close(json_encode(array('status' => 'ok', 'subgroup' => $subgroup)));
?>