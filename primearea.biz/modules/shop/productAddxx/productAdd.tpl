<div class="pageName"><h1>ДОБАВИТЬ ТОВАР</h1></div>

<div class="pro_add_form">
<form onsubmit="panel.user.myshop.add.send(this);return false;">
  <div id="addProductStepOne">
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t pro_add_form_punct_w">Категория</div> 

        <select name="group0" id="group0" onchange="groupChange(this.value, 0)">
            <option value="0">-Выберите группу-</option> 
            {group}
        </select>
        <select onchange="groupChange(this.value, 1)" name="group1" id="group1" style="display:none;"></select>
        <select onchange="groupChange(this.value, 2)" name="group2" id="group2" style="display:none;"></select>
        
    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Свойство товара:</div>

        <p><input type="radio" name="many" checked="checked"> - Уникальный <i>(продается 1 раз)</i> <a id="south1" class="live-tipsy" href="#" title="Уникальный — это товар, каждый экземпляр которого продается только один раз, без возможности тиражирования: ПИН-код, пароль, реквизиты доступа к чему-либо и т.д. При этом, чтобы продать 100 пин-кодов пополнения мобильного телефона, вам понадобится загрузить все 100 кодов, и каждый покупатель получит свой уникальный код.">[?]</a></p>
        <p><input type="radio" name="many"> - Универсальный <i>(продается неоднократно)</i> <a id="south1" class="live-tipsy" href="#" title="Универсальный — наоборот, загружается в одном экземпляре, а продается бесконечное число раз. Типичные примеры универсальных товаров: программа, электронная книга, база данных. Так, чтобы продать электронную книгу 100 раз, достаточно загрузить ее  лишь однажды.">[?]</a></p>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Тип товара:</div>

<!--         <p><input type="radio" name="type" checked> - Файл</p> -->
        <p><input type="radio" name="type" > - Тексток2вая информация</p>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Название товара:</div>

        <input type="text" name="name" maxlength="60" /> <a id="south1" class="live-tipsy" href="#" title="В название тавара, максимально 60 символов.">[?]</a>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Цена:</div>

        <input type="text" name="price" maxlength="19" style="width:80px;">
        <select name="current" style="width:80px;"><option value="1">USD</option><option value="2">ГРН</option><option value="3">EUR</option><option value="4">РУБ</option></select>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Изображение:</div>
        <div id="SWFuploadPictureDiv">
           <div id="uploadPictureButton"></div>
           <div id="SWFuploadPictureMessage"></div>
        </div>
         <a id="south1" class="live-tipsy" href="#" title="Максимальный размер 65кб.">[?]</a>
        <div class="clr"></div>
    </div>
	<div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Промо-код:</div>
			<div style="line-height:19px;">
				{{IF:NOPROMOCODES}}
						У вас нет созданных выпусков промо-кодов. Создайте выпуск промо-кодов, что бы получить возможность автоматически выдавать их покупателям. 
						Выпуск может содержать один код, новые будут добавляться автоматически.
				{{ELSE:NOPROMOCODES}}
					<input type="checkbox" name="promocodes">
					Автоматически выдать покупателю после оплаты<br>
					Промокод должен быть автоматически создан и добавлен в мой выпуск:<br>
					<select name="promocodes_val" style="padding:3px;">
						<option value="0">-Выберите выпуск-</option>
						{{FOR:PROMOCODES}}
							<option value="{promocode_id}">{name}</option>
						{{ENDFOR:PROMOCODES}}					
					<select>
				{{ENDIF:NOPROMOCODES}}
			</div>
        <div class="clr"></div>		
	</div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Описание товара:</div>

        <textarea name="description" maxlength="40500"></textarea>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Дополнительная информация:</div>

        <textarea name="info" maxlength="20500"></textarea>

    <div class="clr"></div>
    </div>
	<div class="pro_add_form_punct">
		<div id="panel_user_myshop_add_error"></div>
		<div class="clr"></div>
	</div>
	<div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t"></div>
		<button name="submit" class="btn btn-small btn-success">Добавить</button>
        <div class="clr"></div>
    </div>   
  </div>
</form>
</div>