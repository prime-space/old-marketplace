<?php
	session_start();
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Отсутствуют данные"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['captcha'] || !$_SESSION['captcha'] || $_SESSION['captcha'] !== $data['captcha']){
		unset($_SESSION["captcha"]);
		close('{"status": "error", "message": "Неверное число с картинки"}');
	}
	unset($_SESSION["captcha"]);
	
	$error = array();
	if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))$error[] = 'Неверный формат email';
	else{
		$email = $bd->prepare($data['email'], 32);
		$request = $bd->read("SELECT id FROM discount_personal WHERE email = '".$email."' AND user_id = ".$user->id." LIMIT 1");
		if($bd->rows)$error[] = 'Такой email уже есть в базе';
	}
	
	$type = $data['type'] ? 'percent' : 'money';
	if($type == 'percent' && (!preg_match('/^\d{1,2}$/', $data['percent']) || $data['percent'] < 1 || $data['percent'] > 50))$error[] = 'Фиксированная скида должна быть от 1% до 50%';
	if($type == 'money' && (!preg_match('/^\d{1,16}$/', $data['money']) || $data['money'] < 1 || $data['money'] > 9999))$error[] = 'Сумма должна быть положительным числом до 10000';
	
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	$percent = 'NULL';
	$money =  'NULL';
	if($type == 'percent')$percent = (int)$data['percent'];
	else $money = shop::price_format($data['money']);

	
	$bd->write("INSERT INTO discount_personal VALUES(NULL, ".$user->id.", '".$email."', '".$type."', ".$percent.", ".$money.", NOW())");
		
	close(json_encode(array('status' => 'ok', 'message' => 'Скидка добавлена')));
?>