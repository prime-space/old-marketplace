{{SWITCH:page}}
	{{CASE:PAGEMAIN}}
		<div class="pa_middle_c_l_b_head">
			<div class="sprite sprite_user"></div>
			<div class="pa_middle_c_l_b_head_7"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Скрытые товары</div>
		</div>

		<a name="head_panel.user.cabinet.list.get"></a>
		<div class="orderSearchForm">
			<form onsubmit="panel.moder.hiddenproducts.productslist.get(0, false, true); return false;">
				Найти
				<input type="text" id="loginSearch" />
				<input class="btn btn-small btn-primary" type="submit" value="Найти">
			</form>
		</div>

		<table class="table table-striped table_page">
			<thead>
				<tr>
					<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Логин</td>
					<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Товаров скрыто/всего</td>
				</tr>
			</thead>
			<tbody id="hiddenproducts_list">
			</tbody>
		</table>

	{{ENDCASE:PAGEMAIN}}

	{{CASE:PAGEONE}}
		<div class="pa_middle_c_l_b_head">
			<div class="sprite sprite_user"></div>
			<div class="pa_middle_c_l_b_head_7"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/hiddenproducts/">Скрытые товары</a> » {login}</div>
		</div>
		<div style="text-align: right;padding: 5px">
			<button onclick="panel.moder.hiddenproducts.edit_status({user_id}, this, 'allShow');return false;" class="btn newbtn-success btn-sm" name="button">Показать все</button>
			<button onclick="panel.moder.hiddenproducts.edit_status({user_id}, this, 'allHide');return false;" class="btn newbtn-danger btn-sm" name="button">Скрыть все</button>
		</div>
		
		<form id="hiddenproducts_form">
			<table class="table table-striped table_page">
				<thead>
					<tr>
						<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Название товара</td>
						<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Скрыто</td>
					</tr>
				</thead>
				<tbody id="hiddenproducts_list_one" data-id="{user_id}">
				</tbody>
			</table>
		</form>
	{{ENDCASE:PAGEONE}}

{{ENDSWITCH:page}}