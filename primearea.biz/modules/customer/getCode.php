<?php
	session_start();
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/main.php";
	include "../../func/customer.class.php";
		
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))close('{"status": "error", "message": "email_incorrect_format"}');

	if(!isset($_POST['captcha']) || ($_SESSION['captcha'] !== $_POST['captcha'])){
		unset($_SESSION['captcha']);
		close('{"status": "error", "message": "captcha"}');
	}
	unset($_SESSION['captcha']);
	
	$bd = new mysql();
	$customer = new customer();
	
	$email = $bd->prepare($_POST['email'], 64);
	$h = $customer->create_key($email);
	if(!$h)close('{"status": "error", "message": "email_not_found"}');
	
	$subject = 'ДОСТУП В КАБИНЕТ ПОКУПАТЕЛЯ';
	$message = 	'<p>Уважаемый покупатель!</p><br>
				<p>Для доступа к вашим покупкам, осуществленным через <a href="'.$siteAddr.'">'.$CONFIG['site_domen'].'</a>, пожалуйста перейдите по ссылке:</p>
				<a href="'.$siteAddr.'customer/'.$h.'/">'.$siteAddr.'customer/'.$h.'/</a>
				<p>Если данная ссылка не открывается из письма, скопируйте ее и вставьте в адресную строку интернет-браузера.</p>
				<br>
				<p>С уважением, администрация '.$CONFIG['site_domen'].'</p>
				<br>
				<p>Письмо сформировано автоматически сервисом приема платежей '.$CONFIG['site_domen'].'</p>';
    $message = $bd->prepare($message, 65535, false);
	$bd->write("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$email."', 'need', CURRENT_TIMESTAMP)");
	
	
	close('{"status": "ok"}');
?>
