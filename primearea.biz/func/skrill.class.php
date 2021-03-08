<?php
class skrill{
	
	public function checkResponse($db, $config, $POST, $type){

		require_once "SkrillValidateErrorException.class.php";

		$email       = $config['skrill']['wallet']['email'];
		$merchant_id = $config['skrill']['wallet']['merchant_id'];
		$secret      = $config['skrill']['wallet']['secret'];
		$currency    = $config['skrill']['wallet']['currency'];
		
		$response_order_id    = (int)$POST['transaction_id'];
		$response_amount      = $POST['amount'];
		$response_mb_amount   = $POST['mb_amount'];
		$response_mb_currency = $POST['mb_currency'];
		$response_email       = $POST['pay_to_email'];
		$response_merchant_id = $POST['merchant_id'];
		$response_currency    = $POST['currency'];
		$response_status      = $POST['status'];
		$response_md5_hash    = $POST['md5sig'];

		$hash  = $merchant_id;
		$hash .= $response_order_id;
		$hash .= strtoupper(md5($secret));
		$hash .= $response_mb_amount;
		$hash .= $response_mb_currency;
		$hash .= $response_status;
		$md5hash = strtoupper(md5($hash));

		if(!preg_match('/^(91\.208\.28\.|93\.191\.174\.|193\.105\.47\.|195\.69\.173\.)\d{1,2}$/', REMOTE_ADDR)){//недопустимый ip
			throw new SkrillValidateErrorException("skrill|wrong_ip|pay_no:".$response_order_id); 
		}

		if ($response_status !== '2'){
			throw new SkrillValidateErrorException("skrill|not_verified|pay_no:".$response_order_id);
		} 

		if ($response_email !== $email){
			throw new SkrillValidateErrorException("skrill|wrong_email|pay_no:".$response_order_id);
		}

		/******** 
		check if transaction is not complete already
		********/ 
		if($type == 'merchant'){
			$field = 'amountPay';
			$request = $db->super_query("SELECT id, amountPay FROM `mpayments` WHERE id=".$response_order_id." AND status = 'success' LIMIT 1");
			$request2 = $db->super_query("SELECT ".$field." FROM `mpayments` WHERE id=".$response_order_id." LIMIT 1");
		}elseif($type == 'shop'){
			$field = 'totalBuyer';
			$request = $db->super_query("SELECT id, totalBuyer FROM `order` WHERE id=".$response_order_id." AND status IN('sended', 'paid', 'review')  LIMIT 1");
			$request2 = $db->super_query("SELECT ".$field." FROM `order` WHERE id=".$response_order_id." LIMIT 1");
		}
		
		
		if ($request['id']){
			throw new SkrillValidateErrorException("skrill|already_paid|pay_no:".$response_order_id);
		} 
		/******** 
		check amount
		********/ 
		if (bccomp($response_amount, $request2[$field], 2) !== 0){ 
			throw new SkrillValidateErrorException("skrill|wrong_amount|pay_no:".$response_order_id);
		}

		if ($response_currency !== $currency){
			throw new SkrillValidateErrorException("skrill|wrong_curr|pay_no:".$response_order_id);
		}
		
		if ($md5hash !== $response_md5_hash){
			throw new SkrillValidateErrorException("skrill|md5_hash|pay_no:".$response_order_id);
		}

		return $response_order_id;

	}

}