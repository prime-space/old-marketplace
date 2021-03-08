<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$product_id = (int)$data['product_id'];

	$request = $bd->read("SELECT idUser, typeObject, many FROM product WHERE id = '".$product_id."' LIMIT 1");
	$product_user_id = mysql_result($request,0,0);
	if($user->id !== $product_user_id)close('{"status": "error", "content": "Ошибка доступа"}');

	$typeObject = mysql_result($request,0,1);
	$many = mysql_result($request,0,2);
	
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop.prosuctProced/objects.tpl"));
	
	if($typeObject == 1){
		$request = $bd->read("
			SELECT * 
			FROM product_text
			WHERE 	idProduct = '".$product_id."' 
				AND (
							status = 'sale' 
						OR 	status = 'reserved'
					)
			ORDER BY status
			LIMIT 500
		");	
		$tpl->switchy('OBJ', 'TEXT');
	}else{
		$request = $bd->read("
			SELECT id, name, status, timeReserv
			FROM product_file
			WHERE 	idProduct = '".$product_id."' 
				AND (
							status = 'sale' 
						OR 	status = 'reserved'
					)
			ORDER BY status
			LIMIT 500
		");		
		$tpl->switchy('OBJ', 'FILE');
		$tpl->fory('FILE_RESERV');
		for($i=0;$i<$bd->rows;$i++){
			$name = mysql_result($request,$i,1);
			$status = mysql_result($request,$i,2);
			$time_reserv = mysql_result($request,$i,3);
			if($status !== 'reserved')continue;
			
			$date = date('d-m-Y H:i:s', ($time_reserv + 40 * 60))
			$tpl->fory_cycle(array(	'name' => $name,
									'date' => $date));			
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		$tpl->fory('FILE');
		for($i=0;$i<$bd->rows;$i++){
			$product_file_id = mysql_result($request,$i,0);
			$name = mysql_result($request,$i,1);
			$status = mysql_result($request,$i,2);
			if($status !== 'sale')continue;
			
			$tpl->fory_cycle(array(	'name' => $name,
									'product_file_id' => $product_file_id));			
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		
	}

	close(json_encode(array(
		'status' => 'ok', 
		'content' => $tpl->content
	)));	

	
	/*if($typeObject == 0 && $bd->rows != 0){
		$filename = array();
		$filedir = array();
		$status = array();
		for($i=0;$i<$bd->rows;$i++){
			$filename[] = mysql_result($request,$i,3);
			$filedir[] = mysql_result($request,$i,4);
			$status[] = mysql_result($request,$i,5);
		}
	}
  
	close(json_encode(array(
		'status' => 'ok', 
		'typeObject' => $typeObject,
		'many' => $many,
		'count' => $bd->rows,
		'filename' => $filename,
		'filedir' => $filedir,
		'status' => $status,
	)));*/
?>