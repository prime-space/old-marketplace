

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Продавцы</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Ник</td>
				<td class="text_align_c">Процент</td>
				<td class="text_align_c">Партнерство</td>
				<td colspan="2"></td>
			</tr>
			</thead>
			<tbody>
			{{IF:NOLIST}}
			<tr>
				<td colspan="5" class="font_size_14 font_weight_700 text_align_c padding_10">У вас нет продавцов</td>
			</tr>
			{{ELSE:NOLIST}}
			{{FOR:MYSELLERS}}
			<tr>
				<td class="font_size_14 padding_10">{login}</td>
				<td style="width: 220px;" class="font_size_14 text_align_c padding_10">{percent}</td>
				<td style="width: 220px;" class="font_size_14 text_align_c padding_10">{assignDate}</td>
				<td style="width: 50px;" class="font_size_14 text_align_c padding_b_10 padding_t_10"><a onclick="panel.user.partner.reject(1,{partnerId},this);"><span class="span_clear">×</span></a></td>
				<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10"><a href="/panel/partner/mysellers/{partnerId}/"><span class="span_info">i</span> {message}</a></td>
			</tr>
			{{ENDFOR:MYSELLERS}}
			{{ENDIF:NOLIST}}
			</tbody>
		</table>
	</div>
</div>

