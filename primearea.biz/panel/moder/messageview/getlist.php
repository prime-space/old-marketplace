<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
	
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];
	$order_id = (int)$data['order_id'];
	
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

	$order_id = $order_id ? 'WHERE m.order_id='.$order_id : '';
	
	$request = $bd->read("	SELECT order_id, date, name, (SELECT COUNT(id) FROM message WHERE order_id = q.order_id)
								FROM(SELECT m.order_id, m.date, p.name
								FROM `message` m 
								INNER JOIN `order` o ON o.id = m.order_id
								INNER JOIN `product` p ON p.id = o.productId 
								".$order_id."
								GROUP BY m.order_id
								ORDER BY m.id DESC
								LIMIT ".$elementLoadInPageStart.", ".$elements_on_page.
							")q");						
	
	$list = '';
	for($i=0; $i<$bd->rows; $i++){
		$id = mysql_result($request,$i,0);
		$name = strBaseOut(mysql_result($request,$i,2));
		$count = mysql_result($request,$i,3);
		$date = mysql_result($request,$i,1);

		$list .= <<<HTML
		<tr>
			<td class="padding_10 text_align_c vertical_align">{$id}</td>
			<td class="padding_10 vertical_align">{$name}</td>
			<td class="padding_10 text_align_c vertical_align"><a href="/panel/messageview/{$id}/" target="_blank">{$count}</a></td>
			<td class="padding_10 text_align_c vertical_align">{$date}</td>
		</tr>
HTML;

	}
	
	if($data['method'] != 'add'){
		$request = $bd->read("	SELECT COUNT( id ) 
							FROM (
							SELECT m.id
							FROM message m
							".$order_id."
							GROUP BY m.order_id
							)q");
		$product_count = mysql_result($request, 0, 0);
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.moder.messageview.list.get');
		$pagination = str_replace( 'colspan="6"', 'colspan="4"', $pagination );
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>