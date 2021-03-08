<a name="head_panel.admin.order.list.get"></a>

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">ЗАКАЗЫ</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.admin.order.list.get(0, false, true); return false;">
			<table class="table table-striped table_page table_page_input1 table-responsive">
				<tbody>
				<tr class="tr-white">
					<td style="width: 150px;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Искать</td>
					<td class="padding_10 vertical_align">
						<select id="glob_orderSearchSelect">
							<option value="all">все</option>
							<option value="email">по email покупателя</option>
							<option value="order">по номеру заказа</option>
						</select>
						<input type="text" id="glob_orderSearchText" />
						<input class="btn btn-success" type="submit" value="Найти">
					</td>
					<td style="width: 150px;" class="padding_10 text_align_r vertical_align">
						<a href="/panel/messages/" target="_blank" class="btn btn-danger">Поддержка</a>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>



<table class="table table-striped table_page" style="display:none;">
	<tbody>
		<tr>
			<td style="width: 260px;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align"></td>
		</tr>
	</tbody>
</table>



<div class="panel panel-headline">
	<div class="panel-body">
		<table class="table table-striped table_page table-responsive tr-border">
			<thead>
			<tr>
				<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Счет №</td>
				<td class="font_size_12 padding_l_5 padding_r_5">Название товара</td>
				<td style="width: 70px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Дата</td>
				<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Сумма</td>
				<td style="width: 120px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Доступны после</td>
				<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Информация</td>
				<td style="width: 50px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Рейтинг</td>
			</tr>
			</thead>
			<tbody id="panel_admin_order_list">
			</tbody>
		</table>
	</div>
</div>