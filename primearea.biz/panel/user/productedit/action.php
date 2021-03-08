<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/db.class.php";
	include "../../../func/partner.class.php";
    include "../../../func/product.class.php";
    include "../../../func/Sphinx.php";
    include "../../../func/logs.class.php";
	include "../../../modules/currency/currclass.php";
	
	$db = new db();
	$bd = new mysql();
	$user = new user();
	$current_convert = new current_convert();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$product_id = (int)$data['product_id'];
	$promocode_id = 0;
	
	$request =  $bd->read("SELECT `idUser` FROM `product` WHERE `id` = '".$product_id."' LIMIT 1");
	$productUserId = mysql_result($request,0,0);
	if($user->id !== $productUserId && $user->role !== 'admin')close('{"status": "error", "message": "Ошибка доступа"}');
 
	$error = array();
	if(!$data['group'])$error[] = 'Выберите группу';
	if(!$data['name'])$error[] = 'Введите название';
	if(!$data['price'])$error[] = 'Укажите стоимость';
	else if(!shop::reg_exp($data['price'], 'money'))$error[] = 'Сумма должна быть в формате 136.50';
	if(!shop::reg_exp($data['current'], 'current'))$error[] = 'Неверное значение валюты';
		
	if($data['price'] && shop::reg_exp($data['current'], 'current')){
		$priceCur = $current_convert->curr($data['price'],$data['current'],4, false);
		if($priceCur < 3){
			$error[] = 'Минимальная сумма 3 руб.';
		}
	}

	if($data['promocodes']){
		if(!$data['promocodes_val'])$error[] = 'Выберите выпуск промо-кодов';
		else{
			$promocode_id = (int)$data['promocodes_val'];
			$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
			if(!$bd->rows)$error[] = 'Данный промо-код недоступен';
		}
	}
	$partnerFee = (int)$data['partner'];
	if(!preg_match('/\d{1,2}/', $data['partner']) || $partnerFee < 0 || $partnerFee > 50)$error[] = 'Комиссия партнера должна быть от 0% до 50%';
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
  
	$partner = new partner();
	$partner->productEdit($product_id, $partnerFee);
  	

	$rating =  $bd->read("SELECT `rating` FROM `user` WHERE `id` = '".$user->id."' LIMIT 1");
	$rating_val =  $bd->read("SELECT `value` FROM `setting` WHERE `id` = 9 LIMIT 1");
	$_rateing = mysql_result($rating,0,0);
	$_rateing_val = mysql_result($rating_val,0,0);

	if($_rateing >= $_rateing_val || $user->active_privileges()){
		$mod = 'ok';
	}else{
		$mod = 'check';
	}

	$bd->write("	UPDATE `product` 
			SET	`name` = '".$name."', 
				`group` = '".$group."', 
				`price` = '".$price."', 
				`curr` = '".$current."', 
				`descript` = '".$description."', 
				`info` = '".$info."', 
				promocode_id = ".$promocode_id.",
				`picture` = '".$picture."',
				partner = ".$partnerFee.",
				moderation = '".$mod."'
			WHERE `id` = '".$product_id."'");

    product::searchIndex(['id' => $product_id, 'name' => $data['name']]);

  close(json_encode(array('status' => 'ok', 'message' => 'Товар успешно отредактирован')));
