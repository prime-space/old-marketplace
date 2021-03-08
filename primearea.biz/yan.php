<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/func/config.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/func/lib/external_payment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/func/lib/api.php";

$clientId = $_GET['client_id'];
$code = $_GET['code'];
$three = $_GET['three'];

$data = ['step' => 'error'];

if (null !== $clientId) {
    $url = \API::buildObtainTokenUrl(
        $clientId,
        "https://{$CONFIG['site_domen']}/yan.php",
        ['account-info', 'payment-p2p', 'operation-details']
    );
    $data['step'] = 'getToken';
    $data['url'] = $url;
    echo '<form action="' . $data['url'] . '" method="POST">
        <input type="submit" value="Получить токен" />
    </form>';
}
if (null !== $code) {
    $data['step'] = 'showForm';
    $data['code'] = $code;
    $data['url'] = "https://{$CONFIG['site_domen']}/yan.php?three=1";
    echo '<form action="' . $data['url'] . '" method="POST">
        Идентификатор приложения<input type="text" name="client_id" />
        OAuth2 client_secret<input type="text" name="secret" />
        access token<input type="text" name="at" value="' . $data['code'] . '"/>
        <input type="submit" value="Получить" />
    </form>';
}
if (null !== $three) {
    $clientId = $_POST['client_id'];
    $instanceId = \ExternalPayment::getInstanceId($clientId);
    $at = $_POST['at'];
    $secret = $_POST['secret'];
    $access_token_response = \API::getAccessToken($clientId, $at, "https://{$CONFIG['site_domen']}/", $secret);
    if (property_exists($access_token_response, "error")) {
        $data['step'] = 'error';
    } else {
        $data['step'] = 'result';
        $data['instance_id'] = $instanceId->instance_id;
        $data['client_id'] = $clientId;
        $data['secret'] = $secret;
        $data['token'] = $access_token_response->access_token;
        echo 'instance_id: ' . $data['instance_id'] . '<br>
    client_id: ' . $data['client_id'] . '<br>
    secret: ' . $data['secret'] . '<br>
    token: ' . $data['token'];
    }
}
if ('error' === $data['step']) {
    echo 'Error';
}
