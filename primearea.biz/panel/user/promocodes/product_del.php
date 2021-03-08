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
	
	$promocode_product_id = (int)$data['promocode_product_id'];
	
	
	$bd->write("
		DELETE 
		FROM promocode_products
		WHERE 	id = ".$promocode_product_id."
			AND promocode_id IN (SELECT id FROM promocodes WHERE user_id = ".$user->id.")
		LIMIT 1
	");
	
	
	close(json_encode(array('status' => 'ok')));
?>