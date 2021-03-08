<form onsubmit="panel.admin.bookkeeping.completeRequest.action(this);return false;">
	<table class="table table-striped table_page_popup">
		<tbody>
			{request}
			<tr>
				<td style="width: 120px;" class="font_weight_700 vertical_align text_align_r padding_10">Протекция</td>
				<td class="vertical_align padding_10 text_align_l">
					<input type="text" name="code" maxlength="16">
					<label style="margin-top: 5px;">Без протекции <input type="checkbox" name="protect"></label>
				</td>
			</tr>
			<tr>
				<td colspan="4" class="vertical_align text_align_r padding_10">
					<div id="panel_admin_bookkeeping_completeRequest_error"></div>
					<button class="btn btn-sm btn-warning" name="button">Исполнить</button>
					<input type="hidden" name="id" value="{id}">
				</td>
			</tr>
		</tbody>
	</table>
</form>