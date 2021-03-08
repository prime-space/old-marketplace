<?php
	include '../../../func/config.php';
	include '../../../func/db.class.php';
	include '../../../func/user.class.php';
	include '../../../func/mail/mail.class.php';
	
	$db = new db();
	$user = new user(array('user', 'moder', 'admin'));
	
	if(!$user->id)exit(json_encode(array('status' => 'error','message' => 'Ошибка доступа')));
	
	//Проверка входящих переменных
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else exit('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)exit('{"status": "error", "message": "Ошибка доступа"}');
	
	if(!$data['order_id'])exit('{"status": "error", "message": "Нет данных"}');
	if(!$data['text'])exit('{"status": "error", "message": "Введите текст"}');
	
	$order_id = (int)$data['order_id'];
	$text = $db->safesql(nl2br($data['text']), true);
	$picture = (int)$data['picture'];
	
	//Проверить ваш ли это товар
	$orderInfo = $db->super_query("
		SELECT p.name, o.userIdEmail, o.unsubscribeCustomer
		FROM product p
		JOIN `order` o ON p.id = o.productId
		WHERE
			p.idUser = ".$user->id." AND
			o.id = ".$order_id."
		LIMIT 1
	");
	if(!$orderInfo['name'])exit('{"status": "error", "message": "Доступ запрещен"}');
	
	//Есть ли непрочитанные покупателем сообщения
	$notification = $db->super_query_value("
		SELECT id
		FROM message
		WHERE
			order_id = ".$order_id." AND 
			author = ".$user->id." AND
			status = 'not_read'
		LIMIT 1
	");
	
	//Создаем сообщение
	$date = date( 'Y-m-d H:i:s');
	
	if($picture == 0){
		$picture = 'NULL';
	}
	$db->query("INSERT INTO message VALUES (NULL, ".$order_id.", '".$user->id."', 'Продавец', '".$text."','".$date."', 'not_read', ".$picture.")");
	
	/***Создание письма***/
	//Проверить не отписан ли пользователь от этого заказа и нет ли у него непрочитанных писем уже
	if(!$orderInfo['unsubscribeCustomer'] && !$notification){
		$mail = new mail();
		$unsubscribe = $mail->unsubscribe($orderInfo['userIdEmail']);
		if(!$unsubscribe['un']['orderUpdate']){
			$subject = 'НОВОЕ СООБЩЕНИЕ';
			$message = 	'<p>Уважаемый покупатель!</p><br>
						<p>На площадке '.$CONFIG['site_domen'].' продавец оставил сообщение к купленному вами товару: "'.$orderInfo['name'].'"</p>
						<br>
						<p>Вы можете его прочитать в вашем личном кабинете:</p>
						<a href="'.$CONFIG['site_address'].'customer/">'.$CONFIG['site_address'].'customer/</a>
						<br>
						<p>С уважением, администрация '.$CONFIG['site_domen'].'</p>
						<br>
						<p>Письмо сформировано автоматически сервисом приема платежей '.$CONFIG['site_domen'].'</p>
						<p>Вы можете отписаться от email уведомлений на этой странице:</p>
						<a href="'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/">'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/</a>';
            $message = $db->safesql($message);
			$db->query("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$orderInfo['userIdEmail']."', 'need', CURRENT_TIMESTAMP)");
		}
	}
	exit('{"status": "ok"}');
?>
