<a name="head_panel.user.review.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">Личный кабинет</a> » Отзывы</h3>
	</div>
	<div class="panel-body">
		<div class="orderSearchForm">
			<form id="searchForm" onsubmit="panel.user.review.list.get(0, false, true, true); return false;">
				Найти
				<input type="text" id="glob_orderSearchText" name="glob_orderSearchText" />
				<select id="glob_orderSearchSelect">
					<option value="all">Все</option>
					<option value="email">По email покупателя</option>
					<option value="order">По номеру заказа</option>
					<option value="review">По номеру отзыва</option>
				</select>
				<input class="btn btn-small btn-primary" type="submit" value="Найти">
			</form>
		</div>

		<table class="table table-striped table_page label-margin td-table">
			<thead>
			<tr>
				<td>Товар</td>
				<td class="text_align_c">
					Отзывы
					<label style="background: #ffffff;max-width: 72px;padding:5px;">Все <input onchange="panel.user.review.list.get(0, false, true);" type="radio" name="user_reviewListGood" value="2" checked="checked" /></label>
					<label style="background: #ffffff;padding:5px;position:relative;">Новые <div class="pa_panel_u " style="float: right;position: absolute;left:-33px;top:5px;"><span class="color_red">{newReviewCount}</span></div><input onchange="panel.user.review.list.get(0, false, true);" type="radio" name="user_reviewListGood" value="3" /></label>
					<label style="background: #81E6AC;padding:5px;">Хорошие <input onchange="panel.user.review.list.get(0, false, true);" type="radio" name="user_reviewListGood" value="1" /></label>
					<label style="background: #FFA59C;padding:5px;position:relative;">Плохие <div class="pa_panel_u " style="float: right;position: absolute;left:-23px;top:5px;"><span class="color_red">{badReviewCount}</span></div><input onchange="panel.user.review.list.get(0, false, true);" type="radio" name="user_reviewListGood" value="0" /></label>
				</td>
			</tr>
			</thead>


			<tbody id="panel_user_review_list">
			</tbody>
		</table>
	</div>
</div>