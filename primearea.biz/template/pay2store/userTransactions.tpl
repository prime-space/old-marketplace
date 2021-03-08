<div class="overfloy_300">
	<table class="table table-condensed table_page">
		<thead>
			<tr>
				<td class="padding_5 text_align_c">Дата</td>
				<td class="padding_5 text_align_c">Метод</td>
				<td class="padding_5 text_align_c">Сумма</td>
				<td class="padding_5 text_align_c">Баланс</td>
			</tr>
		</thead>
		<tbody>
			{{FOR:TRANSACTIONS}}
			<tr>
				<td class="padding_5 text_align_c vertical_align" style="color:{executeColor}">{execute}</td>
				<td class="padding_5 text_align_c vertical_align">{method}</td>
				<td class="padding_5 text_align_c vertical_align">{amount} руб.</td>
				<td class="padding_5 text_align_c vertical_align">{balance}</td>
			</tr>
			{{ENDFOR:TRANSACTIONS}}
		</tbody>
	</table>
</div>