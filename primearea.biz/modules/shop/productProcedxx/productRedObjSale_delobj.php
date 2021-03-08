<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$product_obj_id = (int)$data['product_obj_id'];
	$product_id = (int)$data['product_id'];

	$request = $bd->read("SELECT idUser, typeObject, many FROM product WHERE id = '".$product_id."' LIMIT 1");
	$product_user_id = mysql_result($request,0,0);
	if($user->id !== $product_user_id)close('{"status": "error", "content": "Ошибка доступа"}');
	
	$obj_type = mysql_result($request,0,1);
	$obj_many = mysql_result($request,0,2);
	
	$table = $obj_type ? 'product_text' : 'product_file';
	
	$bd->write("
		DELETE FROM ".$table."
		WHERE 	id = ".$product_obj_id."
			AND	idUser = ".$user->id."
			AND `status` = 'sale'
		LIMIT 1
	");
	
	$refresh = $obj_many && $obj_type ? true : false;
	close(json_encode(array(
		'status' => 'ok',
		'refresh' => $refresh
	)));	
?>