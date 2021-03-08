<?php
include "../../../func/config.php";
include "../../../func/mysql.php";
include "../../../func/main.php";
include "../../currency/currclass.php";

include "../../../func/mail/mail.class.php";
include "../../../func/db.class.php";
include "../../../func/transactions.class.php";
include "../../../func/partner.class.php";
include "../../../func/logs.class.php";

$db = new db();

$logs = new logs();
$logs->add('primepayerResult',false,$db->safesql(serialize($_POST)));

$transactions = new transactions();

$bd = new mysql();
$order = new order();

if (!$_POST['payment']) {
    logs("error:primepayer|||payment");
    exit('{"status": "error", "message": "payment is missing"}');
}
$order_id = (int)$_POST['payment'];
$amount = $_POST['amount'];
if (!in_array(REMOTE_ADDR, array('109.120.152.109', '145.239.84.249'), true)) {
    logs("error:primepayer|||IP|||orderId:".$order_id);
    exit('{"status": "error", "message": "Unlnow IP"}');
}

$data = $_POST;
unset($data['sign']);
ksort($data, SORT_STRING);
$sign = hash('sha256', sprintf('%s:%s', implode(':', $data), $CONFIG['primePayer']['key']));

if ($sign !== $_POST['sign']) {
    logs("error:sign|||pay_no:".$order_id);
    exit('{"status": "error", "message": "Wrong sign"}');
}

if (!isset($_GET['type']) || !in_array($_GET['type'], ['order', 'payment', 'addmoney'], true)) {
    logs("error:app|||pay_no:".$order_id);
    exit('{"status": "error", "message": "Unknown app '.$_GET['type'].'"}');
}

switch ($_GET['type']) {
    case 'order':
        $orderUtil = new order();
        if (!$orderUtil->check_amount($order_id, $amount)) {
            logs("error:amount|||pay_no:".$order_id);
            exit('{"status": "error", "message": "Wrong amount"}');
        }
        break;
    case 'payment':
        require_once '../../../func/merchant.class.php';
        $merchant = new merchant();
        $externalinit = $merchant->externalinit($order_id);
        if ($externalinit !== true) {
            logs("error:externalinit|||pay_no:".$order_id);
            exit('{"status": "error", "message": "externalinit"}');
        }
        if (!$merchant->checkamount($amount)) {
            logs("error:amount|||pay_no:".$order_id);
            exit('{"status": "error", "message": "Wrong amount"}');
        }
        break;
    case 'addmoney':
        require_once "../../../func/addmoney.class.php";
        $addmoneyUtil = new addmoney();
        if (!$addmoneyUtil->isAmountValid($order_id, $amount)) {
            logs("error:amount|||pay_no:".$order_id);
            exit('{"status": "error", "message": "Wrong amount"}');
        }
        break;
}

$dbQueue = new db(0, 'queue');
$message = json_encode(['type' => $_GET['type'], 'id' => $order_id,]);
$dbQueue->query("CALL sp_create_message('exec_payment', '$message')");

logs("ok|||pay_no:".$order_id);

exit('OK'.$order_id);
