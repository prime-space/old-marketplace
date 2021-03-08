<a name="head_merchant.operation.list"></a>

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/merchant/">Мерчант</a> » {mshopName}</h3>
	</div>
	<div class="panel-body">
		<div class="pa_middle_c_l_b_head_6"><a href="/merchant/docs/" target="_blank" class="btn btn-info btn-lg">Документация</a></div>
		<br>
		<table class="table table_page">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c" id="anchor">
					<a href="/merchant/shop/{mshopId}/#anchor" class="btn btn-primary computer-width-30">Операции</a>
					<a href="/merchant/shop/{mshopId}/settings/#anchor" class="btn btn-primary computer-width-30">Настройки</a>
					<a href="/merchant/shop/{mshopId}/methods/#anchor" class="btn btn-primary computer-width-30">Методы оплат</a>
				</td>
			</tr>
			</tbody>
		</table>

		{{SWITCH:page}}
		{{CASE:operations}}

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Статистика продаж:</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="padding_10 text_align_c vertical_align">
					<div class="chartPeriod">
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
					</div>
				</td>
			</tr>
			<tr>
				<td class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
			</tr>
			</tbody>
		</table>

		<div class="pa_middle_c_l_b_head no_radius_border">
			<div class="sprite sprite_page"></div>
			<div class="pa_middle_c_l_b_head_7">Операции</div>
		</div>

		<div class="orderSearchForm">
			<form onsubmit="merchant.operation.list.get(0, false, true); return false;">


				<div>
					<label style="width:100px" for="id">Номер счета</label>
					<input type="text" id="id" />
				</div>


		<div style="margin: 20px 0">
			<div style="display:inline-block">
				<label style="width:100px" for="status">Статус</label>
				<select id="status">
					<option value="0">Все</option>
					<option value="success">оплачен</option>
					<option value="cancel">отменен</option>
					<option value="pending">ожидание</option>
				</select>
			</div>

			<div style="display:inline-block">
				<label style="width:100px" for="payed">Способ оплаты</label>
				<select id="payed">
					<option value="0">Все</option>
					{availableMethods}
				</select>
			</div>
		</div>

				<div>
					<label style="width:100px" for="date">Дата платежа</label>
					От - <input type="text" id="date1" /> До - <input type="text" id="date2" />
				</div>

				<input class="btn btn-small btn-primary" type="submit" value="Найти">
			</form>
		</div>
		<br>
		<hr>
		<br>
		<table class="table table-striped table_page merchantoperations table-responsive tr-border">
			<thead>
			<tr>
				<td class="text_align_c" style="width: 40px;">Счет №</td>
				<td class="text_align_c" style="width: 100px;">PRIME ID №</td>
				<td class="text_align_c" style="width: 120px;">Название товара</td>
				<td class="text_align_c" style="width: 120px;">Сумма</td>
				<td class="text_align_c" style="text-align: center;">Статус</td>
				<td class="text_align_c" style="width: 120px;">Дата платежа</td>
				<td class="text_align_c" style="width: 120px;">Доступны после</td>
				<td class="text_align_c" style="width: 85px;">Уведомления</td>
			</tr>
			</thead>
			<tbody id="list"></tbody>
		</table>
		{{ENDCASE:operations}}
		{{CASE:settings}}
		<form onsubmit="merchant.savesettings(this);return false;">
			<table class="table table-striped table_page table_page_input1">
				<thead>
				<tr>
					<td colspan="2">Настройки</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width: 200px;" class="font_weight_700 padding_10 text_align_r vertical_align">shopid:</td>
					<td class="padding_10 vertical_align">{mshopId}</td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Название магазина:</td>
					<td class="padding_10 vertical_align"><input type="text" name="name" value="{name}" maxlength="50"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Адрес магазина:</td>
					<td class="padding_10 vertical_align"><input type="text" name="url" value="{url}" maxlength="100"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Краткое описание:</td>
					<td class="padding_10 vertical_align"><input type="text" name="description" value="{description}" maxlength="200"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Комиссию оплачивает:</td>
					<td class="padding_10 vertical_align"><label><input type="radio" name="fee" {fee1}> покупатель</label><label><input type="radio" name="fee" {fee2}> магазин</label></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Время жизни платежа(минут):</td>
					<td class="padding_10 vertical_align"><input type="text" name="lifetime" value="{lifetime}" maxlength="5"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Секретная фраза:</td>
					<td class="padding_10 vertical_align"><input type="text" name="secret" value="{secret}" maxlength="32"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Подпись:</td>
					<td class="padding_10 vertical_align"><label><input type="checkbox" name="checksigncreate" {checksigncreate}> Проверять подпись при создании платежа</label></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Адрес перенаправления при успешной оплате:</td>
					<td class="padding_10 vertical_align"><input type="text" name="success" value="{success}" maxlength="100"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Адрес перенаправления при ошибке оплаты:</td>
					<td class="padding_10 vertical_align"><input type="text" name="fail" value="{fail}" maxlength="100"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Адрес отправки уведомлений:</td>
					<td class="padding_10 vertical_align"><input type="text" name="result" value="{result}" maxlength="100"></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Метод отправки данных:</td>
					<td class="padding_10 vertical_align"><label><input type="radio" name="sendmethod" {sendmethod1}> POST</label><label style="display:none"><input type="radio" name="sendmethod" {sendmethod2}> GET</label></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Переопределение:</td>
					<td class="padding_10 vertical_align"><label><input type="checkbox" name="overrideurl" {overrideurl}> разрешить переопределение адресов в запросе</label></td>
				</tr>
				<tr>
					<td class="font_weight_700 padding_10 text_align_r vertical_align">Режим:</td>
					<td class="padding_10 vertical_align"><label><input type="checkbox" name="test" {test}> тестовый режим</label></td>
				</tr>
				<tr>
					<td colspan="2" class="padding_10 text_align_r vertical_align">
						<div data-name="info" style="display: block;text-align: center;"></div>
						<button class="btn btn-success" name="button">Сохранить</button>
						<input type="hidden" name="mshopId" value="{mshopId}">
					</td>
				</tr>
				</tbody>
			</table>
		</form>
		{{ENDCASE:settings}}
		{{CASE:methods}}
		<form  onsubmit="merchant.admin.feeeditUser(this);return false;">
			<input type="hidden" name="shopId" value="{shopId}" >
			<table class="table table-striped table_page table_page_input1">
				<thead>
				<tr>
					<td class="text_align_c">Название</td>
					<td class="text_align_c">Доступен</td>
					<td class="text_align_c">Задействован</td>
				</tr>
				</thead>
				<tbody>
				{{FOR:list}}
				<tr >

					<td class="padding_10 text_align_c vertical_align">
						{name}
					</td>
					<td class="padding_10 text_align_c vertical_align">
						<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="method_{id}" {checked} disabled></label>
					</td>
					<td class="padding_10 text_align_c vertical_align">
						<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="sys_{id}_enabled"  {checkedUser} {methodDisabled}></label>
					</td>

				</tr>
				{{ENDFOR:list}}
				<tr>
					<td colspan="4" class="padding_10 text_align_c">
						<div data-name="info" id="info" style="text-align: center;"></div>
						<button class="btn btn-success btn-sm" name="button">Сохранить</button>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
		{{ENDCASE:methods}}
		{{ENDSWITCH:page}}

	</div>
</div>


