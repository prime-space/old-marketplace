<?php
	header('Access-Control-Allow-Origin: *');

  ($_COOKIE['curr'] == '') ? $currList = 4 : $currList = $_COOKIE['curr'];
  
  include "../../../func/config.php";
  include "../../../func/mysql.php";
  include "../../../func/main.php";
  include "../../currency/currclass.php"; 
  
	$currClass = new currConvFPayC();
	$bd = new mysql();
	
	$data = json_decode($_POST['data'], true);
	
	$elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
	
  $product_id = $bd->prepare((int)$data['product_id'], 8);	
  $current_list = (int)$data['current_list'];
  if($current_list == 0)$elementLoadInPageStart = 0;
  else $elementLoadInPageStart = $current_list * $elements_on_page;  
  

	
  $request = $bd->read("SELECT SQL_CALC_FOUND_ROWS r.userGuest, r.userIdEmail, 
                               (SELECT u.login FROM user u WHERE u.id = r.userIdEmail LIMIT 1),
							   r.text, r.good, r.date, r.id,
							   (SELECT ra.text FROM review_answer ra WHERE ra.reviewId = r.id LIMIT 1)
					    FROM review r
					    JOIN product p ON p.id = r.idProduct
						WHERE 	r.idProduct = ".$product_id."
							AND r.del = 0 AND p.hidden = 0
						ORDER BY r.date DESC 
						LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
	if(!$product_count)close(json_encode(array('status' => 'ok', 'content' => '')));
  $rows = mysql_num_rows($request);
  if(mysql_num_rows($request) > 0){
  	 $review = "";
     for($i=0;$i<$rows;$i++){
  	    if(mysql_result($request,$i,0) == 0)$userLogin  = "Незарегистрированный покупатель";
	    else $userLogin = mysql_result($request,0,2);
	    
		$text = strBaseOut(mysql_result($request,$i,3));
		$good = mysql_result($request,$i,4);
		$date = mysql_result($request,$i,5);
		$reviewId = mysql_result($request,$i,6);
		$answer = strBaseOut(mysql_result($request,$i,7));

		$date_strtotime = strtotime( $date );
		$date = @date( "Y-m-d", $date_strtotime );
		$time = @date( "H:i:s", $date_strtotime );

	    if($answer != ""){
			$answer = <<<HTML
		<div class="middle_lot_o_one admin">
			<div class="middle_lot_o_t1">Ответ продавца</div>
			<div class="middle_lot_o_t2">{$answer}</div>
		</div>
HTML;

	    }else{
			$answer = "";

		}

		//Покупатель: <i class=\"icon-user\"></i> ".$userLogin." - 
		$review .= <<<HTML
		<div class="middle_lot_o_one middle_lot_o_one{$good}">
			<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> ID: {$reviewId} | Оценка покупателя <span class="middle_lot_o_good">положительная</span><span class="middle_lot_o_bead">отрицательная</span></div>
			<div class="middle_lot_o_t2">{$text}</div>
			<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {$date} <i class="sprite sprite_otz_time"></i> {$time}</div>
		</div>
		{$answer}
HTML;

     }
  }/*else $review = '	<div class="ProductShow_O">
						<div style="text-align:center;font-weight:bold;padding:15px;">Отзывов не найдено</div>
						<div class="clr"></div>
					</div>';*/

	
		
	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, $data['func']);
		$review .= '<div id="list_add_'.$data['func'].'"></div>';
		$review .= $pagination;
	}
  
  
  close(json_encode(array('status' => 'ok', 'content' => $review)));
  
?>