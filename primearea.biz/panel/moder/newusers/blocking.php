<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$user_id = (int)$data['user_id'];

	$value = $data['method'] === 'block' ? 'blocked' : 'ok';

	$bd->write("
		UPDATE `user`
		SET `status` = '".$value."'
		WHERE `id` = ".$user_id."
		LIMIT 1
	");

	$bd->write("
		UPDATE `product`
		SET `block` = '".$value."'
		WHERE
			idUser = ".$user_id." AND
			block != 'deleted'
		");

	close(json_encode(array('status' => 'ok')));
?>