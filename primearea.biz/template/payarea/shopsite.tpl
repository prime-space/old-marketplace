
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Магазин на сайте</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td colspan="2">Инструкция</td>
			</tr>
			</thead>
			<tbody>
			<tr class="tr-white">
				<td colspan="2" class="padding_10">
					<div class="info_red_form alert alert-info">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Для того что бы перенести магазин на свой сайт добавьте необходимые товары из раздела "Мои товары".</span>
					</div>
				</td>
			</tr>
			<tr>
				<td style="width: 50px;" class="text_align_c font_weight_700 padding_10">SHOP V.1</td>
				<td class="padding_10">
					<div class="bs-callout bs-callout-info">Магазин устанавливается на вашем сайте. Для его работы необходимо создать категории и добавить в них товары, загрузить скрипт на хостинг и произвести настройки.</div>
					<div class="alert alert-success">Новая версия магазина адаптирована под мобильные устройства.<br>
									Посмотреть адаптив можно здесь - <a href="https://vk.com/primearea_biz?w=wall-11565719_7368" target="_blank">Перейти</a>

</div>

					<div class="bs-callout bs-callout-info">
						Готовое решение для магазинов <a href="http://p2case.ru/PRIME%20SHOP%20V.1.zip" download>(Скачать файл)</a>
					</div>
					<div class="alert alert-war">Помощь в установке Skype: info.primearea (также присылайте пожелания/недочеты)</div>

				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>



<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Категории товаров "Мой магазин"</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 10px;" class="text_align_c">#</td>
				<td>Название категории</td>
				<td style="width: 75px;"></td>
				<td style="width: 75px;"></td>
			</tr>
			</thead>
			<tbody id="user_shopsiteCategoryList">
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3" class="text_align_c padding_10 vertical_align" style="background: #4E4E4E;border-top: 0 dashed transparent;color: #ffffff;">
					<div id="category_create_error"></div>
					Добавить категорию:
					<form onsubmit="panel.user.shopsite.category.create(this);return false;" class="form_content_def">
						<input type="text" name="name" style="width:400px;">
						<button class="btn btn-primary btn-sm" name="button">Создать</button>
					</form>
				</td>
				<td class="text_align_c padding_10 vertical_align" style="background: #4E4E4E;border-top: 0 dashed transparent;"><a class="btn btn-success btn-sm" onclick="panel.user.shopsite.product.list.get();return false;">Показать все</a></td>
			</tr>
			</tfoot>
		</table>
	</div>
</div>


<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Список товаров добавленных в "Мой магазин</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 20px;" class="text_align_c">id</td>
				<td>Название</td>
				<td style="width: 190px;" class="text_align_c">Категория</td>
				<td style="width: 55px;" class="text_align_c">Убрать</td>
			</tr>
			</thead>
			<tbody id="user_shopsiteListDiv">
			</tbody>
		</table>
	</div>
</div>