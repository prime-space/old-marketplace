{{IF:nolist}}
	{{SWITCH:page}}
		{{CASE:panel}}
			{{FOR:list}}
			<tr>
				<td class="font_weight_700 padding_10 text_align_c">
					{ts}
					<button class="btn btn-success btn-sm" onclick="module.news.getedit(this,{id});">Редактировать</button>
				</td>
				<td class="padding_10">{text} </td>
			</tr>
			{{ENDFOR:list}}
		{{ENDCASE:panel}}
		{{CASE:news}}
			{{FOR:list}}
			<tr>
				<td class="font_weight_700 padding_10 text_align_c">{ts}</td>
				<td class="padding_10">{text}</td>
			</tr>
			{{ENDFOR:list}}
		{{ENDCASE:news}}
	{{ENDSWITCH:page}}
{{ELSE:nolist}}
			<tr>
				<td colspan="2" class="font_size_14 font_weight_700 text_align_c padding_10">Новостей нет</td>
			</tr>
{{ENDIF:nolist}}