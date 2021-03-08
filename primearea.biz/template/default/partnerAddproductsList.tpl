{{IF:NOLIST}}
		<tr>
			<td colspan="6" class="font_size_14 font_weight_700 text_align_c padding_10">Нет доступных товаров</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:list}}
		<tr>
			<td class="font_size_14 padding_10"><a href="/product/{productId}/" target="_blank">{name}</a></td>
			<td style="width: 130px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{sellerLink}</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{price}</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{fee}</td>
			<td style="width: 95px;" class="font_size_14 text_align_c padding_b_10 padding_t_10">{sales}</td>
			<td style="width: 40px;" class="font_size_14 text_align_c padding_b_10 padding_t_10"><input type="checkbox" name="products[]" value="{productId}"></td>
		</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}