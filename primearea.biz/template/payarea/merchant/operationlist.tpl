{{IF:nolist}}<tr><td colspan="6" class="font_size_14 font_weight_700 text_align_c padding_10">Операции отсутствуют</td></tr>{{ELSE:nolist}}
	{{FOR:list}}
		<tr>
			<td data-label="Счет №" class="text_align_c padding_10">{id}</td>
			<td data-label="PRIME ID №" class="text_align_c padding_10">{paymentId}</td>
			<td data-label="Название товара" class="text_align_c padding_10">{ts}</td>
			<td data-label="Сумма" class="text_align_c padding_10">{amount} руб.</td>
			<td data-label="Статус" class="text_align_c padding_10 {statusclass}" style="text-align: center;">{status}</td>
			<td data-label="Дата" class="text_align_c padding_10">{date}</td>
			<td data-label="Доступны после" class="text_align_c padding_10">{tsr}</td>
			<td data-label="Уведомления" class="text_align_c padding_10">{{IF:notifications}}<a class="shownotif" onclick="merchant.operation.shownotif(this,{paymentId});">&#9660;</a>{{ELSE:notifications}}{{ENDIF:notifications}}</td>
		</tr>
		<tr class="notification">
			<td colspan="8" class="padding_10 {statusclass}"></td>
		</tr>
	{{ENDFOR:list}}
{{ENDIF:nolist}}