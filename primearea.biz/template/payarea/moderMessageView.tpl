{{SWITCH:MAIN}}
	{{CASE:LIST}}
		<a name="head_panel.moder.messageview.list.get"></a>
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">ПРОСМОТР СООБЩЕНИЙ</h3>
			</div>
			<div class="panel-body">
				<table class="table table-striped table_page table_page_input1">
					<tbody>
					<tr>
						<td class="padding_10 text_align_c">
							Найти по номеру заказа
							<form class="form_content_def" onsubmit="panel.moder.messageview.list.get(0, false, true);return false;">
								<input type="text" name="order_id" id="messageview_search_order_id">
								<input class="btn btn-primary" type="submit" value="Найти">
							</form>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>


		<div class="panel panel-headline">
			<div class="panel-body">
				<table class="table table-striped table_page">
					<thead>
					<tr>
						<td style="width: 80px;" class="padding_10 text_align_c">Счет №</td>
						<td class="padding_10">Товар</td>
						<td style="width: 90px;" class="padding_10 text_align_c">Сообщений</td>
						<td style="width: 140px;" class="padding_10 text_align_c">Дата сообщения</td>
					</tr>
					</thead>
					<tbody id="messageview_list">
					</tbody>
				</table>
			</div>
		</div>

		
	{{ENDCASE:LIST}}
	{{CASE:MESSAGE}}
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="/panel/messageview/">ПРОСМОТР СООБЩЕНИЙ</a> » Счет № {order_id}</h3>
			</div>
			<div class="panel-body">

				<table class="table table-striped table_page">
					<tbody>
					<tr>
						<td style="width: 150px;" class="font_size_14 font_weight_700 padding_10 text_align_r">Название товара</td>
						<td class="padding_10">{product_name}</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Оплаченный товар</td>
						<td class="padding_10">
							{{IF:OBJECT}}{object}{{ELSE:OBJECT}}Скачать: <a href="download.php?i={order_id}" target="_blank">{object}</a>{{ENDIF:OBJECT}}
						</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Выписан</td>
						<td class="padding_10">
							{date_order}
						</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Оплачено</td>
						<td class="padding_10">
							{price} руб.
						</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Начисленный рейтинг</td>
						<td class="padding_10">
							{rating}
						</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Адрес покупателя</td>
						<td class="padding_10">
							{email}
						</td>
					</tr>
					<tr>
						<td class="font_size_14 font_weight_700 padding_10 text_align_r">Скидка</td>
						<td class="padding_10">
							{discount}
						</td>
					</tr>
					</tbody>
				</table>

				<table class="table table-striped table_page">
					<thead>
					<tr>
						<td>Отзыв</td>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td class="padding_10">
							{review}
						</td>
					</tr>
					</tbody>
				</table>

				<table class="table table-striped table_page">
					<thead>
					<tr>
						<td style="width: 110px;" class="text_align_c">Дата</td>
						<td style="width: 100px;" class="text_align_c">Автор</td>
						<td style="width: 80px;" class="text_align_c">Статус</td>
						<td>Сообщение</td>
					</tr>
					</thead>
					<tbody>
					{{IF:MESSAGES}}
					{{FOR:MESSAGES}}
					<tr>
						<td class="text_align_c padding_10">{date}</td>
						<td class="text_align_c padding_10">{person} {author}</td>
						<td class="text_align_c padding_10">{status}</td>
						<td class="padding_5"><span class="span_gray_bg">{text}</span></td>
					</tr>
					{{ENDFOR:MESSAGES}}
					{{ELSE:MESSAGES}}
					<tr>
						<td colspan="4" class="font_size_14 font_weight_700 text_align_c padding_10">Ни одного сообщения не найдено</td>
					</tr>
					{{ENDIF:MESSAGES}}
					</tbody>
				</table>

			</div>
		</div>



	{{ENDCASE:MESSAGE}}
{{ENDSWITCH:MAIN}}