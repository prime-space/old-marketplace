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
	
	$promocode_id = 0;
	
	$error = array();
	if(!$data['group'])$error[] = 'Выберите группу';
	if(!$data['name'])$error[] = 'Введите название';
	if(!$data['price'])$error[] = 'Укажите стоимость';
	else if(!shop::reg_exp($data['price'], 'money'))$error[] = 'Сумма должна быть в формате 136.50';
	if(!shop::reg_exp($data['current'], 'current'))$error[] = 'Неверное значение валюты';
	if($data['promocodes']){
		if(!$data['promocodes_val'])$error[] = 'Выберите выпуск промо-кодов';
		else{
			$promocode_id = (int)$data['promocodes_val'];
			$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
			if(!$bd->rows)$error[] = 'Данный промо-код недоступен';
		}
	}
	if(!$data['description'])$error[] = 'Введите описание товара';
	if(!$data['info'])$error[] = 'Введите дополнительную информацию';
	
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	$group = (int)$data['group'];
	$obj_many = $data['obj_many'] ? 0 : 1;//0 - один; 1 - много;
	$obj_type = $data['obj_type'] ? 0 : 1;//0 - файлы; 1 - текст; 
	$price = shop::price_format($data['price']);
	$current = (int)$data['current'];
	$name = $bd->prepare($data['name'], 128);
	$description = $bd->prepare(nl2br($data['description']), 3000);
	$description = shop::url_replace($description);
	$info = $bd->prepare(nl2br($data['info']), 3000);
	$info = shop::url_replace($info);
	$picture = (int)$data['picture'];
	
	$product_id = $bd->write("
		INSERT INTO `product` 
		VALUES (
			NULL, 
			'".$user->id."', 
			'".$name."', 
			'".$group."', 
			NULL, 
			".$obj_many.", 
			".$price.", 
			'".$current."',
			".$promocode_id.",
			'".$description."', 
			'".$info."', 
			".$obj_type.", 
			'sale', 
			0, 
			NOW(), 
			'ok', 
			-1, 
			".$picture.", 
			1
	)");
	close(json_encode(array('status' => 'ok', 'product_id' => $product_id)));
?>