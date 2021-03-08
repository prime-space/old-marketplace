<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$id = (int)$data['id'];
	
	$request = $bd->read("SELECT c.date, c.amount, c.status, c.protect, u.login,
						case c.currency
					        when 'wmr' then u.wmr
					        when 'yar' then u.yandex_purse
					        when 'qiwi' then u.qiwi_purse
			    		end
						FROM `cashout` c
						INNER JOIN `user` u
						ON c.userId = u.id
						WHERE c.id = '".$id."'
						LIMIT 1");

	if(!$bd->rows)close('{"status": "error", "message": "Позиция не найдена"}');

	$date = mysql_result($request,0,0);
	$amount = mysql_result($request,0,1);
	$status = mysql_result($request,0,2);
	$protect = mysql_result($request,0,3);
	$login = mysql_result($request,0,4);
	$purse = mysql_result($request,0,5);
	if(!$purse || $purse == ''){
		$purse = 'Не заполнено.';
	}
	$request = <<<HTML
		<tr>
			<td style="width: 120px;" class="font_weight_700 vertical_align text_align_r padding_10">Дата</td>
			<td class="vertical_align padding_10 text_align_l">{$date}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_10">Логин</td>
			<td class="vertical_align padding_10 text_align_l">{$login}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_10">Кошелек</td>
			<td class="vertical_align padding_10 text_align_l">{$purse}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_10">Сумма</td>
			<td class="vertical_align padding_10 text_align_l">{$amount} руб.</td>
		</tr>
HTML;

	$tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/admin/bookkeeping/completeRequestDialog.tpl");
	$tpl = str_replace("{request}", $request, $tpl);
	$tpl = str_replace("{id}", $id, $tpl);
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl)));
  
?>