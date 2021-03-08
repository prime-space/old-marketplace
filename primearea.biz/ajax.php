<?php
	define('PRIMEAREARU', TRUE);

	include 'func/config.php';
	include 'func/db.class.php';
	include 'func/user.class.php';
	include 'func/assist.php';
	include 'modules/currency/currclass.php';
	include 'func/mysql.php';

	sleep(1);

	$db = new db();
	$bd = new mysql();
	
	if(!$_GET['module'])exit(json_encode(array('status' => 'error','message' => 'Module name is missing')));
	if(!$_GET['method'])exit(json_encode(array('status' => 'error','message' => 'Method name is missing')));
	

	$data = json_decode($_POST['data'], true);
	if(!$data)exit(json_encode(array('status' => 'error','message' => 'Data is missing')));	
	
	$user = new user(array('user', 'moder', 'admin'));
	
	if(isset($data['token'])){
		if($data['token'] !== $user->token) exit('{"status": "error", "message": "Ошибка доступа"}');
		if(!$user->id)exit(json_encode(array('status' => 'error','message' => 'Необходимо авторизоваться')));
	}

	if(!$_GET['user']){
		switch($_GET['module']){
			case 'customer':
				include 'func/customer.class.php';
				$handler = new customer();
				$out = $handler->ajax($_GET['method']);
				break;
			case 'interkassa':
				include 'func/interkassa.class.php';
				$handler = new interkassa();
				$out = $handler->ajax($_GET['method']);
				break;
			case 'news':
				include 'func/news.class.php';
				$handler = new news();
				$out = $handler->ajax($_GET['method']);
				break;
			case 'merchant':
				include 'func/merchant.class.php';
				$handler = new merchant();
				$out = $handler->ajax($_GET['method']);
				break;
			case 'product':
				include "func/product.class.php";
				$handler = new product();
				$out = $handler->ajax($_GET['method']);
				break;
			default: exit(json_encode(array('status' => 'error','message' => 'Unknown module')));
		}
		exit(json_encode($out));
	}

	if (!isset($data['token'])) {
        exit(json_encode(['status' => 'error', 'message' => 'Ошибка доступа']));
	}

	switch($_GET['module']){
		case 'partner':
			include 'func/partner.class.php';
			$handler = new partner();
			$out = $handler->ajax($_GET['method']);
			break;
		case 'apipanel':
			include 'func/apipanel.class.php';
			$handler = new apipanel();
			$out = $handler->ajax($_GET['method']);
			break;
		case 'sales':
			include 'func/sales.class.php';
			$handler = new sales();
			$out = $handler->ajax($_GET['method']);
			break;
		case 'hiddenproducts':
			include 'func/hiddenproducts.class.php';
			$handler = new hiddenproducts();
			$out = $handler->ajax($_GET['method']);
			break;
		case 'user':
            include 'func/userUtils.class.php';
			require_once "func/auth.class.php";
            $handler = new userUtils();
			$out = $handler->ajax($_GET['method']);
			break;
		default: exit(json_encode(array('status' => 'error','message' => 'Unknown module')));
	}
	exit(json_encode($out));
?>
