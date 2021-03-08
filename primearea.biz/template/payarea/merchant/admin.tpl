<a name="head_merchant.operation.list"></a>

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Администрирование Мерчанта</h3>
	</div>
	<div class="panel-body">
		<div class="pa_middle_c_l_b_head_6"><a href="/merchant/docs/" target="_blank" class="btn btn-info btn-lg">Документация</a></div>

		<table class="table table_page">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c">
					<a href="/merchant/admin/" class="btn btn-primary" style="width: 20%;">Магазины</a>
					{{IF:moder}}
					<a href="/merchant/admin/feeedit/" class="btn btn-primary" style="width: 20%;">Комиссии</a>
					<a href="/merchant/admin/statistic/" class="btn btn-primary" style="width: 20%;">График поступлений</a>
					{{ELSE:moder}}
					{{ENDIF:moder}}
					<a href="/merchant/admin/orders/" class="btn btn-primary" style="width: 20%;">Заказы</a>
				</td>
			</tr>
			</tbody>
		</table>

		{{SWITCH:page}}
		{{CASE:shops}}
		<div class="orderSearchForm">
			<form id="searchForm" onsubmit="merchant.admin.shopslist.get(0, false, true); return false;">


				<div>
					<label style="width:100px" for="id">Логин</label>
					<input type="text" id="login" name="login" />
				</div>

				<div style="margin: 20px 0">
					<div style="display:inline-block">
						<label style="width:100px" for="status">Статус</label>
						<select id="status" name="status">
							<option value="0">Все</option>
							<option value="checking">Проверяется</option>
							<option value="new">Настраивается</option>
							<option value="accepted">Принят</option>
						</select>
					</div>

				</div>

				<input class="btn btn-small btn-primary" type="submit" value="Найти">
			</form>
		</div>

		<table class="table table-striped table_page merchantfeeedit">
			<thead>
			<tr>
				<td>Название</td>
				<td class="text_align_c" style="width: 120px;">Пользователь</td>
				<td class="text_align_c" style="width: 100px;">Статус</td>
				<td style="width: 50px;">Операции</td>
				<td class="text_align_c" style="width: 100px;">Индивидуальные настройки</td>
			</tr>
			</thead>
			<tbody id="list"></tbody>
		</table>
		{{ENDCASE:shops}}
		{{CASE:feeedit}}
		<form onsubmit="merchant.admin.feeedit(this);return false;">
			<table class="table table-striped table_page table_page_input1">
				<thead>
				<tr>
					<td>Название</td>
					<td class="text_align_c" style="width: 80px;">Комиссия</td>
					<td class="text_align_c" style="width: 120px;">Задействован</td>
				</tr>
				</thead>
				<tbody>
				{{FOR:list}}
				<tr>
					<td class="padding_10 vertical_align">{name}</td>
					<td class="padding_10 text_align_c vertical_align">
						<input type="text" name="sys_{id}_fee" value="{fee}" maxlength="5" style="text-align: center;width: 50px;"> %
					</td>
					<td class="padding_10 text_align_c vertical_align">
						<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="sys_{id}_enabled" {enabled}></label>
					</td>
				</tr>
				{{ENDFOR:list}}
				<tr>
					<td colspan="3" class="padding_10 text_align_r">
						<div data-name="info" id="info" style="text-align: center;"></div>
						<button class="btn btn-success btn-sm" name="button">Сохранить</button>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
		{{ENDCASE:feeedit}}

		{{CASE:statistic}}


		<div class="pa_middle_c_l_b_head no_radius_border">
			<div class="sprite sprite_page"></div>
			<div class="pa_middle_c_l_b_head_7">СТАТИСТИКА</div>
		</div>

		<table class="table table-striped table_page table_page_input1">
			<tbody>
			<tr>
				<td colspan="2" class="padding_10 text_align_c vertical_align">
					<canvas id="example" style="width:888px;height:450px;"></canvas>
				</td>
			</tr>
			<tr>
				<td style="width: 90px;" class="font_weight_700 padding_10 text_align_r vertical_align">Интервал за:</td>
				<td class="padding_10 vertical_align">
					<label>Дни <input type="radio" checked="checked" name="admin_money_graph_period_radio" id="admin_money_graph_period_radio"  onchange="panel.admin.bookkeeping.graph(true);"></label>
					<label>Месяцы <input type="radio" name="admin_money_graph_period_radio" onchange="panel.admin.bookkeeping.graph(true);"></label>
				</td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 text_align_r vertical_align">Показывать:</td>
				<td class="padding_10 vertical_align">
					<label>Продажи <input checked="checked" type="checkbox" id="admin_money_graph_sales_checkbox" onchange="panel.admin.bookkeeping.graph(true);"></label>
					<label>Прибыль с продаж <input type="checkbox" id="admin_money_graph_sales_profit_checkbox" onchange="panel.admin.bookkeeping.graph(true);"></label>
					<!-- <label>Общая прибыль <input type="checkbox" id="admin_money_graph_profit_checkbox" onchange="panel.admin.bookkeeping.graph(true);" ></label> -->
				</td>
			</tr>
			</tbody>
		</table>



		{{ENDCASE:statistic}}


		{{CASE:orders}}

		<a name="head_panel.admin.order.list.get"></a>

		<div class="pa_middle_c_l_b_head">
			<div class="sprite sprite_page"></div>
			<div class="pa_middle_c_l_b_head_7">ЗАКАЗЫ</div>
		</div>

		<div class="orderSearchForm">
			<form onsubmit="panel.admin.order.list.get(0, false, true); return false;">


				<div>
					<label style="width:100px" for="id">Номер счета</label>
					<input type="text" id="id" />
				</div>

				<div style="margin: 20px 0">
					<div style="display:inline-block">
						<label style="width:100px" for="status">Статус</label>
						<select id="status">
							<option value="all">Все</option>
							<option value="success">оплачен</option>
							<option value="cancel">отменен</option>
							<option value="pending">ожидание</option>
						</select>
					</div>

					<div style="display:inline-block">
						<label style="width:100px" for="payed">Способ оплаты</label>
						<select id="payed">
							<option value="all">Все</option>
							{availableMethods}
						</select>
					</div>
				</div>

				<div style="margin-bottom:10px">
					<label style="width:100px" for="date">Дата платежа</label>
					От - <input type="text" id="date1" /> До - <input type="text" id="date2" />
				</div>

				<input class="btn btn-small btn-primary" type="submit" value="Найти">
			</form>
		</div>
		<table class="table table-striped table_page table_page_input1">
			<tbody>
			<tr>
				<td style="width: 150px;" class="padding_10 text_align_r vertical_align">
					<a style="position: absolute;left: 800px;top: 344px;" href="/panel/messages/" target="_blank" class="btn btn-lg btn-danger">Поддержка</a>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td style="width: 260px;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align"></td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Cчет №</td>
				<td style="width: 55px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">PRIME ID №</td>
				<td style="width: 55px;" class="font_size_12 padding_l_5 padding_r_5">Название товара</td>
				<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Цена товара</td>
				<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Статус</td>
				<td style="width: 80px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Дата покупки</td>
				<td style="width: 120px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Доступны после</td>
				<td style="width: 120px;" class="font_size_12 text_align_c padding_l_5 padding_r_5">Уведомление</td>
			</tr>
			</thead>
			<tbody id="panel_admin_order_list">
			</tbody>
		</table>

		{{ENDCASE:order}}

		{{ENDSWITCH:page}}
	</div>
</div>


