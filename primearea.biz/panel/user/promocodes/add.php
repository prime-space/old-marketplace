<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
	
	$bd = new mysql();
	$user = new user();
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close('{"status": "error", "message": "Ошибка доступа"}');
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Отсутствуют данные"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$error = array();
	if(!$data['name'])$error[] = 'Введите название';
	if(!$data['type'])$error[] = 'Выберите тип';
	elseif(!preg_match('/^1|2|3$/', $data['type']))$error[] = 'Неверный формат типа';
	if($data['group'] === 'default'){
		if(!$data['count'])$error[] = 'Укажите количество';
		elseif(!preg_match('/^\d{1,3}$/', $data['count']) || $data['count'] > 100 || $data['count'] < 1)$error[] = 'Укажите правильное количество';
	}
	if(!$data['datestart'])$error[] = 'Укажите дату начала';
	elseif(!preg_match('/^\d{2}-\d{2}-\d{4}$/', $data['datestart']))$error[] = 'Укажите правильную дату начала';
	else{
		$data['datestart'] = strtotime($data['datestart']);
		if($data['datestart'] < mktime(0,0,0))$error[] = 'Дата начала не может быть прошедшей';
	}
	if(!$data['dateend'])$error[] = 'Укажите дату окончания';
	elseif(!preg_match('/^\d{2}-\d{2}-\d{4}$/', $data['dateend']))$error[] = 'Укажите правильную дату окончания';
	else{
		$data['dateend'] = strtotime($data['dateend']);
		if($data['datestart']+86399 > $data['dateend'])$error[] = 'Дата окончания должна быть после даты начала';
	}
	if($data['type'] == 3){
		if(!$data['maxuse'])$error[] = 'Введите максимальное количество использования';
		elseif(!preg_match('/^\d{1,2}$/', $data['maxuse']))$error[] = 'Максимальное количество использования должно быть число от 1 до 99';
	}
	
	if($data['group'] === 'nominal'){
		if(!$data['code']){
			$error[] = 'Введите промокод';	
		}elseif(!preg_match('/^[a-zA-Z0-9-]{5,16}$/', $data['code'])){
			$error[] = 'Разрешены большие/маленькие буквы, цифры, дефис, длиною  от 5 до 16 символов.';	
		}
		
	}	

	if(count($error))close(json_encode(array('status' => 'error', 'list' => $error)));
	

	$name = $bd->prepare($data['name'], 64);
	$type = (int)$data['type'];
	$count = (int)$data['count'];
	$datestart = date('Y-m-d', $data['datestart']);
	$dateend = date('Y-m-d', $data['dateend']);
	$maxuse = $data['type'] == 3 ? (int)$data['maxuse'] : 'NULL';
	

	if($data['group'] === 'nominal'){
		$bd->read("SELECT id FROM promocode_el WHERE code = '".$bd->prepare($data['code'], 16)."' AND user_id = ".$user->id." LIMIT 1");
		if($bd->rows){
			close(json_encode(array('status' => 'error', 'list' => ['Промокод уже используется.'])));
		}
	}
	$promocode_id = $bd->write("INSERT INTO promocodes VALUES(NULL, ".$user->id.", '".$name."', ".$type.", ".$maxuse.", '".$datestart."', '".$dateend."', NOW())");	
	
	$codes = array();
	
	if($data['group'] === 'nominal'){
		$codes[] = "(NULL, ".$promocode_id.", '".$bd->prepare($data['code'], 16)."', 0, 0, NULL, ".$user->id.")";
	}else{
		$length = 16;
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$numChars = strlen($chars);

		while(count($codes) < $count){
			$code = '';
			for($i=0;$i<$length;$i++)$code .= substr($chars, rand(1, $numChars) - 1, 1);
			$bd->read("SELECT id FROM promocode_el WHERE code = '".$code."' AND user_id = ".$user->id." LIMIT 1");
			if($bd->rows)continue;
			$codes[] = "(NULL, ".$promocode_id.", '".$code."', 0, 0, NULL, ".$user->id.")";
		}
	}
	
	$bd->write("INSERT INTO promocode_el VALUES".implode(',', $codes));
	
	close(json_encode(array('status' => 'ok', 'promocode_id' => $promocode_id)));
?>