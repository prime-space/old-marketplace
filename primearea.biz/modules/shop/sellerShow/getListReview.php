<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/config.php";
  include "../../../func/mysql.php";
  include "../../../func/main.php";
    
	$bd = new mysql();
   
	$data = json_decode($_POST['data'], true);	
	
  $user_id = $bd->prepare($data['user_id'], 8);	
  $current_list = $data['current_list'];
  $elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
  if($current_list == 0)$elementLoadInPageStart = 0;
  else $elementLoadInPageStart = $current_list * $elements_on_page;  
  
	$request = $bd->write("SET lc_time_names = 'ru_RU'");
	$request = $bd->read("SELECT SQL_CALC_FOUND_ROWS re.id, re.idProduct, re.text, re.good,DATE_FORMAT(re.date,'%e %b %Y') AS dt,DATE_FORMAT(re.date,'%H:%i') AS tm, pr.name,
                               (SELECT ra.text FROM review_answer ra WHERE ra.reviewId = re.id LIMIT 1), re.id
                        FROM `review` re
                        inner join `product` pr
						on pr.id = re.idProduct
						WHERE 	pr.idUser = ".$user_id."
							AND	re.del = 0
							AND pr.hidden = 0
						ORDER BY re.date DESC 
						LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
	
	$rows = mysql_num_rows($request); 
	if($rows == 0 && $current_list != 0)exit();
	if($rows == 0)close(json_encode(array('status' => 'ok', 'content' => 'У продавца нет отзывов')));
	$list = "";
	if( $data['method'] != 'add' ) {
		$list .= <<<HTML
		<table class="table table-striped table_page">
			<thead>
				<tr>
					<td style="width: 240px;">Товар</td>
					<td class="text_align_c">Отзыв / Ответ</td>
				</tr>
			</thead>
			<tbody id="productList">
HTML;

	}
	for($i=0; $i<$rows; $i++){
		$reviewId =  mysql_result($request,$i,0);	
		$productId =  mysql_result($request,$i,1);
		$text = strBaseOut(mysql_result($request,$i,2));
		$good = strBaseOut(mysql_result($request,$i,3));
		$dt = mysql_result($request,$i,4);
		$tm = mysql_result($request,$i,5);
		$name = strBaseOut(mysql_result($request,$i,6));
		$divAnswer = strBaseOut(mysql_result($request,$i,7));
		$id = mysql_result($request,$i,8);

		if ( $divAnswer ) {
			$divAnswer = <<<HTML
				<div class="middle_lot_o_one admin admin2">
					<div class="middle_lot_o_t1">Ответ продавца</div>
					<div class="middle_lot_o_t2">{$divAnswer}</div>
				</div>
HTML;

		} else {
			$divAnswer = '';

		}

		$list .= <<<HTML
			<tr>
				<td class="padding_10"><a href="/product/{$productId}/" target="_blank">{$name}</a></td>
				<td class="padding_5 vertical_align">
					<div class="middle_lot_o_one middle_lot_o_one{$good}">
						<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> ID: {$id} | Оценка покупателя <span class="middle_lot_o_good">положительная</span><span class="middle_lot_o_bead">отрицательная</span></div>
						<div class="middle_lot_o_t2">{$text}</div>
						<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {$dt} <i class="sprite sprite_otz_time"></i> {$tm}</div>
					</div>
					{$divAnswer}
				</td>
			</tr>

HTML;

  }
  
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $data['func']);
		//$list .= '<div id="list_add_'.$data['func'].'"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="2"', $pagination );
		$list .= $pagination;
	}

	if( $data['method'] != 'add' ) {
      $list .= <<<HTML
			</tbody>
		</table>
HTML;

	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));
  
?>