<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	include "../../../func/db.class.php";
	include "../../../func/duplicate.class.php";
	include "../../../func/transactions.class.php";

	$bd = new mysql();
	$user = new user();
	
	$db = new db();
	$transactions = new transactions();
	$duplicate = new duplicate('createAd');
	
	if(!$duplicate->access)exit('{"status": "error", "message": "Попробуйте позже"}');

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else $duplicate->end('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');
	
	$product_id = (int)$data['product_id'];
	$duration_placement_graphic_field_ad = $bd->prepare((int)$data['duration_placement_graphic_field_ad'],4);
	$amount_click_text_ad = $bd->prepare( number_format( (float)$data['amount_click_text_ad'], 2 ), 8 );
	$type = $bd->prepare($data['type'],8);
	
	$request = $bd->read("SELECT id FROM product WHERE id = ".$product_id." AND idUser = ".$user->id." AND block = 'ok' LIMIT 1");
	if(!$bd->rows)$duplicate->end('{"status": "error", "message": "Выберите продукт"}');
	if(!$type)$duplicate->end('{"status": "error", "message": "Выберите тип объявления"}');
	$request = $bd->read("SELECT id FROM ad WHERE product_id = ".$product_id." AND status = 'Активно' LIMIT 1");
	if($bd->rows)$duplicate->end('{"status": "error", "message": "Данный продукт уже участвует в рекламной компании"}');
	
	$request = $bd->read("SELECT value FROM setting WHERE ids IN (4,5) ORDER BY id LIMIT 2");
	$price_day = mysql_result($request,0,0);
	$price_click = (float)mysql_result($request,1,0);
	
	if($type == 'graphic'){
		$amount = $price_day * $duration_placement_graphic_field_ad;
		if($amount < 1)$duplicate->end('{"status": "error", "message": "Ошибка стоимости"}');
		
		$transactionId = $transactions->create(array(
			'user_id' => $user->id,
			'type' => 0,
			'method' => 'createAd',
			'method_id' => 0,
			'currency' => 4,
			'amount' => '-'.$amount
		));
		if(!$transactionId)$duplicate->end('{"status": "error", "message": "Недостаточно средств"}');
		
		$adId = $bd->write("INSERT INTO ad VALUES(	NULL, 
											".$user->id.", 
											".$product_id.", 
											'Графическое', 
											".$price_day.", 
											".$duration_placement_graphic_field_ad.",
											0,
											'Активно',
											CURRENT_TIMESTAMP)");
		
		$transactions->updateMethodId($transactionId, $adId);
		
	}else{
		if($amount_click_text_ad < $price_click)$duplicate->end('{"status": "error", "message": "Ошибка стоимости"}');
		if(!$transactions->checkFounds($user->id, '-'.$amount_click_text_ad))$duplicate->end('{"status": "error", "message": "Недостаточно средств"}');
		$bd->write("INSERT INTO ad VALUES(	NULL, 
											".$user->id.", 
											".$product_id.", 
											'Текстовое', 
											".$amount_click_text_ad.", 
											NULL,
											0,
											'Активно',
											CURRENT_TIMESTAMP)");		
	}
	$duplicate->end('{"status": "ok"}');
?>