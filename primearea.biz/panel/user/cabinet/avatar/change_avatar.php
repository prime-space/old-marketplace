<?php
	include_once '../../../../func/main.php';
	include_once '../../../../func/config.php';
	include_once '../../../../func/db.class.php';
	include_once '../../../../func/mysql.php';
	
	$db = new db();
	$bd = new mysql();
	$user = new user(array('user', 'moder', 'admin'));
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))exit(json_encode(array('status' => 'error','message' => 'Ошибка доступа')));
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else exit('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)exit('{"status": "error", "message": "Ошибка доступа"}');
	
	$src = $db->safesql($data['src']);
	$popup = new templating(file_get_contents('change_avatar.tpl'));
	$popup->set('{src}', '<img src="'.$src.'"> ');

	exit(json_encode(array('status' => 'ok','content' => $popup->content)));
?>