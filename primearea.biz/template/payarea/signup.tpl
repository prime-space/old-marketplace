<div class="RegAuto_F" id="regDiv">
	<div class="RegAuto_F_Title">Регистрация</div>
	<form onsubmit="module.user.signup.action(this); return false;">
		<table class="table table-striped table_page">
			<tr>
				<td style="width:400px;" class="font_weight_700 padding_10 vertical_align text_align_r">Логин:</td>
				<td class="padding_10 vertical_align"><input type="text" name="login"></td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">Пароль:</td>
				<td class="padding_10 vertical_align"><input type="password" name="pass"></td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">Повторите пароль:</td>
				<td class="padding_10 vertical_align"><input type="password" name="passr"></td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">ФИО:</td>
				<td class="padding_10 vertical_align"><input type="text" name="fio"></td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">E-mail:</td>
				<td class="padding_10 vertical_align"><input type="text" name="email"></td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r">Код с картинки:</td>
				<td class="padding_10 vertical_align">
					<input type="text" name="captcha">
					<div class="div_captcha" style="display:inline-block;vertical-align: middle;"><img id="regcaptcha" src="/modules/captcha/captcha.php"></div>
				</td>
			</tr>
			<tr>
				<td class="font_weight_700 padding_10 vertical_align text_align_r"><a target="_blank" href="/sogl/">Правила</a>:</td>
				<td class="padding_10 vertical_align"><label><input type="checkbox" name="agreement"> Я ознакомлен с правилами<label></td>
			</tr>	
			<tr>
				<td colspan="2" class="padding_10 vertical_align text_align_r">
					<button name="button" class="btn btn-sm btn-success">Зарегистрироваться</button>
				</td>
			</tr>
		</table>
	</form>	
	<div id="regError" class="regError"></div>
</div>
