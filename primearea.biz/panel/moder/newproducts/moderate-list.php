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
	$pagination_js_func = 'panel.moder.moderate.list.get';

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

	$request = $bd->read("
		SELECT SQL_CALC_FOUND_ROWS p.`id`, p.`name`, p.`date`, p.`moderation`, p.`info`
		FROM `product` p
        LEFT JOIN product_file pf ON
            pf.idProduct = p.id AND
            pf.status = 'sale'
        LEFT JOIN product_text pt ON
            pt.idProduct = p.id AND
            pt.status = 'sale'
		WHERE
            (
                pf.id IS NOT NULL OR
                pt.id IS NOT NULL
            ) AND
            p.`block` = 'ok' AND
            p.`moderation` = 'check'
		GROUP BY p.id
		ORDER BY p.`id` DESC
		LIMIT {$elementLoadInPageStart}, {$elements_on_page}
	");
    $product_count = $bd->total_rows;

	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		$id = mysql_result($request,$i,0);
		$name = strBaseOut(mysql_result($request,$i,1));
		$date = mysql_result($request,$i,2);
		$moderation = mysql_result($request,$i,3);
		$info = mysql_result($request,$i,4);
		

		if($moderation == 'refuse'){
			$_message = 'Отказ';
			$_color = 'black';
		}elseif($moderation == 'ok' || $moderation == NULL){
			$_message = 'Одобрено';
			$_color = 'green';
		}elseif($moderation == 'check'){
			$_message = 'На проверке';
			$_color = 'aqua';
		}


		$list .= <<<HTML
		<tr>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$id}</td>
			<td style='text-align: center;' class="font_size_14 padding_10 vertical_align"><a href="/panel/moderate/$id"  >{$name}</a></td>
			<td class="font_size_14 padding_10 text_align_c vertical_align"><span  style="width: 65px; background-color: $_color;" class="btn btn-danger btn-sm thead_disabled">{$_message}</span></td>
		</tr>
HTML;

	}
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $pagination_js_func);
		$pagination = str_replace( 'colspan="6"', 'colspan="8"', $pagination );
		$list .= $pagination;
	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));

?>