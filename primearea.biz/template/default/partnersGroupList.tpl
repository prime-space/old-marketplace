{{IF:NOLIST}}
		<tr>
			<td colspan="3" class="font_size_14 font_weight_700 text_align_c padding_10">Создайте группу</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:GROUPS}}
		<tr>
			<td style="width: 50px;" class="font_size_14 text_align_c font_weight_700 padding_b_10 padding_t_10">{id}</td>
			<td class="font_size_14 padding_10"><a href="/panel/partner/products/{id}">{name}</a></td>
			<td style="width: 20px;" class="font_size_14 text_align_c padding_b_10 padding_t_10"><span class="span_clear" onclick="panel.user.partner.products.group.del({id},this)">×</span></td>
		</tr>
	{{ENDFOR:GROUPS}}
{{ENDIF:NOLIST}}
