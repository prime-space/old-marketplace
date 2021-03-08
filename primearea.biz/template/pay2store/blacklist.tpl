
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Черный список</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table_cashout">
			<thead>
			<tr>
				<td colspan="2">Форма добавления в черный список:</td>
			</tr>
			</thead>
			<tbody>
			<tr class="tr-white">
				<td style="width: 300px;" class="font_size_14 font_weight_700 text_align_r padding_10">E-mail:</td>
				<td class="padding_10">
					<form onsubmit="panel.user.blacklist.add(this);return false;" class="form_content_def">
						<input type="text" maxlength="32" name="email">
						<button class="btn btn-success btn-sm" name="button">Добавить</button>
					</form>
				</td>
			</tr>
			<tr class="tr-white">
				<td colspan="2" class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table class="table table-striped table_page table_cashout">
			<thead>
			<tr>
				<td colspan="3">Ниже размещен список покупателей, которым вы запретили приобретать ваши товары:</td>
			</tr>
			</thead>
			<tbody>
			{{IF:NOBLACKLIST}}
			{{FOR:BLACKLIST}}
			<tr>
				<td style="width: 10%;" class="font_weight_700 text_align_r padding_10 vertical_align">E-mail:</td>
				<td class="padding_10 vertical_align">{email}</td>
				<td style="width: 65px;" class="padding_10 vertical_align" id="panel_user_blacklist_del_{blacklist_id}">
					<a class="btn btn-danger btn-sm" onclick="panel.user.blacklist.del({blacklist_id}, '{email}');">удалить</a>
				</td>
			</tr>
			{{ENDFOR:BLACKLIST}}
			{{ELSE:NOBLACKLIST}}<tr><td colspan="3" class="padding_10">Список пуст</td></tr>{{ENDIF:NOBLACKLIST}}
			</tbody>
		</table>
	</div>
</div>

