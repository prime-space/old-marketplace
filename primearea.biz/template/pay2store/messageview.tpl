
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/messages/">Сообщения</a> » Тема: {title}</h3>
	</div>
	<div class="panel-body">
		<form class="HelpListForm" onsubmit="panel.user.messages.answer.send(this);return false;">
			<table class="table table-striped table_page table_page_input1">
				<tbody>
				<tr>
					<td class="padding_10 font_size_14 font_weight_700 text_align_r">Ответ:</td>
					<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="5" name="text" maxlength="1500"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="padding_10 text_align_r">
						<div id="message_answer_error"></div>
						<button class="btn btn-success" name="button">Отправить</button>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="padding_10">{messages}</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>