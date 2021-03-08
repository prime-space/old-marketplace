<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if($user->role){
		$content = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/myshop/myshop.tpl"));
		
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
			WHERE p.idUser = '".$user->id."' AND p.block = 'ok'
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

		$content->set('{$noProduct}', $noProduct);

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
			WHERE p.idUser = '".$user->id."' AND p.block = 'ok'
			GROUP BY p.id
			LIMIT 0,1"
		);
		$product_count = $bd->total_rows;

		$content->set('{$product_count}', $product_count);

		$page = $content->content;
	}else $page = 'Необходимо авторизоваться';  
?>
