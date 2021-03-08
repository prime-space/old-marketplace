{{IF:NOLIST}}
		<tr>
			<td colspan="4" class="font_size_14 font_weight_700 text_align_c padding_10">У вас нет предложений от продавцов</td>
		</tr>
{{ELSE:NOLIST}}
	{{IF:ADD}}{{ELSE:ADD}}
	{{ENDIF:ADD}}
	{{FOR:list}}
		<tr>
			<td class="font_size_14 padding_10 vertical_align"><a href="/seller/{sellerUserId}/" target="_blank">{sellerLogin}</a></td>
			<td style="width: 150px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">{fee}%</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">
				<div class="btn btn-success btn-sm" onclick="panel.user.partner.become.offers.acceptReject(1,{partnerShipId},this)" style="width: 80px;">Принять</div>
			</td>
			<td style="width: 110px;" class="font_size_14 text_align_c padding_b_10 padding_t_10 vertical_align">
				<div class="btn btn-danger btn-sm" onclick="panel.user.partner.become.offers.acceptReject(0,{partnerShipId},this)" style="width: 80px;">Отказать</div>
			</td>
		</tr>
	{{ENDFOR:list}}
{{ENDIF:NOLIST}}
