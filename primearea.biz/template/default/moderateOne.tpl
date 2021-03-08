
<div class="panel panel-headline">
	<div class="panel-heading">
		<div class="col-md-9">
			<h3 class="panel-title">МОДЕРАЦИЯ</h3>
		</div>
		<div class="col-md-3">
			<a href="/panel/newproducts/" class="btn btn-primary" >Все товары</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="panel-body">
		<div class="info_red_form alert alert-info">
			<span class="span_info_red"><i>!</i></span><span class="span_text_b"></span>
			<span class="span_text">ВНИМАНИЕ!</b> Нажмите на товар, чтобы модерировать.</span>
		</div>
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Id</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Логин продавца</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Название товара</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Категория</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Свойство товара</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Тип товара</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Цена</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Изображение</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Комиссия партнеру</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Описание товара</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Дополнительная информация</td>
				<td  class="font_size_12 text_align_c padding_l_5 padding_r_5">Объекты продаж</td>
			</tr>
			<tr>
				<td class="font_weight_700 vertical_align text_align_r padding_5"><a onclick='panel.moderate(this,true, {id});' style="width: 100px; background-color:rgb(0, 189, 0);"  class="btn btn-primary">Одобрить</a></td>
				<td class="vertical_align padding_5 text_align_l text_url"><a onclick='panel.moderate(this, false, {id});' style="width: 100px;background-color: red"  class="btn btn-primary">Отклонить</a></td>
			</tr>
			</thead>
			<tbody id="panel_admin_moderate_list">
			</tbody>
		</table>
	</div>
</div>