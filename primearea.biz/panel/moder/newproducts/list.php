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
	$pagination_js_func = 'panel.moder.newproducts.list.get';

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

	$request = $bd->read("
		SELECT `id`, `name`, `date`, `moderation`, `moderation_message`
		FROM `product`
		WHERE `block` = 'ok'
		ORDER BY `moderation` = 'check' DESC, `id` DESC
		LIMIT {$elementLoadInPageStart}, {$elements_on_page}
	");


	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		$id = mysql_result($request,$i,0);
		$name = strBaseOut(mysql_result($request,$i,1));
		$date = mysql_result($request,$i,2);
		$moderation = mysql_result($request,$i,3);
		$moderation_message = mysql_result($request,$i,4) ? mysql_result($request,$i,4) : '';

		if($moderation == 'refuse'){
			$_message = 'Отказ';
			$_color = 'black';
		}elseif($moderation == 'ok' || $moderation == NULL){
			$_message = 'Одобрено';
			$_color = 'green';
			$moderation_message = $_message;
		}elseif($moderation == 'check'){
			$_message = 'На проверке';
			$_color = 'blue';
			$moderation_message = $_message;
		}


		$list .= <<<HTML
		<tr>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$id}</td>
			<td class="font_size_14 padding_10 vertical_align"><a href="/product/{$id}/" target="_blank">{$name}</a></td>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$date}</td>
			<td class="font_size_14 padding_10 text_align_c vertical_align"><span onclick="common.popup.open('Отказано', false, 'Статус');" style="background-color: $_color; cursor: pointer;" class="btn btn-danger btn-sm thead_disabled">{$_message}</span></td>
		</tr>
HTML;

	}
	if($data['method'] != 'add'){
		$request = $bd->read("
			SELECT COUNT(id) 
			FROM `product` 
			WHERE `block` = 'ok' ");
		$product_count = mysql_result($request,0,0);
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $pagination_js_func);
		$pagination = str_replace( 'colspan="6"', 'colspan="8"', $pagination );
		$list .= $pagination;
	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));

?>