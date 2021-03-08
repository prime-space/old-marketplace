<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../func/config.php";
  include "../../func/mysql.php";
  include "../../func/db.class.php";
  include "../../func/verification.php";
  include "../../func/main.php";
  include "../../func/customer.class.php";
  include "../currency/currclass.php";
  include "../../func/partner.class.php";
  include '../../func/logs.class.php';
  include '../../func/PaymentAccountFetcher.php';
  include '../../func/lib/external_payment.php';

try {
    $bd = new mysql();
    $db = new db();
    $order = new order();
    $user = new user();
    $current_convert = new current_convert();
    $paymentAccountFetcher = new PaymentAccountFetcher($db);
    bcscale(2);

    $email = $bd->prepare(trim($_POST['email']), 64);
    $via = $bd->prepare($_POST['via'], 32);
    $viaCode = $db->safesql($_POST['via_code']);
    $viaId = (int)$_POST['via_id'];
    $userGuest = 0;
    $productId = (int)$_POST['id'];
    $curr = $bd->prepare((int)$_POST['curr'], 2);
    $promocode_use = $_POST['promocode_use'] ? true : false;
    $promocode_code = $bd->prepare($_POST['promocode_code'], 16);
    if (!empty($_POST['soglNotAgree'])) {
        close('{"status": "error", "message": "sogl"}');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        close('{"status": "error", "message": "email"}');
    }
    if (!$order->product_availability($productId)) {
        close('{"status": "error", "message": "availability"}');
    }
    if (!$order->blacklist($productId, $email)) {
        close('{"status": "error", "message": "blacklist"}');
    }

    $agent = $db->safesql($_SERVER['HTTP_USER_AGENT']);
    $db->query("INSERT INTO orderIpLong (ip, orderNum, agent) VALUES ('".REMOTE_ADDR."', 1, '$agent') ON DUPLICATE KEY UPDATE orderNum = orderNum + 1, agent = VALUES(agent)");
    $isIpBlocked = $db->super_query_value("SELECT isBlocked FROM orderIpLong WHERE ip = '".REMOTE_ADDR."'");
    if ($isIpBlocked) {
        close('{"status": "error", "message": "Обратитесь в поддержку"}');
    }
    $orderIpAttempt = count($db
        ->super_query("SELECT * FROM orderIp WHERE CONCAT(p1, p2, p3, p4, p5) = '".REMOTE_ADDR."' LIMIT 3", true));
    if ($orderIpAttempt > 2) {
        close('{"status": "error", "message": "later"}');
    } else {
        $orderIpPeriod = 'p'.(floor(date('i') / 2) % 5 + 1);
        $db->query("INSERT INTO `orderIp` ($orderIpPeriod) VALUES ('".REMOTE_ADDR."')");
    }

    $request = $bd->read("SELECT `price`, `curr`, `many`, `typeObject`, (SELECT `value` FROM `setting` WHERE `ids` = 2), idUser, `name`, (SELECT `value` FROM `setting` WHERE `ids` = 12) 
                          FROM `product` WHERE `id` = '" . $productId . "' LIMIT 1");

    $user_id = mysql_result($request, 0, 5);

    $request2 = $bd->read("SELECT `percent_out`
                          FROM `user` WHERE `id` = '" . $user_id . "'");
    $percent_out = mysql_result($request2, 0, 0);

    $price = $current_convert->curr(mysql_result($request, 0, 0), mysql_result($request, 0, 1), $curr, 0);
    if (!$price) {
        exit('c');
    }

    $additionalFee = 0;
    $oldPrice = $price;

    $data = [];
    $receiver = '';
    $paymentAccount = null;
    if ($via == 'paypal') {
        //add fee to price for paypal

        $paypal_settings = $bd->read("SELECT `value` FROM `setting` WHERE `ids` IN (10,11)  ORDER BY id");
        $paypal_percent = mysql_result($paypal_settings, 0, 0);
        $paypal_val = mysql_result($paypal_settings, 1, 0);

        $price = $price * (100 + intval($paypal_percent)) / 100;
        $price_ad = $current_convert->curr($paypal_val, 4, $curr, 0);
        $price += $price_ad;
        $additionalFee += $price_ad;
        $price = round($price, 2);

    } elseif ($via == 'skrill') {
        $price = $order->addFeeByCode($viaCode, $price);

        $skrill_fee_val = $CONFIG['skrill']['wallet']['provider_fee'];
        $curr = $CONFIG['skrill']['wallet']['currency_val'];
        $price = bcadd($price, $skrill_fee_val, 2);
        $additionalFee = bcadd($additionalFee, $skrill_fee_val, 2);
    } elseif ($via == 'qiwi') {
        $price = $order->addFeeByCode($viaCode, $price);
        $paymentAccount = $paymentAccountFetcher->fetchOne(
            PaymentAccountFetcher::PAYMENT_SYSTEM_QIWI_ID,
            PaymentAccountFetcher::ENABLED_FOR_SHOP
        );
    } elseif ($via == 'wm') {
        switch ($viaCode) {
            case 'WMRM':
                $receiver = $CONFIG['wm']['purses']['r'];
                break;
            case 'wmz':
                $receiver = $CONFIG['wm']['purses']['z'];
                break;
            case 'wme':
                $receiver = $CONFIG['wm']['purses']['e'];
                break;
            case 'wmu':
                $receiver = $CONFIG['wm']['purses']['u'];
                break;
        }
    } elseif ($via === 'yandex') {
        $paymentAccount = $paymentAccountFetcher->fetchOne(
            PaymentAccountFetcher::PAYMENT_SYSTEM_YANDEX_ID,
            PaymentAccountFetcher::ENABLED_FOR_SHOP
        );
        $receiver = $paymentAccount['config']['purse'];
    } elseif ($via === 'yandex2') {
        $paymentAccount = $paymentAccountFetcher->fetchOne(
            PaymentAccountFetcher::PAYMENT_SYSTEM_YANDEX_CARD_ID,
            PaymentAccountFetcher::ENABLED_FOR_SHOP
        );
        $receiver = $paymentAccount['config']['purse'];
    }

    $paymentAccountId = null === $paymentAccount ? 0 : $paymentAccount['id'];

    $many = mysql_result($request, 0, 2);
    $typeObject = mysql_result($request, 0, 3);
    $defaultComm = mysql_result($request, 0, 4);
    $priv_settings = unserialize(mysql_result($request, 0, 7));

    if ($user->priv_type($user_id) && $user->priv_type($user_id) == 4) {
        $defaultComm = (float)number_format($priv_settings['system_com_' . $user->priv_type($user_id)], 1, '.', '');
    }

    //set global percent or local for seller
    $percent_out == null ? $commPer = $defaultComm : $commPer = $percent_out;
    $product_name = mysql_result($request, 0, 6);

    $promocode_el_id_use = 0;
    if ($promocode_use) {
        $promocode_check = promocode::checkpromocode($productId, $promocode_code);
        $discountPer = $promocode_check['discount'];
        $promocode_el_id_use = $promocode_check['el_id'];
    } else {
        $discountPer = discountCheck($email, $productId);
    }

    if ($discountPer != 0) {
        $discount = round(($price * $discountPer / 100), 2);
    } else {
        $discount = 0;
    }
    if ($commPer != 0) {
        $comm = round((($price - $discount) * $commPer / 100), 2);
    } else {
        $commPer = 0;
    }


    $totalBuyer = $price - $discount;
    if ($totalBuyer < 0.01) {
        $totalBuyer = 0.01;
    }

    $totalBuyerWithoutFee = $oldPrice - $discount;
    if ($totalBuyerWithoutFee < 0.01) {
        $totalBuyerWithoutFee = 0.01;
    }

    $totalSeller = $totalBuyerWithoutFee - $comm;

    $partner = new partner();
    $partnerData = $partner->order($productId, $totalSeller);

    if ($partnerData['percent'] && $partnerData['percent'] > 75) {
        close('{"status": "error", "message": "percent"}');
    }

    $totalSeller = bcsub($totalSeller, $partnerData['fee']);
    if ($totalSeller < 0.01) {
        $totalSeller = 0.01;
    }

    $id = $bd->write("
        INSERT INTO `order`
        (
            user_id,
            userIdEmail,
            productId,
            price,
            curr,
            comm,
            commper,
            discount,
            discountper,
            totalBuyer,
            totalSeller,
            promocode_el_id_use,
            partner,
            partnerFee,
            partnerPercent,
            pay_method_id,
            payment_account_id,
            ip
        )
        VALUES (
            " . $user_id . ",
            '" . $email . "', 
            '" . $productId . "', 
            '" . $price . "', 
            '" . $curr . "',
            '" . $comm . "',
            '" . $commPer . "',
            '" . $discount . "',
            '" . $discountPer . "',
            '" . $totalBuyer . "',
            '" . $totalSeller . "',
            " . $promocode_el_id_use . ",
            " . $partnerData['id'] . ",
            " . $partnerData['fee'] . ",
            " . $partnerData['percent'] . ",
            $viaId,
            $paymentAccountId,
            '".REMOTE_ADDR."'
      )
    ");

    $customer = new customer();
    $h = $customer->create_key($email, $id);

    SetCookie('bh' . $id, $h, time() + ($timeReserv * 60), '/', $CONFIG['site_domen'], $CONFIG['cookie_SSL'], true);

    if ($many == 0) {
        $time = time();
        if ($typeObject == 1) {
            $tbWhereObject = "product_text";
        } else {
            $tbWhereObject = "product_file";
        }

        $bd->write("UPDATE `" . $tbWhereObject . "` 
                  SET `status` = 'reserved', `orderid` = " . $id . ", `timeReserv` = " . $time . " 
            WHERE `idProduct` = '" . $productId . "' AND `status` = 'sale'
            LIMIT 1");

        $request2 = $bd->read("SELECT `id` FROM `" . $tbWhereObject . "` WHERE `idProduct` = '" . $productId . "' AND `orderid` = " . $id . " LIMIT 1");
        $objectId = mysql_result($request2, 0, 0);
        logs("orderDetails:idProd=" . $productId . ", type=" . $many . ", typeObject=" . $typeObject . ", orderId=" . $id . ", objectId=" . $objectId);
        if (!$objectId) {
            close('{"status": "error", "message": "availability"}');
        }

    }

    $UPDATE = [];
    $data['successURL'] = $CONFIG['site_address'] . 'customer/' . $id . '/' . $h . '/';
    $data['failURL'] = $CONFIG['site_address'] . 'payment-error/';

    $amountParts = $order->amountParts($totalBuyer);

    if ($via === 'yandex2') {
        $externalPayment = new ExternalPayment($paymentAccount['config']['instance_id']);
        $externalPaymentRequest = $externalPayment->request([
            'pattern_id' => 'p2p',
            'to' => $paymentAccount['config']['purse'],
            'amount_due' => $totalBuyer,
            'paymentType' =>'AC',
            'message' => str_replace(['{productName}', '{orderId}'], [$product_name, $id], $CONFIG['yandex']['card_comment']),
        ]);
        if ($externalPaymentRequest->status !== 'success') {
            throw new Exception('Ошибка платежной системы. Используйте другую систему');
        }
        $externalPaymentRequestId = $externalPaymentRequest->request_id;
        $process_options = array(
            'request_id' => $externalPaymentRequestId,
            'ext_auth_success_uri' => $data['successURL'],
            'ext_auth_fail_uri' => $data['failURL'],
            'paymentType' =>'AC',
        );
        $externalPaymentResult = $externalPayment->process([
            'request_id' => $externalPaymentRequestId,
            'ext_auth_success_uri' => $data['successURL'],
            'ext_auth_fail_uri' => $data['failURL'],
            'paymentType' =>'AC',
        ]);
        if (!$externalPaymentResult->acs_params->cps_context_id) {
            throw new Exception('Ошибка платежной системы. Используйте другую систему');
        }
        $UPDATE[] = "yandex_cart_context_id = '".$db->safesql($externalPaymentResult->acs_params->cps_context_id)."'";
        $data['yandex_cart_acs_uri'] = $externalPaymentResult->acs_uri;
        $data['yandex_cart_cps_context_id'] = $externalPaymentResult->acs_params->cps_context_id;
        $data['yandex_cart_paymentType'] = $externalPaymentResult->acs_params->paymentType;
    } elseif ($via === 'qiwi') {
        $data['qiwiUri'] =
            "https://qiwi.com/payment/form/99"
            ."?amountFraction={$amountParts[1]}"
            ."&extra%5B%27comment%27%5D=%D0%9F%D0%B5%D1%80%D0%B5%D0%B2%D0%BE%D0%B4+%28ID%3AO$id%29"
            ."&extra%5B%27account%27%5D=%2B{$paymentAccount['config']['account']}"
            ."&amountInteger={$amountParts[0]}"
            ."&currency=643";
    } elseif ($via === 'primePayer') {
        $primePayerData = [
            'shop' => $CONFIG['primePayer']['id'],
            'payment' => $id,
            'amount' => $totalBuyer,
            'description' =>  htmlspecialchars_decode($product_name, ENT_QUOTES),
            'currency' => 3,
            'via' => $viaCode,
            'success' => $data['successURL'],
            'email' => $_POST['email'],
        ];
        ksort($primePayerData, SORT_STRING);
        $data['sign'] = hash('sha256', implode(':', $primePayerData).':'.$CONFIG['primePayer']['key']);
        $primePayerData['description'] = htmlspecialchars($primePayerData['description'], ENT_QUOTES);
    } elseif ($via = 'ikassa') {
        $ikassaData = [
            'ik_co_id' => $CONFIG['interkassa']['id'],
            'ik_pm_no' => $id,
            'ik_cli' => $email,
            'ik_am' => $totalBuyer,
            'ik_cur' => 'RUB',
            'ik_desc' => htmlspecialchars_decode($product_name, ENT_QUOTES),
        ];
        if ($viaCode !== 'other') {
            $ikassaData['ik_act'] = 'process';
            $ikassaData['ik_pw_via'] = $viaCode;
        }
        ksort($ikassaData, SORT_STRING);
        array_push($ikassaData, $CONFIG['interkassa']['key']);
        $ik_sign = implode(':', $ikassaData);
        $ik_sign = base64_encode(hash('sha256', $ik_sign, true));
        $data['ik_sign'] = $ik_sign;
        $ikassaData['ik_desc'] = htmlspecialchars($ikassaData['ik_desc'], ENT_QUOTES);
    }

    if (count($UPDATE) > 0) {
        $db->query("
            UPDATE `order`
            SET ".implode(', ', $UPDATE)."
            WHERE id = $id
        ");
    }

    $data['email'] = $_POST['email'];
    $data['status'] = 'ok';
    $data['id'] = $id;
    $data['totalBuyer'] = $totalBuyer;
    $data['h'] = $h;
    $data['receiver'] = $receiver;
    $data['receiverPaypal'] = $CONFIG['paypal']['receiver'];
    $data['product_name'] = $product_name;
    $data['product_id'] = $productId;
    $data['paypalN'] = $CONFIG['site_address'] . 'modules/buy/paypal/result.php';
    $data['payment_fee'] = bcsub($price, $oldPrice, 2);

} catch (Exception $e) {
    $data = [
        'status' => 'error',
        'message' => $e->getMessage(),
    ];
}
close(json_encode($data));
