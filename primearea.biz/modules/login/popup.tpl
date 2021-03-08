<div class="RegAuto_F">
	<div class="RegAuto_F_Title">Вход</div>
	<form onsubmit="module.user.signin.action(this); return false;">
		<table class="table table-striped table_page">
			<tr>
				<td style="width: 146px;" class="font_weight_700 padding_10 vertical_align text_align_r">Логин:</td>
				<td class="padding_10 vertical_align"><input type="text" name="login"></td>
			</tr>	   
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">Пароль:</td>
				<td class="padding_10 vertical_align"><input type="password" name="pass"></td>
			</tr>   
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">Код с картинки:</td>
				<td class="padding_10 vertical_align">
					<input type="text" name="captcha">
					<div class="div_captcha" style="display:inline-block;vertical-align: middle;"><img id="loginCaptcha" src="/modules/captcha/captcha.php"></div>
				</td>
			</tr> 
			<tr>
				<td colspan="2" class="padding_10 vertical_align text_align_r">
					<button class="btn btn-sm btn-success">Войти</button>
				</td>
			</tr>
		</table>
	</form>	
</div>
<div id="loginError"></div>