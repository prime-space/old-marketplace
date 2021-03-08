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
	$bd = new mysql();
	$duplicate = new duplicate('wmResult');

	if(!$duplicate->access)exit('{"status": "error", "message": "duplicate"}');
	
	if(!$_POST['LMI_PAYMENT_NO'])$duplicate->end('{"status": "error", "message": "PAYMENT_NO is missing"}');

	$pay_no = $bd->prepare((int)$_POST['LMI_PAYMENT_NO'], 16);
	$amount = $bd->prepare($_POST['LMI_PAYMENT_AMOUNT'], 16);
	$payee_purse = $bd->prepare($_POST['LMI_PAYEE_PURSE'], 16);
	$customer_purse = $bd->prepare($_POST['LMI_PAYER_PURSE'], 16);
	
	$shp_userid = $bd->prepare((int)$_POST['shp_userid'], 8);

	if(!preg_match('/^(212\.118\.48\.|212\.158\.173\.|91\.200\.28\.|91\.227\.52\.)\d{1,3}$/', REMOTE_ADDR)){//недопустимый ip
		logs("error:ip|||pay_no:".$pay_no);
		$duplicate->end('{"status": "error", "message": "Unknow ip"}');
	}
	
	if($_POST['LMI_MODE']){//тестовый режим
		logs("error:test_mode|||pay_no:".$pay_no);
		$duplicate->end('{"status": "error", "message": "Test mode"}');
	}
	$access_purses = array_merge(array_values($CONFIG['wm']['purses']), $CONFIG['wm']['access_purses']);
	if(!in_array($payee_purse, $access_purses, true)){//неверный кошелек получения
		logs("error:purse|||pay_no:".$pay_no."|||payee_purse:".$payee_purse);
		$duplicate->end('{"status": "error", "message": "Unknow purse"}');
	}
	
	if($shp_userid){//Пополнение счета
		
		$bd->write("UPDATE addmoney SET status = 'Пополнено', money = ".$amount." WHERE id = ".$pay_no." LIMIT 1");
		
		$transactions = new transactions();
		$transactions->create(array(
			'user_id' => $shp_userid,
			'type' => 1,
			'method' => 'addmoney',
			'method_id' => $pay_no,
			'currency' => 4,
			'amount' => $amount
		));
		
		$duplicate->end('OK'.$pay_no);
	}
	
	$order = new order();
	
	if(!$order->check_amount($pay_no, $amount)){//не совпадает цена
		logs("error:amount:".$amount."|||pay_no:".$pay_no);
		$duplicate->end('{"status": "error", "message": "Wrong amount"}');
	}
	
	$hash = strtoupper(hash('sha256', 
		$_POST['LMI_PAYEE_PURSE'].
		$_POST['LMI_PAYMENT_AMOUNT'].
		$_POST['LMI_PAYMENT_NO'].
		$_POST['LMI_MODE'].
		$_POST['LMI_SYS_INVS_NO'].
		$_POST['LMI_SYS_TRANS_NO'].
		$_POST['LMI_SYS_TRANS_DATE'].
		$wm_key.
		$customer_purse.
		$_POST['LMI_PAYER_WM']
	));
	
	if($hash !== $_POST['LMI_HASH']){//неверный хэш
		logs("error:hash|||pay_no:".$pay_no);
		$duplicate->end('{"status": "error", "message": "Wrong amount"}');
	}
	
	$order->confirm_order($pay_no, $customer_purse);
	logs("ok|||pay_no:".$pay_no); 
	
	$duplicate->end('{"status": "ok"}');
?>
