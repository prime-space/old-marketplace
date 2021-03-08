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
	$db = new db();
	
	$duplicate = new duplicate('roboResult');
	if(!$duplicate->access)exit('{"status": "error", "message": "duplicate"}');
	
	
	$transactions = new transactions();
	
	$bd = new mysql();
	$order = new order();

	if(!$_POST['InvId'])$duplicate->end('{"status": "error", "message": "InvId is missing"}');
	
	$amount_long = $bd->prepare($_POST['OutSum'], 16);
	$amount =  substr($amount_long, 0,-4);
	$order_id = $bd->prepare((int)$_POST['InvId'], 8);
	$shph = $bd->prepare($_POST['shph'], 64);
	$shp_userid = $bd->prepare((int)$_POST['shp_userid'], 8);
	
	if($shp_userid){//Пополнение счета
		$hash = strtoupper(md5(
			$amount_long
			.':'.$order_id
			.':'.$robokassa_MerchantPass2
			.':shp_userid='.$shp_userid
		));	
		if($hash !== $_POST['SignatureValue']){//неверный хэш
			logs("error:hash|||addmoney:pay_no:".$order_id);
			$duplicate->end('{"status": "error", "message": "Wrong hash"}');
		}
		$bd->write("UPDATE addmoney SET status = 'Пополнено', money = ".$amount." WHERE id = ".$order_id." LIMIT 1");
		
		$transactions->create(array(
			'user_id' => $shp_userid,
			'type' => 1,
			'method' => 'addmoney',
			'method_id' => $order_id,
			'currency' => 4,
			'amount' => $amount
		));
		
		$duplicate->end('OK'.$order_id);
	}
	
	if(!$order->check_amount($order_id, $amount)){//не совпадает цена
		logs("error:amount:".$amount."|||pay_no:".$order_id);
		$duplicate->end('{"status": "error", "message": "Wrong amount"}');
	}
	
	$hash = strtoupper(md5(
		$amount_long
		.':'.$order_id
		.':'.$robokassa_MerchantPass2
		.':shph='.$shph
	));
	
	if($hash !== $_POST['SignatureValue']){//неверный хэш
		logs("error:hash|||pay_no:".$order_id);
		$duplicate->end('{"status": "error", "message": "Wrong hash"}');
	}
	$order->confirm_order($order_id);
	logs("ok|||pay_no:".$order_id);
	
	$duplicate->end('OK'.$order_id);
?>