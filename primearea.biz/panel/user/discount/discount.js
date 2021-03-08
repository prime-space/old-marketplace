var user_discountArr = new Array();
var user_discountNum = 0;
/*function user_discountReady(){
   user_discountArr = new Array();
   user_discountNum = 0;
   var res = ajaxxx('panel/user/discount/get.php', '&crypt='+getCookie('crypt'));
   if(res == "0")return;
   var resArr = JSON.parse(res);
   user_discountNum = resArr.count;
   for(var i=0;i<resArr.count;i++){
      user_discountArr[i] = i;
	  var newDiv = document.createElement('div');
	  newDiv.innerHTML = user_discountGetDivHTML(i,resArr.money[i],resArr.percent[i]);
      newDiv.setAttribute('id', 'user_discountDiv'+i);
      document.getElementById('user_discountDivMain').appendChild(newDiv);   	  
   }
}*/
function user_discountGetDivHTML(id, money, percent){
	var out = '\
							<tr>\
								<td class="text_align_c padding_10 vertical_align">\
									Если сумма покупок моих товаров больше \
									<input type="text" style="width:80px;" value="'+money+'" maxlength="16" /> <strong>руб.</strong>\
									- скидка составляет\
									<input type="text" style="width:40px;" value="'+percent+'" maxlength="2" /> <strong>%</strong> \
									<button class="btn btn-danger btn-sm" onclick="panel.user.discount.glob.field.del(this);return false;">  <i class="icon-minus icon-white"></i> Удалить</button>\
								</td>\
							</tr>\
	';
	return out;
}
function user_discountDel(id){
   var div = document.getElementById('user_discountDiv'+id);
   document.getElementById('user_discountDivMain').removeChild(div);
   user_discountArr.splice($.inArray(id, user_discountArr),1);
}
function user_discountAdd(){
	if(user_discountArr.length == 10)return;
	user_discountArr[user_discountArr.length] = user_discountNum;
	var newDiv = document.createElement('div');
	newDiv.innerHTML = user_discountGetDivHTML(user_discountNum, '', '');
    newDiv.setAttribute('id', 'user_discountDiv'+user_discountNum);
    document.getElementById('user_discountDivMain').appendChild(newDiv);
    user_discountNum++;
}
function user_discountSend(){
	var error = 0;
	var errorDiv;
	var money;
	var percent;
	var out = '{';
	for(var i=0;i<user_discountArr.length;i++){
		document.getElementById('user_discountError'+user_discountArr[i]).innerHTML = '';
		money = document.getElementById('user_discountMoney'+user_discountArr[i]).value;
		percent = document.getElementById('user_discountPercent'+user_discountArr[i]).value;
		if(isEmpty(money)){
			error = 1;
			document.getElementById('user_discountError'+user_discountArr[i]).innerHTML += '<p>Введите значение цены</p>';
        }else if(checkRegex(money, "price")){
			error = 1;
			document.getElementById('user_discountError'+user_discountArr[i]).innerHTML += '<p>Неверный формат цены, пример: 12 или 356.01</p>';			
		}
		if(isEmpty(percent)){
			error = 1;
			document.getElementById('user_discountError'+user_discountArr[i]).innerHTML += '<p>Введите проценты</p>';
        }else if(checkRegex(percent, "int")){
			error = 1;
			document.getElementById('user_discountError'+user_discountArr[i]).innerHTML += '<p>Неверный формат процентов</p>';			
		}
		if(i>0)out+=',';
		out += '"'+i+'":["'+money+'","'+percent+'"]';
	}
	out += '}';
	if(!error){
		var res = ajaxxx('panel/user/discount/save.php', '&crypt='+getCookie('crypt')+'&str='+out);
		if(res == true){
			popupOpen("Данные успешно сохранены");
			changePage('p','user_discount');
		}else alert("Ошибка");
	}
}