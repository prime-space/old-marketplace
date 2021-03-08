<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$product_id = (int)$data['product_id'];

	$request = $bd->read("SELECT idUser, typeObject, many FROM product WHERE id = '".$product_id."' LIMIT 1");
	$product_user_id = mysql_result($request,0,0);
	if($user->id !== $product_user_id)close('{"status": "error", "content": "Ошибка доступа"}');
	
	$obj_type = mysql_result($request,0,1);
	
	$table = $obj_type ? 'product_text' : 'product_file';
	
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