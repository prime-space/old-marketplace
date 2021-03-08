<?php
	include "../../../func/config.php";
	include "../../../func/db.class.php";
	include "../../../func/user.class.php";
	include "../../../func/transactions.class.php";
	include "../../../func/tpl.class.php";
	
	$db = new db();
	$user = new user(array('admin'));
	$transactions = new transactions();
	
	if(!$user->id)$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else exit('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');
	
	$userId = (int)$data['userId'];
	
	exit(json_encode($transactions->userTransactions($userId)));
?>
