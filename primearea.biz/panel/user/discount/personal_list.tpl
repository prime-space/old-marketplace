{{IF:NOLIST}}
	{{FOR:LIST}}
		<tr>
			<td class="padding_10 vertical_align">{email}</td>
			<td class="text_align_c padding_10 vertical_align">{percent}</td>
			<td class="text_align_c padding_10 vertical_align">{money}</td>
			<td class="text_align_c padding_10 vertical_align"><a class="btn btn-danger btn-sm" onclick="panel.user.discount.personal.del({id}, this);">удалить</a></td>
		</tr>			
	{{ENDFOR:LIST}}
{{ELSE:NOLIST}}
		<tr>
			<td colspan="4" class="font_size_14 font_weight_700 text_align_c padding_10">Создайте группу</td>
		</tr>
{{ENDIF:NOLIST}}