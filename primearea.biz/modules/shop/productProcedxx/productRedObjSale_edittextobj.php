<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$product_obj_id = (int)$data['product_obj_id'];
	$text = $bd->prepare(nl2br($data['text']), 43000);
	$text = shop::url_replace($text);	
	
	$bd->write("
		UPDATE product_text 
		SET text = '".$text."' 
		WHERE 	id = ".$product_obj_id."
			AND idUser = ".$user->id."
			AND status = 'sale'
		LIMIT 1"
	);


	close(json_encode(array(
		'status' => 'ok'
	)));	
?>