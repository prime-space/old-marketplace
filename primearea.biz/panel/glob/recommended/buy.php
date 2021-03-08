<?php

	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../modules/currency/currclass.php";
	include "../../../func/transactions.class.php";
	
	$bd = new mysql();
	$db = new db();
	$user = new user();
	$data = json_decode($_POST['data'], true);
	$transactions = new transactions();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$request = $db->super_query("SELECT value FROM setting WHERE ids = 12 ORDER BY id LIMIT 3");
	$settings = unserialize($request['value']);

	$amount = $data['amount'];

	$startDate = time();
	$today = date('Y-m-d H:i:s', $startDate);
	$date = date('Y-m-d H:i:s', strtotime('+1 month', $startDate));

	$request = $db->super_query("SELECT `until`, `type`
		FROM `user_privileges`
		WHERE `user_id` = ".$user->id." AND `until` > '".$today."'"
	);	

	if($user->active_privileges()){
		$d = $request['until'];
		close(json_encode(array('status' => 'error', 'message' => 'У вас уже имеются привилегии до '.$d)));
	}

	if( !($amount == $settings['priv_amount_1'] || $amount == $settings['priv_amount_2'] || $amount == $settings['priv_amount_3'] || $amount == $settings['priv_amount_4']) )close(json_encode(array('status' => 'error', 'message' => 'Неправильная сумма')));
	if(!$transactions->checkFounds($user->id, '-'.$amount))close(json_encode(array('status' => 'error', 'message' => 'Недостаточно средств. Для пополнения перейдите по ссылке <a class="btn newbtn-success" href="/panel/addmoney" target="_BLANK">Пополнить</a>')));
	
	if($amount == $settings['priv_amount_1']){
		$type = 1;
	}elseif($amount == $settings['priv_amount_2']){
		$type = 2;
	}elseif($amount == $settings['priv_amount_3']){
		$type = 3;
	}elseif($amount == $settings['priv_amount_4']){
		$type = 4;
	}				

	if(!$user->active_privileges()){

		$user->removePrivCookie();

		$db->query("INSERT INTO `user_privileges` VALUES(NULL,".$user->id.", '".$date."', ".$type.")");
		$id = $db->db_id->insert_id;

		$transactionId = $transactions->create(array(
			'user_id' => $user->id,
			'type' => 0,
			'method' => 'priv',
			'method_id' => $id,
			'currency' => 4,
			'amount' => '-'.$amount
		));

		close(json_encode(array('status' => 'ok')));
	}