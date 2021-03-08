$(document).ready(function(){
	$('.btn-toggle-fullwidth').click(function(){
		$('body,html').animate({scrollTop:0},1);
	});
	jsconfig = JSON.parse(jsconfig);
	if(jsconfig.category)module.category.show(jsconfig.category.sg, 1)
	switch(jsconfig.module){
		case 'main': module.main.ready(); break
		case 'category': module.main.ready(); break
		case 'customer': module.customer.ready(); break
		case 'showproduct': module.product.ready(); break
		case 'sellershow': module.seller.ready(); break
		case 'buy': module.buy.ready(); break
		case 'news': module.news.ready(); break
		case 'merchant': merchant.ready(); break
		case 'panel':
			main.swfUploadReady(false, 'SWFuploadPictureDiv3');
			switch(jsconfig.sp){
				case 'myshop': panel.user.myshop.ready(); break
				case 'productedit': panel.user.myshop.edit.ready(); break
				case 'productadd': panel.user.myshop.add.ready(); break
				case 'productobj': panel.user.myshop.edit.obj.ready(); break
				case 'cabinet': panel.user.cabinet.ready(); break
				case 'cashout': panel.user.cashout.ready(); break
				case 'review': panel.user.review.ready(); break
				case 'discount': panel.user.discount.ready(); break
				case 'promocodes': panel.user.promocodes.ready(); break
				case 'shopsite': panel.user.shopsite.ready(); break
				case 'recommended': user_recommended.ready(); break
				case 'messages': panel.user.messages.ready(); break
				case 'partner': panel.user.partner.ready(); break
				case 'sales': panel.user.sales.ready(); break
				
				case 'group': panel.admin.group.ready(); break
				case 'bookkeeping': panel.admin.bookkeeping.ready(); break
				case 'order': panel.admin.order.ready(); break
				case 'news': module.news.ready(); break
				
				case 'messageview': panel.moder.messageview.ready(); break
				case 'newproducts': panel.moder.newproducts.ready(); break
				case 'moderate': panel.moder.moderate.ready(); break
				case 'newusers': panel.moder.newusers.ready(); break
				case 'hiddenproducts': panel.moder.hiddenproducts.ready(); break
			}
			break
	}
   if(document.getElementById('currentListSel') && getCookie("curr") != 'undefined'){
		document.getElementById('currentListSel').options[(getCookie("curr")-1)].selected = 'true';
		$('select:not([multiple])').trigger('refresh');
   }
   //if(document.getElementById('sortListSel'))document.getElementById('sortListSel').options[0].selected = 'true';

	$('.pa_middle_c_r_block  ._group .menu_block_head').on('click',function(e){
		$('.pa_middle_c_r_block  ._group').find('.category_block').hide();
		$(this).parent().find('.category_block').show();
		
		if($(this).parent().find(".category_group .category_block").length){

			if($('body>#categoryShowBackground').length == 0){
				$('body').prepend('<div id="categoryShowBackground" style="position:absolute; background-color:black; width:100%; height:100%; opacity:0; z-index: 10000; cursor: pointer;" ></div>');
				$( "#categoryShowBackground" ).animate({
				    opacity: 0.7,
				  }, 800);
				$('.pa_middle_c_r_block.categorysidetop').css('z-index','10001');
			}
			

		}
		
	
	});
	$('._group').on('mouseover', function(e){
		e.stopPropagation();
	});

	$('body').on('mouseover',function(e){

		if($('.pa_middle_c_r_block  ._group').find('.category_block:visible').length){
			$('.pa_middle_c_r_block  ._group').find('.category_block').hide();

			$( "#categoryShowBackground" ).css('cursor', 'default');
			
			$( "#categoryShowBackground" ).animate({
			    opacity: 0,
			}, 300, function(){
				$('#categoryShowBackground').remove();
			});
			
			$('.pa_middle_c_r_block.categorysidetop').css('z-index','1');
		}
		
	});

	$('body').on('click', '#categoryShowBackground',function(e){
		console.log('click');
		if($('.pa_middle_c_r_block  ._group').find('.category_block:visible').length){
			$('.pa_middle_c_r_block  ._group').find('.category_block').hide();

			$( "#categoryShowBackground" ).animate({
			    opacity: 0,
			}, 300, function(){
				$('#categoryShowBackground').remove();
			});
			
			$('.pa_middle_c_r_block.categorysidetop').css('z-index','1');
		}
		
	});

	var myloader;

	
	$('.last_sales_group').each(function(i, e){
		if(i <= 4){
			$(e).appendTo('.last_sales_group1');
		}else if(i > 4 && i <= 9){
			$(e).appendTo('.last_sales_group2');
		}else if(i > 9 && i <= 14){
			$(e).appendTo('.last_sales_group3');
		}else if(i > 14){
			$(e).appendTo('.last_sales_group4');
		}
	})

	$('.last_sales_group_parent').each(function(){
		$(this).hide();
		if($(this).data('group') == 0){
			$(this).show();
		}
	});

	function groupChanger(type){
		if(type == 'next'){
			
			if($('.last-next').data('group') == 3){
				$('.last-next').data('group', 0)
			}else{
				$('.last-next').data('group', $('.last-next').data('group') + 1)
			}

			if($('.last-prev').data('group') == 3){
				$('.last-prev').data('group', 0)
			}else{
				$('.last-prev').data('group', $('.last-prev').data('group') + 1)
			}
			

		}else if(type == 'prev'){

			if($('.last-next').data('group') == 0){
				$('.last-next').data('group', 3)
			}else{
				$('.last-next').data('group', $('.last-next').data('group') - 1)
			}

			if($('.last-prev').data('group') == 0){
				$('.last-prev').data('group', 3)
			}else{
				$('.last-prev').data('group', $('.last-prev').data('group') - 1)
			}
		}
	}

	$('.last_sales_group_changer').on('click',function(){
		$('.last_sales_group_parent').hide();
		var data_show;
		if($(this).hasClass('last-prev')){
			data_show = $(this).data('group');
			groupChanger('prev');
		}else if($(this).hasClass('last-next')){
			data_show = $(this).data('group');
			groupChanger('next');
		}

		$('.last_sales_group_parent').each(function(){
			if($(this).data('group') == data_show){
				$(this).show();
			}
		})
	})

	if($.fn.bxSlider){
		$('.bxslider').bxSlider({
		   captions: true,
		   slideWidth: 300
		});
	}
	
	if($('#panel_mode').length){
		var panel_mode = getCookie('panel_mode');
		panel_mode == 'undefined' ? setCookie('panel_mode', 'shop') : "" ;
		panel_mode == 'merchant'  ? $('#panel_mode').attr("checked", "checked") : "";

		$('#panel_menu li a').each(function(){
			if(this.href == location.origin+location.pathname+location.hash){
				var spoiler = $(this).parents('div.collapse').prev('a.collapsed');
				if( spoiler.length ){
					spoiler.addClass('active');
					spoiler.removeClass('.collapsed');
					spoiler.attr('aria-expanded','true');
					spoiler.next('div').addClass('in');
				}
				$(this).addClass('active');
			}
		})
	}


	setInterval(function(){

		if( !$('#productsGraph').find('.highcharts-container').length )
			return false;

		var width = $('#productsGraph').find('.highcharts-container').width();

		if( width > 300 && width < 450 ){
			$('#productsGraph').find('.highcharts-legend').attr('transform','translate(20,298)');
			$('#productsGraph').find('.highcharts-legend').attr('style','font-size:75% !important');
			$('#productsGraph').find('.highcharts-title').attr('style','font-size:85% !important');

		}else if( width > 250 && width < 300 ){
			$('#productsGraph').find('.highcharts-legend').attr('transform','translate(5,298)');
			$('#productsGraph').find('.highcharts-legend').attr('style','font-size:55% !important');
			$('#productsGraph').find('.highcharts-title').attr('style','font-size:70% !important');

		}else if( width < 250 ){
			$('#productsGraph').find('.highcharts-legend').attr('transform','translate(0,298)');
			$('#productsGraph').find('.highcharts-legend').attr('style','font-size:35% !important');
			$('#productsGraph').find('.highcharts-title').attr('style','font-size:55% !important');

		}else{

			var legend_width = $('#productsGraph').find('.highcharts-legend-item:first-child').find('text:first-child').width();
			var new_width = width - legend_width;

			if( new_width > 0 ){

				new_width = parseInt(new_width/4);

				$('#productsGraph').find('.highcharts-legend').attr('transform','translate('+new_width+',298)');
				$('#productsGraph').find('.highcharts-legend').attr('style','font-size:96% !important');
				$('#productsGraph').find('.highcharts-title').attr('style','font-size:100% !important');

			}else{
				$('#productsGraph').find('.highcharts-legend').attr('transform','translate(5,298)');
				$('#productsGraph').find('.highcharts-legend').attr('style','font-size:90% !important');
				$('#productsGraph').find('.highcharts-title').attr('style','font-size:100% !important');
			}

		}

	},3000);

	$('body').on('click','.select-mobil .open-computer',function(){

		$(this).parents('.select-mobil').find('.mobil').hide();
		$(this).parents('.select-mobil').find('.computer').show();

		return false;

	});

	$('body').on('click','.select-mobil .close-select',function(){

		$(this).parents('.select-mobil').find('.mobil').show();
		$(this).parents('.select-mobil').find('.computer').hide();

		return false;

	});

	$('#logs_spoiler_show').length ? panel.user.cabinet.show_logs($('#logs_spoiler_show')) : '';
});

function hideloader(){
	if(typeof myloader !== 'undefined'){
		myloader.hide();
	}
	
	if($('#recommended_index').length && $('#SearshForm #search').val() != ''){
		$('#recommended_index').hide();
	}
}

function get_share_button(){
	$('div.share42init').each(function(idx){var el=$(this),u=el.attr('data-url'),t=el.attr('data-title'),i=el.attr('data-image'),d=el.attr('data-description'),f=el.attr('data-path'),fn=el.attr('data-icons-file'),z=el.attr("data-zero-counter");if(!u)u=location.href;if(!fn)fn='icons.png';if(!z)z=0;if(!f){function path(name){var sc=document.getElementsByTagName('script'),sr=new RegExp('^(.*/|)('+name+')([#?]|$)');for(var p=0,scL=sc.length;p<scL;p++){var m=String(sc[p].src).match(sr);if(m){if(m[1].match(/^((https?|file)\:\/{2,}|\w:[\/\\])/))return m[1];if(m[1].indexOf("/")==0)return m[1];b=document.getElementsByTagName('base');if(b[0]&&b[0].href)return b[0].href+m[1];else return document.location.pathname.match(/(.*[\/\\])/)[0]+m[1];}}return null;}f=path('share42.js');}if(!t)t=document.title;if(!d){var meta=$('meta[name="description"]').attr('content');if(meta!==undefined)d=meta;else d='';}u=encodeURIComponent(u);t=encodeURIComponent(t);t=t.replace(/\'/g,'%27');i=encodeURIComponent(i);d=encodeURIComponent(d);d=d.replace(/\'/g,'%27');var fbQuery='u='+u;if(i!='null'&&i!='')fbQuery='s=100&p[url]='+u+'&p[title]='+t+'&p[summary]='+d+'&p[images][0]='+i;var vkImage='';if(i!='null'&&i!='')vkImage='&image='+i;var s=new Array('"#" data-count="fb" onclick="window.open(\'//www.facebook.com/sharer.php?m2w&'+fbQuery+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Поделиться в Facebook"','"#" data-count="gplus" onclick="window.open(\'//plus.google.com/share?url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Поделиться в Google+"','"#" data-count="mail" onclick="window.open(\'//connect.mail.ru/share?url='+u+'&title='+t+'&description='+d+'&imageurl='+i+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Поделиться в Моем Мире@Mail.Ru"','"#" data-count="odkl" onclick="window.open(\'//ok.ru/dk?st.cmd=addShare&st._surl='+u+'&title='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Добавить в Одноклассники"','"//rutwit.ru/tools/widgets/share/popup?url='+u+'&title='+t+'" title="Добавить в РуТвит"','"#" data-count="twi" onclick="window.open(\'//twitter.com/intent/tweet?text='+t+'&url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Добавить в Twitter"','"#" data-count="vk" onclick="window.open(\'//vk.com/share.php?url='+u+'&title='+t+vkImage+'&description='+d+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0\');return false" title="Поделиться В Контакте"','"" onclick="return fav(this);" title="Сохранить в избранное браузера"');var l='';for(j=0;j<s.length;j++)l+='<span class="share42-item" style="display:inline-block;margin:0 3px 3px 0;height:32px;"><a rel="nofollow" style="display:inline-block;width:32px;height:32px;margin:0;padding:0;outline:none;background:url('+f+fn+') -'+32*j+'px 0 no-repeat" href='+s[j]+' target="_blank"></a></span>';el.html('<span id="share42">'+l+'</span>'+'');});function fav(a){var title=document.title;var url=document.location;try{window.external.AddFavorite(url,title);}catch(e){try{window.sidebar.addPanel(title,url,'');}catch(e){if(typeof(opera)=='object'||window.sidebar){a.rel='sidebar';a.title=title;a.url=url;a.href=url;return true;}else{alert('Нажмите Ctrl-D, чтобы добавить страницу в закладки');}}}return false;};
}
function addMonthsUTC (date, count) {
    if (date && count) {
        var m, d = (date = new Date(+date)).getUTCDate()

        date.setUTCMonth(date.getUTCMonth() + count, 1)
        m = date.getUTCMonth()
        date.setUTCDate(d)
        if (date.getUTCMonth() !== m) date.setUTCDate(0)
    }
    return date
}
var file_upload_limitVar = "0";
var textareaList = new Array("text1");
var textareaNum = 1;
function Pagination(fn_name, handler, list, elements_on_page){
	this.fn_name = fn_name+'.get';
	this.handler = handler;
	this.list_id = list;
	this.list = document.getElementById(list);
	this.current_list = 0;
	this.elements_on_page = elements_on_page;
	this.exist = false;
	this.method = 0;
	this.pages_head = 0;
	this.recommended = false;
	this.preloader = false;
	this.button = false;
	this.get = function(data, list_number, method, callback){

		if(!this.list)return;
		var callback = callback || false;
		this.method = method;
		if(this.method == 'add'){
			this.current_list++;
		}else{
			this.current_list = list_number || 0;
		}
		if(jsconfig.curr_page == 'orders'){
			if(document.getElementById('elements_on_page_panel.user.cabinet.list.get')){
				this.elements_on_page = document.getElementById('elements_on_page_panel.user.cabinet.list.get').value;
			}else if(document.getElementById('elements_on_page_panel.admin.order.list.get')){
				this.elements_on_page = document.getElementById('elements_on_page_panel.admin.order.list.get').value;
			}
		}else{
			if(document.getElementById('elements_on_page_'+this.fn_name))this.elements_on_page = document.getElementById('elements_on_page_'+this.fn_name).value;
		}
		data.current_list = this.current_list;
		data.elements_on_page = this.elements_on_page;
		data.func = this.fn_name;
		data.method = method;
		if(data.recommended)this.recommended = true;
		this.pages_head = document.getElementById('pages_head_'+this.fn_name);
		if(this.method == 'add'){
			this.preloader = document.getElementById('load_more_'+this.fn_name).getElementsByTagName('button')[0];
		}else{
			this.preloader = this.list;
		}
		main.post(this.handler, function(){

			if(this.aobj.recommended)user_recommended.block.show('text', this.exist);
			if(this.aobj.method == 'add'){
				if ( this.aobj.list_id ) {
					
					if ( this.aobj.list_id == 'review_list' ) {
						$( '.pa_middle_c_l_b_pagination' ).before( this.content );
					} else {
						var before = $( '#' + this.aobj.list_id + ' .tableAddMy' );

						if(before.length){
							before.before(this.content);
						} else {

							if($( '#' + this.aobj.list_id + ' #table_pagination' ).length){
								$( '#' + this.aobj.list_id + ' #table_pagination' ).before( this.content );
							}else{
								$( '#' + this.aobj.list_id + ' .pa_middle_c_l_b_pagination' ).before( this.content );
							}
							
						}
					}
				} else {
					if(document.getElementById('list_add_'+this.aobj.fn_name))document.getElementById('list_add_'+this.aobj.fn_name).innerHTML += this.content ;
					else this.aobj.list.innerHTML += this.content;
				}
			}else{
				this.aobj.list.innerHTML = this.content;
				if(this.aobj.method == 'gotoanchor')window.location.href = '#head_'+this.aobj.fn_name;
			}
			if(document.getElementById('load_more_'+this.aobj.fn_name) && document.getElementById('pages_'+this.aobj.fn_name).innerHTML-1 == this.aobj.current_list)
						document.getElementById('load_more_'+this.aobj.fn_name).style.display = 'none';
			
			if(callback)callback(this);
			if(this.num)document.getElementById('num_'+this.aobj.fn_name).innerHTML = this.num;
		}, data, true, this);

	}
}
var module = {
	main: {
		ready: function(){
			document.getElementById('currentListSel').style.visibility = "visible";
			user_recommended.show_recommended();
			module.main.productlist.get(0, false, true);
		},
		productlist: {
			pagination: 0,
			get: function(list_number, method, newly){
				if(newly)this.pagination = new Pagination('module.main.productlist', '/modules/shop/productList/func/productListShow.php', 'productList', 50);
				var data = {
					sort: document.getElementById('sortListSel_all_product').value,
					search: document.getElementById("search").value,
					cat: jsconfig.category ? jsconfig.category.id : 0
				};
				if(document.getElementById('search').value != '')method = 'gotoanchor';
				this.pagination.get(data, list_number, method, hideloader);
			}	
		}	
	},
	user: {
		signin: {
			popup: function(){
                var data = {
					
				};
				main.post('/modules/login/popup.php', function(){
					popupOpen(this.content, true);
				},data);
			},
			action: function(form){
				var error = document.getElementById("loginError");
				error.innerHTML = '';
				var res = ajaxxx('/modules/login/logining.php', '&login=' + form.login.value + '&password=' + form.pass.value + '&captcha=' + form.captcha.value + '&code=' + form.code.value);
				res = JSON.parse(res);
				if(res.status != 'ok'){
					if(res.message == 'maintenance')error.innerHTML = "Обслуживание";
					if(res.message == 'recover')error.innerHTML = "Ваш пароль сброшен, создайте новый через процедуру восстановления пароля. После этого настройте двухфакторную аутентификацию";
					if(res.message == 'data')error.innerHTML = "Неверное сочетание Логин\Пароль";
					if(res.message == 'conf')error.innerHTML = "Ваш E-mail не подтвержден";
					if(res.message == 'captcha')error.innerHTML = "Неверный код с картинки";
					if (res.message == 'twindata') {
						if ($('.RegAuto_F__twin-auth').css('display') === 'none') {
                            form.code.value = '';
                            $('.RegAuto_F__twin-auth').css('display', 'table-row');
                            error.innerHTML = "Введите код верификации";
						} else {
                            error.innerHTML = "Неверный пароль или код верификации";
						}
					}
                    if (res.noUpdateCaptcha !== true) {
						document.getElementById("loginCaptcha").src = "/modules/captcha/captcha.php?" + Math.random();
                    }
					return;
				}
				main.forwarding('Авторизация прошла успешно', '/panel/cabinet/');
			}
		},
		recover:{
			action: function(form){
				var error = document.getElementById("loginError");
				error.innerHTML = '';
				var res = ajaxxx('/modules/login/recover.php', '&email=' + form.email.value +'&captcha=' + form.captcha.value);
				res = JSON.parse(res);
				if(res.status != 'ok'){
					if(res.message == 'data')error.innerHTML = "Введите e-mail";
					if(res.message == 'conf')error.innerHTML = "e-mail не подвержден";
					if(res.message == 'noemail')error.innerHTML = "Неправильный e-mail";
					if(res.message == 'captcha')error.innerHTML = "Неверный код с картинки";
					document.getElementById("loginCaptcha").src = "/modules/captcha/captcha.php?" + Math.random();
					return;
				}
				main.forwarding('На ваш email отправлено письмо c ссылкой подтверждения.<br> Перейдя по ссылке вы можете изменить Ваш пароль.<br>', '/');
			}
		},
		reset:{
			action: function(form){
				var error = document.getElementById("loginError");
				error.innerHTML = '';
				var res = ajaxxx('/modules/login/reset.php', '&password=' + form.password.value + '&repassword='+ form.repassword.value + '&user_id='+ form.user_id.value + '&key='+ form.key.value +'&captcha=' + form.captcha.value);
				res = JSON.parse(res);
				if(res.status != 'ok'){
					if(res.message == 'data')error.innerHTML = "Введите пароль";
					if(res.message == 'matching')error.innerHTML = "Пароли не совпадают";
					if(res.message == 'captcha')error.innerHTML = "Неверный код с картинки";
					if(res.message == 'error')error.innerHTML = "Что то не так...";
					document.getElementById("loginCaptcha").src = "/modules/captcha/captcha.php?" + Math.random();
					return;
				}
				main.forwarding('Ваш пароль изменен.<br>', '/signin/');
			}
		},
		signup: {
			/*popup: function(){
				main.post('/modules/reg/reg.php', function(){
					popupOpen(this.content, true);
				});
			},*/
			action: function(form){
				var data = {
					login: form.login.value,
					pass: form.pass.value,
					passr: form.passr.value,
					fio: form.fio.value,
					email: form.email.value,
					captcha: form.captcha.value,
					agreement: form.agreement.checked
				};
				data = JSON.stringify(data);
                                console.log(data);
				main.post('/modules/reg/inDB.php', function(){
					if(this.status == 'ok')main.forwarding('На Ваш электронный адрес отправлено контрольное письмо с просьбой подтверждения.', '/');
					if(document.getElementById('regcaptcha'))document.getElementById('regcaptcha').src = '/modules/captcha/captcha.php?'+Math.random();
				}, data, false, {preloader: form.button, error: 'regError'});
			}
		}
	},
	seller: {
		ready: function(){
			document.getElementById('sortListSel').style.visibility = "visible";
			document.getElementById('currentListSel').style.visibility = "visible";
			document.getElementById('sortListSel').options[4].selected = 'true';
			module.seller.productlist.get(0, false, true);
		},
		productlist: {
			pagination: 0,
			get: function(list_number, method, newly){
				if(newly)this.pagination = new Pagination('module.seller.productlist', '/modules/shop/sellerShow/getListProduct.php', 'sellerShow_list', 10);
				var data = {
					sort: document.getElementById('sortListSel').value,
					user_id: jsconfig.seller.id,
					method: method
				};
				this.pagination.get(data, list_number, method);
			}		
		},
		reviewlist: {
			pagination: 0,
			get: function(list_number, method, newly){
				if(newly)this.pagination = new Pagination('module.seller.reviewlist', '/modules/shop/sellerShow/getListReview.php', 'sellerShow_list', 10);
				var data = {
					user_id: jsconfig.seller.id,
					method: method
				};
				this.pagination.get(data, list_number, method);
			}
		}
	},
	product: {
		ready: function(){
			if(document.getElementById('currentListSel')){
				document.getElementById('currentListSel').style.visibility = "visible";
			}
			
			module.product.review.list.get(0, false, true);
			get_share_button();
		},
		review: {
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('module.product.review.list', '/modules/shop/productShow/get_review_list_new.php', 'review_list', 10);
					var data = {
						product_id: jsconfig.product.id,
						method: method
					};
					this.pagination.get(data, list_number, method);
				}
			}
		},
		checkdiscount: function(){
			var email = document.getElementById('productShowDiscountEmail').value;
			if(isEmpty(email)){alert('Введите email'); return;}
			if(checkRegex(email, 'email')){alert('Неверный формат email'); return;}
			var productid = 0;
			if(jsconfig.module == 'showproduct')productid = jsconfig.product.id 
			else if(jsconfig.module == 'buy')productid =  jsconfig.buy.id;
			var res = ajaxxx("/modules/shop/productShow/func/checkDiscount.php", "&email="+email+"&productId="+productid);
			
			popupOpen("<div class='ws_checkdiscount'>Ваша скидка "+res+"%</div>", false, 'Скидка');
		}
	},
	category:{
		elms: new Array(0,0),
		show:function(id,deep){
			//document.getElementById('sortListSel').options[0].selected = 'true';
			if(id == 0){
				if(this.elms[1] != 0){
					//document.getElementById("categoryDiv"+this.elms[1]).style.display = "none";
					this.elms[1] = 0;
				}
				return;
			}
			if(this.elms[deep] == id){
				//document.getElementById("categoryDiv"+id).style.display = "none";
				this.elms[deep] = 0;
				//module.main.productlist.get(0, false, true);
				//user_recommended.show_recommended();
				return;
			}
			if(this.elms[deep] != 0){
				//document.getElementById("categoryDiv"+this.elms[deep]).style.display = "none";
				//module.main.productlist.get(0, false, true);
				//user_recommended.show_recommended();
			}
			this.elms[deep] = id;
			//document.getElementById("categoryDiv"+id).style.display = "block";
		}
	},
	customer: {
		ready: function(){
			if(document.getElementById('module_customer_productlist'))module.customer.productlist.get(0, false, true);
			if(document.getElementById('wait_payment_div') != null)setTimeout("location.reload()", 10000);
		},
		login: function(){
			var email_permanent = document.getElementById('customer_email').value;
			var captcha = document.getElementById('customer_captcha').value;
			var captcha_pic = document.getElementById('customer_captcha_pic');
			var error = document.getElementById('customer_login_error');
			
			error.innerHTML = '';
			
			var email = strBaseTo(email_permanent);
			captcha = strBaseTo(captcha);
			var res = ajaxxx('/modules/customer/getCode.php','&email='+email+'&captcha='+captcha);
			document.getElementById("customer_captcha_pic").src = "/modules/captcha/captcha.php?" + Math.random();
			res = JSON.parse(res);
			if(res.status == 'error'){
				if(res.message == 'email_incorrect_format')error.innerHTML = "Неверный формат email";
				if(res.message == 'email_not_found')error.innerHTML = "Мы не нашли ни одной покупки по данному E-mail Адресу";
				if(res.message == 'captcha')error.innerHTML = "Неверный код с картинки";
				
				return;
			}
			popupOpen('На ваш email ' + email_permanent + ' отправлено письмо c авторизационной ссылкой.<br> С помощью неё вы получите доступ к вашим покупкам. Проверяйте папку "СПАМ"!<br>');
			
		},
		productlist: {
			pagination: 0,
			get: function(list_number, method, newly){
				if(newly)this.pagination = new Pagination('module.customer.productlist', '/modules/customer/productlist.php', 'module_customer_productlist', 25);
				var data = {
					h: jsconfig.customer.h,
					i: jsconfig.customer.i
				}
				this.pagination.get(data, list_number, method);
			}			
		},
		review: {
			send: function(form){
				var error = document.getElementById('review_send_error');
				error.innerHTML = "";
				var res = ajaxxx('/modules/customer/review_create.php','&h='+jsconfig.customer.h+'&i='+jsconfig.customer.i+'&text='+encodeURIComponent(form.text.value)+'&evaluation='+form.evaluation[0].checked);
				res = JSON.parse(res);
				if(res.status == 'error'){
					switch (res.message) {
					  case 'parameters_empty':
						error.innerHTML = "Введите текст отзыва";
						break
					  case 'timeout_bad_review':
						error.innerHTML = "Плохой отзыв можно оставить только в течение 30-ти дней после покупки";
						break
					  default:
						error.innerHTML = "Ошибка при создании отзыва";
					}
					return false;
				}
				location.reload();
			},
			change_prepare: function(){
				var button = document.getElementById('review_change_button');
				var change_div = document.getElementById('review_change');
				if(change_div.style.display == 'none'){change_div.style.display = 'block';button.innerHTML = 'Отменить изменение отзыва';}
				else{change_div.style.display = 'none';button.innerHTML = 'Изменить отзыв';}
			},
			change: function(form){
				var error = document.getElementById('review_change_error');
				error.innerHTML = "";
				
				var res = ajaxxx('/modules/customer/review_change.php','&h='+jsconfig.customer.h+'&i='+jsconfig.customer.i+'&text='+encodeURIComponent(form.text.value)+'&evaluation='+form.evaluation[0].checked);
				//alert(res);
				res = JSON.parse(res);
				if(res.status == 'error'){
					switch (res.message) {
					  case 'parameters_empty':
						error.innerHTML = "Введите текст отзыва";
						break
					  case 'timeout_bad_review':
						error.innerHTML = "Плохой отзыв можно оставить только в течение 30-ти дней после покупки";
						break
					  default:
						error.innerHTML = "Ошибка при редактировании отзыва";
					}
					return false;
				}
				location.reload();	
			},
			del: {
				popup: function(button, review_id){
					var data = {
						review_id: review_id
					};
					data = JSON.stringify(data);
					main.post('/modules/customer/review_del_popup.php', function(){
						popupOpen(this.content, false, this.title);
					}, data, false, {preloader: button});
					
				},
				action: function(form, review_id){
					var data = {
						review_id: review_id,
						agreement: form.agreement.checked,
						h: jsconfig.customer.h,
						i: jsconfig.customer.i
					};
					data = JSON.stringify(data);
					main.post('/modules/customer/review_delete.php', function(){
						if(this.status == 'ok'){popupClose();location.reload();}
					}, data, false, {preloader: form.button, error: 'module_customer_review_del_action_error'});						
				}
			}
		},
		send_message: function(form){
			var error = document.getElementById('message_send_error');
			error.innerHTML = "";
			
			var res = ajaxxx('/modules/customer/send_message.php','&h='+jsconfig.customer.h+'&i='+jsconfig.customer.i+'&text='+encodeURIComponent(form.text.value));
			//alert(res);
			res = JSON.parse(res);
			if(res.status == 'error'){
				switch (res.message) {
				  case 'parameters_empty':
					error.innerHTML = "Введите текст сообщения";
					break
				  default:
					error.innerHTML = "Ошибка при создании сообщения";
				}
				return false;
			}
			location.reload();	
		},
		unsubscribe:{
			order:function(button){
				if(!confirm('Уверены, что хотите отписаться от email уведомлений по этому заказу?'))return;
				main.post('/ajax/customer/unsubscribe/', function(){
					if(this.status == 'ok'){
						location.reload();
					}
				}, {
					type:'order',
					orderId:jsconfig.customer.i,
					h:jsconfig.customer.h
				}, true, {preloader:button,popupError:true});
			},
			common:function(form){
				main.post('/ajax/customer/unsubscribe/', function(){},{
					type:'common',
					h:jsconfig.customer.h,
					buy:form.buy.checked,
					orderUpdate:form.orderUpdate.checked
				}, true, {preloader:form.button,popupError:true,error:'unsubscribeInfo'});
			}
		}
	},
	checkpromocode: {
		check: function(form){
			var data = {
				code: form.code.value,
				captcha: form.captcha.value
			};
			main.post('/modules/checkpromocode/check.php', function(){
				document.getElementById('module_checkpromocode_check_captcha').src = '/modules/captcha/captcha.php?'+Math.random();
				if(this.status == 'ok')document.getElementById('module_checkpromocode_response').innerHTML = this.content;
			}, data, false, {preloader: form.button, error: 'module_checkpromocode_check_error'});
		}
	},
	buy: {
		ready: function(){
			// @TODO HARDCODE
			this.change('44-44');
			this.promocode.discount = 0;
		},
		nowmethod: 44,
		nowmethod_pic: 44,
		nowmethod_code: 'wmr',
		nowsystem: 'wm',
		change: function(a){
			if(!document.getElementById('buy_method'+this.nowmethod_pic))return;
			a = a.split('-');
			this.nowmethod = a[0];
			
			if(!isEmpty(a[1]))this.nowmethod_pic = a[1];
			else this.nowmethod_pic = a[0];

			$('.buy_method_btn').removeClass('active');
			$('#buy_method'+this.nowmethod_pic).addClass('active');
			
			document.getElementById('buy_methodselect').value = this.nowmethod+'-'+this.nowmethod_pic;
			var res = ajaxxx('/modules/buy/getPayMethodInfo.php','&method_id='+this.nowmethod+'&discount='+module.buy.promocode.discount+'&product_id='+jsconfig.buy.id);
			//alert(res);
			res = JSON.parse(res);
			document.getElementById('buy_info').innerHTML = res.info;
			this.nowmethod_code = res.code;
			this.nowsystem = res.system;
			document.getElementById('buy_price').innerHTML = res.price;
			$( 'select:not([multiple])' ).trigger( 'refresh' );
		},
		send: function(){
			var error = document.getElementById('buy_error');
			var email = document.getElementById('buy_email');
			var curr = 4;
			switch(this.nowmethod){
				case '2':
					curr = 1;
					break
				case '3':
					curr = 3;
					break
				case '4':
					curr = 2;
					break
			}
			if(this.nowsystem == 'paypal' || this.nowsystem == 'skrill'){
				curr = 1;
			}

			var soglAgreeCheckbox = document.getElementById('soglAgree');
			var soglNotAgree = (soglAgreeCheckbox === null || soglAgreeCheckbox.checked) ? 0 : 1;

			error.innerHTML = "";
			var promocode_use = document.getElementById('module_buy_promocode_check_code') && document.getElementById('module_buy_promocode_check_code').disabled ?  1 : 0;
			var promocode_code = document.getElementById('module_buy_promocode_check_code') ? document.getElementById('module_buy_promocode_check_code').value : 0;
			var res = ajaxxx('/modules/buy/order_.php','&soglNotAgree='+soglNotAgree+'&id='+jsconfig.buy.id+'&email='+email.value+'&curr='+curr+'&promocode_use='+promocode_use+'&promocode_code='+promocode_code+'&via='+this.nowsystem+'&via_code='+this.nowmethod_code+'&via_id='+this.nowmethod);
            console.log(res);
            res = JSON.parse(res);

            if(res.status == 'error'){
				if(res.message == 'later'){error.innerHTML = "Попробуйте через 10 минут";return;}
				if(res.message == 'email'){error.innerHTML = "Неверный формат E-mail";return;}
				if(res.message == 'availability'){error.innerHTML = "Товара нет в наличии";return;}
				if(res.message == 'blacklist'){error.innerHTML = "Вы занесены в черный список у данного продавца";return;}
				if(res.message == 'percent'){error.innerHTML = "Неправильный процент партнера";return;}
				if(res.message == 'sogl'){error.innerHTML = "Вы не приняли соглашение";return;}
                error.innerHTML = res.message;
				return;
			}

			if(this.nowsystem == 'wm'){
				document.getElementById('wmerchant').LMI_PAYMENT_AMOUNT.value = res.totalBuyer;
				document.getElementById('wmerchant').LMI_PAYMENT_NO.value = res.id;
				document.getElementById('wmerchant').h.value = res.h;
                document.getElementById('wmerchant').LMI_PAYEE_PURSE.value = res.receiver;
				document.getElementById('wmerchant').submit();
			}else if(this.nowsystem == 'ikassa'){
				var button = $('.ws_v2_mail_formbuy .mybtnfooter');

				var form = document.getElementById('interkassa');
				form.ik_pm_no.value = res.id;
				form.ik_cli.value = email.value;
				form.ik_am.value = res.totalBuyer;
				form.ik_sign.value = res.ik_sign;
				if(this.nowmethod_code && this.nowmethod_code != 'other'){
					var ik_act = document.createElement('input');
					ik_act.type = 'hidden';
					ik_act.value = 'process';
					ik_act.name = 'ik_act';
					form.appendChild(ik_act);
					var ik_pw_via = document.createElement('input');
					ik_pw_via.type = 'hidden';
					ik_pw_via.value = this.nowmethod_code;
					ik_pw_via.name = 'ik_pw_via';
					form.appendChild(ik_pw_via);
				}
                form.submit();
			}else if(this.nowsystem == 'yandex'){
				
				var form = document.getElementById('yandexmoney');
				form.sum.value = res.totalBuyer;
				form.receiver.value = res.receiver;
				form.label.value = res.id;
				form.successURL.value = res.successURL;
				form.targets.value = form.targets.value+' [#'+ res.id+']';
				form.submit();
			}else if(this.nowsystem == 'yandex2'){
				$('<form action="'+res.yandex_cart_acs_uri+'"><input type="hidden" name="cps_context_id" value="'+res.yandex_cart_cps_context_id+'"><input value="'+res.yandex_cart_paymentType+'" type="hidden" name="paymentType"></form>').appendTo('body').submit();
				return false;
			}else if(this.nowsystem == 'paypal'){
				var form = document.getElementById('paypal');
				form.business.value = res.receiverPaypal;
				form.receiver_email.value = res.receiverPaypal;
				form.item_name.value = res.product_name;
				form.item_number.value = res.id;
				form.amount.value = res.totalBuyer;
				form.return.value = res.successURL;
				form.cancel_return.value = res.failURL;
				form.notify_url.value = res.paypalN;

				form.submit();
			}else if(this.nowsystem == 'skrill'){
				var button = $('.ws_v2_mail_formbuy .mybtnfooter');
				button.prop('disabled', true).css('cursor', 'progress');

				$.ajax({
					url: '/modules/buy/skrill/process.php',
					type: 'POST',
					data: {'transaction_id':res.id,'return_url':res.successURL,'amount':res.totalBuyer, 'detail1_text': res.product_name, 'amount_fee': res.payment_fee, 'via': this.nowmethod_code},
					success: function(data) {

					  if(data){
						var data = $.parseJSON(data);
						if(data['error']){
							error.innerHTML = data['error'];
						}else{
							window.location.href = data['url'];
						}   
					  }
					  button.prop('disabled', false).css('cursor', 'default');
					}
				});
				return false;
			}else if(this.nowsystem == 'qiwi'){
                $('#addProductStepOne').remove();
                $('.transition_to_payment').css('display', 'block');
                $('.qiwiLink')
					.attr('href', res.qiwiUri)
					.on('click', function() {
						main.sendMetric(1, res.id, function() {
                            setTimeout(function(){
                                window.location.href = res.successURL;
                            }, 100);
						});
				});
			}else if(this.nowsystem == 'primePayer') {
                var form = document.getElementById('primePayer');
                form.payment.value = res.id;
                form.amount.value = res.totalBuyer;
                form.via.value = this.nowmethod_code;
                form.success.value = res.successURL;
                form.email.value = res.email;
                form.sign.value = res.sign;
                form.submit();
            }else{
				var SignatureValue = ajaxxx('/modules/buy/robo/get_SignatureValue.php','&nOutSum='+res.totalBuyer+'&nInvId='+res.id+'&shph='+res.h);
				SignatureValue = JSON.parse(SignatureValue);
				SignatureValue = SignatureValue.value;
				document.getElementById('robokassa').OutSum.value = res.totalBuyer;
				document.getElementById('robokassa').InvId.value = res.id;
				document.getElementById('robokassa').Email.value = email.value;
				document.getElementById('robokassa').shph.value = res.h;
				document.getElementById('robokassa').SignatureValue.value = SignatureValue;
				document.getElementById('robokassa').IncCurrLabel.value = this.nowmethod_code;
				
				document.getElementById('robokassa').submit();
			}
		},
		promocode: {
			discount: 0,
			check: function(button, apply){
				var data = {
					code: document.getElementById('module_buy_promocode_check_code').value,
					product_id: jsconfig.buy.id,
					apply: apply,
                    viaCode: module.buy.nowmethod_code
				};
				data = JSON.stringify(data);
				main.post('/modules/buy/checkpromocode.php', function(){
					if(this.status == 'ok' && this.apply){
						module.buy.promocode.discount = this.discount;
						document.getElementById('buy_price').innerHTML = this.lowprice;
						document.getElementById('module_buy_promocode_check_code').disabled = true;
					}
				}, data, true, {preloader: button, error: 'module_buy_promocode_check_info'});
			}
		}
	},
	news:{
		ready:function(){
			this.list.get(0, false, true);
		},
		add:function(form){
			main.post('/ajax/news/add/', function(){
				if(this.status == 'ok'){
					module.news.list.get(0, false, true);
					form.reset();
				}
			},main.formdata(form),true,{preloader:form.button,error:'newsAddInfo'});
		},
		edit:function(form){
			main.post('/ajax/news/edit/', function(){
				if(this.status == 'ok'){
					main.forwarding('Новость успешно отредактирована', '/panel/news/');
				}
			},main.formdata(form),true,{preloader:form.button,error:'newsEditInfo'});
		},
		list: {
			pagination: 0,
			get: function(list_number, method, newly){
				if(newly)this.pagination = new Pagination('module.news.list', '/ajax/news/list', 'newslist', 10);
				var data = {
					page:jsconfig.module
				};
				this.pagination.get(data, list_number, method);
			}
		},
		getedit:function(button,newsId){
			main.post('/ajax/news/editpopup/', function(){
				if(this.status == 'ok'){
					popupOpen(this.content);
				}
			},{newsId:newsId},true,{preloader:button,error:'newsAddInfo',popupError:true});
		}
	}
}
var panel = {
	showprod:function(link,prodId){
		common.post('/ajax/product/showprod/',{preloader:link.parentNode,data:{prodId:prodId},popuperror:true,success:function(data){
			common.popup.open(data.content, false, data.prodName);
		}});
	},
	moderate:function(link,result, prodId){
		Msg = false;
		common.post('/ajax/product/moderate/',{preloader:link.parentNode,data:{prodId:prodId, result:result, msg:Msg},popuperror:true,success:function(data){
			if(data.result){
				alert('Одобрено!');
				window.history.back();
			}else{
				window.history.back();
			}
		
		}});
	},
	admin: {
		bookkeeping:{
			ready:function(){
				this.graph();
				this.wdlist.get(0, false, true);

				this.graph_priv();
				this.wdlist_priv.get(0, false, true);
				
			},
			retryRequest: function(id, button){
                var row = button.parentNode.parentNode;
				var data = {
                    id:id
                };
                main.post('/panel/admin/bookkeeping/retryRequest.php', function(){
                    if(this.status === 'ok') {
                        row.parentNode.removeChild(row);
                    } else {
                        popupOpen('<div class="form_error">'+this.message+'</div>');
                    }
                }, data, false, {preloader:button});
			},
			completeRequest:{
				dialog:function(id, button){
					var data = {
						id:id
					};
					main.post('/panel/admin/bookkeeping/completeRequestDialog.php', function(){
						if(this.status == 'ok')popupOpen(this.content);
						else popupOpen('<div class="form_error">'+this.message+'</div>');
					}, data, false, {preloader:button});
				},
				action:function(form){
					var data = {
						id:form.id.value,
						protect:form.protect.checked,
						code:form.code.value
					};
					main.post('/panel/admin/bookkeeping/completeRequestAction.php', function(){
						if(this.status == 'ok'){
							popupClose();
							panel.admin.bookkeeping.wdlist.get(0, false, true);
						}
					}, data, false, {preloader:form.button,error:'panel_admin_bookkeeping_completeRequest_error'});
				}
			},
			savesettings:function(form){
				var data = {
					fee:form.fee.value,
					time_retention:form.time_retention.value,
					graphic_ad_price:form.graphic_ad_price.value,
					text_ad_price:form.text_ad_price.value,
					wmx2:form.wmx2.checked,
					wmx2_fee:form.wmx2_fee.value,
					yandex_autopayments:form.yandex_autopayments.checked,
					yandex_autopayments_fee:form.yandex_autopayments_fee.value,
					rate_moder:form.rate_moder.value,
					paypal_fee_percent:form.paypal_fee_percent.value,
					paypal_fee_val:form.paypal_fee_val.value,
                    qiwi_fee_percent:form.qiwi_fee_percent.value,
                    qiwi_autopayments:form.qiwi_autopayments.checked,
                    qiwi_autopayments_fee:form.qiwi_autopayments_fee.value
				};
				main.post('/panel/admin/bookkeeping/savesettings.php', function(){
					if(this.status == 'ok')location.reload();
				}, data, true, {preloader: form.button, error: 'panel_admin_money_change_error'});
			},
			savePrivsettings:function(form){
				var data = {
					priv_amount_1: form.priv_amount_1.value,
					priv_amount_2: form.priv_amount_2.value,
					priv_amount_3: form.priv_amount_3.value,
					priv_amount_4: form.priv_amount_4.value,
					up_count_2: form.up_count_2.value,
					up_count_3: form.up_count_3.value,
					up_count_4: form.up_count_4.value,
					up_interval_2: form.up_interval_2.value,
					up_interval_3: form.up_interval_3.value,
					up_interval_4: form.up_interval_4.value,
					com_day_3: form.com_day_3.value,
					com_day_4: form.com_day_4.value,
					system_com_4: form.system_com_4.value
				};
				
				main.post('/panel/admin/bookkeeping/savePrivsettings.php', function(){
					if(this.status == 'ok')location.reload();
				}, data, true, {preloader: form.button, error: 'panel_admin_money_change_error2'});
			},
			graph:function(statistic){
				
				if($('#admin_money_graph_period_radio').length){
					var data = {
						period:document.getElementById('admin_money_graph_period_radio').checked ? 'day' : 'month'
					};
					var url;
					if(statistic){
						url = '/panel/admin/bookkeeping/get_graph_statistic.php';
					}else{
						url = '/panel/admin/bookkeeping/get_graph.php';
					}
					

					main.post(url, function(){
						if(this.status == 'ok'){
							var g = new Bluff.Line('example', '888x450');

							g.title = 'График поступлений';
							g.tooltips = true;
							g.theme_pastel();
							if(document.getElementById('admin_money_graph_sales_checkbox').checked)g.data("Продажи", this.values.sales);
							if(document.getElementById('admin_money_graph_sales_profit_checkbox').checked)g.data("Прибыль с продаж", this.values.sales_profit);
							if(document.getElementById('admin_money_graph_ad_checkbox') && document.getElementById('admin_money_graph_ad_checkbox').checked)g.data("Реклама", this.values.ad);
							if(document.getElementById('admin_money_graph_ad_text_checkbox') && document.getElementById('admin_money_graph_ad_text_checkbox').checked)g.data("Текстовая реклама", this.values.ad_text);
							if(document.getElementById('admin_money_graph_ad_graphic_checkbox') && document.getElementById('admin_money_graph_ad_graphic_checkbox').checked)g.data("Графическая реклама", this.values.ad_graphic);
							if(document.getElementById('admin_money_graph_profit_checkbox') && document.getElementById('admin_money_graph_profit_checkbox').checked)g.data("Общая прибыль", this.values.profit);
							g.labels = this.labels;
							g.draw();
						}else popupOpen('<div class="form_error">'+this.message+'</div>');
					}, data, false,{preloader:document.getElementById('example')});
				}
				
			},
			wdlist:{
				pagination: 0,
				get:function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.admin.bookkeeping.wdlist', '/panel/admin/bookkeeping/getRequestList.php', 'admin_money_request_list', 25);
					var data = {};
					this.pagination.get(data, list_number, method);
				}
			},
			graph_priv:function(period){
			
				if (typeof(period)=="undefined"){period = 0;}
				var self = this;
			
				main.post('/panel/glob/recommended/product_up_stat.php', function(){
					if(this.status == 'ok'){
						$('#salesGraph').highcharts({
							chart:{type:'column'},
							title:{text: 'Подписки'},
							exporting: {
								buttons: {
									contextButton: {
										enabled: false
									}
								}
							},
							xAxis: [{
								categories:['BRONZE', 'SILVER', 'GOLD', 'DIAMOND']
							}],
							yAxis: [{
								min:0,
								allowDecimals: false,
								title:{text:'Сумма (.руб)'},
								labels:{style:{color: Highcharts.getOptions().colors[3]}}
							},{
								min:0,
								allowDecimals: false,
								title:{text:''},
								opposite: true,
								labels:{style:{color: Highcharts.getOptions().colors[0]}}
							}],
							series:[{
								yAxis: 1,
								xAxis: 0,
								name: 'Общая сумма',
								data:this.amounts
							},{
								yAxis: 0,
								xAxis: 0,
								color:Highcharts.getOptions().colors[3],
								type: 'column',
								name: 'Количесво',
								data: this.counts,
								marker: {
									height:8,
									width:9,
									lineWidth: 2,
									lineColor: Highcharts.getOptions().colors[3],
									fillColor: 'white'
								}
							}]
						});
					
						$('.spoiler_3>.spoiler').hide();
						$('.highcharts-legend').hide();	
					}
				},{period:period},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
			
				
			},
			wdlist_priv:{
				pagination: 0,
				get:function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.admin.bookkeeping.wdlist_priv', '/panel/glob/recommended/priv_stat.php', 'priv_stat', 10);
					var data = {};
					this.pagination.get(data, list_number, method);
				}
			},
			userTransactions:function(userId, button){
				main.post('/panel/admin/bookkeeping/userTransactions.php', function(){
					if(this.status == 'ok')popupOpen(this.content);
					else popupOpen('<div class="form_error">'+this.message+'</div>');
				}, {userId:userId}, true, {preloader: button.parentNode});
			}
		},
		order: {
			ready: function(){
				this.list.get(0, false, true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					
					var url;
					if(jsconfig.curr_page == 'orders'){
						url = '/panel/glob/orderSearch/getOrderListMerchant.php';

						var data = {
							id: $('#id').val(),
							status: $('#status').val(),
							payed: $('#payed').val(),
							date1: $('#date1').val(),
							date2: $('#date2').val(),
							page: jsconfig.sp
						};

					}else{
						url = '/panel/glob/orderSearch/getOrderList.php';
						var data = {
							req: $('#glob_orderSearchText').val(),
							par: $('#glob_orderSearchSelect').val(),
							page: jsconfig.sp
						};
					}

					if(newly)this.pagination = new Pagination('panel.admin.order.list', url, 'panel_admin_order_list', 25);
					
					

					if(jsconfig.curr_page != 'orders' && data.par != 'all' && isEmpty(data.req)){alert('Введите запрос'); return;}

					this.pagination.get(data, list_number, method);	
				}
			}
		},
		group:{
			ready:function(){
				this.list(0);
			},
			active: {1:0,2:0},
			list:function(id){
				var data = {
					id:id
				};
				main.post('/panel/admin/group/getList.php', function(){
						if(this.status == 'ok'){
							if(this.deep-0 < 2){
								$( '[data-admin_cat_form="2"]' ).html( '' );
							}
							if(this.deep-0 < 3){
								$( '[data-admin_cat_form="3"]' ).html( '' );
							}
							if( $( '[data-admin_cat_form="' + this.deep + '"]' ) ){
								$( '[data-admin_cat_form="' + this.deep + '"]' ).html( this.content );
							}
							if(id != 0 && this.deep-0 < 4){
								var block_cat = $( '[data-admin_cat_btn="' + id + '"]' ).closest( '[data-admin_cat_form]' ),
									id_cat = block_cat.data( 'admin_cat_form' );

								panel.admin.group.active[id_cat] = id;

								$( '[data-admin_cat_form="1"] [data-admin_cat_btn]' ).removeClass( 'active focus' );
								$( '[data-admin_cat_form="1"] [data-admin_cat_btn="' + panel.admin.group.active[1] + '"]' ).addClass( 'active focus' );

								$( '[data-admin_cat_form="2"] [data-admin_cat_btn]' ).removeClass( 'active focus' );
								$( '[data-admin_cat_form="2"] [data-admin_cat_btn="' + panel.admin.group.active[2] + '"]' ).addClass( 'active focus' );

							}
						}
				}, data, false);
			},
			create:function(form,id){
				var data = {
					id:id,
					name:form.name.value
				};
				main.post('/panel/admin/group/create.php', function(){
					if(this.status == 'ok')panel.admin.group.list(id);
					else popupOpen('<div class="form_error">'+this.message+'</div>');
				}, data, false,{preloader:form.button});
			},
			del:function(id, icon){
				if(!confirm("Удалить группу?"))return;
				var data = {
					id:id
				};
				main.post('/panel/admin/group/delete.php', function(){
					if(this.status == 'ok')panel.admin.group.list(this.subgroup);
					else popupOpen('<div class="form_error">'+this.message+'</div>');
				}, data, false,{preloader:icon.parentNode});
			}
		}
	},
	moder:{
		messageview:{
			ready:function(){
				this.list.get(0, false, true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.messageview.list', '/panel/moder/messageview/getlist.php', 'messageview_list', 25);
					
					if($('#messageview_search_order_id').length){
						var data = {
							order_id: $('#messageview_search_order_id').val()
						};
					}else{
						var data = {
						};
					}
					
					
					this.pagination.get(data, list_number, method);	
				}
			}
		},
		reviewdelete:{
			search:function(form){
				var data = {
					id:form.id.value
				};
				main.post('/panel/moder/reviewdelete/search.php', function(){
					if(this.status == 'ok')document.getElementById('admin_reviewDeleteResult').innerHTML = this.content;
				}, data, false,{preloader:form.button,error:'reviewdelete_search_error'});
			},
			del:function(id, button){
				if(!confirm('Удалить отзыв '+id))return;
				var data = {
					id:id
				};
				main.post('/panel/moder/reviewdelete/del.php', function(){
					if(this.status == 'ok')main.forwarding('Отзыв успешно удален, рейтинг продавца восстановлен', '/panel/reviewdelete/');
				}, data, true,{preloader:button,error:'reviewdelete_del_error'});
			}
		},
		newproducts:{
			ready:function(){
				this.list.get(0,false,true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.newproducts.list', '/panel/moder/newproducts/list.php', 'admin_productProductListDiv', 25);
					
					var data = {
						req: '',
						par: 'all',
						page: jsconfig.sp,
					}
					this.pagination.get(data, list_number, method);	
				},
			},
			search:function(form){
				document.getElementById('admin_productSearchResult').innerHTML = '';
				main.post('/panel/moder/newproducts/search.php', function(){
					if(this.status == 'ok')document.getElementById('admin_productSearchResult').innerHTML = this.content;
				}, {
					id:form.id.value
				},false,{preloader:form.button,error:'newproducts_search_error'});
			},
			del:function(id, button){
				if(!confirm('Удалить товар '+id+'?'))return;
				main.post('/panel/user/productdel/action.php', function(){
					if(this.status == 'ok')main.forwarding('Товар успешно удалён', '/panel/newproducts/');
					else popupOpen('<div class="form_error">'+this.message+'</div>');
				}, {
					product_id:id
				},false,{preloader:button});
			}
		},
		moderate:{
			ready:function(){
				this.list.get(0,false,true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.moderate.list', '/panel/moder/newproducts/moderate-list.php', 'panel_admin_moderate_list', 25);
					
					var data = {
						req: '',
						par: 'all',
						page: jsconfig.sp,
					}
					this.pagination.get(data, list_number, method);	
				}
			},
		},
		newusers:{
			ready:function(){
				this.userlist.get(0, false, true);
				if(document.getElementById('admin_userModerListDiv'))this.moder.list();
			},
			'form':0,
			search:function(form){
				this.form = form;
				main.post('/panel/moder/newusers/search.php', function(){
					if(this.status == 'ok')document.getElementById('admin_userSearchResult').innerHTML = this.content;
					else popupOpen('<div class="form_error">'+this.message+'</div>');

					$('.chartPeriod.chart-shop div').each(function(i){$(this).on('click',function(){panel.moder.newusers.charts(i, true);});});
					$('.chartPeriod.chart-merch div').each(function(i){$(this).on('click',function(){panel.moder.newusers.chartsMerchant(i);});});
				}, {
					query:form.query.value,
					method:form.method.value
				}, false);
				this.charts();
			},
			charts:function(period, click){
				if(typeof(period)=="undefined"){period = 0;}
				if(typeof(click)=="undefined"){click = false;}
				$('.chartPeriod.chart-shop div').each(function(i){this.className = i == period ? 'btn btn-info btn-sm active' : 'btn btn-primary btn-sm'});
				var self = this;

				main.post('/ajax/sales/charts/user/', function(){
					if(this.status == 'ok'){
						if(this.sales.dt.length == 0)$('table.shop #salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('table.shop #salesGraph').highcharts({
								chart:{type:'column'},
								title:{text: 'Продажи'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								xAxis: [{
									categories:this.sales.dt
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series:[{
									yAxis: 1,
									xAxis: 0,
									name: 'Продажи',
									data:this.sales.sales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Прибыль',
									data: this.sales.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
						if(this.reviews.good == 0 && this.reviews.bad == 0)$('table.shop #reviewsGraph').html('<div class="noData"><p class="head">Отзывы</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('table.shop #reviewsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Отзывы'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Отзывов <b>{point.y} ({point.percentage:.1f}%)</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data: [{
										name: 'Положительные',
										y:this.reviews.good,
										color:'#8DF184'
									}, {
										name: 'Отрицательные',
										y:this.reviews.bad,
										color:'#F18484'
									}]
								}]
							});
						}
						if(this.products.length == 0)$('table.shop #productsGraph').html('<div class="noData"><p class="head">Товары</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('table.shop #productsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Товары'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Продаж: <b>{point.y}</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data:this.products
								}]
							});
						}

						if(!click){
							self.chartsMerchant();
						}
						
						$('table.shop .highcharts-title').html('Продажи на общую сумму '+self.commaSeparateNumber(this.allProfit)+' руб.');
					}
				},{period:period, 'user_login':this.form.query.value},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
			},
			chartsMerchant:function(period){
				if(typeof(period)=="undefined"){period = 0;}
				$('table.merchant .chartPeriod div').each(function(i){this.className = i == period ? 'btn btn-info btn-sm active' : 'btn btn-primary btn-sm'});
				var self = this;

				
				main.post('/ajax/sales/chartsMerchant/user/', function(){
					if(this.status == 'ok'){
						if(this.sales.dt.length == 0)$('table.merchant #salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('table.merchant #salesGraph').highcharts({
								chart:{type:'column'},
								title:{text: 'Продажи'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								xAxis: [{
									categories:this.sales.dt
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series:[{
									yAxis: 1,
									xAxis: 0,
									name: 'Продажи',
									data:this.sales.sales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Прибыль',
									data: this.sales.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
						
					
						$('table.merchant .highcharts-title').html('Продажи на общую сумму '+self.commaSeparateNumber(this.allProfit)+' руб.');
					}
				},{period:period, 'user_login':this.form.query.value},true,{preloader:document.getElementById('salesCharts2'),cancel:'chartPeriod'});
			},
			commaSeparateNumber:function(val){
				while (/(\d+)(\d{3})/.test(val.toString())){
					val = val.toString().replace(/(\d+)(\d{3})/, '$1'+'.'+'$2');
				}
				return val;
			},
			info:function(id, button){
					main.post('/panel/glob/useredit/edit.php', function(){
						if(this.status == 'ok')document.getElementById("admin_userUserInfo").innerHTML = this.content;
						$('#admin_userUserInfo_settings').html('');

					}, {
						user_id: id
					}, false, {preloader:button});
			},
			blocking:function(user_id, login, method, button){
				if(!confirm((method == 'block' ? 'Заблокировать пользователя ' : 'Разблокировать пользователя ')+login+'?'))return;
				main.post('/panel/moder/newusers/blocking.php', function(){
					if(this.status == 'ok')main.forwarding(method == 'block' ? 'Пользователь заблокирован' : 'Пользователь разблокирован', '/panel/newusers/');
					else popupOpen('<div class="form_error">'+this.message+'</div>');
				},{
					user_id:user_id,
					method:method
				}, false, {preloader:button});
			},
			userlist:{
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.newusers.userlist', '/panel/moder/newusers/userlist.php', 'newusers_userlist', 25);
					var data = {};
					this.pagination.get(data, list_number, method);	
				}
			},
			moder:{
				list:function(){
					main.post('/panel/moder/newusers/moderlist.php', function(){
						if(this.status == 'ok')document.getElementById('admin_userModerListDiv').innerHTML = this.content;
						else popupOpen('<div class="form_error">'+this.message+'</div>');
					}, {}, false);
				},
				adddel:function(user_id, login, method, button){
					if(!confirm((method == 'add' ? 'Добавить модератора ' : 'Снять модератора ')+login+'?'))return;
					main.post('/panel/moder/newusers/adddelmoder.php', function(){
						if(this.status == 'ok')panel.moder.newusers.moder.list();
						else popupOpen('<div class="form_error">'+this.message+'</div>');
					}, {
						user_id:user_id,
						method:method
					}, false, {preloader:button});
				},
				rights:function(user_id){
					
					main.post('/panel/moder/newusers/getRightsList.php', function(){

						popupOpen('<div id="rightsContainer"></div>', '', 'Права');
						$('#rightsContainer').append(this.content);
						$('#popup>.popup').css('top', '0%');

					}, {user_id: user_id})
				},
				rightsSet:function(user_id, form){
					
					main.post('/panel/moder/newusers/setRights.php', function(){

						popupClose();

					}, {user_id: user_id, formdata: $(form).serialize()}, false, {preloader:$(form).find('.btn-success')})

				},
				settings:function(user_id, button){
					main.post('/panel/glob/useredit/edit.php', function(){
					   if(this.status == 'ok'){
					       document.getElementById("admin_userUserInfo_settings").innerHTML = this.content;
					       $('#admin_userUserInfo').html('');
					   }
					}, {
					   user_id: user_id,
					   'settings': true
					}, false, {preloader:button});
				},
				save_settings:function(user_id, button){
					var percent = $('#user_percent').val();
					var reserv_time = $('#user_reservation').val();

					data = {
					   percent: percent,
					   reserv_time: reserv_time,
					   user_id: user_id
					},
					main.post('/panel/glob/useredit/save_settings.php', function(){
					   if(this.status == 'ok'){
					       $('#user_percent').val(percent);
					       $('#user_reservation').val(reserv_time);
					   }
					}, data , false, {preloader:button, error: 'panel_cabinet_useredit_info'});
				}
			}
		},
		hiddenproducts:{
			ready:function(){
				if(document.getElementById('hiddenproducts_list'))this.productslist.get(0, false, true);
				if(document.getElementById('hiddenproducts_list_one'))this.productslistOne.get(0, false, true);
			},
			productslist:{
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.hiddenproducts.productslist', '/panel/moder/hiddenproducts/productslist.php', 'hiddenproducts_list', 10);
					var data = {login: $('#loginSearch').val()};
					this.pagination.get(data, list_number, method);
				}
			},
			productslistOne:{
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.moder.hiddenproducts.productslistOne', '/panel/moder/hiddenproducts/productslistOne.php', 'hiddenproducts_list_one', 10);
					var data = {user_id: $('#hiddenproducts_list_one').data('id')};
					this.pagination.get(data, list_number, method);
				}
			},
			edit_status: function(user_id, button, type){
				var unchecked = [];
				var checked   = [];

				if(type != 'choosen' && $('#hiddenproducts_form').find("input:checkbox").length == 0){
					return false;
				}

				$('#hiddenproducts_form').find("input:checkbox:not(:checked)").each(function(){ unchecked.push($(this).val()) });
				$('#hiddenproducts_form').find("input:checkbox:checked:enabled").each(function(){ checked.push($(this).val()) });

				data = {
				   checked   : checked,
				   unchecked : unchecked,
				   type      : type,
				   userId    : user_id
				},
				main.post('/ajax/hiddenproducts/editstatus/user/', function(){
					if(this.status == 'ok' && type != 'choosen'){
						window.location.reload();
					}
				}, data , false, {preloader:button, error: 'info_button'});
			},
		},
	},
	user: {
		wishes: {
			send: function(form){
				var data = {
					text: form.text.value
				};
				main.post('/ajax/user/wish/user', function(){
					if(this.status == 'ok'){
						$('#wishForm').css('display', 'none');
						$('#wishSended').css('display', 'table-cell');
						form.reset();
					}
				}, data, false, {preloader: form.button, error: 'panel_wishsend_info'});
			},
			oneMore: function() {
				$('#wishForm').css('display', 'table-cell');
				$('#wishSended').css('display', 'none');
			},
		},
		apipanel:{
			generateKey:function(form, again){
				if(again && !confirm("После генерации нового ключа вы не сможете пользоваться старым. Продолжить?"))return;
				main.post('/ajax/apipanel/generateKey/user/', function(){
					if(this.status == 'ok'){
						main.forwarding('Ключ сгенерирован', '/panel/api/');
					}
				},{}, true, {preloader:form.button,popupError:true});
				
			}
		},
		partner:{
			ready:function(){
				if(!jsconfig.partner)return;
				if(jsconfig.partner.a == 'find')panel.user.partner.find.list.get(0, false, true);
				else if(jsconfig.partner.a == 'partnerships')panel.user.partner.partnerships.ready();
				else if(jsconfig.partner.a == 'mysellers')panel.user.partner.showMessages.get(0, false, true);
				else if(jsconfig.partner.a == 'productGroups')panel.user.partner.products.group.list.get(0, false, true);
				else if(jsconfig.partner.a == 'addproducts')panel.user.partner.products.group.addproducts.list.get(0, false, true);
				else if(jsconfig.partner.a == 'products')panel.user.partner.products.list.get(0, false, true);
				else if(jsconfig.partner.a == 'become')panel.user.partner.become.offers.list.get(0, false, true);
				else if(jsconfig.partner.a == 'partnerStat')panel.user.partner.partnerStat.ready();
				else if(jsconfig.partner.a == 'sellerStat')panel.user.partner.sellerStat.ready();
				else if(jsconfig.partner.a == 'setfee')panel.user.partner.setfee.list.get(0, false, true);
			},
			setfee:{
				action:function(form){
					main.post('/ajax/partner/setfee/user/', function(){
						if(this.status == 'ok'){
							main.forwarding('Настройки сохранены', '/panel/partner/setfee/');
						}
					},main.formdata(form), true, {preloader:form.button,popupError:true});
				},
				all:function(form){
					main.post('/ajax/partner/setfeeAll/user/', function(){
						if(this.status == 'ok'){
							main.forwarding('Настройки сохранены', '/panel/partner/setfee/');
						}
					}, {
						fee:form.fee.value
					}, true, {preloader:form.button,popupError:true});
				},
				list:{
					pagination:0,
					get:function(list_number, method, newly){
						if(newly)this.pagination = new Pagination('panel.user.partner.setfee.list', '/ajax/partner/setfeeList/user/', 'partnerSetfeeList', 25);
						var data = {};
						this.pagination.get(data, list_number, method);
					}
				},
			},
			partnerships:{
				ready:function(){
					panel.user.partner.showMessages.get(0, false, true);
					this.drawGraph('days');
				},
				drawGraph:function(period){
					var sData = {periods:[],clicks:[],sales:[],profit:[]};
					main.post('/ajax/partner/getPartnershipsGraph/user/', function(){
						if(this.status == 'ok'){
							var noPush = 1;
							if(period == 'days'){
								for(var i=1;i<32;i++){
									var date  = new Date((new Date().getTime() - 2678400000) + i * 86400000);
									var month = date.getMonth() < 9 ? '0'+(date.getMonth()+1) : date.getMonth()+1;
									var sPeriod = date.getDate()+'.'+month;
									var valS = 0;
									for(var v in this.sales){
										if(v == sPeriod){
											valS = this.sales[v]-0;
											break;
										}
									}
									if(noPush == 1 && valS == 0)continue;
									noPush = 0;
									sData.periods.push(sPeriod);
									sData.sales.push(valS);
								}
							}else{
                                for(var i=11;i>=0;i--){
                                    var date  = new Date();
                                    date = addMonthsUTC(date, -i);
                                    var month = date.getMonth() + 1;
                                    month = month < 10 ? '0'+month : month;
									var sPeriod = month+'.'+date.getFullYear();
									var valS = 0;
									for(var v in this.sales){
										if(v == sPeriod){
											valS = this.sales[v]-0;
											break;
										}
									}
									if(noPush == 1&& valS == 0)continue;
									noPush = 0;
									sData.periods.push(sPeriod);
									sData.sales.push(valS);
								}
								console.log(sData);
							}
							if(sData.periods.length == 0){
								document.getElementById('graph').innerHTML = '<table class="table table-striped table_page"><thead><tr><td colspan="2">Статистика продаж</td></tr></thead><tbody><tr><td colspan="2" class="padding_10">Для построения графика пока нет данных.</td></tr></tbody></table>';
								return;
							}
							$('#graph').highcharts({
								title: {
									text: 'Статистика продаж'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										},
										customButton1: {
											text: period == 'months' ? '<b>По месяцам</b>' : 'По месяцам',
											onclick: function () {
												panel.user.partner.partnerships.drawGraph('months');
											}
										},
										customButton2: {
											text: period == 'days' ? '<b>По дням</b>' : 'По дням',
											onclick: function(){
												panel.user.partner.partnerships.drawGraph('days');
											}
										}
									}
								},
								xAxis: {
									categories:sData.periods
								},
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								}],
								series: [{
									yAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Продаж',
									data: sData.sales,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
					},{
						period:period,
						partnershipId:jsconfig.partner.partnershipId
					}, true, {preloader:document.getElementById('graph'),error:'graph'});
				}
			},
			sellerStat:{
				ready:function(){
					if(document.getElementById('partnerNotificationsList'))this.notifications.list.get(0, false, true);
					if(document.getElementById('graph'))this.drawGraph('days');
				},
				notifications:{
					list:{
						pagination:0,
						get:function(list_number, method, newly){
							if(newly)this.pagination = new Pagination('panel.user.partner.sellerStat.notifications.list', '/ajax/partner/sellerNotifiList/user/', 'partnerNotificationsList', 25);
							var data = {};
							this.pagination.get(data, list_number, method);
						},
					}
				},
				drawGraph:function(period){
					var sData = {periods:[],partners:[],sales:[],partnerSales:[]};
					main.post('/ajax/partner/sellerStatGraph/user/', function(){
						if(this.status == 'ok'){
							var noPush = 1;
							if(period == 'days'){
								for(var i=1;i<32;i++){
									var date  = new Date((new Date().getTime() - 2678400000) + i * 86400000);
									var month = date.getMonth() < 9 ? '0'+(date.getMonth()+1) : date.getMonth()+1;
									var sPeriod = date.getDate()+'.'+month;
									var valS = 0;
									for(var v in this.sales){
										if(v == sPeriod){
											valS = this.sales[v]-0;
											break;
										}
									}
									if(noPush == 1 && valS == 0)continue;
									noPush = 0;
									sData.periods.push(sPeriod);
									sData.sales.push(valS);
								}
							}else{
                                for(var i=11;i>=0;i--){
                                    var date  = new Date();
                                    date = addMonthsUTC(date, -i);
                                    var month = date.getMonth() + 1;
                                    month = month < 10 ? '0'+month : month;
                                    var sPeriod = month+'.'+date.getFullYear();
                                    var valS = 0;
                                    for(var v in this.sales){
                                        if(v == sPeriod){
                                            valS = this.sales[v]-0;
                                            break;
                                        }
                                    }
                                    if(noPush == 1&& valS == 0)continue;
                                    noPush = 0;
                                    sData.periods.push(sPeriod);
                                    sData.sales.push(valS);
                                }
                                console.log(sData);
							}
							if(sData.periods.length == 0){
								document.getElementById('graph').innerHTML = '<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>';
								return;
							}
							$('#graph').highcharts({
								chart:{type:'column'},
								title:{text: 'Статистика продаж'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										},
										customButton1: {
											text: period == 'months' ? '<b>По месяцам</b>' : 'По месяцам',
											onclick: function () {
												panel.user.partner.sellerStat.drawGraph('months');
											}
										},
										customButton2: {
											text: period == 'days' ? '<b>По дням</b>' : 'По дням',
											onclick: function(){
												panel.user.partner.sellerStat.drawGraph('days');
											}
										}
									}
								},
								xAxis: [{
									categories:sData.periods
								},{
									categories:this.partners
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series: [{
									yAxis: 1,
									xAxis: 1,
									name: 'Продаж',
									data:this.partnerSales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Продаж',
									data: sData.sales,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
					},{
						period:period,
						partnershipId:jsconfig.partner.partnershipId
					}, true, {preloader:document.getElementById('graph').parentNode,error:'graph'});
				}
			},
			partnerStat:{
				ready:function(){
					if(document.getElementById('salesList'))this.notifications.list.get(0, false, true);
					if(document.getElementById('graph'))this.drawGraph('days');
				},
				tab:function(){
					document.getElementById('notifications').style.display = 'none';
					document.getElementById('sales').style.display = 'none';
					if(main.tab.getnum('statTabs') == 0){
						document.getElementById('notifications').style.display = 'table';
						panel.user.partner.partnerStat.notifications.list.get(0, false, true);
					}else{
						document.getElementById('sales').style.display = 'table';
						panel.user.partner.partnerStat.sales.list.get(0, false, true);
					}
				},
				notifications:{
					list:{
						pagination:0,
						get:function(list_number, method, newly){
							if(newly)this.pagination = new Pagination('panel.user.partner.partnerStat.notifications.list', '/ajax/partner/partnerNotifiList/user/', 'partnerNotificationsList', 25);
							var data = {};
							this.pagination.get(data, list_number, method);
						},
					}
				},
				sales:{
					list:{
						pagination:0,
						get:function(list_number, method, newly){
							if(newly)this.pagination = new Pagination('panel.user.partner.partnerStat.sales.list', '/ajax/partner/partnerSalesList/user/', 'salesList', 25);
							var data = {};
							this.pagination.get(data, list_number, method);
						},
					}
				},
				drawGraph:function(period){
					var sData = {periods:[],clicks:[],sales:[],profit:[]};
					main.post('/ajax/partner/getPartnerStatData/user/', function(){
						if(this.status == 'ok'){
							var noPush = 1;
							if(period == 'days'){
								for(var i=1;i<32;i++){
									var date  = new Date((new Date().getTime() - 2678400000) + i * 86400000);
									var month = date.getMonth() < 9 ? '0'+(date.getMonth()+1) : date.getMonth()+1;
									var sPeriod = date.getDate()+'.'+month;
									var valC = 0;
									for(var v in this.clicks){
										if(v == sPeriod){
											valC = this.clicks[v]-0;
											break;
										}
									}
									var valS = 0;
									for(var v in this.sales){
										if(v == sPeriod){
											valS = this.sales[v]-0;
											break;
										}
									}
									var valP = 0;
									for(var v in this.profit){
										if(v == sPeriod){
											valP = this.profit[v]-0;
											break;
										}
									}
									if(noPush == 1 && valC == 0 && valS == 0 && valP == 0)continue;
									noPush = 0;
									sData.periods.push(sPeriod);
									sData.clicks.push(valC);
									sData.sales.push(valS);
									sData.profit.push(valP);
								}
							}else{
                                for(var i=11;i>=0;i--){
                                    var date  = new Date();
                                    date = addMonthsUTC(date, -i);
                                    var month = date.getMonth() + 1;
                                    month = month < 10 ? '0'+month : month;
                                    var sPeriod = month+'.'+date.getFullYear();
									var valC = 0;
									for(var v in this.clicks){
										if(v == sPeriod){
											valC = this.clicks[v]-0;
											break;
										}
									}
									var valS = 0;
									for(var v in this.sales){
										if(v == sPeriod){
											valS = this.sales[v]-0;
											break;
										}
									}
									var valP = 0;
									for(var v in this.profit){
										if(v == sPeriod){
											valP = this.profit[v]-0;
											break;
										}
									}
									if(noPush == 1 && valC == 0 && valS == 0 && valP == 0)continue;
									noPush = 0;
									sData.periods.push(sPeriod);
									sData.clicks.push(valC);
									sData.sales.push(valS);
									sData.profit.push(valP);
								}
							}
                            console.log(sData);
							if(sData.periods.length == 0){
								document.getElementById('graph').innerHTML = '<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>';
								return;
							}
							$('#graph').highcharts({
								title: {
									text: 'Статистика продаж'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										},
										customButton1: {
											text: period == 'months' ? '<b>По месяцам</b>' : 'По месяцам',
											onclick: function () {
												panel.user.partner.partnerStat.drawGraph('months');
											}
										},
										customButton2: {
											text: period == 'days' ? '<b>По дням</b>' : 'По дням',
											onclick: function(){
												panel.user.partner.partnerStat.drawGraph('days');
											}
										}
									}
								},
								xAxis: {
									categories:sData.periods
								},
								yAxis: [{
										allowDecimals: false,
										title:{text:''},
										opposite: true,
										labels:{style:{color: Highcharts.getOptions().colors[0]}}
									},{
										min:0,
										title:{text:''},
										labels:{
											format: '{value} руб.',
											style:{color: Highcharts.getOptions().colors[3]}
										}
								}],
								series: [{
									yAxis: 0,
									type: 'column',
									name: 'Переходов',
									data: sData.clicks
								}, {
									yAxis: 0,
									type: 'column',
									name: 'Продаж',
									data: sData.sales
								},  {
									yAxis: 1,
									color:Highcharts.getOptions().colors[3],
									tooltip:{valueSuffix:' руб.'},
									type: 'spline',
									name: 'Прибыль',
									data: sData.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
					},{
						period:period
					}, true, {preloader:document.getElementById('graph').parentNode,error:'graph'});
				}
			},
			reject:function(partner,partnerShipId,button){
				if(!confirm('Уверены, что хотите разорвать партнерство?'))return;
				main.post('/ajax/partner/rejectPartner/user/', function(){
					if(this.status == 'ok'){
						if(partner)main.forwarding('Партнерство разорвано', '/panel/partner/mysellers/');
						else main.forwarding('Партнерство разорвано', '/panel/partner/partnerships/');
					}
				}, {
					partner:partner,
					partnerShipId:partnerShipId
				}, true, {preloader:button.parentNode,popupError:true});
			},
			showMessages:{
				pagination:0,
				get:function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.user.partner.showMessages', '/ajax/partner/showMessages/user/', 'messagesBlock', 25);
					var data = {partnershipId:jsconfig.partner.partnershipId};
					this.pagination.get(data, list_number, method);
				}
			},
			become:{
				offers:{
					list:{
						pagination:0,
						get:function(list_number, method, newly){
							if(newly)this.pagination = new Pagination('panel.user.partner.become.offers.list', '/ajax/partner/offersList/user/', 'offersList', 25);
							var data = {};
							this.pagination.get(data, list_number, method);
						},
					},
					acceptReject:function(accept,partnerShipId,button){
						if(!accept && !confirm('Уверены, что хотите отказаться от предложения?'))return;
						main.post('/ajax/partner/acceptRejectOffer/user/', function(){
							if(this.status == 'ok'){
								if(accept)main.forwarding('Предложение принято', '/panel/partner/mysellers/'+partnerShipId);
								else panel.user.partner.become.offers.list.get(0, false, true);
							}
						}, {
							accept:accept,
							partnerShipId:partnerShipId
						}, true, {preloader:button.parentNode,popupError:true});
					}
				},
				profile:function(form){
					main.post('/ajax/partner/becomePartner/user/', function(){}, {
						become:form.become.checked,
						description: form.description.value
					}, false, {preloader: form.button, error: 'becomePartnerError'});
				}
			},
			mypartner:{
				editPercent:function(form){
					main.post('/ajax/partner/editPersonalPercent/user/', function(){
						
					}, {
						partnershipId:form.partnershipId.value,
						percent:form.percent.value
					}, true, {preloader:form.button,error:'personalPercentError'});
				}
			},
			sendMessage:function(form){
				main.post('/ajax/partner/sendMessage/user/', function(){
					if(this.status == 'ok'){
						panel.user.partner.showMessages.get(0, false, true);
						form.reset();
					}
				}, {
					partnershipId:form.partnershipId.value,
					text:form.text.value
				}, true, {preloader:form.button,error:'sendMessageError'});
			},
			products:{
				link:function(url){
					popupOpen(url, false, 'Прямая ссылка');
				},
				showMl:function(){
					var el1 = document.getElementById('htmlCode');
					var but1 = document.getElementById('htmlButt');
					
					el1.style.display = el1.style.display == 'block' ? 'none' : 'block';
					//but1.disabled = but1.disabled == 'disabled' ? '' : 'disabled';
				},
				list:{
					pagination:0,
					get:function(list_number, method, newly){
						if(newly)this.pagination = new Pagination('panel.user.partner.products.list', '/ajax/partner/groupProductsList/user/', 'productsList', 25);
						var data = {groupId:jsconfig.partner.groupId};
						this.pagination.get(data, list_number, method);
					},
				},
				del:function(id,button){
					main.post('/ajax/partner/delProduct/user/', function(){
						if(this.status == 'ok'){
							if(this.noproducts)panel.user.partner.products.list.get(0, false, true);
							else{
								var block = this.aobj.preloader.parentNode;
								block.parentNode.removeChild(block);
							}
						}
					}, {
						partnerproductsId:id,
						groupId:jsconfig.partner.groupId
					}, true, {preloader:button.parentNode,popupError:true});
				},
				group:{
					add:function(form){
						main.post('/ajax/partner/addGroup/user/', function(){
							if(this.status == 'ok'){
								form.reset();
								panel.user.partner.products.group.list.get(0, false, true);
							}
						}, {
							name:form.name.value
						}, true, {preloader:form.button,popupError:true});
					},
					list:{
						pagination:0,
						get:function(list_number, method, newly){
							if(newly)this.pagination = new Pagination('panel.user.partner.products.group.list', '/ajax/partner/groupList/user/', 'groupList', 25);
							var data = {};
							this.pagination.get(data, list_number, method);
						},
					},
					del:function(id,button){
						if(!confirm('Уверены, что хотите удалить группу?'))return;
						main.post('/ajax/partner/delGroup/user/', function(){
							if(this.status == 'ok'){
								panel.user.partner.products.group.list.get(0, false, true);
							}
						}, {
							id:id
						}, true, {preloader:button,popupError:true});
					},
					addproducts:{
						list:{
							pagination:0,
							get:function(list_number, method, newly){
								if(newly)this.pagination = new Pagination('panel.user.partner.products.group.addproducts.list', '/ajax/partner/addproductsList/user/', 'addproductList', 25);
								var data = {
									seller:document.getElementById('productSeller').options[document.getElementById('productSeller').selectedIndex].value,
									productGroup:document.getElementById('groupSelect_3') ? document.getElementById('groupSelect_3').options[document.getElementById('groupSelect_3').selectedIndex].value : 'no',
									groupId:jsconfig.partner.groupId,
									sort:document.getElementById('addProductsListSort').selectedIndex
								};
								this.pagination.get(data, list_number, method, function(data){
									if(data.noItem){
										$( '#addproductPanel' ).fadeOut();
									} else {
										$( '#addproductPanel' ).fadeIn();
									}
								});
							},
						},
						add:function(form){
							var products = [];
							var els = document.getElementsByName('products[]');
							for(var el in els)if(els[el].checked)products.push(els[el].value);
							main.post('/ajax/partner/addProducts/user/', function(){
								if(this.status == 'ok'){
									main.forwarding('Товары добавлены', '/panel/partner/products/'+jsconfig.partner.groupId);
								}
							}, {
								groupId:jsconfig.partner.groupId,
								products:products
							}, true, {preloader:form.button,popupError:true});
						},
						chooseGroup:{
							status:false,
							opened:false,
							action:function(groupId, deep){
								if(deep == 3){
									this.opened = true;
									panel.user.partner.products.group.addproducts.list.get(0, false, true);
									return;
								}
								var box = document.getElementById('addproductGroups');
								if(this.status)return;
								var elms = box.getElementsByTagName('div');
								for(var i=elms.length-1;i>=0;i--)
									if(i >= deep)box.removeChild(elms[i]);
								if(groupId === false){
									document.getElementById('addproductGroupsRow').style.display = 'none';
									if(this.opened == true){
										this.opened = false;
										panel.user.partner.products.group.addproducts.list.get(0, false, true);
									}
									return;
								}
								document.getElementById('addproductGroupsRow').style.display = 'block';
								if(groupId == 'no')return;
								this.status = true;
								var div = document.createElement('div');
								box.appendChild(div);
								main.post('/ajax/partner/getGroupSelect/user/', function(){
									if(this.status == 'ok'){
										div.innerHTML = this.content;
										if(panel.user.partner.products.group.addproducts.chooseGroup.opened == true){
											panel.user.partner.products.group.addproducts.chooseGroup.opened = false;
											panel.user.partner.products.group.addproducts.list.get(0, false, true);
										}
									}
									panel.user.partner.products.group.addproducts.chooseGroup.status = false;
								}, {
									groupId:groupId
								}, true, {preloader:div,popupError:true});
							}
						}
					}
				}
			},
			find:{
				list:{
					pagination:0,
					get:function(list_number, method, newly){
						if(newly)this.pagination = new Pagination('panel.user.partner.find.list', '/ajax/partner/partnersList/user/', 'partnersList', 25);
						var data = {};
						this.pagination.get(data, list_number, method);
					}
				},
				info:function(partnerId, link, butt){
					var button = (butt || false) ? link : link.parentNode;
					main.post('/ajax/partner/partnerInfo/user/', function(){
						if(this.status == 'ok'){
							popupOpen(this.content, false, 'Информация о партнере');
						}
					}, {
						partnerId:partnerId
					}, false, {preloader:button,popupError:true});
				},
				offerPartnership:function(form){
					main.post('/ajax/partner/offerPartnership/user/', function(){
						if(this.status == 'ok'){
							popupOpen('Предложение сделано. Партнер сможет продавать ваши товары после того, как вы установите комиссию на товары<br><a href="/panel/partner/setfee/">Установка комиссий</a>', false, 'Предложение сделано');
						}
					}, {
						partnerId:form.partnerId.value,
						fee:form.fee.value
					}, true, {preloader:form.button,popupError:true});
				},
				byId:function(form){
					this.info(form.partnerUserId.value, form.button, 1);
				}
			},
			sellerSettings:{
				save:function(form){
					var notifi = 0;
					for(var i=0;i<form.notifi.length;i++)
						if(form.notifi[i].checked)notifi = i;
					main.post('/ajax/partner/sellerSettingsSave/user/', function(){}, {
						notifi:notifi,
						fee:form.fee.value
					}, true, {preloader:form.button,error:'saveSettError'});
				}
			}
		},
		discount: {
			ready: function(){
				main.post('/panel/user/discount/get_global_discount.php', function(){
					if(!this.count)return;
					for(var i=0;i<this.count;i++){
						$( '#user_discountDivMain' ).append( panel.user.discount.glob.field.block(this.money[i], this.percent[i]) );
						/*var newDiv = document.createElement('div');
						newDiv.innerHTML = panel.user.discount.glob.field.block(this.money[i], this.percent[i]);
						document.getElementById('user_discountDivMain').appendChild(newDiv);*/
					}
				}, {});
				panel.user.discount.personal.getlist();
			},
			glob:{
				field:{
					block:function(money, percent){
						money = money || '';
						percent = percent || '';
						return '\
							<tr>\
								<td class="text_align_c padding_10 vertical_align">\
									Если сумма покупок моих товаров больше \
									<input type="text" style="text-align: center;width:80px;" value="'+money+'" maxlength="16" /> <strong>руб.</strong>\
									- скидка составляет\
									<input type="text" style="text-align: center;width:40px;" value="'+percent+'" maxlength="2" /> <strong>%</strong> \
									<button class="btn btn-danger btn-sm" onclick="panel.user.discount.glob.field.del(this);return false;">  <i class="icon-minus icon-white"></i> Удалить</button>\
								</td>\
							</tr>\
						';
					},
					add:function(){
						var form = document.getElementById('panel_user_discount_global_form');
						if(form.getElementsByTagName('input').length == 20)return;

						$( '#user_discountDivMain' ).append( this.block() );

						/*var newDiv = document.createElement('div');
						newDiv.innerHTML = this.block();
						document.getElementById('user_discountDivMain').appendChild(newDiv);*/
					},
					del:function(button){
						var block = button.parentNode.parentNode;
						block.parentNode.removeChild(block);
					}
				},
				save:function(form){
					var fields = form.getElementsByTagName('input');

					var data = {
						fields: []
					};
					for(var i=0;i<fields.length/2;i++)data.fields[data.fields.length] = {
						money: fields[i*2].value,
						percent: fields[i*2+1].value
					};
					
					main.post('/panel/user/discount/save.php', function(){
						if(this.status == 'ok')location.reload();
					}, data, true, {preloader: form.button, error: 'panel_user_discount_glob_save_info'});
				}
			},
			personal: {
				add: function(form){
					var data = {
						email: form.email.value, 
						type: form.type[0].checked,
						percent: form.percent.value,
						money: form.money.value,
						captcha: form.captcha.value
					};
					main.post('/panel/user/discount/add_personal.php', function(){
						document.getElementById('panel_user_discount_personal_add_captcha').src = '/modules/captcha/captcha.php?'+Math.random();
						if(this.status == 'ok'){
							this.aobj.form.reset();
							panel.user.discount.personal.getlist();
						}
					}, data, true, {preloader: form.button, error: 'panel_user_discount_personal_add_error', form:form});
				},
				getlist: function(){
					main.post('/panel/user/discount/get_personal_list.php', function(){
						document.getElementById('panel_user_discount_personal_list').innerHTML = this.content;
					}, false, false, {preloader: document.getElementById('panel_user_discount_personal_list')});
				},
				del: function(discount_personal_id, button){
					var data = {discount_personal_id: discount_personal_id};
					main.post('/panel/user/discount/del_personal.php', function(){
						if(this.status != 'ok'){alert('Ошибка');return;}
						var p = this.aobj.preloader.parentNode.parentNode;
						p.parentNode.removeChild(p);
					}, data, true, {preloader: button});
				}
			}
		},
		myshop: {
			ready: function(){
				if(document.getElementById('sortListSel')){
					document.getElementById('sortListSel').style.visibility = "visible";
					document.getElementById('sortListSel').options[4].selected = true;
					panel.user.myshop.list.get(0, false, true);
				}
				if(document.getElementById('currentListSel')){
					document.getElementById('currentListSel').style.visibility = "visible";
				}
				
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.user.myshop.list', '/panel/user/myshop/list.php', 'panel_user_myshop_list', 100);
					var data = {sort: document.getElementById('sortListSel').value};
					
					this.pagination.get(data, list_number, method);

				}
			},
			edit: {
				save: function(form){
						var data = {
							product_id: jsconfig.productedit.id,
							name: form.name.value,
							price: form.price.value,
							current: form.current.value,
							promocodes: form.promocodes ? form.promocodes.checked : false,
							promocodes_val: form.promocodes_val ? form.promocodes_val.value : 0,
							description: form.description.value,
							info: form.info.value,
							group: document.getElementById('group2').value,
							picture: uploadPicture,
							partner:form.partner.value
						};
						main.post('/panel/user/productedit/action.php', function(){
						}, data, false, {error: 'productRedEror', button: form.button});	
				},
				ready: function(){
					var uploadDiv = document.getElementById('SWFuploadPictureDiv');
					var p = uploadDiv.innerHTML.split(':');
					uploadPicture = p[0];
					if(p[0] == 0){
							uploadDiv.innerHTML = '<div id="uploadPictureButton"></div><div id="SWFuploadPictureMessage"></div>';
							newSWFUploadPicture();
					}else{
						uploadDiv.innerHTML = '<img src="'+p[1]+'"/>';
						var deleteImg = document.createElement('div');
						deleteImg.innerHTML = '<div class="btn btn-sm btn-danger">Удалить</div>';
						deleteImg.onclick = function(){
							uploadDiv.removeChild(deleteImg);
							uploadDiv.innerHTML = '<div id="uploadPictureButton"></div><div id="SWFuploadPictureMessage"></div>';
							newSWFUploadPicture();
						}
						uploadDiv.appendChild(deleteImg);
					}
				},
				obj: {
					edittextobj: function(button, product_obj_id){
						var textfield = document.getElementById('panel_user_myshop_edit_obj_edittextobj_obj_'+product_obj_id);
						if(textfield.disabled){
							textfield.disabled = false;
							button.innerHTML = 'сохранить';
							button.className = 'btn btn-sm btn-success';
							textfield.style.backgroundColor = '#ffffff';
						}else{
							textfield.disabled = true;
							button.innerHTML = 'изменить';
							button.className = 'btn btn-sm btn-info';
							textfield.style.backgroundColor = '#ffffff';
							var button_html = button.parentNode.innerHTML;
							button_block = button.parentNode;
							button.parentNode.innerHTML = main.loading;
							var data = {
								product_obj_id: product_obj_id, 
								text: textfield.value
							};
							main.post('/panel/user/productobj/edittext.php', function(){
								if(this.status == 'ok')this.aobj.button_block.innerHTML = this.aobj.button_html;
								else alert('Ошибка');
							}, data, true, {button_html: button_html, button_block: button_block});									
						}
					},
					add: {
						field: {
							add: function(text){
								text = text || '';
								var newtext = '\
												<tr>\
													<td style="width: 180px;" class="font_size_14 font_weight_700 padding_10 text_align_r">Текст (объект продажи):</td>\
													<td class="padding_10 vertical_align"><textarea class="textarea_def form-control" maxlength="43000" rows="4">' + text + '</textarea></td>\
													<td class="padding_10 text_align_c"><a class="btn btn-sm btn-danger" onclick="panel.user.myshop.edit.obj.add.field.del(this);">Удалить</a></td>\
												</tr>\
								';	
								$( '#panel_user_myshop_edit_obj_add_fields' ).append( newtext );	
							},
							del: function(button){
								button.parentNode.parentNode.parentNode.removeChild(button.parentNode.parentNode);
							}
						},
						savetextobj: function(csv){
							csv = csv || false;
							var texts = document.getElementById('panel_user_myshop_edit_obj_add_fields');
							var textsval = [];
							$('#panel_user_myshop_edit_obj_add_fields textarea').each(function(){
								textsval.push(this.value);
							});
							var data = {
								product_id: jsconfig.productobj.product_id,
								texts: textsval
							};
							main.post('/panel/user/productobj/save.php', function(){
								if(this.status == 'ok')main.forwarding('Данные успешно сохранены', '/panel/productobj/edit/'+data.product_id);
							}, data, true, {preloader: document.getElementById('panel_user_myshop_edit_obj_add_button'), error: 'panel_user_myshop_edit_obj_add_error'});
						},
						multi: {
							upload: function(form){
								document.getElementById('panel_user_myshop_edit_obj_add_multi_upload_info').className = 'form_info';
								main.upload(form.file.files[0], '/panel/user/productobj/savetextmulti.php', 'panel_user_myshop_edit_obj_add_multi_upload_info', function(){
									if(this.status == 'error'){
										document.getElementById(this.div).className = 'form_error';
										document.getElementById(this.div).innerHTML = this.message;
										return;
									}
									if(this.status != 'ok'){alert('Ошибка');return;}
									document.getElementById(this.div).className = 'form_success';
									document.getElementById(this.div).innerHTML = 'Загружен';
									for(var k in this.content)
										panel.user.myshop.edit.obj.add.field.add(this.content[k]);
								}, true);
							}
						}
					},
					delall: function(button){
						if(!confirm('Вы уверены, что хотите удалить все объекты продажи этого товара?'))return;
						var data = {
							product_id: jsconfig.productobj.product_id
						};
						main.post('/panel/user/productobj/delall.php', function(){
							if(this.status != 'ok'){alert('Ошибка');return}
							main.forwarding('Объекты успешно удалены', '/panel/productobj/edit/'+data.product_id);
						}, data, true, {button: button});						
					},
					save: function(button){
						var data = {
							product_id: jsconfig.productobj.product_id,
							file: {
								dir: fileUploadSuccessDir,
								name: fileUploadSuccessName
							}
						};
						main.post('/panel/user/productobj/save.php', function(){
							if(this.status == 'ok')if(this.status == 'ok')main.forwarding('Данные успешно сохранены', '/panel/productobj/edit/'+data.product_id);
							else alert('Ошибка');
						}, data, false, {button: button});
					},
					del_textobj: function(button, product_obj_text_id){
						if(!confirm('Уверены, что хотите безвозвратно удалить объект?'))return;
						var p = button.parentNode.parentNode;
						p.innerHTML = main.loading;
						var data = {
							product_id: jsconfig.productobj.product_id,
							product_obj_id: product_obj_text_id								
						};
						main.post('/panel/user/productobj/del.php', function(){
							if(this.status != 'ok'){document.getElementById('page').innerHTML = 'Ошибка'; return;}
							this.aobj.p.parentNode.parentNode.removeChild(this.aobj.p.parentNode);
							if(this.refresh)location.reload();
						}, data, false, {p: p});						
					},
					file_del: function(product_file_dir, button, product_file_id){
						if(!confirm('Уверены, что хотите безвозвратно удалить файл?'))return;
						product_file_id = product_file_id || false;
						var p = button.parentNode.parentNode;

						if(product_file_id){
							var data = {
								product_id: jsconfig.productobj.product_id,
								product_obj_id: product_file_id								
							};
							main.post('/panel/user/productobj/del.php', function(){
								if(this.status != 'ok'){document.getElementById('page').innerHTML = 'Ошибка'; return;}
								(this.aobj.p).parentNode.removeChild(this.aobj.p);
							}, data, false, {p: p, preloader: button});
						}else{
							var index = fileUploadSuccessDir.indexOf(product_file_dir);
							fileUploadSuccessDir.splice(index, 1);
							fileUploadSuccessName.splice(index, 1);
							p.parentNode.removeChild(p);
						}
						if(typeof(swfu) == "undefined"){
							file_upload_limitVar = "1";
							newSWFUploadFiles();
						}
						else{
						   if(file_upload_limitVar > 0){
							  file_upload_limitVar++;
							  swfu.setFileUploadLimit(file_upload_limitVar);
						   }
						}
					},
					ready: function(){
						if(jsconfig.productobj.type == 0){
							fileUploadSuccessName = [];
							fileUploadSuccessDir = [];
							if(jsconfig.productobj.many != true){
								file_upload_limitVar = 0;
								newSWFUploadFiles();
							}else if(jsconfig.productobj.many == true && jsconfig.productobj.count == 0){
								file_upload_limitVar = 1;
								newSWFUploadFiles();
							}
						}
					}
				}
			},
			del: function(product_id, elm){
				if(!confirm("Удалить товар "+product_id))return;
				var data = {
					product_id: product_id
				};
				main.post('/panel/user/productdel/action.php', function(){
					if(this.status == 'ok'){
						var elm = this.aobj.elm.parentNode.parentNode;
						elm.parentNode.removeChild(elm)
					}
				}, data, false, {elm: elm});
			},
			add: {
				ready: function(){
					newSWFUploadPicture();
				},
				send: function(form){
					var data = {
						group: form.group2.value,
						obj_many: form.many[0].checked,
						obj_type: form.type[0].checked,
						name: form.name.value,
						price: form.price.value,
						current: form.current.value,
						picture: uploadPicture,
						promocodes: form.promocodes ? form.promocodes.checked : false,
						promocodes_val: form.promocodes_val ? form.promocodes_val.value : 0,
						description: form.description.value,
						info: form.info.value,
						partner:form.partner.value
					};
					main.post('/panel/user/productadd/action.php', function(){
						if(this.status == 'ok')main.forwarding('Товар успешно создан', '/panel/productobj/add/'+this.product_id);
					}, data, false, {preloader: form.submit, error: 'panel_user_myshop_add_error'});
				}
			}
		},
		blacklist: {
			add: function(form){
				var data = {
					email: form.email.value
				};
				main.post('/panel/user/blacklist/add.php', function(){
					if(this.status == 'ok')location.reload();
				}, data, false, {button: this.button, error: 'panel_user_blacklist_add_info'});
			},
			del: function(blacklist_id, email){
				if(!confirm('Уверены, что хотите удалить '+email+' из черного списка?'))return;
				var data = {
					blacklist_id: blacklist_id
				};
				main.post('/panel/user/blacklist/del.php', function(){
					if(this.status == 'ok'){
						var p = this.aobj.preloader.parentNode;
						p.parentNode.removeChild(p);
					}
					else alert('Ошибка');
				}, data, false, {preloader: document.getElementById('panel_user_blacklist_del_'+blacklist_id)});
			}
		},
		messages: {
			ready: function(){
				if(document.getElementById('userOrAdmin_messageList'))panel.user.messages.list.get(0, false, true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.user.messages.list', '/panel/glob/messages/getList.php', 'userOrAdmin_messageList', 25);
					var data = {};
					this.pagination.get(data, list_number, method);	
				}
			},
			answer: {
				button: 0,
				send: function(form){
					var data = {
						text: form.text.value,
						topic: jsconfig.messages.topic
					};
					main.post('/panel/glob/messages/answer.php', function(){
						if(this.status == 'ok')location.reload();
					}, data, true, {preloader:form.button,error:'message_answer_error'});
				}
			},
			topic: {
				button: 0,
				create: function(form){
					var data = {
						title: form.title.value,
						text: form.text.value,
						to: jsconfig.messages.to
					};
					main.post('/panel/glob/messages/new.php', function(){
						if(this.status == 'ok')location.reload();
					}, data, false,{error:'newtopic_error',preloader:form.button});
				}
			}
		},
		cashout: {
			ready: function(){
				panel.user.cashout.list.get(0, false, true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly){
					if(newly)this.pagination = new Pagination('panel.user.cashout.list', '/panel/user/cashout/getList.php', 'user_cashoutRequestListDiv', 25);
					var data = {};
					this.pagination.get(data, list_number, method);	
				}
			},
			add: function(form){
				var data = {
					amount: form.amount.value,
					type: $('input[name=cashout_type]:checked').val()
				};
				main.post('/panel/user/cashout/add.php', function(){
					if(this.status == 'ok'){
						var bal = document.getElementById('user_cashoutWMRbalance');
						bal.innerHTML = (bal.innerHTML - this.aobj.amount).toFixed(2);
						panel.user.cashout.list.get(0, false, true);
					}
				}, data, true, {amount: form.amount.value, button: form.button, error: 'panel_user_cashout_add_error'});
			}
		},
		cabinet: {
			useredit: function(form, role){
				var a = new Array('login', 'fio', 'phone', 'skype', 'icq', 'email', 'pass', 'passr', 'wm', 'wmz', 'wmr', 'wme', 'wmu', 'yandex_purse', 'qiwi_purse');
				if(!form.phone){
					$( '[data-form="user_cabine"]' ).html( 'Сохранить' );
					$( '[data-form="user_cabine"]' ).addClass('cabinet_edit_opened');
					$('#SWFuploadPictureDiv .btn-danger').css('display', 'inline-block');
					for(var i=0;i<a.length;i++){
						if((role != 'admin' && role != 'moder') && (a[i] == 'login' || a[i] == 'fio' || a[i] == 'email'))continue;
						if((role != 'admin' && role != 'moder') && i>7 && document.getElementById("user_cabinet"+a[i]).innerHTML != "")continue;
						if(a[i] == 'pass' || a[i] == 'passr'){
							document.getElementById("user_cabinet"+a[i]).innerHTML = '<input maxlength="18" type="password" name="'+a[i]+'" value="#$%|notChanged%">';
							//document.getElementById("user_cabinetPassDoubleDiv").style.display = "block";
							$( '[data-user_cabinet="pass"]' ).fadeIn();
						continue;
					}
					document.getElementById("user_cabinet"+a[i]).innerHTML = '<input type="text" maxlength="24" id="'+a[i]+'" value="'+document.getElementById("user_cabinet"+a[i]).innerHTML+'">';
					}
					return;
				}
				var data = {
					user_id: document.getElementById("user_cabinetId").innerHTML,
					login: form.login ? form.login.value : false,
					fio: form.fio ? form.fio.value : false,
					phone: form.phone ? form.phone.value : false,
					skype: form.skype ? form.skype.value : false,
					site: form.icq ? form.icq.value : false,
					email: form.email? form.email.value : false,
					pass: form.pass ? form.pass.value : false,
					passr: form.passr ? form.passr.value : false,
					wm: form.wm ? form.wm.value : false,
					wmz: form.wmz ? form.wmz.value : false,
					wmr: form.wmr ? form.wmr.value : false,
					wme: form.wme ? form.wme.value : false,
					wmu: form.wmu ? form.wmu.value : false,
					yandex_purse: form.yandex_purse ? form.yandex_purse.value : false,
					qiwi_purse: form.qiwi_purse ? form.qiwi_purse.value : false
				};
				main.post('/panel/glob/useredit/action.php', function(){
					var url = jsconfig.sp == 'cabinet' ? '/panel/cabinet/' : '/panel/newusers/';
					if(this.status == 'ok')main.forwarding('Данные успешно сохранены', url);
				}, data, true, {preloader: form.button, error: 'panel_cabinet_useredit_info'});
			},
            emailInforming: function(button){
                var value = $(button).data('value');
				var data = {
                    value: value
                };
                main.post('/ajax/user/emailInforming/user', function(){
                    if(this.status == 'ok'){
                    	button.innerHTML = value === 1 ? 'Запретить' : 'Разрешить';
                        $(button).data('value', value === 1 ? 0 : 1);
                    }else popupOpen('<div class="form_error">Невозможно сохранить</div>');
                }, data, false, {preloader:button});
            },
            twinAuthenticateSwitch: function(form){
				var data = {
                    code: form.code.value,
                    pass: form.pass.value,
                    secret: form.secret.value,
                    action: form.action.value
                };
                main.post('/ajax/user/twinAuthenticateSwitch/user', function(){
                    if(this.status == 'ok'){
                        main.forwarding('Успешно', '/panel/cabinet/');
                    }
                }, data, false, {preloader: form.button, error: 'panel_cabinet_twin_auth_info'});
            },
			edit: function(form){
				main.post('/panel/glob/useredit/edit.php', function(){
					common.popup.open(this.content, false, 'Настройки', form);
					$('.btn-avatarka').show();
				}, {}, true, {preloader: document.getElementById("user_cabinetUserEdit")});

				main.post('/panel/user/privileges/notification.php', function(){
					if(this.content){
						popupOpen(this.content, true);
					}
				}, {}, true, {preloader: false});
			},
			confirm_email: function(e){
				if (confirm("Вы действительно хотите отправить письмо подтверждения?")){
					main.post('/panel/glob/useredit/send_confirmation.php', function(){
						$(e).hide();
						popupOpen('<div style="font-size:18px; padding:20px 10px">Ваша учетная запись будет подтверждена после перехода по ссылке, что указана в теле письма</div>', true);
					}, {}, true, {preloader: $(e)});
				}
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly, cabinet, elements, page, mshop){
					if (typeof(elements)=="undefined"){elements = 25;}
					var url;

					if(jsconfig.curr_page == 'orders' || page == 'merchant'){
						url = '/panel/glob/orderSearch/getOrderListMerchant.php';
					}else{
						url = '/panel/glob/orderSearch/getOrderList.php'
					}
					if(newly)this.pagination = new Pagination('panel.user.cabinet.list', url, 'panel_user_cabinet_list', elements);
					var data = {
						req: $('#glob_orderSearchText').val(),
						par: $('#glob_orderSearchSelect').val(),
						page: jsconfig.sp,
						mshop: mshop
					};

					if(isEmpty(data.req)){

						if( !(data.par == 'all' || data.par =='new') ){
							alert('Введите запрос'); return;
						} 
						
					}

					if(jsconfig.curr_page == 'orders'){
						panel.admin.order.list.get(list_number, method, data);	
					}else{
						this.pagination.get(data, list_number, method);	
					}
					
				}
			},
			message: function(form){
				var data = {
					order_id: jsconfig.sales.order_id,
					text: form.text.value
				};
				main.post('/panel/user/cabinet/send_message.php', function(){
					if(this.status == 'ok')location.reload();
				}, data, true, {preloader: form.button, error: 'message_send_error'});
			},
			file: function(form, type){
				var data = new FormData($(form)[0]);
				$(form).find("input[name='upload']").css('opacity', '0.5');
				$(form).find("input[name='upload']").css('pointer-events', 'none');
				$.ajax({
				        // Your server script to process the upload
				        url: '/panel/user/cabinet/send_file.php',
				        type: 'POST',

				        // Form data
				        data: data,

				        // Tell jQuery not to process data or worry about content-type
				        // You *must* include these options!
				        cache: false,
				        contentType: false,
				        processData: false,

				        // Custom XMLHttpRequest
				        xhr: function() {
				            var myXhr = $.ajaxSettings.xhr();
				            if (myXhr.upload) {
				                // For handling the progress of the upload
				                myXhr.upload.addEventListener('progress', function(e) {
				                    if (e.lengthComputable) {
				                        $('progress').attr({
				                            value: e.loaded,
				                            max: e.total,
				                        });
				                    }
				                } , false);
				            }
				            return myXhr;
				        },
				    }).done(function(e){
				    	e = JSON.parse(e);
				    	if(e.status != 'ok'){
				    		alert(e.message);
				    	}else{

				    		if(type=='customer'){
				    			var error = document.getElementById('message_send_error');
								error.innerHTML = "";
								
								var res = ajaxxx('/modules/customer/send_message.php','&h='+jsconfig.customer.h+'&i='+jsconfig.customer.i+'&text='+e.path+'&picture='+e.id);
								//alert(res);
								res = JSON.parse(res);
								if(res.status == 'error'){
									switch (res.message) {
									  case 'parameters_empty':
										error.innerHTML = "Введите текст сообщения";
										break
									  default:
										error.innerHTML = "Ошибка при создании сообщения";
									}
									return false;
								}
								location.reload();	
				    		}else{
				    			var data = {
									order_id: jsconfig.sales.order_id,
									text: e.path,
									picture: e.id
								};
								main.post('/panel/user/cabinet/send_message.php', function(){
									if(this.status == 'ok')location.reload();
								}, data, true, {preloader: form.button, error: 'message_send_error'});
				    		}
				    	}
				    	$(form).find("input[name='upload']").css('opacity', '1');
						$(form).find("input[name='upload']").css('pointer-events', 'all');
				    	
				    });
			},
			review_answer: function(form){
				var data = {
					review_id: form.review_id.value,
					text: form.text.value
				};
				main.post('/panel/user/cabinet/review_answer.php', function(){
					if(this.status == 'ok')location.reload();
				}, data, true, {preloader: form.button, error: 'review_answer_error'});
			},
			ready: function(){

				$('#currentShop').val() ? setCookie('mshop_id', $('#currentShop').val()) : '';

				var self = this;
				$('.chartPeriod').on('change',function(){
					getCookie('panel_mode') == 'merchant' ?  panel.user.cabinet.chartsMerchant($(this).val(), getCookie('mshop_id')) : panel.user.cabinet.charts($(this).val()) ;
					getCookie('panel_mode') == 'merchant' ?  self.show_stats($(this).val(), getCookie('mshop_id')) : self.show_stats($(this).val());
				});
				getCookie('panel_mode') == 'merchant' ? this.chartsMerchant(0, getCookie('mshop_id')) : this.charts();
				getCookie('panel_mode') == 'merchant' ? this.show_stats(0, getCookie('mshop_id')) : this.show_stats(0);
				this.list.get(0, false, true, true, 10, getCookie('panel_mode'), getCookie('mshop_id'));

				var spoilers = ['stat', 'graph', 'sales', 'reviews', 'topsales'];
				$(spoilers).each(function(i,j){
					getCookie('spoiler_'+j) == '0' ? $('.spoiler_'+j).find('.spoiler').hide() : '';
				});

			},
			edit_avatar: function(src){
				main.post('/panel/user/cabinet/avatar/change_avatar.php', function(){
					popupOpen(this.content, false, 'Изменить аватар');
					main.swfUploadReady(true, 'SWFuploadPictureDiv2');
				}, {src: src}, false, {});
			},
			show_logs: function(element){
				if($(element).parent().find('.spoiler').length == 0){
					main.post('/panel/user/cabinet/logs/show_logs.php', function(){
						$('.spoiler_4').append(this.content);
						showSpoiler(element);
					}, {}, false, {});
				}else{
					showSpoiler(element);
				}
			},
			panel_mode_switch: function(element, event){
				var panel_mode = getCookie('panel_mode');

				var CookieDate = new Date;
				CookieDate.setFullYear(CookieDate.getFullYear( ) +1);

				panel_mode == 'shop' ? setCookie('panel_mode', 'merchant', CookieDate.toGMTString()) : setCookie('panel_mode', 'shop', CookieDate.toGMTString());
				window.location.href = '/panel/cabinet';
			},
			news_popup: function(){
				main.post('/panel/user/cabinet/news_popup/content.php', function(){
					popupOpen(this.content, false, 'Новости');
				}, {}, false, {});
			},
			charts:function(period){
				if (typeof(period)=="undefined"){period = 0;}
				var self = this;
				$('.chartPeriod div').each(function(i){this.className = i == period ? 'btn btn-info btn-sm active' : 'btn btn-primary btn-sm'});
				main.post('/ajax/sales/charts/user/', function(){
					if(this.status == 'ok'){
						if(this.sales.dt.length == 0)$('#salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#salesGraph').highcharts({
								chart:{type:'column'},
								title:{text: 'Продажи на общую сумму'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								xAxis: [{
									categories:this.sales.dt
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series:[{
									yAxis: 1,
									xAxis: 0,
									name: 'Продажи',
									data:this.sales.sales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Прибыль',
									data: this.sales.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
						if(this.reviews.good == 0 && this.reviews.bad == 0)$('#reviewsGraph').html('<div class="noData"><p class="head">Отзывы</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#reviewsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Отзывы'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Отзывов <b>{point.y} ({point.percentage:.1f}%)</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data: [{
										name: 'Положительные',
										y:this.reviews.good,
										color:'#8DF184'
									}, {
										name: 'Отрицательные',
										y:this.reviews.bad,
										color:'#F18484'
									}]
								}]
							});
						}
						if(this.products.length == 0)$('#productsGraph').html('<div class="noData"><p class="head">Топ продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#productsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Топ продаж'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Продаж: <b>{point.y}</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data:this.products
								}]
							});
						}
						$('#all_profit').html(this.allProfit);
						var avg_profit = this.allProfit ?  this.allProfit/ $('#success_sales').data('count') : 0;
						$('#average_profit').html(Math.round(avg_profit));
						$('.highcharts-title').html('Продажи на общую сумму '+panel.user.sales.commaSeparateNumber(this.allProfit)+' руб.');
					}
				},{period:period},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
			},
			chartsMerchant:function(period, id){
				if (typeof(period)=="undefined"){period = 0;}
				var self = this;
				main.post('/ajax/sales/chartsMerchant/user/', function(){
					if(this.status == 'ok'){
						if(this.sales.dt.length == 0)$('#salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#salesGraph').highcharts({
								chart:{type:'column'},
								title:{text: 'Продажи'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								xAxis: [{
									categories:this.sales.dt
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series:[{
									yAxis: 1,
									xAxis: 0,
									name: 'Продажи',
									data:this.sales.sales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Прибыль',
									data: this.sales.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
						$('#all_profit').html(this.allProfit);
						var avg_profit = this.allProfit ?  this.allProfit/ $('#success_sales').data('count') : 0;
						$('#average_profit').html(Math.round(avg_profit));
						$('.highcharts-title').html('Продажи на общую сумму '+panel.user.sales.commaSeparateNumber(this.allProfit)+' руб.');
					}
				},{period:period, id:id},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
			},
			show_stats: function(period, id){
				main.post('/panel/user/cabinet/stats/content.php', function(){
					$('#user_cabinetStat').html('');
					$('#user_cabinetStat').append(this.content);
				}, {period: period, type: getCookie('panel_mode'), id: id}, false, {});
			},
			mshop_set: function(element){
				setCookie('mshop_id', $(element).val());
				if(jsconfig.module == 'merchant'){

					var urlParts = window.location.pathname.split('/');
					var newUrl = '';

					$(urlParts).each(function(i, j){

						if(j != ''){
							if($.isNumeric(j)){
								newUrl = newUrl + '/' + $(element).val();
							}else{
								newUrl = newUrl + '/' + j;
							}
						}

					})
					newUrl += '/#anchor';
					var currentUrl = window.location.pathname + '#anchor';

					currentUrl == newUrl ? window.location.reload() : window.location.href = newUrl;

				}else{
					window.location.reload();
				}

			}
		},
		sales:{
			ready: function(){
				if(!jsconfig.sales){
					$('.chartPeriod div').each(function(i){$(this).on('click',function(){panel.user.sales.charts(i);});});
					main.post('/panel/glob/orderSearch/orderSearch.php', function(){
						if(this.status == 'ok'){
							document.getElementById("user_cabinetOrder").innerHTML = this.content;
							panel.user.cabinet.list.get(0, false, true);
							$( 'select:not([multiple])' ).styler();
						}
					}, false, false, {preloader: document.getElementById("user_cabinetOrder")});
					this.charts();
				}
			},
			commaSeparateNumber:function(val){
				while (/(\d+)(\d{3})/.test(val.toString())){
					val = val.toString().replace(/(\d+)(\d{3})/, '$1'+'.'+'$2');
				}
				return val;
			},
			charts:function(period){
				if (typeof(period)=="undefined"){period = 0;}
				var self = this;
				$('.chartPeriod div').each(function(i){this.className = i == period ? 'btn btn-info btn-sm active' : 'btn btn-primary btn-sm'});
				main.post('/ajax/sales/charts/user/', function(){
					if(this.status == 'ok'){
						if(this.sales.dt.length == 0)$('#salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#salesGraph').highcharts({
								chart:{type:'column'},
								title:{text: 'Продажи'},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								xAxis: [{
									categories:this.sales.dt
								}],
								yAxis: [{
									min:0,
									allowDecimals: false,
									title:{text:''},
									labels:{style:{color: Highcharts.getOptions().colors[3]}}
								},{
									min:0,
									allowDecimals: false,
									title:{text:''},
									opposite: true,
									labels:{style:{color: Highcharts.getOptions().colors[0]}}
								}],
								series:[{
									yAxis: 1,
									xAxis: 0,
									name: 'Продажи',
									data:this.sales.sales
								},{
									yAxis: 0,
									xAxis: 0,
									color:Highcharts.getOptions().colors[3],
									type: 'spline',
									name: 'Прибыль',
									data: this.sales.profit,
									marker: {
										height:8,
										width:8,
										lineWidth: 2,
										lineColor: Highcharts.getOptions().colors[3],
										fillColor: 'white'
									}
								}]
							});
						}
						if(this.reviews.good == 0 && this.reviews.bad == 0)$('#reviewsGraph').html('<div class="noData"><p class="head">Отзывы</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#reviewsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Отзывы'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Отзывов <b>{point.y} ({point.percentage:.1f}%)</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data: [{
										name: 'Положительные',
										y:this.reviews.good,
										color:'#8DF184'
									}, {
										name: 'Отрицательные',
										y:this.reviews.bad,
										color:'#F18484'
									}]
								}]
							});
						}
						if(this.products.length == 0)$('#productsGraph').html('<div class="noData"><p class="head">Товары</p><p class="infotext">Для построения графика пока нет данных</p></div>');
						else{
							$('#productsGraph').highcharts({
								chart: {
									plotBackgroundColor: null,
									plotBorderWidth: null,
									plotShadow: false,
									type: 'pie'
								},
								title: {
									text: 'Товары'
								},
								exporting: {
									buttons: {
										contextButton: {
											enabled: false
										}
									}
								},
								tooltip: {
									pointFormat: 'Продаж: <b>{point.y}</b>'
								},
								plotOptions: {
									pie: {
										allowPointSelect: true,
										cursor: 'pointer',
										dataLabels: {
											enabled: false
										},
										showInLegend: true
									}
								},
								series: [{
									colorByPoint:true,
									data:this.products
								}]
							});
						}
						$('.highcharts-title').html('Продажи на общую сумму '+self.commaSeparateNumber(this.allProfit)+' руб.');
					}
				},{period:period},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
			}
		},
		review: {
			ready: function(){
					panel.user.review.list.get(0, false, true);
			},
			list: {
				pagination: 0,
				get: function(list_number, method, newly, search){
					if(newly)this.pagination = new Pagination('panel.user.review.list', '/panel/user/review/getList.php', 'panel_user_review_list', 25);
					var data = {good: 2, search: false, text: false};
					
					if(typeof search === 'undefined'){
						if(document.getElementsByName('user_reviewListGood')[1].checked == true)data.good = 3;
						if(document.getElementsByName('user_reviewListGood')[2].checked == true)data.good = 1;
						if(document.getElementsByName('user_reviewListGood')[3].checked == true)data.good = 0;
					}else{
						data.search = $('#searchForm #glob_orderSearchSelect').val();
						data.text = $('#searchForm #glob_orderSearchText').val();
					}

					this.pagination.get(data, list_number, method);	
				}
			},
			answer_popup: function(id){
				popupOpen(main.loading);
				var data = {review_id: id};
				data = JSON.stringify(data);
				main.post('/panel/user/review/addPopup.php', function(){
					popupOpen(this.content);
				}, data);
			},
			change: function(id, element){
				
				var textElement = $(element).parent().find('.middle_lot_o_t2')
				var oldText = $(textElement).text();

				if(!$(element).hasClass('changing')){
					$(element).addClass('changing');
					$(element).text('Сохранить');
					$(textElement).html('<input style="width: 100%;height: 30px;" type="text" id="newreview" name="newreview" value="'+oldText+'">');
				
				}else{
				
					$(textElement).html($('#newreview').val());

					$(element).removeClass('changing');
					$(element).text('Изменить');

					var data = {review_id: id, text: $(textElement).text()};
					data = JSON.stringify(data);
					main.post('/panel/user/review/answer_change.php', function(){
					}, data);
				}
				
			},
			delete: function(id, element){

				if(confirm('Вы действительно хотите удалить ответ?')){
					var data = {review_id: id};
					data = JSON.stringify(data);
					main.post('/panel/user/review/answer_delete.php', function(){
						console.log($(element).parent());
						$(element).parent().remove();
						window.location.reload();
					}, data);	
				}
				
			},
			answer: {
				popup: function(id){
					popupOpen(main.loading);
					main.post('/panel/user/review/addPopup.php', function(){
						popupOpen(this.content, false, 'Ответ');
					}, {review_id:id});
				},
				send: function(form){
					var data = {
						review_id: form.review_id.value,
						text: form.text.value
					};
					main.post('/panel/user/cabinet/review_answer.php', function(){
						if(this.status == 'ok')location.reload();
					}, data, true, {preloader: form.button, error: 'panel_review_answer_info'});
				}
			}
		},
		promocodes: {
			ready: function(){
				if(jsconfig.promocodes && jsconfig.promocodes.a == 'add'){
					$('[data-calendar="datestart"]').Zebra_DatePicker({
						direction: true,
						format: 'd-m-Y',
						pair: $('[data-calendar="dateend"]')
					});
					$('[data-calendar="dateend"]').Zebra_DatePicker({
						direction: 1,
						format: 'd-m-Y'
					});
				}
				if(document.getElementById('panel_user_promocodes_edit_tab'))this.edit.lists();
			},
			edit: {
				/*page: function(promocode_id){
					changePage('p,sp,promocode_id','user_promocodes,edit,'+promocode_id);
				},*/
				lists: function(){
					var data = {
						promocode_id: jsconfig.promocodes.promocode_id
					};
					data = JSON.stringify(data);
					var file = main.tab.getnum('panel_user_promocodes_edit_tab') == 0 ? 'products' : 'codes';
					
					main.post('/panel/user/promocodes/'+file+'_list.php', function(data){
						document.getElementById('panel_user_promocodes_edit_lists').innerHTML = this.content;
						if(this.count && this.count > 0){
							$( '#promocodes_edit_percentall_block' ).fadeIn();
						}
					}, data, false, {preloader: document.getElementById('panel_user_promocodes_edit_lists')});
				},
				products: {
					add: function(element, type){
						
						var form = $(element).parents("form")[0];
						var product_ids = [];

						$("#productSelectInput option").each(function(id, el){
							if($(el).val() != 0){
								product_ids.push($(el).val());
							}
						});
						if(type == 'one')product_ids = [form.product.value];

						var data = {
							promocode_id: jsconfig.promocodes.promocode_id,
							products: product_ids,
							percent: form.percent.value
						};
						main.post('/panel/user/promocodes/product_add.php', function(){
							if(this.status == 'ok'){
								panel.user.promocodes.edit.lists();
								document.getElementById('panel_user_promocodes_products_count').innerHTML = document.getElementById('panel_user_promocodes_products_count').innerHTML - 0 + 1;
							}
						}, data, false, {preloader: form.button, error: 'panel_user_promocodes_products_add_error'});
					},
					save: function(form){
						var inputs = form.getElementsByTagName('input');
						var percents_name = [];
						var percents_value = [];
						for(var input_num in inputs){
							if(inputs[input_num].name == 'percentall')break;
							percents_name[percents_name.length] = inputs[input_num].name;
							percents_value[percents_value.length] = inputs[input_num].value;
						}
						var data = {
							promocode_id: jsconfig.promocodes.promocode_id,
							percents_name: percents_name,
							percents_value: percents_value,
							percentall: form.percentall.checked,
							percentall_value: form.percentall_value.value
						};
						main.post('/panel/user/promocodes/products_edit.php', function(){
							if(this.all){
								for(var num in this.aobj.form){
									if(this.aobj.form[num].name == 'percentall')break;
									this.aobj.form[num].value = this.aobj.form.percentall_value.value;
								}
							}
						}, data, false, {preloader: this.button, error: 'panel_user_promocodes_edit_products_save_error', form: form});
					},
					del: function(promocode_product_id, icon){
						var data = {
							promocode_product_id: promocode_product_id
						};
						main.post('/panel/user/promocodes/product_del.php', function(){
							if(this.status == 'ok'){
								panel.user.promocodes.edit.lists();
								document.getElementById('panel_user_promocodes_products_count').innerHTML = document.getElementById('panel_user_promocodes_products_count').innerHTML - 1;
							}
						}, data, false, {preloader: icon.parentNode});
					}
				},
				codes: {
					save: function(form){
						var inputs = form.getElementsByTagName('input');

						var comment_name = [];
						var comment_value = [];
						$(inputs).each(function(i, element, j){
							comment_name[i] = $(element).attr('name');
							comment_value[i] = $(element).val();
						});

						var data = {
							promocode_id: jsconfig.promocodes.promocode_id,
							comment_name: comment_name,
							comment_value: comment_value
						};
						main.post('/panel/user/promocodes/codes_save.php', function(){
							
						}, data, false, {preloader: this.button, error: 'panel_user_promocodes_edit_codes_save_error', form: form});
					}
				}
			},
			del: function(promocode_id, button){
				if(!confirm('Уверены, что хотите удалить выпуск?'))return;
				var data = {
					promocode_id: promocode_id
				};
				main.post('/panel/user/promocodes/del.php', function(){
					if(this.status == 'ok')main.forwarding('Выпуск успешно удалён', '/panel/promocodes/');
				}, data, false, { error: 'panel_user_promocodes_edit_products_save_error'});
			},
			add: {
				send: function(form){
					var data = {
						name: form.name.value,
						type: form.type.value,
						maxuse: form.maxuse.value,
						count: form.count ? form.count.value : '',
						datestart: form.datestart.value,
						dateend: form.dateend.value,
						group: form.promocode_type.value,
						code: form.code ? form.code.value : ''
					};
					main.post('/panel/user/promocodes/add.php', function(){
						if(this.status == 'ok')main.forwarding('Выпуск создан', '/panel/promocodes/edit/'+this.promocode_id);
					}, data, true, { error: 'panel_user_promocodes_products_add_error'});						
				},
				changetype: function(sel){
					sel.value == 3 ? $(sel).parent().find('#panel_user_promocodes_add_maxuse').css('display', 'inline-block') : $(sel).parent().find('#panel_user_promocodes_add_maxuse').hide();
				},
				change_group_type: function(type, el){
					$('.btn-recommended').removeClass('active');
					$(el).addClass('active');

					$('.panel-body.promocodes-tab').addClass('hidden');
					$('.promocodes-'+type).removeClass('hidden');
				}
			}
		},
		shopsite:{
			ready:function(){
				this.category.list();
				this.product.list.get(0,false, true);
			},
			category:{
				list:function(){
					var data = {};
					main.post('/panel/user/shopsite/getCategoryList.php', function(){
							if(this.status == 'ok')document.getElementById('user_shopsiteCategoryList').innerHTML = this.content;
					}, data, false, {preloader:document.getElementById('user_shopsiteCategoryList')});
				},
				create:function(form){
					var data = {
						name: form.name.value
					};
					main.post('/panel/user/shopsite/category_create.php', function(){
						if(this.status == 'ok'){
							panel.user.shopsite.category.list();
							panel.user.shopsite.product.list.get();
							this.aobj.form.reset();
						}
					}, data, false, {preloader:form.button,form:form,error:'category_create_error'});
				},
				del:function(category_id, button){
					if(!confirm('Удалить категорию?'))return;
					var data = {
						category_id: category_id
					};
					main.post('/panel/user/shopsite/category_delete.php', function(){
						if(this.status == 'ok'){
							panel.user.shopsite.category.list();
							panel.user.shopsite.product.list.get();
						}
					}, data, false, {preloader:button});
				}
			},
			product:{
				list:{
					pagination: 0,
					get:function(list_number, method, newly, category_id){
						if(newly)this.pagination = new Pagination('panel.user.shopsite.product.list', '/panel/user/shopsite/getlist.php', 'user_shopsiteListDiv', 10);
						var data = {
							category_id: category_id || 0
						};
					
						this.pagination.get(data, list_number, method);
					}
				},
				category_change:function(product_id, category_id){
					var data = {
						product_id: product_id,
						category_id: category_id
					};
					main.post('/panel/user/shopsite/category_change.php', function(){
						if(this.status != 'ok')popupOpen('<div class="form_error">'+this.message+'</div>');
					}, data, false);
				},
				move:function(product_id, method, button){
					var data = {
						product_id: product_id,
						method: method
					};
					main.post('/panel/user/shopsite/move.php', function(){
						if(this.status == 'ok'){
							if(jsconfig.sp == 'shopsite') {
								button.parentNode.parentNode.parentNode.removeChild(button.parentNode.parentNode);
							} else if(jsconfig.sp == 'myshop') {
								$( button ).removeAttr( 'onclick' ).attr({'title':'Товар в магазине'}).addClass( 'disabled' ).html( 'В магазине' );
								//button.parentNode.removeChild(button);
							}
						}else popupOpen('<div class="form_error">'+this.message+'</div>');
					}, data, false, {preloader:button});
				}
			}
		},
		addmoney: {
			send:function(form){
                var data = {
                    'amount': form.amount.value,
                    'shop': form.shop.value,
                    'description': form.description.value,
                    'currency': form.currency.value,
                    'success': form.success.value
                };
				main.post('/panel/user/addmoney/create_order.php', function(){
					if(this.status == 'ok'){
                        form.payment.value = this.payment;
                        form.sign.value = this.sign;
                        form.submit();
					}else popupOpen('<div class="form_error">'+this.message+'</div>');
				}, data, false, {preloader:form.button});
			}
		}
	},
	recommended: {
		buy:function(element){

			var t = confirm("Вы действительно желаете приобрести услугу? Средства будут списаны с вашего баланса.");
			if(t == true){
				main.post('/panel/glob/recommended/buy.php', function(){
					if(this.status == 'ok'){
						delCookie('recommended_type');
						setCookie('recommended_type', 'privileges');
						window.location.reload();
					}
				},{amount:$(element).data('amount')},true,{preloader:element,error:'errorBlock'});
			}
			
		},
		product_up:function(element){

			main.post('/panel/glob/recommended/product_up.php', function(){
				if(this.status == 'ok'){
					window.location.reload();
				}
			},{product:$('#product_up_name').val()},true,{preloader:element,error:'user_product_up_error'});
		},
	},
};
var merchant = {
	ready:function(){
		if(jsconfig.curr_page == 'orders'){
			panel.admin.order.list.get(0, false, true);
			if($('#date1').length){
				$('#date1').Zebra_DatePicker();
			}
			if($('#date1').length){
				$('#date2').Zebra_DatePicker();	
			}
		}
		panel.admin.bookkeeping.graph(true);
		if(jsconfig.sp == 'pay'){
			merchant.pay.ready();
		}else if(jsconfig.sp == 'operations'){
			merchant.operation.list.get(0, false, true);
			merchant.operation.ready();
		}else if(jsconfig.sp == 'sendform'){
			if (jsconfig.autoSubmitForm) {
                $('form').submit();
			} else {
                $('.qiwiLink').on('click', function() {
                	$(this).css( 'display', 'none');
                    merchant.checkpayment(jsconfig.paymentId);
				});
			}
		}else if(jsconfig.sp == 'adminshops'){
			merchant.admin.shopslist.get(0, false, true);
		}else if(jsconfig.sp == 'refresh'){
			setTimeout(function () {
            	document.location.reload();
         	}, 2500);
		}
		main.swfUploadReady(false, 'SWFuploadPictureDiv3');
	},
	checkpayment:function(paymentId) {
        setTimeout(function () {
            $.ajax({
                url: '/sf/merchant/check-payment/'+paymentId,
                success: function(data) {
					if (data.status == 'success' || data.status == 'cancel') {
                        window.location.href = jsconfig.returnUrl;
					} else {
                        merchant.checkpayment(paymentId);
					}
                },
				error: function() {
                    window.location.href = jsconfig.returnUrl;
				}
            });
        }, 5000);
	},
	createshop:function(button){
		common.post('/ajax/merchant/createshop/',{preloader:button,popuperror:true});
	},
	delshop:function(button,mshopId){
		common.post('/ajax/merchant/delshop/',{preloader:button,data:{mshopId:mshopId},popuperror:true});
	},
	savesettings:function(form){
		common.post('/ajax/merchant/savesettings/',{form:form});
	},
	pay:{
		ready:function(){

			var paypalfee = '';
			$( '[data-via]' ).on('click',function(){
				paypalfee = '';
				$( '[data-via]' ).removeClass('selected');
				$(this).addClass('selected');
				
				var via = $( this ).data( 'via' );

				$( '[data-select="via"] option' ).each( function() {
					if ( $( this ).data( 'option_via' ) == via ) {
						this.selected = true;

					} else {
						this.selected = false;

					}

				});
				$( 'select:not([multiple])' ).trigger( 'refresh' );

				var currency = 'руб.';
				/*if (via == 'wmz'){
					currency = 'WMZ';
				}
				else if (via == 'wmu'){
					currency = 'WMU';
				}
				else if (via == 'wme'){
					currency = 'WME';
				}
				else if (via == 'wmr'){
					currency = 'WMR';
				}else */if (via == 'paypal'){
					currency = 'USD';
					paypalfee = '+ комиссия Paypal';
				}else if (via == 'bitcoin' ){
					currency = 'EUR';
				}else if(via.split(' ')[0] == 'skrill'){
					currency = 'USD';
				}
				
				$( 'input[name=via]' ).val($(this).data('via'));
									
				$( '.merchantpay_pay' ).html( 'К оплате: <span class="amount">' + $( this ).data( 'amount' ) + '</span> ' + currency );

				
				if( !jsconfig.mshopFee ){
					$('.feebox').css( 'display', $( this ).data('feeamount') == '0.00' ? 'none' : 'block' ).html( 'В том числе комиссия: <span class="feeamount">' + $( this).data('feeamount') + '</span> ' + currency +paypalfee);
				}

			});
			$( '[data-select="via"]' ).on( 'change', function(){
				paypalfee = '';
				var $data = $( '[data-select="via"] option:selected' ).data();
				

				$( '[data-via]' ).removeClass( 'selected' );
				$( '[data-via="' + $data.option_via + '"]' ).addClass( 'selected' );

				var via = $data.option_via;
				var currency = 'руб.';

				/*if ( via == 'wmz' ) {
					currency = 'WMZ';
				}
				else if ( via == 'wmu' ) {
					currency = 'WMU';
				}
				else if ( via == 'wme' ) {
					currency = 'WME';
				}
				else if ( via == 'wmr' ) {
					currency = 'WMR';
				}else */if (via == 'paypal'){
					currency = 'USD';
					paypalfee = '+ комиссия Paypal';
				}else if (via == 'bitcoin' ){
					currency = 'EUR';
				}else if(via.split(' ')[0] == 'skrill'){
					currency = 'USD';
				}

				$( 'input[name=via]' ).val( $data.option_via );

				$( '.merchantpay_pay' ).html( 'К оплате: <span class="amount">' + $data.option_amount + '</span> ' + currency );

				if( !jsconfig.mshopFee ){
					$('.feebox').css( 'display', $data.option_feeamount == '0.00' ? 'none' : 'block' ).html( 'В том числе комиссия: <span class="feeamount">' + $data.option_feeamount + '</span> ' + currency +paypalfee);

				}

			});
		}
	},
	operation:{
		list:{
			pagination: 0,
			get: function(list_number, method, newly){

				var data = {
					mshopId:jsconfig.mshopId,
					id: $('#id').val(),
					status: $('#status').val(),
					date1: $('#date1').val(),
					date2: $('#date2').val(),
					payed: $('#payed').val()
				}

				if(newly)this.pagination = new Pagination('merchant.operation.list', '/ajax/merchant/operationlist', 'list', 10);
				this.pagination.get(data, list_number, method);
			}
		},
		shownotif:function(link,paymentId){
			var notif = link.parentNode.parentNode.nextSibling.nextSibling;
			if(notif.style.display == 'table-row'){
				notif.style.display = 'none';
				link.innerHTML = '&#9660;';
			}else{
				notif.style.display = 'table-row';
				link.innerHTML = '&#9650;';
				var box = $(notif).find('td')[0];
				if(box.innerHTML == ''){
					common.post('/ajax/merchant/shownotif/',{preloader:box,data:{paymentId:paymentId},popuperror:true,success:function(data){
						box.innerHTML = data.content;
					}});
				}
			}
		},
		ready:function(){
			$('.chartPeriod div').each(function(i){$(this).on('click',function(){merchant.operation.charts(jsconfig.mshopId,i);});});
			this.charts(jsconfig.mshopId);

			$('#date1').Zebra_DatePicker();
			$('#date2').Zebra_DatePicker();
		},
		commaSeparateNumber:function(val){
		    while (/(\d+)(\d{3})/.test(val.toString())){
		      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+'.'+'$2');
		    }
		    return val;
  		},
		charts:function(mshopid, period){
			if (typeof(period)=="undefined"){period = 0;}
			$('.chartPeriod div').each(function(i){this.className = i == period ? 'btn btn-info btn-sm active' : 'btn btn-primary btn-sm'});
			var self = this;
			main.post('/ajax/sales/chartsMerchant/user/', function(){
				if(this.status == 'ok'){
					if(this.sales.dt.length == 0)$('#salesGraph').html('<div class="noData"><p class="head">Статистика продаж</p><p class="infotext">Для построения графика пока нет данных</p></div>');
					else{
						$('#salesGraph').highcharts({
							chart:{type:'column'},
							title:{text: 'Продажи'},
							exporting: {
								buttons: {
									contextButton: {
										enabled: false
									}
								}
							},
							xAxis: [{
								categories:this.sales.dt
							}],
							yAxis: [{
								min:0,
								allowDecimals: false,
								title:{text:''},
								labels:{style:{color: Highcharts.getOptions().colors[3]}}
							},{
								min:0,
								allowDecimals: false,
								title:{text:''},
								opposite: true,
								labels:{style:{color: Highcharts.getOptions().colors[0]}}
							}],
							series:[{
								yAxis: 1,
								xAxis: 0,
								name: 'Продажи',
								data:this.sales.sales
							},{
								yAxis: 0,
								xAxis: 0,
								color:Highcharts.getOptions().colors[3],
								type: 'spline',
								name: 'Прибыль',
								data: this.sales.profit,
								marker: {
									height:8,
									width:8,
									lineWidth: 2,
									lineColor: Highcharts.getOptions().colors[3],
									fillColor: 'white'
								}
							}]
						});
					}

					$('.highcharts-title').html('Продажи на общую сумму '+self.commaSeparateNumber(this.allProfit)+' руб.');
				}
			},{period:period, id:mshopid},true,{preloader:document.getElementById('salesCharts'),cancel:'chartPeriod'});
		}

	},
	tochecking:function(button,mshopId){
		common.post('/ajax/merchant/tochecking/',{button:button,data:{mshopId:mshopId},popuperror:true,success:function(data){
			$(button).remove();
		}});
	},
	admin:{
		shopslist:{
			pagination: 0,
			get: function(list_number, method, newly){

				var data = {
					login: $('#searchForm #login').val(),
					status: $('#searchForm #status').val()
				};

				if(newly)this.pagination = new Pagination('merchant.admin.shopslist', '/ajax/merchant/shopslist', 'list', 10);
				this.pagination.get(data, list_number, method);
			}
		},
		feeedit:function(form){
			common.post('/ajax/merchant/feeeditsave/',{form:form});
		},
		feeeditUser:function(form){
			common.post('/ajax/merchant/payMethodsUserSave/',{form:form});
		},
		acceptshop:function(button,mshopId, result){
			common.post('/ajax/merchant/acceptshop/',{button:button,data:{mshopId:mshopId, result:result},popuperror:true,success:function(data){
				$(button).parent().remove();
				if(result == '0'){
					$('#mshopStatus_'+mshopId).html('Отклонен');
				}else{
					$('#mshopStatus_'+mshopId).html('Принят');
				}
				
			}});
		},
		showshop:function(link,mshopId){
			common.post('/ajax/merchant/showshop/',{preloader:link.parentNode,data:{mshopId:mshopId},popuperror:true,success:function(data){
				common.popup.open(data.content, false, data.shopName);
			}});
		}
	}
};

var user_recommended = {
	type_ad: 'text',
	//type_ad: 0,
	ready: function(){
		user_recommended.count_after_paid_graphic_ad();
		user_recommended.list.get(0, false, true);
		user_recommended.product_up_list.get(0, false, true);

		var module = getCookie('recommended_type');
		if(location.hash == '#privileges'){
			module = 'privileges';
		}
		if(module != 'undefined'){
			user_recommended.change_type(module);
		}

		var priv_type = $('#current_priv').data('type');
		if(priv_type){
			var priv_date = $('#current_priv').data('until');
			if(priv_date){
				priv_date = priv_date.split(' ')[0];
			}
			var priv_element = $('#priv_'+priv_type);
			$(priv_element).find('>div').css('opacity', '0.5');
			$(priv_element).append('<p style="font-size:20px;margin-top: 10px;font-weight: bold;color: rgb(255, 73, 63);">Тариф доступен до</p><p style="font-size:20px;font-weight: bold;">'+priv_date+'</p></div>');
		}

	},
	change_type_ad: function(type){
		$( '[data-recommended-btn]' ).removeClass( 'active' );
		$( '[data-recommended-content]' ).removeClass( 'active' );
		$( '[data-recommended-btn="' + type + '"]' ).addClass( 'active' );
		$( '[data-recommended-content="' + type + '"]' ).addClass( 'active' );
		user_recommended.type_ad = type;
/*
		if(type == 'graphic')var type_param = ['graphic', '#F4F4F3', '#CCCCFF', 'block', 'none'];
		else var type_param = ['text', '#CCCCFF', '#F4F4F3', 'none', 'block'];
		user_recommended.type_ad = type_param[0];
		document.getElementById('user_recommended_text_button').style.background = type_param[1];
		document.getElementById('user_recommended_graphic_button').style.background = type_param[2];
		document.getElementById('user_recommended_graphic_div').style.display = type_param[3];
		document.getElementById('user_recommended_text_div').style.display = type_param[4];
		document.getElementById('user_recommended_graphic_info').style.display = type_param[3];
		document.getElementById('user_recommended_text_info').style.display = type_param[4];
		document.getElementById('user_recommended_param_div').style.display = 'block';
		document.getElementById('user_recommended_submit_div').style.display = 'block';
*/
	},
	change_type: function(type){
		$( '[data-recommended-type-btn]' ).removeClass( 'active' );
		$( '[data-recommended-type-btn="' + type + '"]' ).addClass( 'active' );

		$( '[data-type]' ).removeClass( 'active' ).hide();
		$( '[data-type="' + type + '"]' ).addClass( 'active' ).show();
		
		delCookie('recommended_type');
		setCookie('recommended_type', type);
		
		location.hash = type;
	},

	change_duration_placement_graphic_ad: function(method){
		var field = document.getElementById('user_recommended_order_form').duration_placement_graphic_field_ad;
		var price_day = document.getElementById('user_recommended_price_day_graphic_ad');
		var amount = document.getElementById('user_recommended_amount_graphic_ad');
		switch(method){
			case 'plus':
				field.value = field.value - 0 + 1;
				break
			case 'minus':
				if(field.value-0 == 1)break
				field.value = field.value - 1;
				break
			case 'input':
				if(field.value-0 < 1 || isNaN(field.value-0))field.value = 1;
				break
		}
		amount.innerHTML = ((price_day.innerHTML-0) * (field.value-0)).toFixed(2);
		user_recommended.count_after_paid_graphic_ad();
	},
	count_after_paid_graphic_ad: function(){
		if(document.getElementById('user_recommended_wmrbal_graphic_ad')){
			var count = (document.getElementById('user_recommended_wmrbal_graphic_ad').innerHTML - document.getElementById('user_recommended_amount_graphic_ad').innerHTML).toFixed(2);
			if(count < 0)document.getElementById('user_recommended_after_paid_graphic_ad').style.color = 'red';
			else document.getElementById('user_recommended_after_paid_graphic_ad').style.color = '#666666';
			document.getElementById('user_recommended_after_paid_graphic_ad').innerHTML = count;
		}
	},
	change_price_click_text_ad: function(){
		var amount_click_min = document.getElementById('user_recommended_amount_click_min_text_ad');
		var amount_click = document.getElementById('user_recommended_order_form').amount_click_text_ad;
		if(amount_click.value-0 < amount_click_min.innerHTML-0 || isNaN(amount_click.value-0))amount_click.value = amount_click_min.innerHTML;
		amount_click.value = (amount_click.value-0).toFixed(2);
	},
	refresh_wmrbal: function(icon){
		var data = {};
		main.post('/panel/user/recommended/refresh_wmrbal.php', function(){
				if(this.status == 'ok'){
					var wmrbal = (this.wmrbal-0).toFixed(2);
					document.getElementById('user_recommended_wmrbal_graphic_ad').innerHTML = wmrbal;
					document.getElementById('user_recommended_wmrbal_text_ad').innerHTML = wmrbal;
					user_recommended.count_after_paid_graphic_ad();
				}else popupOpen('<div class="form_error">'+this.message+'</div>');
		}, data, false, {preloader:icon.parentNode});
	},
	create_ad: function(form){
		var data = {
			type: user_recommended.type_ad,
			product_id: form.product.value,
			duration_placement_graphic_field_ad: form.duration_placement_graphic_field_ad.value,
			amount_click_text_ad: form.amount_click_text_ad.value
		};
		main.post('/panel/user/recommended/create_ad.php', function(){
			if(this.status == 'ok'){
				document.getElementById('user_recommended_success_add_ad').style.display = 'block';
				document.getElementById('user_recommended_order_form').style.display = 'none';
				user_recommended.list.get(0, false, true);
			}
		}, data, false, {preloader:form.button,error:'user_recommended_order_form_error'});
	},
	list: {
		pagination: 0,
		get: function(list_number, method, newly){
			if(newly)this.pagination = new Pagination('user_recommended.list', '/panel/user/recommended/getlist_ad.php', 'user_recommended_list_ad_listing', 25);
			var data = {};
			this.pagination.get(data, list_number, method);	
		}
	},
	stop_ad: function(ad_id, icon){
		if(!confirm('Вы уверены, что хотите закрыть объявление?'))return false;
		var data = {
			ad_id:ad_id
		};
		main.post('/panel/user/recommended/delete_ad.php', function(){
			if(this.status == 'ok')user_recommended.list.get(0, false, true);
			else popupOpen('<div class="form_error">'+this.message+'</div>');
		}, data, false, {preloader:icon.parentNode});
	},
	show_graphic_recommended: function(){
		if(document.getElementById("recommended_graphic")){
			var data = {
				cat:jsconfig.category ? jsconfig.category.id : 0
			};
			main.post('/modules/recommended/get_list_graphic.php', function(){
				if(this.status == 'ok'){
					document.getElementById('recommended_graphic').innerHTML = this.content;
					user_recommended.block.show('graphic', this.showing);
					$( '[data-toggle="tooltip"]' ).tooltip({container: 'body', html: true});
				}else popupOpen('<div class="form_error">'+this.message+'</div>');
			}, data, false, {preloader:document.getElementById('recommended_graphic')});
		}
	},
	text_list: {
		pagination: 0,
		get: function(list_number, method, newly){
			document.getElementById('recommended_text').style.display = 'block';
			if(newly)this.pagination = new Pagination('user_recommended.text_list', '/modules/recommended/get_list_text.php', 'recommended_text', 24);
			var data = {method: method, cat:jsconfig.category ? jsconfig.category.id : 0, recommended: true};
			this.pagination.get(data, list_number, method);
		}
	},
	block: {
		text: false,
		graphic: false,
		show: function(block, exist){
			if(block == 'text'){
				this.text = exist == 0 ? false : true;
				document.getElementById('recommended_text').style.display = this.text ? 'block' : 'none';
			}
			if(block == 'graphic')this.graphic = exist;
			document.getElementById('recommended_index').style.display = this.text || this.graphic ? 'block' : 'none';
		}
	},
	show_recommended: function(){
		this.show_graphic_recommended();
		this.text_list.get(0, false, true);
	},
	product_up_list: {
		pagination: 0,
		get: function(list_number, method, newly){
			if(newly)this.pagination = new Pagination('user_recommended.product_up_list', '/modules/recommended/product_up_list.php', 'user_product_up_list', 10);
			var data = {};
			this.pagination.get(data, list_number, method);
		}
	},
};
function showSpoiler(e, save, type){

	if($(e).parent().find('>.spoiler:visible').length){
		$(e).parent().find('>.spoiler').hide();
	}else{
		$(e).parent().find('>.spoiler').show();
	}

	save ? setCookie('spoiler_'+type, $(e).parent().find('>.spoiler:visible').length) : '';
}
function showSpoiler2(e, save, type){

	if($(e).parents('.panel').find('.spoiler:visible').length){
		$(e).parents('.panel').find('.spoiler').hide();
	}else{
		$(e).parents('.panel').find('.spoiler').show();
	}

	save ? setCookie('spoiler_'+type, $(e).parents('.panel').find('.spoiler:visible').length) : '';
}
function currentList(v){
	setCookie('curr', v);
	switch(jsconfig.module){
		case "showproduct":
			location.reload();
		   break
		case "sellershow":
		   module.seller.productlist.get(0, false, true);
		   break
		case "panel":
		   if(jsconfig.sp == 'myshop')panel.user.myshop.list.get(0, false, true);
		   break
		case "main":
			location.reload();
			break
		default:
		   if(document.getElementById('productList'))module.main.productlist.get(0, false, true);
		   user_recommended.show_recommended();
		   break
	}
}
function sortList(e){
	switch(jsconfig.module){
		case "sellershow":
		   module.seller.productlist.get(0, false, true);
		   break
		case "panel":
			if(jsconfig.sp == 'myshop'){
				panel.user.myshop.list.get(0, false, true);
			}
		   break
		default:
		   if(document.getElementById('productList'))module.main.productlist.get(0, false, true);
		   break
	}
}

function isEmpty(str) {
	if(str){
		for (var i = 0; i < str.length; i++){
      		if (" " != str.charAt(i)){
      			 return false;
      			}else{
      				 return true;
      			}
        }
	}else{
		return false;
	}
   
}

function popupOpen(content, noindent, title){
	title = title || 'Сообщение';
	if(!(noindent = noindent || false))content = '<div class="RegAuto_F_Title">'+title+'</div><div class="form_popup">'+content+'</div>';
	document.getElementById("popupContent").innerHTML = content;
	document.getElementById('popup').style.display = 'block';
}
function popupClose(){
	document.getElementById("popupContent").innerHTML = "";
	document.getElementById('popup').style.display = 'none';
}
var searchTimer;
$(function(){

	
	$("#search").keyup(function(){

		$('#page').loadingIndicator();
		myloader = $('#page').data("loadingIndicator");

		if($('#search').val() == ""){
			$('#recommended_index').show();
		}else{
			$('#recommended_index').hide();
		}

		if(typeof myloader !== 'undefined'){
			myloader.show();
		}
		
		clearTimeout(searchTimer);
		searchTimer = setTimeout(function(){
			if(!document.getElementById("productList") || jsconfig.module == 'category')document.getElementById("SearshForm").submit();
			else module.main.productlist.get(0, 'gotoanchor', true);
		}, 1200);
	});
});
function searchFormReset(){
	document.getElementById("search").value = "";
}

var common = {
	forwarding: function(text, url){
		popupOpen(text, false, 'Переадресация');
		setTimeout(function(){window.location.href = url;}, 2000)
	},
	/*popup:{
		opened:false,
		overlayfixed:function(){
			var a=$('#popup').height()+100;
			var b=$(window).height();
			$('#overlay').css('height',a<b?b:a);
			if(this.opened){
				var winH = $(window).height(), winW = $(window).width();
				$('#popupbox').show();
				var top = winH / 2 - $('#popup').height() / 2;
				top = top < 20 ? 20 : top;
				$('#popup').css('margin-top', top);
			}
		},
		open:function(html){
			this.opened = true;
			$('#popup').html(html);
			$('body').css('overflow', 'hidden');
			common.popupd.overlayfixed();
		},
		close:function(){
			this.opened = false;
			$('#popupbox').hide();
			$('#popup').html('');
			$('body').css('overflow', '');
		}
	},*/
	popup:{
		open:function(content,noindent,title,form){
			var title = title || 'Сообщение';

			if(typeof form !== 'undefined'){
				if($(form).data('type') == 'mod'){
					$('#popup>div.popup').css('top', '10%');
					$('#popup>div.popup').css('left', '40%');
					$('#popup>div.popup').css('width', '610');
				}
			}

			if(!(noindent = noindent || false))content = '<div class="RegAuto_F_Title">'+title+'</div><div class="form_popup">'+content+'</div>';
			document.getElementById("popupContent").innerHTML = content;
			document.getElementById('popup').style.display = 'block';



		},
		close:function(){
			document.getElementById('popupContent').innerHTML = '';
			document.getElementById('popup').style.display = 'none';
		}
	},
	preloader:{
		htmls:{},
		set:function(obj,cancel){ 
			var html = obj.innerHTML;
			obj.innerHTML = '\
				<div class="windows8">\
					<div class="windows8balls">\
						<div class="wBall" id="wBall_1">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_2">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_3">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_4">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_5">\
							<div class="wInnerBall"></div>\
						</div>\
					</div>\
					<div class="windows8text">'+html+'</div>\
				</div>\
			';
			this.htmls[cancel] = html;
			return html;
		}
	},
	poststack:{},
	post:function(url, obj){
		obj.test = true;
		if(obj.form){
			obj.button = obj.form.button;
			obj.info = $(obj.form).find('[data-name=info]').get(0);
			var formdata = common.formdata(obj.form);
			obj.data = obj.data || {empty:true};
			for(var v in formdata)obj.data[v] = formdata[v];
		}else{
			if(!obj.data)obj.data = {empty:true};
		}
		obj.data.token = jsconfig.token;
		if(obj.backurl)obj.data.backurl = document.location.href;
		if(obj.preloader && obj.preloader.tagName == 'BUTTON')obj.button = obj.preloader;
		if(obj.button){
			obj.preloader = obj.button;
			obj.button.disabled = true;
		}
		var cancel = obj.cancel || 'last';
		if(cancel != 'last' && this.poststack[cancel]){
			this.poststack[cancel].abort();
			if(this.preloader.htmls[cancel])obj.preloader.innerHTML = this.preloader.htmls[cancel];
			this.preloader.htmls[cancel] = false;
		}
		if(obj.preloader)obj.preloader_html = this.preloader.set(obj.preloader,cancel);
		if(obj.info){
			obj.info.className = '';
			obj.info.innerHTML = '';
		}
		var data = JSON.stringify(obj.data);
		data = "data="+encodeURIComponent(data);
		this.poststack[cancel] = $.ajax({url:url, type: 'POST', cache: false, dataType: 'json', data: data, success: function(data,textStatus,request){
			console.log(data)
			if(obj.cancel)common.preloader.htmls[cancel] = false;
			if(obj.button)obj.button.disabled = false;
			if(obj.preloader && !(obj.nostop && data.status == 'ok'))obj.preloader.innerHTML = obj.preloader_html;
			var info = '';
			if(data.messages || data.message){
				if(data.messages)for(var i=0;i<data.messages.length;i++)info += '<div>'+data.messages[i]+'</div>';
				else if(data.message)info = data.message;
			}
			
			if(data.status != 'ok'){
				if(info != ''){
					if(obj.info){
						obj.info.className = 'infoError';
						obj.info.innerHTML = info;
					}else if(obj.popuperror)common.popup.open('<div class="popupError"><div class="form_error">'+info+'</div></div>', false, 'Ошибка');
				}
				if(obj.funcerror)obj.funcerror(data);
			}else{
				if(obj.info && info != ''){
					obj.info.className = 'infoSuccess';
					obj.info.innerHTML = info;
				}
				data.obj = obj;
				if(data.forwarding)common.forwarding(data.message,data.forwarding);
				if(obj.success)obj.success(data);
			}
		},
		error:function(data){
			var redirect = data.getResponseHeader('Refresh');
			if(redirect){
				redirect = redirect.split('=');
				window.location.href = redirect[1];
			}
			if(data.status == 503)location.reload();
			if(obj.button)obj.button.disabled = false;
			if(obj.preloader)obj.preloader.innerHTML = obj.preloader_html;
			if(obj.funcerror)obj.funcerror(data);
		}});
	},
	upload:function(url,file,obj,func){
		var aobj = obj.aobj || {};
		obj.test = true;
		obj.data = obj.data || {};
		obj.data.token = jsconfig.token;
		var data = JSON.stringify(obj.data);
		var form = new FormData();
		form.append('file',file);
		form.append('data',data);
		$.ajax({url:url,type:'POST',dataType:'json',data:form,cache:false,contentType:false,processData:false,
			xhr:function(){
				var myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload)
					myXhr.upload.addEventListener('progress',function(data){if(!obj.box)return;obj.box.innerHTML='<span class="progress">'+Math.ceil(data.loaded*100/data.total)+'%</span>'}, false);
				return myXhr;
			},
			success:function(result){result.aobj=aobj;func(result)},
			error:function(result){result.aobj=aobj;func(result)}
		});
	},
	formdata:function(form){
		var data = {};
		$(form).find('input').each(function(){
			if(!form[this.name])return;
			if(this.type == 'checkbox')data[this.name] = this.checked;
			else if(this.type == 'radio'){
				if(data[this.name])return;
				for(var i=0;i<form[this.name].length;i++)if(form[this.name][i].checked == true)data[this.name] = i+1;
			}else data[this.name] = this.value;
		});
		$(form).find('select').each(function(){
			data[this.name] = this[this.selectedIndex].value;
		});
		$(form).find('textarea').each(function(){
			data[this.name] = this.value;
		});
		return data;
	},
	cookie:{
		set:function(name, value, options){
			options = options || {expires:60*60*24*30*12};
			var expires = options.expires;
			if (typeof expires == "number" && expires) {
				var d = new Date();
				d.setTime(d.getTime() + expires * 1000);
				expires = options.expires = d;
			}
			if(expires && expires.toUTCString)options.expires = expires.toUTCString();
			value = encodeURIComponent(value);
			var updatedCookie = name + "=" + value;
			for(var propName in options){
				updatedCookie += "; " + propName;
				var propValue = options[propName];
				if(propValue !== true)updatedCookie += "=" + propValue;
			}
			document.cookie = updatedCookie;
		},
		get:function(name){
			var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
			return matches ? decodeURIComponent(matches[1]) : undefined;
		}
	}
};

var req;
function ajaxxx(handlerPath, parameters){
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
		alert('Ошибка');
	}
		return req.responseText;
}
var main = {
    sendMetric:function(id, relateId, callback) {
		$.get('/sf/metric/'+id+'/'+relateId, callback);
    },
	forwarding: function(text, url){
		popupOpen(text, false, 'Переадресация');
		setTimeout(function(){window.location.href = url;}, 2000)
	},
	preloader:{
		htmls:{},
		set:function(obj,cancel){
			var html = obj.innerHTML;
			obj.innerHTML = '\
				<div class="windows8">\
					<div class="windows8balls">\
						<div class="wBall" id="wBall_1">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_2">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_3">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_4">\
							<div class="wInnerBall"></div>\
						</div>\
						<div class="wBall" id="wBall_5">\
							<div class="wInnerBall"></div>\
						</div>\
					</div>\
					<div class="windows8text">'+html+'</div>\
				</div>\
			';
			this.htmls[cancel] = html;
			return html;
		}
	},
	loading: '<img src="/stylisation/images/preloader.gif" alt="Загрузка...">',
	poststack:{},
	post: function(url, func, data, test, aobj){
		test = test || false;
		aobj = aobj || false;
		if(typeof(data) != 'object') data = JSON.parse(data);
		data.token = jsconfig.token;
		console.log(data);
		if(aobj && aobj.error)document.getElementById(aobj.error).innerHTML = '';
		var cancel = aobj.cancel || 'last';
		if(cancel != 'last' && this.poststack[cancel]){
			this.poststack[cancel].abort();
			if(this.preloader.htmls[cancel])aobj.preloader.innerHTML = this.preloader.htmls[cancel];
			this.preloader.htmls[cancel] = false;
		}
		if(aobj && aobj.preloader){
			aobj.preloader.disabled = true;
			aobj.preloader_html = this.preloader.set(aobj.preloader,cancel);
		}
		
		
		data = "data="+encodeURIComponent(JSON.stringify(data));
		console.log(data);
		this.poststack[cancel] = $.ajax({url:url, type: 'POST', cache: false, dataType: 'json',data: data, success:function(data,textStatus,request){
			
			if(aobj.cancel)main.preloader.htmls[cancel] = false;
			data.aobj = aobj;
			if(aobj && aobj.preloader){
				aobj.preloader.disabled = false;
				aobj.preloader.innerHTML = aobj.preloader_html;
			}
			if(aobj && aobj.error && data.status == 'error'){
				var error = '';
				if(data.list)for(var i=0;i<data.list.length;i++)error += '<div>'+data.list[i]+'</div>';
				else error = data.message;
				document.getElementById(aobj.error).className = 'form_error';
				document.getElementById(aobj.error).innerHTML = error;
			}
			if(aobj && aobj.popupError && data.status == 'error'){
				var error = '';
				if(data.list)for(var i=0;i<data.list.length;i++)error += '<div>'+data.list[i]+'</div>';
				else error = data.message;
				popupOpen('<div class="popupError"><div class="form_error">'+error+'</div></div>', false, 'Ошибка');
			}
			if(aobj && aobj.error && data.status == 'ok' && data.message){
				document.getElementById(aobj.error).className = 'form_success';
				document.getElementById(aobj.error).innerHTML = data.message;
			}
			func.call(data);

			if($('#panel_admin_order_list').length || $('.mainPageTooltip').length || $('#panel_user_cabinet_list').length){
				$( '[data-toggle="tooltip"]' ).tooltip({container: 'body', html: true});
			}
	
		},
		error:function(data){
			// console.log(data.responseText);
		}
            });
		
		/*try {this.poststack[cancel] = new ActiveXObject("Msxml2.XMLHTTP");} 
		catch (e) {
			try {this.poststack[cancel] = new ActiveXObject("Microsoft.XMLHTTP");} 
			catch (E) {this.poststack[cancel] = false;}
		}
		if(!this.poststack[cancel] && typeof XMLHttpRequest!='undefined')this.poststack[cancel] = new XMLHttpRequest();
		this.poststack[cancel].open('POST', url, true);
		this.poststack[cancel].setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		this.poststack[cancel].onreadystatechange = function() {
			if(aobj.cancel)common.preloader.htmls[cancel] = false;
			if(main.poststack[cancel].readyState == 4 && main.poststack[cancel].status == 200){
				if(test)console.log(main.poststack[cancel].responseText);
				try{var result = JSON.parse(main.poststack[cancel].responseText);}
				catch(e){
					if(test)func.call(false);
					else alert('Ошибка');
					return false;
				}
				result.aobj = aobj;
				if(aobj && aobj.preloader){
					aobj.preloader.disabled = false;
					aobj.preloader.innerHTML = aobj.preloader_html;
				}
				if(aobj && aobj.error && result.status == 'error'){
					var error = '';
					if(result.list)for(var i=0;i<result.list.length;i++)error += '<div>'+result.list[i]+'</div>';
					else error = result.message;
					document.getElementById(aobj.error).className = 'form_error';
					document.getElementById(aobj.error).innerHTML = error;
				}
				if(aobj && aobj.popupError && result.status == 'error'){
					var error = '';
					if(result.list)for(var i=0;i<result.list.length;i++)error += '<div>'+result.list[i]+'</div>';
					else error = result.message;
					popupOpen('<div class="popupError"><div class="form_error">'+error+'</div></div>', false, 'Ошибка');
				}
				if(aobj && aobj.error && result.status == 'ok' && result.message){
					document.getElementById(aobj.error).className = 'form_success';
					document.getElementById(aobj.error).innerHTML = result.message;
				}
				func.call(result);
			}
		};
		this.poststack[cancel].send('data='+data);*/
	},
	formdata:function(form){
		var data = {};
		$(form).find('input').each(function(){
			if(!form[this.name])return;
			if(this.type == 'checkbox')data[this.name] = this.checked;
			else if(this.type == 'radio'){
				if(data[this.name])return;
				for(var i=0;i<form[this.name].length;i++)if(form[this.name][i].checked == true)data[this.name] = i+1;
			}else data[this.name] = this.value;
		});
		$(form).find('select').each(function(){
			data[this.name] = this[this.selectedIndex].value;
		});
		$(form).find('textarea').each(function(){
			data[this.name] = this.value;
		});
		return data;
	},
	upload: function(file, path, div, func, test){
		var http = new XMLHttpRequest();
		if (http.upload && http.upload.addEventListener) {
			http.upload.addEventListener('progress',
				function(e) {
					if (e.lengthComputable) {
						document.getElementById(div).innerHTML = Math.round(e.loaded * 100 / e.total)+'%';
					}
				},false);
			http.onreadystatechange = function () {
				if (this.readyState == 4) {
					if(this.status == 200) { 
						// if(test = test || false)console.log(this.response);
						try{var result = JSON.parse(this.response);}catch (e){func.call(false); return false;}
						result.div = div;
						func.call(result);
					}else document.getElementById(div).innerHTML = 'Ошибка';
				}
			};
			http.upload.addEventListener('error',function(e) {document.getElementById(div).innerHTML = 'Ошибка';});
		}
		var form = new FormData();
		form.append('path', '/');
		form.append('file', file);
		http.open('POST', path);
		http.send(form);		
	},
	fullviewimage: function(img){
		popupOpen('<img src="'+img+'">', true);
	},
	tab: {
		getnum: function(div){
			var els = document.getElementById(div).getElementsByTagName('button');
			for(var tab in els)if(els[tab].disabled)return tab;
		},
		change: function(tab, func){
			els = tab.parentNode.getElementsByTagName('button');
			for(var tab_num in els)els[tab_num].disabled = false;
			tab.disabled = true;
			func.call();
		}
	},
	swfUploadReady: function(showdel, element){
		if(element){
			var uploadDiv = document.getElementById(element);
		}else{
			var uploadDiv = document.getElementById('SWFuploadPictureDiv');
		}

		if($(uploadDiv).length == 0){
			return false;	
		} 

		var p = uploadDiv.innerHTML.split(':');
		uploadPicture = p[0];

		if(p[0] == 0){
				uploadDiv.innerHTML = '<div id="uploadPictureButton"></div><div id="SWFuploadPictureMessage"></div>';
				newSWFUploadPicture('avatar');
		}else{

			if(typeof p[1] === 'undefined'){
				uploadDiv.innerHTML = $('#'+element).data('avatar');
			}else{
				uploadDiv.innerHTML = '<img src="'+p[1]+'"/>';
			}

			var deleteImg = document.createElement('div');
			if(showdel){
				deleteImg.innerHTML = '<div class="btn btn-sm btn-danger">Удалить</div>';
			}
			deleteImg.onclick = function(){
				uploadDiv.removeChild(deleteImg);
				uploadDiv.innerHTML = '<div id="uploadPictureButton"></div><div id="SWFuploadPictureMessage"></div>';
				newSWFUploadPicture('avatar');
			}

			uploadDiv.appendChild(deleteImg);
		}
	}
};
function setCookie(a, b, c){
	if(typeof c === 'undefined'){
		c = '/';
	}
	document.cookie = a + "=" + b + "; expires="+c+";path=/";
}
function getCookie(a){
    var b = a + "=";
    var d = document.cookie.split(';');
    for (var i = 0; i < d.length; i++) {
        var c = d[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(b) == 0) return c.substring(b.length, c.length)
    }
    return "undefined";
}
function delCookie(a){setCookie(a, "", -1)}
function groupChange(id, num){
	addProductGroup = 0;
    function closeGroupe(num){
		if(num == 0){
			document.getElementById("group1").value = 0;
			document.getElementById("group2").value = 0;
			document.getElementById("group1").style.display = "none";
			document.getElementById("group2").style.display = "none";
		}
		if(num == 1){
			document.getElementById("group2").value = 0;
			document.getElementById("group2").style.display = "none";
		}
	}
	if(id == 0){closeGroupe(num); return;}
	if(num == 0)closeGroupe(1);
	var rez = ajaxxx("/panel/user/productadd/subGroupGet.php","&id=" + id);
	rez = JSON.parse(rez);
	if(rez.option == 1)document.getElementById("group2").style.display = "none";
	if(rez.option != 0){
		document.getElementById("group"+rez.numSub).innerHTML = rez.option;
		document.getElementById("group"+rez.numSub).style.display = "inline-table";
	}else{
		addProductGroup = rez.numSub;
		closeGroupe(num);
	}
	$( 'select:not([multiple])' ).trigger( 'refresh' );;
}
function strBaseTo(a) {
   a = a.replace(/\&/gi, '{.-amp;-.}');
   a = a.replace(/\:/gi, '{.-colon;-.}');
   a = a.replace(/\+/gi, '{.-plus;-.}');			
   a = a.replace(/\"/gi, '{.-quote;-.}');
   a = a.replace(/\'/gi, '{.-onequote;-.}');
   a = a.replace(/\t/gi, '{.-tab;-.}');
   return a;
}

function checkRegex(value, modus) {
   var checkRegexReg = "";
   switch (modus) {
   	  case "int":
   	     checkRegexReg = /^[0-9]{1,8}$/i;	  
   	  break
   	  case "login":
   	     checkRegexReg = /^[a-z0-9_-]{3,16}$/i;	  
   	  break
	  case "fio":
	     checkRegexReg = /^[а-яёa-z\s]+$/i;	 
   	  break	  
	  case "name":
	     checkRegexReg = /^[а-яёa-z0-9\s]+$/i;	 
   	  break	  
	  case "group":
	     checkRegexReg = /^[а-яёa-z0-9\s]+$/i;	 
   	  break	  
	  case "price":      
	     //checkRegexReg = /^[0-9]+(,[0-9]{2})?$/;	 
	     checkRegexReg = /^\b\d+(\.\d{1,2})?$/;	 
   	  break	  
	  case "textarea":
	     checkRegexReg = /^[а-яёa-z0-9\s\u0021\u003F\u002C\u002E\u003A\u0022\u0028\u0029\u002D\u005F]+$/i;	//!?,.:"()-_ 
   	  break	  
	  case "password":
	     checkRegexReg = /^[a-z0-9_-]{6,18}$/i;	 
   	  break
	  case "email":
   	     checkRegexReg = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
      break
   }
   if (!value.match(checkRegexReg)) return 1;
}

function display(d, t){
	d = document.getElementById(d);
	if(d.style.display == 'none'){d.style.display = 'block'; t.innerHTML = "Скрыть"} 
	else {d.style.display = 'none'; t.innerHTML = "Показать"}
}
var dm = 'none';
$(document).on('click', '[data-display_dm]', function () {

	var id = $( this ).data( 'display_dm' );
	if ( $( '#' + id ).css( 'display' ) == 'none' ) {

		if ( dm != 'none' ) {
			$( '#' + dm ).css({
				'display' : 'none'
			});
			$( '[data-display_dm="' + dm + '"]' ).html( 'Показать' );
		}

		$( '#' + id ).css({
			'display' : 'block'
		});
		$( this ).html( 'Скрыть' );
		dm = id;
	} else {
		$( '#' + id ).css({
			'display' : 'none'
		});
		$( this ).html( 'Показать' );
		dm = 'none';
	}


});





function alertObj(obj) {
	var str = "";
	for(k in obj) {
		str += k+": "+ obj[k]+"\r\n";
	}
	return(str);
}
