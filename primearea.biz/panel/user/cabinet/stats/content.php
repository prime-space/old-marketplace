<?php
	include_once '../../../../func/main.php';
	include_once '../../../../func/config.php';
	include_once '../../../../func/db.class.php';
	include_once '../../../../func/mysql.php';
	include_once '../../../../func/news.class.php';
	include_once '../../../../func/tpl.class.php';
	include_once '../../../../modules/currency/currclass.php';

	$db = new db();
	$bd = new mysql();
	$user = new user(array('user', 'moder', 'admin'));
	$curr = new currConvFPayC();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))exit(json_encode(array('status' => 'error','message' => 'Ошибка доступа')));
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else exit('{"status": "error", "message": "Нет данных"}');
	if($data['token'] !== $user->token)exit('{"status": "error", "message": "Ошибка доступа"}');
	
	$tpl = new templating(file_get_contents('content.tpl'));
	$id = (int)$data['id'];

	//текущий
	$periods = array(0,1,7,30,90,365);
	$period = $periods[(int)$data['period']];
	$dt = $period == 1 ? " BETWEEN DATE(ADDDATE(NOW(),INTERVAL -1 DAY)) AND NOW()" : "BETWEEN DATE(ADDDATE(NOW(),INTERVAL -".$period." DAY)) AND NOW()";
	
	if($data['type'] == 'merchant'){
		$db->query("
			SELECT COUNT(o.id) AS count,(CASE WHEN SUM(o.amountProfit) IS NULL THEN 0 ELSE SUM(o.amountProfit) END) AS profit, o.status
			FROM(
				SELECT dt
				FROM dates
				WHERE dt ".$dt."
			)d
			LEFT JOIN(
				SELECT id,amountProfit,DATE(ts) AS dt, status
				FROM `mpayments`
				WHERE
					mshopId = ".$id." AND
					ts ".$dt." AND
					NOT (viaID = 1) 
			)o ON o.dt = d.dt
			GROUP BY o.status
		");
	}else{
		$db->query("SELECT o.date,
			(CASE WHEN SUM(o.totalSeller) IS NULL THEN 0 ELSE SUM(o.totalSeller * CASE
				WHEN o.curr = 1 THEN ".$curr->c['usd']."
				WHEN o.curr = 2 THEN ".$curr->c['uah']."
				WHEN o.curr = 3 THEN ".$curr->c['eur']."
				ELSE 1 END)  END) AS profit, 
			o.status, COUNT(o.id) as count
			FROM `order` o
			INNER JOIN `product` p
			ON p.id = o.productId
			WHERE p.idUser = ".$user->id." 
			AND o.date ".$dt."
			GROUP BY o.status"
		);
	}

	$errors = 0;
	$success = 0;
	$average_profit = 0;
	$profit = 0;
	while($row = $db->get_row()){

		if($row['status'] == 'pay' || $row['status'] == 'new' || $row['status'] == 'pending' || $row['status'] == 'cancel' || $row['status'] == null){
			$errors += $row['count'];
		}else{
			$success += $row['count'];
			$profit += round($row['profit']);
		} 
	}
	$average_profit = round($profit/$success);

	$tpl->set('{errors}', $errors);
	$tpl->set('{success}', $success);
	$tpl->set('{average_profit}', $average_profit);
	$tpl->set('{profit}', $profit);

	//предыдущий
	$periods = array(1,2,14,60,180,730);
	$period_last = $periods[(int)$data['period']];
	$dt = $period_last == 2 ? " BETWEEN DATE(ADDDATE(NOW(),INTERVAL -2 DAY)) AND DATE(ADDDATE(NOW(),INTERVAL -1 DAY))" : "BETWEEN DATE(ADDDATE(NOW(),INTERVAL -".$period_last." DAY)) AND DATE(ADDDATE(NOW(),INTERVAL -".$period." DAY))";
	
	if($data['type'] == 'merchant'){
		$db->query("
			SELECT COUNT(o.id) AS count,(CASE WHEN SUM(o.amountProfit) IS NULL THEN 0 ELSE SUM(o.amountProfit) END) AS profit, o.status
			FROM(
				SELECT dt
				FROM dates
				WHERE dt ".$dt."
			)d
			LEFT JOIN(
				SELECT id,amountProfit,DATE(ts) AS dt, status
				FROM `mpayments`
				WHERE
					mshopId = ".$id." AND
					ts ".$dt." AND
					NOT (viaID = 1) 
			)o ON o.dt = d.dt
			GROUP BY o.status
		");
	}else{
		$db->query("SELECT o.date, 
			(CASE WHEN SUM(o.totalSeller) IS NULL THEN 0 ELSE SUM(o.totalSeller * CASE
				WHEN o.curr = 1 THEN ".$curr->c['usd']."
				WHEN o.curr = 2 THEN ".$curr->c['uah']."
				WHEN o.curr = 3 THEN ".$curr->c['eur']."
				ELSE 1 END)  END) AS profit, 
			o.status, COUNT(o.id) as count
			FROM `order` o
			INNER JOIN `product` p
			ON p.id = o.productId
			WHERE p.idUser = ".$user->id." 
			AND o.date ".$dt."
			GROUP BY o.status"
		);	
	}

	$errors_last = 0;
	$success_last = 0;
	$average_profit_last = 0;
	$profit_last = 0;
	while($row = $db->get_row()){
		if($row['status'] == 'pay' || $row['status'] == 'new' || $row['status'] == 'pending' || $row['status'] == 'cancel' || $row['status'] == null){
			$errors_last += $row['count'];
		}else{
			$success_last += $row['count'];
			$profit_last += round($row['profit']);
		} 
	}
	$average_profit_last = round($profit_last/$success_last);

	// //считать проценты
	$errors_last = intval(($errors - $errors_last) * 100 / $errors);
	$success_last = intval(($success - $success_last) * 100 / $success);
	$average_profit_last = intval(($average_profit - $average_profit_last) * 100 / $average_profit);
	$profit_last = intval(($profit - $profit_last) * 100 / $profit);

	$errC = $errors_last <= 0 ? 'green' : 'red';
	$succC = $success_last >= 0 ? 'green' : 'red';
	$avgC = $average_profit_last >= 0 ? 'green' : 'red';
	$profC = $profit_last >= 0 ? 'green' : 'red';

	if( $errC == 'red' ){
		$errC_icon = '<i class="fa fa-arrow-circle-o-down margin-right-5"></i>';
	}else{
		$errC_icon = '<i class="fa fa-arrow-circle-o-up margin-right-5"></i>';
	}

	if( $succC == 'red' ){
		$succC_icon = '<i class="fa fa-arrow-circle-o-down margin-right-5"></i>';
	}else{
		$succC_icon = '<i class="fa fa-arrow-circle-o-up margin-right-5"></i>';
	}

	if( $avgC == 'red' ){
		$avgC_icon = '<i class="fa fa-arrow-circle-o-down margin-right-5"></i>';
	}else{
		$avgC_icon = '<i class="fa fa-arrow-circle-o-up margin-right-5"></i>';
	}

	if( $profC == 'red' ){
		$profC_icon = '<i class="fa fa-arrow-circle-o-down margin-right-5"></i>';
	}else{
		$profC_icon = '<i class="fa fa-arrow-circle-o-up margin-right-5"></i>';
	}


	$tpl->set('{errors_last}', '<div style="color:'.$errC.'">'.$errC_icon.''.$errors_last.'%</div>');
	$tpl->set('{success_last}', '<div style="color:'.$succC.'">'.$succC_icon.''.$success_last.'%</div>');
	$tpl->set('{average_profit_last}', '<div style="color:'.$avgC.'">'.$avgC_icon.''.$average_profit_last.'%</div>');
	$tpl->set('{profit_last}', '<div style="color:'.$profC.'">'.$profC_icon.''.$profit_last.'%</div>');

	exit(json_encode(array('status' => 'ok','content' => $tpl->content)));
