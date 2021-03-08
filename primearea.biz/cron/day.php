<?php
	include "../func/config.php";
	include "../func/mysql.php";
	include "../func/main.php";
	include "../modules/currency/currclass.php";
	include "../modules/currency/currency.php";
	include "../func/cron.class.php";
	
	$currency = new currConvFPayC();
	$bd = new mysql();

	$cron = new cron('day', 86400);
	if($cron->stop)close();
	
	$cron->clearLogs();
	
	//update currency
	$rates = new ExchangeRatesCBRF();
	$usd = round($rates->GetRate("USD"), 2);
	$eur = round($rates->GetRate("EUR"), 2);
	$uah = round($rates->GetRate("UAH"), 2);

	$bd->write("INSERT INTO `currency` VALUES (NULL, '".date("Y-m-d")."', '".$usd."', '".$eur."', '".$uah."')");

	//delete merchant outdated orders
	$bd->write("DELETE FROM mpayments WHERE status IN('new', 'pending') AND ADDDATE(NOW(), INTERVAL -1 DAY) >  ts");

	//если у продавца отрицательный рейтинг, блокировать аккаунт
	$bd->write("
		UPDATE `user`
		SET `status` = 'blocked'
		WHERE `rating` < 0 AND `status` != 'blocked'
	");
	$user_ids = $bd->read("SELECT id FROM user WHERE status = 'blocked' ");
	$ids = array();
	for($i=0;$i<mysql_num_rows($user_ids);$i++){
		$ids[] = mysql_result($user_ids,$i,0);
	}
	$ids = implode(',', $ids);

	$bd->write("
		UPDATE `product`
		SET `block` = 'blocked', `showing` = 0
		WHERE
			idUser IN(".$ids.") AND
			block NOT IN('deleted')
	");

	$cron->end();
	close();
?>