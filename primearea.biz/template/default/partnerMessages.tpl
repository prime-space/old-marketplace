<a name="head_panel.user.partner.showMessages.get"></a>
<div class="panel panel-headline">
	<div class="panel-body">
		<form class="HelpListForm" onsubmit="panel.user.partner.sendMessage(this);return false;">
			<table class="table table-striped table_page">
				<tbody>
				<tr>
					<td style="border-top: 0 solid transparent;width: 150px;" class="font_size_14 font_weight_700 text_align_r padding_10">Написать партнеру</td>
					<td style="border-top: 0 solid transparent;" class="padding_10"><textarea class="textarea_def form-control" rows="10" name="text" maxlength="1500"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="text_align_r padding_10">
						<button class="btn btn-sm btn-success btn-sm" name="button">Отправить</button>
						<input type="hidden" name="partnershipId" value="{partnershipId}">
					</td>
				</tr>
				<tr>
					<td colspan="2" class="text_align_r padding_10">
						<div id="sendMessageError"></div>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>







<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Сообщения</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 110px;" class="text_align_c">Дата</td>
				<td style="width: 100px;" class="text_align_c">Автор</td>
				<td style="width: 80px;" class="text_align_c">Статус</td>
				<td>Сообщение</td>
			</tr>
			</thead>
			<tbody id="messagesBlock">
			</tbody>
		</table>
	</div>
</div>
