{{SWITCH:OBJ}}
	{{CASE:FILE}}
		{{FOR:FILE_RESERV}}
			<div>
				<div>{name}</div>
				<div>*забронирован покупателем до {date}</div>
			</div>
		{{ENDFOR:FILE_RESERV}}
	{{ENDCASE:FILE}}
	{{CASE:TEXT}}
		<div id="newTextArea1">
			<div class="pro_add_form_punct_t">Текст (объект продажи):</div>
			<textarea id="text1" maxlength="2500"></textarea>
		</div>

		<div id="newTextArea'.($i+1).'">
			<div class="pro_add_form_punct_t">
				Текст (объект продажи):
				<a href="#" class="btn btn-small btn-danger" onclick="delTextArea('.($i+1).'); return false;">
					<i class="icon-minus icon-white"></i> 
					Удалить
				</a>
			</div>
			<textarea id="text'.($i+1).'" maxlength="2500">'.$text.'</textarea>
		</div>
	{{ENDCASE:TEXT}}
{{ENDSWITCH:CABINET}}