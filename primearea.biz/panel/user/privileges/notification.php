<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";
	include "../../../func/tpl.class.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));

	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$priv_type = $user->priv_type();
	!$priv_type ?  exit(json_encode(array('status' => 'ok', 'content' => false))) : '';
	
	$tpl = new tpl('privileges/notification');
	$tpl->set('{user_id}', $user->id);
	$tpl->set('{type}', $user->priv_type(NULL, true));
	$tpl->set('{cookie_name}', $user->id.':'.$priv_type);

	$until     = $user->priv_until();

	$datetimeUntil = new DateTime($until);
	$datetimeNow = new DateTime();
	$interval = $datetimeNow->diff($datetimeUntil);
	$hoursLeft = ($interval->days * 24) + $interval->h;

	$cookie_name = $user->id.':'.$priv_type;

	if($hoursLeft > 24 && $hoursLeft <= 74 && $_COOKIE[$cookie_name] != 'd'){
		// left 3 days

		$tpl->set('{left}', '3 дня');
		$tpl->set('{cookie_value}', 'd');
	}elseif($hoursLeft <= 24 && $hoursLeft > 0 && $_COOKIE[$cookie_name] != 'h'){
		// left 24 hours

		$tpl->set('{left}', '24 часа');
		$tpl->set('{cookie_value}', 'h');
	}else{
		// dont show notification

		$tpl->content = false;
	}
	
	exit(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
