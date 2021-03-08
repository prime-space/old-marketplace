<?php
	session_start();
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/db.class.php";
	include "../../func/main.php";
	include "../../func/google-autenticator/GoogleAuthenticator.php";
    require_once "../../func/auth.class.php";

	if(!isset($_POST['login']) || !isset($_POST['password']))close('{"status": "error", "message": "data"}');

    $bd = new mysql();
    $db = new db();

    $login = $bd->prepare($_POST['login'], 16);

    $request = $bd->read("SELECT id, pass, `status`, googleSecret FROM user WHERE login = '".$login."' LIMIT 1");
    $userId = mysql_result($request,0,0);
    $pass = mysql_result($request,0,1);
    $status = mysql_result($request,0,2);
    $googleSecret = mysql_result($request,0,3);

    if ($pass === '') {
        close('{"status": "error", "message": "recover"}');
    }

    if ($bd->rows && $googleSecret !== '' && $_POST['code'] === 'nocode') {
        close(json_encode(['status' => 'error', 'message' => 'twindata', 'noUpdateCaptcha' => true]));
    }

	if(!$_POST['captcha'] || !$_SESSION['captcha'] || ($_SESSION['captcha'] !== $_POST['captcha'])){
		unset($_SESSION["captcha"]);
		close('{"status": "error", "message": "captcha"}');
	}
	unset($_SESSION["captcha"]);

	if(!$bd->rows)close('{"status": "error", "message": "data"}');
	if ($googleSecret !== '') {
        $googleAuthenticator = new GoogleAuthenticator;
        if ($googleAuthenticator->getCode($googleSecret) !== $_POST['code']) {
            close(json_encode(['status' => 'error', 'message' => 'twindata']));
        }
        if(auth::encodePassword($_POST['password']) !== $pass)close('{"status": "error", "message": "twindata"}');
    } else {
        if(auth::encodePassword($_POST['password']) !== $pass)close('{"status": "error", "message": "data"}');
    }
	if($status === 'conf')close('{"status": "error", "message": "conf"}');
	
	$genhashes = false;
	while(!$genhashes){
		$crypt = hash('sha256', $pass . $login . time());
		$token = hash('sha256', $login . $pass . time());
		$bd->read("SELECT id FROM usersession WHERE crypt = '".$crypt."' OR token = '".$token."' LIMIT 1");
		if($bd->rows)continue;
		$genhashes = true;
	}

	$time = time();
	$bd->write("INSERT INTO usersession VALUES (NULL, ".$userId.", '".$crypt."', '".$token."', ".$time.")");
	
	$ip = $db->safesql(REMOTE_ADDR,true);
	$system = $db->safesql(user_browser().' / '.user_os(),true);
	require_once '../../func/sxgeo.class.php';
	$sxgeo = new SxGeo('../../modules/sxgeo/sxgeocountry.dat');
	$alpha2 =  $sxgeo->getCountry(REMOTE_ADDR);
	$countryCode = $alpha2 ? (int)$db->super_query_value("SELECT code FROM countries WHERE alpha2 = '".$alpha2."' LIMIT 1") : 'NULL';
	$delAuthlogId = $db->super_query_value("SELECT id FROM authlog WHERE userId = ".$userId." ORDER BY id DESC LIMIT 9, 1");
	if($delAuthlogId)$db->query("DELETE FROM authlog WHERE id = ".$delAuthlogId." LIMIT 1");
	$db->query("INSERT INTO authlog (userId,ip,countryCode,system) VALUES (".$userId.", '".$ip."', ".$countryCode.", '".$system."')");
	
	SetCookie('s', $crypt, time()+86400, '/', $CONFIG['site_domen'], $CONFIG['cookie_SSL'], true);
	
	close('{"status": "ok"}');
