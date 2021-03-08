<a name="head_module.news.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Управление новостями</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="module.news.add(this);return false;">
			<table class="table table-striped table_page">
				<thead>
				<tr>
					<td class="font_size_12">Форма добавления новостей</td>
				</tr>
				</thead>
				<tbody>
				<tr class="tr-white">
					<td class="padding_10 text_align_c">
						<textarea class="textarea_def form-control" name="text" style="min-height:150px;"></textarea>
					</td>
				</tr>
				<tr class="tr-white">
				<td class="padding_10 text_align_r">
					<div id="newsAddInfo"></div>
					<button class="btn btn-success" name="button">Добавить</button>
				</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>


<div class="panel panel-headline">
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 105px;" class="font_size_12 text_align_c">Дата добавления</td>
				<td class="font_size_12">Новость</td>
			</tr>
			</thead>
			<tbody id="newslist"></tbody>
		</table>
	</div>
</div>

