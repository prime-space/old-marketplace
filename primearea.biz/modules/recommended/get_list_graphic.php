<?php
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/main.php";
	include '../currency/currclass.php';
  
	$bd = new mysql();
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/recommended/graphic_list.tpl"));
	$curr = new current_convert();

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');

	$where = "WHERE p.showing = 1 AND p.hidden = 0";
    $cat = (int)$data['cat'];
	if($cat != 0) $where .= " AND p.group = ".$cat;
	
	$request = $bd->read("
		SELECT COUNT(ad.id) 
		FROM ad 
		INNER JOIN product p ON p.id = ad.product_id
		INNER JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
		".$where." 
			AND ad.type='Графическое' 
			AND ad.status='Активно'
	");
	
	$count = mysql_result($request,0,0);
	
	$nographic = $tpl->ify('NOGRAPHIC');
	if($count){
		$tpl->content = str_replace($nographic['orig'], $nographic['if'], $tpl->content);
		$ad_rand_arr = array();
		$amt_ad = $count < 7 ? $count : 6;
		while(count($ad_rand_arr) != $amt_ad){
			$val = rand(0, $count-1);
			if(!in_array($val, $ad_rand_arr))$ad_rand_arr[] = $val;
		}
		
		$tpl->fory('GRAPHIC');
		$query = array();
		foreach($ad_rand_arr as $v)$query[] = "(SELECT ad.id, p.name, p.price, p.curr, p.picture, pic.name, pic.path, p.sale, (select count(*) from review where idProduct = p.id), u.login
												FROM ad
												INNER JOIN product p ON p.id = ad.product_id
												JOIN user u ON p.idUser = u.id
												INNER JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
												".$where." AND ad.type = 'Графическое' 
														   AND ad.status = 'Активно'
												LIMIT ".$v.", 1)";
		$request = $bd->read(implode(' UNION ', $query));
		//close($bd->rows.'q');
		if(!$bd->rows)close(json_encode(array('status' => 'ok', 'content' => '', 'showing' => false)));
		for($i=0;$i<$bd->rows;$i++){
			$curr_price = $_COOKIE['curr'] ? $_COOKIE['curr'] : 4;
			$price = $curr->curr(mysql_result($request,$i,2), mysql_result($request,$i,3), $curr_price);
			if(mysql_result($request,$i,4)){
				$picture = '/'.(mysql_result($request,$i,5) ? mysql_result($request,$i,6).mysql_result($request,$i,5) : 'picture/'.mysql_result($request,$i,6).'recommended.jpg');
			}else $picture = '/stylisation/images/no_img.png';
			$picture = $CONFIG['cdn'].$picture;
			$tpl->fory_cycle(array(	'id' => mysql_result($request,$i,0),
									'price' => $price,
									'picture' => $picture,
									'sale' => mysql_result($request, $i,7),
									'reviews' => mysql_result($request, $i, 8),
									'user' => mysql_result($request, $i, 9),
									'name' => strBaseOut(mysql_result($request,$i,1))));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	}else{
		 //$tpl->content = str_replace($nographic['orig'], $nographic['else'], $tpl->content);
		 close(json_encode(array('status' => 'ok', 'content' => '', 'showing' => false)));
	}
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content, 'showing' => true)));
?>
