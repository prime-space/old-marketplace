<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	require_once "../../../func/auth.class.php";

$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	$modering = in_array($user->role, array('admin', 'moder')) ? true : false;
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$error = array();
	if(!$data['pass'])$error[] = 'Укажите пароль';
	elseif($data['pass'] !== '#$%|notChanged%' && !preg_match('/^[a-z0-9-_]{6,18}$/iu', $data['pass']))$error[] = 'Неверный пароль: от 6 до 18 символов, буквы, цифры, дефисы и подчёркивания';
	elseif($data['pass'] !== $data['passr'])$error[] = 'Пароли не совпадают';	
	
	if($data['site']){
		if(!preg_match('/^.+\..+$/',$data['site']))$error[] = 'Неверный формат адреса сайта';
		if(!preg_match('/^https?\:\/\//',$data['site']))$data['site'] = 'http://'.$data['site'];
	}
	
	$user_id = $modering && $data['user_id'] ? (int)$data['user_id'] : $user->id;

	$request = $bd->read("SELECT wm, wmz, wmr, wme, wmu, yandex_purse, qiwi_purse FROM `user` WHERE id = ".$user_id." LIMIT 1");
	if(!$bd->rows)close('{"status": "error", "message": "Пользователь не найден"}');
	$wm = mysql_result($request,0,0);
	$wmz = mysql_result($request,0,1);
	$wmr = mysql_result($request,0,2);
	$wme = mysql_result($request,0,3);
	$wmu = mysql_result($request,0,4);
	$yandex_purse = mysql_result($request,0,5);
	$qiwi_purse = mysql_result($request,0,6);

	$update = array();
	$update[] = "phone = '".$bd->prepare($data['phone'], 32)."'";
	$update[] = "skype = '".$bd->prepare($data['skype'], 32)."'";
	$update[] = "site = '".$bd->prepare($data['site'], 128)."'";
	
	if($data['pass'] !== '#$%|notChanged%')$update[] = "pass = '".auth::encodePassword($data['pass'])."'";
	
	if($modering){
		if(!$data['login'])$error[] = 'Укажите логин';
		elseif(!preg_match('/^[a-z0-9-_]{3,16}$/iu', $data['login']))$error[] = 'Неверный логин: от 3 до 16 символов, буквы, цифры, дефисы и подчёркивания';
		if(!$data['email'])$error[] = 'Укажите E-mail';
		elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))$error[] = 'Неверный формат email';
		if(!$data['fio'])$error[] = 'Укажите ФИО';
		elseif(!preg_match('/^[a-zа-яё\s]{8,64}$/iu', $data['fio']))$error[] = 'Неверный формат ФИО: от 8 до 64 символов, русские и английские буквы, пробелы';
		$update[] = "login = '".$bd->prepare($data['login'], 16)."'";
		$update[] = "email = '".$bd->prepare($data['email'], 70)."'";
		$update[] = "fio = '".$bd->prepare($data['fio'], 64)."'";
	}
	
	if(!$wm || $modering)$update[] = "wm = '".$bd->prepare($data['wm'], 16)."'";
	if(!$wmz || $modering)$update[] = "wmz = '".$bd->prepare($data['wmz'], 16)."'";
	if(!$wmr || $modering)$update[] = "wmr = '".$bd->prepare($data['wmr'], 16)."'";
	if(!$wme || $modering)$update[] = "wme = '".$bd->prepare($data['wme'], 16)."'";
	if(!$wmu || $modering)$update[] = "wmu = '".$bd->prepare($data['wmu'], 16)."'";
	if(!$yandex_purse || $modering)$update[] = "yandex_purse = '".$bd->prepare($data['yandex_purse'], 16)."'";
	if(!$qiwi_purse || $modering){
	    if ('' !== $data['qiwi_purse'] && !preg_match('/^\+[1-9]{1}[0-9]{7,14}$/', $data['qiwi_purse'])) {
            $error[] = 'Неверный формат qiwi кошелька. Должен начинаться с + и не содержать ничего кроме цифр';
        } else {
            $update[] = "qiwi_purse = '".$bd->prepare($data['qiwi_purse'], 16)."'";
        }
    }
	

	
	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	
	$bd->write("
		UPDATE `user`
		SET ".implode(', ', $update)."
		WHERE `id` = ".$user_id."
		LIMIT 1
	");

	close(json_encode(array(
		'status' => 'ok',
		'message' => 'Данные успешно сохранены'
	)));
?>
