<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/db.class.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$product_id = (int)$data['product_id'];

	$request = $bd->read("SELECT idUser, typeObject, many FROM product WHERE id = '".$product_id."' LIMIT 1");
	$product_user_id = mysql_result($request,0,0);
	if($user->id !== $product_user_id)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$obj_type = mysql_result($request,0,1);
	
	$table = $obj_type ? 'product_text' : 'product_file';
	
	$rating =  $bd->read("SELECT `rating` FROM `user` WHERE `id` = '".$user->id."' LIMIT 1");
	$rating_val =  $bd->read("SELECT `value` FROM `setting` WHERE `id` = 9 LIMIT 1");
	$_rateing = mysql_result($rating,0,0);
	$_rateing_val = mysql_result($rating_val,0,0);
	
	if($_rateing >= $_rateing_val || $user->active_privileges()){
		$mod = 'ok';
	}else{
		$mod = 'check';
	}

	$bd->write("UPDATE `product` 
			SET	moderation = '".$mod."'
			WHERE `id` = '".$product_id."'"
	);
	
	
	
	$bd->write("
		DELETE FROM ".$table." 
		WHERE 	idUser = ".$user->id." 
			AND	idProduct = ".$product_id."
			AND status = 'sale'
		LIMIT 500");		
	
	close(json_encode(array(
		'status' => 'ok'
	)));	
?>