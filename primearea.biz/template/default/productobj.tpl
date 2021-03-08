
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Объекты продажи товара {product_name}</h3>
	</div>
	<div class="panel-body">

		{{SWITCH:OBJ}}

		{{CASE:FILE}}
		{{IF:NOFILE_RESERV}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td class="padding_10">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Состояние забронирован означает, что покупатель выписал счет на оплату товара, но пока его не оплатил. Время жизни счета составляет {time_reserv} минут. Именно на это время и резервируется (бронируется) товар под этого покупателя.</span>
					</div>
				</td>
			</tr>
			{{FOR:FILE_RESERV}}
			<tr>
				<td class="padding_10 text_align_c">
					Состояние: {name} забронирован покупателем до {date}
				</td>
			</tr>
			{{ENDFOR:FILE_RESERV}}
			</tbody>
		</table>
		{{ELSE:NOFILE_RESERV}}{{ENDIF:NOFILE_RESERV}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td style="/*width: 180px;*/" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Загрузка файла(ов):</td>
				<td class="padding_10 vertical_align" id="productAddFileForm">
					<div id="uploadButton"></div>
					<div id="status"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="table table-striped table_page">
						<tbody id="statuss">
						{{FOR:FILE}}
						<tr data-productobj="file">
							<td class="font_size_14 font_weight_700 padding_10 vertical_align">{name}</td>
							<td style="/*width: 70px;*/" class="padding_10 vertical_align"><button class="btn btn-sm btn-danger" onclick="panel.user.myshop.edit.obj.file_del(0, this, {product_obj_file_id});">удалить</button></td>
						</tr>
						{{ENDFOR:FILE}}
						</tbody>
					</table>
				</td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="2" style="background: #4E4E4E;border-top: 0 dashed transparent;" class="padding_10 text_align_r vertical_align">
					<button class="btn btn-sm btn-success" onclick="panel.user.myshop.edit.obj.save(this); return false;">Сохранить</button>
					{{IF:DELALL_BUTTON_FILE}}
					<a class="btn btn-sm btn-danger" onclick="panel.user.myshop.edit.obj.delall(this);">удалить все</a>
					{{ELSE:DELALL_BUTTON_FILE}}{{ENDIF:DELALL_BUTTON_FILE}}
				</td>
			</tr>
			</tfoot>
		</table>
		{{ENDCASE:FILE}}

		{{CASE:TEXT}}
		{{IF:NOTEXT_RESERV}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td class="padding_10">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Состояние забронирован означает, что покупатель выписал счет на оплату товара, но пока его не оплатил. Время жизни счета составляет {time_reserv} минут. Именно на это время и резервируется (бронируется) товар под этого покупателя.</span>
					</div>
				</td>
			</tr>
			{{FOR:TEXT_RESERV}}
			<tr>
				<td class="padding_10 text_align_c">
					Состояние: {text} забронирован покупателем до {date}
				</td>
			</tr>
			{{ENDFOR:TEXT_RESERV}}
			</tbody>
		</table>
		{{ELSE:NOTEXT_RESERV}}{{ENDIF:NOTEXT_RESERV}}
		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td class="padding_10 text_align_c">

					{{IF:ADD_BUTTON}}
					<a class="btn btn-sm btn-success" href="/panel/productobj/add/{product_id}">Добавить еще</a>
					{{ELSE:ADD_BUTTON}}{{ENDIF:ADD_BUTTON}}

					{{IF:DELALL_BUTTON_TEXT}}
					<a class="btn btn-sm btn-danger" onclick="panel.user.myshop.edit.obj.delall(this);">удалить все</a>
					{{ELSE:DELALL_BUTTON_TEXT}}{{ENDIF:DELALL_BUTTON_TEXT}}

				</td>
			</tr>
			</tbody>
		</table>
		{{IF:NOTEXT}}
		<table class="table table-striped table_page td-table">
			<thead>
			<tr>
				<td>Текст (объект продажи)</td>
				<td style="/*width: 130px;*/"></td>
			</tr>
			</thead>
			{{FOR:TEXT}}
			<tbody>
			<tr>
				<td class="padding_10 text_align_c"><textarea class="textarea_def form-control" maxlength="43000" disabled style="background-color:#EBEBE4;" id="panel_user_myshop_edit_obj_edittextobj_obj_{product_obj_text_id}">{text}</textarea></td>
				<td class="padding_10 text_align_c">
					<a class="btn btn-info btn-sm" onclick="panel.user.myshop.edit.obj.edittextobj(this, {product_obj_text_id});">изменить</a>
					<a class="btn btn-danger btn-sm" onclick="panel.user.myshop.edit.obj.del_textobj(this, {product_obj_text_id});">удалить</a>
				</td>
			</tr>
			</tbody>
			{{ENDFOR:TEXT}}
		</table>
		{{ELSE:NOTEXT}}{{ENDIF:NOTEXT}}
		{{ENDCASE:TEXT}}

		{{CASE:TEXT_UNI_OUT}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td style="/*width: 180px;*/" class="font_size_14 font_weight_700 padding_10 text_align_r">Текст (объект продажи):</td>
				<td class="padding_10" id="panel_user_myshop_edit_obj_add_fields">
					<textarea class="textarea_def form-control" rows="4" maxlength="43000"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="padding_10 text_align_r">
					<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
					<button id="panel_user_myshop_edit_obj_add_button" class="btn btn-sm btn-success" onclick="panel.user.myshop.edit.obj.add.savetextobj();"> Сохранить</button>
				</td>
			</tr>
			</tbody>
		</table>
		{{ENDCASE:TEXT_UNI_OUT}}

		{{CASE:ADDCHOOSE}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td colspan="2" class="padding_10 text_align_c">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">
										Выберите способ загрузки «уникальных» товаров (PIN-кодов, реквизитов доступа и т.д.). Поочередная загрузка - необходимо
										добавить каждый «уникальный» товар по очереди, заполняя для этого форму. Мультизагрузка - необходимо создать .csv или .txt файл, в который поместить
										ваши «уникальные» товары в определённом формате, затем загрузить его. Пример файла и формат будут представлены далее.
									</span>
					</div>
				</td>
			</tr>
			<tr>
				<td style="/*width: 180px;*/" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Поочередная загрузка:</td>
				<td class="padding_10 vertical_align"><a class="btn btn-sm btn-success" href="/panel/productobj/simple/{product_id}">Далее</a></td>
			</tr>
			<tr>
				<td class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Мультизагрузка:</td>
				<td class="padding_10 vertical_align"><a class="btn btn-sm btn-success" href="/panel/productobj/multi/{product_id}">Импорт из мульти-файла</a></td>
			</tr>
			</tbody>
		</table>
		{{ENDCASE:ADDCHOOSE}}

		{{CASE:ADDSIMPLE}}
		<table class="table table-striped table_page td-table">
			<tbody id="panel_user_myshop_edit_obj_add_fields">
			<tr>
				<td style="/*width: 180px;*/" class="font_size_14 font_weight_700 padding_10 text_align_r">Текст (объект продажи):</td>
				<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" rows="4" maxlength="43000"></textarea></td>
				<td class="padding_10 text_align_c"></td>
			</tr>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="3" class="padding_10 vertical_align text_align_r">
					<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
					<button class="btn btn-sm btn-info" onclick="panel.user.myshop.edit.obj.add.field.add();"> Добавить поле</button>
					<button id="panel_user_myshop_edit_obj_add_button" class="btn btn-sm btn-success" onclick="panel.user.myshop.edit.obj.add.savetextobj();"> Сохранить</button>
					<a href="/panel/productobj/edit/{product_id}"><button class="btn btn-sm btn-danger"><i class="icon-minus icon-white"></i> Отменить</button></a>
				</td>
			</tr>
			</tfoot>
		</table>
		{{ENDCASE:ADDSIMPLE}}

		{{CASE:ADDMULTI}}
		<table class="table table-striped table_page td-table">
			<tbody>
			<tr>
				<td colspan="2" class="padding_10 text_align_c">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">
										Сформируйте .txt или .csv файл, формат и пример которого приведены ниже. Обратите внимание на то, что каждый "PIN-код" размещается с новой строки.
									</span>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="padding_10 text_align_c vertical_align">
					<div style="display:inline-block;margin: 0 20px;text-align: left;">
						Формат файла:
									<pre style="/*width: 150px;*/">&#60;PIN-код1&#62;
&#60;PIN-код2&#62;
&#60;PIN-код3&#62;
...</pre>
					</div>
					<div style="display:inline-block;margin: 0 20px;text-align: left;">
						Пример файла:
									<pre style="/*width: 150px;*/">987-QWERTY
654-ASDFGH
321-ZXCVBN
...</pre>
					</div>
				</td>
			</tr>
			<tr>
				<td style="/*width: 180px;*/" class="font_size_14 font_weight_700 padding_10 text_align_r vertical_align">Мульти-файл:</td>
				<td class="padding_10 vertical_align">
					<form onsubmit="panel.user.myshop.edit.obj.add.multi.upload(this); return false;">
						<div class="file_upload">
							<button type="button" class="btn btn-sm btn-success">Выбрать файл</button>
							<input name="file" type="file">
						</div>
						<input class="btn btn-sm btn-success" type="submit" value="Загрузить">
						<div id="panel_user_myshop_edit_obj_add_multi_upload_info" style="display: inline-block;margin: 0 0 0 30px;"></div>
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="table table-striped table_page">
						<tbody id="panel_user_myshop_edit_obj_add_fields">
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="padding_10 text_align_r">
					<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
					<button id="panel_user_myshop_edit_obj_add_button" class="btn btn-sm btn-success" style="" onclick="panel.user.myshop.edit.obj.add.savetextobj(true);">Сохранить</button>
					<a href="/panel/productobj/edit/{product_id}" class="btn btn-sm btn-danger">Отменить</a>
				</td>
			</tr>
			</tbody>
		</table>
		{{ENDCASE:ADDMULTI}}

		{{ENDSWITCH:OBJ}}


	</div>
</div>



