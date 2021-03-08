<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "ok", "content": "Ошибка доступа"}');
	
	$data = json_decode($_POST['data'], true);
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];
	
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  
	$request = $bd->read("	SELECT SQL_CALC_FOUND_ROWS c.date, c.amount, c.currency, c.status, c.protect
							FROM cashout c
							WHERE c.userId = ".$user->id." 
							ORDER BY
								CASE
									WHEN c.status = 'error' THEN 1
									WHEN c.status IN ('process', 'new') THEN 2
									ELSE 3
								END,
								c.date DESC
							LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
  
	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		$date = mysql_result($request,$i,0);
		$amount = mysql_result($request,$i,1);
		$currency = mysql_result($request,$i,2);
		if($currency == 'yar'){
			$currency = 'yandex';
		}
		$status = mysql_result($request,$i,3);
		$protect = mysql_result($request,$i,4);


		if($status === 'performed'){
			$status = '<span class="span_green_bg" >Исполнен</span>';
		}elseif($status === 'process' || $status === 'new'){
			$status = '<span class="span_ellow_bg">На очереди</span>';
        }elseif($status === 'cancel'){
            $status = '<span class="span_cancel_bg">Отменен</span>';
		}else{
			$status = '<span class="span_red_bg" >Ошибка</span>';
		}


		$list .= <<<HTML
						<tr>
							<td data-label="Дата" class="text_align_c padding_10 vertical_align">{$date}</td>
							<td data-label="Сумма" class="text_align_c cent_css padding_10 vertical_align">{$amount}</td>
							<td data-label="Валюта" class="text_align_c cent_css padding_10 vertical_align">{$currency}</td>
							<td data-label="Статус" class="text_align_c cent_css padding_10 vertical_align">{$status}</td>
							<td data-label="Протекция" class="text_align_c cent_css padding_10 vertical_align" >{$protect}</td>
						</tr>
HTML;

  }
  
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.user.cashout.list.get');
		//$list .= '<div id="list_add_panel.user.cashout.list.get"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="5"', $pagination );
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>
