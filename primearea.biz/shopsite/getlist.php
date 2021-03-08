<?php
    header("Content-type: text/html; charset=utf-8");
    include "../func/mysql.php";
    include "../func/main.php";
    include "../modules/currency/currclass.php";
	
	$currClass = new currConvFPayC();
	
    $callback = $_GET["primearea_callback"];
    $id = realMysql($_GET["id"], 8);
	
	$request = readMysql("SELECT id, name, price, curr 
	                      FROM product 
						  WHERE block = 'ok' 
						    AND shopsite != -1 
							AND idUser = ".$id."
						  ORDER BY name");
	$rows = mysql_num_rows($request);
	$return['rows'] = $rows;
	if($rows == 0)exit($callback.'('.json_encode($return).')');
	
	for($i=0;$i<$rows;$i++){
		$price = mysql_result($request,$i,2);
		$curr = mysql_result($request,$i,3);
		$return['id'][$i] = mysql_result($request,$i,0);
		$return['name'][$i] = strBaseOut(mysql_result($request,$i,1));
		//$return['price'][$i] = mysql_result($request,$i,2);
		//$return['curr'][$i] = curr_ident(mysql_result($request,$i,3));
		$return['price'][$i] = $currClass->curr($price,$curr,(int)$_GET['curr']);
	}
	
    exit($callback.'('.json_encode($return).')');
?>
