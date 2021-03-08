<form onsubmit="module.news.edit(this);return false;">
	<table class="table table-striped table_page">
		<tbody>
			<tr>
				<td class="padding_10 text_align_c">
					<textarea class="textarea_def form-control" rows="10" name="text" style="max-width: 378px;min-width: 378px;width: 378px;">{text}</textarea>
				</td>
			</tr>
			<td class="padding_10 text_align_r">
				<div id="newsEditInfo"></div>
				<button class="btn btn-success" name="button">Сохранить</button>
				<input type="hidden" name="newsId" value="{newsId}">
			</td>
		</tbody>
	</table>
</form>