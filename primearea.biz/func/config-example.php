<?php
define('REMOTE_ADDR', $_SERVER["HTTP_X_FORWARDED_FOR"]);
define('TPL_DIR', dirname(dirname(__FILE__)).'/template/default/');
date_default_timezone_set('Europe/Minsk');
//site domain
$domain = '~';
$cookieIsSsl = '~'; //boolean
$siteAddr = "http".($cookieIsSsl ? "s" : "")."://".$domain."/";//Адрес сайта

$elementLoadInPage = 60; //Количество подгружаемых элементов

$userSessionLifeTime = 1; //Время жизни сессии в днях
$customer_session_timeout = 30; //Время жизни сессии покупателя в минутах

$adminEmail = "admin@primearea.biz"; //емейл админа

$cronIp = '~';

$timeReserv = 15; //время резервирования товара в минутах

$wm_key = '~';

$robokassa_MrchLogin = '~';
$robokassa_MerchantPass1 = '~';
$robokassa_MerchantPass2 = '~';

/*Положительный отзыв покупателя увеличивает рейтинг на величину (цена товара / 150).
Отрицательный отзыв уменьшает рейтинг на величину (цена товара / 100).
Продажа без отзыва увеличивает рейтинг на величину (цена товара / 250).*/
$rating_coeff = array(	'buy' => 250,
						'good' => 150,
						'bad' => 100,
						'partner' => 250);

$CONFIG = array(
    'salt' => '~',
    'main_page_name' => 'main',
	'mysql' => array(
		'host' => '~',
		'user' => '~',
		'pass' => '~',
		'db'   => '~',
        'queue_db' => 'queue',
	),
	'sphinx' => [
	    'host' => '~',
    ],
    'cdn' => '~',
	'recaptcha' => [
	    'key1' => '~',
	    'key2' => '~',
    ],
	'site_domen' => $domain,
	'site_address' => $siteAddr,
	'title' => 'default title',
	'version' => '29032017',
	'cookie_SSL' => $cookieIsSsl,
    'wm' => [
        'purses' => [
            'r' => '~',
            'z' => '~',
            'e' => '~',
            'u' => '~',
        ],
        'access_purses' => [],
    ],
	'wmx2' => array(
		'wmid' => '~',
		'pursesrc' => '~',
		'period' => 3,
		'kwm' => '~',
		'pass' => '~',
	),
    'merchant' => [
        'secret' => '~',
        'sendUrl' => '~',
    ],
	'interkassa' => array(
		'id' => '~',
		'key' => '~'
	),
    'primePayer' => [
        'id' => '~',
        'key' => '~',
    ],
    'primePayerMerchant' => [
        'id' => '~',
        'key' => '~',
    ],
    'primePayerAddMoney' => [
        'id' => '~',
        'key' => '~',
    ],
	'yandex' => array(
		'receiver' => '~',
		'secret' => '~',
        'instance_id' => '~',
        'autopayments' => [
        	'client_id'     => '~',
        	'secret'        => '~',
        ],
        'card_comment' => '{productName} [#{orderId}]',
	),
	'paypal'=>array(
		'receiver' => '~',
	),
	'skrill'=>[
		'wallet'=>[
			'url'            => '~',
			'email'          => '~',
			'secret'         => '~',
			'merchant_id'    => '~',
			'currency'       => '~',
			'currency_val'   => '~',
			'provider_fee' => '~'
		]
	],
    'qiwi' => [
        'account' => '~',
        'token'   => '~',
    ],
    'sessionlifetime' => 1,

	'smtp' => array(
		'host' => '~',
		'port' => '25',
		'login' => '~',
		'password' => '~',
		'secure' => 'tls',
		'from_email' => 'admin@primearea.biz',
		'from_name' => 'PRIMEAREA'
	),
    'cashout' => [
        'wm' => [
            'enabled' => true,
            'name' => 'Webmoney кошелек',
            'user_purse_field' => 'wmr',
            'curr' => 'wmr',
        ],
        'yandex' => [
            'enabled' => true,
            'name' => 'Yandex кошелек',
            'user_purse_field' => 'yandex_purse',
            'curr' => 'yar',
        ],
        'qiwi' => [
            'enabled' => true,
            'name' => 'QIWI кошелек (+{qiwi_autopayments_fee}%)',
            'user_purse_field' => 'qiwi_purse',
            'curr' => 'qiwi',
        ],
    ],
);

$mySQLhost   = $CONFIG['mysql']['host'];
$mySQLname   = $CONFIG['mysql']['user'];
$mySQLpass   = $CONFIG['mysql']['pass'];
$mySQLdbname = $CONFIG['mysql']['db'];
