<?php
	include "../../../func/config.php";
	include "../../../func/db.class.php";
	include "../../../func/user.class.php";
	include "../../../func/duplicate.class.php";
	include "../../../func/transactions.class.php";
	include "../../../func/mysql.php";
	
	$db = new db();
	$bd = new mysql();
	$user = new user(array('user', 'moder', 'admin'));
	$transactions = new transactions();
	$duplicate = new duplicate('cashout');
	
	if(!$duplicate->access)exit('{"status": "error", "message": "Попробуйте позже"}');
	
	if(!$user->id)$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');

	if($user->status === 'blocked')$duplicate->end('{"status": "error", "message": "Пользователь заблокирован"}');

	$request = $bd->read("SELECT id
        FROM user  
		WHERE id = ".$user->id." AND random_key IS NULL LIMIT 1");

	if($bd->rows){
		$duplicate->end('{"status": "error", "message": "e-mail не подтвержден"}');
	}


	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else $duplicate->end('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)$duplicate->end('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['amount'])$duplicate->end('{"status": "error", "message": "Введите сумму"}');
	if(!preg_match('/^\d{1,14}(\.\d{1,2})?$/', $data['amount']))$duplicate->end('{"status": "error", "message": "Неверный формат суммы(155.78)"}');
	if($data['amount'] < 15 || $data['amount'] > 10000)$duplicate->end('{"status": "error", "message": "Сумма должна быть в пределах 15р. - 10000р."}');
	
	$amount = $data['amount'];

	if (!isset($CONFIG['cashout'][$data['type']]) || !$CONFIG['cashout'][$data['type']]['enabled']) {
        $duplicate->end('{"status": "error", "message": "Ошибка при выборе метода. Обновите страницу и попробуйте снова."}');
    }
    $user_purse_field = $CONFIG['cashout'][$data['type']]['user_purse_field'];
    $purse = $db->super_query_value("SELECT ".$user_purse_field." FROM user WHERE id = ".$user->id);
    $_curr = $CONFIG['cashout'][$data['type']]['curr'];
    if (empty($purse)) {
        $duplicate->end('{"status": "error", "message": "Заполните кошелек в личном кабинете."}');
    }

    if($data['type'] == 'qiwi' && !preg_match('/^\+[1-9]{1}[0-9]{7,14}$/', $purse)){
        $duplicate->end('{"status": "error", "message": "Неверный формат кошелька. Отредактируйте в настройках"}');
	}

	$transactionId = $transactions->create(array(
		'user_id' => $user->id,
		'type' => 0,
		'method' => 'cashout',
		'method_id' => 0,
		'currency' => 4,
		'amount' => '-'.$amount
	));
	
	if(!$transactionId)$duplicate->end('{"status": "error", "message": "Недостаточно средств"}');

	$db->query("
        INSERT INTO `cashout`
            (userId, amount, currency, status, date)
        VALUES
            ({$user->id}, '$amount', '$_curr', 'new', NOW())
    ");
	$cashoutId = $db->insert_id();
	
	$transactions->updateMethodId($transactionId, $cashoutId);

	$dbQueue = new db(0, 'queue');
	$message = json_encode(['aggregator' => $data['type'], 'id' => $cashoutId,]);
	$dbQueue->query("CALL sp_create_message('withdraw', '$message')");
	
	$duplicate->end('{"status": "ok", "message": "Запрос добавлен"}');
