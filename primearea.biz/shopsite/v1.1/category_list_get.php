<?php
	header('Access-Control-Allow-Origin: *');
	
    include "../../func/config.php";
    include "../../func/mysql.php";
    include "../../func/main.php";
	
	$bd = new mysql();
	
    $user_id = $bd->prepare((int)$_GET['id'], 8);
	
	$request = $bd->read("SELECT id, name
	                      FROM shopsite_category 
						  WHERE user_id = ".$user_id."
						  ORDER BY name");
						  
	$return['id'] = array();
	$return['name'] = array();
	
	$return['id'][] = 0;
	$return['name'][] = 'Все товары';	
	
	$return['rows'] = $bd->rows+1;
	if(!$bd->rows)close(json_encode($return));
	
	

	
	for($i=0;$i<$bd->rows;$i++){
		$return['id'][] = mysql_result($request,$i,0);
		$return['name'][] = strBaseOut(mysql_result($request,$i,1));
	}
	
    close(json_encode($return));
?>
