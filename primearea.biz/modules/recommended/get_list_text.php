<?php
	include "../../func/config.php";
	include "../../func/mysql.php";
	include "../../func/db.class.php";
	include "../../func/main.php";
	include '../currency/currclass.php';
  
	$bd = new mysql();
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/recommended/text_list.tpl"));
	$curr = new current_convert();
	$user = new user();
	$db = new db();
	$user->verify($_SESSION['s'],'user,moder,admin');
	
	$data = json_decode($_POST['data'], true);
	$elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
	$current_list = (int)$data['current_list'];
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  
	
	$where = ' WHERE 1 AND p.hidden = 0 ';
	$cat = (int)$data['cat'];
	if($cat != 0) $where .= " AND p.group = ".$cat;
	
	$request = $bd->read("	SELECT SQL_CALC_FOUND_ROWS
								ad.id, p.name, p.price, p.curr, p.sale,
								u.login, u.rating, u.id, p.picture,
								pg.name AS groupName3,pgg.name AS groupName2,pggg.name AS groupName1,
								pic.name, pic.path, (SELECT count(d.id) from discount d where d.userId = p.idUser), p.rating
							FROM ad
							INNER JOIN product p ON p.id = ad.product_id
							INNER JOIN user u ON u.id = p.idUser
							JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
							JOIN productgroup pg ON pg.id = p.group
							JOIN productgroup pgg ON pgg.id = pg.subgroup
							JOIN productgroup pggg ON pggg.id = pgg.subgroup
							".$where." AND ad.type = 'Текстовое' 
									   AND ad.status = 'Активно' 
										AND p.showing = 1
							ORDER BY ad.price DESC
							LIMIT ".$elementLoadInPageStart.", ".$elements_on_page);
	$product_count = $bd->total_rows;
		
	$tpl->fory('TEXT');

	$getPicture = function($request, $row) use ($CONFIG) {
		if(mysql_result($request,$row,8)){
			$path = mysql_result($request,$row,12) ? '/'.mysql_result($request,$row,13).mysql_result($request,$row,12) : '/picture/'.mysql_result($request,$row,13).'productshow.jpg';
		}else{
			$path = '/stylisation/images/no_img.png';
		}
		$path = $CONFIG['cdn'].$path;

		return $path;
	};

	$getRate = function($request, $row, $type){

		$rating = mysql_result($request,$row,15);
		

		$minusClass = '';
		if($rating > 0){//положительная шкала
			$stars = $rating.'%';
			$percents = '<span style="color:green">'.$rating.'%</span>';
		}elseif($rating < 0){//отрицательная шкала.
			$stars = $rating.'%';
			$percents = '<span style="color:red">'.$rating.'%</span>';
			$minusClass = 'minus';
		}elseif($bads == 0 && $goods == 0){// нету отзывов
			$stars = '0%';
			$percents = '0%';
		}

		if($type == 'stars'){
			return $stars;
		}elseif($type == 'percents'){
			return $percents;
		}elseif($type == 'minusClass'){
			return $minusClass;
		}else{
			return 'undefined';
		}
	};


	for($i=0;$i<$bd->rows;$i++){

		if($user->active_privileges(mysql_result($request,$i,7))){
			$pro = 'PRO SELLER';
			$proIcon = '<img width="27px" height="27px" src="/style/img/logopriv.png">';
			$proClass = 'pro';
		}else{
			$pro = 'UNKNOWN SELLER';
			$proIcon = '';
			$proClass = 'default';
		}
		
		$curr_price = $_COOKIE['curr'] ? $_COOKIE['curr'] : 4;
		$price = $curr->curr(mysql_result($request,$i,2), mysql_result($request,$i,3), $curr_price);
		$tpl->fory_cycle(array(	'id' => mysql_result($request,$i,0),
								'price' => $price,
								'seller_id' => mysql_result($request,$i,7),
								'seller' => mysql_result($request,$i,5),
								'rating' => mysql_result($request,$i,6),
								'sale' => mysql_result($request,$i,4),
								'name' => strBaseOut(mysql_result($request,$i,1)),
								'group' => mysql_result($request,$i,10).'/'.mysql_result($request,$i,11).'/'.mysql_result($request,$i,9),
								'picture' => $getPicture($request, $i),
								'percent' => '<td class="vertical_align">' . (mysql_result($request, $i, 14) ? '<i class="sprite sprite_percent"></i>' : '').'</td>',
								'stars' => $getRate($request, $i, 'stars'),
								'percents' => $getRate($request, $i, 'percents'),
								'minusClass' =>$getRate($request, $i, 'minusClass'),
								'pro' => $pro,
								'proIcon' => $proIcon,
								'proClass' => $proClass,
						));
	}



	$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	
	
	
	$add = $tpl->ify('ADD');	
	if($data['method'] != 'add'){
		if(!$user->id)$tpl->content .= '<div class="tableAddMy"><div><img src="/stylisation/images/addmy.png"> <a href="/signup/">Добавить свой товар</a></div></div>';
		$tpl->content = str_replace($add['orig'], $add['if'], $tpl->content);
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'user_recommended.text_list.get', 'only_pages');
		$tpl->content .= $pagination;
	}else $tpl->content = str_replace($add['orig'], $add['else'], $tpl->content);	
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content, 'exist' => $product_count,'num' => $product_count)));
?>
