<div class="pa_middle_c_l_b_head">
	<div class="sprite sprite_page"></div>
	<div class="pa_middle_c_l_b_head_7"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Привилегии</div>
</div>

<div class="line_recommended_form">
	<div onclick="user_recommended.change_type('default');" data-recommended-type-btn="default" class="btn btn-danger btn-recommended active">Платные объявление</div>
	<div class="line_recommended">
		<div class="line_recommended_h"><div class="line_recommended_h2"></div></div>
		<div class="line_recommended_v" style="height: 0px;"></div>
	</div>
	<div onclick="user_recommended.change_type('privileges');" data-recommended-type-btn="privileges" class="btn btn-danger btn-recommended">Платные привилегии</div>
</div>

<div data-type="default">
	<table class="table table-striped table_page">
		<tbody>
			<tr>
				<td style="display: none;" class="font_size_14 font_weight_700 padding_10" id="user_recommended_success_add_ad">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Объявление успешно добавлено. Добавить <a href="/panel/recommended/">еще одно</a></span>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<form id="user_recommended_order_form" onsubmit="user_recommended.create_ad(this); return false;">

						<div class="info_orang_form alert alert-success">
							<span class="span_info_orang"><i>!</i></span>
							<span class="span_text_b"></span><span class="span_text">
								Появилась возможность размещать свой товар в ТОП. Это означает, что Ваш товар всегда будет на первых местах вверху страницы соответствующего раздела сайта. Помещение в «рекомендуемые» является платной услугой. Разделено на два типа - графическое и текстовое. Для подробного описание, нажмите на интересующий тип обьявления. В случае блокировки вашего аккаунта или снятия товара с продажи, оплата за размещение товара в «рекомендуемые» не возвращается!
							</span>
						</div>

		<h3>Тип объявления:</h3>


						<div class="line_recommended_form">
							<div onclick="user_recommended.change_type_ad('text');" data-recommended-btn="text" class="btn btn-danger btn-recommended active">Текстовое объявление</div>
							<div class="line_recommended">
								<div class="line_recommended_h" style="    top: 18px;"><div class="line_recommended_h2"></div></div>
								<div class="line_recommended_v"></div>
							</div>
							<div onclick="user_recommended.change_type_ad('graphic');" data-recommended-btn="graphic" class="btn btn-danger btn-recommended">Графическое объявление</div>
						</div>

						<div data-recommended-content="text" class="recommended_tabs active">
							<img class="recommended_img" src="/stylisation/images/recommended_text_info_new.png">
							<div class="info_recommended_form">
								Вы оплачиваете только переходы пользователей на ваш товар и сами решаете, сколько платить. Причем чем больше размер оплаты за "уникальный клик", тем выше ваш товар будет стоять на главной странице, а также на страницах категорий.<br>
								Выберите товар из списка в выпадающем меню формы. Затем введите требуемую сумму и нажмите кнопку "заказать". Указанная сумма автоматически снимается с вашего личного счета. В случае нулевого остатка на нем вам необходимо <a href="/panel/addmoney/" target="_blank">пополнить его</a>
							</div>

						</div>

						<div data-recommended-content="graphic" class="recommended_tabs">
							<img class="recommended_img" src="/stylisation/images/recommended_graphic_info_new.png">
							<div class="info_recommended_form">
								Графическое обьявление включает в себя шесть позиций. В случае размещения нескольких объявлений в ТОП на главной странице, а также на страницах категорий, то рандомно показывается шесть позиций. Следует учитывать нюанс, что рандомный алгоритм предполагает случайный выбор очередности показа. Вы оплачиваете только за срок размещения(время) товара.<br>
								Выберите товар и срок размещения нажав [+] в форме. Затем вам подсчитают требуемую сумму и нажмите кнопку "заказать". Подсчитанная сумма автоматически снимается с вашего личного счета. В случае нулевого остатка на нем вам необходимо <a href="/panel/addmoney/" target="_blank">пополнить его</a>
							</div>

						</div>


		<table class="table table_page table_span_bg">
			<tbody>
				<tr>
					<td style="width: 240px;" class="text_align_c">

						<table class="table table_page table_span_bg">
							<tbody>
								<tr>
									<td class="text_align_c vertical_align recommended-selector">
										<span class="span_bgR">
											<select name="product" style="font-size:18px;width:300px;">
												<option value='0'>Выбрать...</option>
												{{FOR:PRODUCT_SELECT}}<option value="{id}">{name}</option>{{ENDFOR:PRODUCT_SELECT}}
											</select>
										</span>
									</td>
								</tr>
								<tr>
									<td class="text_align_c">Товар</td>
								</tr>
							</tbody>
						</table>

					</td>
					<td colspan="2">

						<div class="mobil-recommend-block">
						<table class="table table_page table_tabs table_span_bg active table_page_input1" data-recommended-content="text">
							<tbody>
								<tr>
									<td style="width: 50%;" class="text_align_c vertical_align">
										<span class="span_bgR">
											<input type="text" maxlength="5" name="amount_click_text_ad" onclick="select(this);" onchange="user_recommended.change_price_click_text_ad();" style="font-size:18px;width:70px;" value="{price_click}"> руб.
										</span>
									</td>
									<td style="width: 50%;" class="text_align_c vertical_align">
										<span class="span_bgR">
											<img src="/stylisation/images/refresh.png" style="width:14px;cursor:pointer;" onclick="user_recommended.refresh_wmrbal(this);">
											<span id="user_recommended_wmrbal_graphic_ad">{wmrbal}</span> руб.
										</span>
									</td>
								</tr>
								<tr>
									<td class="text_align_c">Цена за переход<div>минимально: <span id="user_recommended_amount_click_min_text_ad">{price_click}</span> руб.</div></td>
									<td class="text_align_c">На вашем счету</td>
								</tr>
							</tbody>
						</table>
						</div>

						<div class="mobil-recommend-block">
							<table class="table table_page table_tabs table_span_bg table_page_input1" data-recommended-content="graphic">
								<tbody>
									<tr>
										<td class="text_align_c vertical_align">
											<span class="span_bgR">
												<div style="display:inline-block;vertical-align:middle;"><img src="/stylisation/images/minus.png" style="width:14px;cursor:pointer;" onclick="user_recommended.change_duration_placement_graphic_ad('minus');"></div>
												<input type="text" style="text-align:center;width:40px;" value="1" maxlength="4" name="duration_placement_graphic_field_ad" onclick="select(this);" oninput="user_recommended.change_duration_placement_graphic_ad('input');">
												<div style="display:inline-block;vertical-align:middle;"><img src="/stylisation/images/plus.png" style="width:14px;cursor:pointer;" onclick="user_recommended.change_duration_placement_graphic_ad('plus');"></div>
											</span>
										</td>
										<td  class="text_align_c vertical_align">
											<span class="span_bgR">
												<span id="user_recommended_price_day_graphic_ad">{price_day}</span> руб.
											</span>
										</td>
										<td class="text_align_c vertical_align">
											<span class="span_bgR">
												<span id="user_recommended_amount_graphic_ad">{price_day}</span> руб.
											</span>
										</td>
										<td class="text_align_c vertical_align">
											<span class="span_bgR">
												<img src="/stylisation/images/refresh.png" style="width:14px;cursor:pointer;" onclick="user_recommended.refresh_wmrbal(this);">
												<span id="user_recommended_wmrbal_graphic_ad">{wmrbal}</span> руб.
											</span>
										</td>
										<td class="text_align_c vertical_align">
											<span class="span_bgR">
												<span id="user_recommended_after_paid_graphic_ad"></span> руб.
											</span>
										</td>
									</tr>
									<tr>
										<td class="text_align_c">Срок размещения (дней)</td>
										<td class="text_align_c">Цена за сутки</td>
										<td class="text_align_c">Итого</td>
										<td class="text_align_c">На вашем счету</td>
										<td class="text_align_c">После оплаты</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="padding_10 vertical_align">
						<button name="button" class="btn btn-success">Заказать</button>
					</td>
					<td class="text_align_c padding_10 vertical_align">
						<div class="form_error" id="user_recommended_order_form_error"></div>
					</td>
					<td style="width: 100px;" class="text_align_r padding_10 vertical_align">
						<a href="/panel/addmoney/" class="btn btn-info" target="_blank">Пополнить</a>
					</td>
				</tr>
			</tbody>
		</table>


	</form>

	<a name="head_user_recommended.list.get"></a>

	<table class="table table-striped table_page table-responsive tr-border">
		<thead>
			<tr>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Дата</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Товар</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Тип</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Стоимость</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">До окончания</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Клики</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Списано</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Статус</td>
				<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align"></td>
			</tr>
		</thead>
		<tbody  id="user_recommended_list_ad_listing">
		</tbody>
	</table>
</div>

<br>
<div data-type="privileges" style="display:none">
	
	<div style="display:none" id="current_priv" data-type="{priv}" data-until="{until}"></div>
	
	
	<div class="mainpriv">
		
		<div class="linebackform">
		<div class="info_orang_form alert alert-success">
							<span class="span_info_orang"><i>!</i></span>
							<span class="span_text_b"></span><span class="span_text" style="font-size: 13px;">
								Возьми PRO SELLER аккаунт, чтобы найти своего покупателя быстрее!
С PRO аккаунтом твой товар станет заметней и привлечет больше клиентов. Так же ты получишь доступ ко многим  преимуществам. Услуга приобретается на месяц.
							</span>
						</div>
		</div>
	 
	 <div class="mytariff" style="display:{active_priv2}">
		 <div class="mytariffname">{priv_text_1}</div>
		 <div class="mytarriftext">{priv_text_2}</div>
		 <div class="mytarriftext">{priv_text_3}</div>
		 <div class="mytarriftext">{priv_text_4}</div>
		 <div class="mytarriftext">{priv_text_5}</div>
	 </div>
	 
	 <div class="saleblockprivone" id="priv_1">
		 <div class="headpriv">Базовый</div>
		 <div class="pricepriv">{priv_amount_1} Р.</div>
		 <div class="pricelinepriv"></div>
		 <ul class="descpriv">
			 <li>Товар выставляется без модерации</li>
		 </ul>
		 <div class="buttonbuypriv btn btn-success" data-amount="{priv_amount_1}" onclick="panel.recommended.buy(this);return false;">Приобрести<div class="proselbuy">PRO SELLER аккаунт</div></div>
	 </div>
	 
	 <div class="saleblockpriv" id="priv_2">
		 <div class="headpriv">Нейтральный</div>
		 <div class="pricepriv">{priv_amount_2} Р.</div>
		 <div class="pricelinepriv"></div>
		 <ul class="descpriv">
			 <li>Товар выставляется без модерации</li>
			 <li >Поднятие (количество {up_count_2}) в вверх товаров, каждые {up_interval_2} дня в раздел "Весь товар".</li>
			 <li>Товар будет выделен синим цветом</li>
		 </ul>
		 <div class="buttonbuypriv btn btn-success" data-amount="{priv_amount_2}" onclick="panel.recommended.buy(this);return false;">Приобрести<div class="proselbuy">PRO SELLER аккаунт</div></div>
	 </div>
	 
	 <div class="saleblockpriv" id="priv_3">
		 <div id="triangle-topleft"></div>
		 <div class="headprivsale">Независимый</div>
		 <div class="pricepriv">{priv_amount_3} Р.<span><s>1000 Р.</s></span></div>
		 <div class="pricelinepriv"></div>
		 <ul class="descpriv">
			 <li>Товар выставляется без модерации</li>
			 <li >Поднятие (количество {up_count_3}) в вверх товаров, каждые {up_interval_3} дня в раздел "Весь товар".</li>
			 <li>Товар будет выделен синим цветом</li>
			 <li>Удержание средств системой составит - {com_day_3}ч.</li>
		 </ul>
		 <div class="buttonbuypriv btn btn-success" data-amount="{amount_3_real}" onclick="panel.recommended.buy(this);return false;">Приобрести<div class="proselbuy">PRO SELLER аккаунт</div></div>
	 </div>
	 
	 <div class="saleblockpriv" id="priv_4">
		 <div class="headpriv">Профессионал</div>
		 <div class="pricepriv">{priv_amount_4} Р.</div>
		 <div class="pricelinepriv"></div>
		 <ul class="descpriv">
			 <li>Товар выставляется без модерации</li>
			 <li >Поднятие (количество {up_count_4}) в вверх товаров, каждые {up_interval_4} дня в раздел "Весь товар".</li>
			 <li>Товар будет выделен синим цветом</li>
			 <li>Удержание средств системой составит - {com_day_4}ч.</li>
			 <li>Комиссия системы составит {system_com_4}%</li>
		 </ul>
		 <div class="buttonbuypriv btn btn-success" data-amount="{priv_amount_4}" onclick="panel.recommended.buy(this);return false;">Приобрести<div class="proselbuy">PRO SELLER аккаунт</div></div>
	 </div>
	</div>
	
	<div id="errorBlock" style="margin-top: 10px;"></div>
	
	<div style="display:{active_priv}">
		 <div class="topblockname">Поднятие товара вверх</div>
		<table class="table table_page table_span_bg">
			<tbody>
				<tr>
					<td style="width: 30%;" class="text_align_c">

						<table class="table table_page table_span_bg">
							<tbody>
								<tr>
									<td class="text_align_c vertical_align" style="background: #F3F5F8;border: 1px solid  #F3F5F8;">
										<span class="span_bgR">
											<select id="product_up_name" name="product_up" style="font-size:18px;width:300px;">
												<option value='0'>Выбрать...</option>
												{{FOR:PRODUCT_SELECT}}<option value="{id}">{name}</option>{{ENDFOR:PRODUCT_SELECT}}
											</select>
										</span>
									</td>
								</tr>
							</tbody>
						</table>

					</td>
					<td colspan="2">

						<table class="table table_page table_tabs table_span_bg active table_page_input1" >
							<tbody>
								<tr>
									
									<td style="width:40%;background: #33cb98;" class="text_align_c vertical_align">
										<span class="span_bgR" style="background:#33cb98; color:white;font-size: 19px;">
											Доступно поднятий: {up_count}
										</span>
									</td>
								</tr>
								
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td class="padding_10 vertical_align">
						<button onclick="panel.recommended.product_up(this);return false;" name="button" class="btn btn-success">Поднять</button>
					</td>
					<td class="text_align_c padding_10 vertical_align">
						<div class="form_error" id="user_product_up_error"></div>
					</td>
					<td style="width: 100px;" class="text_align_r padding_10 vertical_align">
						
					</td>
				</tr>
			</tbody>
		</table>

		<a name="head_user_product_up.list.get"></a>

		<table class="table table-striped table_page">
			<thead>
				<tr>
					<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Название</td>
					<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Цена</td>
					<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Продаж</td>
					<td class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">Поднято до</td>
				</tr>
			</thead>
			<tbody  id="user_product_up_list">
			</tbody>
		</table>
	</div>

</div>
