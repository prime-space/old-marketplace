<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');

	$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/review/addPopup.tpl");
	$tpl = str_replace("{id}", $data['review_id'], $tpl);

	close(json_encode(array('status' => 'ok', 'content' => $tpl)));
?>