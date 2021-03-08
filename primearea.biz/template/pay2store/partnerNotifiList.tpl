{{IF:NOLIST}}
						<tr>
							<td colspan="2" class="font_size_14 font_weight_700 text_align_c padding_10">Уведомления отсутствуют</td>
						</tr>
{{ELSE:NOLIST}}
	{{FOR:list}}
						<tr class="tr_nr{notReaded}">
							<td class="text_align_c padding_10 vertical_align" style="color: {color}">{date}</td>
							<td class="padding_10 vertical_align" style="color: {color}">{txt}</td>
						</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}