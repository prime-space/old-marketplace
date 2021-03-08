<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode(stripslashes($_POST['data']), true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$promocode_id = (int)$data['promocode_id'];
	$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
	
	$error = array();
	if(!$bd->rows)$error[] = 															'Не найдено или нет доступа';
	if(!$data['comment_name'] || !count($data['comment_name']))$error[] = 				'Отсутствуют идентификаторы кодов';
	if(!$data['comment_value'] || !count($data['comment_value']))$error[] = 			'Отсутствуют данные о комментариях';
	if(count($data['comment_name']) > 100)$error[] = 									'Максимальное кол-во позиций 100';
	if(count($data['comment_name']) !== count($data['comment_value']))$error[] = 		'Не совпадение количества данных';
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	
	for($i=0;$i<count($data['comment_name']);$i++){
		$data['comment_name'][$i] = explode("_", $data['comment_name'][$i]);
		$code_id = (int)$data['comment_name'][$i][1];
		$comment = $data['comment_value'][$i] ? "'".$bd->prepare($data['comment_value'][$i], 64)."'" : 'NULL';
		$bd->write("
			UPDATE promocode_el 
			SET comment = ".$comment." 
			WHERE 	promocode_id = ".$promocode_id."
				AND	id = ".$code_id."
			LIMIT 1
		");	
	}
	
	close(json_encode(array('status' => 'ok', 'message' => 'Сохранено')));
?>