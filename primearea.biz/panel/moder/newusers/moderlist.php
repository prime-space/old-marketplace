<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$request = $bd->read("SELECT `id`, `login` FROM `user` WHERE `role` = 'moder'");
	$list = "";
	for($i=0; $i<$bd->rows; $i++){
		$moderId =  mysql_result($request,$i,0);
		$moderLogin =  mysql_result($request,$i,1);

		$list .= <<<HTML
		<tr>
			<td class="font_size_14 padding_10 vertical_align">{$moderLogin}</td>
			<td class="font_size_14 padding_10 text_align_c vertical_align"><div class="btn btn-sm newbtn-success" onclick="panel.moder.newusers.moder.rights({$moderId});">Права</div></td>
			<td class="font_size_14 padding_10 text_align_c vertical_align"><div class="btn btn-sm btn-danger" onclick="panel.moder.newusers.moder.adddel({$moderId}, '{$moderLogin}', 'del', this);">снять статус модератора</div></td>
		</tr>
HTML;

	}

	if ( $list == '' ) {
		$list = <<<HTML
		<tr>
			<td colspan="3" class="font_size_14 padding_10 text_align_c vertical_align">Нет назначенных модераторов</td>
		</tr>
HTML;

	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));
?>