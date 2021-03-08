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
	$obj_many = mysql_result($request,0,2);	
	
	if($obj_type == 0){
		$file_count = count($data['file']['dir']);
		if($file_count){
			$file_insert = array();
			for($i=0;$i<$file_count;$i++){
				$file_dir = $bd->prepare($data['file']['dir'][$i], 64);
				$file_name = $bd->prepare($data['file']['name'][$i], 64);
				$file_insert[] = "(NULL, ".$user->id.", ".$product_id.", '".$file_name."', '".$file_dir."', 'sale', NULL, NULL)";
			}
			$bd->write("INSERT INTO product_file VALUES ".implode(", ", $file_insert));
		}
	}else{
		$texts_count = count($data['texts']);
		if(!$texts_count)close('{"status": "error", "content": "Нет полей или какое-то поле не заполнено"}');
		$texts_count = $obj_many ? 1 : $texts_count;
		$texts_insert = array();
		for($i=0;$i<$texts_count;$i++){
			if(!$data['texts'][$i])close('{"status": "error", "content": "Заполнены не все поля"}');
			$text = $bd->prepare(nl2br($data['texts'][$i]), 43000);
			$text = shop::url_replace($text);
			$texts_insert[] = "(NULL, ".$user->id.", ".$product_id.", '".$text."', 'sale', NULL, NULL)";
		}
		$bd->write("INSERT INTO product_text VALUES ".implode(", ", $texts_insert));
	}
	
	
	close(json_encode(array(
		'status' => 'ok'
	)));	
?>