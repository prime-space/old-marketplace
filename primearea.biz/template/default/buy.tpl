<div id="addProductStepOne">
<div class="payment-container">  
 <div class="payment-header">
  <div class="payment-left"><p><span class="payment-left-green"><i class="fa fa-tag" aria-hidden="true"></i>Купить:</span>{product_name}</p></div> 
  <div class="payment-right"><p>В наличии</p></div>   
 </div>
 <div class="ket-title-border"> 
  <div class="payment-cost"><p>К оплате: <span id="buy_price">{price}</span></p></div>  
 </div>
 <div class="payment-methods">
   <div class="payment-methods-left" >
	     <select onchange="module.buy.change(this.value);" id="buy_methodselect" data-select="display_none" style="display:none;"> {pay_method}</select>
	    
   </div>  
   <div class="payment-methods-right" style="display: none;">
	  </div> 
 </div> 
 <div class="payment-icons">
	 {pay_pic}
 </div>
 
 <div class="ket-title-border" style=" margin-top: -10px; margin-bottom: 20px;"> 
  <div class="payment-costinfo" id="buy_info"></div>  
 </div>

 <div class="separator"></div>
 <div class="discount-block" style="display:{discountDisplay};">
 <p class="discount-block-title">Скидка постоянным покупателям!</p> 
 <div class="discount-block-check">
 <form class="input-field" onsubmit="return false;">
  <input class="payment-check"  type="text" id="productShowDiscountEmail" name="story" maxlength="32" placeholder="Введите ваш E-mail">
  <button class="discount-check-text" type="submit" onclick="module.product.checkdiscount();">Узнать скидку</button> 
 </form> 
 </div>    
 </div> 

 {{IF:NOPROMOCODEUSE}}{{ELSE:NOPROMOCODEUSE}}
 <div class="separator"></div> 
 <div class="promocode-block">
  <p class="promocode-block-title"><b>У вас есть промокод?</b> Значит, вы сможете купить товар дешевле!</p>   
  <form class="input-field">
   <input type="text" name="promocode" class="payment-check" maxlength="16" placeholder="Введите промокод" id="module_buy_promocode_check_code">
   <button class="promocode-check-text" onclick="module.buy.promocode.check(this, false);return false;">Проверить</button>
   <button class="promocode-check-accept" onclick="module.buy.promocode.check(this, true);return false;">Применить</button>
  </form>
  <div id="module_buy_promocode_check_info"></div>
 </div>  
  {{ENDIF:NOPROMOCODEUSE}}
 
 <div class="separator"></div>
 <div style="text-align:center;padding:15px 0;">
  <label>
   <input type="checkbox" id="soglAgree" style="appearance:checkbox;-moz-appearance:checkbox;-webkit-appearance:checkbox;vertical-align:middle;margin-top:-2px;">
   Принимаю условия <a href="/sogl" target="_blank">соглашения</a>
  </label>
 </div>
 <div class="separator"></div>
 <div class="payment-proceed">
  <div class="payment-proceed-title"><b>Внимание!</b> Укажите ваш действующий Е-mail для доставки товара:</div>  
  <form class="input-field" style="border:2px dashed #2cb7ff;" onsubmit="module.buy.send();return false;">
  <input class="payment-check" type="text" maxlength="32" placeholder="Введите ваш E-mail" id="buy_email" value="{email}">  
   <button class="payment-proceed-button" onsubmit="module.buy.send();return false;"><i class="shopping_cart"></i>Продолжить оплату</button> 
   </form> 
   <div id="buy_error"></div> 
 </div>    
 </div> 
 
</div>

<form id="wmerchant" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp">
 <input type="hidden" name="LMI_PAYMENT_AMOUNT">
 <input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="{product_name_base64}">
 <input type="hidden" name="LMI_PAYMENT_NO">
 <input type="hidden" name="LMI_PAYEE_PURSE">
 <input type="hidden" name="h">
</form>

<form id="robokassa" method="POST" action="https://auth.robokassa.ru/Merchant/Index.aspx">
 <input type="hidden" name="MrchLogin" value="{robokassa_MrchLogin}">
 <input type="hidden" name="OutSum">
 <input type="hidden" name="InvId">
 <input type="hidden" name="Desc" value="{product_name}">
 <input type="hidden" name="SignatureValue">
 <input type="hidden" name="IncCurrLabel">
 <input type="hidden" name="Email">
 <input type="hidden" name="Culture" value="ru">
 <input type="hidden" name="shph">
</form>

<form id="interkassa" method="post" action="https://sci.interkassa.com/" accept-charset="UTF-8">
 <input type="hidden" name="ik_co_id" value="{interkassaId}">
 <input type="hidden" name="ik_pm_no">
 <input type="hidden" name="ik_cli">
 <input type="hidden" name="ik_am">
 <input type="hidden" name="ik_cur" value="RUB">
 <input type="hidden" name="ik_sign">
 <input type="hidden" name="ik_desc" value="{product_name}">
</form>

<form id="yandexmoney" method="post" action="https://money.yandex.ru/quickpay/confirm.xml">
 <input type="hidden" name="receiver"">
 <input type="hidden" name="formcomment" value="{product_name}">
 <input type="hidden" name="short-dest" value="{product_name}">
 <input type="hidden" name="quickpay-form" value="shop">
 <input type="hidden" name="paymentType" value="PC">
 <input type="hidden" name="targets" value="{product_name}">
 <input type="hidden" name="sum">
 <input type="hidden" name="label">
 <input type="hidden" name="successURL">
</form>

<form id="paypal" method="post" action= "https://www.paypal.com/cgi-bin/webscr">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business">
<input type="hidden" name="receiver_email">
<input type="hidden" name="item_name">
<input type="hidden" name="item_number">
<input type="hidden" name="amount">
<input type="hidden" name="return">
<input type="hidden" name="cancel_return">
<input type="hidden" name="notify_url">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="no_shipping" value="1">
</form>

<form id="primePayer" method="post" action= "https://primepayer.com/pay">
<input type="hidden" name="shop" value="{primePayerId}">
<input type="hidden" name="payment">
<input type="hidden" name="amount">
<input type="hidden" name="description" value="{product_name}">
<input type="hidden" name="currency" value="3">
<input type="hidden" name="via">
<input type="hidden" name="success">
<input type="hidden" name="email">
<input type="hidden" name="sign">
</form>

<div class="transition_to_payment">
  <div class="payment-container">  
 <div class="ket-title-border"> 
  <div class="payment-costqiwi"><p>Переход на оплату</p></div>  
 </div>
 <div class="separatorqiwi"></div>
 <div class="payqiwinew">
  <div class="payqiwileft">
   <div class="payqiwilogo"></div>
  </div>
  <div class="payqiwiright">
   <div class="payqiwiname">
    <p>QIWI-кошелёк</p>
    <p class="qiwipay2">Оплатить с помощью кошелька QIWI</p>
   </div>
   <div class="payqiwiinfo">
    <div class="payqiwiinfowait">
     <div class="payqiwiiconwait"></div>
     <p class="qiwiwaitp">Не закрывайте эту страницу!<br>
      Мы ждем, пока вы выполните платеж через QIWI-кошелек в новом окне или вкладке браузера.</p>
    </div>
    <div class="payqiwibutton">
      <a class="qiwiLink btnpayqiwi" target="_blank">Оплатить</a>
    </div>
   </div>
  </div>
 </div>
 </div> 
 
</div>
