<table class="table table-striped table_page_popup td-table">
	<tbody>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Название товара:</td>
			<td class="vertical_align padding_5 text_align_l">{name}</td>
		</tr>
		<tr>
			<tdclass="font_weight_700 vertical_align text_align_r padding_5">Логин продавца:</td>
			<td class="vertical_align padding_5 text_align_l"><a href="/seller/{user_id}/">{login}</a></td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Категория:</td>
			<td class="vertical_align padding_5 text_align_l text_url">{group}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Свойство товара:</td>
			<td class="vertical_align padding_5 text_align_l">{many}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Тип товара:</td>
			<td class="vertical_align padding_5 text_align_l text_url">{typeObject}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Цена:</td>
			<td class="vertical_align padding_5 text_align_l text_url">{price}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Изображение:</td>
			<td class="vertical_align padding_5 text_align_l text_url"><img height="100"  src="{picture}"></td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Комиссия партнеру:</td>
			<td class="vertical_align padding_5 text_align_l text_url">{partner} %</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Описание товара:</td>
			<td class="vertical_align padding_5 text_align_l text_url" >{descript}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Дополнительная информация:</td>
			<td class="vertical_align padding_5 text_align_l text_url" >{info}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5">Объекты продаж:</td>
			<td class="vertical_align padding_5 text_align_l text_url" >{file}</td>
		</tr>
		<tr>
			<td class="font_weight_700 vertical_align text_align_r padding_5"><a onclick='panel.moderate(this,true, {id});' style="width: 100px; background-color: green"  class="btn btn-primary">Одобрить</a></td>
			<td class="vertical_align padding_5 text_align_l text_url"><a onclick='panel.moderate(this, false, {id});' style="width: 100px;background-color: red"  class="btn btn-primary">Отклонить</a></td>
		</tr>
	</tbody>
</table>