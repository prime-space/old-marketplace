<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">ТОВАРЫ</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_page_input1">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c">
					Введите id товара
					<form class="form_content_def" onsubmit="panel.moder.newproducts.search(this);return false;">
						<input type="text" name="id" maxlength="11">
						<button class="btn btn-success" name="button">Найти</button>
					</form>
					<div id="newproducts_search_error"></div>
				</td>
			</tr>
			</tbody>
		</table>
		<div id="admin_productSearchResult"></div>
	</div>
</div>