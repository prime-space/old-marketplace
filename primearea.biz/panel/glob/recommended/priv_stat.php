<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../func/tpl.class.php";

	$bd = new mysql();
	$user = new user();  
	$tpl = new tpl('recommended/priv_stat');
	
	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');
  
  
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
  	
	$request = $bd->read("	SELECT SQL_CALC_FOUND_ROWS u.login, (CASE WHEN priv.until > NOW() THEN priv.until ELSE 'Истекло' END), priv.type, u.id
							FROM `user_privileges` priv
							LEFT JOIN user u on priv.user_id = u.id
							ORDER BY priv.until DESC
							LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
	);
	$product_count = $bd->total_rows;
	$list = "";
	$tpl->fory('list');
	for($i=0; $i<$bd->rows; $i++){
		
		$login = mysql_result($request,$i,0);
		$until = mysql_result($request,$i,1);
		$type = mysql_result($request,$i,2);
		$user_id = mysql_result($request,$i,3);
		switch ($type) {
			case '1':
				$type = 'BRONZE';
				break;
			case '2':
				$type = 'SILVER';
				break;
			case '3':
				$type = 'GOLD';
				break;
			case '4':
				$type = 'DIAMOND';
				break;
			
		}
	
		$tpl->foryCycle(array(
			'login' => $login,
			'user_id' => $user_id,
			'type' => $type,
			'style' => $style,
			'until' => $until
		));
				
	}
	$tpl->foryEnd();
	$list = $tpl->content;
	
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.admin.bookkeeping.wdlist_priv.get');
		//$list .= '<div id="list_add_panel.admin.bookkeeping.wdlist.get"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="7"', $pagination );
		$list .= $pagination;
	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>