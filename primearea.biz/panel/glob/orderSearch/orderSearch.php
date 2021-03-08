<?php
	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
  
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/glob/orderSearch/orderSearch.tpl"));
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));  
?>