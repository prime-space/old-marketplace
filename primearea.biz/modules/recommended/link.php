<?php
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/main.php";
	
	include "../../func/db.class.php";
	include "../../func/transactions.class.php";
	
	if(!$_COOKIE['guest']){
		$guest =  hash("sha256", microtime().rand(1111111, 9999999));
		SetCookie('guest', $guest, (time()+60*60*24) , '/', $CONFIG['site_domen'], FALSE, TRUE);
	}else $guest = $_COOKIE['guest'];
  
	$bd = new mysql();
	$db = new db();
	$transactions = new transactions();
	
  $ip = REMOTE_ADDR;


	$ad_id = $bd->prepare((int)$_GET['id'], 8);
	
	$request = $bd->read("SELECT user_id, product_id, type, price  FROM ad WHERE status='Активно' AND id = ".$ad_id." LIMIT 1");
	
	if(!$bd->rows){
		header("Location:/"); 
		close();
	}
	
	$user_id = mysql_result($request,0,0);
	$product_id = mysql_result($request,0,1);
	$type = mysql_result($request,0,2);
	$price = mysql_result($request,0,3);
	
	$bd->write("UPDATE ad SET click = click + 1 WHERE id = ".$ad_id." LIMIT 1");
	if($type == 'Текстовое'){
		$guest =$bd->prepare($guest, 64);

		$bd->read("SELECT ip FROM ad_pay_click WHERE ad_id = ".$ad_id." AND ip = '".$ip."' AND datetime > ADDDATE( NOW( ) , INTERVAL -1 DAY ) LIMIT 1");
		$ip_matched = false;
		if($bd->rows){
			$ip_matched = true;
		}

		$bd->read("SELECT id, ip FROM ad_pay_click WHERE ad_id = ".$ad_id." AND guest = '".$guest."' LIMIT 1");


		if(!$bd->rows && !$ip_matched){
			$click_id = $bd->write("INSERT INTO ad_pay_click VALUES(NULL, ".$ad_id.", '".$guest."',  CURRENT_TIMESTAMP, '".$ip."')");
			$transactions->create(array(
				'user_id' => $user_id,
				'type' => 0,
				'method' => 'clickAd',
				'method_id' => $click_id,
				'currency' => 4,
				'amount' => '-'.$price
			));
		}
	}
	
	header("Location:/product/".$product_id."/"); 
	close();
?>