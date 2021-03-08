<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	
	$error = array();
	if(!$data['promocode_id'])$error[] = 													'Отсутствует идентификатор промокода';
	else{
		$promocode_id = (int)$data['promocode_id'];
		$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
		if(!$bd->rows)$error[] = 															'Промокод не найден или нет доступа';
	}
	if($data['percentall']){
		if(!$data['percentall_value'])$error[] = 											'Введите процент скидки';
		elseif(
			!preg_match('/^\d{1,2}$/', $data['percentall_value']) 
			|| $data['percentall_value'] < 1 
			|| $data['percentall_value'] > 99
		)$error[] = 																		'Неверный формат процента скидки';		
	}else{
		if(!$data['percents_name'] || !count($data['percents_name']))$error[] = 			'Отсутствуют идентификаторы товаров';
		if(!$data['percents_value'] || !count($data['percents_value']))$error[] = 			'Отсутствуют данные о процентах';
		if(count($data['percents_name']) > 500)$error[] = 									'Максимальное кол-во позиций 500';
		elseif(count($data['percents_value']) !== count($data['percents_name']))$error[] = 	'Не совпадение количества данных';
		else{
			for($i=0;$i<count($data['percents_value']);$i++){
				if(!$data['percents_value'][$i])$error[] = 									'Введите процент скидки';
				elseif(
					!preg_match('/^\d{1,2}$/', $data['percents_value'][$i]) 
					||  $data['percents_value'][$i] < 1 
					|| $data['percents_value'][$i] > 99
				)$error[] = 																'Неверный формат процента скидки';
			}
		}
	}
	
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	if($data['percentall']){
		$percent = (int)$data['percentall_value'];
		$bd->write("
			UPDATE promocode_products 
			SET percent = ".$percent." 
			WHERE promocode_id = ".$promocode_id."
			LIMIT 500
		");
		$all = true;
	}else{
		for($i=0;$i<count($data['percents_name']);$i++){
			$data['percents_name'][$i] = explode("_", $data['percents_name'][$i]);
			$promocode_product_id = (int)$data['percents_name'][$i][1];
			$percents_value = (int)$data['percents_value'][$i];
			$bd->write("
				UPDATE promocode_products 
				SET percent = ".$percents_value." 
				WHERE 	promocode_id = ".$promocode_id."
					AND	id = ".$promocode_product_id."
				LIMIT 1
			");					
		}
		$all = false;
	}
	
	
	close(json_encode(array('status' => 'ok', 'message' => 'Сохранено', 'all' => $all)));
?>