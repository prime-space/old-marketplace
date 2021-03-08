<?php

	($_COOKIE['curr'] == '') ? $currList = 4 : $currList = $_COOKIE['curr'];

	include "../../../../func/config.php";
	include "../../../../func/mysql.php";
	include "../../../../func/verification.php";
	include "../../../../func/main.php";
	include "../../../currency/currclass.php";
  
	$currClass = new currConvFPayC();
	$bd = new mysql();
  
	$data = json_decode($_POST['data'], true);
  
	$elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
	$elements_on_page = $elements_on_page ? $elements_on_page : $elementLoadInPage;
  
	$where = "WHERE 1";
	$user = new user();
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productList/func/productListShowMyShop.tpl"));
	$where .= " AND p.idUser = '".$user->id."'";
  
	$current_list = (int)$data['current_list'];
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

    $cat = (int)$data['cat'];
	if($cat) $where .= " AND p.group = ".$cat;
	$sortArr = array('p.date DESC', 
				   'p.sale DESC', 
				   'p.price * CASE
						 WHEN curr = 1 THEN (SELECT usd FROM currency ORDER BY id DESC LIMIT 1)
						 WHEN curr = 2 THEN (SELECT uah FROM currency ORDER BY id DESC LIMIT 1)
						 WHEN curr = 3 THEN (SELECT eur FROM currency ORDER BY id DESC LIMIT 1)
						 ELSE 1 END', 
				   'p.price * CASE
						 WHEN curr = 1 THEN (SELECT usd FROM currency ORDER BY id DESC LIMIT 1)
						 WHEN curr = 2 THEN (SELECT uah FROM currency ORDER BY id DESC LIMIT 1)
						 WHEN curr = 3 THEN (SELECT eur FROM currency ORDER BY id DESC LIMIT 1)
						 ELSE 1 END DESC', 
				   'p.name');

	$sort = (int)$data['sort'];
	if($sort < 0 || $sort > 4)close();
	$sort = $sortArr[$sort];
	
	$request = $bd->read("SELECT SQL_CALC_FOUND_ROWS p.id, p.idUser, p.name, p.sale, p.price, p.typeObject, p.many, p.shopsite, p.curr,
							   (SELECT COUNT(*) FROM product_text prt WHERE prt.idProduct = p.id AND `status` = 'sale'),
							   (SELECT COUNT(*) FROM product_file prf WHERE prf.idProduct = p.id AND `status` = 'sale')
						FROM product p ".$where." AND p.block = 'ok'
						ORDER BY ".$sort." limit ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
		
	$noproductlist = $tpl->ify('NOPRODUCTLIST');
	if(!$bd->rows)$tpl->content = str_replace($noproductlist['orig'], $noproductlist['else'], $tpl->content);
	else{
			$tpl->content = str_replace($noproductlist['orig'], $noproductlist['if'], $tpl->content);
			$tpl->fory('PRODUCTLIST');
			for($i=0;$i<$bd->rows;$i++){	
				$price =  mysql_result($request,$i,4);
				$typeObject =  mysql_result($request,$i,5);
				$many =  mysql_result($request,$i,6);
				$shopsite =  mysql_result($request,$i,7);
				$pcs = mysql_result($request,$i,9) + mysql_result($request,$i,10);
				$curr = mysql_result($request,$i,8);
				
				$pcs = $many == 0 ? $pcs : 'x';
				
				$show_addmyshop = $shopsite == -1 ? 'visible' : 'hidden';
			
				$tpl->fory_cycle(array(	'id' => mysql_result($request,$i,0),
										'name' => strBaseOut(mysql_result($request,$i,2)),
										'pcs' => $pcs,
										'sold' => mysql_result($request,$i,3),
										'price' => $currClass->curr($price,$curr,$currList),
										'show_addmyshop' => $show_addmyshop,
										'color' => $i&1==1?'a':'b'));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);	
	}

 	if($data['method'] != 'add'){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'product_list_myshop.get');
		$tpl->content .= '<div id="list_add_product_list_myshop.get"></div>';
		$tpl->content .= $pagination;
	}else $tpl->content = $tpl->fory_arr['content'];
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
  
?>