<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
  	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "content": "Ошибка доступа"}');
  
	if($user->status == 'blocked')close('{"status": "error", "content": "Ошибка доступа"}');
  
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productAdd/productAdd.tpl"));
  
	$request = $bd->read("SELECT `id`, `name` FROM `productgroup` WHERE `subgroup` IS NULL ORDER BY `name`");
	$groupe = "";
	for($i=0;$i<$bd->rows;$i++){
		$name = strBaseOut(mysql_result($request,$i,1));
		$groupe .= "<option value=\"".mysql_result($request,$i,0)."\">".$name."</option>";
	}

	$request = $bd->read("SELECT id, name FROM promocodes WHERE user_id = ".$user->id." LIMIT 100");
	$nopromocodes = $tpl->ify('NOPROMOCODES');
	if(!$bd->rows)$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['if'], $tpl->content);
	else{
		$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['else'], $tpl->content);
		$tpl->fory('PROMOCODES');
		for($i=0;$i<$bd->rows;$i++){
			$tpl->fory_cycle(array(	
				'promocode_id' => mysql_result($request,$i,0),
				'name' => mysql_result($request,$i,1)
			));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);				
	}
	
	
	$tpl->content = str_replace("{group}", $groupe, $tpl->content);
  
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
?>