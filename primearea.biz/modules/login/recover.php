<?php
	session_start();
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/db.class.php";
	include "../../func/main.php";
	include "../../func/mail/mail.class.php";
	global $siteAddr;

	if(!$_POST['email'])close('{"status": "error", "message": "data"}');
	
	if(!$_POST['captcha'] || !$_SESSION['captcha'] || ($_SESSION['captcha'] !== $_POST['captcha'])){
		unset($_SESSION["captcha"]);
		close('{"status": "error", "message": "captcha"}');
	}
	unset($_SESSION["captcha"]);
	
	$bd = new mysql();
	$db = new db();
	
	$email = $bd->prepare($_POST['email'], 256);

	$request = $bd->read("SELECT id, fio FROM user WHERE email = '".$email."' LIMIT 1");
	if(!$bd->rows){
		close('{"status": "error", "message": "noemail"}');
	}

	$user_id = mysql_result($request, 0, 0);
	$fio = mysql_result($request, 0, 1);
	$key = substr( md5(rand()), 0, 15);


	$request2 = $bd->read("SELECT id FROM user WHERE email = '".$email."' AND status = 'conf' LIMIT 1");
	if($bd->rows){
		close('{"status": "error", "message": "conf"}');
	}

	$bd->write("UPDATE user SET random_key = '".$key."' WHERE email = '".$email."' LIMIT 1");

	$confirmation_url = $siteAddr."reset/".$user_id."/".$key;
	$mail = new mail();

	$subject = 'ВОССТАНОВЛЕНИЕ ПАРОЛЯ';
	$message = 	'<p>Здравствуйте, '.$fio.'.</p>
				<br>
				<p>Чтобы сменить пароль, пожалуйста, перейдите по ссылке: </p>
				<p><a href="'.$confirmation_url.'">'.$confirmation_url.'</a></p>
				<p>Если это письмо попало к вам случайно, то просто удалите его.</p>';
    $message = $bd->prepare($message, 65535, false);
	$bd->write("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$email."', 'need', CURRENT_TIMESTAMP)");
	
	close('{"status": "ok"}');
?>