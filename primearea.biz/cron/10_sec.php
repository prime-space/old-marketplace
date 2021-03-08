<?php
	
	include '../func/config.php';
	include '../func/mysql.php';
	include '../func/main.php';
	include '../func/cron.class.php';
	include '../func/mail/PHPMailer.class.php';
	include '../func/mail/smtp.class.php';
	
	include '../func/db.class.php';
	include '../func/transactions.class.php';
	include '../func/logs.class.php';
	
	$bd = new mysql();
	
	$cron = new cron('10_sec', 10);
	if($cron->stop)close();
	
	//освобождаем customer_session
	$timeout = time() - ($customer_session_timeout * 60);
	$bd->write("DELETE FROM customer_session WHERE time < '".$timeout."'");
	
	//освобождаем usersession
	$timeout = time() - ($userSessionLifeTime * 60 * 60 * 24);
	$bd->write("DELETE FROM usersession WHERE lastVisit < '".$timeout."'");

	//снятие резервов с товаров
	$timeout = time() - ($timeReserv * 60);
	$bd->write("UPDATE `product_text` SET `status` = 'sale', `timeReserv` = NULL WHERE `status` = 'reserved' AND `timeReserv` < ".$timeout);
	$bd->write("UPDATE `product_file` SET `status` = 'sale', `timeReserv` = NULL WHERE `status` = 'reserved' AND `timeReserv` < ".$timeout);

	//Рекоммендуемое
	$bd->write("UPDATE ad SET status = 'Окончено' 
				WHERE 	type = 'Графическое' 
					AND status = 'Активно' 
					AND `datetime` < ADDDATE( NOW( ) , INTERVAL -graphic_duration DAY )");
	$bd->write("UPDATE ad SET ad.status = 'На паузе' 
				WHERE 	ad.type = 'Текстовое' 
					AND ad.status = 'Активно' 
					AND (SELECT wmrbal FROM user WHERE id=ad.user_id LIMIT 1) <  ad.price");
	$bd->write("UPDATE ad SET ad.status = 'Активно' 
				WHERE 	ad.type = 'Текстовое' 
					AND ad.status = 'На паузе' 
					AND (SELECT wmrbal FROM user WHERE id=ad.user_id LIMIT 1) >  ad.price");

	$db = new db();
	$transactions = new transactions();
	$transactions->execute();


$cron->end();
	close();
	
?>
