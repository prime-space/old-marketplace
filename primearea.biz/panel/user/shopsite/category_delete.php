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
	
	$category_id = (int)$data['category_id'];
	
	$bd->write("UPDATE product SET shopsite = 0 WHERE shopsite = ".$category_id." AND idUser = ".$user->id);
	
	$bd->write("DELETE FROM shopsite_category WHERE id = ".$category_id." AND user_id = ".$user->id." LIMIT 1");
	
	close(json_encode(array('status' => 'ok')));
?>