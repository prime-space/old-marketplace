<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";
	include "../../../func/tpl.class.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($user->role == 'moder' && !$user->checkModerRight('panel_hiddenproducts'))close('{"status": "error", "message": "Ошибка доступа"}');
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$elements_on_page   = (int)$data['elements_on_page'];
	$current_list       = (int)$data['current_list'];
	$pagination_js_func = 'panel.moder.hiddenproducts.productslist.get';
	$login              = $db->safesql($data['login']);

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

	if($login){
		$where = ' WHERE u.login = "'.$login.'" ';
		$join  = "LEFT JOIN product p ON u.id = p.idUser AND p.block = 'ok'
				  LEFT JOIN product ph ON u.id = ph.idUser AND ph.block = 'ok' AND ph.hidden = 1";
	}else{
		$where = ' WHERE 1 ';
		$join  = "JOIN product p ON u.id = p.idUser AND p.block = 'ok'
				  JOIN product ph ON u.id = ph.idUser AND ph.block = 'ok' AND ph.hidden = 1";
	}

	$request = $db->query("
		SELECT SQL_CALC_FOUND_ROWS u.login, count(DISTINCT p.id) as count, count(DISTINCT ph.id) as countHidden, u.id as userId
		FROM user u
		".$join." 
		".$where." 
		GROUP BY u.id
		ORDER BY countHidden DESC
		LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
	);
	$listCount = $db->super_query_value("SELECT FOUND_ROWS()");

	$listCount == 0 ? close(json_encode(['status' => 'ok', 'content' => '<tr><td colspan="7" class="font_size_14 font_weight_700 text_align_c padding_10">Ничего не найдено</td></tr>'])) : '';

	$tpl = new tpl('hiddenproducts/listAll');	
	$list = "";

	$tpl->fory('list');
	while($row = $db->get_row($request)){
		$tpl->foryCycle(array(
			'login'        => $row['login'],
			'productCount' => $row['countHidden'].'/'.$row['count'],
			'user_id'      => $row['userId']
		));
	}
	
	$tpl->foryEnd();
	$list .= $tpl->content;

	if($data['method'] != 'add'){
		
		$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $pagination_js_func);
		$pagination = str_replace( 'colspan="2"', 'colspan="2"', $pagination );
		$list .= $pagination;
	}

	close(json_encode(['status' => 'ok', 'content' => $list]));

?>