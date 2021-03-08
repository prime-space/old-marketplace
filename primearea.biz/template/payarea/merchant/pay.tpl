{{IF:head}}

{headTitle}

{{ELSE:head}}{{ENDIF:head}}
{{SWITCH:page}}
	{{CASE:error}}
	<div class="promocode-table">
	<div class="item-seller-warning">
     <div class="item-seller-warning-header">
      <div class="item-seller-warning-header-title">
       <p>Внимание</p>  
      </div>
      <div class="item-seller-warning-header-check">
       <i class="fa fa-check-circle" aria-hidden="true"></i>
      </div>   
     </div> 
     <div class="item-seller-warning-inner" style="min-height: 0px;">
      <div class="service_message">{error}</div>
     </div>
   </div>
	</div>
	{{ENDCASE:error}}
	{{CASE:page}}
<div class="payment-container">  
 <div class="payment-header">
  <div class="payment-left"><p><span class="payment-left-green"><i class="fa fa-tag" aria-hidden="true"></i>Купить:</span>{description}</p></div> 
  <div class="payment-right"><p>В наличии</p></div>   
 </div>
 <div class="ket-title-border"> 
  <div class="payment-cost"><p class="price merchantpay_pay">К оплате: <span class="amount">{price}</span> руб.</p></div>  
 </div>
 <span class="feebox persale" style="display: none;">Комиссия за осуществление расчетов по переводу составляет <span class="feeamount"></span>руб. (<span class="fee"></span>%)</span>

 <div class="payment-methods">
   <div class="payment-methods-left" >
	 	<select data-select="via" class="selectvia">
			{{FOR:option_paysyss}}
			{dropdown}
			{{ENDFOR:option_paysyss}}
		</select>
	</div>  
   <div class="payment-methods-right" style="display: none;">
	  </div> 
 </div> 
 <div class="payment-icons">
	<div class="imgoplata">
		{{FOR:paysyss}}
		{method}
		{{ENDFOR:paysyss}}
	</div>				
 </div>
 <div class="separator"></div>
 <div class="payment-buttons">
				<form method="POST"  action="/merchant/pay/cancel/">
					<button class="payment-button-decline"><i class="fa fa-times" aria-hidden="true"></i> Отменить</button> 
					<input type="hidden" name="mpaymentId" value="{mpaymentId}">
				</form>
   <form class="merchantpay_form" method="POST" action="/merchant/pay/continue/">
					<button class="payment-button-proceedm"><i class="fa fa-check" aria-hidden="true"></i> Оплатить</button> 
					<input type="hidden" name="via" value="{via}">
					<input type="hidden" name="mpaymentId" value="{mpaymentId}">
				</form>
 </div>  
 
 </div> 
	{{ENDCASE:page}}
	{{CASE:yandexmoney}}
	<div class="redirecttopay">
		Переадресация на платежную систему
	</div>
		<form method="post" action="https://money.yandex.ru/quickpay/confirm.xml">
			<input type="hidden" name="receiver" value="{receiver}">
			<input type="hidden" name="formcomment" value="{formcomment}">
			<input type="hidden" name="short-dest" value="{short-dest}">
			<input type="hidden" name="quickpay-form" value="shop">
			<input type="hidden" name="paymentType" value="PC">
			<input type="hidden" name="targets" value="{targets}">
			<input type="hidden" name="sum" value="{sum}">
			<input type="hidden" name="label" value="{label}">
			<input type="hidden" name="successURL" value="{successURL}">
		</form>
	{{ENDCASE:yandexmoney}}
			{{CASE:interkassa}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form method="post" action="https://sci.interkassa.com" accept-charset="UTF-8">
			<input type="hidden" name="ik_co_id" value="{ik_co_id}">
			<input type="hidden" name="ik_am" value="{ik_am}">
			<input type="hidden" name="ik_pm_no" value="{ik_pm_no}">
			<input type="hidden" name="ik_desc" value="{ik_desc}">
			<input type="hidden" name="ik_pw_via" value="{ik_pw_via}">
			<input type="hidden" name="ik_cur" value="{ik_cur}">
			<input type="hidden" name="ik_act" value="{ik_act}">
			<input type="hidden" name="ik_sign" value="{ik_sign}">
		</form>

	{{ENDCASE:interkassa}}
			{{CASE:webmoney}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form method="post" action="https://merchant.webmoney.ru/lmi/payment.asp">
			<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="{LMI_PAYMENT_AMOUNT}">
			<input type="hidden" name="LMI_PAYMENT_NO" value="{LMI_PAYMENT_NO}">
			<input type="hidden" name="LMI_PAYEE_PURSE" value="{LMI_PAYEE_PURSE}">

			<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="{LMI_PAYMENT_DESC_BASE64}">
		</form>
	{{ENDCASE:webmoney}}
	{{CASE:liqpay}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form method="post" action="https://www.liqpay.com/api/checkout">
			<input type="hidden" name="data" value="{data}">
			<input type="hidden" name="signature" value="{signature}">

		</form>
	{{ENDCASE:liqpay}}
	{{CASE:cardpay}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form method="post" action="{action}">
				<input type="hidden" name="cps_context_id" value="{cps_context_id}">
				<input type="hidden" name="paymentType" value="{paymentType}">
		</form>
	{{ENDCASE:cardpay}}
	{{CASE:paypal}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form id="paypal" method="post" action= "https://www.paypal.com/cgi-bin/webscr">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="{business}">
			<input type="hidden" name="receiver_email" value="{receiver_email}">
			<input type="hidden" name="item_name" value="{item_name}">
			<input type="hidden" name="item_number" value="{item_number}">
			<input type="hidden" name="amount" value="{amount}">
			<input type="hidden" name="return" value="{return}">
			<input type="hidden" name="cancel_return" value="{cancel_return}">
			<input type="hidden" name="notify_url" value="{notify_url}">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="no_shipping" value="1">
		</form>
	{{ENDCASE:paypal}}

	{{CASE:primepayer}}
		<div class="redirecttopay">
			Переадресация на платежную систему
		</div>
		<form id="primePayer" method="post" action= "https://primepayer.com/pay">
			<input type="hidden" name="shop" value="{primePayerShopId}">
			<input type="hidden" name="payment" value="{primePayerPayment}">
			<input type="hidden" name="amount" value="{primePayerAmount}">
			<input type="hidden" name="description" value="{primePayerDescription}">
			<input type="hidden" name="currency" value="{primePayerCurrency}">
			<input type="hidden" name="via" value="{primePayerVia}">
			<input type="hidden" name="fail" value="{primePayerFail}">
			<input type="hidden" name="success" value="{primePayerSuccess}">
			<input type="hidden" name="sign" value="{primePayerSign}">
		</form>
	{{ENDCASE:primepayer}}

	{{CASE:skrill}}
		<div class="redirecttopay">
		Переадресация на платежную систему
		</div>
		<form method="POST" action="{url}">
			<input type="hidden" name="sid" value="{sid}">
		</form>
	{{ENDCASE:skrill}}

	{{CASE:qiwiown}}
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
      <a class="qiwiLink btnpayqiwi" target="_blank" href="{qiwiUri}">Оплатить</a>
    </div>
   </div>
  </div>
 </div>
	{{ENDCASE:qiwiown}}

	{{CASE:return}}
		<div class="redirecttopay">
		Возврат на сайт
		</div>
		<form method="{method}" action="{url}">
			<input type="hidden" name="shopid" value="{shopid}">
			<input type="hidden" name="payno" value="{payno}">
			<input type="hidden" name="amount" value="{amount}">
			<input type="hidden" name="via" value="{via}">
			{{FOR:vars}}<input type="hidden" name="{varname}" value="{varval}">{{ENDFOR:vars}}
		</form>
	{{ENDCASE:return}}

{{ENDSWITCH:page}}
