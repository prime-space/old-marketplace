var user_cabinetChangeFlag = 0;
function user_cabinetChange(role){
	var a = new Array('Login', 'Fio', 'Phone', 'Skype', 'Icq', 'Email', 'Pass', 'PassDouble', 'WM', 'Wmz', 'Wmr', 'Wme', 'Wmu');
	if(!user_cabinetChangeFlag){
	   for(var i=0;i<a.length;i++){
		  //if(i>2 && document.getElementById("user_cabinet"+a[i]).innerHTML != "")continue;
		  if(role != 'admin' && (a[i] == 'Login' || a[i] == 'Fio' || a[i] == 'Email'))continue;
		  if(role != 'admin' && i>7 && document.getElementById("user_cabinet"+a[i]).innerHTML != "")continue;
		  if(a[i] == 'Pass' || a[i] == 'PassDouble'){
		  	 document.getElementById("user_cabinet"+a[i]).innerHTML = '<input maxlength="18" type="password" id="user_cabinet'+a[i]+'Change" value="#$%|notChanged%" />';
             document.getElementById("user_cabinetPassDoubleDiv").style.display = "block";
			 continue;
		  }
		  document.getElementById("user_cabinet"+a[i]).innerHTML = '<input type="text" maxlength="24" id="user_cabinet'+a[i]+'Change" value="'+document.getElementById("user_cabinet"+a[i]).innerHTML+'" />';	      
	   }	   
	   return user_cabinetChangeFlag++;		
	}
	
	var error = [];
	var out = {};
	var submit = document.getElementById("user_cabinetEditUserSubmit").innerHTML;
	document.getElementById("user_cabinetEditUserSubmit").innerHTML = "Загрузка";
	for(var i=0;i<a.length;i++){
		if(document.getElementById("user_cabinet"+a[i]+"Change") != null)out[a[i]] = strBaseTo(document.getElementById("user_cabinet"+a[i]+"Change").value);
	}
	
	if(isEmpty(out['Pass']))error[error.length] = 'Укажите пароль';
	else {
		if(checkRegex(out['Pass'], "password") && out['Pass'] != "#$%|notChanged%")error[error.length] = 'Неверный пароль: от 6 до 18 символов, буквы, цифры, дефисы и подчёркивания';
		else if(out['Pass'] != out['PassDouble'])error[error.length] = 'Пароли не совпадают';
	}
	
	if(error.length != 0){
		document.getElementById("user_cabinetEditUserSubmit").innerHTML = submit;
		alert(error[0]);
		return;
	}
	
	out['Pass'] = hex_md5(out['Pass']);
	out['PassDouble'] = "";
	out['userId'] = document.getElementById("user_cabinetId").innerHTML;
	
	var res = ajaxxx('panel/glob/userEdit/inDB.php', '&crypt=' + getCookie('crypt') + '&data=' + JSON.stringify(out));
	if(res == true){
		popupOpen("Данные успешно отредактированы");
	}else alert("Ошибка");
	user_cabinetChangeFlag = 0;
	if(urlVar("p", "get") == 'user_cabinet')changePage('p','user_cabinet');
    else admin_userGetUserInfo(out['userId']);
}