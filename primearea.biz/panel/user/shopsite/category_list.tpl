{{IF:NOCATEGORY}}
{{ELSE:NOCATEGORY}}{{ENDIF:NOCATEGORY}}
{{FOR:CATEGORIES}}
	<tr id="productShopsiteAddDelHide{id}">
		<td class="text_align_c padding_10 vertical_align">{i}</td>
		<td class="padding_10 vertical_align">{name}</td>
		<td class="text_align_c padding_10 vertical_align"><a class="btn btn-success btn-sm" onclick="panel.user.shopsite.product.list.get(0, false, true,{id}); return false;">Показать</a></td>
		<td class="text_align_c padding_10 vertical_align"><a class="btn btn-danger btn-sm" onclick="panel.user.shopsite.category.del({id}, this); return false;"><i class="icon-remove icon-white"></i> Удалить</a></td>
	</tr>
{{ENDFOR:CATEGORIES}}