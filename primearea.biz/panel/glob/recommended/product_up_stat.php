<?php

	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../modules/currency/currclass.php";
	
	$bd = new mysql();
	$db = new db();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	$priv_settings = $db->super_query('SELECT value FROM setting WHERE ids = 12 ');
	$priv_settings = unserialize($priv_settings['value']);
	$request = $db->super_query("SELECT priv.type, count(priv.id) as count, SUM(tr.amount) as amount
		FROM user_privileges priv
		JOIN `transactions` tr on tr.method_id = priv.id AND tr.method = 'priv'  
		GROUP BY type ", true
	);

	foreach ($request as $key => $value) {
		
		switch ($value['type']) {
			case 1:
				$bronze_count = $value['count'];
				$value['amount'] ? $bronze_amount = $value['amount'] : 0;
				break;
			case 2:
				$silver_count = $value['count'];
				$value['amount'] ? $silver_amount = $value['amount'] : 0;
				break;
			case 3:
				$gold_count = $value['count'];
				$value['amount'] ? $gold_amount = $value['amount'] : 0;
				break;
			case 4:
				$diamond_count = $value['count'];
				$value['amount'] ? $diamond_amount = $value['amount'] : 0;
				break;
		}
	}
	
	$counts = array((int)$bronze_count, (int)$silver_count, (int)$gold_count, (int)$diamond_count);

	$bronze_amount ? $bronze_amount = (int)$bronze_amount : $bronze_amount = 0;
	$silver_amount ? $silver_amount = (int)$silver_amount : $silver_amount = 0;
	$gold_amount ? $gold_amount = (int)$gold_amount : $gold_amount = 0;
	$diamond_amount ? $diamond_amount = (int)$diamond_amount : $diamond_amount = 0;
	$amounts = array(abs($bronze_amount), abs($silver_amount), abs($gold_amount), abs($diamond_amount));


	close(json_encode(array('status' => 'ok', 'amounts' => $amounts, 'counts' => $counts)));
	