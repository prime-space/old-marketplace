<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/func/messages.class.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/func/partner.class.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/func/customer.class.php';

	//panel mode (shop or merchant)
	$mode = $_COOKIE['panel_mode'] ? $_COOKIE['panel_mode'] : 'shop';

	//user information
	$userinfo = file_get_contents(TPL_DIR.'/main/sidebar/logged.tpl');
	$userinfo = str_replace("{login}", $user->login, $userinfo);
	$pro = $user->active_privileges() ? 'PRO SELLER' : 'UNKNOWN SELLER';
	$userinfo = str_replace("{pro}", $pro, $userinfo);
	
	//Аватарка
	$request = $db->super_query("
        SELECT  u.id, p.path
        FROM user u
        LEFT JOIN picture p ON p.id = u.picture
        WHERE u.id = ".$user->id."
        LIMIT 1
    ");
    $src = $request['path'] ? "/picture/".$request['path']."recommended.jpg" : "/style/img/man2.jpg";
    $src = $CONFIG['cdn'].$src;
	$avatar = '<img src='.$src.' alt="Продавец" style="cursor:pointer" onclick="panel.user.cabinet.edit_avatar(this.src);" >';

	//сообщение об активации
	$acivation_message = !$user->random_key ? file_get_contents(TPL_DIR.'/main/sidebar/activation/message.tpl') : '';
	$blocked_user = $user->status == 'blocked' ? file_get_contents(TPL_DIR.'/main/sidebar/blocked.tpl') : '';

	//меню
	$merchant_menu = $tpl->ify('merchant_menu');
	$no_merchant_shop = '';
	if($mode == 'shop'){
		$menuUser = file_get_contents(TPL_DIR.'/main/sidebar/menus/shop.tpl');
		$tpl->content = str_replace($merchant_menu['orig'], $merchant_menu['else'], $tpl->content);
	}else{
		$menuUser = file_get_contents(TPL_DIR.'/main/sidebar/menus/merchant.tpl');
		$tpl->content = str_replace($merchant_menu['orig'], $merchant_menu['if'], $tpl->content);

		$request = $db->query("SELECT id,name,status FROM mshops WHERE userId = ".$user->id." ORDER BY id DESC");
		$current_shop = $_COOKIE['mshop_id'];
		if($db->num_rows($request)){
			$tpl->fory('shops');
			while($row = $db->get_row($request)){
				
				!$current_shop ? $current_shop = $row['id'] : '';
				$shopName = (strlen($row['name']) > 15) ? substr($row['name'],0,15).'...' : $row['name'];

				$tpl->fory_cycle(array(
					'shopid' => $row['id'],
					'mshopName' => $row['name'] ? $shopName.' id - '.$row['id'] : 'Не назван'.' id - '.$row['id'],
					'selected' => $_COOKIE['mshop_id'] == $row['id'] ? 'selected' : ''
				));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		}else{
			$tpl->fory('shops');
			$tpl->fory_cycle(array(
				'shopid' => '0',
				'mshopName' => 'Не найден',
				'selected' => ''
			));
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
			$no_merchant_shop = file_get_contents(TPL_DIR.'/main/sidebar/no_mshop.tpl');

			$current_shop = 'undefinded';
		}
	}
	$menuAdmin = $user->role == 'admin' || $user->role == 'moder' ? file_get_contents(TPL_DIR.'/main/sidebar/menus/admin_menu.tpl') : '';

	//поддержка
	$messages = new messages();
	$to = $messages->getRecipient($user);
	$numMessages = $messages->numMessages($to);
	
	//партнерская программа
	$partner = new partner();
	$partnerMessages = $partner->haveMessages($user->id);
	$partnerNotifications = $partner->numNotifications($user->id);
	
	//новые сообщения
	$customer = new customer();
	$cabinetMessages = $customer->numNewReviews($user->id);
	
	//хорошие и плохие отзывы
	$cabinetNegMessages = $customer->numNegMessages($user->id);
	$cabinetMsg = $customer->numMessages($user->id);
