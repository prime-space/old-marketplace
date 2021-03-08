<?php

  ($_COOKIE['curr'] == '') ? $currList = 4 : $currList = $_COOKIE['curr'];
  
  include "../../../func/config.php";
  include "../../../func/mysql.php";
  include "../../../func/main.php";
  include "../../currency/currclass.php"; 
  
	$currClass = new currConvFPayC();
	$bd = new mysql();
   
	$data = json_decode($_POST['data'], true);
   
	$elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
	
  $userid = $bd->prepare((int)$data['user_id'], 8);	
  $current_list = (int)$data['current_list'];
  if($current_list == 0)$elementLoadInPageStart = 0;
  else $elementLoadInPageStart = $current_list * $elements_on_page;  
  
  
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
  
 	$request = $bd->read("SELECT COUNT(id) FROM product WHERE idUser = '".$userid."' AND block = 'ok' AND showing = 1 AND (moderation = 'ok' OR moderation IS NULL)");
	$product_count = mysql_result($request,0,0);
	
  $request = $bd->read("SELECT
							p.id,p.name,p.sale,p.price,p.curr,
							pg.name AS groupName3,pgg.name AS groupName2,pggg.name AS groupName1,
							p.picture, pic.name, pic.path
                        FROM product p
						JOIN productgroup pg ON pg.id = p.group
						JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
						JOIN productgroup pgg ON pgg.id = pg.subgroup
						JOIN productgroup pggg ON pggg.id = pgg.subgroup
						WHERE
							p.idUser = '".$userid."' AND
							p.block = 'ok' AND
							p.showing = 1 AND 
							(p.moderation = 'ok' OR p.moderation IS NULL) AND
							p.hidden = 0
                        ORDER BY ".$sort." limit ".$elementLoadInPageStart.", ".$elements_on_page);
  $rows = mysql_num_rows($request); 
  if($rows == 0 && $current_list != 0)close();
  if($rows == 0)close(json_encode(array('status' => 'ok', 'content' => 'Продавец ничего не продает')));
  $list = "";

	if( $data['method'] != 'add' ) {
		$list .= <<<HTML
		<table class="table table-striped table_lot">
			<thead>
				<tr>
					<th class="table_name">Наименование</th>
					<th class="table_discount"></th>
					<th class="table_sales">Продаж</th>
					<th class="table_price">Цена товара</th>
				</tr>
			</thead>
			<tbody id="productList">
HTML;

	}

	for($i=0; $i<$rows; $i++){
		$id =  mysql_result($request,$i,0);	
		$name =  strBaseOut(mysql_result($request,$i,1));	
		$sale =  mysql_result($request,$i,2);
		$price =  mysql_result($request,$i,3);
	 
	 	
		$currNum = mysql_result($request,$i,4);
		$groupName3 = mysql_result($request,$i,5);
		$groupName2 = mysql_result($request,$i,6);
		$groupName1 = mysql_result($request,$i,7);

		if(mysql_result($request,$i,8)){
			$picture = mysql_result($request,$i,9) ? '/'.mysql_result($request,$i,10).mysql_result($request,$i,9) : '/picture/'.mysql_result($request,$i,10).'productshow.jpg';
		}else{
			$picture = '/stylisation/images/no_img.png';
		}
		$picture = $CONFIG['cdn'].$picture;
/*
								<div class="lot_nct">
									<a href="#" class="lot_name">{$name}</a>
									<div class="lot_category">Категория: Игры / Активация: Steam</div>
									<div class="lot_tags">
										<a href="/"><i class="sprite sprite_tag_clock"></i> Мгновенная доставка</a>, 
										<a href="/"><i class="sprite sprite_tag_garant"></i> Гарантия покупки</a>, 
										<a href="/"><i class="sprite sprite_tag_pay"></i> Лучшие цены</a>
									</div>
								</div>
*/
		//$curr = curr_ident($currNum);
		$priceN = $currClass->curr( $price, $currNum, $currList );
		$list .= <<<HTML
						<tr>
							<td class="vertical_align">
								<a href="/product/{$id}/" title="{$name}" class="lot_img"><img src="{$picture}" alt="{$name}"></a>
								<div class="lot_nct">
									<a href="/product/{$id}/" class="lot_name">{$name}</a>
									<div class="lot_category">Категория: {$groupName2} / {$groupName1} / {$groupName3}</div>
								</div>
							</td>
							<td class="vertical_align"></td>
							<td class="vertical_align"><span class="label label-danger">{$sale}</span></td>
							<td class="vertical_align"><span class="label label-success">{$priceN}</span></td>
						</tr>
HTML;

	}

	if( $data['method'] != 'add' ) {
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'module.seller.productlist.get');
		//$list .= '<div id="list_add_module.seller.productlist.get"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="4"', $pagination );
		$list .= $pagination;
	}

	if( $data['method'] != 'add' ) {
      $list .= <<<HTML
			</tbody>
		</table>
HTML;

	}

	close(json_encode(array('status' => 'ok', 'content' => $list,'num' => $rows)));
  
?>
