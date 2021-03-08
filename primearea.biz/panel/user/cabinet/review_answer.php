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
	
	if(!$data['review_id'])exit('{"status": "error", "message": "Нет данных"}');
	if(!$data['text'])exit('{"status": "error", "message": "Введите текст"}');
	
	$review_id = (int)$data['review_id'];
	$text = $db->safesql(nl2br($data['text']), true);
	
	//Проверить существует ли отзыв по этой покупке
	$review = $db->super_query("SELECT id, good, orderId FROM review WHERE id=".$review_id." LIMIT 1");
	if(!$review['id'])exit('{"status": "error", "message": "Ошибка доступа"}');
	
	//Проверить ваш ли это товар
	$order = $db->super_query("SELECT productId, userIdEmail, unsubscribeCustomer FROM `order` WHERE id=".$review['orderId']." LIMIT 1");
	$product = $db->super_query("
		SELECT id 
		FROM product
		WHERE
			idUser = ".$user->id." AND
			id = ".$order['productId']."
		LIMIT 1
	");
	if(!$product['id'])exit('{"status": "error", "message": "Ошибка доступа"}');
	//Проверить не оставлен ли уже ответ к отзыву
	$review_answer = $db->super_query("SELECT id FROM review_answer WHERE reviewId=".$review_id." LIMIT 1");
	if($review_answer['id'])exit('{"status": "error", "message": "К данному отзыву уже написан ответ"}');
	//Создать ответ
	$date = date( 'Y-m-d H:i:s');
	$db->query("INSERT INTO review_answer VALUES (NULL, ".$review_id.", '".$text."', '".$date."')");
	//Создание письма при ответе на плохой отзыв	
	if(!$review['good'] && !$order['unsubscribeCustomer']){
		$mail = new mail();
		$unsubscribe = $mail->unsubscribe($order['userIdEmail']);
		if(!$unsubscribe['un']['orderUpdate']){
			$subject = 'ОТВЕТ НА ОТЗЫВ';
			$message = 	'<p>Уважаемый покупатель!</p><br>
						<p>Продавец, у которого вы приобрели товар и затем оставили отрицательный отзыв, добавил свой комментарий.</p>
						<a href="'.$CONFIG['site_address'].'?p=customer">'.$CONFIG['site_address'].'?p=customer</a>
						<br>
						<p>Письмо сформировано автоматически сервисом приема платежей '.$CONFIG['site_domen'].'</p>
						<p>Вы можете отписаться от email уведомлений на этой странице:</p>
						<a href="'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/">'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/</a>';
            $message = $db->safesql($message);
			$db->query("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$order['userIdEmail']."', 'need', CURRENT_TIMESTAMP)");
		}
	}
	exit('{"status": "ok"}');
?>
