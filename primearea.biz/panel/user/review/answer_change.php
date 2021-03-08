<?php
	include "../../../func/config.php";
	include "../../../func/db.class.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$db = new db();
	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'ok', 'content' => 'Ошибка доступа')));

	$where = "WHERE 1";
 
	$data = json_decode($_POST['data'], true);
	
	$reviewId = (int)$db->safesql($data['review_id']);
	$text = $db->safesql($data['text']);

	if($text != ''){
		$request = $db->super_query("SELECT re.idProduct, pr.idUser
	        FROM review re 
	        JOIN product pr on pr.id = re.idProduct
			WHERE re.id = ".$reviewId." LIMIT 1");
		$user_id = $request['idUser'];
		
		if($user_id == $user->id){
			$db->query("UPDATE `review_answer` SET `text` = '".$text."' 
				WHERE 	reviewId = ".$reviewId." LIMIT 1"
			);
		}
		
	}
	
	
	close(json_encode(array('status' => 'ok')));
  
?>