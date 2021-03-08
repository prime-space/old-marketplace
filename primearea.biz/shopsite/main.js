var primearea_shopid = '{id_shop}';
var primearea_callbacks=new Object();
var primearea_main;
var primearea_page;
window.onload = function() {
  primearea_main = document.getElementById('primearea_main');
  primearea_main.innerHTML = '<div class="currentListSelForm"><select id="primearea_currentListSel" onchange="primearea_currentList(this.value);"><option value="1">USD</option><option value="2">ГРН</option><option value="3">EUR</option><option value="4" selected="true">РУБ</option></select></div>';
  primearea_page = document.createElement('div');
  //primearea_page.className = 'my-class';
  primearea_main.appendChild(primearea_page);
  if(getCookie("curr") != 'undefined')document.getElementById('primearea_currentListSel').options[(getCookie("curr")-1)].selected = 'true';
  primearea_viewPage();
}
function primearea_currentList(v){
	setCookie('curr', v);
	primearea_viewPage();
}
function primearea_getlist(){
	var curr = document.getElementById('primearea_currentListSel').value;
	getJSONP('{site_addr}shopsite/getlist.php?id='+primearea_shopid+'&curr='+curr,primearea_writelist);
}
function primearea_getproduct(prid){
	var curr = document.getElementById('primearea_currentListSel').value;
	getJSONP('{site_addr}shopsite/getproduct.php?id='+primearea_shopid+'&prid='+prid+'&curr='+curr,primearea_writeproduct);
}
function primearea_writeproduct(res){
	if(res.err == 1){primearea_page.innerHTML = "Ошибка"; return;}
	primearea_page.innerHTML = '<div class="btn btn_my_site" onclick="primearea_changePage(\'\',\'\');">К списку товаров.</div>\
                                   <div class="FormPage">\
					<div class="nameFormPage"><H1>'+res.name+'</H1></div>\
	                            <div class="priceFormPage"><strong>Цена:</strong> '+res.price+'</div>\
	                            <div class="saleFormPage"><strong>Продано:</strong> '+res.sale+'</div>\
	                            <div class="status"><div class="left">Статус:&nbsp;</div><div class="'+res.available+'">'+res.available_text+'</div></div>\
	                            <div class="descriptFormPage">\
                                       <div class="formPicture">'+ res.picture +'</div>\
                                       <p class="formText"><strong>Описание:</strong> '+res.descript+'</p>\
                                   <div class="clr"></div></div>\
	                            <div class="infoFormPage"><strong>Дополнительная информация:</strong> '+res.info+'</div>\
					<div class="buyformdivFormPage" id="primearea_buyformdiv" style="display:'+res.button+';">\
						<a href="{site_addr}?p=buy&productid='+res.id+'" target="_blank"><img border="0" src="{site_addr}stylisation/images/buy.png"></a>\
					</div>\
					<div class="buyAnswerFormPage" id="primearea_buyAnswer" style="display: none">Заказ принят, будет отправлен на email после поступления оплаты.</div>\
					</div>';
}
function primearea_writelist(res){
  primearea_page.innerHTML = "";
  if(res.rows == 0){primearea_page.innerHTML = "Добавьте товары"; return;};
  
  var newDiv =[];
  
  for(var i=0;i<res.rows;i++){
     newDiv[i] = document.createElement('div');
     //newDiv.className = 'my-class';
     newDiv[i].id = 'primearea_listEl'+res.id[i];
     newDiv[i].innerHTML = '<div class="Formlist">\
                            <div class="nameFormlist">'+res.name[i]+'</div>\
                            <div class="priceFormlist">Цена '+res.price[i]+'</div>\
                            <div class="clr"></div>\
				</div>';
	 newDiv[i].onclick = function(event) {
	 	event = event || window.event;
		var prid = this.id.replace('primearea_listEl', '');
		primearea_changePage('p,prid','product,'+prid);
	 }
     primearea_page.appendChild(newDiv[i]);  	
  }

  
}
function getJSONP(url,cb) {
  var i;
  do
    i='c'+Math.floor(Math.random() * 99999);
  while(primearea_callbacks[i]);

  primearea_callbacks[i] = function(obj) {
                      cb(obj);
                      delete primearea_callbacks[i];
                    };
  if(document.getElementById('getJSONPcallback'))document.body.removeChild(document.getElementById('getJSONPcallback'));
  
  var script=document.createElement('script');
  script.src=url+(url.indexOf('?')>=0?'&':'?')+'primearea_callback=primearea_callbacks.'+i;
  script.type='text/javascript';
  script.id = 'getJSONPcallback';
  document.body.appendChild(script);
}
function primearea_changePage(vares,values){
	primearea_urlVar(vares, 'set', values);
	primearea_viewPage();
}
function primearea_viewPage(){
	var p = primearea_urlVar("p", "get");
    switch(p){
		case 'product':
		   primearea_getproduct(primearea_urlVar("prid", "get"));
		   break	
		default:
           primearea_getlist();
    	   break
    }
}
function primearea_urlVar(varSearch, method, value){

   var tmp = new Array();		// два вспомогательных
   var tmp2 = new Array();		// массива
   var param = new Array();

   if(method == "set"){ //метод set
        if(varSearch == ""){history.pushState(null, null, "#!p=main"); return;}
		varSearch = varSearch.split(",");   //делим переменные и значения на массив
		value = value.split(",");
		for(i=0;i<varSearch.length;i++){ 
		   param[varSearch[i]] = value[i]; //присваиваем переменным значания
		}
		var returnSearch = "#!";  //в начало строки
		for(var key in param){
			returnSearch += key+"="+param[key]+"!"; //выводим переменные в строку
		}
		returnSearch = returnSearch.slice(0, -1); //удаляем последний символ "&"
		history.pushState(null, null, returnSearch);
		return;
   } 
     
      var get = location.href;	// строка GET запроса
   	  get = get.split('#!');
	  if(get.length == 1)return;
   	  get = get[1];
	  tmp = get.split('!');	// разделяем переменные
	  for(var i=0; i < tmp.length; i++) {
	     tmp2 = tmp[i].split('=');		// массив param будет содержать
	     param[tmp2[0]] = tmp2[1];		// пары ключ(имя переменной)->значение
      }
	  return param[varSearch];//если метод get - выдать переменную
   
}
function setCookie(a, b) {
    document.cookie = a + "=" + b + "; path=/"
}
function getCookie(a) {
    var b = a + "=";
    var d = document.cookie.split(';');
    for (var i = 0; i < d.length; i++) {
        var c = d[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(b) == 0) return c.substring(b.length, c.length)
    }
    return "undefined";
}
function delCookie(a) {
    setCookie(a, "", -1)
}
function checkRegex(value, modus) {
   var checkRegexReg = "";
   switch (modus) {
	  case "email":
   	     checkRegexReg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
      break
   }
   if (!value.match(checkRegexReg)) return 1;
}