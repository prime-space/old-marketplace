{{IF:NOLIST}}
		<tr>
			<td colspan="3" class="font_size_14 font_weight_700 text_align_c padding_10">Добавьте товары</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:list}}
		<tr>
			<td class="font_size_14 padding_10 vertical_align"><a href="/product/{productId}/" target="_blank">{name}</a></td>
			<td style="width: 100px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">{price}</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">
				<input type="text" name="product_{productId}" value="{fee}" maxlength="2" style="margin: 0 5px;padding: 5px 10px;text-align: center;width: 50px;">%
			</td>
		</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}
