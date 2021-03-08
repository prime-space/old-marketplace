<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	$modering = in_array($user->role, array('admin', 'moder')) ? true : false;
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['topic'])close('{"status": "error", "message": "Отсутствуют данные"}');
	if(!$data['text'])close('{"status": "error", "message": "Введите текст"}');
	
	$topic = (int)$data['topic'];
	$request = $bd->read("SELECT `to`, `from` FROM `help` WHERE `topic` = ".$topic." ORDER BY id LIMIT 1");
	$to = mysql_result($request,0,0);
	$from = mysql_result($request,0,1);
  
	if(!($user->id == $to || $user->id == $from || $modering))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
  
	$text = $bd->prepare(nl2br($data['text']), 1600);
	
	if($modering){
		$messageFrom = 0;
		$messageTo = ($to == 0) ? $from : $to;
	}else {
		$messageFrom = ($to == 0) ? $from : $to;
		$messageTo = 0;
	}
	$bd->write("INSERT INTO `help` VALUES (NULL, ".$messageFrom.", ".$messageTo.", ".$topic.", NULL, '".$text."', NOW(), 0)");
  
	close(json_encode(array('status' => 'ok')));
?>