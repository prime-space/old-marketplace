<a name="head_panel.user.myshop.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Мои товары</h3>
	</div>
	<div class="panel-body">
		<div class="info_red_form alert alert-info">
			<span class="span_info_red"><i>!</i></span>
			<span class="span_text_b"></span><span class="span_text">ВНИМАНИЕ! В случае отказа публикации товара, нажмите кнопку статуса публикации, чтобы увидеть причину отказа.</span>
		</div>



<div class="newalert-success hidden-xs">
	<a style="text-decoration:none;" href="/panel/productadd" target="_BLANK">
			<div class="alertgood">+</div>
			<div class="alertname">Добавить новый товар</div>
		</a>
</div>

<div class="newalert-info hidden-xs">
	<a class="noProductSort" onclick="$('#sortListSel').val('5').trigger('refresh');sortList(this); return false;" style="text-decoration:none;" href="#">
			<div class="alertinfogood">{$noProduct}</div>
			<div class="alertinfoname">Нет в наличии</div>
		</a>
</div>
<div class="newalert-warning hidden-xs">
	<div class="alertwarngood">{$product_count}</div>
		<div class="alertwarnname">Все активные товары</div>
</div>

		
		<div class="clear"></div>
	</div>
</div>


<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title" style="width:200px;float:left">Список</h3>
		<div style="text-align:right;">

			<div class="pa_middle_c_l_b_head_10" style="display:inline-block;"><a href="/panel/recommended/" class="btn btn-success ">Рекомендуемое</a></div>
			<div class="pa_middle_c_l_b_head_9" style="display:inline-block;">
				<select class="select_style" id="currentListSel" onchange="currentList(this.value);">
					<option value="1">USD</option>
					<option value="2">ГРН</option>
					<option value="3">EUR</option>
					<option value="4" selected="true">РУБ</option>
				</select>
			</div>
			<div class="pa_middle_c_l_b_head_11" style="display:inline-block;">
				<select class="select_style" id="sortListSel" onchange="sortList(this.value);">
					<option value="0">По дате поступления</option>
					<option value="1">По количеству продаж</option>
					<option value="2">По цене - возрастанию</option>
					<option value="3">По цене - убыванию</option>
					<option value="4">По алфавиту</option>
					<option value="5">Нет в наличии</option>
				</select>
			</div>

		</div>
	</div>
	<div class="panel-body">

		

		<table class="table table-striped table_page table-responsive tr-border">
			<thead>
			<tr>
				<td class="text_align_c padding_l_5 padding_r_5">id</td>
				<td class="text_align_c padding_l_5 padding_r_5">Название</td>
				<td class="text_align_c padding_l_5 padding_r_5">Продаж</td>
				<td class="text_align_c padding_l_5 padding_r_5">Цена</td>
				<td class="text_align_c padding_l_5 padding_r_5 only-mobil">В наличие</td>
				<td class="text_align_r padding_10 td-width-700 nowrap only-computer" style="text-align: center;padding-left: 0px;">
					<span class="btn btn-primary btn-sm thead_disabled" style="margin:0;padding:6px 10px;">Мой магазин</span>
					<span class="btn btn-success btn-sm thead_disabled" style="margin:0;padding:6px 10px;">В наличие</span>
					<span class="btn btn-info btn-sm thead_disabled" style="margin:0;padding:6px 10px;">Добавить</span>
					<span class="btn btn-warning btn-sm thead_disabled" style="margin:0;padding:6px 10px;">Изменить</span>
					<span class="btn btn-danger btn-sm thead_disabled" style="margin:0;padding:6px 10px;">Удалить</span>
					<span class="btn btn-danger btn-sm thead_disabled" style="margin:0;padding:6px 10px;">Публикация</span>

				</td>
			</tr>
			</thead>
			<tbody id="panel_user_myshop_list">

			</tbody>
		</table>

	</div>
</div>
