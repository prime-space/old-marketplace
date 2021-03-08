<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	$promocode_id = (int)$data['promocode_id'];
	$request = $bd->read("SELECT type, maxuse FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
	if(!$bd->rows)close(json_encode(array('status' => 'error', 'content' => 'Не найдено или нет доступа')));
	$type = mysql_result($request,0,0);
	$maxuse = mysql_result($request,0,1);
	
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/promocodes/codes_list.tpl"));
	
	$request = $bd->read("
		SELECT id, code, used, comment
		FROM promocode_el
		WHERE promocode_id = ".$promocode_id."
		ORDER BY id DESC
		LIMIT 5000
	");	
	
	$tpl->fory('LIST');
	for($i=0;$i<$bd->rows;$i++){
		if($type == 1)$using = mysql_result($request,$i,2) ? 'Использован' : 'Не использован';
		elseif($type == 2)$using = mysql_result($request,$i,2).'';
		elseif($type == 3)$using = mysql_result($request,$i,2).'/'.$maxuse;
		$tpl->fory_cycle(array(	
			'code' => mysql_result($request,$i,1),
			'status' => $using,
			'comment' => mysql_result($request,$i,3),
			'promocodes_code_id' => mysql_result($request,$i,0)
		));
	}
	$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);				
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
?>