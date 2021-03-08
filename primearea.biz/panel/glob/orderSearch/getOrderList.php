<?php
	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../modules/currency/currclass.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	
	$data = json_decode($_POST['data'], true);

	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];
	
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  
	$where = "o.status IN('review','sended','paid')";
	$having = '';
	if($user->role == 'admin' || $user->role == 'moder'){
		if($data['page'] != 'order' && $data['page'] != 'orders'){
			$where .= " AND o.user_id = ".$user->id;
		}
	}else{
		$where .= " AND o.user_id = ".$user->id;
	}

	if($data['par'] == 'order') $where .= " AND o.id = ".$bd->prepare($data['req'], 8);
	if($data['par'] == 'new') $having = "HAVING msg";

	if($data['par'] == 'email'){
		$email = $bd->prepare($data['req'],32);
		$request = $bd->read("SELECT `id` FROM `user` WHERE `email` = '".$email."' LIMIT 1"); 
		if(mysql_num_rows($request) != 0){
			$where .= " AND (
		                    o.userIdEmail = '".$email."' 
						 OR o.userIdEmail = '".mysql_result($request,0,0)."'
						  )";
		}else $where .= " AND o.userIdEmail = '".$email."'";
	}
  
  $request = $bd->read("SELECT SQL_CALC_FOUND_ROWS o.productId, o.date, p.name, o.id, o.totalSeller, o.curr, o.time_money_in, o.rating,
								(SELECT value FROM setting WHERE ids = 3),
								(SELECT id FROM message WHERE order_id = o.id AND status = 'not_read' AND author != {$user->id} LIMIT 1) as msg,

								(CASE
									WHEN t.executed IS NULL THEN t.executing
									ELSE 'Зачислены'
								END), o.partner, t.executed
						FROM `order` o
						LEFT JOIN `product` p ON p.id = o.productId
						LEFT JOIN transactions t ON t.method_id = o.id AND t.method = 'sale'
						WHERE {$where}
						GROUP BY o.id
						$having
						ORDER BY o.id DESC
						LIMIT {$elementLoadInPageStart}, {$elements_on_page}");
						

  $rows = mysql_num_rows($request);
  $product_count = $bd->total_rows;

  if(!$rows && !$current_list)close(json_encode(array('status' => 'ok', 'content' => '<tr><td colspan="8" class="font_size_14 font_weight_700 text_align_c padding_10">Ничего не найдено</td></tr>')));
  if(!$rows)close(json_encode(array('status' => 'ok', 'content' => '<tr><td colspan="8" class="font_size_14 font_weight_700 text_align_c padding_10">Ничего не найдено</td></tr>')));
  
  $list = "";


  for($i=0; $i<$rows; $i++){
	$product_id = mysql_result($request,$i,0);
	$date = substr(mysql_result($request,$i,1),0,10);
	$date2 = substr(mysql_result($request,$i,1),10,20);
	$name = strBaseOut(mysql_result($request,$i,2));
	$order_id = strBaseOut(mysql_result($request,$i,3));

	$time_money_in = mysql_result($request,$i,6);

	$rating = mysql_result($request,$i,7) == 0.00 ?  '' : mysql_result($request,$i,7);

	//$time_money_retention = mysql_result($request,$i,8);

	$message = mysql_result($request,$i,9) != NULL ? '<div style="display:inline-block;vertical-align:middle;"><img src="/stylisation/images/new_message.png"></div>' : '';
	
	$curr = new current_convert();
	$curr_price = mysql_result($request,$i,5) ? mysql_result($request,$i,5) : 4;//для отображения старых покупок
	$total_seller = $data['page'] == 'cabinet' ?  $curr->curr(mysql_result($request,$i,4), $curr_price, $curr_price) : $curr->curr(mysql_result($request,$i,4), $curr_price, $curr_price);
	 
	 //if($time_money_in == 0)$accesAfter = "доступны";
	 //else $accesAfter = date('d-m-Y H:i:s',($time_money_in + ($time_money_retention * 60 * 60)));

	$dt2 = '';
	if(mysql_result($request,$i,10)){
		$accesAfter =  mysql_result($request, $i, 12) == NULL ? $data['page'] == 'cabinet' ? '<span style="color:red">Доступны после</span>' : mysql_result($request,$i,10) : mysql_result($request,$i,10);
		$dt2 = mysql_result($request,$i,10);
	}else{
	 	$accesAfter = 'Данные устарели';
	}

	$accesAfter2 = mysql_result($request, $i, 12) == NULL ?  $dt2 : mysql_result($request,$i,12) ;
	$partner = mysql_result($request,$i,11) ? '<img src="/stylisation/images/partner24.png" style="vertical-align: middle;" title="Через партнера">' : '';
	if($data['page'] != 'cabinet'){
		$list .= <<<HTML
			<tr>
				<td data-label="Счет №" class="text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$order_id}{$partner}</td>
				<td data-label="Название товара" class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align"><a href="/product/{$product_id}/" target="_blank">{$name}</a></td>
				<td data-label="Дата" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$date}</td>
				<td data-label="Сумма" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$total_seller}</td>
				<td data-label="Доступны после" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$accesAfter}</td>
				<td data-label="Информация" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align"><a href="/panel/sales/{$order_id}/" target="_blank">подробнее</a><div>{$message}</div></td>
				<td data-label="Рейтинг" class="padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$rating}</td>
			</tr>
HTML;
	}else{
		$list .= <<<HTML
			<tr class="tr-white" style="border: 1px solid #eee;">
				<td style="padding: 0px;">
				<a href="/panel/sales/{$order_id}/" target="_blank" class="link-list">
					<div class="list-block">
						<div class="col-md-1 hidden-xs"><i class="fa fa-check-circle-o" style="font-size:45px;color:#27a75e;"></i></div>
						<div class="col-md-3">
							{$name}<p class="extra-text">№ {$order_id}</p>
						</div>
						<div class="col-md-2">
							{$date} <p class="extra-text">{$date2}</p>
						</div>
						<div class="col-md-3">
							{$accesAfter} <p class="extra-text">{$accesAfter2}</p>
						</div>
						<div class="col-md-3">
							<p class="price-text">+{$total_seller}</p>
						</div>
						<div class="clear"></div>
					</div>
					</a>
				</td>
			</tr>
HTML;

	}
  }
  
 	if($data['method'] != 'add' && $data['page'] !== 'cabinet'){
		if($data['page'] === 'order')$pagination_js_func = 'panel.admin.order.list.get';
		else $pagination_js_func = 'panel.user.cabinet.list.get';
		
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $pagination_js_func);
		$pagination = str_replace( 'colspan="6"', 'colspan="8"', $pagination );
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
