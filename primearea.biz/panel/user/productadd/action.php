<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";
	include "../../../func/product.class.php";
	include "../../../func/Sphinx.php";
	include "../../../func/logs.class.php";
	include "../../../modules/currency/currclass.php";
	
	$bd = new mysql();
	$db = new db();
	$user = new user();
	$current_convert = new current_convert();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$promocode_id = 0;
	
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
	$partner = (int)$data['partner'];
	if(!preg_match('/\d{1,2}/', $data['partner']) || $partner < 0 || $partner > 50)$error[] = 'Комиссия партнера должна быть от 0% до 50%';
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
	

	$rating =  $bd->read("SELECT `rating` FROM `user` WHERE `id` = '".$user->id."' LIMIT 1");
	$rating_val =  $bd->read("SELECT `value` FROM `setting` WHERE `id` = 9 LIMIT 1");
	$_rateing = mysql_result($rating,0,0);
	$_rateing_val = mysql_result($rating_val,0,0);

	if($_rateing >= $_rateing_val || $user->active_privileges()){
		$mod = 'ok';
	}else{
		$mod = 'refuse';
	}
	
	$product_id = $bd->write("
		INSERT INTO `product`
		( `idUser`, `name`, `group`, `many`, `price`, 
		  `curr`, `promocode_id`, `descript`, `info`, `typeObject`, 
		  `status`, `sale`, `date`, `block`, `shopsite`, `picture`, 
		  `showing`, `partner`, `moderation`
		) 
		VALUES (
			'".$user->id."', 
			'".$name."', 
			'".$group."', 
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
			0,
			".$partner.",
			'".$mod."'
	)");

    product::searchIndex(['id' => $product_id, 'name' => $data['name']], true);

	close(json_encode(array('status' => 'ok', 'product_id' => $product_id, 'message' => 'Товар добавлен, необходимо загрузить объекты продажи')));
