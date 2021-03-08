<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Отсутствуют данные"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!isset($data['fields']))close('{"status": "error", "message": "Отсутствуют данные"}');
	
	$insert = array();
	foreach($data['fields'] as $v){
		if(!$v['money'])close('{"status": "error", "message": "Введите сумму"}');
		if(!preg_match('/^\d{1,8}(.\d{1,2})?$/', $v['money']) || !(float)$v['money'])close('{"status": "error", "message": "Неверный формат суммы"}');
		if(!$v['percent'])close('{"status": "error", "message": "Введите процент"}');
		if(!preg_match('/^\d{1,2}$/', $v['percent']) || !(int)$v['percent'])close('{"status": "error", "message": "Неверный формат процента"}');
		$insert[] = "(NULL, ".$v['money'].", ".$v['percent'].", ".$user->id.")";
		if(count($insert) == 10)break;
	}

	$bd->write("DELETE FROM discount WHERE userId = ".$user->id." LIMIT 10");

	if(count($insert))$bd->write("INSERT INTO `discount` VALUES ".implode(', ', $insert));


	close(json_encode(array('status' => 'ok')));
?>