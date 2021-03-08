
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Редактирование товара</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.user.myshop.edit.save(this); return false;">
			<script type="text/javascript">var productRedGroup='{groupVar}';</script>
			<style>
				.select {margin:0 10px; }
			</style>
			<table class="table table-striped table_page table_page_input1 td-table">
				<tbody>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Категория:</td>
					<td class="padding_10 vertical_align select_style_120">{group}</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Свойство товара:</td>
					<td class="padding_10 vertical_align">{many}</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Тип товара:</td>
					<td class="padding_10 vertical_align">{typeObject}</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Название товара:</td>
					<td class="padding_10 vertical_align">
						<input type="text" name="name" maxlength="60" value="{name}"  style="max-width:300px;width: 80%;" /> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="В название тавара, максимально 60 символов.">?</span>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Цена:</td>
					<td class="padding_10 vertical_align td_img">
						<input type="text" name="price" maxlength="16" value="{price}" style="text-align: center;max-width: 70px;">
						<select name="current" style="max-width:80px;">{curr}</select>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Изображение:</td>
					<td class="padding_10 vertical_align" id="SWFuploadPictureDiv">{picture}</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Промо-код:</td>
					<td class="padding_10 vertical_align select_style_150">
						{{IF:NOPROMOCODES}}
						У вас нет созданных выпусков промо-кодов. Создайте выпуск промо-кодов, что бы получить возможность автоматически выдавать их покупателям.
						Выпуск может содержать один код, новые будут добавляться автоматически.
						{{ELSE:NOPROMOCODES}}
						<label><input type="checkbox" name="promocodes" {checked}> Автоматически выдать покупателю после оплаты</label>
						<div style="margin-top: 10px;">
							Промокод должен быть автоматически создан и добавлен в мой выпуск:
							<select name="promocodes_val" style="padding:3px;">
								<option value="0">-Выберите выпуск-</option>
								{{FOR:PROMOCODES}}
								<option value="{promocode_id}" {selected}>{name}</option>
								{{ENDFOR:PROMOCODES}}
								<select>
						</div>
						{{ENDIF:NOPROMOCODES}}
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">Комиссия партнеру:</td>
					<td class="padding_10 vertical_align"><input type="text" value="{partner}" maxlength="2" name="partner" style="text-align: center;max-width:40px;"> %</td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Описание товара:</td>
					<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="10" name="description" maxlength="2500">{descript}</textarea></td>
				</tr>
				<tr>
					<td class="font_weight_700 font_size_14 padding_10 text_align_r">Дополнительная информация:</td>
					<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="10"	 name="info" maxlength="2500">{info}</textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="font_weight_700 font_size_14 padding_10 text_align_r vertical_align">
						<div id="productRedEror"></div>
						<button name="button" class="btn btn-success">Сохранить</button>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>

