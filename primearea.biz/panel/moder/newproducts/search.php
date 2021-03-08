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

	$id = (int)$data['id'];

	$request = $bd->read("
		SELECT p.idUser, p.price, p.name, u.login
		FROM product p
		JOIN user u ON u.id = p.idUser
		WHERE
			p.id = ".$id." AND
			p.block <> 'deleted'
		LIMIT 1
	");
	if(!$bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Товар не найден')));

	$idUser = mysql_result($request,0,0);
	$price = mysql_result($request,0,1);
	$name = strBaseOut(mysql_result($request,0,2));
	$login = strBaseOut(mysql_result($request,0,3));

	$list = <<<HTML
<table class="table table-striped table_page">
	<thead>
		<tr>
			<td style="width: 130px;" class="text_align_c">Продавец</td>
			<td>Название товара</td>
			<td style="width: 90px;" class="text_align_c">Цена</td>
			<td style="width: 180px;"></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$login}</td>
			<td class="font_size_14 padding_10 vertical_align">{$name}</td>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$price}</td>
			<td class="font_size_14 padding_10 text_align_c vertical_align">
				<a class="btn btn-sm btn-warning" href="/panel/productedit/{$id}/" target="_blank">Редактировать</a>
				<button class="btn btn-sm btn-danger" onclick="panel.moder.newproducts.del({$id}, this);">Удалить</button>
			</td>
		</tr>
	</tbody>
</table>
HTML;

	close(json_encode(array('status' => 'ok', 'content' => $list)));
?>