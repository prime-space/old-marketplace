{{SWITCH:sales}}
	{{CASE:MAIN}}
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Бухгалтерия</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table_page">
					<thead>
					<tr>
						<td>Статистика продаж:</td>
					</tr>
					</thead>
					<tbody>
					<tr class="tr-white">
						<td class="padding_10 text_align_c vertical_align">
							<div class="chartPeriod">
								<div class="btn btn-info btn-sm active">Сегодня</div>
								<div class="btn btn-primary btn-sm">Вчера</div>
								<div class="btn btn-primary btn-sm">Неделя</div>
								<div class="btn btn-primary btn-sm">Месяц</div>
								<div class="btn btn-primary btn-sm">Квартал</div>
								<div class="btn btn-primary btn-sm">Год</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="padding_10 text_align_c">
							<div id="salesCharts">
								<div id="salesGraph"></div>
								<div id="reviewsGraph" class="col-md-6" style="margin-top:15px;vertical-align:top;"></div>
								<div id="productsGraph" class="col-md-6" style="margin-top:15px;vertical-align:top;"></div>
							</div>
						</td>
					</tr>
					<tr class="tr-white">
						<td class="padding_0"><div id="panel_user_blacklist_add_info"></div></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>


		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">ПРОДАЖИ</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table_page table-responsive tr-border">
					<tbody>
					<tr style="background:#fff;">
						<td colspan="3" id="user_cabinetOrder" style="border-top:none;">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>


	{{ENDCASE:MAIN}}
	{{CASE:ORDER}}
		<div class="pa_middle_c_l_b_head">
			<div class="sprite sprite_user"></div>
			<div class="pa_middle_c_l_b_head_7"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/sales/">Бухгалтерия</a> » Заказ № {i}</div>
		</div>
		<table class="table table-striped table_page table_cabinet_1 td-table">
			<thead>
				<tr>
					<td colspan="2">Счет № {i}</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 250px;" class="font_size_14 font_weight_700 text_align_r padding_10">Товар:</td>
					<td class="font_size_14 padding_10">{name}</td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Выписан:</td>
					<td class="font_size_14 padding_10">{date}</td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Оплачено:</td>
					<td class="font_size_14 padding_10">{price}</td>
				</tr>
				{{IF:PARTNER}}
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Продажа через партнера:</td>
					<td class="font_size_14 padding_10">{partnerLogin}</td>
				</tr>
				{{ELSE:PARTNER}}{{ENDIF:PARTNER}}
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Начисленный рейтинг:</td>
					<td class="font_size_14 padding_10">{rating}</td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Адрес покупателя:</td>
					<td class="font_size_14 padding_10">
						{customer_email}
						<div style="background: #E7E7E7;font-size: 13px;margin-top: 10px;padding: 5px;text-align: center;">Если Вы не хотите, чтобы данный покупатель в дальнейшем совершал у вас покупки, то можете <a target="_blank" href="/panel/blacklist/">добавить</a> его реквизиты в "черный список".</div>
					</td>
				</tr>
				{{IF:CUSTOMER_PURSE}}
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Кошелек плательщика:</td>
					<td class="font_size_14 padding_10"><a href="https://passport.webmoney.ru/asp/CertView.asp?purse={customer_purse}" target="_blank">{customer_purse}</a></td>
				</tr>
				{{ELSE:CUSTOMER_PURSE}}{{ENDIF:CUSTOMER_PURSE}}
				{{IF:DISCOUNT}}
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Скидка:</td>
					<td class="font_size_14 padding_10">{discount}</td>
				</tr>
				{{ELSE:DISCOUNT}}{{ENDIF:DISCOUNT}}
				{{IF:NOPROMOCODEBONUS}}{{ELSE:NOPROMOCODEBONUS}}
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10">Бонус промокод:</td>
					<td class="font_size_14 padding_10">{promocodebonus}</td>
				</tr>
				{{ENDIF:NOPROMOCODEBONUS}}
				<tr>
					<td class="sellgood font_size_14 font_weight_700 text_align_r padding_10">Оплаченный товар:</td>
					<td class="sellgoodrew font_size_14 padding_10">
					{{IF:OBJECT}}
						{object}
					{{ELSE:OBJECT}}
						Скачать: <a href="/download/{i}/" target="_blank">{object}</a>
					{{ENDIF:OBJECT}}
					</td>
				</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page table_cabinet_2">
			<thead>
				<tr>
					<td>Отзыв</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="padding_10">{review}</td>
				</tr>
			</tbody>
		</table>

		<div style="position: relative">
			<form onsubmit="panel.user.cabinet.message(this); return false;">
				<table class="table table-striped table_page table_cabinet_3 td-table">
					<thead>
						<tr>
							<td colspan="3">Вы можете написать покупателю:</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3" class="font_size_14 padding_10">Обращаем внимание, что при возникновении споров администрация торговой площадки рассматривает вашу переписку с покупателем только в рамках данной страницы сервера PRIMEAREA.BIZ. Никакие выдержки из переписки посредством email, icq и т.п. не рассматриваются. Любые просьбы покупателя вести переписку по отзыву через внешние системы обмена сообщениями настоятельно рекомендуем игнорировать!</td>
						</tr>
						<tr>
							<td class="font_size_14 font_weight_700 text_align_r padding_10">Сообщение:</td>
							<td class="font_size_14 padding_10"><textarea class="textarea_def form-control" name="text" maxlength="500" rows="5"></textarea></td>
							<td colspan="3" class="text_align_r padding_10" style="text-align: left;" >
								<div id="message_send_error"  class="form_error"></div>
								<button class="btn btn-small btn-success" name="button">Отправить сообщение</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>

			<div class="filesendsale">
<form style=" margin-left: 13px; margin-bottom: 10px;" onsubmit="panel.user.cabinet.file(this, 'sales'); return false;" method="post" enctype="multipart/form-data">
				<span class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="Макс размер - 1МБ. Поддерживаемые форматы jpg, jpeg, png, zip, rar">?</span><input id="file" type="file" name="fileToUpload">
			    <input style="margin-left: 16px;" class="btn btn-small btn-success" type="submit" value="Отправить файл" name="upload">
			</form>
</div>
		</div>
	
		{{IF:MESSAGES}}
		<div class="middle_lot_fc">
			{{FOR:MESSAGES}}
			<div class="middle_lot_o_one middle_lot_o_one_def">
				<div class="middle_lot_o_t1"><i class="sprite sprite_msg_user"></i> Автор: {person} {author} | Статус: {status}</div>
				<div class="middle_lot_o_t2">{text}</div>
				<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {mdt} <i class="sprite sprite_otz_time"></i> {mtm}</div>
			</div>
			{{ENDFOR:MESSAGES}}
		</div>
		{{ELSE:MESSAGES}}{{ENDIF:MESSAGES}}
	{{ENDCASE:ORDER}}
	{{CASE:ERRORORDER}}<div style="text-align:center;font-weight:bold">Доступ запрещен</div>{{ENDCASE:ERRORORDER}}
{{ENDSWITCH:sales}}