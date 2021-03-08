<a name="head_panel.user.cabinet.list.get"></a>

<div class="orderSearchForm">
	<form onsubmit="panel.user.cabinet.list.get(0, false, true); return false;">
		Найти
		<input type="text" id="glob_orderSearchText" style="width: 200px;" />
		<select id="glob_orderSearchSelect">
			<option value="all">Все</option>
			<option value="email">По email покупателя</option>
			<option value="order">По номеру заказа</option>
			<option value="new">С новыми сообщениями</option>
		</select>
		<input class="btn btn-small btn-primary" type="submit" value="Найти">
		<a href="/panel/messages/" target="_blank" class="btn btn-small btn-danger"><i class="icon-envelope icon-white"></i> Поддержка</a>
	</form>
</div>
<hr>
<table class="table table-striped table_page table-responsive tr-border">
	<thead>
		<tr>
			<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Счет №</td>
			<!--<td style="width: 5px;" class="padding_0"></td>-->
			<td class="font_size_12 padding_l_5 padding_r_5">Название товара</td>
			<td style="width: 70px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Дата</td>
			<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Сумма</td>
			<td style="width: 120px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Доступны после</td>
			<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Информация</td>
			<td style="width: 50px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Рейтинг</td>
		</tr>
	</thead>
	<tbody id="panel_user_cabinet_list">
	</tbody>
</table>