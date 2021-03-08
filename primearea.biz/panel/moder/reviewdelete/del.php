<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";
	include "../../../func/product.class.php";
	include '../../../modules/currency/currclass.php';
	
	$bd = new mysql();
	$db = new db();
	$user = new user();  
	$product = new product();

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$review_id = (int)$data['id'];

	$request = $bd->read("
		SELECT r.good, o.price, r.idProduct, o.curr, p.idUser, o.id
		FROM `order` o
		INNER JOIN `review` r ON o.id = r.orderId
		INNER JOIN product p ON  p.id = o.productId
		WHERE r.id = '".$review_id."' AND r.del = 0
		LIMIT 1
	");
	
	if(!$bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Отзыв не найден')));
	
	$good = mysql_result($request,0,0);
	$price = mysql_result($request,0,1);
	$curr = mysql_result($request,0,3);
	$productId = mysql_result($request,0,2);
	$seller_id = mysql_result($request,0,4);
	$order_id = mysql_result($request,0,5);

	if($good)close(json_encode(array('status' => 'error', 'message' => 'Удалить можно только отрицательный отзыв')));

	$order = new order();
	$current_convert = new current_convert();
	$price = $current_convert->curr($price, $curr, 4, 0);
	
	$order->rating($seller_id, $price, 'bad_review_delete', $order_id);

	$bd->write("UPDATE review SET del = 2, datedel = NOW() WHERE id = ".$review_id." LIMIT 1");

	//обновить рейтинг продукта
	$product->updateRatings($productId);

	close(json_encode(array('status' => 'ok')));
?>