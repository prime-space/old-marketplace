<form onsubmit="panel.user.promocodes.edit.codes.save(this); return false;">

	<table class="table table-striped table_page table_page_input1">
		<thead>
			<tr>
				<td>Промо-код</td>
				<td style="width: 120px;" class="text_align_c">Использование</td>
				<td style="width: 442px;" class="text_align_c">Мой комментарий</td>
			</tr>
		</thead>
		<tbody>
		{{FOR:LIST}}
			<tr>
				<td class="padding_10 vertical_align">{code}</td>
				<td class="text_align_c padding_10 vertical_align">{status}</td>
				<td class="text_align_c padding_10 vertical_align"><input type="text" maxlength="64" placeholder="здесь вы можете оставить для себя комментарий" style="width:450px;" name="comment_{promocodes_code_id}" value="{comment}"></td>
			</tr>
		{{ENDFOR:LIST}}
			<tr>
				<td colspan="3" class="padding_0"><div id="panel_user_promocodes_edit_codes_save_error"></div></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="background: #4E4E4E;border-top: 0 dashed transparent;" class="padding_10">
					<a class="btn btn-danger btn-sm" href="/panel/promocodes/">Назад</a>
				</td>
				<td style="background: #4E4E4E;border-top: 0 dashed transparent;" colspan="2" class="text_align_r padding_10">
					<button class="btn btn-success btn-sm" name="button">Сохранить</button>
				</td>
			</tr>
		</tfoot>
	</table>
</form>