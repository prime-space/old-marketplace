	{{IF:NOAD}}
		{{FOR:AD}}
		<tr>
			<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{name}</td>
			<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{price}</td>
			<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{sales}</td>
			<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{date}</td>
		</tr>
		{{ENDFOR:AD}}
	{{ELSE:NOAD}}
		<tr>
			<td colspan="9" class="font_size_14 font_weight_700 text_align_c padding_10">У вас пока нет ни одного объявления</td>
		</tr>
	{{ENDIF:NOAD}}
