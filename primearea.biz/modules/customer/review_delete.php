<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include '../currency/currclass.php';
	include "../../func/customer.class.php";
	include '../../func/db.class.php';
	include "../../func/product.class.php";
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));

	
	if(!$data['h'] || !$data['i'])close('{"status": "error", "message": "parameters_empty"}');
	
	$bd = new mysql();
	$db = new db();
	$product = new product();
	$h = $bd->prepare($data['h'], 64);
	$i = $bd->prepare((int)$data['i'], 8);
	
	//Проверить существует ли отзыв по этой покупке
	$request = $bd->read("SELECT `status`, productId, price, curr FROM `order` WHERE id=".$i." LIMIT 1");
	if(!$bd->rows)close('{"status": "error", "message": "order_not_exists"}');	
	$order_status = mysql_result($request,0,0);
	$product_id = mysql_result($request,0,1);
	$price = mysql_result($request,0,2);
	$curr = mysql_result($request,0,3);
	$request = $bd->read("SELECT id, good FROM review WHERE orderId=".$i." AND del = 0  LIMIT 1");
	if(!$bd->rows)close('{"status": "error", "message": "review_not_found"}');
	$review_id = mysql_result($request,0,0);
	$evaluation_old = mysql_result($request,0,1);
	if(!$evaluation_old && !$data['agreement'])close('{"status": "error", "message": "Вы не дали согласия на удаление отзыва"}');
	//Проверить ключ, получить емейл
	$customer = new customer();
	if(!$customer->check_key($h))close('{"status": "error", "message": "key"}');
	//Если доступ только к конкретному заказу проверить совпадение
	if($customer->order_id && $customer->order_id !== $i)close('{"status": "error", "message": "stranger_customer_access"}');
	//Проверить ваша ли это покупка
	if(!$customer->check_order($product_id, $i))close('{"status": "error", "message": "stranger_order"}');
	//Удаляем отзыв
	$bd->write("UPDATE review SET del = 1, datedel = NOW() WHERE id = ".$review_id." AND del = 0 LIMIT 1");
	
	//обновить рейтинг продукта
	$product->updateRatings($product_id);

	//Пересчитать рейтинг
	$order = new order();
	$current_convert = new current_convert();
	$price = $current_convert->curr($price, $curr, 4, 0);
	
	if($evaluation_old)$order->rating($customer->seller_id, $price, 'good_review_delete', $i);
	else $order->rating($customer->seller_id, $price, 'bad_review_delete', $i);
	
	close('{"status": "ok"}');
?>