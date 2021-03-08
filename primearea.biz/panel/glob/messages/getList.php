<?php
	include "../../../func/config.php";
	include "../../../func/main.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'ok', 'content' => 'Ошибка доступа')));
  
  
	if(in_array($user->role, array('admin', 'moder'))){
		
		if($user->role == 'moder'){

			if($user->checkModerRight('messages')){
				$whereTwo = " AND `to` = 0";
			}else{
				$where = " AND (h.to = ".$user->id." OR h.from = ".$user->id.")";
				$whereTwo = " AND `to` = ".$user->id;
			}

		}else{
			$whereTwo = " AND `to` = 0";
		}
		

	}else{
		$where = " AND (h.to = ".$user->id." OR h.from = ".$user->id.")";
		$whereTwo = " AND `to` = ".$user->id;
	}
	
	
	$data = json_decode($_POST['data'], true);
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];  

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  
	$request = $bd->read("
		SELECT 	SQL_CALC_FOUND_ROWS h.id, h.title, h.date, h.topic,
				(SELECT COUNT(id) FROM help WHERE topic = h.topic AND see = 0 ".$whereTwo." LIMIT 1)ggg
		FROM help h
		WHERE h.title IS NOT NULL ".$where."
		ORDER BY ggg DESC, h.id DESC
		LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
	);
	$product_count = $bd->total_rows;

	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		$id = mysql_result($request,$i,0);
		$title = strBaseOut(mysql_result($request,$i,1));
		$date = mysql_result($request,$i,2);
		$topic = mysql_result($request,$i,3);
		$countMessage = mysql_result($request,$i,4);
		if( $countMessage > 0 ) {
			$class_btn = 'warning';
		} else {
			$class_btn = 'info';
		}

		$list .= <<<HTML
		<tr>
			<td class="padding_10 font_size_14 vertical_align font_weight_700 text_align_c">{$id}</td>
			<td class="padding_10 vertical_align"><a style="display: block;text-align: left;" class="btn btn-sm btn-{$class_btn}" href="/panel/messages/view/{$topic}/">{$title}</a></td>
			<td class="padding_10 font_size_14 vertical_align font_weight_700 text_align_c">{$date}</td>
		</tr>
HTML;

	}
 
 	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.user.messages.list.get');
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
?>