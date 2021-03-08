<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include '../currency/currclass.php';
	include "../../func/customer.class.php";
	include "../../func/db.class.php";

	//Проверка входящих переменных
	if(!$_POST['h'] || !$_POST['i'] || !$_POST['text'])close('{"status": "error", "message": "parameters_empty"}');	
	
	$bd = new mysql();
	$db = new db();
	$h = $bd->prepare($_POST['h'], 64);
	$i = (int) $_POST['i'];
	$text = $bd->prepare(nl2br($_POST['text']), 600);
	$picture = (int)$_POST['picture'];

	//Проверить существует ли заказ с таким номером
	$request = $bd->read("
        SELECT p.id, p.name, u.email
        FROM `order` o
        JOIN product p ON p.id = o.productId
        JOIN user u ON u.id = p.idUser
        WHERE o.id = $i
    ");
	if(!$bd->rows)close('{"status": "error", "message": "order_not_exists"}');	
	$product_id = mysql_result($request,0,0);
	$productName = mysql_result($request,0,1);
	$sellerEmail = mysql_result($request,0,2);
	//Проверить ключ, получить емейл
	$customer = new customer();
	if(!$customer->check_key($h))close('{"status": "error", "message": "key"}');
	//Если доступ только к конкретному заказу проверить совпадение
	if($customer->order_id && $customer->order_id != $i)close('{"status": "error", "message": "stranger_customer_access"}');
	//Проверить ваша ли это покупка
	if(!$customer->check_order($product_id, $i))close('{"status": "error", "message": "stranger_order"}');
	//Редактируем отзыв
	$date = date( 'Y-m-d H:i:s');
	if($picture == 0){
		$picture = 'NULL';
	}
	$bd->write("INSERT INTO message VALUES (NULL, ".$i.", '".$customer->email."', 'Покупатель', '".$text."','".$date."', 'not_read', ".$picture.")");

$subject = 'НОВОЕ СООБЩЕНИЕ';
$message = "<p>Вам поступило новое сообщение от покупателя. </p>
						<p>Товар: \"$productName\"</p>
						<br>
						<p>Счет № {$i}</p>
						<p>Подробнее:  <a href=\"{$CONFIG['site_address']}panel/sales/$i/\">{$CONFIG['site_address']}panel/sales/$i/</a></p>
						<br>
						<p>Письмо сформировано автоматически и не требует ответа.</p>
						<br>
						<p>C Уважением, команда {$CONFIG['site_domen']}</p>
				";
$message = $db->safesql($message);
$db->query("
					INSERT INTO `mail`
						(subject, message, `to`, `status`)
					VALUES
						('$subject', '$message', '{$sellerEmail}', 'need')
				");

	close('{"status": "ok"}');
?>
