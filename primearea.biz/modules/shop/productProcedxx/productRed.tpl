<div class="pageName"><h1>РЕДАКТИРОВАНИЕ ТОВАРА</h1></div>

<div class="pro_add_form">
<form onsubmit="panel.user.myshop.edit.save(this); return false;">
  <script type="text/javascript">var productRedGroup='{groupVar}';</script>
  <div id="addProductStepOne">
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Категория</div> 
        {group}
    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Свойство товара:</div>
        {many}
    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Тип товара:</div>
        {typeObject}
    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Название товара:</div>

        <input type="text" name="name" maxlength="60" value="{name}" />  <a id="south3" class="live-tipsy" href="#" title="В название тавара, максимально 60 символов.">[?]</a>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Цена:</div>

        <input type="text" name="price" maxlength="16" value="{price}" style="width:60px;">
        <select name="current" style="width:80px;">{curr}</select>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Изображение:</div>
        <div id="SWFuploadPictureDiv">{picture}</div>
        <div class="clr"></div>
    </div>
	<div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Промо-код:</div>
			<div style="line-height:19px;">
				{{IF:NOPROMOCODES}}
						У вас нет созданных выпусков промо-кодов. Создайте выпуск промо-кодов, что бы получить возможность автоматически выдавать их покупателям. 
						Выпуск может содержать один код, новые будут добавляться автоматически.
				{{ELSE:NOPROMOCODES}}
					<input type="checkbox" name="promocodes" {checked}>
					Автоматически выдать покупателю после оплаты<br>
					Промокод должен быть автоматически создан и добавлен в мой выпуск:<br>
					<select name="promocodes_val" style="padding:3px;">
						<option value="0">-Выберите выпуск-</option>
						{{FOR:PROMOCODES}}
							<option value="{promocode_id}" {selected}>{name}</option>
						{{ENDFOR:PROMOCODES}}					
					<select>
				{{ENDIF:NOPROMOCODES}}
			</div>
        <div class="clr"></div>		
	</div>	
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Описание товара:</div>

        <textarea name="description" maxlength="2500">{descript}</textarea>

    <div class="clr"></div>
    </div>
    <div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t">Дополнительная информация:</div>

        <textarea name="info" maxlength="2500">{info}</textarea>

    <div class="clr"></div>
    </div>
	<div class="pro_add_form_punct">
        <div class="pro_add_form_punct_t"></div>
		<div id="productRedEror"></div>
        <div class="clr"></div>
    </div>
	<div class="pro_add_form_punct">
		<div class="pro_add_form_punct_t"></div>
		<button name="button" class="btn btn-small btn-success">Редактировать</button>
    </div>   
  </div>
</form>
</div>