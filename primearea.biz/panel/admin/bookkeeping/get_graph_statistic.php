<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');


	if($data['period'] == 'day'){
		$request = $bd->read("SELECT sales, sales_profit, ad_graphic, ad_text, ad FROM report_money WHERE isMerchant = 1 ORDER BY date DESC LIMIT 30");
		for($i=29;$i>=0;$i--){
			$date = date("Y-m-d", (time() - 86400 * $i));
			$values['sales'][] = (float)mysql_result($request, $i, 0);
			$values['sales_profit'][] = (float)mysql_result($request, $i, 1);
			$values['ad'][] = (float)mysql_result($request, $i, 4);
			$values['ad_text'][] = (float)mysql_result($request, $i, 3);
			$values['ad_graphic'][] = (float)mysql_result($request, $i, 2);
			$values['profit'][] = (float)mysql_result($request, $i, 1) + (float)mysql_result($request, $i, 4);
			if(!($i & 1))$labels[(29 - $i)] = date("M d", (time() - 86400 * ($i + 1)));
		}
	}
	if($data['period'] == 'month'){
		$count_month = 18;
		$request = array();
		$date = new DateTime();
		for($i=1;$i<=$count_month;$i++){
			$date->modify('-1 month');
			$this_date = $date->format('Y-m').'-00';
			$this_date_end = $date->format('Y-m').'-31';
			$request[] = "SELECT SUM(sales), SUM(sales_profit), SUM(ad_graphic), SUM(ad_text), SUM(ad) FROM report_money WHERE isMerchant = 1 AND date > '".$this_date."' AND date < '".$this_date_end."' ";
		}
		
		$request = $bd->read(implode(' UNION ALL ', $request));
		
		for($i=($count_month-1);$i>=0;$i--){
			$date = date("Y-m-d", (time() - 86400 * $i));
			$values['sales'][] = (float)mysql_result($request, $i, 0);
			$values['sales_profit'][] = (float)mysql_result($request, $i, 1);
			$values['ad'][] = (float)mysql_result($request, $i, 4);
			$values['ad_text'][] = (float)mysql_result($request, $i, 3);
			$values['ad_graphic'][] = (float)mysql_result($request, $i, 2);
			$values['profit'][] = (float)mysql_result($request, $i, 1) + (float)mysql_result($request, $i, 4);
			if(!($i & 1))$labels[($count_month - $i)] = date("Y-M",  time() - $i * 2592000);
		}
	}
	
	
	$out = array('status' => 'ok', 'labels' => $labels, 'values' => $values);

	close(json_encode($out));
?>