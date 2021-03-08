<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../modules/currency/currclass.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode(stripslashes($_POST['data']), true);
	
	if(!$data['promocode_id'])close(json_encode(array('status' => 'error', 'content' => 'Недостаточно данных')));
	$promocode_id = (int)$data['promocode_id'];
	$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
	if(!$bd->rows)close(json_encode(array('status' => 'error', 'content' => 'Не найдено или нет доступа')));
	
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/promocodes/products_list.tpl"));
	
	$request = $bd->read("
		SELECT id, name
		FROM product
		WHERE 	id NOT IN (SELECT product_id FROM promocode_products WHERE promocode_id = ".$promocode_id.")
			AND	block = 'ok'
			AND idUser = ".$user->id."
		ORDER BY id DESC
		LIMIT 500
	");
	$noselect = $tpl->ify('NOSELECT');
	if(!$bd->rows)$tpl->content = str_replace($noselect['orig'], $noselect['else'], $tpl->content);
	else{
		$tpl->content = str_replace($noselect['orig'], $noselect['if'], $tpl->content);
		$tpl->fory('SELECT');
		for($i=0;$i<$bd->rows;$i++){
			$tpl->fory_cycle(array(	
				'value' => mysql_result($request,$i,0),
				'option' => mysql_result($request,$i,1)
			));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
	}
	
	$current_convert = new current_convert();
	$request = $bd->read("
		SELECT pp.id, p.name, p.price, p.curr, p.sale, pp.percent
		FROM promocode_products pp
		JOIN product p ON p.id = pp.product_id
		WHERE pp.promocode_id = ".$promocode_id."
		ORDER BY p.name DESC
		LIMIT 500
	");
	$nolist = $tpl->ify('NOLIST');
	if(!$bd->rows)$tpl->content = str_replace($nolist['orig'], $nolist['else'], $tpl->content);
	else{
		$tpl->content = str_replace($nolist['orig'], $nolist['if'], $tpl->content);
		$tpl->fory('LIST');
		for($i=0;$i<$bd->rows;$i++){
			$tpl->fory_cycle(array(	
				'promocode_product_id' => mysql_result($request,$i,0),
				'name' => mysql_result($request,$i,1),
				'price' => $current_convert->curr(mysql_result($request,$i,2),mysql_result($request,$i,3),mysql_result($request,$i,3)),
				'sold' => mysql_result($request,$i,4),
				'percent' => mysql_result($request,$i,5)
			));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
	}
	
	$tpl->content = str_replace('{promocode_id}', $promocode_id, $tpl->content);
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content, 'count' => $bd->rows)));
?>