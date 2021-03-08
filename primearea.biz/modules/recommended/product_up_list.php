<?php
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/db.class.php";
	include "../../func/main.php";
  
	$bd = new mysql();
	$db = new db();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
 
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/recommended/product_up.tpl"));
 
	$data = json_decode($_POST['data'], true);
	$elements_on_page = (int)$data['elements_on_page'];
	$current_list = (int)$data['current_list'];

	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page; 
  
	$request = $db->query("SELECT 	SQL_CALC_FOUND_ROWS
									p.name,
									p.price,
									p.curr,
									(CASE
													WHEN pr.date > NOW() THEN pr.date
													ELSE 'Исполнено' END) as `date`,
									(
										SELECT COUNT(o.id)
										FROM `order` o
										WHERE o.productId = pr.productId
									) as sales
                        FROM user_privileges_product_up pr
                        JOIN product p ON p.id = pr.productId
						WHERE pr.user_id = '".$user->id."'
                        ORDER BY pr.id DESC
						LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $db->super_query_value("SELECT FOUND_ROWS()");
	$noad = $tpl->ify('NOAD');

	if(!$request)$tpl->content = str_replace($noad['orig'], $noad['else'], $tpl->content);
	else{
		$tpl->content = str_replace($noad['orig'], $noad['if'], $tpl->content);
		$tpl->fory('AD');

		while($row = $db->get_row($request)){
			$curr = $row['curr'];
			switch ($curr) {
				case '1':
					$curr = '$';
					break;
				case '2':
					$curr = 'грн.';
					break;
				case '3':
					$curr = '€';
					break;
				case '4':
					$curr = 'руб.';
					break;
			}
			
			$price = $row['price'].' '.$curr;
			$name = $row['name'];
			$sales = $row['sales'];
			$date = $row['date'];

			$tpl->fory_cycle(array(	
									'name' => $name,
									'price' => $price,
									'sales' => $sales,
									'date' => $date,
									));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	}
	
	if($data['method'] != 'add' && $product_count){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'user_recommended.product_up_list.get');
		//$tpl->content .= '<div id="list_add_user_recommended.list.get"></div>';
		$tpl->content .= str_replace( 'colspan="7"', 'colspan="9"', $pagination );
	}	
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
?>