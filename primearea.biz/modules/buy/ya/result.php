<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../currency/currclass.php";

	include "../../../func/mail/mail.class.php";
	include "../../../func/db.class.php";
	include "../../../func/transactions.class.php";
	include "../../../func/duplicate.class.php";
	include "../../../func/partner.class.php";
	include "../../../func/logs.class.php";
    include '../../../func/PaymentAccountFetcher.php';
	
	$db = new db();
	$paymentAccountFetcher = new PaymentAccountFetcher($db);
	
	$duplicate = new duplicate('yaResult');
	if(!$duplicate->access)exit('{"status": "error", "message": "duplicate"}');

	$bd = new mysql();
	$logs = new logs();
	
	$logs->add('yaResult', 0, $db->safesql(serialize($_POST)));
	
	if(!$_POST['label']){
		$logs->add('yaResult',0,"error:label");
		$duplicate->end('{"status": "error", "message": "label is missing"}');
	}
	$label = $db->safesql($_POST['label']);
	$labelex = explode('_',$label);
	
	if($labelex[0] == 'merchant'){
		require_once '../../../func/merchant.class.php';
		$merchant = new merchant();
		$externalinit = $merchant->externalinit((int)$labelex[1]);
		if($externalinit !== true){
			$logs->add('yaResult',0,'error:'.$externalinit.':|||label:'.$label);
			$duplicate->end('{"status": "error", "message": "'.$status.'"}');
		}
        $paymentAccountId = $merchant->mpayment['payment_account_id'];
	} elseif($labelex[0] == 'addmoney') {
		$addMoneyId = (int) $labelex[1];
		$addMoney = $db->super_query("SELECT id, user_id, payment_account_id FROM addmoney WHERE id = $addMoneyId");
		if (null === $addMoney) {
            $logs->add('yaResult',0,'error:addmoney_not_found|||label:'.$label);
            $duplicate->end('{"status": "error", "message": "Order not found"}');
		}
        $paymentAccountId = $addMoney['payment_account_id'];
    } else {
	    $orderId = (int) $label;
        $orderUtil = new order();
	    $order = $orderUtil->getById($orderId);
	    if (null === $order) {
            $logs->add('yaResult',0,'error:order_not_found|||label:'.$label);
            $duplicate->end('{"status": "error", "message": "Order not found"}');
        }
        $paymentAccountId = $order['payment_account_id'];
    }
	try {
		$paymentAccount = $paymentAccountFetcher->getById($paymentAccountId);
	} catch (Exception $e) {
		$message = $e->getMessage();
		$logs->add('yaResult',0,"error:$message|||label:$label");
		$duplicate->end(json_encode(['status' => 'error', 'message' => $message]));
	}
	$secret = $paymentAccount['config']['secret'];
	
	if($_POST['codepro'] !== 'false'){
		$logs->add('yaResult',0,'error:codepro:|||label:'.$label);
		$duplicate->end('{"status": "error", "message": "codepro"}');
	}
	
	if($_POST['unaccepted'] !== 'false'){
		$logs->add('yaResult',0,'error:unaccepted:|||label:'.$label);
		$duplicate->end('{"status": "error", "message": "unaccepted"}');
	}
	
	if($_POST['currency'] !== '643'){
		$logs->add('yaResult',0,'error:currency:|||label:'.$label);
		$duplicate->end('{"status": "error", "message": "currency"}');
	}
	
	$hash = hash('sha1',implode('&',array(
		$_POST['notification_type'],
		$_POST['operation_id'],
		$_POST['amount'],
		$_POST['currency'],
		$_POST['datetime'],
		$_POST['sender'],
		$_POST['codepro'],
		$secret,
		$_POST['label']
	)));
	if($hash !== $_POST['sha1_hash']){
		$logs->add('yaResult',0,'error:sign:|||label:'.$label);
		$duplicate->end('{"status": "error", "message": "Wrong hash"}');
	}
	
	$amount = $db->safesql($_POST['withdraw_amount']);
	
	if($labelex[0] == 'merchant'){
		if(!$merchant->checkamount($amount)){
			$logs->add('yaResult',0,'error:amount:'.$amount.'|||label:'.$label);
			$duplicate->end('{"status": "error", "message": "Wrong amount"}');
		}
		$merchant->confirm();
	}elseif($labelex[0] == 'addmoney' ){
		$bd->write("UPDATE addmoney SET status = 'Пополнено', money = ".$amount." WHERE id = {$addMoney['user_id']}");

		$transactions = new transactions();
		$transactions->create(array(
			'user_id' => $addMoney['user_id'],
			'type' => 1,
			'method' => 'addmoney',
			'method_id' => $addMoney['id'],
			'currency' => 4,
			'amount' => $amount
		));
	}else{
		$orderUtil = new order();
		$order_id = (int)$label;
		if(!$orderUtil->check_amount($order_id, $amount)){
			$logs->add('yaResult',0,'error:amount:'.$amount.'|||label:'.$label);
			$duplicate->end('{"status": "error", "message": "Wrong amount"}');
		}
		$orderUtil->confirm_order($order_id);
	}
	
	$logs->add('yaResult',0,'ok|||label:'.$label); 
	
	$duplicate->end('OK'.$label);
?>
