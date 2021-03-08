{{FOR:list}}
	<tr>
		<td class="padding_10 text_align_c vertical_align"><a href="/product/{product_id}" target="_BLANK">{product_name}</a></td>
		<td class="padding_10 text_align_c vertical_align"><input type="checkbox" name="hidden[{product_id}]" value="{product_id}" {checked} ></td>
	</tr>
{{ENDFOR:list}}
