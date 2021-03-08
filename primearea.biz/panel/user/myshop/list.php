<?php

	($_COOKIE['curr'] == '') ? $currList = 4 : $currList = $_COOKIE['curr'];

	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	include "../../../modules/currency/currclass.php";
  
	$currClass = new currConvFPayC();
	$bd = new mysql();
  
	$data = json_decode($_POST['data'], true);
  
	$elements_on_page = $bd->prepare((int)$data['elements_on_page'], 8);
	$elements_on_page = $elements_on_page ? $elements_on_page : $elementLoadInPage;
  
	$where = "WHERE 1";
	$user = new user();
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."//panel/user/myshop/list.tpl"));
	$where .= " AND p.idUser = '".$user->id."'";
  
	$current_list = (int)$data['current_list'];
	if($current_list == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $current_list * $elements_on_page;  

    $cat = (int)$data['cat'];
	if($cat) $where .= " AND p.group = ".$cat;
    $sortArr = [
        'p.date DESC',
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
        'p.name',
        "(SUM(CASE WHEN prt.status = 'sale' THEN 1 ELSE 0 END) + SUM(CASE WHEN prf.status = 'sale' THEN 1 ELSE 0 END)) ASC"
    ];

	$sort = (int)$data['sort'];
	if($sort < 0 || $sort > 5)close();
	$sort = $sortArr[$sort];
	
	$request = $bd->read("
		SELECT SQL_CALC_FOUND_ROWS
			p.id,
			p.idUser,
			p.name,
			p.sale,
			p.price,
			p.typeObject,
			p.many,
			p.shopsite,
			p.curr,
			SUM(CASE WHEN prt.status = 'sale' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prf.status = 'sale' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prt.status = 'reserved' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prf.status = 'reserved' THEN 1 ELSE 0 END),
			p.moderation,
			p.moderation_message
		FROM product p
		LEFT JOIN product_text prt ON prt.idProduct = p.id
		LEFT JOIN product_file prf ON prf.idProduct = p.id
		".$where." AND p.block = 'ok'
		GROUP BY p.id"
	);
	$noProduct = 0;
	for($i=0;$i<$bd->rows;$i++){
		$pcs_t = mysql_result($request,$i,9);
		$pcs_f = mysql_result($request,$i,10);

		$count0 = $pcs_f + $pcs_t;
		if($count0 == '0'){
			$noProduct++;
		}
	}	

	$request = $bd->read("
		SELECT SQL_CALC_FOUND_ROWS
			p.id,
			p.idUser,
			p.name,
			p.sale,
			p.price,
			p.typeObject,
			p.many,
			p.shopsite,
			p.curr,
			SUM(CASE WHEN prt.status = 'sale' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prf.status = 'sale' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prt.status = 'reserved' THEN 1 ELSE 0 END),
			SUM(CASE WHEN prf.status = 'reserved' THEN 1 ELSE 0 END),
			p.moderation,
			p.moderation_message
		FROM product p
		LEFT JOIN product_text prt ON prt.idProduct = p.id
		LEFT JOIN product_file prf ON prf.idProduct = p.id
		".$where." AND p.block = 'ok'
		GROUP BY p.id
		ORDER BY ".$sort."
		LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
	);
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
				$pcs_t = mysql_result($request,$i,9);
				$pcs_f = mysql_result($request,$i,10);
				$reserved_t = mysql_result($request,$i,11);
				$reserved_f = mysql_result($request,$i,12);
				$curr = mysql_result($request,$i,8);
				$moderation = mysql_result($request,$i,13);

				$type = 'ok';
				if($moderation == 'refuse'){
					$moderation_message = "<div id=\'moderationRefuseDiv\'><div id=\'modtitle\'><b>Вы попытались добавить товар на Торговую Площадку PRIMEAREA.BIZ и получили отказ в публикации товара.</b></div><div id=\'modreasn\'>Отказ в публикации Вашего товара может произойти по следующим часто возникающим причинам:</div><div id=\'reasonrefuse\'><b>• При добавлении Вы указали не верную категорию товара.</b><br/><font color=\'red\'> - Пример:</font> Вы продаете «Аккаунт», а товар добавили в раздел «Ключи активации» </div><div class=\'reasonrefuse\'><b>• Вы добавили товар который не соответствует ценовой политике сайта, что влечет демпинг рынка Торговой Площадки.</b><br/><font color=\'red\'> - Пример:</font> Вы хотите выставить ключ активации для игры «CS:GO» за 100 рублей. </div><div id=\'reasonrefuse\'><b>•	Вы указываете некорректный объект продажи. В нашем сервисе категорически запрещается размещать в качестве товара что-либо, кроме самого товара.</b><br/><font color=\'red\'> - Пример:</font> В графе «Объект продажи» Вы указываете посторонние ссылки, где покупатель сможет получить свой товар. </div><div id=\'reasonrefuse\'><b>• Товар несоответствующий регламенту Торговой Площадки PRIMEAREA.BIZ.</b></div><div id=\'reasonrefuse\'><b>• В отдельных случаях Модератор вправе отказать продавцу в публикации товара на свое усмотрение.</b> </div> <div id=\'modtitle\'>Если Вы считаете, что Вам необоснованно отказали в публикации товара, либо Вам не ясна причина, обращайтесь в <b>поддержку</b> для выяснения конкретной причины - <b>moderate@primearea.biz</b></div>С Уважением, команда PRIMEAREA.BIZ.</div>";
					$_message = 'Причина отказа';
					$type = 'mod';
					$_desc = 'Отказ';
					$_color = 'black';
				}elseif($moderation == 'ok' || $moderation == NULL){
					$_desc = 'Одобрен';
					$moderation_message = 'Допущено к продаже';
					$_message = 'Статус';
					$_color = 'green';
				}elseif($moderation == 'check'){
					$_desc = 'Ожидание';
					$moderation_message = 'На проверке';
					$_message = 'Статус';
					$_color = 'aqua';
				}

				$pcs = $pcs_t + $pcs_f;
				if(!$many || !$pcs){
					$reserved = $reserved_t + $reserved_f;
					if($reserved)$pcs = $pcs.' / <span style="color:red;">'.$reserved.'</span>';
				}else $pcs = 'x';
				
				//$show_addmyshop = $shopsite == -1 ? 'visible_' . $shopsite : 'hidden_' . $shopsite;
				
				$id = mysql_result($request,$i,0);
				if ( $shopsite == -1 ) {
					$disabled = '';
					$show_text = 'Добавить';
					$show_text_title = 'Добавить в Мой магазин';
					$onclick = ' onclick="panel.user.shopsite.product.move(' . $id .  ',\'add\', this); return false;"';

				} else {
					$disabled = ' disabled';
					$show_text = 'В магазине';
					$show_text_title = 'Товар в магазине';
					$onclick = '';

				}
			
				$tpl->fory_cycle(array(	'id' => mysql_result($request,$i,0),
										'name' => strBaseOut(mysql_result($request,$i,2)),
										'pcs' => $pcs,
										'sold' => mysql_result($request,$i,3),
										'price' => $currClass->curr($price,$curr,$currList),
										'disabled' => $disabled,
										'text' => $show_text,
										'text_title' => $show_text_title,
										'onclick' => $onclick,
										'refuse_message' => $moderation_message,
										'moderation' => $moderation,
										'_message' => $_message,
										'_color' => $_color,
										'desc' => $_desc,
										'type' =>$type,
										'color' => $i&1==1?'a':'b'));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);	
	}
	
 	if($data['method'] != 'add' && $product_count){
		$pagination = pagination::getPanel($product_count, $elements_on_page, $current_list, 'panel.user.myshop.list.get');
		$tpl->content .= '<div id="list_add_panel.user.myshop.list.get"></div>';
		$tpl->content .= $pagination;
	}

	

	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
  
?>
