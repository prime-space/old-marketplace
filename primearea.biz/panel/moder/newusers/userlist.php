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
  
	$request = $bd->read("SELECT SQL_CALC_FOUND_ROWS `id`, `login`, `email`, `fio`, `date`, 
                               `rating`, `phone`, `skype`, `site`, last_action
						FROM `user`
						ORDER BY `date` DESC
						LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);						
	$product_count = $bd->total_rows;
  
	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		 $id = mysql_result($request,$i,0);
		 $login = mysql_result($request,$i,1);
		 $email = mysql_result($request,$i,2);
		 $fio = strBaseOut(mysql_result($request,$i,3));
		 $date = mysql_result($request,$i,4);
		 $rating = mysql_result($request,$i,5);
		 $phone = strBaseOut(mysql_result($request,$i,6));
		 $skype = strBaseOut(mysql_result($request,$i,7));
		 $site = strBaseOut(mysql_result($request,$i,8));
		 $last_action = strBaseOut(mysql_result($request,$i,9));

		$list .= <<<HTML
		<tr>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$date}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{$login}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{$email}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{$fio}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$last_action}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$skype}</td>
			<td class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 text_align_c vertical_align">{$site}</td>
		</tr>
HTML;

	}
  
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.moder.newusers.userlist.get');
		$pagination = str_replace( 'colspan="6"', 'colspan="7"', $pagination );
		$list .= $pagination;
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>