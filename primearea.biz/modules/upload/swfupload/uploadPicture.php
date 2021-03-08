<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  	
	$bd = new mysql();

	class paimage{
		public static function resize($orig_image, $need_width, $need_height, $new_path, $method){
			list($orig_width, $orig_height) = getimagesize($orig_image);
			if($method == 'strict'){
				$width = $need_width;
				$height = $need_height;				
			}
			if($method == 'max'){
				if($orig_width > $need_width || $orig_height > $need_height)$method = 'proportion';
				else {
					$width = $orig_width;
					$height = $orig_height;
				}
			}
			if($method == 'proportion'){
				$k1 = $need_width / $orig_width;
				$k2 = $need_height / $orig_height;
				$k = $k1 < $k2 ? $k1 : $k2;
				$width = round($orig_width * $k);
				$height = round($orig_height * $k);
			}
			$image_p = imagecreatetruecolor($width, $height);
			$ext = strtolower(strrchr($orig_image, '.'));
			if($ext == '.jpg' || $ext == '.jpeg')$image = imagecreatefromjpeg($orig_image);
			if($ext == '.gif')$image = imagecreatefromgif($orig_image);
			if($ext == '.png')$image = imagecreatefrompng($orig_image);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
			imagejpeg($image_p, $new_path, 100);
		}
	}
	
	

	$uploadDir = '../../../picture/'; 
	 
	$allowedExt = array('jpg', 'png', 'gif');
	$maxFileSize = 1024 * 1024; //1 мБ
	 
	if (isset($_FILES)) {
		$ext = end(explode('.', strtolower($_FILES['Filedata']['name'])));
		if (!in_array($ext, $allowedExt)) {
			close('ext');
		}
		if ($maxFileSize < $_FILES['Filedata']['size']) {
			close('size');
		}

	if (is_uploaded_file($_FILES['Filedata']['tmp_name'])){
		$fileFolder = md5(time().$_FILES['Filedata']['name']);
		$fileName = 'original.'.$ext;
		$filePathDir = $uploadDir.date('Y').'/'.date('m').'/'.date('d').'/'.$fileFolder.'/';
		if(!file_exists($uploadDir.date('Y')))mkdir($uploadDir.date('Y'));
		if(!file_exists($uploadDir.date('Y')."/".date('m')))mkdir($uploadDir.date('Y')."/".date('m'));
		if(!file_exists($uploadDir.date('Y')."/".date('m')."/".date('d')))mkdir($uploadDir.date('Y')."/".date('m')."/".date('d'));
		mkdir($uploadDir.date('Y').'/'.date('m').'/'.date('d').'/'.$fileFolder);
		$filePathFull = $filePathDir.$fileName;
		move_uploaded_file($_FILES['Filedata']['tmp_name'], $filePathFull);

		paimage::resize($filePathFull, 200, 125, $filePathDir.'productshow.jpg', 'proportion');
		paimage::resize($filePathFull, 290, 175, $filePathDir.'recommended.jpg', 'strict');
		paimage::resize($filePathFull, 1024, 768, $filePathDir.'fullview.jpg', 'max');
		
		$idPicture = $bd->write("INSERT INTO `picture` VALUES(NULL, NULL, '".substr($filePathDir, 17)."', '".$ext."', ".time().")");

		close('ok:'.$idPicture.':'.substr($filePathFull, 8).':'.$filePathFull.':'.'type:'.$_POST['type']);

		}
	   
	}
	close('error');
?>