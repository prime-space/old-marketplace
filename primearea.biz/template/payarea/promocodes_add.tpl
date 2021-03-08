<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/promocodes/">Промо-коды</a> » Новый выпуск</h3>
	</div>

	<div style="padding: 10px">
	Для получения промокодов необходимо произвести их выпуск. Пожалуйста, определите их количество, время действия и порядок использования(погашения).<br />
	Выпусков может быть несколько с разными параментрами. Обращаем Ваше внимание, что после выпуска внести изменения в эти параметры уже нельзя.<br />
	После нажатия на кнопку "выпустить" вам будет предложено выбрать товары и определить размер скидки, которая будет предоставлена при использовании выпущенных промо-кодов.
	</div>


	<div class="line_recommended_form">
		<div onclick="panel.user.promocodes.add.change_group_type('default', this);" data-recommended-type-btn="default" class="btn btn-danger btn-recommended active">Стандартный купон</div>
		<div class="line_recommended">
			<div class="line_recommended_h"><div class="line_recommended_h2"></div></div>
			<div class="line_recommended_v" style="height: 0px;"></div>
		</div>
		<div onclick="panel.user.promocodes.add.change_group_type('nominal', this);" data-recommended-type-btn="privileges" class="btn btn-danger btn-recommended">Именной купон</div>
	</div>

	<div class="panel-body promocodes-tab promocodes-default">
		<form onsubmit="panel.user.promocodes.add.send(this); return false;">
			<input type="hidden" name="promocode_type" value="default">
			<table class="table table-striped table_page table_page_input1">
				<thead>
				<tr>
					<td colspan="3">Форма выпуска</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Название выпуска:</td>
					<td colspan="2" class="padding_10 vertical_align"><input type="text" name="name" maxlength="32"></td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Промо-код можно использовать:</td>
					<td colspan="2" class="padding_10 vertical_align">
						<select name="type" style="padding:3px;display:inline-block;" onchange="panel.user.promocodes.add.changetype(this);">
							<option value="0">-Выберите-</option>
							<option value="1">Однократно</option>
							<option value="2">Многократно</option>
							<option value="3">Не более...</option>
						</select>
						<div id="panel_user_promocodes_add_maxuse" style="display:none;">
							<input type="text" name="maxuse" maxlength="2" style="width:50px;"> раз
						</div>
					</td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Кол-во промо-кодов в выпуске:</td>
					<td colspan="2" class="padding_10 vertical_align">
						<input type="text" name="count" maxlength="3" style="text-align: center;width: 50px;">
						<span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Не более 100шт">?</span>
					</td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Время действия промо-кодов:</td>
					<td colspan="2" class="padding_10 vertical_align">
						с <input type="text" class="date" name="datestart" data-calendar="datestart" style="cursor: pointer;padding-right: 23px;text-align: center;width:150px;"> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Начальная дата не позднее сегодняшнего дня и не выше конечной даты.">?</span> по <input type="text" class="date" name="dateend" data-calendar="dateend" style="cursor: pointer;padding-right: 23px;text-align: center;width:150px;"> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Конечная дата не ранее завтрашнего дня и не ниже начальной даты.">?</span>
					</td>
				</tr>
				<tr>
					<td class="padding_10 vertical_align"><a class="btn btn-danger" style="float:left;" href="/panel/promocodes/">Вернуться</a></td>
					<td class="text_align_r padding_10 vertical_align"><button class="btn btn-success" name="button">Выпустить</button></td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>

	<div class="panel-body promocodes-tab promocodes-nominal hidden">
		<form onsubmit="panel.user.promocodes.add.send(this); return false;">
			<input type="hidden" name="promocode_type" value="nominal">
			<table class="table table-striped table_page table_page_input1">
				<thead>
				<tr>
					<td colspan="3">Форма выпуска</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Название выпуска:</td>
					<td colspan="2" class="padding_10 vertical_align"><input type="text" name="name" maxlength="32"></td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Промо код:</td>
					<td colspan="2" class="padding_10 vertical_align"><input type="text" name="code" maxlength="16"></td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Промо-код можно использовать:</td>
					<td colspan="2" class="padding_10 vertical_align">
						<select name="type" style="padding:3px;display:inline-block;" onchange="panel.user.promocodes.add.changetype(this);">
							<option value="0">-Выберите-</option>
							<option value="1">Однократно</option>
							<option value="2">Многократно</option>
							<option value="3">Не более...</option>
						</select>
						<div id="panel_user_promocodes_add_maxuse" style="display:none;">
							<input type="text" name="maxuse" maxlength="2" style="width:50px;"> раз
						</div>
					</td>
				</tr>
				<tr>
					<td style="width: 250px;" class="text_align_r font_weight_700 padding_10 vertical_align">Время действия промо-кодов:</td>
					<td colspan="2" class="padding_10 vertical_align">
						с <input type="text" class="date" name="datestart" data-calendar="datestart" style="cursor: pointer;padding-right: 23px;text-align: center;width:150px;"> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Начальная дата не позднее сегодняшнего дня и не выше конечной даты.">?</span> по <input type="text" class="date" name="dateend" data-calendar="dateend" style="cursor: pointer;padding-right: 23px;text-align: center;width:150px;"> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Конечная дата не ранее завтрашнего дня и не ниже начальной даты.">?</span>
					</td>
				</tr>
				<tr>
					<td class="padding_10 vertical_align"><a class="btn btn-danger" style="float:left;" href="/panel/promocodes/">Вернуться</a></td>
					<td class="text_align_r padding_10 vertical_align"><button class="btn btn-success" name="button">Выпустить</button></td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>

	<td class="text_align_c padding_10 vertical_align"><div id="panel_user_promocodes_products_add_error"></div></td>
</div>

