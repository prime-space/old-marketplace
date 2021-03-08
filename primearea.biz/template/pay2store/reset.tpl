<div class="RegAuto_F">
	<div class="RegAuto_F_Title">Введите новый пароль</div>
	<form onsubmit="module.user.reset.action(this); return false;">
		<table class="table table-striped table_page">
			<tr>
				<td style="width: 400px;" class="font_weight_700 padding_10 vertical_align text_align_r">новый пароль:</td>
				<td class="padding_10 vertical_align"><input type="text" name="password"></td>
			</tr>
			<tr>
				<td style="width: 400px;" class="font_weight_700 padding_10 vertical_align text_align_r">повторите:</td>
				<td class="padding_10 vertical_align"><input type="text" name="repassword"></td>
			</tr>
			<tr>		
				<input value="{user_id}" type="hidden" name="user_id">
				<input value="{key}" type="hidden" name="key">
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
					<button class="btn btn-sm btn-success">Отправить</button>
				</td>
			</tr>
			
		</table>
	</form>
</div>
<div id="loginError"></div>