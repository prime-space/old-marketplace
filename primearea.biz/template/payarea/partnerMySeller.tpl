
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/mysellers/">Продавцы</a> » {login}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			{{IF:NOFOUND}}
			<tbody>
			<tr>
				<td colspan="2" class="font_size_14 font_weight_700 text_align_c padding_10">Продавец не найден.</td>
			</tr>
			</tbody>
			{{ELSE:NOFOUND}}
			<thead>
			<tr>
				<td colspan="2">Дополнительный процент от продавца: {percent}%</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>{messages}</td>
			</tr>
			</tbody>
			{{ENDIF:NOFOUND}}
		</table>
	</div>
</div>

