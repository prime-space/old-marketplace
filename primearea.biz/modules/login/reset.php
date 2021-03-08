<?php
	session_start();
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/db.class.php";
	include "../../func/main.php";
	include "../../func/mail/mail.class.php";
    require_once "../../func/auth.class.php";

	if(!$_POST['password'] || !$_POST['repassword'])close('{"status": "error", "message": "data"}');
	
	if($_POST['password'] != $_POST['repassword'])close('{"status": "error", "message": "matching"}');

	if(!$_POST['captcha'] || !$_SESSION['captcha'] || ($_SESSION['captcha'] !== $_POST['captcha'])){
		unset($_SESSION["captcha"]);
		close('{"status": "error", "message": "captcha"}');
	}
	unset($_SESSION["captcha"]);
	
	
	$db = new db();
	

	$user_id = (int)$_POST['user_id'];

	$key = $db->safesql($_POST['key']);
	$pass = $db->safesql($_POST['password']);
	$newKey = substr( md5(rand()), 0, 15);

	$pass = auth::encodePassword($pass);

	$request = $db->query("SELECT id
        FROM user  
		WHERE id = ".$user_id." AND random_key = '".$key."' LIMIT 1");
	if(!$request->num_rows)close('{"status": "error", "message": "error"}');
		
	$res = $db->query("UPDATE user SET pass = '".$pass."', random_key = '".$newKey."' WHERE id = ".$user_id." LIMIT 1");

	close('{"status": "ok"}');
