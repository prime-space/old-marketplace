{{IF:NOLIST}}
		<tr>
			<td colspan="6" class="font_size_14 font_weight_700 text_align_c padding_10">Добавьте товары</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:list}}
		<tr>
			<td class="font_size_12 padding_10 vertical_align"><a href="/product/{productId}/" target="_blank">{name}</a></td>
			<td style="width: 130px;" class="font_size_12 text_align_c padding_b_10 padding_t_10 vertical_align">{sellerLink}</td>
			<td style="width: 150px;" class="font_size_14 text_align_c vertical_align"><a onclick="panel.user.partner.products.link('{url}');"><img src="/stylisation/images/link32.png" style="vertical-align: top;"></a></td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">{price}</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">{fee}</td>
			<td style="width: 40px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align"><span class="span_stop" onclick="panel.user.partner.products.del({partnerProductId},this)">−</span></td>
		</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}