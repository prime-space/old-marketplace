 {{IF:nomoder}}
 <div class="panel panel-headline">
	 <div class="panel-heading">
		 <h3 class="panel-title">БУХГАЛТЕРИЯ</h3>
	 </div>
	 <div class="panel-body">

<div class="spoiler_1" style="margin:0 0 10px 0">
	<div onclick="showSpoiler(this)" style="" class="btn newbtn-success btn-success">Настройки магазина</div>
	<div class="spoiler" style="display:none">
		<form  onsubmit="panel.admin.bookkeeping.savesettings(this);return false;">
			<table class="table table-striped table_page table_page_input1">
				<tbody>
					<tr>
						<td style="width: 260px;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Процент отчислений в систему:</td>
						<td class="padding_10"><input type="text" maxlength="2" value="{fee}" name="fee"> %</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Время удержания средств:</td>
						<td class="padding_10"><input type="text" maxlength="3" value="{time}" name="time_retention"> часов</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Графическое объявление:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{graphic_ad}" name="graphic_ad_price"> р. сутки</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Текстовое объявление:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{text_ad}" name="text_ad_price"> р. клик</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Автовыплаты:</td>
						<td class="padding_10"><label><input type="checkbox" name="wmx2" {wmx2_checked}></label></td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Комиссия автовыплат:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{wmx2_fee}" name="wmx2_fee"> %</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Автовыплаты на яндекс:</td>
						<td class="padding_10"><label><input type="checkbox" name="yandex_autopayments" {yandex_autopayments_checked}></label></td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Комиссия автовыплат яндекса:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{yandex_autopayments_fee}" name="yandex_autopayments_fee"> %</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Автовыплаты на qiwi:</td>
						<td class="padding_10"><label><input type="checkbox" name="qiwi_autopayments" {qiwi_autopayments_checked}></label></td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Комиссия автовыплат qiwi:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{qiwi_autopayments_fee}" name="qiwi_autopayments_fee"> %</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Мин. рейтинг для публикации товара без модерации:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{rate_moder}" name="rate_moder"> баллов</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Наценка к сумме оплаты через paypal:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{paypal_fee_percent}" name="paypal_fee_percent"> %</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Добавочная стоимость к оплате через paypal:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{paypal_fee_val}" name="paypal_fee_val"> руб</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Комиссия платежей qiwi:</td>
						<td class="padding_10"><input type="text" maxlength="6" value="{qiwi_fee_percent}" name="qiwi_fee_percent"> %</td>
					</tr>
					<tr>
						<td colspan="2" class="padding_10 text_align_r vertical_align">
							<div id="panel_admin_money_change_error"></div>
							<button class="btn btn-sm btn-success" name="button">Сохранить</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>

		 <div class="spoiler_2" style="margin:10px 0">
			 <div onclick="showSpoiler(this)" style="" class="btn newbtn-success btn-success">Настройки подписок</div>
			 <div class="spoiler" style="display:none">
				 <form onsubmit="panel.admin.bookkeeping.savePrivsettings(this);return false;">
					 <table class="table table-striped table_page table_page_input1">
						 <tbody>
						 <tr>
							 <td colspan="2" style="width: 260px;text-align: center;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Блок 1</td>
						 </tr>

						 <tr>
							 <td style="width: 200px;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Цена: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{priv_amount_1}" name="priv_amount_1"> руб</td>
						 </tr>

						 <tr>
							 <td colspan="2" style="width: 260px;text-align: center;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Блок 2</td>
						 </tr>

						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Цена: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{priv_amount_2}" name="priv_amount_2"> руб</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Число поднятия товара: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_count_2}" name="up_count_2"> раз(а)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Через сколько дней: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_interval_2}" name="up_interval_2"> дней(дня)</td>
						 </tr>

						 <tr>
							 <td colspan="2" style="width: 260px;text-align: center;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Блок 3</td>
						 </tr>

						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Цена: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{priv_amount_3}" name="priv_amount_3"> руб</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Число поднятия товара: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_count_3}" name="up_count_3"> раз(а)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Через сколько дней: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_interval_3}" name="up_interval_3"> дней(дня)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Удержание средств системой: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{com_day_3}" name="com_day_3"> часов(часа)</td>
						 </tr>

						 <tr>
							 <td colspan="2" style="width: 260px;text-align: center;" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Блок 4</td>
						 </tr>

						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Цена: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{priv_amount_4}" name="priv_amount_4"> руб</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Число поднятия товара: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_count_4}" name="up_count_4"> раз(а)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Через сколько дней: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{up_interval_4}" name="up_interval_4"> дней(дня)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Удержание средств системой: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{com_day_4}" name="com_day_4"> часов(часа)</td>
						 </tr>
						 <tr>
							 <td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Комиссия системой: </td>
							 <td class="padding_10"><input type="text" maxlength="6" value="{system_com_4}" name="system_com_4"> %</td>
						 </tr>

						 <tr>
							 <td colspan="2" class="padding_10 text_align_r vertical_align">
								 <div id="panel_admin_money_change_error2"></div>
								 <button class="btn btn-sm btn-success btn-success" name="button">Сохранить</button>
							 </td>
						 </tr>
						 </tbody>
					 </table>
				 </form>
			 </div>
		 </div>

		 <div class="spoiler_3" style="margin:10px 0">
			 <div onclick="showSpoiler(this)" style="" class="btn newbtn-success  btn-success">Статистика подписок</div>
			 <div class="spoiler" >
				 <table class="table table-striped table_page">
					 <thead>
					 <tr>
						 <td>Статистика подписок:</td>
					 </tr>
					 </thead>
					 <tbody>

					 <tr>
						 <td colspan="2" class="padding_10 text_align_c">
							 <div id="salesCharts">
								 <div id="salesGraph"></div>
								 <div id="reviewsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;width:100%;max-width:450px;"></div>
								 <div id="productsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;width:100%;max-width:450px;"></div>
							 </div>
						 </td>
					 </tr>
					 <tr>
						 <td class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
					 </tr>
					 </tbody>
				 </table>
				 <table class="table table-striped table_page">
					 <thead>
					 <tr>
						 <td style="width: 110px;" class="text_align_c">Пользователь</td>
						 <td class="text_align_c">Тип</td>
						 <td class="text_align_c">Действителен до</td>
					 </tr>
					 </thead>
					 <tbody id="priv_stat">
					 </tbody>
				 </table>
			 </div>

		 </div>
	 </div>
 </div>




 <div class="panel panel-headline">
	 <div class="panel-heading">
		 <h3 class="panel-title">СТАТИСТИКА</h3>
	 </div>
	 <div class="panel-body">

		 <table class="table table-striped table_page table_page_input1 label-margin">
			 <tbody>
			 <tr class="tr-white">
				 <td colspan="2" class="padding_10 text_align_c vertical_align">
					 <canvas id="example" style="width:100%;height:auto;"></canvas>
				 </td>
			 </tr>
			 <tr>
				 <td style="width:130px;" class="font_weight_700 padding_10 text_align_r vertical_align">Интервал за:</td>
				 <td class="padding_10 vertical_align">
					 <label>Дни <input type="radio" checked="checked" name="admin_money_graph_period_radio" id="admin_money_graph_period_radio" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Месяцы <input type="radio" name="admin_money_graph_period_radio" onchange="panel.admin.bookkeeping.graph();"></label>
				 </td>
			 </tr>
			 <tr>
				 <td class="font_weight_700 padding_10 text_align_r vertical_align">Показывать:</td>
				 <td class="padding_10 vertical_align">
					 <label>Продажи <input type="checkbox" id="admin_money_graph_sales_checkbox" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Прибыль с продаж <input type="checkbox" id="admin_money_graph_sales_profit_checkbox" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Реклама <input type="checkbox" id="admin_money_graph_ad_checkbox" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Текстовая реклама <input type="checkbox" id="admin_money_graph_ad_text_checkbox" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Графическая реклама <input type="checkbox" id="admin_money_graph_ad_graphic_checkbox" onchange="panel.admin.bookkeeping.graph();"></label>
					 <label>Общая прибыль <input type="checkbox" id="admin_money_graph_profit_checkbox" onchange="panel.admin.bookkeeping.graph();" checked="checked"></label>
				 </td>
			 </tr>
			 </tbody>
		 </table>


	 </div>
 </div>

 <a name="head_panel.admin.bookkeeping.wdlist.get"></a>
 <div class="panel panel-headline">
	 <div class="panel-heading">
		 <h3 class="panel-title">СПИСОК ЗАПРОСОВ</h3>
	 </div>
	 <div class="panel-body">
		 {{ELSE:nomoder}}
		 {{ENDIF:nomoder}}

		 <table class="table table-striped table_page">
			 <thead>
			 <tr>
				 <td style="width: 110px;" class="text_align_c">Дата</td>
				 <td class="text_align_c" style="width: 100px;">Логин</td>
				 <td style="width: 80px;" class="text_align_c">Метод</td>
				 <td style="width: 80px;" class="text_align_c">Кошелек</td>
				 <td style="width: 80px;" class="text_align_c">Сумма</td>
				 <td style="width: 100px;" class="text_align_c">Статус</td>
				 <td style="width: 75px;" class="text_align_c">Протекция</td>
				 <td style="width: 80px;" class="text_align_c"></td>
			 </tr>
			 </thead>
			 <tbody id="admin_money_request_list">
			 </tbody>
		 </table>
	 </div>
 </div>
