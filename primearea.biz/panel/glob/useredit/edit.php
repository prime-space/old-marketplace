<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/google-autenticator/GoogleAuthenticator.php";
	include "../../../func/tpl.class.php";

	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	$modering = in_array($user->role, array('admin', 'moder')) ? true : false;
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$user_id = $modering && $data['user_id'] ? (int)$data['user_id'] : $user->id;

	$request = $bd->read("
        SELECT u.id, u.login, u.email, u.fio, u.phone, u.skype, u.site, u.wm, u.wmz, u.wmr, u.wme, u.wmu, u.yandex_purse, u.qiwi_purse, u.percent_out, u.reservation, u.emailInforming
        FROM user u
        WHERE u.id = ".$user_id."
    ");

	$id = mysql_result($request,0,0);
	$login = mysql_result($request,0,1);
	$email = mysql_result($request,0,2);
	$fio = mysql_result($request,0,3);
	$phone = strBaseOut(mysql_result($request,0,4));
	$skype = strBaseOut(mysql_result($request,0,5));
	$site = strBaseOut(mysql_result($request,0,6));
	$wm = strBaseOut(mysql_result($request,0,7));
	$wmz = strBaseOut(mysql_result($request,0,8));
	$wmr = strBaseOut(mysql_result($request,0,9));
	$wme = strBaseOut(mysql_result($request,0,10));
	$wmu = strBaseOut(mysql_result($request,0,11));
	$yandex_purse = strBaseOut(mysql_result($request,0,12));
	$qiwi_purse = strBaseOut(mysql_result($request,0,13));
	$percent = mysql_result($request,0,14);
	$reservation = mysql_result($request,0,15);
	$emailInforming = (int)mysql_result($request,0,16);

	if( isset($data['settings']) && $data['settings'] == true ){
		$tpl = new tpl('cabinet/edit_settings');
		$tpl->set([
			'percent' => $percent,
			'time' => $reservation,
			'id' => $user_id,
		]);
	}else{
        $googleAuthenticator = new GoogleAuthenticator;
        $googleSecret = $googleAuthenticator->generateSecret();
        $googleQrUrl = $googleAuthenticator->getUrl($login, $CONFIG['site_domen'], $googleSecret);

        $tpl = new tpl('cabinet/edit');
        $tpl->ify('TWIN_AUTH', $user->googleSecret === '' ? 1 : 2);
        $tpl->set([
            'login' => $login,
            'userId' => $user_id,
            'fio' => $fio,
            'phone' => $phone,
            'skype' => $skype,
            'site' => $site,
            'email' => $email,
            'wm' => $wm,
            'wmz' => $wmz,
            'wmr' => $wmr,
            'wme' => $wme,
            'wmu' => $wmu,
            'yandex_purse' => $yandex_purse,
            'qiwi_purse' => $qiwi_purse,
            'role' => $user->role,
            'emailInformingValue' => 1 === $emailInforming ? 0 : 1,
            'emailInformingButton' => 1 === $emailInforming ? 'Запретить' : 'Разрешить',
            'googleSecret' => $googleSecret,
            'googleQrUrl' => $googleQrUrl,
        ]);
	}

	$request = $bd->read("SELECT id
        FROM user  
		WHERE id = ".$user_id." AND random_key IS NULL LIMIT 1");

	if($bd->rows){
		$acivation_message .= <<<HTML
			<div onclick="panel.user.cabinet.confirm_email(this)" class="btn btn-small btn-success">
				Подтвердить
			</div>
HTML;
	}else{
		$acivation_message = <<<HTML
					<div style="width:30px; height:30px; display:inline-block;margin-left:5px;" class="email_activate_cabinet"> <img style="width:30px; height:30px"  src="/style/img/email_activated.png"></div>
HTML;
	}

	$activation_message = $acivation_message;
	$tpl->set('{email_activation}', $activation_message);

	exit(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
