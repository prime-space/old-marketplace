<?php
class shop{
	public static function product_listing_show_static_version($page_number){
		global $bd;
		
		$elements_on_page = 30;
		$element_start = (int)$page_number * $elements_on_page;
		
		$request = $bd->read("SELECT id, name FROM product WHERE block='ok' AND (moderation = 'ok' OR moderation IS NULL) ORDER BY id DESC LIMIT ".$element_start.", ".$elements_on_page);
		$return = '';
		for($i=0;$i<$bd->rows;$i++){
			$product_id = mysql_result($request,$i,0);
			$name = strBaseOut(mysql_result($request,$i,1));
			$return .= '<div><a href="/product/'.$product_id.'/">'.$name.'</a></div>';
		}
		
		$request = $bd->read("SELECT COUNT(id) FROM product WHERE block='ok' AND (moderation = 'ok' OR moderation IS NULL) ");
		$total = floor(mysql_result($request,0,0) / $elements_on_page);
		$pagination = array();
		for($i=0;$i<$total;$i++)$pagination[] = '<a href="/main/'.$i.'/">'.($i+1).'</a>';
		$return .= '<div>'.implode($pagination, ' ').'</div>';
		return $return;
	}
	public static function show_product(){
		global $bd, $CONFIG, $construction, $user;
		include_once 'product.class.php';

		bcscale(2);
		$currList = $_COOKIE['curr'] ? $_COOKIE['curr'] : 4;
		$product_id = (int)$_GET['productid'];	
		
		$construction->jsconfig['product']['id'] = $product_id;
		$return = array(
			'content' => '',
			'title' => '',
			'keywords' => '',
			'description' => ''
		);
		
		$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productShow/productShow.tpl");
		 
		$request = $bd->read("	SELECT p.id, p.name, p.descript, p.info, p.price, p.curr, p.idUser, p.block, 
										 (SELECT u.login FROM user u WHERE u.id = p.idUser LIMIT 1),
										 p.sale, p.typeObject,
										(SELECT name FROM productgroup WHERE id = p.group LIMIT 1),
										(SELECT subgroup FROM productgroup WHERE id = p.group LIMIT 1)ggg,
										(SELECT name FROM productgroup WHERE id = ggg  LIMIT 1),
										(SELECT name FROM productgroup WHERE id = (SELECT subgroup FROM productgroup WHERE id = ggg LIMIT 1) LIMIT 1),
										p.picture, pic.name, pic.path, p.partner, p.rating
								FROM product p
								JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
								WHERE p.id = '".$product_id."' AND p.hidden  = 0
								GROUP BY p.id
								LIMIT 1");
		if(!$bd->rows)return array('content' => 'Что-то пошло не так. Попробуйте перейти к другому товару, который точно есть (или на главную).');
		$id = mysql_result($request,0,0);
		$name = strBaseOut(mysql_result($request,0,1));
		$descript = strBaseOut(mysql_result($request,0,2));
		$info = strBaseOut(mysql_result($request,0,3));
		$price = mysql_result($request,0,4);
		$curr = mysql_result($request,0,5);
		$sellerid = mysql_result($request,0,6);
		$seller = mysql_result($request,0,8);
		$sale = mysql_result($request,0,9);
		$typeObject = mysql_result($request,0,10);
		$group0 = strBaseOut(mysql_result($request,0,11));
		$group1 = strBaseOut(mysql_result($request,0,13));
		$group2 = strBaseOut(mysql_result($request,0,14));
		$partnerFee = mysql_result($request,0,18);
			
		$rating = (int)mysql_result($request,0,19);

		//get percent labels
		$labels 		= product::getPercentLabels($rating);
		$minusClass2 	= $labels['minusClass2'];
		$stars 			= $labels['stars'];
		$percents 		= $labels['percents'];

		//get count labels
        $countLabels    = product::getCountReviews($id);
        $reviewGood     = $countLabels['reviewGood'];
        $reviewBad     = $countLabels['reviewBad'];
        


		if($user->active_privileges($sellerid)){
			$tpl = str_replace("{pro}", '<div style="border-radius:3px;margin-right:20px;background: black;color:white;text-align:center;">PRO SELLER</div>', $tpl);
		}else{
			$tpl = str_replace("{pro}", '<div style="border-radius:3px;margin-right:20px;background: black;color:white;text-align:center;">UNKNOWN SELLER</div>', $tpl);
		}

		if(mysql_result($request,0,15)){
			$picture = mysql_result($request,0,16) ? '/'.mysql_result($request,0,17).mysql_result($request,0,16) : '/picture/'.mysql_result($request,0,17).'productshow.jpg';
			$fullviewimage = mysql_result($request,0,16) ? '/'.mysql_result($request,0,17).mysql_result($request,0,16) : '/picture/'.mysql_result($request,0,17).'fullview.jpg';
		}else{
			$picture = '/stylisation/images/no_img.png';
			$fullviewimage = $picture;
		}
		$picture = $CONFIG['cdn'].$picture;
		$fullviewimage = $CONFIG['cdn'].$fullviewimage;

		$headpattern = '/(")|(<br \/>)|(\n)/';
		$return['title'] = 'Купить '.preg_replace($headpattern, '', $name).' на PRIMEAREA.BIZ';
		$return['keywords'] = $group0.', купить '.$group1.', купить '.$group2.', куплю '.$name.', продажа, мгновенная доставка, пин коды, PIN коды, WebMoney, цифровые товары, продать товар';
		$return['keywords'] = preg_replace($headpattern, '', $return['keywords']);
		$return['description'] = preg_replace($headpattern, '', $descript);
		$return['picture'] = $picture;
		$return['price'] = $price;


		$current_convert = new current_convert();
		$currNum = mysql_result($request,0,5);
		$priceCur = $current_convert->curr($price,$currNum,$currList);
		$priceCalc = $current_convert->curr($price,$currNum,4,false);
		

		$partner = '';
		if($partnerFee){
			$partnerFee = bcmul(bcdiv($partnerFee, 100), $priceCalc);
			$partnerFee = $current_convert->curr($partnerFee,4,4);
			$partner = '<div class="ProductShow_partner"><div class="ProductShow_partner_Title">Партнерская программа:</div><div class="ProductShow_partner_Content">С каждой продажи, совершенной по партнерской ссылке, Вы получите '.$partnerFee.'<div style="margin-top: 5px;text-align: right;"><a class="btn btn-primary" href="/partcode/'.$product_id.'/" target="_blank">Получить код ссылки</a></div></div></div>';
		}
		$return['discount'] = $partnerFee;
		$tpl = str_replace('{partner}', $partner, $tpl);
		$tpl = str_replace("{partnertab}", $partner ? '<div class="middle_lot_t_l_btn" data-tabs-btn="3">Партнёрам</div>' : '', $tpl);

		$typeObject = $typeObject ? "Текст" : "Файл";

		$block = mysql_result($request,0,7);
		if($block == 'blocked'){
			$return['content'] = 'Товар заблокирован';
			return $return;
		}
		if($block == 'deleted'){
			$return['content'] = 'Товар удален';
			return $return;
		}
	  
		$request = $bd->read("SELECT id, login, rating FROM user WHERE `id` = (
										  SELECT idUser FROM product 
										  WHERE id = ".$product_id." LIMIT 1) LIMIT 1");
		
		$productUserId = mysql_result($request,0,0);
		$productUserLogin = mysql_result($request,0,1);
		$seller_rating = mysql_result($request,0,2);	
		if($seller_rating>=0){
			$seller_rating = '<span style="color:green">'.$seller_rating.'</span>';
		}else{
			$seller_rating = '<span style="color:red">'.$seller_rating.'</span>';
		}

        $productIds = [];
		$request2 = $bd->read("SELECT  id
		    FROM product p
			WHERE p.idUser = ".$productUserId." AND p.showing = 1
		");
		for($i=0;$i<mysql_num_rows($request2);$i++){
			$productIds[] = mysql_result($request2,$i,0);
		}

        $seller_goods = 0;
        $seller_bads = 0;
        if ($productIds) {
            $productIds = implode(',', $productIds);
            $request3 = $bd->read("SELECT id, good
                FROM review
                WHERE idProduct IN(".$productIds.") AND del = 0
            ");
            for($i=0;$i<mysql_num_rows($request3);$i++){
                if(mysql_result($request3,$i,1) == 1){
                    $seller_goods++;
                }else{
                    $seller_bads++;
                }
            }
        }

		$sellerLabels = $user->getRating($seller_goods, $seller_bads);
		$seller_stars = $sellerLabels['seller_stars'];
		$seller_percents = $sellerLabels['seller_percents'];
		$minusClass = $sellerLabels['minusClass'];

		$request = $bd->read("	SELECT money, percent 
								FROM discount 
								WHERE userId = ".$productUserId."
								ORDER BY percent
								LIMIT 10");
		$rows = mysql_num_rows($request);
		if($rows > 0){
			$discountDisplay = "block";
			$discount = "";
			for($i=0;$i<$rows;$i++){
				$money = mysql_result($request,$i,0);
				$percent = mysql_result($request,$i,1);
				$discount .= '<div>'.$money.'руб. скидка составляет '.$percent.'%</div>';
			}
		}else $discountDisplay = "none";
		
		$available = order::product_availability($product_id);
		if($available){
			$buy_button = '<a href="/buy/'.$product_id.'/" target="_blank" class="middle_lot_c_pay_btn1"><i class="sprite sprite_lot_cart"></i><span>Купить</span></a>';
			$availableInfo = '<span class="color_green">Есть в наличии</span>';
		}else{
			$buy_button = '<div class="middle_lot_c_pay_btn1"><i class="sprite sprite_lot_cart"></i><span>Нет товара</span></div>';
			$availableInfo = '<span class="color_red">Нет в наличии</span>';
		}
		
		$tpl = str_replace("{id}", $id, $tpl);
		$tpl = str_replace("{name}", $name, $tpl);
		$tpl = str_replace("{descript}", $descript, $tpl);
		$tpl = str_replace("{info}", $info, $tpl);
		$tpl = str_replace("{price}", $priceCur, $tpl);
		$tpl = str_replace("{buy_button}", $buy_button, $tpl);
		$tpl = str_replace("{email}", $email, $tpl);
		$tpl = str_replace("{review}", $review, $tpl);
		$tpl = str_replace("{discount}", $discount, $tpl);
		$tpl = str_replace("{discountDisplay}", $discountDisplay, $tpl);
		$tpl = str_replace("{productUserLogin}", $productUserLogin, $tpl);
		$tpl = str_replace("{sellerid}", $sellerid, $tpl);
		$tpl = str_replace("{seller}", $seller, $tpl);
		$tpl = str_replace("{sale}", $sale, $tpl);
		$tpl = str_replace("{typeObject}", $typeObject, $tpl);
		$tpl = str_replace("{group0}", $group0, $tpl);
		$tpl = str_replace("{group1}", $group1, $tpl);
		$tpl = str_replace("{group2}", $group2, $tpl);
		$tpl = str_replace("{picture}", $picture, $tpl);	
		$tpl = str_replace("{fullviewimage}", $fullviewimage, $tpl);	
		$tpl = str_replace("{reviewGood}", $reviewGood ? '+'.$reviewGood : 0, $tpl);	
		$tpl = str_replace("{reviewBad}", $reviewBad ? '-'.$reviewBad : 0, $tpl);	
		$tpl = str_replace("{availableInfo}", $availableInfo, $tpl);
		$tpl = str_replace("{stars}", $stars, $tpl);
		$tpl = str_replace("{percents}", $percents, $tpl);
		$tpl = str_replace("{seller-rating}", $seller_rating, $tpl);
		$tpl = str_replace("{seller-stars}", $seller_stars, $tpl);
		$tpl = str_replace("{seller-percents}", $seller_percents, $tpl);
		$tpl = str_replace("{minusClass}", $minusClass, $tpl);
		$tpl = str_replace("{minusClass2}", $minusClass2, $tpl);
		
		
		$return['content'] = $tpl;
		
		return $return;
	}
	public static function cron_search_hidden_product()
    {
        global $db;

        $db->query("
          UPDATE product
          SET searchHidden = 1
          WHERE `name` like '%skype%' OR  `name` like '%скайп%'
        ");
    }
	public static function cron_showing_product(){
		global $db;

		$request = $db->query("
            SELECT p.id
            FROM product p
            LEFT JOIN product_file pf ON
                pf.idProduct = p.id AND
                pf.status = 'sale'
            LEFT JOIN product_text pt ON
                pt.idProduct = p.id AND
                pt.status = 'sale'
            WHERE
                (
                    pf.id IS NOT NULL OR
                    pt.id IS NOT NULL
                ) AND
                p.moderation = 'ok' AND
                p.block = 'ok'
            GROUP BY p.id
		");
		$ids = [0];
        while($row = $db->get_row($request)){
            $ids[] = $row['id'];
        }
        $db->query("
          UPDATE product
          SET showing = CASE
            WHEN id IN(".implode(",", $ids).") THEN 1
	        ELSE 0
	      END
        ");
	}
	public static function cron_in_stock_product(){
		global $db;

		$request = $db->query("
            SELECT p.id
            FROM product p
            LEFT JOIN product_file pf ON
                pf.idProduct = p.id AND
                pf.status = 'sale'
            LEFT JOIN product_text pt ON
                pt.idProduct = p.id AND
                pt.status = 'sale'
            WHERE
                pf.id IS NOT NULL OR
                pt.id IS NOT NULL
            GROUP BY p.id
		");
		$ids = [];
        while($row = $db->get_row($request)){
            $ids[] = $row['id'];
        }
        $db->query("
            UPDATE product
            SET inStock = CASE
                WHEN id IN(".implode(",", $ids).") THEN 1
                ELSE 0
            END
        ");
	}
	public static function cron_statistic_cache(){
		global $bd;
		
		$tpl = new templating(file_get_contents(TPL_DIR.'statistic.tpl'));
		$request = $bd->read("
			SELECT 
				COUNT(`id`), 
				(SELECT COUNT(`id`) FROM `product` WHERE `block` = 'ok' AND (`moderation` = 'ok' OR `moderation` IS NULL) AND `showing` = 1 ),
				(SELECT COUNT(`id`) FROM `order` WHERE `status` = 'sended' OR `status` = 'review') 
			FROM `user` 
			WHERE 
				`status` = 'ok' AND 
				rating > 0
		");
		
		$tpl->set('{user}', mysql_result($request,0,0));
		$tpl->set('{product}', mysql_result($request,0,1));
		$tpl->set('{order}', mysql_result($request,0,2));
		
		echo mysql_result($request,0,2);
		
		$fp = fopen("../cache/statistic.html", "w");
		$test = fwrite($fp, $tpl->content);
		fclose($fp);		
	}
	/**** новый cron_group_cache ****/
	public static function cron_group_cache() {

		function generate( $arr, $id = 0, $level = 0, $count, $url = array() ) {
			$result = '';

			$result_arr = array(
				'html'	=> '',
				'count'	=> 0
			);

			if( count( $arr ) ) {

				foreach ( $arr AS $cats ) {
					if( $cats['subgroup'] == $id ) {
						if ( $cats['id'] ) {
							$root_category[] = $cats['id'];

						}

					}

				}

				foreach ( $root_category AS $id ) {
					if ( $level == 0 AND $arr[$id]['name'] != '' ) {
						$result .= <<<HTML
<div class="_group" style="display:inline-block">
<div class="menu_block_head">{$arr[$id]['name']}
<div class="menu_block_head_img"><img src="/style/img/category.png"/></div>
</div>
<div class="category_group">

HTML;
						$url[0] = $arr[$id]['muu'];
						$result .= generate( $arr, $id, 1, TRUE, $url );
						$result .= <<<HTML
</div>
</div>
HTML;
					} else if ( $level == 1 AND $arr[$id]['name'] != '' ) {
						$url[1] = $arr[$id]['muu'];
						$result_arr = generate( $arr, $id, 2, TRUE, $url );

						if ( $result_arr['count'] > 0 ) {
							$result .= <<<HTML
<div class="category_block" data-category="{$arr[$id]['id']}">
	<div class="category_group_triangle"></div>
 	<div class="category_group_border"></div>
	<div class="category" data-category-btn="{$arr[$id]['id']}">
		<i class="sprite sprite_menu_arroy"></i>
		<div class="category_name">{$arr[$id]['name']}</div>
		<i class="label label_category_count">{$result_arr['count']}</i>
	</div>
	<div class="pod_category_block" data-pod_category="{$arr[$id]['id']}">
		{$result_arr['html']}
	</div>
</div>
		
HTML;

						}

					} else if ( $level == 2 AND $arr[$id]['name'] != '' AND $arr[$id]['muu'] != '' ) {
						if ( $arr[$id]['count'] < 1 ) {
							$count = 0;

						} else {
							$count = $arr[$id]['count'];

						}

						if ( $count > 0 ) {
							$http_url = '/category/' . $url[0] . '/' . $url[1] . '/' . $arr[$id]['muu'] . '/';

							$result_arr['html'] .= <<<HTML
<a href="{$http_url}" class="pod_category">
			<i class="sprite sprite_pod_menu_arroy"></i>
			<div class="pod_category_name">{$arr[$id]['name']}</div>
			<i class="label label_pod_category_count">{$count}</i>
		</a>
		
HTML;

						}

						$result_arr['count'] = $result_arr['count'] + $count;

					}


				}

			}

			if ( $level == 2 ) {
				return $result_arr;
	
			} else {
				return $result;

			}

		}

		global $bd;

		$request = $bd->read( "SELECT `id`, `subgroup`, `name`, `muu` FROM `productgroup` ORDER BY `name` ASC" );

		$pga = array();
		for( $i = 0; $i < $bd->rows; $i++ ) {
			$id = mysql_result( $request, $i, 0 );
			$subgroup = mysql_result( $request, $i, 1 );
			$name = mysql_result( $request, $i, 2 );
			$muu = mysql_result( $request, $i, 3 );

			if ($id == 865) {
				continue;
			}

			$pga[$id] = array();

			$pga[$id]['id'] = $id;
			if ( $subgroup == NULL ) {
				$subgroup = 0;

			}
			$pga[$id]['subgroup'] = $subgroup;
			$pga[$id]['name'] = $name;
			$pga[$id]['muu'] = $muu;

		}

		$request = $bd->read( "SELECT `group`, count( id ) AS count_id FROM `product` WHERE `showing` = '1' AND (`moderation` = 'ok' OR `moderation` IS NULL) AND block = 'ok' GROUP BY `group` ORDER BY `group` ASC" );
		for( $i = 0; $i < $bd->rows; $i++ ) {
			$id = mysql_result( $request, $i, 0 );
			$count = mysql_result( $request, $i, 1 );
			if ( ! $count ) {
				$count = 0;

			}
			$pga[$id]['count'] = $count;

		}
		
		$pga = generate( $pga, 0, 0, 0, array() );

		$fp = fopen( '../cache/categories.html', 'w' );
		$test = fwrite( $fp, $pga );
		fclose( $fp );


	}
	/**** старый cron_group_cache ****/
	/*public static function cron_group_cache(){

		global $bd;
		
		$request = $bd->read("	SELECT 	pg2.id, (SELECT COUNT(id) FROM product WHERE showing = 1 AND `group` IN(SELECT id FROM productgroup WHERE subgroup = pg2.id))
								FROM productgroup pg1 
								LEFT JOIN productgroup pg2 ON pg2.subgroup = pg1.id
								WHERE pg1.subgroup IS NULL 
								ORDER BY pg1.name, pg2.name");
		$count2 = array();
		for($i=0;$i<$bd->rows;$i++)$count2[mysql_result($request,$i,0)] = mysql_result($request,$i,1);

		$request = $bd->read("SELECT 
									pg1.id, 
									pg1.name, 
									pg1.muu, 
									pg2.id, 
									pg2.name, 
									pg2.muu, 
									pg3.id, 
									pg3.name, 
									pg3.muu, 
									(SELECT COUNT(id) FROM product WHERE showing = 1 AND `group` = pg3.id)
										FROM 
											productgroup pg1 
												LEFT JOIN 
											productgroup pg2 
												ON pg2.subgroup = pg1.id
												LEFT JOIN 
											productgroup pg3 
												ON pg3.subgroup = pg2.id
													WHERE 
														pg1.subgroup IS NULL 
															ORDER BY 
																pg1.name, pg2.name, pg3.name");
		if(!$bd->rows)return;
		
		$category = '';
		$pod_category = array();
		$id1 = false;
		$id2 = false;
		
		for( $i = 0; $i < $bd->rows; $i++ ) {
			if( ! $id1 || $id1 != mysql_result( $request,$i,0 ) ) {
				$id1 = mysql_result( $request,$i,0 );
				$name1 = mysql_result( $request,$i,1 );
				$muu1 = mysql_result( $request,$i,2 );
				$category .= <<<HTML
						<div class="menu_block_head">{$name1}</div>
HTML;
				$id2 = false;
			}

			if( !$id2 || $id2 != mysql_result( $request,$i,3 ) ) {
				$id2 = mysql_result( $request,$i,3 );
				$name2 = mysql_result( $request,$i,4 );
				$muu2 = mysql_result( $request,$i,5 );
				if( $count2[$id2] ){
					$pod_category[$id2] = array();
					$category .= <<<HTML
					<div class="category_block" data-category="{$id2}">
						<div class="category" data-category-btn="{$id2}">
							<i class="sprite sprite_menu_arroy"></i>
							<div class="category_name">{$name2}</div>
							<i class="label label_category_count">{$count2[$id2]}</i>
						</div>
						<div class="pod_category_block" data-pod_category="{$id2}">{pod_category_{$id2}}</div></div>
HTML;

				}

			}

			if( !$id3 || $id3 != mysql_result( $request,$i,6 ) ) {
				$id3 = mysql_result( $request,$i,6 );
				$name3 = mysql_result( $request,$i,7 );
				$muu3 = mysql_result( $request,$i,8 );
				$muu = '/category/'.$muu1.'/'.$muu2.'/'.$muu3.'/';
				$count3 = mysql_result($request,$i,9);
				
				if( $count3 ) {
					$pod_category[$id2][] = <<<HTML
						<a href="{$muu}" class="pod_category">
							<i class="sprite sprite_pod_menu_arroy"></i>
							<div class="pod_category_name">{$name3}</div>
							<i class="label label_pod_category_count">{$count3}</i>
						</a>
HTML;

				}

			}

		}

		foreach ( $pod_category AS $pc_id => $pc_arr ) {
			$pod_category_content = '';
			foreach ( $pc_arr AS $pc ) {
				$pod_category_content .= $pc;

			}
			//$pod_category_content = implode( '', $pc_arr );
			$category = str_replace( "{pod_category_" . $pc_id . "}", $pod_category_content, $category );

		}
		echo $category;
		$fp = fopen("../cache/categories.html", "w");
		$test = fwrite($fp, $category);
		fclose($fp);

	}*/
	public static function convertToMuu($str){
		$str = mb_strtolower($str, 'UTF-8');
		$str = str_replace(
			array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','-',' '),
			array('a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sh','','i','','e','u','ya','','-'),
			$str
		);
		$str = preg_replace("/[^a-z0-9\-]/",'', $str);
		return($str);
	}
	public static function price_format($price){
		return number_format((float)$price, 2, '.', '');
	}
	public static function reg_exp($str, $method){
		switch($method){
			case 'money': $exp = '/^\d{1,16}(\.\d{1,2})?$/'; break;
			case 'current': $exp = '/^1|2|3|4$/'; break;
		}
		return preg_match($exp, $str);
	}
	public static function url_replace($str){
		return preg_replace('#((https?|ftp)://)(.*)((&lt;br)|(\s)|$)#iuU', '<a href="$1$3" target="_blank">$1$3</a>$4', $str);
	}
	public static function url_replace_out($str){
		return preg_replace('#<a href="(.*)" target="_blank">(.*)</a>#iuU', '$2', $str);
	}
	public static function random_str($length){
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$numChars = strlen($chars);
		$str = '';
		for($i=0;$i<$length;$i++)$str .= substr($chars, rand(1, $numChars) - 1, 1);
		return $str;
	}


	public static function get_last_sells(){
		
		global $bd, $CONFIG;
		include_once dirname(__file__)."/../modules/currency/currclass.php";
	
		$current_convert = new current_convert();

		//Блок последние продажы
		$tpl_last_sells = new templating(file_get_contents(TPL_DIR.'last_sells.tpl'));	
		$tpl_last_sells->fory('ITEMS');

		$last_sales = $bd->read("SELECT p.name, p.price, pic.path, pic.name, o.date, NOW(), p.id, p.curr, p.picture FROM `order` o
			LEFT JOIN product p ON p.id = o.productId
			LEFT JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
			WHERE p.hidden = 0 AND (o.status = 'sended' 
				OR 	o.status = 'paid' 
				OR 	o.status = 'review') order by o.date desc LIMIT 20");

		if($bd->rows){
			for($i=0;$i<$bd->rows;$i++){
				$product_name =  mysql_result($last_sales,$i,0);
				$product_price =  mysql_result($last_sales,$i,1);
				$picture_path =  mysql_result($last_sales,$i,2);
				
				$picture_name =  mysql_result($last_sales,$i,3);
				$date = mysql_result($last_sales,$i,4);
				$date_now = mysql_result($last_sales,$i,5);
				$id = mysql_result($last_sales,$i,6);
				$curr = mysql_result($last_sales,$i,7);
				$currency = 4;
				$product_price = $current_convert->curr($product_price,$curr,$currency);

				$picture = mysql_result($last_sales,$i,8);
				if($picture){
					$picture_name = $picture_name.'productshow.jpg';
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name;
				}else{
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name.'productshow.jpg';
				}

				$d = strtotime($date_now) - strtotime($date);
				
				if($d > 60){
					$d = intval($d/(60));
	
					if($d > 60){
						$d = intval($d/(60));

						$d = $d . ' часа назад';
					}else{

						if($d == 1){
							$d = $d .' минуту назад';
						}elseif($d == 2 || $d == 3 || $d == 4 ){
							$d = $d .' минуты назад';
						}else{
							$d = $d .' минут назад';
						}

						
					}
				}else{
					$d = $d .' секунд назад';
				}
	
				$tpl_last_sells->fory_cycle(array(	
						'title' => $product_name,
						'image_url' => $pic_path,
						'amount' => $product_price,
						'date'     => $d,
						'count'  => $i, 
						'id'     => $id
					)
				);		
			}
		}
		
		$tpl_last_sells->content = str_replace($tpl_last_sells->fory_arr['model_tags'], $tpl_last_sells->fory_arr['content'], $tpl_last_sells->content);	

		$fp = fopen("../cache/sidebar_last_sells.html", "w");
		$test = fwrite($fp, $tpl_last_sells->content);
		fclose($fp);

		//Блок последние продажы
		$tpl_last_sells = new templating(file_get_contents(TPL_DIR.'last_sells.tpl'));	
		$tpl_last_sells->fory('ITEMS');

		if($bd->rows){
			for($i=0;$i<$bd->rows;$i++){
				$product_name =  mysql_result($last_sales,$i,0);
				$product_price =  mysql_result($last_sales,$i,1);
				$picture_path =  mysql_result($last_sales,$i,2);
				
				$picture_name =  mysql_result($last_sales,$i,3);
				$date = mysql_result($last_sales,$i,4);
				$date_now = mysql_result($last_sales,$i,5);
				$id = mysql_result($last_sales,$i,6);
				$curr = mysql_result($last_sales,$i,7);
				$currency = 1;
				$product_price = $current_convert->curr($product_price,$curr,$currency);

				$picture = mysql_result($last_sales,$i,8);
				if($picture){
					$picture_name = $picture_name.'productshow.jpg';
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name;
				}else{
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name.'productshow.jpg';
				}

				$d = strtotime($date_now) - strtotime($date);
				
				if($d > 60){
					$d = intval($d/(60));
	
					if($d > 60){
						$d = intval($d/(60));

						$d = $d . ' часа назад';
					}else{

						if($d == 1){
							$d = $d .' минуту назад';
						}elseif($d == 2 || $d == 3 || $d == 4 ){
							$d = $d .' минуты назад';
						}else{
							$d = $d .' минут назад';
						}

						
					}
				}else{
					$d = $d .' секунд назад';
				}
	
				$tpl_last_sells->fory_cycle(array(	
						'title' => $product_name,
						'image_url' => $pic_path,
						'amount' => $product_price,
						'date'     => $d,
						'count'  => $i, 
						'id'     => $id
					)
				);		
			}
		}
		
		$tpl_last_sells->content = str_replace($tpl_last_sells->fory_arr['model_tags'], $tpl_last_sells->fory_arr['content'], $tpl_last_sells->content);	

		$fp = fopen("../cache/sidebar_last_sells_usd.html", "w");
		$test = fwrite($fp, $tpl_last_sells->content);
		fclose($fp);

		//Блок последние продажы
		$tpl_last_sells = new templating(file_get_contents(TPL_DIR.'last_sells.tpl'));	
		$tpl_last_sells->fory('ITEMS');

		if($bd->rows){
			for($i=0;$i<$bd->rows;$i++){
				$product_name =  mysql_result($last_sales,$i,0);
				$product_price =  mysql_result($last_sales,$i,1);
				$picture_path =  mysql_result($last_sales,$i,2);
				
				$picture_name =  mysql_result($last_sales,$i,3);
				$date = mysql_result($last_sales,$i,4);
				$date_now = mysql_result($last_sales,$i,5);
				$id = mysql_result($last_sales,$i,6);
				$curr = mysql_result($last_sales,$i,7);
				$currency = 2;
				$product_price = $current_convert->curr($product_price,$curr,$currency);

				$picture = mysql_result($last_sales,$i,8);
				if($picture){
					$picture_name = $picture_name.'productshow.jpg';
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name;
				}else{
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name.'productshow.jpg';
				}

				$d = strtotime($date_now) - strtotime($date);
				
				if($d > 60){
					$d = intval($d/(60));
	
					if($d > 60){
						$d = intval($d/(60));

						$d = $d . ' часа назад';
					}else{

						if($d == 1){
							$d = $d .' минуту назад';
						}elseif($d == 2 || $d == 3 || $d == 4 ){
							$d = $d .' минуты назад';
						}else{
							$d = $d .' минут назад';
						}

						
					}
				}else{
					$d = $d .' секунд назад';
				}
	
				$tpl_last_sells->fory_cycle(array(	
						'title' => $product_name,
						'image_url' => $pic_path,
						'amount' => $product_price,
						'date'     => $d,
						'count'  => $i, 
						'id'     => $id
					)
				);		
			}
		}
		
		$tpl_last_sells->content = str_replace($tpl_last_sells->fory_arr['model_tags'], $tpl_last_sells->fory_arr['content'], $tpl_last_sells->content);	


		$fp = fopen("../cache/sidebar_last_sells_grn.html", "w");
		$test = fwrite($fp, $tpl_last_sells->content);
		fclose($fp);

		//Блок последние продажы
		$tpl_last_sells = new templating(file_get_contents(TPL_DIR.'last_sells.tpl'));	
		$tpl_last_sells->fory('ITEMS');

		if($bd->rows){
			for($i=0;$i<$bd->rows;$i++){
				$product_name =  mysql_result($last_sales,$i,0);
				$product_price =  mysql_result($last_sales,$i,1);
				$picture_path =  mysql_result($last_sales,$i,2);
				
				$picture_name =  mysql_result($last_sales,$i,3);
				$date = mysql_result($last_sales,$i,4);
				$date_now = mysql_result($last_sales,$i,5);
				$id = mysql_result($last_sales,$i,6);
				$curr = mysql_result($last_sales,$i,7);
				$currency = 3;
				$product_price = $current_convert->curr($product_price,$curr,$currency);

				$picture = mysql_result($last_sales,$i,8);
				if($picture){
					$picture_name = $picture_name.'productshow.jpg';
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name;
				}else{
					$pic_path = $CONFIG['cdn'].'/picture/'.$picture_path.$picture_name.'productshow.jpg';
				}

				$d = strtotime($date_now) - strtotime($date);
				
				if($d > 60){
					$d = intval($d/(60));
	
					if($d > 60){
						$d = intval($d/(60));

						$d = $d . ' часа назад';
					}else{

						if($d == 1){
							$d = $d .' минуту назад';
						}elseif($d == 2 || $d == 3 || $d == 4 ){
							$d = $d .' минуты назад';
						}else{
							$d = $d .' минут назад';
						}

						
					}
				}else{
					$d = $d .' секунд назад';
				}
	
				$tpl_last_sells->fory_cycle(array(	
						'title' => $product_name,
						'image_url' => $pic_path,
						'amount' => $product_price,
						'date'     => $d,
						'count'  => $i, 
						'id'     => $id
					)
				);		
			}
		}
		
		$tpl_last_sells->content = str_replace($tpl_last_sells->fory_arr['model_tags'], $tpl_last_sells->fory_arr['content'], $tpl_last_sells->content);	


		$fp = fopen("../cache/sidebar_last_sells_eur.html", "w");
		$test = fwrite($fp, $tpl_last_sells->content);
		fclose($fp);


	}

	public static function get_popular_sells(){
		error_reporting(E_ALL);
		global $bd, $CONFIG;
		include_once dirname(__file__)."/../modules/currency/currclass.php";

		$currency = $_COOKIE['curr'];
		if(!$currency){
			$currency = 4;
		}

		$current_convert = new current_convert();

		$tpl_popular = new templating(file_get_contents(TPL_DIR.'popular.tpl'));	
		$tpl_popular->fory('ITEMS');
		

		$popular = $bd->read("SELECT count(o.productId), p.picture, o.productId, p.name, pic.name, pic.path, p.curr, p.picture  FROM `order` o
			LEFT JOIN product p ON p.id = o.productId
			LEFT JOIN picture pic ON pic.id = p.picture
			WHERE   p.hidden = 0 AND o.date > CURDATE() AND (o.status = 'sended' 
				OR 	o.status = 'paid' 
				OR 	o.status = 'review') GROUP BY o.productId ORDER BY count(o.productId) desc LIMIT 3");


		if($bd->rows){
			for($i=0;$i<$bd->rows;$i++){
				$count =  mysql_result($popular,$i,0);	
				$id =  mysql_result($popular,$i,2);	
				$product_name =  mysql_result($popular,$i,3);	
				$pic_name =  mysql_result($popular,$i,4);	
				$pic_path =  mysql_result($popular,$i,5);		
				
				$picture = mysql_result($popular,$i,7);
				if($picture){
					$pic_name = $pic_name.'productshow.jpg';
					$url = $CONFIG['cdn'].'/picture/'.$pic_path.$pic_name;
				}else{
					$url = $CONFIG['cdn'].'/picture/'.$pic_path.$pic_name.'productshow.jpg';
				}

				$tpl_popular->fory_cycle(array(	
								'title' => $product_name,
								'image_url' => $url,
								'count'  => $count, 
								'id'     => $id,
								'position' => $i
							)
						);

			}
		}

		$tpl_popular->content = str_replace($tpl_popular->fory_arr['model_tags'], $tpl_popular->fory_arr['content'], $tpl_popular->content);	

		$fp = fopen("../cache/sidebar_popular_sells.html", "w");
		$test = fwrite($fp, $tpl_popular->content);
		fclose($fp);


	}


}
class pagination{
	/*Кол-во продуктов, кол-во позиций для пподгрузки, необходимая страница, функция для javascript*/
	public static function getPanel($product_count, $elements_on_page, $current_list, $function, $conf = false, $before_pagination = false){
		global $CONFIG;
		$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/pagination.tpl"));
		$tpl->fory('PAGE_NO');
		$pages = ceil($product_count / $elements_on_page);
		
		$start_i = $current_list < 3 ? 1 : $current_list - 2;
		$end_i = ($pages-$start_i) > 5 ? $start_i+6 : $start_i+$pages;
		$end_i = $end_i <= $pages ? $end_i : $pages;
		
		if($current_list > 3){
			$dots = $current_list == 4 ? '' : '<li class=""><span>...</span></li>';
			$tpl->fory_cycle(array(	'p' => '1',
											'dats_previous' => '',
											'class' => '',
											'page_no_event' => $function.'(0, \'gotoanchor\');',
											'dats_next' => $dots));
		}		
		for($i=$start_i;$i<=$end_i;$i++){
			$link_class = ($current_list+1) == $i ? 'active' : '';
			$tpl->fory_cycle(array(	'p' => $i, 
											'class' => $link_class, 
											'page_no_event' => $function.'('.($i-1).', \'gotoanchor\');',
											'dats_previous' => '',
											'dats_next' => ''));
		}
		if($pages > 5 && ($current_list+1) < ($pages - 3)){
			$dots = $current_list == ($pages-5) ? '' : '<li class=""><span>...</span></li>';
			$tpl->fory_cycle(array(	'p' => $pages,
											'page_no_event' => $function.'('.($pages-1).', \'gotoanchor\');',
											'dats_previous' => $dots,
											'class' => '',
											'dats_next' => ''));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		
		$tpl->fory('POSITION_ON_PAGE');
		$amount_variant = array(10, 25, 50, 100);
		for($i=0;$i<count($amount_variant);$i++){
			$amount = $amount_variant[$i];
			$selected = $amount_variant[$i] == $elements_on_page ? $selected = "selected" : "";
			$tpl->fory_cycle(array(	'amount' 	=> 	$amount,
									'selected' 	=> 	$selected));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);		
		
		
		$tpl->content = str_replace("{site_address}", $CONFIG['site_address'], $tpl->content);
		$tpl->content = str_replace("{pages}", $pages, $tpl->content);
		$tpl->content = str_replace("{function}", $function, $tpl->content);
		$tpl->content = str_replace("{select_event}", $function.'(0, \'gotoanchor\');', $tpl->content);
		$tpl->content = str_replace("{add}", $function.'('.($current_list+1).', \'add\');', $tpl->content);	
		
		$load_more = $tpl->ify('LOAD_MORE');
		$seop = $tpl->ify('SELECT_ELEMENTS_ON_PAGE');
		if($conf == 'only_pages'){
			$tpl->content = str_replace($load_more['orig'], $load_more['else'], $tpl->content);
			$tpl->content = str_replace($seop['orig'], $seop['else'], $tpl->content);
		}else{
			$tpl->content = str_replace($load_more['orig'], $load_more[$product_count?'if':'else'], $tpl->content);
			$tpl->content = str_replace($seop['orig'], $seop['if'], $tpl->content);		
		}
		
		$before_pagination_td = $tpl->ify('BEFORE_PAGINATION_TD');

		if($before_pagination){
			$tpl->content = str_replace($before_pagination_td['orig'], $before_pagination_td['if'], $tpl->content);
			$tpl->set('{before_pagination}', $before_pagination);
		}else{
			$tpl->content = str_replace($before_pagination_td['orig'], $before_pagination_td['else'], $tpl->content);
		}

		return $tpl->content;
	}
}
class user{
	public $id;
	public $role;
	public $wmrbal;
	public $status;
	public $login;
	public $googleSecret;
	public $token;
	public $fio;
	public $random_key;

	public function verify($crypt, $access_roles){
		global $bd;
		
		$crypt = $_COOKIE['s'];
		
		if(!$crypt)return FALSE;
		$crypt = $bd->prepare($crypt, 64);
		$request = $bd->read("
			SELECT u.id, u.login, u.role, u.wmrbal, u.status, u.googleSecret, us.token, u.fio, u.random_key
			FROM usersession us
			INNER JOIN user u ON u.id = us.userid
			WHERE BINARY us.crypt = '".$crypt."' 
			LIMIT 1
		");

		if(!$bd->rows)return FALSE;//выходим если не находим
		
		$this->id = mysql_result($request,0,0);
		$this->login = mysql_result($request,0,1);
		$this->role = mysql_result($request,0,2);
		$this->wmrbal = mysql_result($request,0,3);
		$this->status = mysql_result($request,0,4);
		$this->googleSecret = mysql_result($request,0,5);
		$this->token = mysql_result($request,0,6);
		$this->fio = mysql_result($request,0,7);
		$this->random_key = mysql_result($request,0,8);

		$access_roles = explode(",", $access_roles);//Переводим строку ролей в массив, производим поиск, если не находим - выходим
		if(!in_array($this->role, $access_roles))return FALSE;
		
		$bd->write("UPDATE `usersession` SET `lastVisit` = '".time()."' WHERE `crypt` = '".$crypt."' LIMIT 1");//обновляем время записи
		$bd->write("UPDATE user SET last_action = NOW() WHERE id = ".$this->id." LIMIT 1");//обновляем время записи
		
		return TRUE;
	}
	public function panel(){
		global $bd;
		
		$content = '';

		if($this->status === 'blocked')$content .= '<div class="form_info_blocked">ВЫ ЗАБЛОКИРОВАНЫ</div><div class="form_info_blocked">Для уточнения причины, напишите в <a href="https://primearea.biz/panel/messages/" target="_blank">поддержку</a></div>';

		require_once $_SERVER['DOCUMENT_ROOT'].'/func/messages.class.php';
		$messages = new messages();
		$to = in_array($this->role, array('admin', 'moder')) ? 0 : $this->id;
		$numMessages = $messages->numMessages($to);
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/func/partner.class.php';
		$partner = new partner();
		$partnerMessages = $partner->haveMessages($this->id);
			
		require_once $_SERVER['DOCUMENT_ROOT'].'/func/customer.class.php';
		$customer = new customer();
		$cabinetMessages = $customer->numMessages($this->id);
		
		if(in_array($this->role, array('admin', 'moder'))){
			$partnerNotifications = $partner->numNotifications($this->id);
			
			$request = $bd->read("SELECT name, url, `right`, id FROM panel  ORDER BY `right`, sort");
			if($bd->rows == 0)return;

			$pos = "";
			$class_array = array( ' btn-danger', ' btn-warning', ' btn-primary' );
			$class_array_num = 0;
			for($i=0;$i<$bd->rows;$i++){
				$right = mysql_result($request,$i,2);
				if($i != 0 && $pos != $right){
					$content .= '<div class="form_probel"></div>';
					$class_array_num++;
				}
				$pos = $right;
				
				$addit = '';
				if(mysql_result($request,$i,3) == 22)continue;
				if(mysql_result($request,$i,3) == 12 && $numMessages)$addit .= $numMessages;
				elseif(mysql_result($request,$i,3) == 4 && $cabinetMessages)$addit .= $cabinetMessages;
				elseif(mysql_result($request,$i,3) == 21){
					if($partnerMessages)$addit .= '<img src="/stylisation/images/new_message.png">';
					if($partnerNotifications)$addit .= '('.$partnerNotifications.')';
				}else $addit = '';
				
				$content .= '<a class="btn' . $class_array[$class_array_num] . ' btn-panel" href="'.mysql_result($request,$i,1).'"><i class="sprite sprite_arrow_r"></i>'.mysql_result($request,$i,0).' '.$addit.'</a>';

			}	 

			$content .= '<div class="form_info">'.sitemapInfo().'</div>';
		}else $content .= '<a class="btn btn-primary btn-panel" href="/panel/cabinet/"><i class="sprite sprite_arrow_r"></i>Личный кабинет '.($partnerMessages || $cabinetMessages || $numMessages ? '<img src="/stylisation/images/new_message.png">' : '').'</a>';

		return('<div class="pa_middle_c_r_block pa_middle_c_r_block2">' . $content . '</div>');

	}
	public function newuserspage(){
		if($this->role === 'admin'){
			$tpladmin = new templating(file_get_contents(TPL_DIR.'newusersAdmin.tpl'));
			$moderlist = $tpladmin->content;
		}
		$tpl = new templating(file_get_contents(TPL_DIR.'newusersModer.tpl'));
		$tpl->set('{moderList}', $moderlist);
		
		return(array('content' => $tpl->content));
	}

	public function checkModerRight($module){
		global $db;

		if($this->id){
			$request = $db->super_query("SELECT `moder_rights` FROM `user` WHERE `id` = ".$this->id);
			$rights =  $request['moder_rights'];
			$rights = explode(',', $rights);
			$rights = array_flip($rights);
			
			return isset($rights[$module]);
		}else{
			return false;
		}
		
		
	}

	public function active_privileges($id = NULL){
		global $db;

		$startDate = time();
		$today = date('Y-m-d H:i:s', $startDate);
		$date = date('Y-m-d H:i:s', strtotime('+1 month', $startDate));

		if($this->id || $id){
			if($id){
				$user_id = (int)$id;
			}else{
				$user_id = $this->id;
			}
			$request = $db->query("SELECT `until`, `type`
				FROM `user_privileges`
				WHERE `user_id` = ".$user_id." AND `until` > '".$today."'"
			);
			return (bool) $request->num_rows;
		}else{
			return false;
		}
		
	}

	public function priv_type($id = NULL, $alias = false){
		global $db;

		$startDate = time();
		$today = date('Y-m-d H:i:s', $startDate);
		$date = date('Y-m-d H:i:s', strtotime('+1 month', $startDate));

		if($this->id || $id){
			if($id){
				$user_id = (int)$id;
			}else{
				$user_id = $this->id;
			}

			$request = $db->super_query("SELECT `type`
				FROM `user_privileges`
				WHERE `user_id` = ".$user_id." AND `until` > '".$today."'"
			);

			if($request){
				$type = $alias ? $this->getAliasByType($request['type']) : $request['type'] ;
			}else{
				$type = false;
			}

			return $type;
		}else{
			return false;
		}				 
		
		
	}

	private function getAliasByType($type){
		global $db;

		$result = $db->super_query('SELECT alias FROM user_privileges_settings WHERE id = '.(int)$type);
		$result ? $alias = $result['alias'] : 'undefined';

		return $alias;
	}

	public function priv_until($id = NULL){
		global $db;

		$startDate = time();
		$today = date('Y-m-d H:i:s', $startDate);
		$date = date('Y-m-d H:i:s', strtotime('+1 month', $startDate));

		if($this->id || $id){
			if($id){
				$user_id = (int)$id;
			}else{
				$user_id = $this->id;
			}
			$request = $db->super_query("SELECT `until`
				FROM `user_privileges`
				WHERE `user_id` = ".$user_id." AND `until` > '".$today."'"
			);
			return $request['until'];
		}else{
			return false;
		}					 
		
		
	}

	public function up_interval($interval, $up_count, $productId = NULL){
		global $db;

		if($productId){
			$productId = (int)$productId;
			$request = $db->super_query("SELECT `date` FROM user_privileges_product_up WHERE `date` > NOW() AND productId = ".$productId);			
		}else{
			$request = $db->super_query("SELECT `date` FROM user_privileges_product_up WHERE user_id = ".$this->id." AND `date` > NOW() ");			
		}
	 
		return $db->db_id->affected_rows;
	}

	public function getRating($seller_goods, $seller_bads){

		$minusClass = '';
		$seller_percents = '<span>0%</span>';

		if($seller_goods>$seller_bads){//положительная шкала
            $value = $seller_goods;
        }elseif($seller_bads>$seller_goods){//отрицательная шкала
            $value = $seller_bads;
        }elseif($seller_bads == 0 && $seller_goods == 0){// нету отзывов
        	$value = 0;
        }

		$v = intval($value*100/($seller_bads+$seller_goods));
		$seller_stars = $v.'%';
		
		if($v > 0){//положительная шкала
			$seller_percents = '<span style="color:green">'.$v.'%</span>';
		}elseif($v < 0){//отрицательная шкала
			$seller_percents = '<span style="color:red">'.$v.'%</span>';
			$minusClass = 'minus';
		}

		return ['seller_stars' => $seller_stars, 'seller_percents' => $seller_percents, 'minusClass' => $minusClass];
	}

	public function getLogin($id = NULL){
		global $db;

		if($id){

			$request = $db->super_query("SELECT `login`
				FROM `user`
				WHERE `id` = ".(int)$id
			);

			return $request ? $request['login'] : NULL;

		}else{

			return $this->login;

		}
	}

	public function removePrivCookie(){
		//remove privileges cookie for notification

		for ($i=1; $i <= 4 ; $i++) {
			$cookie_name = $this->id.':'.$i;
			if (isset($_COOKIE[$cookie_name])) {
			    unset($_COOKIE[$cookie_name]);
			    setcookie($cookie_name, '', time() - 3600, '/');
			}
		}

	}
}
class order{
    public function getById($orderId)
    {
        global $db;

        $order = $db->super_query("
            SELECT id, payment_account_id
            FROM `order`
            WHERE id = $orderId
        ");

        return $order;
    }
    /**
     * @param $code
     * @param $amount
     * @return string
     * @throws Exception
     */
    public function addFeeByCode ($code, $amount)
    {
        global $db;

        bcscale(2);

        $fee = $db->super_query_value("SELECT `fee` FROM `pay_method` WHERE `code` = '$code' LIMIT 1");
        if (!$fee) {
            throw new Exception('Pay method not found');
        }
        $amount = bcmul(bcdiv(bcadd(100, $fee), 100), $amount);

        return $amount;
    }

    /**
     * @param $amount
     * @return array
     */
    public function amountParts ($amount)
    {
        $parts = explode('.', $amount);
        if (count($parts) === 1) {
            $parts[1] = 0;
        } else {
            $parts[1] = bcmul("0.$parts[1]", '100', 0);
        }

        return $parts;
    }
	public function blacklist($product_id, $email){
		global $bd;
		$bd->read("	SELECT bl.id 
					FROM blacklist bl
					JOIN product p ON p.idUser = bl.user_id
					WHERE 	bl.email = '".$email."' 
						AND p.id = ".$product_id."
					LIMIT 1");
		return $bd->rows ? false : true;
	}
	public function check_amount($pay_no, $amount){
		global $bd;
		$request = $bd->read("SELECT totalBuyer FROM `order` WHERE id = ".$pay_no);
		if(mysql_result($request,0,0) === $amount)return TRUE;
		else return FALSE;
	}
	public function product_availability($id){
		global $db;
		
		$request2 = $db->super_query("SELECT typeObject, many FROM product WHERE id = ".$id." LIMIT 1");

		if(!$request2)return FALSE;
		if($request2['typeObject'])$tbForCountTI = "product_text";
		else $tbForCountTI = "product_file";
		
		if(!$request2['many'])$where = "AND `status` = 'sale'";
	  
		$request2 = $db->super_query("SELECT COUNT(*) as count FROM `".$tbForCountTI."` WHERE `idProduct` = ".$id." ".$where." LIMIT 1");
		
		return $request2['count'] > 0;
	}
	
	public function confirm_order($order_id, $customer_purse = FALSE){
		include_once dirname(__file__)."/config.php";
		include_once dirname(__file__)."/mysql.php";
		include_once dirname(__file__)."/main.php";
		include_once dirname(__file__)."/../modules/currency/currclass.php";
		include_once dirname(__file__)."/mail/mail.class.php";
		include_once dirname(__file__)."/db.class.php";
		include_once dirname(__file__)."/transactions.class.php";
		include_once dirname(__file__)."/duplicate.class.php";
		include_once dirname(__file__)."/partner.class.php";
		include_once dirname(__file__)."/logs.class.php";

		global $bd, $db, $CONFIG;
		$user = new user;
		//Данные о товаре и заказе по id заказа	
		
		$request = $bd->read("	SELECT 	p.id, p.idUser, p.many, p.typeObject, o.price, o.curr, o.userIdEmail, p.name,
										(SELECT crypt FROM customer_session WHERE email = o.userIdEmail ORDER BY id DESC LIMIT 1),
										p.promocode_id, o.status, o.promocode_el_id_use, o.totalSeller, o.partner, o.partnerFee,
										u.login, u.email, u.emailInforming, o.ip
								FROM product p
								INNER JOIN `order` o ON p.id = o.productId
								JOIN `user` u ON u.id = o.user_id
								WHERE o.id = ".$order_id."
								LIMIT 1");
		$product_id = mysql_result($request,0,0);
		$user_id = mysql_result($request,0,1);
		$many = mysql_result($request,0,2);
		$typeObject = mysql_result($request,0,3);
		$price = mysql_result($request,0,4);
		$curr = mysql_result($request,0,5);
		$email = mysql_result($request,0,6);
		$product_name = mysql_result($request,0,7);
		$h = mysql_result($request,0,8);
		$promocode_id = mysql_result($request,0,9);
		$order_status = mysql_result($request,0,10);
		$promocode_el_id_use = mysql_result($request,0,11);
		$totalSeller = mysql_result($request,0,12);
		$partnerUserId = mysql_result($request,0,13);
		$partnerFee = mysql_result($request,0,14);
		$sellerLogin = mysql_result($request,0,15);
		$sellerEmail = mysql_result($request,0,16);
		$sellerEmailInforming = (int)mysql_result($request,0,17);
		$ip = mysql_result($request,0,18);

		//Отменяем запрос если заказ уже оплачен
		if($order_status !== 'pay'){
			logs("REPEAT_REQUEST|||pay_no:".$order_id);
			return;
		}
		
		//Таблица объекта
		if($typeObject)$tbWhereObject = "product_text";
		else $tbWhereObject = "product_file";	
		//Ищем объект сначала по резервации, если нету, тогда свободный, если тоже нет - пишем ошибку на заказе, выходим
		$request = $bd->read("	SELECT id FROM ".$tbWhereObject." 
								WHERE 	idProduct = ".$product_id." 
										AND `status` = 'reserved' 
										AND orderid = ".$order_id." 
								LIMIT 1");


		if(!$bd->rows){
		$request = $bd->read("	SELECT id FROM ".$tbWhereObject." 
								WHERE 	idProduct = ".$product_id." 
										AND `status` = 'sale' 
								LIMIT 1");			
		}
		if(!$bd->rows){
			$bd->write("UPDATE `order` SET `status` = 'objErr' WHERE id = ".$order_id." LIMIT 1");
			return FALSE;
		}

		if (!empty($ip)) {
            $db->query("INSERT INTO orderIpLong (ip, successNum) VALUES ('$ip', 1) ON DUPLICATE KEY UPDATE successNum = successNum + 1");
        }

		$object_id = mysql_result($request,0,0);
		//Если товар нашли и он универсальный - пишем продано
		if (!$many) {
		    $bd->write("
                UPDATE `$tbWhereObject`
                SET
                    `status` = 'saled',
                    orderid = $order_id
                WHERE id = '$object_id'
            ");
        }
		//Инкрементируем кол-во продаж товара
		$bd->write("UPDATE product SET sale = sale + 1 WHERE id = ".$product_id." LIMIT 1");
		//Устанавливаем рейтинг продавцу
		//преобразуем стоимость
		$current_convert = new current_convert();

		$price = $current_convert->curr($price, $curr,4,0);
		$this->rating($user_id, $price, 'buy', $order_id);
		
		//Если промокод был использован, инкрементируем ему значение использования
		if($promocode_el_id_use)$bd->write("UPDATE promocode_el SET used = used + 1 WHERE id = ".$promocode_el_id_use." LIMIT 1");		
		//Выдача промо-кода
		$promocode_el_id_issued = 0;
		if($promocode_id){
			$length = 16;
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			$numChars = strlen($chars);
			while(!$promocode_el_id_issued){
				$promocodeset = '';
				for($i=0;$i<$length;$i++)$promocodeset .= substr($chars, rand(1, $numChars) - 1, 1);
				$bd->read("SELECT id FROM promocode_el WHERE code = '".$promocodeset."' AND user_id = ".$user_id." LIMIT 1");
				if($bd->rows)continue;
				$promocode_text = 'Автоматически выдан покупателю по заказу №'.$order_id;
				$promocode_el_id_issued = $bd->write("INSERT INTO promocode_el VALUES (NULL, ".$promocode_id.", '".$promocodeset."', 0, 1, '".$promocode_text."', $user_id)");
			}
		}
		
		//Создание письма
		$mail = new mail();
		$unsubscribe = $mail->unsubscribe($email);
		if(!$unsubscribe['un']['buy']){
			$subject = 'ВАША ПОКУПКА';
			global $siteAddr;
			$message = 	'<p>Уважаемый покупатель!</p>
						<br>
						<p>На торговой площадке <a href="'.$siteAddr.'">'.$CONFIG['site_domen'].'</a> вы приобрели товар: "'.$product_name.'"</p>
						<p>Для получения товара перейдите по ссылке и обратите внимание на графу "оплаченный товар":</p>
						<a href="'.$siteAddr.'customer/'.$order_id.'/'.$h.'/">'.$siteAddr.'customer/'.$order_id.'/'.$h.'/</a>
						<p>Там же вы найдете информацию о продавце и его контактные реквизиты. Воспользуйтесь ими, если у вас возникнут вопросы.</p>
						<p>Мы обращамся к вам с просьбой оставить отзыв о покупке.</p>
						<p>Все товары, которые вы приобрели на нашей площадке, вы можете найти в разделе "Мои покупки"</p>
						<a href="'.$siteAddr.'customer/">'.$siteAddr.'customer/</a>
						<br>
						<p>Спасибо за покупку!</p>
						<p>С уважением, администрация '.$CONFIG['site_domen'].'</p>
						<br>
						<p>Письмо сформировано автоматически сервисом приема платежей '.$CONFIG['site_domen'].'</p>
						<p>Вы можете отписаться от email уведомлений на этой странице:</p>
						<a href="'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/">'.$CONFIG['site_address'].'customer/unsubscribe/'.$unsubscribe['key'].'/</a>';
            $message = $bd->prepare($message, 65535, false);
			$bd->write("INSERT INTO `mail` VALUES (NULL, '".$subject."', '".$message."', '".$email."', 'need', CURRENT_TIMESTAMP)");
		}

		//Меняем статус заказа на отправлен.
		$customer_purse = $customer_purse ? ", customer_purse = '".$customer_purse."'" : "";
	
		$request2 = $bd->read("
			SELECT `reservation`
			FROM `user`
			WHERE id = {$user_id}
		");
		
		$user_reservation = mysql_result($request2,0,0);
		$_val = $user_reservation ? $user_reservation : 'value';
		
		if( $user->priv_type($user_id) && ($user->priv_type($user_id) == 3 || $user->priv_type($user_id) == 4) ){
			$privileges = $bd->read("
				SELECT value
				FROM setting
				WHERE ids = 12
				LIMIT 1
			");
			$priv_settings = unserialize(mysql_result($privileges,0,0));
			$priv_reservation = $priv_settings['com_day_'.$user->priv_type($user_id)];
			if($_val == 'value'){
				$_val = $priv_reservation;
			}
		}

		$request = $bd->read("
			SELECT ADDDATE(CURRENT_TIMESTAMP, INTERVAL ".$_val." HOUR)
			FROM setting
			WHERE ids = 3
			LIMIT 1
		");
		
		$executing = mysql_result($request,0,0);

		$transactions = new transactions();
		$totalSeller = $current_convert->curr($totalSeller, $curr, 4, 0);

		$transactions->create(array(
			'user_id' => $user_id,
			'type' => 1,
			'method' => 'sale',
			'method_id' => $order_id,
			'currency' => 4,
			'amount' => $totalSeller,
			'executing' => $executing
		));

        if (1 === $sellerEmailInforming) {
            $subject = 'ПРОДАН ТОВАР';
            $message = "<p>Оплачен товар \"$product_name\"</p>
						<p>Покупатель: $email</p>
						<p>Сумма: {$totalSeller}р</p>
						<br>
						<p>Счет № {$order_id}</p>
						<p>Подробнее:  <a href=\"{$CONFIG['site_address']}panel/sales/$order_id/\">{$CONFIG['site_address']}panel/sales/$order_id/</a></p>
						<br>
						<p>Письмо сформировано автоматически и не требует ответа.</p>
						<p>Отключить уведомления вы можете в настройках аккаунта.</p>
						<br>
						<p>C Уважением, команда {$CONFIG['site_domen']}</p>
				";
            $message = $db->safesql($message);
            $db->query("
					INSERT INTO `mail`
						(subject, message, `to`, `status`)
					VALUES
						('$subject', '$message', '{$sellerEmail}', 'need')
				");
        }

		if($partnerUserId){
			$partner = new partner();
			$partner->confirmOrder($partnerUserId, $user_id);
			$partnerFee = $current_convert->curr($partnerFee, $curr, 4, 0);
            $partner->notification($partnerUserId, $product_name, $sellerLogin, $partnerFee);
			$this->rating($partnerUserId, $price, 'partner', $order_id);
			$transactions->create(array(
				'user_id' => $partnerUserId,
				'type' => 1,
				'method' => 'partnerFee',
				'method_id' => $order_id,
				'currency' => 4,
				'amount' => $partnerFee
			));
		}
		
		$bd->write("
			UPDATE `order` 
			SET status = 'sended',
				confirmed = 1,
				promocode_el_id_issued = ".$promocode_el_id_issued."
				".$customer_purse." 
			WHERE id = ".$order_id." 
			LIMIT 1
		");
		
	}
	public function rating($user_id, $price, $action, $order_id){
		global $bd;
		global $rating_coeff;
		
		if($action == 'partner'){
			$rating = (ceil($price / $rating_coeff['partner'] * 100) / 100);
			$bd->write("UPDATE partners SET rating = rating + ".$rating." WHERE userId = ".$user_id." LIMIT 1");
			return;
		}
		
		switch($action){
			case "buy":
				$rating_order = (ceil($price / $rating_coeff['buy'] * 100) / 100);
				$rating = $rating_order;
				break;
			case "review_good":
				$rating_order = (ceil($price / $rating_coeff['good'] * 100) / 100);
				$rating = - (ceil($price / $rating_coeff['buy'] * 100) / 100) + $rating_order;
				break;
			case "review_bad":
				$rating_order = - (ceil($price / $rating_coeff['bad'] * 100) / 100);
				$rating = - (ceil($price / $rating_coeff['buy'] * 100) / 100) + $rating_order;
				break;
			case "bad_review_delete":
				$rating_order = (ceil($price / $rating_coeff['buy'] * 100) / 100);
				$rating = (ceil($price / $rating_coeff['bad'] * 100) / 100) + $rating_order;
				break;
			case "good_review_delete":
				$rating_order = (ceil($price / $rating_coeff['buy'] * 100) / 100);
				$rating = - (ceil($price / $rating_coeff['good'] * 100) / 100) + $rating_order;
				break;
			case "bad_to_good":
				$rating_order = (ceil($price / $rating_coeff['good'] * 100) / 100);
				$rating = (ceil($price / $rating_coeff['bad'] * 100) / 100) + $rating_order;
				break;
			case "good_to_bad":
				$rating_order = - (ceil($price / $rating_coeff['bad'] * 100) / 100);
				$rating = $rating_order - (ceil($price / $rating_coeff['good'] * 100) / 100);
				break;
		}
		$bd->write("UPDATE user SET rating = rating + ".$rating." WHERE id = ".$user_id." LIMIT 1");
		$bd->write("UPDATE `order` SET rating = ".$rating_order." WHERE id = ".$order_id." LIMIT 1");
	}
	public function adminpage(){
		$tpl = new templating(file_get_contents(TPL_DIR.'order.tpl'));
			
		return(array('content' => $tpl->content));
	}
}
class templating{//Шаблонизатор
	public $content;
	public $fory_arr = array('model'=>'','model_tags'=>'','content'=>'');
	public function __construct($content){
		$this->content = $content;
	}
	public function ify($val, $variant = false){
		preg_match_all('#{{IF:'.$val.'}}(.*?){{ENDIF:'.$val.'}}#is', $this->content, $a);
		$return['orig'] = $a[0][0];
		preg_match_all('#{{IF:'.$val.'}}(.*?){{ELSE:'.$val.'}}#is', $return['orig'], $b);
		$return['if'] = $b[1][0];
		preg_match_all('#{{ELSE:'.$val.'}}(.*?){{ENDIF:'.$val.'}}#is', $return['orig'], $a);
		$return['else'] = $a[1][0];	
		if($variant == 1)$this->set($return['orig'],$return['if']);
		elseif($variant == 2)$this->set($return['orig'],$return['else']);
		return $return;
	}
	public function set($param, $val){
		$this->content = str_replace($param, $val, $this->content);
	}
	public function fory($val){
		$this->fory_arr['content'] = '';
		preg_match_all('#{{FOR:'.$val.'}}(.*?){{ENDFOR:'.$val.'}}#is', $this->content, $a);
		$this->fory_arr['model'] = $a[1][0];
		$this->fory_arr['model_tags'] = $a[0][0];
	}
	public function fory_cycle($arr){
		$a = $this->fory_arr['model'];
		foreach($arr as $k => $v)$a =  str_replace("{".$k."}", $v, $a);
		$this->fory_arr['content'] .= $a;
	}
	public function switchy($val, $case){
		$return = array(	'orig'=>'',
							'case'=>array(),
							'key'=>array());
		preg_match_all('#{{SWITCH:'.$val.'}}(.*?){{ENDSWITCH:'.$val.'}}#is', $this->content, $a);
		$return['orig'] = $a[0][0];
		preg_match_all('#{{CASE:(.*?)}}(.*?){{ENDCASE:(.*?)}}#is', $return['orig'], $b);
		$return['case'] = $b[2];
		$return['key'] = array_flip($b[1]);		
		$this->content = str_replace($return['orig'], $return['case'][$return['key'][$case]], $this->content);
	}
}
class promocode{
	public static function checkpromocode($product_id, $code){
		global $bd;
		$request = $bd->read("
			SELECT prp.percent, p.price, p.curr, pre.id
			FROM promocode_el pre
			JOIN promocodes pr ON pr.id = pre.promocode_id
			JOIN promocode_products prp ON prp.promocode_id = pre.promocode_id
			JOIN product p ON p.id = prp.product_id
			WHERE 
					BINARY pre.code = '".$code."' 
				AND p.id = ".$product_id."
				AND NOW() < pr.dateend
				AND 0 < (CASE 
							WHEN pr.type = 1 THEN 1 - pre.used
							WHEN pr.type = 2 THEN 1
							WHEN pr.type = 3 THEN pr.maxuse - pre.used
						END)
			LIMIT 1
		");
		
		if(!$bd->rows){
			$request = $bd->read("
				SELECT prp.percent, p.price, p.curr, pre.id
				FROM promocode_el pre
				JOIN promocodes pr ON pr.id = pre.promocode_id
				JOIN promocode_products prp ON prp.promocode_id = pre.promocode_id
				JOIN product p ON p.id = prp.product_id
				WHERE 
						BINARY pre.code = '".$code."' 
					AND NOW() < pr.dateend
					AND 0 < (CASE 
								WHEN pr.type = 1 THEN 1 - pre.used
								WHEN pr.type = 2 THEN 1
								WHEN pr.type = 3 THEN pr.maxuse - pre.used
							END)
				LIMIT 1
			");
			
			return array('discount' => 0, 'another' => $bd->rows);
		}
		
		return array(
			'discount' => mysql_result($request,0,0),
			'preprice' => mysql_result($request,0,1),
			'current' => mysql_result($request,0,2),
			'el_id' => mysql_result($request,0,3)
		);
	}
}
function strPrepare($str, $method){
   switch($method){
   	   case "textarea":
	     $pattern = "/[^a-zA-Zа-яёА-ЯЁ0-9!?,.:\"()-_\s]/iu";
		 break;
       case "filename":
	     $pattern = "/[^a-zA-Zа-яА-Я0-9.]/iu";
		 break;
       //case "name":
	   //  $pattern = "/[а-яёa-z0-9\s]/iu";
		// break;
      // case "price":
	   //  $pattern = "/[0-9]/iu";
		// break;
   }
   return preg_replace($pattern,"",$str);
   	
}
function norlzbr($str){
	return str_replace('&lt;br /&gt;', '<br>', $str);
}
function strBaseOut($str){
	return str_replace(array("{.-plus;-.}", "{.-amp;-.}", "&lt;br /&gt;", "{.-quote;-.}", "{.-colon;-.}", "{.-onequote;-.}", "{.-tab;-.}"),array("+", "&", "<br />", "\"", ":", "'", "	"),$str);
	//return stripslashes(str_replace(array("{.-plus;-.}", "{.-amp;-.}", "&lt;br /&gt;", "{.-quote;-.}", "{.-colon;-.}", "{.-onequote;-.}", "{.-tab;-.}"),array("+", "&", "<br />", "\"", ":", "'", "	"),$str));
}

function prepareEmail($str, $len){
	$return = htmlspecialchars($str);
    if(mb_strlen($str,'utf-8') > $len){
       $return = mb_substr($return,0,$len,'utf-8');
    } 
	return $return;
}
function subGroupGett($id){
	global $bd;
	$request = $bd->read("SELECT `subgroup` FROM `productgroup` WHERE `id` = '".$id."'");
	if($bd->rows && mysql_result($request,0,0) > 0)$subgroup = "= ".mysql_result($request,0,0);
	else $subgroup = "IS NULL";
	$request = $bd->read("SELECT * FROM `productgroup` WHERE `subgroup` ".$subgroup." ORDER BY `name`");
	$rows = mysql_num_rows($request);
	$option = "<option value='0'>-Выберите подгруппу-</option>";
	for($i=0;$i<$rows;$i++){
		if(mysql_result($request,$i,0) == $id)$selected = "selected=\"selected\"";
		else $selected = "";  
		$name = strBaseOut(mysql_result($request,$i,2));	
		$option .= "<option ".$selected." value='".mysql_result($request,$i,0)."'>".$name."</option>";
	}
	return $option;
}/*
function subGroupGetDeep($id){//Считаем на какой глубине позиция. 1 - верх
  $numSub = 0;
  $groupRecurs = $id;
  do{
  	$numSub++;
  	$request = readMysql("SELECT `subgroup` FROM `productgroup` WHERE `id` = '".$groupRecurs."'");
	if(mysql_num_rows($request))$groupRecurs = mysql_result($request,0,0);
  }while($groupRecurs);
  return $numSub;
}

function subGroupGet($id){
  //Проверяем есть ли подгруппы
  $request = readMysql("SELECT * FROM `productgroup` WHERE `subgroup` = '".$id."' ORDER BY `name`");
  $rows = mysql_num_rows($request);
  if($rows != 0){
  	 $option = "<option value='0'>-Выберите подгруппу-</option>";
	 if(mysql_result($request,$i,0) == $id)$selected = "selected=\"selected\"";
	 else $selected = "";
     for($i=0;$i<$rows;$i++){
	 	$name = strBaseOut(mysql_result($request,$i,2));
  	    $option .= "<option ".$selected." value='".mysql_result($request,$i,0)."'>".$name."</option>";
     }
  }
  else $option = 0;
   return '{"numSub":"'.subGroupGetDeep($id).'", "option":"'.$option.'"}';
}*/
function subGroupGetDeep_new($id){//Считаем на какой глубине позиция. 1 - верх
  global $bd;
  $numSub = 0;
  $groupRecurs = $id;
  while($groupRecurs){
  	$numSub++;
  	$request = $bd->read("SELECT `subgroup` FROM `productgroup` WHERE `id` = '".$groupRecurs."'");
	//if($bd->rows)$groupRecurs = mysql_result($request,0,0);
	$groupRecurs = $bd->rows ? mysql_result($request,0,0) : 0;
  }
  return $numSub;
}
function subGroupGet_new($id){
	//Проверяем есть ли подгруппы
	global $bd;
	$request = $bd->read("SELECT * FROM `productgroup` WHERE `subgroup` = ".$id." ORDER BY `name`");
	if($bd->rows != 0){
		$option = '<option value="0">-Выберите подгруппу-</option>';
		if(mysql_result($request,$i,0) == $id)$selected = 'selected="selected"';
		else $selected = "";
		for($i=0;$i<$bd->rows;$i++){
			$name = mysql_result($request,$i,2);
			$option .= "<option ".$selected." value='".mysql_result($request,$i,0)."'>".$name."</option>";
		}
	}
	else $option = 0;
	return json_encode(array('numSub' => subGroupGetDeep_new($id), 'option' => $option));
}
function sitemapInfo(){
	global $bd;
	
	$request = $bd->read("SELECT `value`, `date` FROM `setting` WHERE `ids` = 1 LIMIT 1");
	$time = mysql_result($request,0,1);
	
	if(mysql_result($request,0,0) == 'limit') return "SITEMAP: ERROR Достигнут лимит";
    	
	if($time < (time() - (60 * 60 * 72)))return "SITEMAP: ERROR Не обновлено";
	
	return "SITEMAP: OK ".date('m-d H:i:s', $time)."";
}
function discountCheck($email, $productId){
	global $bd;
	$personal_percent = 0;
	$personal_money = 0;
	
	$request = $bd->read("
		SELECT d.percent, d.money 
		FROM discount_personal d
		JOIN product p ON p.idUser = d.user_id
			AND p.id = ".$productId."
		WHERE d.email = '".$email."' LIMIT 1
	");
	if($bd->rows){
		$personal_percent = (int)mysql_result($request,0,0);
		$personal_money = (int)mysql_result($request,0,1);
	}
	
	$request = $bd->read("	SELECT percent FROM discount WHERE userId = (SELECT idUser FROM product WHERE id = '".$productId."' LIMIT 1) AND money < 
								(  
									SELECT SUM(price * CASE
													WHEN curr = 1 THEN (SELECT usd FROM currency ORDER BY id DESC LIMIT 1)
													WHEN curr = 2 THEN (SELECT uah FROM currency ORDER BY id DESC LIMIT 1)
													WHEN curr = 3 THEN (SELECT eur FROM currency ORDER BY id DESC LIMIT 1)
													ELSE 1 END) + ".$personal_money."
									FROM `order` 
									WHERE userIdEmail = '".$email."'  
									AND (status = 'review' OR status = 'sended')
									AND productId IN (SELECT id FROM product WHERE idUser = (
																	  SELECT idUser FROM product WHERE id = '".$productId."' LIMIT 1))
								)
							ORDER BY money DESC LIMIT 1");
	$global_percent = $bd->rows ? mysql_result($request,0,0) : 0;

	$discount = $global_percent > $personal_percent ? $global_percent : $personal_percent;
	
	return $discount;
}
function curr_ident($v){
	  switch($v){
   	    case 1:
	       $curr = " $";
		   break;
        case 2:
	       $curr = " грн.";
		   break;
        case 3:
	       $curr = " €";
		   break;
        case 4:
	       $curr = " руб.";
		   break;
     }
	 return $curr;
}
function close($m=''){
	global $bd;
	unset($bd);
	exit($m);
}
function logs($txt, $close = 0){
	global $bd;
	$date = date('Y-m-d H:i:s');
	$bd->write("INSERT INTO log VALUES (NULL, '".REMOTE_ADDR."', '".$date."', 'pay', '".$txt."')");
	if($close)close($txt);
}
function writeTxt($txt){
	$fp = fopen("counter.txt", "a"); // Открываем файл в режиме записи
    $mytext = $txt."\r\n"; // Исходная строка
    $test = fwrite($fp, $mytext); // Запись в файл
    fclose($fp); //Закрытие файла
}
function user_browser($agent = false) {
	$agent = $agent ? $agent : $_SERVER['HTTP_USER_AGENT'];
	preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info); // регулярное выражение, которое позволяет отпределить 90% браузеров
	list(,$browser,$version) = $browser_info; // получаем данные из массива в переменную
	if (preg_match("/Opera ([0-9.]+)/i", $agent, $opera)) return 'Opera '.$opera[1]; // определение _очень_старых_ версий Оперы (до 8.50), при желании можно убрать
	if ($browser == 'MSIE') { // если браузер определён как IE
			preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie); // проверяем, не разработка ли это на основе IE
			if ($ie) return $ie[1].' based on IE '.$version; // если да, то возвращаем сообщение об этом
			return 'IE '.$version; // иначе просто возвращаем IE и номер версии
	}
	if ($browser == 'Firefox') { // если браузер определён как Firefox
			preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff); // проверяем, не разработка ли это на основе Firefox
			if ($ff) return $ff[1].' '.$ff[2]; // если да, то выводим номер и версию
	}
	if ($browser == 'Opera' && $version == '9.80') return 'Opera '.substr($agent,-5); // если браузер определён как Opera 9.80, берём версию Оперы из конца строки
	if ($browser == 'Version') return 'Safari '.$version; // определяем Сафари
	if (!$browser && strpos($agent, 'Gecko')) return 'Browser based on Gecko'; // для неопознанных браузеров проверяем, если они на движке Gecko, и возращаем сообщение об этом
	return $browser.' '.$version; // для всех остальных возвращаем браузер и версию
}
function user_os($agent = false){
	$agent = $agent ? $agent : $_SERVER['HTTP_USER_AGENT'];  
	$os = array (  
		'Windows' => 'Win',  
		'Open BSD'=>'OpenBSD',  
		'Sun OS'=>'SunOS',  
		'Linux'=>'(Linux)|(X11)',  
		'Mac OS'=>'(Mac_PowerPC)|(Macintosh)',  
		'QNX'=>'QNX',  
		'BeOS'=>'BeOS',  
		'OS/2'=>'OS/2'
	);  

	foreach($os as $key=>$value){  
		if (preg_match('#'.$value.'#i', $agent))  
			return $key;  
	}

	return 'Unknown';  
}
function curlPost($body = [], $url){
	$body_query=http_build_query($body);
    $cpt = curl_init();
    curl_setopt($cpt, CURLOPT_URL, $url);
    curl_setopt($cpt, CURLOPT_POST, true);
    curl_setopt($cpt, CURLOPT_POSTFIELDS, $body_query);
    curl_setopt($cpt, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($cpt, CURLOPT_FAILONERROR,true);
    curl_setopt($cpt, CURLOPT_TIMEOUT, 8); 

    $result = curl_exec($cpt);

    $info = curl_error($cpt);
    $errorCode = curl_errno($cpt);
    $statusCode = curl_getinfo($cpt, CURLINFO_HTTP_CODE);

    curl_close($cpt);

    if($statusCode !== 200){
    	throw new SkrillProcessErrorException(json_encode(['errorCode' => $errorCode, 'info' => $info, 'statusCode' => $statusCode])); 
    }

    return $result;
}
