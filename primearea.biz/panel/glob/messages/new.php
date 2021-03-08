<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/db.class.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	
	if(in_array($user->role, array('admin', 'moder'))){

		if( $user->role == 'moder' && $user->checkModerRight('messages')){
			$modering = true;
		}elseif($user->role == 'admin'){
			$modering = true;
		}else{
			$modering = false;
		}

	}else{
		$modering = false;
	}


	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['title'])close('{"status": "error", "message": "Введите тему"}');
	if(!$data['text'])close('{"status": "error", "message": "Введите текст"}');
	
	$title = $bd->prepare($data['title'], 256);
	$text = $bd->prepare(nl2br($data['text']), 1600);  
	$to = (int)$data['to'];

	
	$to = $modering ? $to : 0;
	$from = $modering ? 0 : $user->id;
  
	$request = $bd->read("SELECT `topic` FROM `help` ORDER BY `topic` DESC LIMIT 1");
	$topic = $bd->rows ? mysql_result($request,0,0) + 1 : 1;


	$bd->write("INSERT INTO `help` VALUES (NULL, ".$from.", ".$to.", ".$topic.", '".$title."', '".$text."', NOW(), 0)");
  
	close(json_encode(array('status' => 'ok')));
?>