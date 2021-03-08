<?php

	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/verification.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/shopsite/category_list.tpl"));
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	$request = $bd->read("SELECT id, name FROM shopsite_category WHERE user_id = ".$user->id." LIMIT 100");
	
	$nocategory = $tpl->ify('NOCATEGORY');
	if($bd->rows)$tpl->content = str_replace($nocategory['orig'], $nocategory['if'], $tpl->content);
	else $tpl->content = str_replace($nocategory['orig'], $nocategory['else'], $tpl->content);
	
	$tpl->fory('CATEGORIES');
	for($i=0; $i<$bd->rows; $i++){
		$id =  mysql_result($request,$i,0);
		$name =  strBaseOut(mysql_result($request,$i,1));
		$tpl->fory_cycle(array(	'id' => $id,
								'name' => $name,
								'i' => ($i+1))); 
	}
	$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
  
?>