<?php
session_start();
header("Content-type: text/html; charset=utf-8");
include "../../../func/config.php";
include "../../../func/main.php";

if($_POST['flag'] == "error"){
	if($_SESSION["captcha"] != $_POST['code']) echo "error";
}else{
	if($_SESSION["captcha"] != $_POST['code'])echo "error";
    else{
		$name = prepareEmail(strBaseOut($_POST['name']), 64);
		$email = prepareEmail($_POST['email'], 32);
		$title = prepareEmail(strBaseOut($_POST['title']), 64);
		$text = nl2br(prepareEmail(strBaseOut($_POST['text']), 600));
		$message = "Принято новое обращение.<br />";
		$message .= "Тема: ".$title."<br />";
		$message .= "От: ".$name." (".$email.")<br />";
		$message .= "<br />";
		$message .= $text;
		$subject = "PrimeArea.com";
		$headers = 'Content-type: text/html; charset=utf8' . "\r\n";
        $headers .= "From: PrimeArea.ru <".$adminEmail.">\r\n";
		mail($email, $subject, $message, $headers);
		mail($adminEmail, $subject, $message, $headers);
	}
}
unset($_SESSION["captcha"]);
?>