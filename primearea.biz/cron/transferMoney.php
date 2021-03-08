<?php
	/*include '../func/config.php';
	include '../func/mysql.php';
	include '../func/main.php';
	include '../modules//currency/currclass.php';
	include "../func/cron.class.php";
	//if(REMOTE_ADDR != $cronIp)exit();
	
	$bd = new mysql();
	$curr = new current_convert();	
	
	$cron = new cron('transferMoney', 60);
	if($cron->stop)close();

	$request = $bd->read("SELECT value FROM setting WHERE ids = 3  LIMIT 1");
	$time_money_retention = mysql_result($request,0,0) * 60 * 60;
   
	$time_money_transfer = time() - $time_money_retention;
   
	$request = $bd->read("	SELECT id, productId, totalSeller, curr FROM `order` 
							WHERE time_money_in < ".$time_money_transfer." AND time_money_in != 0");
	$rows = mysql_num_rows($request);
	echo $rows.'q';
	if($rows == 0){
		$cron->end();
		close();
	}
	
   
	for($i=0;$i<$rows;$i++){
		$orderId = mysql_result($request,$i,0);
		$productId = mysql_result($request,$i,1);
		
		$totalSeller = $curr->curr(mysql_result($request,$i,2), mysql_result($request,$i,3), 4, 0);
		
		$requestTwo = $bd->read("	SELECT u.wmrbal, u.id FROM user u
									WHERE u.id = (SELECT idUser FROM product WHERE id = '".$productId."')
									LIMIT 1");
		$wmrBal = mysql_result($requestTwo,0,0);
		$userId = mysql_result($requestTwo,0,1);
		$newWmrBal = $wmrBal + $totalSeller;

		$bd->write("UPDATE user SET wmrBal = '".$newWmrBal."' WHERE id = ".$userId." LIMIT 1");
		$bd->write("UPDATE `order` SET time_money_in = 0 WHERE id = ".$orderId." LIMIT 1");
	}
   
	$cron->end();
	close();*/
?>