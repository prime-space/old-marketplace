 <?php 
	include "../func/config.php";
	include "../func/mysql.php";
	include "../func/main.php";
	include "../func/cron.class.php";
	require_once("../modules/wmx2/WMXI.php");
 
	$bd = new mysql();

	$cron = new cron('wmx2', 10);
	if($cron->stop)close();

	$bd->read("SELECT id FROM setting WHERE ids = 7 AND value = 1 LIMIT 1");
	if(!$bd->rows){
		$cron->end();
		close('Автовыплаты отключены');
	}
 
	$request = $bd->read("
		SELECT 	
			c.id, c.amount, u.wmr, 
			(SELECT value FROM setting WHERE ids = 6 LIMIT 1)
		FROM cashout c
		LEFT JOIN wmx2 w ON w.cashout_id = c.id
		JOIN user u ON u.id = c.userId
		WHERE 	w.id IS NULL
			AND	c.status = 'process'
			AND c.currency = 'wmr'
		LIMIT 1
	");
	if(!$bd->rows){
		$cron->end();
		close();
	}
	$cashout_id = mysql_result($request,0,0);
	$primearea_amount = mysql_result($request,0,1);
	$pursedest = mysql_result($request,0,2);
	$primearea_comiss = mysql_result($request,0,3);
	
	$amount = (float)shop::price_format($primearea_amount - ceil($primearea_amount * $primearea_comiss) / 100);
	$wmx2_id = $bd->write("
		INSERT INTO wmx2 
		VALUES(
			NULL, 
			".$cashout_id.", 
			".$amount.", 
			'".$pursedest."', 
			NULL, 
			NULL,
			NULL,
			NOW(),
			NULL
		)
	");
	$tranid = 1;//cashout_id
	$wminvid = 0;//№ счета по webmoney
	$pcode = shop::random_str(5);//Код протекции
	$desc = 'Перевод из сервиса '.$CONFIG['site_domen'];//Описание
	
	$wmxi = new WMXI(realpath('../modules/wmx2/WMXI.crt'), 'UTF-8');
	$wmkey = array('pass' => $CONFIG['wmx2']['pass'], 'file' => '../modules/wmx2/'.$CONFIG['wmx2']['kwm']);
	$wmxi->Classic($CONFIG['wmx2']['wmid'], $wmkey);
	
	$res = $wmxi->X2(
		$wmx2_id,                  		# номер перевода
		$CONFIG['wmx2']['pursesrc'],    # номер кошелька с которого выполняется перевод (отправитель)
		$pursedest,      				# номер кошелька, но который выполняется перевод (получатель)
		$amount,                		# переводимая сумма
		$CONFIG['wmx2']['period'],      # срок протекции сделки в днях
		$pcode,              			# код протекции сделки
		$desc,  						# описание оплачиваемого товара или услуги
		$wminvid,               		# номер счета (в системе WebMoney), по которому выполняется перевод
		1                   			# учитывать разрешение получателя
	);
	foreach($res->toObject()->children() as $child => $val){
		if($child == 'retval'){$retval = (int)$val; continue;}
		if($child == 'operation'){
			foreach($val->children() as $child_op => $val_op)
				if($child_op == 'comiss'){$comiss = $val_op; continue;}
			continue;	
		}
	}
	
	$errortext = $bd->prepare($res->ErrorText(),1024);
	$comiss = $comiss ? $comiss : 'NULL';
	$bd->write("
		UPDATE wmx2 
		SET 
			comiss = ".$comiss.",
			retval = ".$retval.",
			errortext = '".$errortext."',
			datetime_execute = NOW()
		WHERE id = ".$wmx2_id."
		LIMIT 1
	");
	
	if($retval === 0){
		$bd->write("
			UPDATE cashout 
			SET 
				status = 'performed',
				protect = '".$pcode."'
			WHERE id = ".$cashout_id."
			LIMIT 1				
		");
	}
	/*
	echo '<pre>';
	print_r($res->toObject());
	echo '</pre>';
	echo '<pre>';
	var_dump($res->ErrorCode());
	echo '</pre>';
	echo '<pre>';
	var_dump($res->ErrorText());
	echo '</pre>';*/
	
	$cron->end();
	close();
?>
