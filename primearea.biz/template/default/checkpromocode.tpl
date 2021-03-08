<div class="inner-page-login">   
 <div class="login">
  <div class="logo-login"></div>
  <div class="text-login">Проверка промокода</div> 
 </div>
 <div class="warning-login">
  <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Для получения списка товаров, которые можно оплатить со скидкой используя промо-код, пожалуйста, укажите его номер и число на картинке.</p>    
 </div>
<form onsubmit="module.checkpromocode.check(this); return false;">
 <div class="input-field login-form">
  <input class="input-login" type="text" name="code" maxlength="32" value="{code}">
  <div class="input-login-comment">Введите Промокод</div>
 </div>     
 <div class="input-field login-form">
  <input class="input-login"  type="text" name="captcha">
  <div class="input-login-comment">Код с картинки</div>
 </div>  
 <div id="module_checkpromocode_check_error"></div>  
  <div class="login-captcha">  
  <div class="captcha"><img id="module_checkpromocode_check_captcha" src="/modules/captcha/captcha.php?{random}"></div>
  <button class="login-button" name="button">Проверить</button>
  </div>
  </form>

 </div> 

<div id="module_checkpromocode_response"></div>

