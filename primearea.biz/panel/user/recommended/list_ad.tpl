	{{IF:NOAD}}
		{{FOR:AD}}
		<tr>
			<td data-label="Дата" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{date}</td>
			<td data-label="Товар" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{product}</td>
			<td data-label="Тип" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{type}</td>
			<td data-label="Стоимость" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{amount}</td>
			<td data-label="До окончания" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{time_end}</td>
			<td data-label="Клики" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{count}</td>
			<td data-label="Списано" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{spent}</td>
			<td data-label="Статус" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{status}</td>
			<td data-label="" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{button}</td>
		</tr>
		{{ENDFOR:AD}}
	{{ELSE:NOAD}}
		<tr>
			<td colspan="9" class="font_size_14 font_weight_700 text_align_c padding_10">У вас пока нет ни одного объявления</td>
		</tr>
	{{ENDIF:NOAD}}
