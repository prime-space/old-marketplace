<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	$maxfilesize = 1 * 1024 * 1024; //1 MB
	
	if(!$_FILES['file'])close('{"status": "error", "message": "Выберите файл"}');
	
	$ext = 	mb_strtolower(substr(strrchr($_FILES['file']['name'],'.'),1));
	if($ext != 'csv' && $ext != 'txt')close('{"status": "error", "message": "Неверное расширение файла, только .csv или .txt"}');
	
	if($_FILES['file']['size'] > $maxfilesize)close('{"status": "error", "message": "Максимальный размер файла 1 мБ"}');
	
	$data = file($_FILES['file']['tmp_name'],FILE_SKIP_EMPTY_LINES);
	if(!count($data))close('{"status": "error", "message": "Файл пустой"}');
	
	if(!preg_match('//u', implode($data)))
		foreach($data AS $k => $v)
			$data[$k] = mb_convert_encoding($v, 'UTF-8', 'CP-1251');
	
	close(json_encode(array(
		'status' => 'ok',
		'content' => $data
	)));	
?>