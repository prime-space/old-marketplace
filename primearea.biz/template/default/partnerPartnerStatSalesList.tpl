{{IF:NOLIST}}
						<tr>
							<td colspan="4" class="font_size_14 font_weight_700 text_align_c padding_10">Продажи отсутствуют</td>
						</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}{{ENDIF:ADD}}
	{{FOR:list}}
						<tr>
							<td class="padding_10 vertical_align"><a href="/product/{productId}/" target="_blank">{name}</a></td>
							<td class="text_align_c padding_10 vertical_align"><a target="_blank" href="/seller/{sellerId}/">{seller}</a></td>
							<td class="text_align_c padding_10 vertical_align">{fee} руб.</td>
							<td class="text_align_c padding_10 vertical_align">{feeDate}</td>
						</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}