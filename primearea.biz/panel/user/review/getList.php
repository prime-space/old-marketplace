<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'ok', 'content' => 'Ошибка доступа')));

	$where = "WHERE 1";
 
	$data = json_decode($_POST['data'], true);
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

	$good = (int)$data['good'];
	if($good != 2 && $good != 3)$where .= " AND re.good = ".$good;
 	$goodSort = $good;
	
	$limit = "LIMIT ".$elementLoadInPageStart.", ".$elements_on_page;
 	
 	if($goodSort == 3){
 		$where2 = "HAVING tr";
 	}

 	$where3 = "";
 	if($data['search'] == 'email'){
 		$where3 = " AND re.userIdEmail = '".$data['text']."'";
 	}elseif($data['search'] == 'order'){
 		$where3 = " AND re.orderId = ".$data['text'];
 	}elseif($data['search'] == 'review'){
 		$where3 = " AND re.id = ".$data['text'];
 	}

	$request = $bd->write("SET lc_time_names = 'ru_RU'");
	$request = $bd->read("	SELECT 	SQL_CALC_FOUND_ROWS re.id, re.idProduct, re.text, re.good,DATE_FORMAT(re.date,'%e %b %Y') AS dt,DATE_FORMAT(re.date,'%H:%i') AS tm, pr.name,
									(SELECT ra.text FROM review_answer ra WHERE ra.reviewId = re.id LIMIT 1),
									(SELECT ma.id FROM message ma WHERE ma.order_id = re.orderId AND ma.status = 'not_read' AND ma.author != {$user->id} LIMIT 1) as tr, re.orderId,(SELECT count(ma.id) FROM message ma WHERE ma.order_id = re.orderId AND ma.status = 'not_read' AND ma.author != {$user->id} )
							FROM `review` re
							inner join `product` pr
							on pr.id = re.idProduct
							".$where." AND pr.idUser = ".$user->id."  AND re.del = 0 ".$where3." ".$where2."
							ORDER BY re.date DESC 
							".$limit);
	$product_count = $bd->total_rows;
	$list = "";

	for($i=0; $i<$bd->rows; $i++){
		$reviewId =  mysql_result($request,$i,0);
		$productId =  mysql_result($request,$i,1);
		$text = strBaseOut(mysql_result($request,$i,2));
		$good = strBaseOut(mysql_result($request,$i,3));
		$dt = mysql_result($request,$i,4);
		$tm = mysql_result($request,$i,5);
		$name = strBaseOut(mysql_result($request,$i,6));
		$divAnswer = strBaseOut(mysql_result($request,$i,7));
		$messageCount = mysql_result($request,$i,10);
		$message = mysql_result($request,$i,8) != NULL ? '<div style="display:inline-block;vertical-align:middle;"><img src="/stylisation/images/new_message.png"><div class="pa_panel_u " style="float: right"><span class="color_red">'.$messageCount.'</span></div></div>' : '';
		$orderId = mysql_result($request,$i,9);


		if($divAnswer == "") {
			$divAnswer = "<div style=\"text-align:right;\"><a class=\"btn btn-info btn-sm\" onclick=\"panel.user.review.answer.popup(".$reviewId."); return false;\">Ответить</a></div>";
		} else {
			$divAnswer = <<<HTML
		<div class="middle_lot_o_one admin admin2">
			<div class="middle_lot_o_t1" style="display:inline-block">Ваш ответ</div>
			<div class="middle_lot_o_t1 btn newbtn-success" onclick="panel.user.review.change({$reviewId}, this); return false;" style="display:inline-block;padding: 2px;margin-bottom: 5px;">Изменить</div>
			<div class="middle_lot_o_t1 btn newbtn-danger" onclick="panel.user.review.delete({$reviewId}, this); return false;" style="display:inline-block;padding: 2px;margin-bottom: 5px;">Удалить</div>
			<div class="middle_lot_o_t2">{$divAnswer}</div>
		</div>
HTML;

		}


		$list .= <<<HTML
			<tr>
				<td class="padding_10">
					<a style="text-decoration: none;" href="/product/{$productId}/" target="_blank">{$name}</a>
					<div class="middle_lotnames">
					<div class="sshetmuner">Счет №: {$orderId}</div>
					<a class="btnreviewsm" href="/panel/sales/{$orderId}/" target="_blank">Подробнее</a>
					{$message}
				</td>
				<td class="padding_5 vertical_align">
					<div class="middle_lot_o_one middle_lot_o_one{$good}">
						<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> ID: {$reviewId} | Оценка покупателя <span class="middle_lot_o_good">положительная</span><span class="middle_lot_o_bead">отрицательная</span></div>
						<div class="middle_lot_o_t2">{$text}</div>
						<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {$dt} <i class="sprite sprite_otz_time"></i> {$tm}</div>
					</div>
					{$divAnswer}
				</td>
			</tr>
HTML;

	}
	if($goodSort == 3 && !$bd->rows){
		$list .=<<<HTML
			<tr>
				<td style="font-size: 21px;padding: 10px 10px;"colspan="2">Не обнаружено непрочитанных сообщений/отзывов от покупателей.</td>
			</tr>
HTML;
	}

	if($data['search'] && !$bd->rows){
		$list .=<<<HTML
			<tr>
				<td style="font-size: 21px;padding: 10px 10px;"colspan="2">Не обнаружено отзывов.</td>
			</tr>
HTML;
	}

	if($data['method'] !== 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.user.review.list.get');
		//$list .= '<div id="list_add_panel.user.review.list.get"></div>';
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>