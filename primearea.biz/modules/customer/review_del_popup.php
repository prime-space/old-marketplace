<?php 
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close(json_encode(array('status' => 'error', 'message' => 'Отсутствуют данные')));

	$bd = new mysql();
	
	$review_id = (int)$data['review_id'];
	
	$bd->read("SELECT id FROM review WHERE id = ".$review_id." AND good = 1 LIMIT 1");
	if($bd->rows){
		$content = '
			<form onsubmit="module.customer.review.del.action(this, '.$review_id.');return false;">
			<div class="verygood"></div>
					<input type="checkbox" name="agreement" style="display:none;">
					<p class="verygoodtext">Вы уверены, что хотите удалить отзыв?</p>
					<div id="module_customer_review_del_action_error"></div><br>
					<button class="unfollow" name="button">Удалить</button>
			</form>
		';	
	}else{	
		$content = '
			<form onsubmit="module.customer.review.del.action(this, '.$review_id.');return false;">
				<div class="verybad"></div>
					<p class="verybadtex" style="font-weight: 700;font-size: 16px;color: #807c7c;line-height: 1em;"><label class="radio-check" style="width: 5px;display: inline;">
    <input class="radio" type="checkbox" name="agreement" value="bad">
    <span class="bad"></span>
    </label>Удаляя отрицательный отзыв я подтверждаю, что возникший конфликт с продавцом исчерпан и я не имею претензий к продавцу и товару.</p><p class="verybadtext">Я осведомлен, что после удаления отзыва я не смогу его повторно разместить или изменить на положительный.</p>
					<div id="module_customer_review_del_action_error"></div><br>
					<button class="unfollow" name="button">Удалить</button>
			</form>
		';
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $content, 'title' => 'Удаление отзыва')));
?>