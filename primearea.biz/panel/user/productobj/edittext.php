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
	

	
	$product_obj_id = (int)$data['product_obj_id'];
	$text = $bd->prepare(nl2br($data['text']), 43000);
	$text = shop::url_replace($text);	
	
	$request =  $bd->read("SELECT `idProduct` FROM product_text WHERE `id` = '".$product_obj_id."' LIMIT 1");
	$productId = mysql_result($request,0,0);
	

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
			WHERE `id` = '".$productId."'"
	);

	
	$bd->write("
		UPDATE product_text 
		SET text = '".$text."' 
		WHERE 	id = ".$product_obj_id."
			AND idUser = ".$user->id."
			AND status = 'sale'
		LIMIT 1"
	);


	close(json_encode(array(
		'status' => 'ok',
		'message' => 'Сохранено'
	)));	
?>