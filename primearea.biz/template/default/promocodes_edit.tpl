
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/promocodes/">Промо-коды</a> » Редактирование</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Название:</td>
				<td class="text_align_c">Использование:</td>
				<td class="text_align_c">Период действия:</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="font_size_14 padding_10">{name}</td>
				<td style="width: 220px;" class="font_size_14 text_align_c padding_10">{type}</td>
				<td style="width: 220px;" class="font_size_14 text_align_c padding_10">{date}</td>
			</tr>
			<tr>
				<td colspan="3" class="text_align_c padding_10" id="panel_user_promocodes_edit_tab">
					<button onclick="main.tab.change(this, panel.user.promocodes.edit.lists);" class="btn btn-info btn-sm" disabled>
						товары (<span id="panel_user_promocodes_products_count">{products_count}</span>)
					</button>
					<button onclick="main.tab.change(this, panel.user.promocodes.edit.lists);" class="btn btn-info btn-sm">
						промо-коды ({promocodes_count})
					</button>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="padding_10" id="panel_user_promocodes_edit_lists"></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>



