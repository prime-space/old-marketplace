<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "content": "Нет данных"}');
	
	$product_id = (int)$data['product_id'];
	$promocode_id = 0;
	
	$request =  $bd->read("SELECT `idUser` FROM `product` WHERE `id` = '".$product_id."' LIMIT 1");
	$productUserId = mysql_result($request,0,0);
	if($user->id !== $productUserId && $user->role !== 'admin')close('{"status": "error", "content": "Ошибка доступа"}');
 
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
	$name = $bd->prepare($data['name'], 128);
	$price = shop::price_format($data['price']);
	$current = (int)$data['current'];
	$description = $bd->prepare(nl2br($data['description']), 3000);
	$description = shop::url_replace($description);
	$info = $bd->prepare(nl2br($data['info']), 3000);  
	$info = shop::url_replace($info);
	$picture = (int)$data['picture'];
  
	$bd->write("	UPDATE `product` 
			SET	`name` = '".$name."', 
				`group` = '".$group."', 
				`price` = '".$price."', 
				`curr` = '".$current."', 
				`descript` = '".$description."', 
				`info` = '".$info."', 
				promocode_id = ".$promocode_id.",
				`picture` = '".$picture."' 
			WHERE `id` = '".$product_id."'");
  
  close(json_encode(array('status' => 'ok')));
?>