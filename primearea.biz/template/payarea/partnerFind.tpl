<a name="head_panel.user.partner.find.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Поиск партнера</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td colspan="2">Поиск</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td style="width: 380px;" class="font_size_14 text_align_r font_weight_700 padding_10 vertical_align">Вы можете пригласить партнера по его ID:</td>
				<td class="padding_10 vertical_align">
					<form onsubmit="panel.user.partner.find.byId(this);return false;" class="form_content_def">
						<input type="text" name="partnerUserId">
						<button name="button" class="btn btn-success btn-sm">Найти</button>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Ник</td>
				<td class="text_align_c">Зарегистрирован</td>
				<td class="text_align_c">Партнерств</td>
				<td class="text_align_c">Продаж</td>
				<td class="text_align_c">Рейтинг</td>
				<td></td>
			</tr>
			</thead>
			<tbody id="partnersList">
			</tbody>
		</table>
	</div>
</div>

