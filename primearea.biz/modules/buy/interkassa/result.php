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
	include "../../../func/interkassa.class.php";
	$db = new db();
	$interkassa = new interkassa();
	
	include "../../../func/logs.class.php";
	$logs = new logs();
	$logs->add('ikassaResult',false,$db->safesql(serialize($_POST)));
	
	$duplicate = new duplicate('ikassaResult');
	if(!$duplicate->access)exit('{"status": "error", "message": "duplicate"}');
	
	$transactions = new transactions();
	
	$bd = new mysql();
	$order = new order();

	if(!$_POST['ik_pm_no']){
		logs("error:interkassa|||orderId");
		$duplicate->end('{"status": "error", "message": "ik_pm_no is missing"}');
	}
	$order_id = (int)$_POST['ik_pm_no'];
	if($_POST['ik_co_id'] !== $CONFIG['interkassa']['id']){
		logs("error:interkassa|||id|||orderId:".$order_id);
		$duplicate->end('{"status": "error", "message": "Wrong id"}');
	}
	if($_POST['ik_inv_st'] !== 'success'){
		logs("error:interkassa|||notSuccess|||orderId:".$order_id);
		$duplicate->end('{"status": "error", "message": "ik_inv_st not Success"}');
	}
    if(!in_array(REMOTE_ADDR,array('151.80.190.97','151.80.190.98','151.80.190.99','151.80.190.100','151.80.190.101','151.80.190.102','151.80.190.103','151.80.190.104','151.80.190.105','151.80.190.106','151.80.190.107','35.233.69.55'),true)){
		logs("error:interkassa|||IP|||orderId:".$order_id);
		$duplicate->end('{"status": "error", "message": "Unlnow IP"}');
	}
	
	$amount =  $db->safesql($_POST['ik_am']);
	$h = $db->safesql($_POST['ik_x_h']);
	
	if(!$order->check_amount($order_id, $amount)){
		logs("error:amount:".$amount."|||pay_no:".$order_id);
		$duplicate->end('{"status": "error", "message": "Wrong amount"}');
	}
	
	if($interkassa->sign($_POST) !== $_POST['ik_sign']){
		logs("error:sign|||pay_no:".$order_id);
		$duplicate->end('{"status": "error", "message": "Wrong ik_sign"}');
	}
	$order->confirm_order($order_id);
	logs("ok|||pay_no:".$order_id);
	
	$duplicate->end('OK'.$order_id);
?>
