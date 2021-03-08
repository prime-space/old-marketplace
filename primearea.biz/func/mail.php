<?php
	include 'func/config.php';
	include 'func/mail/PHPMailer.class.php';
	include 'func/mail/smtp.class.php';
/*
$CONFIG = array(	'smtp' => array(
		'host' => 'smtp-pulse.com',
		'port' => '465',
		'login' => 'order-info@primearea.ru',
		'password' => 'ZcRXAjdPfMd',
		'secure' => 'ssl',
		'from_email' => 'order-info@primearea.ru',
		'from_name' => 'PRIMEAREA',
		'debug' => 1,
		'SMTPAuth' => true
	));
*/
	/*$CONFIG = array('smtp' => array(
		'host' => 'primebizteam.com',
		'port' => '587',
		'login' => 'admin@primebizteam.com',
		'password' => 'FqkwZvhZSmgg0laB',
		'secure' => 'tls',
		'from_email' => 'admin@primebizteam.com',
		'from_name' => 'PRIMEBIZTEAM',
		'debug' => 1,
	));*/
	/*$CONFIG = array('smtp' => array(
		'host' => 'primearea.biz',
		'port' => '587',
		'login' => 'admin@primearea.biz',
		'password' => 'YCz3cFdvTjpEAOg3',
		'secure' => 'tls',
		'from_email' => 'admin@primearea.biz',
		'from_name' => 'PRIMEAREA',
		'debug' => 1,
	));*/
	$CONFIG = array('smtp' => array(
		'host' => 'smtp.stormwall.pro',
		'port' => '25',
		'from_email' => 'admin@primearea.biz',
		'from_name' => 'PRIMEAREA',
		'debug' => 1
	));
	echo '<pre>';
	$PHPMailer = new PHPMailer();
	
	$PHPMailer->Host = $CONFIG['smtp']['host'];
	$PHPMailer->Port = $CONFIG['smtp']['port'];
	$PHPMailer->SMTPAuth  = $CONFIG['smtp']['SMTPAuth'];
	$PHPMailer->Username  = $CONFIG['smtp']['login'];
	$PHPMailer->Password  =  $CONFIG['smtp']['password'];
	$PHPMailer->Mailer = 'smtp';
	$PHPMailer->SMTPSecure = $CONFIG['smtp']['secure'];
	$PHPMailer->ContentType = 'text/html';
	$PHPMailer->CharSet = 'UTF-8';
	$PHPMailer->From = $CONFIG['smtp']['from_email'];
	$PHPMailer->FromName = $CONFIG['smtp']['from_name'];
	$PHPMailer->SMTPDebug = true;
	
	$PHPMailer->Subject = 'ДОСТУП В КАБИНЕТ ПОКУПАТЕЛЯ';
	$PHPMailer->Body = '<p>Уважаемый покупатель!</p><br>
				<p>Для доступа к вашим покупкам, осуществленным через , пожалуйста перейдите по ссылке:</p>
				<p>Если данная ссылка не открывается из письма, скопируйте ее и вставьте в адресную строку интернет-браузера.</p>
				<br>
				<p>С уважением, администрация</p>
				<br>
				<p>Письмо сформировано автоматически сервисом приема платежей</p>';

	$PHPMailer->AddAddress('343604@gmail.com', '');
	
	if(!$PHPMailer->Send())echo 'Не могу отослать письмо!';
	else echo 'Письмо отослано!';
	$PHPMailer->ClearAddresses();
	$PHPMailer->ClearAttachments();
	echo '</pre>';
/*
	$headers = "Content-Type: text/html; charset=UTF-8\r\n";
	$to = '343604@gmail.com';
	$subject = 'Тема';
	$message = 'Текст';
	
	$status = mail($to, $subject, $message, $headers);
	$status = ($status) ? "sended" : "error";
	echo $status;
*/
?>