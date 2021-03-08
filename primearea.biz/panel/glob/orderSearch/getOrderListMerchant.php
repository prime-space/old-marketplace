<?php
	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../modules/currency/currclass.php";
	
	$bd = new mysql();
	$db = new db();
	$user = new user();
	$currency = new current_convert();

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	
	$data = json_decode($_POST['data'], true);

	$id      = 	(int)$data['id'];
	$mshop   = 	(int)$data['mshop'];
	$status  = 	$db->safesql($data['status']);
	$date1   = 	$db->safesql($data['date1']);
	$date2   = 	$db->safesql($data['date2']);
	$payed   = 	$db->safesql($data['payed']);

	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];
	
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  

	$where = '';

	//id
	if($id != ''){
		$where .= ' (mp.id = '.$id.' OR mp.payno = '.$id.') AND ';
	}
	//статус
	if($status != 'all'){
		$where .= ' mp.status = "'.$status.'" AND ';
	}
	//дата
	if($date1 != '' && $date2 != ''){
		$where .= ' (mp.ts BETWEEN "'.$date1.'" AND "'.$date2.'" ) AND ';
	}elseif($date1 != '' && $date2 == ''){
		$where .= ' (mp.ts > "'.$date1.'" ) AND ';
	}elseif($date1 == '' && $date2 != ''){
		$where .= ' (mp.ts < "'.$date2.'" ) AND ';
	}
	//метод оплаты
	if($payed != 'all'){
		$where .= ' (mp.viaId = '.$payed.') AND ';
	}
	$where .= ' 1 ';
    $data['page'] == 'cabinet' ? $where = ' mp.mshopId = '.$mshop.' ' : '';

	$request = $bd->read("SELECT mp.id, (SELECT name FROM mshops WHERE id = mp.mshopid), mp.payno, mp.amount, mp.mshopfee, 
		mp.fee, mp.feeAmount, mp.amountPay, (SELECT name FROM `mpaysyss` WHERE id = mp.viaId), mp.description, mp.tsr, mp.ts, 
		mp.mshopid, (SELECT userId FROM mshops WHERE id = mp.mshopid)userId, (SELECT login FROM user WHERE id = userId), CURRENT_TIMESTAMP, 
		mp.status, mp.amountProfit, mp.curr
		FROM `mpayments` mp
		WHERE {$where}
		ORDER BY ts DESC
		LIMIT {$elementLoadInPageStart}, {$elements_on_page}
	");						
						

  $rows = mysql_num_rows($request);

  if(!$rows && !$current_list)close(json_encode(array('status' => 'ok', 'content' => '<tr><td colspan="7" class="font_size_14 font_weight_700 text_align_c padding_10">Ничего не найдено</td></tr>')));
  if(!$rows)close(json_encode(array('status' => 'ok', 'content' => '<tr><td colspan="7" class="font_size_14 font_weight_700 text_align_c padding_10">Ничего не найдено</td></tr>')));
  
  $list = "";


  for($i=0; $i<$rows; $i++){
	
	$id =  mysql_result($request,$i,0);
	$shopid = mysql_result($request,$i,12);
	$shopName = mysql_result($request,$i,1);
	$date = substr(mysql_result($request,$i,11),0,10);
	$date2 = substr(mysql_result($request,$i,11),10,20);
	$name = strBaseOut(mysql_result($request,$i,9));
	$order_id = strBaseOut(mysql_result($request,$i,2));
	$user_id = strBaseOut(mysql_result($request,$i,13));
	$user_login = strBaseOut(mysql_result($request,$i,14));
	
	$via = mysql_result($request,$i,8);
	$amount_all = mysql_result($request,$i,3);
	$mshop_fee = mysql_result($request,$i,4);
	$amount_fee = mysql_result($request,$i,6);

	$curr = mysql_result($request,$i,18);
	$amount_user = $currency->curr(mysql_result($request,$i,7), $curr, 4, 0);

	$curr_time = mysql_result($request,$i,15);
	$accesAfter = mysql_result($request,$i,10) ? substr(mysql_result($request,$i,10),0,10) : 'Данные устарели';
	$accesAfter2 = mysql_result($request,$i,10);
	$status = mysql_result($request,$i,16);
	
	$access;
	if(strtotime($curr_time) > strtotime(mysql_result($request,$i,10))){
		$access = 'Зачислены';
	}else{

		$access = $data['page'] != 'cabinet' ? mysql_result($request,$i,10) : '<span style="color:red">Доступны после</span>';
	}

	$greenStyle = '';
	$notifElement = '';
	$_payed = '';
	$_amount = '';
	if($status == 'success'){
		$_title = 'Оплачен';
		$greenStyle = 'success';
		$notifElement = '<a class="shownotif" onclick="merchant.operation.shownotif(this,'.$id.');">&#9660;</a>';
		$_payed = "способ оплаты - ".$via;
		$_payedSum = mysql_result($request,$i,17);
		$_amount = "заплачено - $amount_user руб <br> комиссия - $amount_fee руб <br> зачисляем - $_payedSum руб";
		$fa_icon = '<i class="fa fa-check-circle-o" style="font-size:50px;color:#27a75e;"></i>';
	}elseif($status == 'cancel'){
		$_title = 'Отменен';
		$access = '';
		$fa_icon = '<i class="fa fa-times-circle-o" style="font-size:50px;color:#f4503f;;"></i>';
	}else{
		$_title = 'В ожидании';
		$access = '';
		$fa_icon = '<i class="fa fa-clock-o" style="font-size:50px;color:#ffbc24;;"></i>';
	}

	
	
	if($data['page'] != 'cabinet'){
		$list .= <<<HTML
			<tr>			
				<td data-label="Счет №" class="text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$order_id}</td>
				<td data-label="ID" class="text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$id}</td>
				<td data-label="Название товара" style="position:relative" class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align"><a onclick="merchant.admin.showshop(this,{$shopid});" >{$name} </a><div style="float: right;" class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="login - $user_login <br> магазин - $shopName">?</div></td>
				<td data-label="Стоимость" style="cursor:pointer" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align" data-toggle="tooltip" data-placement="top" data-original-title="$_amount" >{$amount_all}</td>
				<td data-label="Статус" style="width: 80px;padding: 10px;" class="font_size_12 text_align_c padding_l_5 padding_r_5 $greenStyle" data-toggle="tooltip" data-placement="top" data-original-title="$_payed">$_title</td>
				<td data-label="Дата" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$date}</td>
				<td data-label="Доступны после" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$access}</td>
				<td data-label="Уведомления" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$notifElement}</td>
			</tr>
			<tr class="notification" style="display:none">
				<td colspan="8" class="padding_10 {$greenStyle}"></td>
			</tr>
	
HTML;
    }else{
		$list .= <<<HTML
			<tr class="tr-white" style="border: 1px solid #eee;">
				<td style="padding: 0px;">
					<a href="/merchant/shop/{$mshop}/#anchor" target="_blank" class="link-list">
						<div class="list-block">
							<div class="col-md-1">
								{$fa_icon}
							</div>
							<div class="col-md-3">
								{$name}<p class="extra-text">Счет № {$order_id}<br>PRIME ID №{$id}</p>
							</div>
							<div class="col-md-2">
								{$date} <p class="extra-text">{$date2}</p>
							</div>
							<div class="col-md-3">
								{$access} <p class="extra-text">{$accesAfter2}</p>
							</div>
							<div class="col-md-3">
								<p class="price-text">{$amount_all} руб.</p>
							</div>
							<div class="clear"></div>
						</div>
					</a>
				</td>
			</tr>
HTML;


    }
  }

 	if($data['method'] != 'add' && $data['page'] != 'cabinet'){
		if($data['page'] === 'orders')$pagination_js_func = 'panel.admin.order.list.get';
		else $pagination_js_func = 'panel.user.cabinet.list.get';
		$request = $bd->read("
			SELECT COUNT(id) 
			FROM `mpayments` mp
			WHERE ".$where);
		$product_count = mysql_result($request,0,0);
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $pagination_js_func);
		//$list .= '<div id="list_add_'.$pagination_js_func.'"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="8"', $pagination );
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>