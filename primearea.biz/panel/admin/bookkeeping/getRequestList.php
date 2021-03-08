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
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  	
	$request = $bd->read("	SELECT SQL_CALC_FOUND_ROWS c.date, c.amount, c.status, c.protect, u.login, 
			case c.currency
		        when 'wmr' then u.wmr
		        when 'yar' then u.yandex_purse
		        when 'qiwi' then u.qiwi_purse
    		end,
			c.id, u.id, c.currency
							FROM `cashout` c
							INNER JOIN `user` u
							ON c.userId = u.id
							ORDER BY
								CASE
									WHEN c.status = 'error' THEN 1
									WHEN c.status IN ('process', 'new') THEN 2
									ELSE 3
								END,
								c.date DESC
							LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
	);
	$product_count = $bd->total_rows;

	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		
		$date = mysql_result($request,$i,0);
		$amount = mysql_result($request,$i,1);
		$status = mysql_result($request,$i,2);
		$protect = mysql_result($request,$i,3);
		$login = mysql_result($request,$i,4);
		if(mysql_result($request,$i,5)){
			$purse = mysql_result($request,$i,5);
		}else{
			$purse = 'Не заполнено';
		}
		$id = mysql_result($request,$i,6);
		$user_id = mysql_result($request,$i,7);
		$curr = mysql_result($request,$i,8);

		if($status === 'performed'){
			$status = '<span class="span_green_bg">Исполнен</span>';
			$actionDiv = '';
		}elseif($status === 'process' || $status === 'new'){
			$status = '<span class="span_ellow_bg">На очереди</span>';
            $actionDiv = '';
		}elseif($status === 'cancel'){
			$status = '<span class="span_cancel_bg">Отменен</span>';
            $actionDiv = '';
		}else{
			$status = '<span class="span_red_bg">Ошибка</span>';
			$actionDiv = '
				<div class="btn btn-sm btn-warning" onclick="panel.admin.bookkeeping.retryRequest('.$id.', this);">Еще раз</div>
				<div class="btn btn-sm btn-warning" onclick="panel.admin.bookkeeping.completeRequest.dialog('.$id.', this);">Исполнить</div>
			';
		}
		

		if($curr == 'wmr'){
			$method = 'webmoney';
			$style = 'style="color:blue;"';
		}elseif($curr == 'yar'){
			$method = 'yandex';
			$style = 'style="color:#2dc36d;"';
		}elseif($curr == 'qiwi'){
			$method = 'qiwi';
			$style = 'style="color:rgb(255, 119, 69);"';
		}

		$list .= <<<HTML
		<tr>
			<td  class="padding_10 text_align_c vertical_align">{$date}</td>
			<td style="text-align: center" class="padding_10 vertical_align"><a onclick="panel.admin.bookkeeping.userTransactions({$user_id}, this);">{$login}</a><br><a style="margin-top: 2px;display: block;" target='_BLANK' href="/seller/{$user_id}">(профиль)</a></td>
			<td {$style} class="padding_10 text_align_c vertical_align">{$method}</td>
			<td class="padding_10 text_align_c vertical_align">{$purse}</td>
			<td class="padding_10 text_align_c vertical_align">{$amount} руб.</td>
			<td class="padding_10 text_align_c vertical_align">{$status}</td>
			<td class="padding_10 text_align_c vertical_align">{$protect}</td>
			<td class="padding_10 text_align_c vertical_align">{$actionDiv}</td>
		</tr>
HTML;

	}

	if($data['method'] != 'add'){
		$request = $bd->read("SELECT COUNT(id) FROM cashout");
		$product_count = mysql_result($request,0,0);
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.admin.bookkeeping.wdlist.get');
		//$list .= '<div id="list_add_panel.admin.bookkeeping.wdlist.get"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="7"', $pagination );
		$list .= $pagination;
	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>
