{{IF:NOLIST}}
		<tr>
			<td colspan="4" class="font_size_14 font_weight_700 text_align_c padding_10">Пока нет ни одного сообщения</td>
		</tr>
{{ELSE:NOLIST}}
	{{FOR:MESSAGES}}
		<tr>
			<td class="text_align_c padding_10">{date}</td>
			<td class="text_align_c padding_10">{author}</td>
			<td class="text_align_c padding_10">{readed}</td>
			<td class="padding_5"><span class="span_gray_bg">{text}</span></td>
		</tr>
	{{ENDFOR:MESSAGES}}
{{ENDIF:NOLIST}}


