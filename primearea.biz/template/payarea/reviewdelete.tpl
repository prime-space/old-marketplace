
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">УДАЛЕНИЕ ОТЗЫВОВ</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_page_input1">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c">
					Введите id-отзыва
					<form class="form_content_def" onsubmit="panel.moder.reviewdelete.search(this);return false">
						<input type="text" maxlength="8" name="id">
						<button class="btn btn-success" name="button">Найти</button>
					</form>
					<div id="reviewdelete_search_error"></div>
				</td>
			</tr>
			<tr>
				<td id="admin_reviewDeleteResult">
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

