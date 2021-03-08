<div class="pageName"><h1>Объекты продажи товара {product_name}</h1></div>
<div class="pro_add_form">
    <div class="pro_add_form_punct">
		{{SWITCH:OBJ}}
			{{CASE:FILE}}
				{{IF:NOFILE_RESERV}}
					<div class="panel_user_myshop_edit_obj_reserves">
						{{FOR:FILE_RESERV}}
							<div class="panel_user_myshop_edit_obj_reserv">
								<div class="panel_user_myshop_edit_obj_reserv_left">Состояние:</div>
								<div class="panel_user_myshop_edit_obj_reserv_right">
									<div class="panel_user_myshop_edit_obj_reserv_name">{name}</div>
									<div>*забронирован покупателем до {date}</div>
								</div>
							</div>
						{{ENDFOR:FILE_RESERV}}
						<p>
							Состояние забронирован означает, что покупатель выписал счет на оплату товара, 
							но пока его не оплатил. Время жизни счета составляет {time_reserv} минут. 
							Именно на это время и резервируется (бронируется) товар под этого покупателя.
						</p>
					</div>
				{{ELSE:NOFILE_RESERV}}{{ENDIF:NOFILE_RESERV}}
				<div align="right">
					{{IF:DELALL_BUTTON_FILE}}
						<a class="btn btn-small btn-danger" onclick="panel.user.myshop.edit.obj.delall(this);">
							<i class="icon-minus icon-white"></i> 
							удалить все
						</a>
					{{ELSE:DELALL_BUTTON_FILE}}{{ENDIF:DELALL_BUTTON_FILE}}
				</div>
				<div id="productAddFileForm">
					<div class="pro_add_form_punct_t">Загрузка файла(ов):</div>
					<div id="uploadButton"></div>
					<div id="status"></div>
					<div id="statuss">
						{{FOR:FILE}}
							<p>
								{name}
								<a class="btn btn-mini btn-danger" onclick="panel.user.myshop.edit.obj.file_del(0, this, {product_obj_file_id});">
									<i class="icon-remove-circle icon-white"></i>
									удалить
								</a>
							</p>
						{{ENDFOR:FILE}}					
					</div>
				</div>
				<div style="text-align:right;padding-right:5px;">
					<input class="btn btn-small btn-success" type="submit" onclick="panel.user.myshop.edit.obj.save(this); return false;" value="Сохранить">
				</div>
			{{ENDCASE:FILE}}
			{{CASE:TEXT}}
				{{IF:NOTEXT_RESERV}}
					<div class="panel_user_myshop_edit_obj_reserves">
						{{FOR:TEXT_RESERV}}
							<div class="panel_user_myshop_edit_obj_reserv">
								<div class="panel_user_myshop_edit_obj_reserv_left">Состояние:</div>
								<div class="panel_user_myshop_edit_obj_reserv_right">
									<div class="panel_user_myshop_edit_obj_reserv_text">{text}</div>
									<div>*забронирован покупателем до {date}</div>
								</div>
							</div>
						{{ENDFOR:TEXT_RESERV}}
					</div>
				{{ELSE:NOTEXT_RESERV}}{{ENDIF:NOTEXT_RESERV}}
					<div style="display:inline-block;width:782px;">
						{{IF:ADD_BUTTON}}<a class="btn btn-small btn-success" onclick="panel.user.myshop.edit.obj.add.on('choose');">Добавить еще</a>
						{{ELSE:ADD_BUTTON}}{{ENDIF:ADD_BUTTON}}		
					</div>
					<div style="display:inline-block;">
						{{IF:DELALL_BUTTON_TEXT}}
							<a class="btn btn-small btn-danger" onclick="panel.user.myshop.edit.obj.delall(this);">
								<i class="icon-minus icon-white"></i> 
								удалить все
							</a>
						{{ELSE:DELALL_BUTTON_TEXT}}{{ENDIF:DELALL_BUTTON_TEXT}}
					</div>
				{{IF:NOTEXT}}
					{{FOR:TEXT}}
					<div>
						<div class="pro_add_form_punct_t">
							Текст (объект продажи):
							<div style="display:inline-block;">
								<a class="btn btn-small btn-danger" onclick="panel.user.myshop.edit.obj.del_textobj(this, {product_obj_text_id});">
									<i class="icon-minus icon-white"></i> 
									удалить
								</a>
							</div>
							<div style="display:inline-block;">
								<a class="btn btn-small btn-info" onclick="panel.user.myshop.edit.obj.edittextobj(this, {product_obj_text_id});">
									изменить
								</a>
							</div>
						</div>
						<textarea maxlength="43000" disabled style="background-color:#EBEBE4;" id="panel_user_myshop_edit_obj_edittextobj_obj_{product_obj_text_id}">{text}</textarea>
					</div>
					{{ENDFOR:TEXT}}
				{{ELSE:NOTEXT}}{{ENDIF:NOTEXT}}			
			{{ENDCASE:TEXT}}
			{{CASE:TEXT_UNI_OUT}}
					<div id="panel_user_myshop_edit_obj_add_fields">
						<div class="pro_add_form_punct_t">Текст (объект продажи):</div>
						<textarea maxlength="43000"></textarea>
					</div>
					<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
					<div style="text-align:right;padding-right:3px;" id="panel_user_myshop_edit_obj_add_button">
						<button class="btn btn-small btn-success" onclick="panel.user.myshop.edit.obj.add.savetextobj.send();"> Сохранить</button>
					</div>
				</div>
			{{ENDCASE:TEXT_UNI_OUT}}
			{{CASE:ADDCHOOSE}}
				<div style="padding:25px;line-height:18px;color:red;">
					Выберите один из предложенных способов загрузки «уникальных» товаров (PIN-кодов, реквизитов доступа и т.д.). При выборе поочередной загрузке, вам необходимо 
					добавить каждый «уникальный» товар по очереди, заполняя для этого форму. Если вы выберите мультизагрузку, то вам необходимо создать .csv или .txt файл, в который поместить 
					ваши «уникальные» товары в определённом формате и затем загрузить его. Пример такого файла и формат будут представлены далее.
				</div>
				<div style="padding-left:50px;">
					<div style="display:inline-block;width:150px;text-align:right;font-weight:bold;padding-right:10px;">Поочередная загрузка:</div>
					<div style="display:inline-block;"><button class="btn btn-small btn-success" onclick="panel.user.myshop.edit.obj.add.on('simple');">Далее</button></div>
				</div>
				<div style="padding:0px 0px 25px 50px;">
					<div style="display:inline-block;width:150px;text-align:right;font-weight:bold;padding-right:10px;">Мультизагрузка:</div>
					<div style="display:inline-block;"><button class="btn btn-small btn-success" onclick="panel.user.myshop.edit.obj.add.on('multi');">Импорт из мульти-файла</button></div>
				</div>
			{{ENDCASE:ADDCHOOSE}}
			{{CASE:ADDSIMPLE}}
				<div>
					<div id="panel_user_myshop_edit_obj_add_fields">
						<div class="pro_add_form_punct_t">Текст (объект продажи):</div>
						<textarea maxlength="43000"></textarea>
					</div>
				</div>
				<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
				<div style="display:inline-block;">
					<button class="btn btn-small btn-danger" style="margin-left:3px;" onclick="panel.user.myshop.edit.obj.add.canceltextobj();"><i class="icon-minus icon-white"></i> Отменить</button>
				</div>
				<div style="display:inline-block;">
					<button class="btn btn-small btn-info" style="margin-left:124px;" onclick="panel.user.myshop.edit.obj.add.field.add();"><i class="icon-plus icon-white"></i> Добавить поле</button>
				</div>
				<div style="display:inline-block;" id="panel_user_myshop_edit_obj_add_button">
					<button class="btn btn-small btn-success" style="margin-left:461px;" onclick="panel.user.myshop.edit.obj.add.savetextobj.send();"> Сохранить</button>
				</div>
			{{ENDCASE:ADDSIMPLE}}
			{{CASE:ADDMULTI}}
				<div style="padding:25px;line-height:18px;color:red;">
					Сформируйте .txt или .csv файл, формат и пример которого приведены ниже. Обратите внимание на то, что каждый "PIN-код" размещается с новой строки.
				</div>
				<div style="margin:0px 150px;">
					<div style="display:inline-block;width:430px;">
						<p style="color:#B2B2B2">Формат файла:</p>
						<p style="line-height:18px;">&#60;PIN-код1&#62;<br>
						&#60;PIN-код2&#62;<br>
						&#60;PIN-код3&#62;<br>
						...</p>
					</div>
					<div style="display:inline-block;">
						<p style="color:#B2B2B2">Пример файла:</p>
						<p style="line-height:18px;">987-QWERTY<br>
						654-ASDFGH<br>
						321-ZXCVBN<br>
						...</p>
					</div>
				</div>
				<div style="margin:25px;">
					<form onsubmit="panel.user.myshop.edit.obj.add.multi.upload(this); return false;">
						<div id="panel_user_myshop_edit_obj_add_multi_upload_info"></div>
						<div style="display:inline-block;vertical-align:top;">
							Мульти-файл:
						</div>
						<div style="display:inline-block;margin-left:20px;">
							<input name="file" type="file"><br>
							<input class="btn btn-small btn-success" type="submit" value="Загрузить">
						</div>
					</form>
				</div>
				<div id="panel_user_myshop_edit_obj_add_fields"></div>
				<div id="panel_user_myshop_edit_obj_add_error" class="form_error"></div>
				<div style="display:inline-block;">
					<button class="btn btn-small btn-danger" style="margin-left:3px;" onclick="panel.user.myshop.edit.obj.add.canceltextobj();"><i class="icon-minus icon-white"></i> Отменить</button>
				</div>
				<div style="display:inline-block;" id="panel_user_myshop_edit_obj_add_button">
					<button class="btn btn-small btn-success" style="margin-left:706px;" onclick="panel.user.myshop.edit.obj.add.savetextobj.send(true);"> Сохранить</button>
				</div>
			{{ENDCASE:ADDMULTI}}
		{{ENDSWITCH:OBJ}}	
</div> 