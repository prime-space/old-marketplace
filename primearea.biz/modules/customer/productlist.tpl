{{IF:NOPRODUCT}}
	{{FOR:PRODUCTS}}
		<tr>
			<td class="padding_10 vertical_align"><a href="/customer/{i}/{h}/">{name}</a></td>
			<td class="padding_10 text_align_c vertical_align">{message_icon}</td>
			<td class="padding_10 text_align_c vertical_align">{date}</td>
			<td class="padding_10 text_align_c vertical_align" style="color: #2ecc71;">{price}</td>
		</tr>
	{{ENDFOR:PRODUCTS}}
{{ELSE:NOPRODUCT}}<tr><td colspan="4" class="padding_10 text_align_c vertical_align">У вас пока нет ни одной оплаченной покупки</td></tr>{{ENDIF:NOPRODUCT}}