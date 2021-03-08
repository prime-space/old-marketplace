
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Добавление товара</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.user.myshop.add.send(this);return false;">
			<div class="info_red_form alert alert-info">
				<span class="span_info_red"><i>!</i></span><span class="span_text_b"></span>
				<span class="span_text">ВНИМАНИЕ!</b> Рекомендуем правильно выбрать категорию размещающего товара и соответствует ли он правилам.<br>После добавления, товар попадает на модерацию. Модератор одобрит или отклонит товар через некоторое время.<br>Все товары, которые нарушают правила системы будут заблокированы до выяснения причин.</span>
			</div>
			<table class="table table-striped table_page table_page_input1 td-table">
				<tbody>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Категория:</td>
					<td class="padding_10 vertical_align select_style_120">
						<select name="group0" id="group0" onchange="groupChange(this.value, 0)">
							<option value="0">-Выберите группу-</option>
							{group}
						</select>
						<select onchange="groupChange(this.value, 1)" name="group1" id="group1" style="display:none;"></select>
						<select onchange="groupChange(this.value, 2)" name="group2" id="group2" style="display:none;"></select>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Свойство товара:</td>
					<td class="padding_10 vertical_align select_style_120">
						<div class="label_f">
							<label><input type="radio" name="many" checked="checked"> - Уникальный <i>(продается 1 раз)</i></label>
							<span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Уникальный — каждый экземпляр которого продается только один раз, без возможности тиражирования: ПИН-код, пароль, реквизиты доступа к чему-либо и т.д. При этом, чтобы продать 100 пин-кодов пополнения мобильного телефона, вам понадобится загрузить все 100 кодов, и каждый покупатель получит свой уникальный код.">?</span>
						</div>
						<div class="label_f">
							<label><input type="radio" name="many"> - Универсальный <i>(продается неоднократно)</i></label>
							<span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Универсальный — загружается в одном экземпляре, а продается бесконечное число раз. Типичные примеры универсальных товаров: программа, электронная книга, база данных. Так, чтобы продать электронную книгу 100 раз, достаточно загрузить ее  лишь единожды.">?</span>
						</div>
					</td>
				</tr>
				<tr>
					<td  class="font_weight_700 font_size_14 padding_10 text_align_r">Тип товара:</td>
					<td class="padding_10 vertical_align select_style_120">
						<div class="label_f"><label><input type="radio" name="type" > - Файл</label></div>
						<div class="label_f"><label><input type="radio" name="type" checked> - Текстовая информация</label></div>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Название товара:</td>
					<td class="padding_10 vertical_align">
						<input type="text" name="name" maxlength="60" style="max-width:300px;width: 80%;" /> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="В название тавара, максимально 60 символов.">?</span>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Цена:</td>
					<td class="padding_10 vertical_align td_img">
						<input type="text" name="price" maxlength="19" style="text-align: center;max-width: 70px;">
						<select name="current" style="max-width:80px;">
							<option value="1">USD</option>
							<option value="2">ГРН</option>
							<option value="3">EUR</option>
							<option value="4">РУБ</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Изображение: <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Максимальный размер 65кб.">?</span></td>
					<td class="padding_10 vertical_align" id="SWFuploadPictureDiv">
						<div id="uploadPictureButton"></div>
						<div id="SWFuploadPictureMessage"></div>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Промо-код:</td>
					<td class="padding_10 vertical_align select_style_150">
						{{IF:NOPROMOCODES}}
						У вас нет созданных выпусков промо-кодов. Создайте выпуск промо-кодов, что бы получить возможность автоматически выдавать их покупателям.
						Выпуск может содержать один код, новые будут добавляться автоматически.
						{{ELSE:NOPROMOCODES}}
						<label><input type="checkbox" name="promocodes"> Автоматически выдать покупателю после оплаты</label>
						<div style="margin-top: 10px;">
							Промокод должен быть автоматически создан и добавлен в мой выпуск:
							<select name="promocodes_val" style="padding:3px;">
								<option value="0">-Выберите выпуск-</option>
								{{FOR:PROMOCODES}}
								<option value="{promocode_id}">{name}</option>
								{{ENDFOR:PROMOCODES}}
								<select>
						</div>
						{{ENDIF:NOPROMOCODES}}
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Комиссия партнеру:</td>
					<td class="padding_10 vertical_align"><input type="text" value="1" name="partner" maxlength="2" style="max-width:40px;"> %</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Описание товара:</td>
					<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="10" name="description" maxlength="40500"></textarea></td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Дополнительная информация:</td>
					<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="10" name="info" maxlength="20500"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">
						<div id="panel_user_myshop_add_error"></div>
						<button name="submit" class="btn btn-success">Добавить</button>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
