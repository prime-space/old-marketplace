<?php
	include_once '../../../../func/main.php';
	include_once '../../../../func/config.php';
	include_once '../../../../func/db.class.php';
	include_once '../../../../func/mysql.php';
	
	$db = new db();
	$bd = new mysql();
	$user = new user(array('user', 'moder', 'admin'));
	
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))exit(json_encode(array('status' => 'error','message' => 'Ошибка доступа')));
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else exit('{"status": "error", "message": "Нет данных"}');
	if($data['token'] !== $user->token)exit('{"status": "error", "message": "Ошибка доступа"}');
	
	
	$tpl = new templating(file_get_contents('show_logs.tpl'));

	$request = $db->query("
		SELECT a.ip,a.system,a.ts,c.name
		FROM authlog a
		LEFT JOIN countries c ON c.code = a.countryCode
		WHERE a.userId = ".$user->id."
		ORDER BY a.id DESC
		LIMIT 10
	");
	
	$tpl->fory('authlog');
	while($row = $db->get_row($request)){
		$tpl->fory_cycle(array(
			'authIp' => $row['ip'],
			'authSystem' => $row['system'],
			'authTs' => $row['ts'],
			'authCountryName' => $row['name']
		));
	}
	$tpl->set($tpl->fory_arr['model_tags'], $tpl->fory_arr['content']);

	exit(json_encode(array('status' => 'ok','content' => $tpl->content)));