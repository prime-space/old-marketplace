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
	
	$product_id = (int)$data['product_id'];
	
	$request =  $bd->read("SELECT `idUser` FROM `product` WHERE `id` = '".$product_id."' LIMIT 1");
	$productUserId = mysql_result($request,0,0);
	if($user->id !== $productUserId && $user->role !== 'admin')close('{"status": "error", "message": "Ошибка доступа"}');
 
	$bd->write("UPDATE `product` SET `block` = 'deleted' WHERE `id` = '".$product_id."' LIMIT 1");
  
	close(json_encode(array('status' => 'ok', 'message' => 'Товар удалён')));
?>