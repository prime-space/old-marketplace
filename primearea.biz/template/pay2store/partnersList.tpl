{{IF:NOLIST}}
		<tr>
			<td colspan="6">Нет доступных партнеров</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:PARTNERS}}
		<tr>
			<td class="font_size_14 padding_10"><a href="/seller/{partnerId}/" target="_blank">{login}</a></td>
			<td style="width: 165px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{registered}</td>
			<td style="width: 125px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{partnersCount}</td>
			<td style="width: 125px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{sales}</td>
			<td style="width: 100px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{rating}</td>
			<td style="width: 40px;" class="font_size_14 text_align_c vertical_align"><a onclick="panel.user.partner.find.info({partnerId}, this);"><span class="span_info">i</span></a></td>
		</tr>
	{{ENDFOR:PARTNERS}}
{{ENDIF:NOLIST}}
