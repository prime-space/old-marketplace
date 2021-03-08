<?php
	function receivePayment($order_id, $context_id, $instance_id){
		$external_payment = new ExternalPayment($instance_id);
 		$process_options = array(
	        "request_id" => $context_id,
	        'ext_auth_success_uri' => '/',
	        'ext_auth_fail_uri' => '/payment-fail',
   		);
    	$result = $external_payment->process($process_options);

		if($result->status == 'success'){
			$order = new order();
			$order->confirm_order($order_id);
		}
	}
