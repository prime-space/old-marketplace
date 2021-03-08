<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	if($user->status == 'blocked')close('{"status": "error", "content": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "content": "Нет данных"}');
	
	$product_id = $bd->prepare($data['product_id'], 8);
	$request = $bd->read("SELECT `idUser` FROM `product` WHERE `id` = '".$product_id."' LIMIT 1");
	$productUserId = mysql_result($request,0,0);
	if($user->id !== $productUserId)close('{"status": "error", "content": "Ошибка доступа"}');

	$objType = $bd->prepare($data['objType'], 20);
	$bd->write("DELETE FROM ".$objType." WHERE `idProduct` = '".$product_id."' AND `status` = 'sale'");
	$values = array();
	if($objType == "`product_text`"){
		$text = $data['objectSale'];
		for($i=0;$i<count($text);$i++){
			$textInDB = nl2br($bd->prepare($text[$i], 43000));
			$textInDB = shop::url_replace($textInDB);
			$values[] = "(NULL, '".$user->id."', '".$product_id."', '".$textInDB."', 'sale', NULL, NULL)";
		}	
		$bd->write("INSERT INTO `product_text` VALUES ".implode(", ", $values));
	}else{
		$files = $data['objectSale'];
		for($i=0;$i<count($files);$i++){
			$dir = $bd->prepare(mb_substr($files[$i][1], -50), 64);
			$values[] = "(NULL, '".$user->id."', '".$product_id."', '".$bd->prepare($files[$i][0],64)."', '".$dir."', 'sale', NULL, NULL)";
		}
		$bd->write("INSERT INTO `product_file` VALUES ".implode(", ", $values));
	}				  

	close(json_encode(array('status' => 'ok')));

?>