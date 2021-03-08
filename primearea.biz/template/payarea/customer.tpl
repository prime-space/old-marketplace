<div class="pageName_fc">
{{SWITCH:CABINET}}
	{{CASE:HELLO}}
	<div class="inner-page-login">   
	 <div class="login">
  <div class="text-login">Вход в кабинет покупателя</div> 
 </div>
		  <div class="warning-login">
  <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Для доступа ко всем вашим покупкам необходимо авторизоваться. <br>Введите ваш E-mail используемый при покупках и защитный код, после чего вам на почту поступит временная авторизационная ссылка.</p>    
 </div>
	<form class="input-field login-form">
  <input class="input-login" type="text" maxlength="32" id="customer_email" />
  <div class="input-login-comment">Введите ваш E-mail</div>
 </form> 
<form class="input-field login-form">
  <input class="input-login" type="text" maxlength="32" id="customer_captcha" />
  <div class="input-login-comment">Код с картинки</div>
 </form>   
 <div class="login-form">
  <div class="login-captcha">  
  <div class="captcha"><img id="customer_captcha_pic" src="/modules/captcha/captcha.php"></div>
 <button class="login-button" type="submit" onclick="module.customer.login(); return false;">Авторизоваться</button>
  </div>
 </div>
	<div id="customer_login_error" class="form_error ws_form_error"></div>
	</div>
	
	{{ENDCASE:HELLO}}
	{{CASE:ERRORCODE}}
	<div class="inner-page-login">  
	 <div class="login">
  <div class="text-login">Вход в кабинет покупателя</div> 
 </div>
		  <div class="warning-login" style="height: 85px;margin-bottom: 20px;">
  <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ваш ключ сессии устарел или ошибочный.<br> Воспользуйтесь процедурой получения нового ключа авторизации.</p>    
 </div>
	 <a class="login-buttonsession" href="/customer/">Получить новый ключ авторизации</a>
	</div>
	{{ENDCASE:ERRORCODE}}
	{{CASE:UNSUBSCRIBE}}
		<div class="unsubscribePage">
			<div class="head">Настройка почтовых уведомлений:</div>
			<form onsubmit="module.customer.unsubscribe.common(this);return false;">
				<label><input type="checkbox" name="buy" {buyChecked}> Получать Email уведомления о покупке товара</label>
				<label><input type="checkbox" name="orderUpdate" {orderUpdateChecked}> Получать Email уведомления о новых сообщениях от продавцов по вашим покупкам</label>
				<div class="butt"><span id="unsubscribeInfo"></span><button name="button">Сохранить</button></div>
			</form>
		</div>
	{{ENDCASE:UNSUBSCRIBE}}
	{{CASE:PRODUCTLIST}}
	 <div class="promocode-table">
 <div class="user_info">
 <div class="seller_profile"><a href="/customer/{h}"><i class="fa fa-user" aria-hidden="true"></i>Кабинет покупателя</a></div>
 <div class="your_discount"><span class="discount-color">Список Ваших покупок</span></div>
 </div>
		<a name="head_module.customer.productlist.get"></a>
		<table id="customer_productlist" class="table table-striped table_page">
			<thead>
				<tr>
					<td colspan="2" class="nametd">Наименование товара</td>
					<td class="text_align_c nametd">Дата</td>
					<td class="text_align_c nametd">Цена</td>
				</tr>
			</thead>
			
			<tbody id="module_customer_productlist">

			</tbody>
		</table>
	 </div>
	{{ENDCASE:PRODUCTLIST}}
	{{CASE:CONTACT_SELLER}}
<div class="promocode-table">
	<div class="user_info">
 <div class="seller_profile"><a href="/customer/{h}"><i class="fa fa-user" aria-hidden="true"></i>Кабинет покупателя</a></div>
 </div>
	 <div class="key-info">
  <div class="ket-title-border">  
  <div class="key-title">
   <div class="key-name">{name}<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a></div>
  </div>
  </div> 
    <div class="key-invoice">Счёт <span class="key-invoice-number">№{i}</span> (Выписан {date}), Оплачено: <span class="key-cost">{price}</span></div>
    <div class="item-seller-warning" style="margin-top: 20px;">
     <div class="item-seller-warning-header">
      <div class="item-seller-warning-header-title">
       <p>Внимание</p>  
      </div>
      <div class="item-seller-warning-header-check">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-inner" style="min-height: 0px;">
      Обращаем внимание, что при возникновении споров администрация торговой площадки рассматривает вашу переписку с продавцом только в рамках данной страницы сервера payarea24.com. Никакие выдержки из переписки посредством email, icq и т.п. не рассматриваются. Любые просьбы продавца вести переписку по отзыву через внешние системы обмена сообщениями настоятельно рекомендуем игнорировать!
     </div>
   </div>
	<div class="key-notofications">
	    {{SWITCH:unsubscribe}}
					{{CASE:common}}<span>Вы отписаны от получения уведомлений на email. </span><a target="_blank" href="/customer/unsubscribe/{unsubscribeKey}/">ПОДПИСАТЬСЯ</a>{{ENDCASE:common}}
					{{CASE:un}}
						<span>Отписаться от получения уведомлений на email по изменениям в этом заказе</span>
						<button class="unfollow" onclick="module.customer.unsubscribe.order(this);">Отписаться</button>
					{{ENDCASE:un}}
					{{CASE:already}}<span>Вы отписаны от получения уведомлений на email по изменениям в этом заказе</span>{{ENDCASE:already}}
				{{ENDSWITCH:unsubscribe}}
  </div>
	<div class="key-msg">
		<form class="ws_review_send_form" onsubmit="module.customer.send_message(this); return false;">
   <div class="key-msg-header">
    <div class="key-msg-enter-msg"><i class="fa fa-question-circle" aria-hidden="true"></i> Введите сообщение:</div>  
    <div class="key-msg-go-back"><a href="/customer/{i}/{h}/"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Вернуться назад</a></div>
   </div>
   <textarea rows="10" cols="45" class="textarea-msg" name="text" maxlength="700"></textarea>
   <div id="message_send_error" class="form_error"></div>
    <button type="submit" class="msg-send">Отправить сообщение</button>
   </form>
   
   <form onsubmit="panel.user.cabinet.file(this, 'customer'); return false;" method="post" enctype="multipart/form-data">
   <div class="msg-buttons">
	    <input id="file" style="border-radius: 5px;background-color: rgb(118, 117, 124);padding: 19px 0px 19px 20px;text-align: center;color: #fff;margin-right: 10px;" type="file" name="fileToUpload" >
	    <button class="msg-send-file" type="submit" name="upload" >Отправить файл</button>
	 
 <span class="span_info span_watch" style="margin-top:-10px;height: 14px;width: 14px;" data-toggle="tooltip" data-placement="right" data-original-title="Макс размер - 1МБ   Поддерживаемые форматы - jpg, jpeg, png, zip, rar"><i class="fa fa-question-circle fa-question-circlecolor" aria-hidden="true"></i></span>
   </div>  
   </form> 
  </div>
	 </div>
</div>
		{{IF:MESSAGES}}
			{{FOR:MESSAGES}}
			<div class="promocode-table" style="padding: 0px 30px 0px 30px;margin-top: -35px;">
			<div class="key-dialog">
   <div class="key-dialog-header">
    <p class="key-date"><b>Дата:</b> {mdate} </p> 
    <p class="key-author"><b>Автор:</b> {person} {author}</p>
    <p class="key-status">Статус: <span class="key-not-read">{status}</span></p>
   </div> 
   <div class="key-comment-user">
    <p>{text}</p>   
   </div>
  </div>
			</div>
			{{ENDFOR:MESSAGES}}	
		{{ELSE:MESSAGES}}{{ENDIF:MESSAGES}}
	{{ENDCASE:CONTACT_SELLER}}
	{{CASE:PRODUCT}}
	<div class="promocode-table">
		 <div class="user_info">
 <div class="seller_profile"><a href="/customer/{h}"><i class="fa fa-user" aria-hidden="true"></i>Кабинет покупателя</a></div>
 		{{IF:DISCOUNT}}
 		 <div class="your_discount">Ваша скидка составляет: <span class="discount-color">{discount} ({discountper}%)</span></div>
					{{ELSE:DISCOUNT}}{{ENDIF:DISCOUNT}}
 </div>
  <div class="key-info">
 <div class="ket-title-border">  
  <div class="key-title">
   <div class="key-name">{name} <a href="/product/{product_id}/" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a></div>
  </div>
  </div>   
 <div class="key-invoice">Счёт <span class="key-invoice-number">№{i}</span> (Выписан {date}), Оплачено: <span class="key-cost">{price}</span></div>
 <div class="item-info-about">
   <div class="item-purchased-info">
     <p style="margin: 0 auto;">ОПЛАЧЕННЫЙ ТОВАР :</p>
     <i class="fa fa-check-circle" aria-hidden="true"></i>  
   </div> 
   <div class="item-purchased-info-text">
     <p>{{IF:OBJECT}}{object}				
		{{ELSE:OBJECT}}Скачать: <a href="/download/{i}/{h}/" target="_blank">{object}</a>{{ENDIF:OBJECT}}</p>
   </div>
  </div>
 <div class="item-info">
 <div class="item-seller-info">
    <div class="item-seller-info-header">
     <p class="about-seller">О продавце</p>
     <p class="all-items"><a href="/seller/{seller_id}/">Все товары</a></p>
    </div> 
    <div class="item-seller-info-inner">
     <p><span class="item-seller-info-inner-first">Псевдоним:</span> {login}</p>
    </div>
   </div>
 <div class="item-seller-warning">
     <div class="item-seller-warning-header">
      <div class="item-seller-warning-header-title">
       <p>Внимание</p>  
      </div>
      <div class="item-seller-warning-header-check">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-inner">
       <p>Обращаем внимание, что при возникновении споров администрация торговой площадки рассматривает вашу переписку с продавцом только в рамках данной страницы сервера payarea24.com. Никакие выдержки из переписки посредством email, icq и т.п. не рассматриваются. Любые просьбы продавца вести переписку по отзыву через внешние системы обмена сообщениями настоятельно рекомендуем игнорировать!</p>
     </div>
   </div>
 
					{{IF:NOPROMOCODE}}{{ELSE:NOPROMOCODE}}
					<div class="seller-gift">
    <p><i class="fa fa-gift" aria-hidden="true"></i> Продавец дарит вам промокод для получения скидки при следующей покупки: <b>{promocode_code}</b></p> 
    <a target="_blank" href="/checkpromocode/{promocode_code}/" style="color: #145374;">Узнать перечень товаров и размеры скидок</a>  
   </div>
					
					{{ENDIF:NOPROMOCODE}}
					</div>
					
					<div class="key-notofications">
						{{SWITCH:unsubscribe1}}
				{{CASE:common}}<span>Вы отписаны от получения уведомлений на E-mail. </span><a target="_blank" href="/customer/unsubscribe/{unsubscribeKey}/">ПОДПИСАТЬСЯ</a>{{ENDCASE:common}}
				{{CASE:un}}
					<span>Отписаться от получения уведомлений на E-mail по изменениям в этом заказе</span>
					<button class="unfollow" onclick="module.customer.unsubscribe.order(this);">Отписаться</button>
				{{ENDCASE:un}}
				{{CASE:already}}<span>Вы отписаны от получения уведомлений на E-mail по изменениям в этом заказе</span>{{ENDCASE:already}}
			{{ENDSWITCH:unsubscribe1}}
  </div>
  
  <div class="admin-warning">
   <div class="admin-warning-header">Внимание</div>
   <p>Администрация <a href="https://payarea24.com/" target="_blank" style="color: #494949">payarea24.com</a> обращается к вам с просьбой оставить отзыв о приобретенном товаре. Если товар некачественный или не соответствует описанию, поставьте оценку <span style="color:#FF6C34;">«<b>плохо</b>»</span> и укажите, что именно вас не устроило. Необоснованные отзывы, а также отзывы, содержащие нецензурные выражения, аннулируются. Если у вас нет претензий к товару, поставьте оценку <span style="color:#38D014;">«<b>хорошо</b>»</span> и прокомментируйте это в отзыве в свободной форме. Обращаем внимание, что данный отзыв может быть изменён вами в любое время, однако отзыв с оценкой «плохо» может быть оставлен не позднее 30 дней с момента продажи.</p>
  </div>
		
		{{SWITCH:REVIEW}}
		{{CASE:FORM}}
	<form class="ws_review_send_form" name="review_send" onsubmit="module.customer.review.send(this); return false;">
		 <div class="grade-comment">
   <div class="grade">
    <div class="grade-header">Ваша оценка:</div>  
    <div class="align-vertical">
    <div class="radio-check-grade"> 
    <label class="radio-check">
    <input class="radio" type="radio" name="evaluation" value="good" checked="checked">
    <span class="good"></span>
    <span class="label goodcolor">Хорошая</span>
    </label>
    <label class="radio-check badcolormargin">
    <input class="radio" type="radio" name="evaluation" value="bad">
    <span class="bad"></span>
    <span class="label badcolor">Плохая</span>
    </label>
    </div>    
    </div>
   </div>
   <div class="comment">
     <div class="comment-header">
      <p class="comment-header-text">Ваш отзыв:</p>  
      <a class="comment-dialog" href="/customer/{i}/messages/{h}/"><i class="fa fa-question-circle fa-question-circlecolor" aria-hidden="true"></i> Переписка с продавцом {message_icon_product}</a>
     </div>
     <div class="comment-body">
      <div class="comment-inner">
       <textarea cols="45" name="text" maxlength="500"></textarea>
      </div>
     </div>
   </div>
  </div>
  <div id="review_send_error" class="form_error"></div>
    <button class="comment-send-review" type="submit">Сохранить отзыв</button>
		</form>
	
		{{ENDCASE:FORM}}
		{{CASE:REVIEW}}
		<div class="grade-comment">
   <div class="comment"> 
     <div class="comment-header">
      <p class="comment-header-text">Ваш отзыв:</p>    
      <a class="comment-dialog" href="/customer/{i}/messages/{h}/"><i class="fa fa-question-circle fa-question-circlecolor" aria-hidden="true"></i> Переписка с продавцом {message_icon_product}</a>
     </div>
    <div class="comment-body"> 
    <div class="comment-info"> 
    <p class="comment-id"><span class="comment-info-bold">ID:</span> {review_id}</p> 
    <p class="comment-rating">
	    <span class="comment-info-bold">Оценка покупателя:</span> 
	    <span class="review-good{evaluation}">Положительная <i class="fa fa-thumbs-up" aria-hidden="true"></i></span>
	    <span class="review-bad{evaluation}">Отрицательная <i class="fa fa-thumbs-down" aria-hidden="true"></i></span>
	</p> 
    <p class="comment-date"><span class="comment-info-bold">Дата:</span> {review_date}</p> 
    <div class="comment-right"> 
    <a class="cancel-edit" id="review_change_button" onclick="module.customer.review.change_prepare(); return false;"><i class="fa fa-external-link" aria-hidden="true"></i>Редактировать</a> 
    <a class="remove-comment" onclick="module.customer.review.del.popup(this, {review_id});"><i class="fa fa-trash-o" aria-hidden="true"></i>Удалить</a> 
    </div> 
    </div> 
    <div class="comment-inner-text"> 
    <div class="comment-inner-customer-comment{evaluation}">
	    <i class="fa fa-exclamation-circle" aria-hidden="true"></i><p class="inline-p">{review_text}</p></div> 
    </div> 
    </div>
    {{IF:REVIEW_ANSWER}}
		<div class="comment-inner-seller-comment">
			<div id="review_text">
			<i class="fa fa-user" aria-hidden="true"></i>
			<p class="inline-p"><span class="bold-answer">Ответ продавца:</span> {review_answer_text}</p>
			</div>
		</div>
		{{ELSE:REVIEW_ANSWER}}
		<div class="comment-inner-seller-comment">
			<i class="fa fa-user" aria-hidden="true"></i>
			<p class="inline-p"><span class="bold-answer">Ответ продавца:</span> комментарий отсутствует
				<span class="admin-msg-if-not-reply">Если у Вас возникли проблемы связанные с качеством товара, Вам первоначально следует обратиться непосредственно к продавцу, через раздел "Переписка с продавцом". Если в течение суток (до {review_answer_must}) продавец не свяжется с вами, оставив здесь комментарий к вашему отзыву, мы рекомендуем вам сообщить в <a href="https://payarea24.com/arbitrage" target="_blank">Арбитраж</a> сервиса.</span></p></div> 
		{{ENDIF:REVIEW_ANSWER}}
   </div>
  </div>
		<div id="review_change" style="display:none;">
<form name="review_send" onsubmit="module.customer.review.change(this); return false;">
	<div class="grade-comment" >
	<div class="grade">
    <div class="grade-header">Изменение оценки:</div>  
    <div class="align-vertical">
    <div class="radio-check-grade"> 
    <label class="radio-check">
    <input class="radio" type="radio" name="evaluation" value="good" checked="checked">
    <span class="good"></span>
    <span class="label goodcolor">Хорошая</span>
    </label>
    <label class="radio-check badcolormargin">
    <input class="radio" type="radio" name="evaluation" value="bad">
    <span class="bad"></span>
    <span class="label badcolor">Плохая</span>
    </label>
    </div>
    </div>
   </div>
   <div class="comment">
     <div class="comment-header">
      <p class="comment-header-text">Изменение отзыва:</p>  
     </div>
     <div class="comment-body">
      <div class="comment-inner">
       <textarea cols="45" name="text" maxlength="500">{review_change_text}</textarea>
      </div>
     </div>
   </div>
  </div>
  <div id="review_change_error" class="form_error"></div>
  <div id="reviewSubmit">
  <button class="comment-send-review" type="submit">Сохранить отзыв</button>
  </div>
</form>
  </div>
		
		{{IF:REVIEW_BAD}}
		<div class="item-seller-warningab" style="margin-top: 20px;">
     <div class="item-seller-warning-headerab">
      <div class="item-seller-warning-header-titleab">
       <p>Внимание</p>  
      </div>
      <div class="item-seller-warning-header-checkab">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-innerab" style="min-height: 0px;">
    Жалоба в арбитраж принимается только при наличии видеозаписи от начала покупки, до проверки товара.
     </div>
   </div>
		 <div class="complaint_warning"> 
 <div class="warning">
  <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>Всю дальнейшую переписку с продавцом следует вести в закладке "Переписка с продавцом". Если переговоры зайдут в тупик, диспут будет проанализирован администратором торговой площадки и по результатам будет вынесено решение. В свою очередь, обращаем внимание, что если в течение трех рабочих дней  от вас не поступит ответ на адресованное вам сообщение, это может быть расценено администратором как отказ от разрешения конфликта с последующим аннулированием претензии.</p>    
 </div>
 </div> 
		{{ELSE:REVIEW_BAD}}{{ENDIF:REVIEW_BAD}}

		{{ENDCASE:REVIEW}}

		{{CASE:REVIEWDELETE}}
		<div class="grade-comment">
   <div class="comment"> 
     <div class="comment-header">
      <p class="comment-header-text">Ваш отзыв:</p>    
      <a class="comment-dialog" href="/customer/{i}/messages/{h}/"><i class="fa fa-question-circle fa-question-circlecolor" aria-hidden="true"></i> Переписка с продавцом {message_icon_product}</a>
     </div>
    <div class="comment-body"> 
    <div class="comment-info"> 
    <p class="comment-id"><span class="comment-info-bold">ID:</span> {review_id}</p> 
    <p class="comment-rating">
	    <span class="review-bad0">Отзыв был удален {review_del_who} </span>
	</p> 
    <p class="comment-date"><span class="comment-info-bold">Дата:</span> {review_del_date}</p> 
    </div> 
    <div class="comment-inner-text"> 
    <div class="comment-inner-customer-comment-delete">
	    <i class="fa fa-exclamation-circle" aria-hidden="true"></i><p class="inline-p">{review_text}</p></div> 
    </div> 
    </div>
      </div>
  </div>
		{{IF:REVIEW_DELETE_ADMIN}}
		<div class="item-seller-warning" style="margin-top: 20px;">
     <div class="item-seller-warning-header">
      <div class="item-seller-warning-header-title">
       <p>Внимание</p>  
      </div>
      <div class="item-seller-warning-header-check">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-inner" style="min-height: 0px;">
     <label class="radio-check">
    <input class="radio" type="radio" value="bad" checked="checked">
    <span class="bad"></span>Удаляя отрицательный отзыв я подтвердил(а), что возникший конфликт с продавцом исчерпан и я не имею претензий к продавцу и товару. <br>Я осведомлен, что после удаления отзыва я не смогу его повторно разместить или изменить на положительный.
     </label>
     </div>
   </div>
		{{ELSE:REVIEW_DELETE_ADMIN}}{{ENDIF:REVIEW_DELETE_ADMIN}}

		{{ENDCASE:REVIEW}}
		{{CASE:REVIEW_NO}}<p>Отзыв был удален</p>{{ENDCASE:REVIEW}}

		{{ENDSWITCH:REVIEW}}
		
		</div>
	</div>

	{{ENDCASE:PRODUCT}}
	
	{{CASE:WAITPRODUCT}}
		<div style="text-align:center;font-weight:bold"><br>
			{{IF:EXPIRED}}
				<div id="wait_payment_div"></div>
				Ожидание поступления оплаты<br>
				Страница перезагрузится автоматически<br>
				<img src="/stylisation/images/loader.gif">
			{{ELSE:EXPIRED}}Время резервации истекло{{ENDIF:EXPIRED}}
		</div>
    {{IF:PAY_METHOD_QIWI}}
    <div class="inner-page-login" style="margin-top: 20px;margin-bottom: 20px;">
        <div class="item-seller-warning">
            <div class="item-seller-warning-header">
                <div class="item-seller-warning-header-title">
                    <p>Внимание</p>
                </div>
                <div class="item-seller-warning-header-check">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </div>
            </div>
            <div class="item-seller-warning-inner" style="min-height: 0px;">Перевод должен быть осуществлен на номер <b
                        style="color: #ffa200;font-size: 16px;">{qiwi_account}</b> с указанием верной переданной суммой и
                комментарием к платежу в течение 30 минут. В ином случае, платеж зачислится не верно.
            </div>
        </div>
    </div>
    {{ELSE:PAY_METHOD_QIWI}}{{ENDIF:PAY_METHOD_QIWI}}
	{{ENDCASE:WAITPRODUCT}}
	{{CASE:PROMOCODES}}
	<div class="promocode-table">
	<div class="user_info">
 <div class="seller_profile"><a href="/customer/{h}"><i class="fa fa-user" aria-hidden="true"></i>Кабинет покупателя</a></div>
 </div>
 <div class="key-info">
	 
  <div class="ket-title-border">  
  <div class="key-title">
   <div class="key-name">Ваши промокоды:</div>
  </div>
  </div> 
				{{IF:NOPROMOCODESLIST}}
				<div class="promo-warning">
   <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Промокодов не обнаружено</p>  
   <p> Некоторые продавцы в качестве вознаграждения после покупки товара могут автоматически выдавать промокод. Все они хранятся именно здесь. </p>
 </div>
				{{ELSE:NOPROMOCODESLIST}}
				<div class="promocode-table">
 <table>
  <thead>
  <tr class="table-header">
   <th>Промокод</th>    
   <th>Продавец</th>    
   <th>Использовать до</th>    
   <th>Использование</th>    
  </tr> 
  </thead>
						{{FOR:PROMOCODESLIST}}
						 <tbody>
  <tr class="tr-table">
	  <td aria-label="Промокод" class="game-name"><a target="_blank" href="/checkpromocode/{code}/">{code}</a></td>
	  <td aria-label="Продавец" class="table-price"><a href="/seller/{seller_id}/" target="_blank">{seller}</a></td>
	  <td aria-label="Использование до" class="date-name">{dateend}</td>
	  <td aria-label="Использование" class="table-price">{using}</td></tr>
  </tbody> 
 </table>  
						{{ENDFOR:PROMOCODESLIST}}
					</div>
				{{ENDIF:NOPROMOCODESLIST}}
<div class="item-seller-warning">
     <div class="item-seller-warning-header">
      <div class="item-seller-warning-header-title">
       <p>Общие правила использования промо-кодов</p>  
      </div>
      <div class="item-seller-warning-header-check">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-inner" style="min-height: 0px;">
    1. Промокод – это уникальный набор символов, предоставляющий право на получение скидки при оплате товаров. <br>
		Список товаров, на которые распространяется скидка и размер скидки определяются продавцом. <br>
		Промокод не является именным и подлежит свободной передаче другому лицу (является предъявительским).<br>
		Для получения скидки по промокоду его необходимо ввести в специальное поле в форме на странице оплаты товара.<br>
		Скидка по промокоду не суммируется с предложениями по другим промокодам и другими скидками и акциями.<br>
		Скидка по промокоду не подразумевает возврат суммы скидки в денежном эквиваленте в любых формах.<br>
	2. Промокод может быть использован до указанной даты. Промокоды, неиспользованные до указанной даты, становятся недействительными.
		Возврат и обмен товара приобретённого со скидкой по промокоду осуществляется в соответствии с действующими правилами покупки в интернет-магазине.
     </div>
   </div> 
   </div>
	</div>
	{{ENDCASE:PROMOCODES}}
{{ENDSWITCH:CABINET}}
</div>
