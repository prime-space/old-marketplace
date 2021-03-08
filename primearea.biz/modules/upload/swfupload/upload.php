<?php
	include '../../../func/mysql.php';
	include '../../../func/verification.php';
	include '../../../func/main.php';

	$uploadDir = '../../../upload/'; //папка для хранения файлов
	 
	//$allowedExt = array('mp3');
	$maxFileSize = 20 * 1024 * 1024; //20 MB
	 
	//если получен файл
	if (isset($_FILES)) {
		//проверяем размер и тип файла
		//$ext = end(explode('.', strtolower($_FILES['Filedata']['name'])));
	   // if (!in_array($ext, $allowedExt)) {
		//    return;
	   // }
		if ($maxFileSize < $_FILES['Filedata']['size']) {
			return;
		}

	if (is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	   $fileName = realMysql(strPrepare($_FILES['Filedata']['name'], "filename"), 64);
	   $filePathDir = $uploadDir.date('Y')."/".date('m')."/".date('d')."/";
	   if(!file_exists($uploadDir.date('Y')))mkdir($uploadDir.date('Y'));
	   if(!file_exists($uploadDir.date('Y')."/".date('m')))mkdir($uploadDir.date('Y')."/".date('m'));
	   if(!file_exists($uploadDir.date('Y')."/".date('m')."/".date('d')))mkdir($uploadDir.date('Y')."/".date('m')."/".date('d'));
	   $fileSave = md5(time().$fileName);
	   $filePathFull = $filePathDir.$fileSave;
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $filePathFull);
			
			$filePathFull = substr($filePathFull, 9);
			
			exit('{"name": "'.$fileName.'", "dir": "'.$filePathFull.'"}');
	   }
	   
	}
?>