<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">УПРАВЛЕНИЕ ЮЗЕРАМИ</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_page_input1">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c">
					Найти пользователя
					<form class="form_content_def" onsubmit="panel.moder.newusers.search(this);return false;">
						<select name="method">
							<option value="login">По логину</option>
							<option value="skype">По skype</option>
							<option value="phone">По телефону</option>
						</select>
						<input type="text" name="query" maxlength="16" style="margin-left: 10px;" />
						<button class="btn btn-primary" name="button">Искать</button>
					</form>
				</td>
			</tr>
			</tbody>
		</table>

		<div id="admin_userSearchResult"></div>

		{moderList}

		<a name="head_panel.moder.newusers.userlist.get"></a>
	</div>
</div>


<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">ПОСЛЕДНИЕ ЗАРЕГИСТРИРОВАВШИЕСЯ</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 80px;" class="font_size_12 padding_l_5 padding_r_5 text_align_c">Дата регистрации</td>
				<td style="width: 110px;" class="font_size_12 padding_l_5 padding_r_5">Логин</td>
				<td style="width: 150px;" class="font_size_12 padding_l_5 padding_r_5">Email</td>
				<td style="width: 150px;" class="font_size_12 padding_l_5 padding_r_5">ФИО</td>
				<td style="width: 80px;" class="font_size_12 padding_l_5 padding_r_5 text_align_c">Посл. активность</td>
				<td style="width: 110px;" class="font_size_12 padding_l_5 padding_r_5 text_align_c">Skype</td>
				<td class="font_size_12 padding_l_5 padding_r_5 text_align_c">Сайт</td>
			</tr>
			</thead>
			<tbody id="newusers_userlist">
			</tbody>
		</table>
	</div>
</div>