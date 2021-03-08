<a name="head_panel.user.partner.setfee.list.get"></a>

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Установка комиссии</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_ps1">
			<thead>
			<tr>
				<td colspan="2">Любой участник площадки может продавать ваши товары, если на них установлена комиссия вознаграждения</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td style="width: 220px;" class="font_size_14 font_weight_700 text_align_r padding_10 vertical_align">Установить на все товары:</td>
				<td class="padding_10 vertical_align">
					<form onsubmit="panel.user.partner.setfee.all(this);return false;" class="form_content_def">
						<input type="text" name="fee" maxlength="2" style="width: 50px;"><span class="form_span">%</span>
						<button name="button" name="name" class="btn btn-success btn-sm">Сохранить</button>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<form onsubmit="panel.user.partner.setfee.action(this);return false;">
			<table class="table table-striped table_page table_ps2">
				<thead>
				<tr>
					<td>Название</td>
					<td class="text_align_c">Цена</td>
					<td class="text_align_c">Комиссия</td>
				</tr>
				</thead>
				<tbody id="partnerSetfeeList">
				</tbody>
				<tfoot>
				<tr>
					<td style="background: #4E4E4E;border-top: 0 dashed transparent;padding: 10px;text-align: right;vertical-align: middle;" colspan="3"><button class="btn btn-info" name="button">Сохранить</button></td>
				</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>

