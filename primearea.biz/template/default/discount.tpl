
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/partner/">Партнерская программа</a> » Управление скидками</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr class="tr-white">
				<td>Общие скидки</td>
			</tr>
			</thead>
			<tbody>
			<tr class="tr-white">
				<td class="text_align_c padding_10">

					<form id="panel_user_discount_global_form" onsubmit="panel.user.discount.glob.save(this);return false;">
						<table class="table table-striped table_page table_page_b table_page_input1">
							<tbody id="user_discountDivMain"></tbody>
							<tfoot>
							<tr class="tr-white">
								<td class="text_align_c padding_10">
									<div id="panel_user_discount_glob_save_info"></div>
									<button class="btn btn-info btn-sm" onclick="panel.user.discount.glob.field.add();return false;">Добавить</button>
									<button class="btn btn-success btn-sm" name="button">Сохранить</button>
								</td>
							</tr>
							</tfoot>
						</table>
					</form>

				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>



<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Персональные скидки</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.user.discount.personal.add(this); return false;">
			<table class="table table_page table_page_input1 td-table">
				<tbody>
				<tr>
					<td style="max-width:530px;" class="font_weight_700 text_align_r padding_10 vertical_align">
						Назначить "фиксированную скидку" в размере <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Фиксированная скидка начисляется покупателю вне зависимости от текущей суммы покупок.">?</span>
					</td>
					<td colspan="3" class="padding_10 vertical_align">
						<input style="max-width:45px;" type="text" name="percent" maxlength="2"> %
					</td>
					<td style="max-width: 73px;" class="text_align_c padding_10 vertical_align">
						<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="radio" name="type" checked></label>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 text_align_r padding_10 vertical_align">
						Установить "первоначальную сумму покупок" <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Первоначальная сумма покупок добавляется к текущей (реальной) сумме покупок при расчете скидки.">?</span>
					</td>
					<td colspan="3" class="padding_10 vertical_align">
						<input style="max-width:45px;" type="text" name="money" maxlength="4"> руб.
					</td>
					<td class="text_align_c padding_10 vertical_align">
						<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="radio" name="type"></label>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 text_align_r padding_10 vertical_align">
						Для покупателя с email:
					</td>
					<td class="padding_10 vertical_align">
						<input type="text" name="email" maxlength="32">
					</td>
					<td style="max-width: 100px;" class="padding_10 vertical_align">
						<img id="panel_user_discount_personal_add_captcha" src="/modules/captcha/captcha.php?{random}">
					</td>
					<td style="max-width: 82px;" class="padding_10 vertical_align">
						<input type="text" name="captcha" style="max-width:70px;">
					</td>
					<td class="padding_10 vertical_align">
						<button name="button" class="btn btn-success btn-sm">Добавить</button>
					</td>
				</tr>
				<tr>
					<td colspan="5" class="text_align_c padding_0"><div id="panel_user_discount_personal_add_error" class="form_error"></div></td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>




<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Список покупателей, которым вы предоставили персональные скидки</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page td-table">
			<thead>
			<tr>
				<td>email</td>
				<td class="text_align_c">фиксированная</td>
				<td class="text_align_c">сумма покупок</td>
				<td></td>
			</tr>
			</thead>
			<tbody id="panel_user_discount_personal_list">

			</tbody>
		</table>
	</div>
</div>