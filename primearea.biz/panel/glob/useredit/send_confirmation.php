<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/mail/mail.class.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	global $siteAddr;

	$request = $bd->read("SELECT id, fio, login, email
        FROM user 
		WHERE id = ".$user->id." AND random_key IS NULL LIMIT 1");

	$id = mysql_result($request,0,0);
	$fio = mysql_result($request,0,1);
	$login = mysql_result($request,0,2);
	$email = mysql_result($request,0,3);


	$random = substr( md5(rand()), 0, 15);
	$confirmation_url = $siteAddr."activation/".$random."/".$id;
	$mail = new mail();

	$subject = 'АКТИВАЦИЯ АККАУНТА';
	$message = 	'<p>Здравствуйте, '.$fio.'.</p>
				<br>
				<p>Ваш логин "'.$login.'"</p>
				<p>Учетная запись "'.$email.'" успешно зарегистрирована.</p>
				<p>Чтобы подтвердить валидность электронного адреса учетной записи, пожалуйста, перейдите по ссылке: </p>
				<p><a href="'.$confirmation_url.'">'.$confirmation_url.'</a></p>
				<p>Если это письмо попало к вам случайно, то просто удалите его.';
    $message = $bd->prepare($message, 65535, false);
	$bd->write("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$email."', 'need', CURRENT_TIMESTAMP)");

	exit(json_encode(array('status' => 'ok')));
?>