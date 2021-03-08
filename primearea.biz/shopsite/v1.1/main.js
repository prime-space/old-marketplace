var primearea = {
	shop_id: {shop_id},
	url: '{site_addr}',
	main: false,
	page: false,
	categories: false,
	currency: 4,
	category: 0,
	category_current: 0,
	ready: function(){
		if(!document.getElementById('primearea_main'))return false;
		this.main = document.getElementById('primearea_main');
		this.main.innerHTML = '	<div class="currentListSelForm">\
									<select id="primearea_currency" onchange="primearea.currency_change(this.value);">\
										<option value="1">USD</option>\
										<option value="2">ГРН</option>\
										<option value="3">EUR</option>\
										<option value="4">РУБ</option>\
									</select>\
								</div>';
		this.page = document.createElement('div');
		this.main.appendChild(this.page);
		if(this.getCookie('primearea_currency') != 'undefined')this.currency = this.getCookie("primearea_currency");
		document.getElementById('primearea_currency').options[this.currency-1].selected = 'true';
		this.page_view();
		if(document.getElementById('primearea_category')){
			this.category = document.getElementById('primearea_category');
			this.category_show();
		}
	},
	page_view: function(){
		if(this.urlvar("p", "get") == 'product')this.product_show(this.urlvar("prid", "get"));
		else this.product_list_show();	
	},
	category_show: function(){
		this.category.innerHTML = '';
		var result = this.post(this.url+'shopsite/v1.1/category_list_get.php?id='+this.shop_id);
		//console.log(result);
		try{result = JSON.parse(result);}catch (e){console.log('Ошибка'); return false;}
		
		if(result.rows == 0)this.category.innerHTML = 'Не найдено ни одной категории';
		
		var el = [];
		for(var i=0;i<result.rows;i++){
			el[i] = document.createElement('div');
			el[i].className = 'Formlist';
			el[i].innerHTML = '	<div class="category_el">'+result.name[i]+'</div>';
			el[i].onclick = function(id){return function(){
				primearea.category_current = id;
				primearea.change_page();
			}}(result.id[i])
			this.category.appendChild(el[i]);
		}
	},
	product_list_show: function(){
		var result = this.post(this.url+'shopsite/v1.1/getlist.php?id='+this.shop_id+'&currency='+this.currency+'&category='+this.category_current);
		//console.log(result);
		try{result = JSON.parse(result);}catch (e){console.log('Ошибка'); return false;}
		this.page.innerHTML = "";
		if(result.rows == 0){this.page.innerHTML = "Добавьте товары"; return;};
		
		if(this.category_current != 0)this.page.innerHTML = '<div class="btn btn_my_site" onclick="primearea.category_current=0;primearea.change_page();">Показать весь список</div>';
		
		var newDiv =[];
	  
		for(var i=0;i<result.rows;i++){
			newDiv[i] = document.createElement('div');
			newDiv[i].className = 'Formlist';
			newDiv[i].innerHTML = '	<div class="nameFormlist">'+result.name[i]+'</div>\
									<div class="priceFormlist">Цена '+result.price[i]+'</div>\
									<div class="clr"></div>';
			newDiv[i].onclick = function(product_id){return function(){
				primearea.change_page('p,prid','product,'+product_id);
			}}(result.id[i])
			this.page.appendChild(newDiv[i]);  	
		}
	},
	product_show: function(product_id){
		var result = this.post(this.url+'shopsite/v1.1/getproduct.php?id='+this.shop_id+'&product_id='+product_id+'&currency='+this.currency);
		//console.log(result);
		try{result = JSON.parse(result);}catch (e){console.log('Ошибка'); return false;}
		if(result.err == 1){this.page.innerHTML = "Ошибка"; return;}
		this.page.innerHTML = '	<div class="btn btn_my_site" onclick="primearea.change_page();">К списку товаров.</div>\
								<div class="FormPage">\
									<div class="nameFormPage"><H1>'+result.name+'</H1></div>\
									<div class="priceFormPage"><strong>Цена:</strong> '+result.price+'</div>\
									<div class="saleFormPage"><strong>Продано:</strong> '+result.sale+'</div>\
									<div class="status"><div class="left">Статус:&nbsp;</div><div class="'+result.available+'">'+result.available_text+'</div></div>\
									<div class="descriptFormPage">\
										<div class="formPicture">'+ result.picture +'</div>\
										<p class="formText"><strong>Описание:</strong> '+result.descript+'</p>\
										<div class="clr"></div>\
									</div>\
									<div class="infoFormPage"><strong>Дополнительная информация:</strong> '+result.info+'</div>\
									<div class="buyformdivFormPage" id="primearea_buyformdiv" style="margin:0px;display:'+result.button+';">\
										<a href="'+this.url+'?p=buy&productid='+result.id+'" target="_blank"><img border="0" src="'+this.url+'stylisation/images/buy.png"></a>\
									</div>\
									<div id="primearea_review_list"></div>\
								</div>';
		this.review_list_get(0, false, true);
	},
	review_list_pagination: 0,
	review_list_get: function(list_number, method, newly){
		if(newly)this.review_list_pagination = new this.Pagination('primearea.review_list_get', this.url+'modules/shop/productShow/get_review_list.php', 'primearea_review_list', 10);
		var data = {product_id: this.urlvar("prid", "get"), method: method};
		this.review_list_pagination.get(data, list_number, method);
		
	},
	currency_change: function(v){
		this.setCookie('primearea_currency', v);
		this.currency = v;
		this.page_view();
	},
	change_page: function(vares, values){
		vares = vares || '';
		values = values || '';
		this.urlvar(vares, 'set', values);
		this.page_view();	
	},
	Pagination: function(fn_name, handler, list, elements_on_page){
		this.fn_name = fn_name;
		this.handler = handler;
		this.list_id = list;
		this.list = document.getElementById(list);
		this.current_list = 0;
		this.elements_on_page = elements_on_page;
		this.get = function(data, list_number, method){
			if(method == 'add')this.current_list++;
			else this.current_list = list_number || 0;
			if(document.getElementById('elements_on_page_'+this.fn_name))this.elements_on_page = document.getElementById('elements_on_page_'+this.fn_name).value;
			data.current_list = this.current_list;
			data.elements_on_page = this.elements_on_page;
			data.func = this.fn_name;
			data = JSON.stringify(data);
			var result = primearea.post(this.handler, '&data=' + data);
			switch(method){
				case 'add': document.getElementById('list_add_'+this.fn_name).innerHTML += result; break
				//case 'gotoanchor': window.location.href = '#head_'+this.fn_name;
				default: this.list.innerHTML = result;
			}
			if(document.getElementById('load_more_'+this.fn_name) && document.getElementById('pages_'+this.fn_name).innerHTML-1 == this.current_list)
							document.getElementById('load_more_'+this.fn_name).style.display = 'none';
		}	
	},
	urlvar: function(varSearch, method, value){
	   var tmp = new Array();		
	   var tmp2 = new Array();		
	   var param = new Array();
	   
	   if(method == "set"){ 
			if(varSearch == ""){history.pushState(null, null, "#!p=main"); return;}
			varSearch = varSearch.split(",");  
			value = value.split(",");
			for(i=0;i<varSearch.length;i++){ 
			   param[varSearch[i]] = value[i]; 
			}
			var returnSearch = "#!";  
			for(var key in param){
				returnSearch += key+"="+param[key]+"!"; 
			}
			returnSearch = returnSearch.slice(0, -1); 
			history.pushState(null, null, returnSearch);
			return;
	   } 
		  var get = location.href;
		  get = get.split('#!');
		  if(get.length == 1)return;
		  get = get[1];
		  tmp = get.split('!');
		  for(var i=0; i < tmp.length; i++) {
			 tmp2 = tmp[i].split('=');
			 param[tmp2[0]] = tmp2[1];
		  }
		  return param[varSearch];
	},
	setCookie: function(a, b) {document.cookie = a + "=" + b + "; path=/";},
	getCookie: function(a){
		var b = a + "=";
		var d = document.cookie.split(';');
		for (var i = 0; i < d.length; i++) {
			var c = d[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(b) == 0) return c.substring(b.length, c.length)
		}
		return "undefined";
	},
	post: function(handlerPath, parameters){
		parameters = parameters || '';
		try {
			if (window.XMLHttpRequest) req = new XMLHttpRequest();
			else if (window.ActiveXObject) {
				req = new ActiveXObject('Msxml2.XMLHTTP');
				req = new ActiveXObject('Microsoft.XMLHTTP');
			}
			req.open("POST", handlerPath, false);
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			req.send(parameters);
		} catch (e){
			console.log('Ошибка post: '+handlerPath);
			return false;
		}
			return req.responseText;
	}
}
onload = function(){primearea.ready();}