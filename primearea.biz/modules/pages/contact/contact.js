function demandSend(){
	var name = document.getElementById('demandName').value;
	var email = document.getElementById('demandEmail').value;
	var title = document.getElementById('demandTitle').value;
	var text = document.getElementById('demandText').value;
	var code = document.getElementById('demandCode').value;
	var error = [];
	var errorOut = "";
	var submit = $('#demandSubmit').html();
	
	$('#demandSubmit').html("Загрузка");
	
	if(isEmpty(name))error[error.length] = 'Укажите Имя';
	if(isEmpty(email))error[error.length] = 'Укажите Email';
	else if(checkRegex(email, "email"))error[error.length] = 'Неверный формат email';
	if(isEmpty(title))error[error.length] = 'Укажите заголовок сообщения';
	if(isEmpty(text))error[error.length] = 'Внесите текст сообщения';
	
	
	if(error.length != 0){
		if(ajaxxx("modules/pages/contact/send.php","&flag=error&code=" + code) == "error")error[error.length] = 'Неверный код с картинки';
		for(var i = 0; i < error.length; i++){
			errorOut += "<p>" + error[i] + "</p>";
		}
        $('#demandCaptcha').html("<img src=\"modules/captcha/captcha.php?" + Math.random() + "\">");
		$('#demandError').html(errorOut);
	}
    else {
	   $('#demandError').html("");
	   name = name.replace(/\&/gi, '{.-amp;-.}');
	   name = name.replace(/\+/gi, '{.-plus;-.}');
	   title = title.replace(/\&/gi, '{.-amp;-.}');
	   title = title.replace(/\+/gi, '{.-plus;-.}');
	   text = text.replace(/\&/gi, '{.-amp;-.}');
	   text = text.replace(/\+/gi, '{.-plus;-.}');
	   var ret = ajaxxx("modules/pages/contact/send.php","&flag=send&code=" + code + "&email=" + email + "&name=" + name + "&title=" + title + "&text=" + text);
	   if(ret == "error"){
	   	  $('#demandError').html("Неверный код с картинки");
	      $('#demandCaptcha').html("<img src=\"modules/captcha/captcha.php?" + Math.random() + "\">");
	   }
	   else {
	   	  $('#demandSubmit').html("Сообщение отправлено");
	      return;
	   }
	}
	
	$('#demandSubmit').html(submit);

}