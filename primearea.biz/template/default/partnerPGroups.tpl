<a name="head_panel.user.partner.products.group.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Группы товаров</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_products">
			<thead>
			<tr>
				<td colspan="2">Информация</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="2" class="padding_10">
					Вы находитесь в конструкторе партнерского магазина. При помощи этого конструктора вы можете перенести понравившиеся вам товары на свой сайт и предложить их своим посетителям. Результатом работы данного конструктора будет html-код таблицы, который вам только останется добавить на свою страницу.
					<br /><br />
					Для создания новой группы товаров нажмите кнопку "создать". Для удаления какой-либо группы воспользуйтесь ссылкой "удалить". Для получения html-кода, добавления новых товаров, изменения настроек перейдите в группу.
					<br /><br />
					Помимо переноса списка товаров на свой сайт Вы можете получить партнерскую ссылку на конкретный товар, а затем отправить ее своим друзьям по Email, ICQ или опубликовать на форуме, в блоге или социальной сети. Для получения партнерской ссылки откройте список добавленных товаров из группы и нажмите на иконку "прямая ссылка". Со всех покупок совершенных вашими друзьями, на ваш личный счет вы будете получать комиссионные.
				</td>
			</tr>
			<tr>
				<td style="width: 300px;" class="font_size_14 text_align_r font_weight_700 padding_10 vertical_align">Создать новую группу:</td>
				<td class="padding_10 vertical_align">
					<form onsubmit="panel.user.partner.products.group.add(this);return false;" class="form_content_def">
						<input type="text" name="name">
						<button class="btn btn-success btn-sm" name="button">Создать</button>
					</form>
				</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table class="table table-striped table_page table_products">
			<thead>
			<tr>
				<td class="text_align_c">ID</td>
				<td>Название</td>
				<td></td>
			</tr>
			</thead>
			<tbody id="groupList">
			</tbody>
		</table>
	</div>
</div>


