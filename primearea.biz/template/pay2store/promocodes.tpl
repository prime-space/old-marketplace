
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Промо-коды</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="font_size_12">Название</td>
				<td style="width: 160px;" class="font_size_12 padding_10 text_align_c">Период действия</td>
				<td style="width: 160px;" class="font_size_12 padding_10 text_align_c">Кол-во кодов</td>
				<td style="width: 155px;" class="font_size_12 padding_10 text_align_c">Использование</td>
				<td style="width: 60px;" class="font_size_12 padding_10 text_align_c">Продажи</td>
				<td  style="width: 160px;" class="font_size_12 padding_10 text_align_c">Товаров со скидкой</td>
				<td  style="width: 150px;"></td>
			</tr>
			</thead>
			<tbody>
			{{IF:NOLIST}}
			{{FOR:LIST}}
			<tr>
				<td class="font_size_12 padding_5 vertical_align"><a href="/panel/promocodes/edit/{promocode_id}/">{name}</a></td>
				<td class="font_size_12 text_align_c padding_5 vertical_align">{date}</td>
				<td class="font_size_12 text_align_c padding_5 vertical_align">{count}</td>
				<td class="font_size_12 text_align_c padding_5 vertical_align">{type}</td>
				<td class="font_size_12 text_align_c padding_5 vertical_align">{sale}</td>
				<td class="font_size_12 text_align_c padding_5 vertical_align">{product_count}</td>
				<td class="font_size_12 text_align_c padding_5 vertical_align"><a class="btn btn-info btn-sm" href="/panel/promocodes/edit/{promocode_id}/">изменить</a></td>
			</tr>
			{{ENDFOR:LIST}}
			{{ELSE:NOLIST}}
			<tr>
				<td colspan="7">Выпусков нет</td>
			</tr>
			{{ENDIF:NOLIST}}
			<tr>
				<td colspan="7" class="text_align_r padding_10"><a class="btn btn-success btn-sm" href="/panel/promocodes/add/">Выпуск промокодов</a></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

