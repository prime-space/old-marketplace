<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
 	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
   
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$product_id = (int)$data['product_id'];
	
	$request = $bd->read("SELECT idUser, typeObject, many, name FROM product WHERE id = '".$product_id."' LIMIT 1");
	$product_user_id = mysql_result($request,0,0);
	if($user->id !== $product_user_id)close('{"status": "error", "content": "Ошибка доступа"}');
  
	$obj_type = mysql_result($request,0,1);
	$obj_many = mysql_result($request,0,2);
	$product_name = strBaseOut(mysql_result($request,0,3));
	
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productProced/productRedObjSale.tpl"));
	
	$tpl->content = str_replace('{product_name}', $product_name, $tpl->content);
	
	$add_button = $tpl->ify('ADD_BUTTON');
	
	if($data['s'] == 'add' && $obj_type == 1 && $obj_many == 0)$data['method'] = 'choose';
	
	if($data['method'] == 'choose'){
		$tpl->switchy('OBJ', 'ADDCHOOSE');
		close(json_encode(array(
			'status' => 'ok', 
			'content' => $tpl->content
		)));
	}
	if($data['method'] == 'simple'){
		$tpl->switchy('OBJ', 'ADDSIMPLE');
		close(json_encode(array(
			'status' => 'ok', 
			'content' => $tpl->content
		)));
	}
	if($data['method'] == 'multi'){
		$tpl->switchy('OBJ', 'ADDMULTI');
		close(json_encode(array(
			'status' => 'ok', 
			'content' => $tpl->content
		)));
	}
	
	if($obj_type == 1){
		$request = $bd->read("
			SELECT id, text, status, timeReserv 
			FROM product_text
			WHERE 	idProduct = ".$product_id."
				AND (
							status = 'sale' 
						OR 	status = 'reserved'
					)
			ORDER BY status
			LIMIT 500
		");
		if($obj_many && !$bd->rows){
			$tpl->switchy('OBJ', 'TEXT_UNI_OUT');
		}else{
			$tpl->switchy('OBJ', 'TEXT');
			$tpl->fory('TEXT_RESERV');
			for($i=0;$i<$bd->rows;$i++){
				$text = strBaseOut(mysql_result($request,$i,1));
				$status = mysql_result($request,$i,2);
				$time_reserv = mysql_result($request,$i,3);
				if($status !== 'reserved')continue;
				
				$date = date('d-m-Y H:i:s', ($time_reserv + 40 * 60));
				$tpl->fory_cycle(array(	'text' => $text,
										'date' => $date));
				$file_reserv = true; 
			}
			$notext_reserv = $tpl->ify('NOTEXT_RESERV');
			if($file_reserv){
				$tpl->content = str_replace($notext_reserv['orig'], $notext_reserv['if'], $tpl->content);
				$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
				$tpl->content = str_replace('{time_reserv}', $timeReserv, $tpl->content);
			}else $tpl->content = str_replace($notext_reserv['orig'], $notext_reserv['else'], $tpl->content);

			$tpl->fory('TEXT');
			$obj_count = 0;
			for($i=0;$i<$bd->rows;$i++){
				$product_obj_text_id = mysql_result($request,$i,0);
				$status = mysql_result($request,$i,2);
				if($status !== 'sale')continue;
				$text = str_replace("<br />", "", strBaseOut(mysql_result($request,$i,1)));
				$text = shop::url_replace_out($text);
				$obj_count++;
				$tpl->fory_cycle(array(	'text' => $text,
										'product_obj_text_id' => $product_obj_text_id));			
			}
			$notext = $tpl->ify('NOTEXT');
			if($obj_count){
				$tpl->content = str_replace($notext['orig'], $notext['if'], $tpl->content);
				$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
			}else $tpl->content = str_replace($notext['orig'], $notext['else'], $tpl->content);
					
			
			$delall_button = $tpl->ify('DELALL_BUTTON_TEXT');
			if(!$obj_many){
				$tpl->content = str_replace($delall_button['orig'], $delall_button['if'], $tpl->content);
				$tpl->content = str_replace($add_button['orig'], $add_button['if'], $tpl->content);
			}else{
				$tpl->content = str_replace($delall_button['orig'], $delall_button['else'], $tpl->content);	
				$tpl->content = str_replace($add_button['orig'], $add_button['else'], $tpl->content);
			}
		}
	}
	if($obj_type == 0){
		$tpl->content = str_replace($add_button['orig'], $add_button['else'], $tpl->content);
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
			
			$date = date('d-m-Y H:i:s', ($time_reserv + 40 * 60));
			$tpl->fory_cycle(array(	'name' => $name,
									'date' => $date));
			$file_reserv = true; 
		}
		$nofile_reserv = $tpl->ify('NOFILE_RESERV');
		if($file_reserv){
			$tpl->content = str_replace($nofile_reserv['orig'], $nofile_reserv['if'], $tpl->content);
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
			$tpl->content = str_replace('{time_reserv}', $timeReserv, $tpl->content);
		}else $tpl->content = str_replace($nofile_reserv['orig'], $nofile_reserv['else'], $tpl->content);
		
		$tpl->fory('FILE');
		$obj_count = 0;
		for($i=0;$i<$bd->rows;$i++){
			$product_obj_file_id = mysql_result($request,$i,0);
			$name = mysql_result($request,$i,1);
			$status = mysql_result($request,$i,2);
			if($status !== 'sale')continue;
			
			$obj_count++;
			$tpl->fory_cycle(array(	'name' => $name,
									'product_obj_file_id' => $product_obj_file_id));			
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		
		$delall_button = $tpl->ify('DELALL_BUTTON_FILE');
		if(!$obj_many)$tpl->content = str_replace($delall_button['orig'], $delall_button['if'], $tpl->content);
		else $tpl->content = str_replace($delall_button['orig'], $delall_button['else'], $tpl->content);	
	}


	
	close(json_encode(array(
		'status' => 'ok', 
		'content' => $tpl->content,
		'obj_type' => $obj_type,
		'obj_many' => $obj_many,
		'obj_count' => $obj_count
	)));	
?>