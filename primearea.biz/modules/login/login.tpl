 <script>
		var show;
		function hidetxt(type){
			 param=document.getElementById(type);
			 if(param.style.display == "none") {
			 if(show) show.style.display = "none";
			 param.style.display = "block";
			 show = param;
			 }
		else param.style.display = "none"
		}
		</script>
<a onclick="hidetxt('divmenubar'); return false;" href="#" rel="nofollow" class="nologdect">
		<div class="list1">
		<i class="sprite sprite_login"></i> <span>Продавцам</span>
		</div></a>
		<div style="display:none;float:right;position:relative;" id="divmenubar">
			<div class="pipnewlog"></div>
			<div class="loginmainbl">
			<a href="/signin/" class="select-list"><i class="sprite sprite_login"></i> Вход</a>
			<a href="/signup/" class="select-list1"><i class="sprite sprite_reg"></i> Регистрация</a>
			</div>
		</div>
<!-- <a class="pa_head_c_btn btn btn-danger" href="/signup/"><i class="sprite sprite_reg"></i> Регистрация</a>
<a class="pa_head_c_btn btn btn-success" href="/signin/"><i class="sprite sprite_login"></i> Войти</a>
 onclick="module.user.signin.popup();" -->