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


	// payment_success.php 
	$paypalemail = $CONFIG['paypal']['receiver'];
	$currency    = "USD";              

	$db = new db();
	$bd = new mysql();
	$transactions = new transactions();
	$order_id = $bd->prepare((int)$_POST['item_number'], 64);
	/******** 
	запрашиваем подтверждение транзакции 
	********/ 
	$postdata=""; 
	foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&"; 
	$postdata .= "cmd=_notify-validate"; 
	$curl = curl_init("https://www.paypal.com/cgi-bin/webscr"); 
	curl_setopt ($curl, CURLOPT_HEADER, 0); 
	curl_setopt ($curl, CURLOPT_POST, 1); 
	curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata); 
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: primearea'));
	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 2); 
	$response = curl_exec ($curl); 
	curl_close ($curl); 
	
	if ($response != "VERIFIED"){
		logs("paypal|not_verified|pay_no:".$order_id);
		die("Что-то не так ...");
	} 
	if ($_POST['payment_status'] != "Completed"){
		logs("paypal|waiting|pay_no:".$order_id);
		die("Ожидание поступления ...");
	} 

	/******** 
	проверяем получателя платежа и тип транзакции, и выходим, если не наш аккаунт 
	в $paypalemail - наш  primary e-mail, поэтому проверяем receiver_email 
	********/ 
	if ($_POST['receiver_email'] != $paypalemail){
		logs("paypal|wrong_email|pay_no:".$order_id);
	  	die("Неверный получатель ..."); 
	}

	/******** 
	убедимся в том, что эта транзакция не 
	была обработана ранее 
	********/ 
	$request = $bd->read("SELECT id, totalBuyer FROM `order` WHERE id=".$order_id." AND status IN('sended', 'paid', 'review')  LIMIT 1");
	$request2 = $bd->read("SELECT totalBuyer FROM `order` WHERE id=".$order_id." LIMIT 1");
	$order = mysql_result($request,0,0);
	$totalBuyer = mysql_result($request2,0,0);
	
	if ($order){
		logs("paypal|alreaty_paid|pay_no:".$order_id);
		die ("Уже оплачено ...");
	} 
	/******** 
	проверяем сумму платежа 
	********/ 
	if ($totalBuyer != $_POST["mc_gross"] || $_POST["mc_currency"] != $currency){ 
		logs("paypal|wron_amount|pay_no:".$order_id);
		die("Неправильная сумма."); 
	} 

	$order = new order();
	$order->confirm_order($order_id);
	
	?>
