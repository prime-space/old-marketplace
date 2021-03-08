<?php
	session_start();
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/main.php";
	include "../../func/mail/mail.class.php";
	require_once "../../func/auth.class.php";

if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));
	
	$error = array();
	if(!$data['login'])$error[] = 'Укажите логин';
	elseif(!preg_match('/^[a-z0-9-_]{3,16}$/iu', $data['login']))$error[] = 'Неверный логин: от 3 до 16 символов, буквы, цифры, дефисы и подчёркивания';
	if(!$data['pass'])$error[] = 'Укажите пароль';
	elseif(!preg_match('/^[a-z0-9-_]{6,18}$/iu', $data['pass']))$error[] = 'Неверный пароль: от 6 до 18 символов, буквы, цифры, дефисы и подчёркивания';
	elseif($data['pass'] !== $data['passr'])$error[] = 'Пароли не совпадают';
	if(!$data['fio'])$error[] = 'Укажите ФИО';
	elseif(!preg_match('/^[a-zа-яё0-9-_\s]{8,64}$/iu', $data['fio']))$error[] = 'Неверный формат ФИО: от 8 до 64 символов, русские и английские буквы';	
	if(!$data['email'])$error[] = 'Укажите E-mail';
	elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))$error[] = 'Неверный формат email';
	if(!$data['captcha'])$error[] = 'Введите число с картинки';
	elseif(!$_SESSION["captcha"] || $_SESSION["captcha"] !== $data['captcha'])$error[] = 'Неверное число с картинки';
	if(!$data['agreement'])$error[] = 'Вы не согласились с правилами';
	unset($_SESSION["captcha"]);
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	$bd = new mysql();
	
	$login = $bd->prepare($data['login'], 16);
	$fio = $bd->prepare($data['fio'], 64);
	$email = $bd->prepare($data['email'], 32);
	
	
	$bd->read("SELECT id FROM user WHERE login = '".$login."' OR email = '".$email."' LIMIT 1");
	if($bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Пользователь с таким логином или email уже существует')));
	
	$pass = auth::encodePassword($data['pass']);
	$random = substr( md5(rand()), 0, 15);

	$bd->write("
		INSERT INTO user 
		VALUES (NULL,
			'".$login."',
			'".$pass."',
			'".$email."',
			'".$fio."',
			NOW(),
			'user',
			0,
			NULL,
			NULL,
			NULL,
			NULL,
			NULL,
			NULL,
			NULL,
			NULL,
			NULL,
			0,
			0,
			0,
			0,
			'conf',
			NOW(),
			NULL,
			NULL,
			NULL,
			NULL,
			'".$random."',
			NULL, 
			NULL,
			1,
			''
		)
	");
	unset($_SESSION["captcha"]);
	$user_id = mysql_insert_id();
	global $siteAddr;
	$confirmation_url = $siteAddr."activation/".$random."/".$user_id;

	$mail = new mail();

	$subject = 'АКТИВАЦИЯ АККАУНТА';
	$message = 	'<p>Здравствуйте, '.$fio.'.</p>
				<br>
				<p>Ваш логин "'.$login.'"</p>
				<p>Учетная запись "'.$email.'" успешно зарегистрирована.</p>
				<p>Чтобы подтвердить корректность учетной записи, пожалуйста, перейдите по ссылке: </p>
				<p><a href="'.$confirmation_url.'">'.$confirmation_url.'</a></p>
				<p>Если это письмо попало к вам случайно, то просто удалите его.</p>';
    $message = $bd->prepare($message, 65535, false);
	$bd->write("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$email."', 'need', CURRENT_TIMESTAMP)");

	
	close(json_encode(array('status' => 'ok', 'message' => 'Регистрация прошла успешно')));

?>
