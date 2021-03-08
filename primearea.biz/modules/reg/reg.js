function reg(){
	var regError = [];
	var regI = 0;
	var regErrorOut = "";
	var regSubmit = $('#regSubmit').html();
	var regLogin = document.getElementById('regLogin').value;
	var regPassword = document.getElementById('regPassword').value;
	var regPasswordRepeat = document.getElementById('regPasswordRepeat').value;
	var regFIO = document.getElementById('regFIO').value;
	var regEmail = document.getElementById('regEmail').value;
	var regCode = document.getElementById('regCode').value;
	var regReturn = "";
	var regCheckUser = ajaxxx("modules/reg/checkUser.php","&login=" + regLogin + "&email=" + regEmail);
	
	$('#regSubmit').html("Загрузка");
	
	if(isEmpty(regLogin))regError[regError.length] = 'Укажите логин';
	else {
		if(checkRegex(regLogin, "login"))regError[regError.length] = 'Неверный логин: от 3 до 16 символов, буквы, цифры, дефисы и подчёркивания';
		else if(regCheckUser == "1" || regCheckUser == "3")	regError[regError.length] = 'Такой логин уже существует';
	}
	
	if(isEmpty(regPassword))regError[regError.length] = 'Укажите пароль';
	else {
		if(checkRegex(regPassword, "password"))regError[regError.length] = 'Неверный пароль: от 6 до 18 символов, буквы, цифры, дефисы и подчёркивания';
		else if(regPassword != regPasswordRepeat)regError[regError.length] = 'Пароли не совпадают';	
	}

	if(isEmpty(regFIO))regError[regError.length] = 'Укажите ФИО';
	else {
		if(checkRegex(regFIO, "fio"))regError[regError.length] = 'Неверный формат ФИО: от 8 до 64 символов, русские и английские буквы';	
	}
	
	if(isEmpty(regEmail))regError[regError.length] = 'Укажите Email';
	else {
		 if(checkRegex(regEmail, "email"))regError[regError.length] = 'Неверный email';
		 else if(regCheckUser == "2" || regCheckUser == "3")	regError[regError.length] = 'Такой Email уже есть в системе';
    }
	if(regError.length != 0){
		if(ajaxxx("modules/reg/inDB.php","&flag=error&regCode=" + regCode) == "error")regError[regError.length] = 'Неверный код с картинки';
		for(regI = 0; regI < regError.length; regI++){
			regErrorOut += "<p>" + regError[regI] + "</p>";
		}
        $('#regCaptcha').html("<img src=\"modules/captcha/captcha.php?" + Math.random() + "\">");
		$('#regError').html(regErrorOut);
	}
    else {
	   $('#regError').html("");
	   regReturn = ajaxxx("modules/reg/inDB.php","&flag=reg&regCode=" + regCode + "&login=" + regLogin + "&password=" + hex_md5(regPassword) + "&email=" + regEmail + "&fio=" + regFIO);
	   if(regReturn == "error"){
	   	  $('#regError').html("Неверный код с картинки");
	      $('#regCaptcha').html("<img src=\"modules/captcha/captcha.php?" + Math.random() + "\">");
	   }
	   else popupOpen("Регистрация прошла успешно");
	}
	$('#regSubmit').html(regSubmit);
}
