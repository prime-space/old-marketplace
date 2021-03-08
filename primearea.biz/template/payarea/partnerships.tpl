
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Мои партнеры</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Ник</td>
				<td class="text_align_c">Рейтинг</td>
				<td class="text_align_c">Процент</td>
				<td class="text_align_c">Партнерство</td>
				<td colspan="2"></td>
			</tr>
			</thead>
			<tbody>
			{{IF:NOLIST}}
			<tr>
				<td colspan="6" class="font_size_14 font_weight_700 text_align_c padding_10">У вас нет партнеров</td>
			</tr>
			{{ELSE:NOLIST}}
			{{FOR:MYPARTNERS}}
			<tr>
				<td class="font_size_14 padding_10"><a href="/seller/{userId}/" target="_blank">{login}</a></td>
				<td style="width: 100px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{rating}</td>
				<td style="width: 100px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{percent}</td>
				<td style="width: 130px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{assignDate}</td>
				<td style="width: 40px;" class="font_size_14 text_align_c vertical_align"><span class="span_clear" onclick="panel.user.partner.reject(0,{partnerId},this);">×</span></td>
				<td style="width: 100px;" class="font_size_14 text_align_c vertical_align"><a href="/panel/partner/partnerships/{partnerId}/"><span class="span_info">i</span> {message}</a></td>
			</tr>
			{{ENDFOR:MYPARTNERS}}
			{{ENDIF:NOLIST}}
			</tbody>
		</table>
	</div>
</div>

