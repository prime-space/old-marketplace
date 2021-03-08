<?php
	
	include '../../func/config.php';
	include '../../func/mysql.php';
    include "../../func/db.class.php";
	include '../../func/main.php';
	include '../currency/currclass.php';

try {
    if (!$_POST['method_id']) {
        close();
    }

    $bd = new mysql();
    $db = new db();
    $order = new order();
    $currency = new current_convert();
    bcscale(2);

    $method_id = $bd->prepare((int)$_POST['method_id'], 8);
    $product_id = $bd->prepare((int)$_POST['product_id'], 8);
    $discount = isset($_POST['discount']) ? $_POST['discount'] : 0;

    $request = $bd->read("SELECT price, curr FROM product WHERE id=" . $product_id . " LIMIT 1");
    $price = mysql_result($request, 0, 0);
    $curr = mysql_result($request, 0, 1);

    $request = $bd->read("SELECT info, code, curr, system FROM pay_method WHERE id=" . $method_id . " LIMIT 1");

    $code = mysql_result($request, 0, 1);
    $curr_need = mysql_result($request, 0, 2);
    $system = mysql_result($request, 0, 3);

    $price = $order->addFeeByCode($code, $price);

    $price = bcsub($price, bcdiv(bcmul($price, $discount), 100));

    $price = $currency->curr($price, $curr, $curr_need);

    $data = [
        'info' => mysql_result($request, 0, 0),
        'code' => $code,
        'price' => $price,
        'system' => mysql_result($request, 0, 3)
    ];
} catch (Exception $e) {
    $data = [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];
}
close(json_encode($data));
