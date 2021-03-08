<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
    include "../../../func/db.class.php";

	$bd = new mysql();
    $db = new db();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin,moder"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');

	$query = $bd->prepare($data['query'], 32);
	if(!in_array($data['method'], array('login', 'skype', 'phone'), TRUE))close(json_encode(array('status' => 'error', 'message' => 'Неверный метод')));

	$request = $bd->read("SELECT `login`, `id`, `status`, last_action FROM `user` WHERE `".$data['method']."` = '".$query."'");

	if(!$bd->rows)close(json_encode(array('status' => 'ok', 'content' => 'Пользователь не найден')));

	$list = "";
	for($i=0;$i<$bd->rows;$i++){
		$login = mysql_result($request,$i,0);
		$id = mysql_result($request,$i,1);
		$status = mysql_result($request,$i,2);
		$last_action = mysql_result($request,$i,3);

		if($status == 'ok'){
			$userBlockingMet = 'block';
			$userBlockingText = 'Заблокировать';
		}else{ 
			$userBlockingMet = 'unblock';
			$userBlockingText = 'Разблокировать';
		}
		
		$btnGroup = $user->role === 'admin' ? '<div class="btn btn-sm btn-success" onclick="panel.moder.newusers.moder.adddel('.$id.', \''.$login.'\', \'add\', this)">Модератором </div>' : '';

		if($user->role == 'admin' || ($user->role == 'moder' && $user->checkModerRight('newusers_stat')) ){
			$_individual = '<div class="btn btn-sm btn-info" onclick="panel.moder.newusers.moder.settings('.$id.', this);">Индивидуальные настройки</div>';
			
			$_adminBlock = '<table class="table table-striped table_page shop">
				<thead>
					<tr>
						<td>Статистика продаж магазина:</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="padding_10 text_align_c vertical_align">
							<div class="chartPeriod chart-shop">
								<div class="btn btn-info btn-sm active">Сегодня</div>
								<div class="btn btn-primary btn-sm">Вчера</div>
								<div class="btn btn-primary btn-sm">Неделя</div>
								<div class="btn btn-primary btn-sm">Месяц</div>
								<div class="btn btn-primary btn-sm">Квартал</div>
								<div class="btn btn-primary btn-sm">Год</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="padding_10 text_align_c">
							<div id="salesCharts">
								<div id="salesGraph"></div>
								<div id="reviewsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
								<div id="productsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
					</tr>
				</tbody>
			</table>

			<table class="table table-striped table_page merchant">
				<thead>
					<tr>
						<td>Статистика продаж мерчанта:</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="padding_10 text_align_c vertical_align">
							<div class="chartPeriod chart-merch">
								<div class="btn btn-info btn-sm active">Сегодня</div>
								<div class="btn btn-primary btn-sm">Вчера</div>
								<div class="btn btn-primary btn-sm">Неделя</div>
								<div class="btn btn-primary btn-sm">Месяц</div>
								<div class="btn btn-primary btn-sm">Квартал</div>
								<div class="btn btn-primary btn-sm">Год</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="padding_10 text_align_c">
							<div id="salesCharts2">
								<div id="salesGraph"></div>
								<div id="reviewsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
								<div id="productsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
					</tr>
				</tbody>
			</table>';


		}else{
			$_individual = '';
			$_adminBlock = '';
		}

		$list = <<<HTML
<table class="table table-striped table_page td-table">
	<thead>
		<tr>
			<td class="text_align_c">Логин</td>
			<td>Посл. активность</td>
			<td" class="text_align_c"></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="font_size_14 padding_10 text_align_c vertical_align">{$login}</td>
			<td class="font_size_14 padding_10 vertical_align">{$last_action}</td>
			<td class="font_size_14 padding_10 text_align_c vertical_align">
				{$_individual}
				<a class="btn btn-sm btn-primary" target="_blank" href="/panel/messages/{$id}/">Сообщение</a>
				<div class="btn btn-sm btn-info" onclick="panel.moder.newusers.info({$id}, this);">Редактировать</div>
				{$btnGroup}
				<div class="btn btn-sm btn-danger" onclick="panel.moder.newusers.blocking({$id}, '{$login}', '{$userBlockingMet}', this);">{$userBlockingText}</div>
			</td>
		</tr>
		<tr>
			<td style="border-bottom: none" colspan="3" class="padding_10">
				<div id="admin_userUserInfo"></div>
			</td>
		</tr>
		<tr >
			<td style="border-bottom: none" colspan="3" class="padding_10">
				<div id="admin_userUserInfo_settings"></div>
			</td>
		</tr>
	</tbody>
</table>

		{$_adminBlock}

HTML;

	}

	close(json_encode(array('status' => 'ok', 'content' => $list)));

?>