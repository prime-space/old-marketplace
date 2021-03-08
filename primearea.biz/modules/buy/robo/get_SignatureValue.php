<?php
	include '../../../func/config.php';
	
	$nOutSum = $_POST['nOutSum'];
	$nInvId = $_POST['nInvId'];
	$shph = $_POST['shph'];
	
	$SignatureValue = 	$robokassa_MrchLogin.':'
						.$nOutSum.':'
						.$nInvId.':'
						.$robokassa_MerchantPass1
						.':shph='.$shph;
	
	$SignatureValue = md5($SignatureValue);
	exit('{"value": "'.$SignatureValue.'"}');
?>