<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
 
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/recommended/list_ad.tpl"));
 
	$data = json_decode($_POST['data'], true);
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page; 
  
	$request = $bd->read("SELECT 	SQL_CALC_FOUND_ROWS
									ad.id, 
									(SELECT name FROM product WHERE id=ad.product_id), 
									ad.type, 
									ad.price, 
									ad.graphic_duration, 
									ad.datetime, 
									ad.click, 
									ad.status, 
									ad.product_id,
									(
										SELECT COUNT(a.id)
										FROM ad_pay_click a
										JOIN transactions t ON a.id = t.method_id AND method = 'clickAd'
										WHERE a.ad_id = ad.id
									)
                        FROM ad 
						WHERE ad.user_id = '".$user->id."'
                        ORDER BY ad.id DESC
						LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
						
	$product_count = $bd->total_rows;
	$noad = $tpl->ify('NOAD');
	if(!$bd->rows)$tpl->content = str_replace($noad['orig'], $noad['else'], $tpl->content);
	else{
		$tpl->content = str_replace($noad['orig'], $noad['if'], $tpl->content);
		$tpl->fory('AD');
		for($i=0;$i<$bd->rows;$i++){
			$price = mysql_result($request,$i,3).' руб.';
			$type = mysql_result($request,$i,2);
			$status = mysql_result($request,$i,7);
			$button = ($status == 'Активно' || $status == 'На паузе') ? $button = '<img src="/stylisation/images/stop.png" title="Закрыть объявление" style="cursor:pointer;width:20px;" onclick="user_recommended.stop_ad('.mysql_result($request,$i,0).', this)">' : '';
			if($type == 'Графическое'){
				$price = $price . ' день';
				$time_end = $status == 'Активно' ? ceil(((strtotime(mysql_result($request,$i,5))  - time()) / 60 / 60 / 24) + mysql_result($request,$i,4)) . ' дней' : '&nbsp;';
				$spent = number_format((mysql_result($request,$i,3) * mysql_result($request,$i,4)), 2) . ' руб.';
			}else{
				$price = $price . ' клик';
				$time_end = $status == 'Активно' ? '&infin;' : '&nbsp;';
				$spent = number_format((mysql_result($request,$i,3) * mysql_result($request,$i,9)), 2) . ' руб.';
			}
			$name = strBaseOut(mysql_result($request,$i,1));
			$product = '<a href="/product/'.mysql_result($request,$i,8).'/" target="_blank">'. $name .'</a>';
			$tpl->fory_cycle(array(	'date' => mysql_result($request,$i,5),
									'product' => $product,
									'type' => $type,
									'amount' => $price,
									'time_end' => $time_end,
									'count' => mysql_result($request,$i,6),
									'spent' => $spent,
									'status' => $status,
									'button' => $button));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	}
	
	if($data['method'] != 'add' && $product_count){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'user_recommended.list.get');
		//$tpl->content .= '<div id="list_add_user_recommended.list.get"></div>';
		$tpl->content .= str_replace( 'colspan="7"', 'colspan="9"', $pagination );
	}	
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
?>