<?php
  ($_COOKIE['curr'] == '') ? $currList = 4 : $currList = $_COOKIE['curr'];
  
  include "../../../../func/config.php";
  include "../../../../func/mysql.php";
  include "../../../../func/db.class.php";
  include "../../../../func/main.php";
  include "../../../../func/product.class.php";
  include "../../../currency/currclass.php";
  
  $currClass = new currConvFPayC();
  $bd = new mysql();
  $db = new db();
  $user = new user();
  $user->verify($_SESSION['s'],'user,moder,admin');
	
  $data = json_decode($_POST['data'], true);
  
  $elements_on_page = (int) $data['elements_on_page'];
  $elements_on_page = $elements_on_page ? $elements_on_page : $elementLoadInPage;
  
  $where = "p.showing = 1 AND p.hidden = 0 AND p.searchHidden = 0";
  $tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productList/func/productListShow.tpl");
  
  $current_list = (int)$data['current_list'];
  if($current_list == 0)$elementLoadInPageStart = 0;
  else $elementLoadInPageStart = $current_list * $elements_on_page;  

  if($data['search'] != ""){
  	 $search = $db->safesql(trim($data['search']));
     $search = explode(" ", $search);
  	 $where .= " AND (LOWER(p.name) LIKE BINARY LOWER('%".$search[0]."%')";
     if(count($search > 1)){
	 	for($i=1;$i<count($search);$i++){
			$where .= " AND LOWER(p.name) LIKE BINARY LOWER('%".$search[$i]."%')";
		}
	 }
	 $where .= ")";
  }

  $cat = (int)$data['cat'];
  if($cat != 0) $where .= " AND p.group = ".$cat;
  $sortArr = array('p.date DESC', 
                   'p.sale DESC', 
				   'p.price',
				   'p.price DESC',
				   'p.name');

  $sort = (int)$data['sort'];
  if($sort < 0 || $sort > 4)close();
  $sort = $sortArr[$sort];

    $product_count = $db->super_query_value("SELECT COUNT(*) FROM product p WHERE $where");

    $request = $db->query("
        SELECT 
            p.id, p.idUser, p.name, p.sale, p.price, p.curr,
            u.login,
            p.picture, pic.name AS pictureName, pic.path,
            COUNT(d.id) AS discount,
            p.rating
        FROM (
            SELECT p.*, priv.id AS priv
            FROM product p
            LEFT JOIN user_privileges_product_up priv ON
                priv.productId = p.id
                AND priv.date > NOW()
            WHERE $where
            ORDER BY (CASE WHEN priv.id IS NULL THEN 1 ELSE 0 END), $sort
            LIMIT $elementLoadInPageStart, $elements_on_page
        ) p
        JOIN user u ON u.id = p.idUser
        LEFT JOIN picture pic ON pic.id = p.picture
        LEFT JOIN discount d ON d.userId = p.idUser
        GROUP BY p.id
        ORDER BY (CASE WHEN p.priv IS NULL THEN 1 ELSE 0 END), $sort
    ");

    $list = "";
    while($row = $db->get_row($request)){
		$discount = $row['discount'] ? '<i class="sprite sprite_percent"></i>' : '';
		if($user->active_privileges($row['idUser'])){
			$pro = 'PRO SELLER';
			$proIcon = '<img width="24px" height="24px" src="/style/img/logopriv.png">';
			$proClass = 'pro';
		}else{
			$pro = 'UNKNOWN SELLER';
			$proIcon = '';
			$proClass = 'default';
		}

		if($user->up_interval(NULL, NULL, $row['id'])){
			$product_upped = 'background-color:#337AB7';
		}else{
			$product_upped = '';
		}

		//get percent labels
		$labels 		= product::getPercentLabels($row['rating']);
		$minusClass2 	= $labels['minusClass2'];
		$stars 			= $labels['stars'];
		$percents 		= $labels['percents'];
		
		if($row['picture']){
			$picture = $row['pictureName'] ? '/'.$row['path'].$row['pictureName'] : '/picture/'.$row['path'].'productshow.jpg';
		}else{
			$picture = '/stylisation/images/no_img.png';
		}
		$picture = $CONFIG['cdn'].$picture;


		$curr = curr_ident($row['curr']);
		$list .= '<div class="newmaingood">
<div class="newgoodviews">
		<div class="newnameanddiscount">
			<div class="newnamegood"><a href="/product/'.$row['id'].'/" class="lot_name">'.$row['name'].'</a></div>
			<div class="newdiscountgood">'.$discount.'</div>
			<span class="mainPageTooltip1" class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="'.$pro.'">'.$proIcon.'</span>
		</div>
			<div class="newdiscrgood '.$proClass.' " style="'.$product_upped.'">
				<div class="newpicturegood">
					<div class="newpicturegood"><a href="/product/'.$row['id'].'/" title="'.$row['name'].'"><img src="'.$picture.'" alt="'.$row['name'].'"></a></div>
				</div>
					<div class="newpriceandname">
						<div class="newpricegood"><a href="/product/'.$row['id'].'/" class="lot_name">'.$currClass->curr($row['price'],$row['curr'],$currList).'</a></div>
						<div class="newnameseller '.$proClass.'">
							<a href="/seller/'.$row['idUser'].'/">'.$row['login'].'</a>
						</div>
					</div>
						<div class="newsalesandrating">
							<div class="newsalesgood"><a href="/product/'.$row['id'].'/" class="lot_name">Продаж: '.$row['sale'].'</a></div>
								<div class="newratinggood">
									<div class="newstars"><div class="productRate">
					    <div class="'.$minusClass2.'" style="width: '.$stars.'"></div>
					</div></div>
									<div class="newpercent">'.$percents.'</div>
			    				</div>
						</div>
			</div>
	</div>
</div>';
  }
	$tpl = str_replace("{productList}", $list, $tpl);
	
	if($data['method'] != 'add'){
		if(!$user->id)$list .= '<div class="tableAddMy"><div><img src="/stylisation/images/addmy.png"> <a href="/signup/">Добавить свой товар</a></div></div>';
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'module.main.productlist.get');
		//$list .= '<div id="list_add_module.main.productlist.get"></div>';
		$list .= $pagination;
	}
	/*if($data['method'] != 'add')$tpl .= $pagination;
	else $tpl = $list;*/

	close(json_encode(array('status' => 'ok','content' => $list,'num' => $product_count)));
  
?>
