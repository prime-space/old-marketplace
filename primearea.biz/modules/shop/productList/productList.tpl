<div id="recommended_index">
	<div class="pageName"><h1>Рекомендуем</h1></div>
	<div id="recommended_graphic"></div>
	<a name="head_user_recommended.text_list.get"></a>
	<div id="recommended_text" class="recommended_text"></div>
	<br>
</div>
<a name="head_module.main.productlist.get"></a>
<div class="pageName">
	<div style="display:inline-block;"><h1>Все товары</h1></div>
	<div style="display:inline-block;float:right;margin-right:0px;">
		<select id="sortListSel_all_product" onchange="sortList(this.value);" style="padding:4px;">
			<option value="0">По дате поступления</option>
			<option value="1">По количеству продаж</option>
			<option value="2">По цене - возрастанию</option>
			<option value="3">По цене - убыванию</option>
			<option value="4">По алфавиту</option>
		</select>
	</div>
</div>

<div id="productList"></div>
