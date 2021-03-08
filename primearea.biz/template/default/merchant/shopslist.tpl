{{FOR:list}}
	<tr>
		<td class="padding_10 vertical_align"><a onclick="merchant.admin.showshop(this,{mshopId});">{name}</a></td>
		<td class="padding_10 text_align_c vertical_align"><a href="/panel/messages/{userId}/" target="_blank">{userLogin}</a></td>
		<td class="padding_10 text_align_c vertical_align" id="mshopStatus_{mshopId}">{status}</td>
		<td class="padding_0 text_align_c vertical_align">{{IF:checking}}<div><div style="display:inline; padding:5px;margin-right:10px" class="btn btn-success btn-sm" onclick="merchant.admin.acceptshop(this,{mshopId}, 1);">Принять</div><div style="display:inline; padding:5px" class="btn btn-danger btn-sm" onclick="merchant.admin.acceptshop(this,{mshopId}, 0);">Отклонить</div></div>{{ELSE:checking}}{{ENDIF:checking}}</td>
		<td class="padding_0 text_align_c vertical_align"><a href="/merchant/admin/{mshopId}" target="_BLANK"><div class="btn btn-success btn-sm" >Настроить</div></a></td>
	</tr>
{{ENDFOR:list}}