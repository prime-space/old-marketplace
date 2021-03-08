<div class="pa_profile" id="sellerListF">
	<div class="pa_profile_avatar">{avatar}</div>
	<div class="pa_profile_info">
		<div class="pa_profile_info_p"><i class="sprite sprite_profile_user"></i> <span class="strong">Псевдоним:</span> {login} <span class="proseller">{pro}</span></div>
		<div class="pa_profile_info_p"><i class="sprite sprite_profile_fio"></i> <span class="strong">ФИО:</span> {fio}</div>
		<div class="pa_profile_info_p"><i class="sprite sprite_profile_date"></i> <span class="strong">Дата регистрации:</span> {date}</div>
		<div class="pa_profile_info_p"><i class="sprite sprite_profile_skype"></i> <span class="strong">Skype:</span> {skype}</div>
		<div class="pa_profile_info_p"><i class="sprite "></i> <span class="strong">Телефон:</span> {phone}</div>
		<div class="pa_profile_info_p"><i class="sprite "></i> <span class="strong">Сайт:</span> {site}</div>
	</div>
	<div class="pa_profile_rs">
		<div class="pa_profile_rs_1">
			<div class="a_profile_rs_1_p"><i class="sprite sprite_profile_wmid"></i> <span class="strong">WMID:</span> {wm}</div>
			<div class="a_profile_rs_1_p"><i class="sprite sprite_profile_star"></i> <span class="strong">Рейтинг:</span> <span class="color_blye">{rating}</span> <span class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="Рейтинг является основным индикатором репутации продавца. На рейтинг могут влиять только непосредственные покупатели, путем написания отзывов о купленном товаре.">?</span></div>
			<div class="a_profile_rs_1_p"><i class="sprite sprite_profile_cart"></i> <span class="strong">Продано товаров:</span> <span class="color_green">{rows_sale}</span></div>
		</div>
		<div class="pa_profile_rs_2">
			<div class="btn" onclick="module.seller.reviewlist.get(0, false, true);">Отзывы о товарах</div>
			<div class="btn" onclick="module.seller.productlist.get(0, false, true);">Товары продавца</div>
			 <div class="btn"><a href="/contact/" target="_blank">Пожаловаться</a></div>	
		</div>
	</div>
</div>

<div class="pa_user_lot">
	<a name="head_module.seller.productlist.get"></a>
	<a name="head_module.seller.reviewlist.get"></a>
	<div class="pa_middle_c_l_b_head">
		<div class="sprite sprite_bril"></div>
		<div class="pa_middle_c_l_b_head_1">Товары продавца<br><span>Добавлено на продажу: <span id="num_module.seller.productlist.get"></span></div>
		<div class="pa_middle_c_l_b_head_4">
			<select class="select_style" id="currentListSel" onchange="currentList(this.value);">
				<option value="1">USD</option>
				<option value="2">ГРН</option>
				<option value="3">EUR</option>
				<option value="4" selected="true">РУБ</option>
			</select>
		</div>
		<div class="pa_middle_c_l_b_head_6">
			<select class="select_style" id="sortListSel" onchange="sortList(this.value);">
				<option value="0">По дате поступления</option>
				<option value="1">По количеству продаж</option>
				<option value="2">По цене - возрастанию</option>
				<option value="3">По цене - убыванию</option>
				<option value="4">По алфавиту</option>
			</select>
		</div>
	</div>
	<div class="pa_middle_c_l_b_content" id="sellerShow_list">
		<table class="table table-striped table_lot">
			<thead>
				<tr>
					<th class="table_name">Наименование</th>
					<th class="table_discount"></th>
					<th class="table_seller">Продавец</th>
					<th class="table_rating">Рейтинг</th>
					<th class="table_sales">Продаж</th>
					<th class="table_price">Цена товара</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

</div>