{{IF:NOSELECT}}
<form id="productSelectForm">
	<table class="table table-striped table_page table_page_input1">
		<tbody>
			<tr>
				<td style="border-top: 0 dashed transparent;" class="font_size_14 text_align_c padding_b_10 padding_l_10 padding_r_10 vertical_align">
					Товар <select id="productSelectInput" name="product" style="padding: 5px;width: 500px;">
						<option value="0">-Выберите товар-</option>
						{{FOR:SELECT}}
							<option value="{value}">{option}</option>
						{{ENDFOR:SELECT}}
					</select> скидка <input type="text" name="percent" maxlength="2" style="text-align: center;width: 50px;"> % 
					<button onclick="panel.user.promocodes.edit.products.add(this, 'one'); return false;" name="button" class="btn btn-success btn-sm">Добавить</button>
					<button onclick="panel.user.promocodes.edit.products.add(this, 'all'); return false;" name="buttonAll" class="btn btn-success btn-sm">Добавить все товары</button>
					<div id="panel_user_promocodes_products_add_error"></div>
				</td>
			</tr>
		</tbody>
	</table>
</form>
{{ELSE:NOSELECT}}
<table class="table table-striped table_page">
	<tbody>
		<tr>
			<td style="border-top: 0 dashed transparent;" class="font_size_14 font_weight_700 text_align_c padding_b_10 padding_l_10 padding_r_10">Товары для добавления отсутствуют</td>
		</tr>
	</tbody>
</table>
{{ENDIF:NOSELECT}}

<form onsubmit="panel.user.promocodes.edit.products.save(this); return false;">
	<table class="table table-striped table_page table_page_input1">
		<thead>
			<tr>
				<td>Название</td>
				<td style="width: 60px;" class="text_align_c">Цена</td>
				<td style="width: 60px;" class="text_align_c">Продаж</td>
				<td style="width: 60px;" class="text_align_c">Скидка</td>
				<td style="width: 10px;"></td>
			</tr>
		</thead>
		<tbody>
		{{IF:NOLIST}}
			{{FOR:LIST}}
			<tr>
				<td class="padding_10 vertical_align">{name}</td>
				<td class="text_align_c padding_10 vertical_align">{price}</td>
				<td class="text_align_c padding_10 vertical_align">{sold}</td>
				<td class="text_align_r padding_10 vertical_align"><input type="text" value="{percent}" name="percent_{promocode_product_id}" style="text-align: center;width: 50px;" maxlength="2"> %</td>
				<td class="text_align_c padding_10 vertical_align"><span onclick="panel.user.promocodes.edit.products.del({promocode_product_id}, this); return false;" class="span_clear">×</span></td>
			</tr>
			{{ENDFOR:LIST}}
		{{ELSE:NOLIST}}
			<tr>
				<td colspan="5" class="font_size_14 font_weight_700 text_align_c padding_10">Добавьте товары</td>
			</tr>
		{{ENDIF:NOLIST}}
			<tr id="promocodes_edit_percentall_block" style="display:none;">
				<td colspan="4" class="text_align_r padding_10"><label style="padding: 6px 10px;vertical-align: top;"><input type="checkbox" name="percentall"> Установить на все товары скидку:</label> <input type="text" name="percentall_value" style="    text-align: center;width: 50px;" maxlength="2"> %</td>
				<td class="padding_10"></td>
			</tr>
			<tr>
				<td colspan="5" class="padding_0"><div id="panel_user_promocodes_edit_products_save_error"></div></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="background: #4E4E4E;border-top: 0 dashed transparent;" class="padding_10">
					<a class="btn btn-danger btn-sm" href="/panel/promocodes/">Назад</a>
				</td>
				<td style="background: #4E4E4E;border-top: 0 dashed transparent;" colspan="4" class="text_align_r padding_10">
					<button class="btn btn-danger btn-sm" onclick="panel.user.promocodes.del({promocode_id}, this); return false;">Удалить выпуск</button>
					<button class="btn btn-success btn-sm" name="button">Сохранить</button>
				</td>
			</tr>
		</tfoot>
	</table>
</form>