<?php
/*if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

require_once('func/transactions.class.php');
require_once('func/lib/external_payment.php');
require_once('func/lib/api.php');
require_once('func/lib/api.php');
require_once 'func/PaymentAccountFetcher.php';
global $siteAddr;
try {
    $paymentAccountFetcher = new PaymentAccountFetcher($db);
    $content = new templating(file_get_contents(TPL_DIR.'addmoney_success.tpl'));
    $uri = explode('?', $_SERVER['REQUEST_URI']);
    $id = explode('/', $uri[0]);
    $id = (int)$id[3];

    if (isset($uri[1])) {
        $request = $db->super_query("
            SELECT user_id, status, money, context_id, payment_account_id
            FROM addmoney
            WHERE id = $id
        ");
        if (null === $request) {
            throw new Exception();
        }
        $userId = $request['user_id'];
        $money = $request['money'];
        $context_id_db = $request['context_id'];
        $paymentAccountId = $request['payment_account_id'];

        if ($request['status'] !== 'Пополнено') {
            $yandex_visa = $uri[1];
            $temp = explode('&', $yandex_visa);

            if (isset($temp[3])) {
                $context_id = explode('=', $temp[3]);
                $context_id = $context_id[1];
            }

            if (isset($temp[8])) {
                $sum = explode('=', $temp[8]);
                $sum = $sum[1];
            }

            if (isset($temp[6])) {
                $temp = explode('=', $temp[6]);
                $temp = $temp[1];
            }

            if (isset($temp) && $temp == 'success' && isset($sum) && isset($context_id)) {
                if ($context_id !== $context_id_db || bccomp($money, $sum, 2) == 1) {
                    throw new Exception();
                }

                $paymentAccount = $paymentAccountFetcher->getById($paymentAccountId);
                $external_payment = new ExternalPayment($paymentAccount['config']['instance_id']);
                $result = $external_payment->process([
                    "request_id" => $context_id,
                    'ext_auth_success_uri' => '/',
                    'ext_auth_fail_uri' => '/payment-fail',
                ]);

                if ($result->status != 'success') {
                    $content->set('{msg}', 'Ожидание пополнения');
                    header("refresh: 5;");
                } else {
                    $db->query("UPDATE addmoney SET status = 'Пополнено' WHERE id = " . $id);

                    $transactions = new transactions();
                    $transactions->create(array(
                        'user_id' => $userId,
                        'type' => 1,
                        'method' => 'addmoney',
                        'method_id' => $id,
                        'currency' => 4,
                        'amount' => $money
                    ));

                    header("Location: " . $siteAddr . "addmoney/success/" . $id . "/");
                    exit();
                }
            } else {
                throw new Exception();
            }
        }
    }
    $content->set('{msg}', 'Успешное пополнение');

    $page = $content->content;
    $HEAD['title'] = 'PRIMEAREA.RU - Пополнить счет';
} catch (Exception $e) {
    header("Location: ".$siteAddr."payment-error/");
    exit();
}
*/
