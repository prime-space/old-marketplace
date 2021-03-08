<?php
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include "../currency/currclass.php";
	include "../../func/customer.class.php";
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));
	
	$bd = new mysql();
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/customer/productlist.tpl"));
	$customer = new customer();
	$curr = new current_convert();
	
	$h = $bd->prepare($data['h'], 64);
	$tpl->content = str_replace('{h}', 	$h, 	$tpl->content);
	
	if(!$customer->check_key($h))close(json_encode(array('status' => 'error', 'message' => 'Доступ запрещен')));//Проверка валидности ключа
	if($customer->order_id && $customer->order_id !== $data['i'])close(json_encode(array('status' => 'error', 'message' => 'Доступ запрещен')));//Доступ только к конкретному заказу
	

	$elements_on_page = (int)$data['elements_on_page'];
	$elements_on_page = $elements_on_page ? $elements_on_page : $elementLoadInPage;
	$current_list = (int)$data['current_list'];
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
	
	$request = $bd->read("	SELECT 	SQL_CALC_FOUND_ROWS o.id, o.date, o.productId, o.price, o.curr, 
									(SELECT name FROM product WHERE id=o.productId LIMIT 1),
									(SELECT id FROM message WHERE order_id = o.id AND status = 'not_read' AND author != '".$customer->email."' LIMIT 1)
							FROM `order` o
							WHERE o.userIdEmail='".$customer->email."' AND (o.status='sended' OR o.status='paid' OR o.status='review')
							ORDER BY o.id DESC
							LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
	
	$noproduct = $tpl->ify('NOPRODUCT');
	if(!$bd->rows)$tpl->content = str_replace($noproduct['orig'], $noproduct['else'], $tpl->content);
	else{
		$tpl->content = str_replace($noproduct['orig'], $noproduct['if'], $tpl->content);
		$tpl->fory('PRODUCTS');
		for($i=0;$i<$bd->rows;$i++){
			$curr_price = mysql_result($request,$i,4) ? mysql_result($request,$i,4) : 4;//для отображения старых покупок
			$price = $curr->curr(mysql_result($request,$i,3), $curr_price, $curr_price);
			$message_icon =  mysql_result($request,$i,6) == NULL ? '' : '<div class="new_message"><img src="/stylisation/images/mailing.svg"></div>';
			$tpl->fory_cycle(array(	'date' => mysql_result($request,$i,1),
									'i' => mysql_result($request,$i,0),
									'message_icon' => $message_icon,
									'name' => strBaseOut(mysql_result($request,$i,5)),
									'price' => $price));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	}
	
	if($data['method'] != 'add' && $product_count){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'module.customer.productlist.get');
		$tpl->content .= '<div id="list_add_module.customer.productlist.get"></div>';
		$tpl->content .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
