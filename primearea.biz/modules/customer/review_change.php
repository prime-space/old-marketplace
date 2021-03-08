<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include '../../func/db.class.php';
	include '../currency/currclass.php';
	include "../../func/customer.class.php";
	include "../../func/product.class.php";
	
	//Проверка входящих переменных
	if(!$_POST['h'] || !$_POST['i'] || !$_POST['evaluation'] || !$_POST['text'])close('{"status": "error", "message": "parameters_empty"}');	
	
	$bd = new mysql();
	$db = new db();
	$product = new product();
	$h = $bd->prepare($_POST['h'], 64);
	$i = $bd->prepare((int)$_POST['i'], 8);
	$evaluation = $bd->prepare($_POST['evaluation'], 4);
	$evaluation = $evaluation === 'true' ? 1 : 0;
	$text = $bd->prepare(nl2br($_POST['text']), 600);
	
	//Проверить существует ли отзыв по этой покупке
	$request = $bd->read("SELECT `status`, productId, price, curr FROM `order` WHERE id=".$i." LIMIT 1");
	if(!$bd->rows)close('{"status": "error", "message": "order_not_exists"}');	
	$order_status = mysql_result($request,0,0);
	$product_id = mysql_result($request,0,1);
	$price = mysql_result($request,0,2);
	$curr = mysql_result($request,0,3);
	$request = $bd->read("SELECT id, good FROM review WHERE orderId=".$i." LIMIT 1");
	if(!$bd->rows)close('{"status": "error", "message": "review_not_found"}');
	$review_id = mysql_result($request,0,0);
	$evaluation_old = mysql_result($request,0,1);
	//Проверить ключ, получить емейл
	$customer = new customer();
	if(!$customer->check_key($h))close('{"status": "error", "message": "key"}');
	//Если доступ только к конкретному заказу проверить совпадение
	if($customer->order_id && $customer->order_id !== $i)close('{"status": "error", "message": "stranger_customer_access"}');
	//Проверить ваша ли это покупка
	if(!$customer->check_order($product_id, $i))close('{"status": "error", "message": "stranger_order"}');
	//Если отзыв плохой, то с момента покупки не должно было пройти 30 дней.
	if(!$evaluation && ( time() - (30 * 24 * 60 * 60) ) > $customer->order_date )close('{"status": "error", "message": "timeout_bad_review"}');
	//Редактируем отзыв
	$date = date( 'Y-m-d H:i:s');
	$bd->write("UPDATE review SET text = '".$text."', good = ".$evaluation.", date = '".$date."' WHERE id = ".$review_id." LIMIT 1");

	//обновить рейтинг продукта
	$product->updateRatings($product_id);
	
	//Пересчитать рейтинг
	$order = new order();
	$current_convert = new current_convert();
	$price = $current_convert->curr($price, $curr, 4, 0);
	
	if(!$evaluation_old && $evaluation)$order->rating($customer->seller_id, $price, 'bad_to_good', $i);
	if($evaluation_old && !$evaluation)$order->rating($customer->seller_id, $price, 'good_to_bad', $i);
	
	close('{"status": "ok"}');
?>