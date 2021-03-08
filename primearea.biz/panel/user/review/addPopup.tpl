<form class="popupForm_answer" onsubmit="panel.user.review.answer.send(this);return false;">
	<table class="table table-striped table_page">
		<tbody>
			<tr>
				<td style="padding: 10px;text-align: center;">
					<textarea class="popup_textarea_def form-control" rows="3" name="text"></textarea>
				</td>
			</tr>
			<tr>
				<td style="padding: 10px;">
					<div id="panel_review_answer_info"></div>
					<input type="hidden" name="review_id" value="{id}">
					<button class="btn btn-success btn-sm" name="button">Ответить</button>
				</td>
			</tr>
		</tbody>
	</table>
</form>