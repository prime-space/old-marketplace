{{IF:nolist}}Уведомления отсутствуют{{ELSE:nolist}}
	{{FOR:list}}
<div class="notifylist_block">
        <table class="table table-striped table_page">
		<thead>
			<tr>
				<td colspan="2">Способ оплаты:  {paymentname}</td>
			</tr>
		</thead>
		
	</table>
	<table class="table table-striped table_page">
		<thead>
			<tr>
				<td colspan="2">Переданные {method} данные:</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="font_weight_700 padding_10 text_align_r" style="width: 100px;">array:</td>
				<td class="padding_10"><pre class="php" style="margin: 0;">{senddata}</pre></td>
			</tr>
		</tbody>
	</table>

	<table class="table table-striped table_page">
		<thead>
			<tr>
				<td colspan="2">Ответ:</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="font_weight_700 padding_10 text_align_r" style="width: 100px;">Дата:</td>
				<td class="padding_10" >{tsr}</td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 text_align_r">Код:</td>
				<td class="padding_10">{code}</td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 text_align_r">Данные:</td>
				<td class="padding_10"><pre class="html" style="margin: 0;">{result}</pre></td>
			</tr>
		</tbody>
	</table>
</div>
	{{ENDFOR:list}}
{{ENDIF:nolist}}