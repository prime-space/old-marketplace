<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include "../currency/currclass.php";
    include "../../func/db.class.php";
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	
	if(!$data['code'])close('{"status": "error", "message": "Введите промо-код"}');
	if(!$data['product_id'])close('{"status": "error", "message": "Отсутствует идентификатор товара"}');
	
	$bd = new mysql();
    $db = new db();
	$current_convert = new current_convert();
	
	$product_id = (int)$data['product_id'];
	$code = $bd->prepare($data['code'], 16);
	
	
	$check = promocode::checkpromocode($product_id, $code);
	
	if(!$check['discount'] && !$check['another'])close('{"status": "error", "message": "Скидка по данному промо-коду отсутствует"}');
	if(!$check['discount'] && $check['another'])close(json_encode(array('status' => 'error', 'message' => 'С помощью данного промо-кода можно получить скидку, но на <a target="_blank" href="?p=checkpromocode&code='.$code.'">другие товары</a>')));

	if (isset($data['viaCode'])) {
        $order = new order();
        $viaCode = $db->safesql($data['viaCode']);
        $price = $order->addFeeByCode($viaCode, $check['preprice']);
    }

	$lowprice = $current_convert->curr(($price - ($price * $check['discount'] / 100)),$check['current'],4);
	
	$content = "Скидка по промо-коду составит ".$check['discount']."%, стоимость ".$lowprice;
	
	close(json_encode([
	    'status' => 'ok',
        'message' => $content,
        'lowprice' => $lowprice,
        'discount' => $check['discount'],
        'apply' => $data['apply'],
    ]));
