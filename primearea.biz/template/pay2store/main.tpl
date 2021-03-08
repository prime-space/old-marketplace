
<div id="recommended_index">

	<div class="recommended_text"></div>

	<a name="head_user_recommended.text_list.get"></a>
	<div class="pa_middle_c_l_b_head">
		<div class="sprite sprite_ccup"></div>
		<div class="pa_middle_c_l_b_head_1">Рекомендуем<br><span>Добавлено рекомендуемого: <span id="num_user_recommended.text_list.get"></span></span></div>
		<div class="pa_middle_c_l_b_head_4">
			<select class="select_style" id="currentListSel" onchange="currentList(this.value);" style="visibility:hidden;">
				<option value="1">USD</option>
				<option value="2">ГРН</option>
				<option value="3">EUR</option>
				<option value="4" selected="true">РУБ</option>
			</select>
		</div>
	</div>
	<div class="pa_middle_c_l_b_content">
		
			<div id="recommended_text">
			</div>
		
	</div>

</div>

<a name="head_module.main.productlist.get"></a>

<div class="pa_middle_c_l_b_head">
	<div class="sprite sprite_cart"></div>
	<div class="pa_middle_c_l_b_head_5">Весь товар<br><span>Добавлено товаров: <span id="num_module.main.productlist.get"></span></span></div>
	<div class="pa_middle_c_l_b_head_6">
		<select class="select_style" id="sortListSel_all_product" onchange="sortList(this.value);">
			<option value="0">По дате поступления</option>
			<option value="1">По количеству продаж</option>
			<option value="2">По цене - возрастанию</option>
			<option value="3">По цене - убыванию</option>
			<option value="4">По алфавиту</option>
		</select>
	</div>
</div>
<div class="pa_middle_c_l_b_content">
	
		<div id="productList">
			{staticcontent}
		</div>

</div>