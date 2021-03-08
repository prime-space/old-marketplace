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
	
	$product_id = (int)$data['product_id'];
  
	$request = $bd->read("SELECT idUser FROM product WHERE id = '".$product_id."' LIMIT 1"); 
	$userIdCheck = mysql_result($request,0,0);

	if($user->id !== $userIdCheck)close('{"status": "error", "message": "Ошибка доступа"}');
 
	$adddel = $data['method'] === 'add' ? 0 : -1;
  
	$bd->write("UPDATE product SET `shopsite` = ".$adddel." WHERE id = ".$product_id." LIMIT 1");

	close('{"status": "ok"}');
?>