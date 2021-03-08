<?php
	header('Access-Control-Allow-Origin: *');
	
    include "../../func/config.php";
    include "../../func/mysql.php";
    include "../../func/main.php";
    include "../../modules/currency/currclass.php";
	include "../../func/db.class.php";
	
	$db = new db();
	$currClass = new currConvFPayC();
	$bd = new mysql();
	
    $id = $bd->prepare((int)$_GET['id'], 8);
	$category = $bd->prepare((int)$_GET['category'], 8);
	
	$category = 'AND p.shopsite ' . (!$category ? '!= -1' : '= '.$category);
	
	$request = $bd->read("
		SELECT
			p.id,p.name,p.price,p.curr,
			pic.name,pic.path
		FROM product p
		LEFT JOIN picture pic ON pic.id = p.picture
		WHERE
			p.idUser = ".$id." AND
			p.showing = 1
			".$category."
		ORDER BY p.name
	");
	$rows = mysql_num_rows($request);
	$return['rows'] = $rows;
	if($rows == 0)close(json_encode($return));
	
	for($i=0;$i<$rows;$i++){
		$price = mysql_result($request,$i,2);
		$curr = mysql_result($request,$i,3);
		$imgpath = mysql_result($request,$i,5);
		$return['img'][$i] = $imgpath && !mysql_result($request,$i,4) ? $CONFIG['site_address'].'/picture/'.$imgpath : false;
		$return['id'][$i] = mysql_result($request,$i,0);
		$return['name'][$i] = strBaseOut(mysql_result($request,$i,1));
		//$return['price'][$i] = mysql_result($request,$i,2);
		//$return['curr'][$i] = curr_ident(mysql_result($request,$i,3));
		$return['price'][$i] = $currClass->curr($price,$curr,(int)$_GET['currency']);
	}
	
    close(json_encode($return));
?>
