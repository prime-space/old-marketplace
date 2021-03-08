<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');

	$user_id =  (int)$_GET['h'];
	$key =  $db->safesql($_GET['i'], true);
	
	$request = $db->query("SELECT id
        FROM user  
		WHERE id = ".$user_id." AND random_key = '".$key."' LIMIT 1");

	if(!$request->num_rows){
		$page = '<div class="pa_middle_c_l_b_content"><div class="pa_middle_c_l_b_head_5" style="color:#000000">Ошибка!</div></div>';	
	}else{
		$maincontent = new templating(file_get_contents(TPL_DIR.'reset.tpl'));
		
		$maincontent->set('{user_id}', $user_id);
		$maincontent->set('{key}', $key);
		$page = $maincontent->content;
	}
	
?>