<?php
	include '../../../func/config.php';
	include '../../../func/db.class.php';
	include '../../../func/user.class.php';
	include '../../../func/mail/mail.class.php';

	$db = new db();
	$user = new user(array('user', 'moder', 'admin'));
	
	if(!$_FILES["fileToUpload"]["name"])exit(json_encode(array('status' => 'error','message' => 'Загрузите изображение')));
	
	$filename = basename($_FILES["fileToUpload"]["name"]);
	$filename_ext = end(explode('.', $filename));

	$fileFolder = md5(time().$_FILES['fileToUpload']['name']);
	$target_dir = '../../../picture/'.date('Y')."/".date('m')."/".date('d')."/".$fileFolder."/";
	$ext = end(explode('.', strtolower($_FILES['fileToUpload']['name'])));
	$target_file = $target_dir . 'original'.'.'.$filename_ext;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 1000000) {
	    exit(json_encode(array('status' => 'error','message' => 'Макс. размер - 1МБ')));
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "zip" && $imageFileType != "rar" ) {
	    exit(json_encode(array('status' => 'error','message' => 'Неправильный формат')));
	}

	mkdir('../../../picture/'.date('Y'));
	mkdir('../../../picture/'.date('Y')."/".date('m')."/");
	mkdir('../../../picture/'.date('Y')."/".date('m')."/".date('d')."/");
	mkdir('../../../picture/'.date('Y')."/".date('m')."/".date('d')."/".$fileFolder."/");

	if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
		
		$db->query("INSERT INTO `picture` VALUES(NULL, NULL, '".substr($target_dir, 17)."', '".$ext."', ".time().")");

		exit(json_encode(array('status' => 'ok','path' => '/picture/'.date('Y')."/".date('m')."/".date('d')."/".$fileFolder."/".'original'.'.'.$filename_ext, 'id' =>$db->db_id->insert_id )));
	}else{
		exit(json_encode(array('status' => 'error','message' => 'Не удалось загрузить файл')));
	}

	
?>