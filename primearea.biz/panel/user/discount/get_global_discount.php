<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
  
	$request = $bd->read("SELECT money, percent FROM discount WHERE userId = ".$user->id." ORDER BY percent LIMIT 10");
  
	$money = array();
	$percent = array();
	for($i=0;$i<$bd->rows;$i++){
		$money[] = mysql_result($request,$i,0);
		$percent[] = mysql_result($request,$i,1);
	}
  
	close(json_encode(array(
		'status' => 'ok', 
		'count' => $bd->rows,
		'money' => $money,
		'percent' => $percent
	)));
?>