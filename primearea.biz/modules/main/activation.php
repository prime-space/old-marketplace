<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	
	$maincontent = new templating(file_get_contents(TPL_DIR.'activation.tpl'));
	$content = $maincontent->ify('ACTIVATED');
	
	$activation_key =  $db->safesql($_GET['h']);
	$user_id =  (int)$_GET['i'];

	$request = $db->query("SELECT id
        FROM user  
		WHERE id = ".$user_id." AND random_key = '".$activation_key."' AND status = 'conf' LIMIT 1");

	if(!$request->num_rows){

		$request = $db->query("SELECT id
	        FROM user  
			WHERE id = ".$user_id." AND status = 'ok' AND random_key IS NULL LIMIT 1");
		
		if($request->num_rows && $activation_key){
			$db->query("UPDATE user SET random_key = '".$activation_key."' WHERE id = ".$user_id." LIMIT 1");
			$page = str_replace($content['orig'], $content['if'], $maincontent->content);
		}else{
			$page = str_replace($content['orig'], $content['else'], $maincontent->content);
		}
	
	}else{
		$page = str_replace($content['orig'], $content['if'], $maincontent->content);
		$db->query("UPDATE user SET status = 'ok' WHERE id = ".$user_id." LIMIT 1");
	}
?>