<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
	
	$maxfilesize = 1 * 1024 * 1024; //1 MB
	
	if(!$_FILES['file'])close('{"status": "error", "content": "Выберите файл"}');
	
	$ext = 	mb_strtolower(substr(strrchr($_FILES['file']['name'],'.'),1));
	if($ext != 'csv' && $ext != 'txt')close('{"status": "error", "content": "Неверное расширение файла, только .csv или .txt"}');
	
	if($_FILES['file']['size'] > $maxfilesize)close('{"status": "error", "content": "Максимальный размер файла 1 мБ"}');
	
	$csv = file_get_contents($_FILES['file']['tmp_name']);
	
	$data = explode("\r", $csv);
	$data_out = array();
	foreach($data as $k){
		$k = str_replace(array("\n","\r"), NULL, $k);
		if($k)$data_out[] = $k;
	}
	if(!count($data_out))close('{"status": "error", "content": "Файл пустой"}');
	
	close(json_encode(array(
		'status' => 'ok',
		'content' => $data_out
	)));	
?>