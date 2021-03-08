<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$user_id = (int)$data['user_id'];

	$formdata = $data['formdata'];
	$formdata = explode('&', $formdata);
	// $formdata = explode('=', $formdata);

	$string = '';
	foreach ($formdata as $key => $value) {
		$value = explode('=', $value);
		$string = $string . $value[0]. ',';
	}
	if($string == '' || $string == ','){
		$string = NULL;
	}

	$db->query("UPDATE `user` SET `moder_rights` = '".$string."' WHERE `id` = ".$user_id." LIMIT 1");

	

	close(json_encode(array('status' => 'ok')));

?>