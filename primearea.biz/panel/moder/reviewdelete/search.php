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

	$request = $bd->read("SELECT `text`, `good`, `date` FROM `review` WHERE id = '".$id."' AND del = 0 LIMIT 1");

	if(!$bd->rows)close(json_encode(array('status' => 'error', 'message' => 'Отзыв не найден')));

	$text = strBaseOut(mysql_result($request,0,0));
	$good = mysql_result($request,0,1);
	$date = mysql_result($request,0,2);

	if($good)close(json_encode(array('status' => 'error', 'message' => 'Удалить можно только отрицательный отзыв')));

	$date_strtotime = strtotime( $date );
	$date = @date( "Y-m-d", $date_strtotime );
	$time = @date( "H:i:s", $date_strtotime );

	$content = <<<HTML
<table class="table table_page">
	<tbody>
		<tr>
			<td style="border: 0 solid transparent;" class="padding_l_10 padding_t_10 padding_r_10">
				<div class="middle_lot_o_one middle_lot_o_one0">
					<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> ID: {$id} | Оценка покупателя <span class="middle_lot_o_bead">отрицательная</span></div>
					<div class="middle_lot_o_t2">{$text}</div>
					<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {$date} <i class="sprite sprite_otz_time"></i> {$time}</div>
				</div>
			</td>
		</tr>
		<tr>
			<td style="border: 0 solid transparent;" class="padding_b_10 padding_l_10 padding_r_10 text_align_r">
				<div id="reviewdelete_del_error"></div>
				<div class="btn btn-sm btn-danger" onclick="panel.moder.reviewdelete.del({$id}, this);">удалить</div>
			</td>
		</tr>
	</tbody>
</table>
HTML;

	close(json_encode(array('status' => 'ok', 'content' => $content)));

?>