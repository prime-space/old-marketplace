<div class="btn btn-tabs btn-default active" data-btn_tabs_id="1">реквизиты</div>
<div class="btn btn-tabs btn-default " data-btn_tabs_id="2">кошельки</div>
<div class="btn btn-tabs btn-default " data-btn_tabs_id="3">информирование</div>
<div class="btn btn-tabs btn-default " data-btn_tabs_id="4">2-аутентификация</div>
<!-- <div class="btn btn-tabs btn-avatarka" style="display:none" data-btn_tabs_id="3">аватарка</div> -->
<div class="clear"></div>
<br>
<form onsubmit="panel.user.cabinet.useredit(this, '{role}');return false;">
	<div class="btn_tabs_content active" data-btn_tabs_content="1">
		<table class="table table-striped table_page table_page_b table_page_input1">
			<tbody>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Продавец(псевдоним):</td>
					<td style="width: 190px;" class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetlogin">{login}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Идентификатор:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetId">{userId}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">ФИО контактного лица:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetfio">{fio}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Телефон:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetphone">{phone}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Skype:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetskype">{skype}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Сайт:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabineticq">{site}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">
					<div style="float: left;margin-top: 10px;">Email:</div> {email_activation}</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetemail">{email}</div></td>
				</tr>
				<tr data-user_cabinet="pass" style="display:none;">
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Пароль:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetpass"></div></td>
				</tr>
				<tr data-user_cabinet="pass" style="display:none;">
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Повторите пароль:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetpassr"></div></td>
				</tr>
			</tbody>
		</table>
		<button class="btn btn-small btn-success" style="margin-top: 10px;padding: 6px 0;width:90%;" name="button" data-form="user_cabine">Изменить</button>
	</div>

	<div class="btn_tabs_content" data-btn_tabs_content="2">
		<table class="table table-striped table_page table_page_b table_page_input1">
			<tbody>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">WM-идентификатор(WMID):</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetwm">{wm}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">WMZ-кошелек:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetwmz">{wmz}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">WMR-кошелек:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetwmr">{wmr}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">WME-кошелек:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetwme">{wme}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">WMU-кошелек:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetwmu">{wmu}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Yandex-кошелек:</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetyandex_purse">{yandex_purse}</div></td>
				</tr>
				<tr>
					<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">
						Qiwi-кошелек:<br>
						Укажите в формате +79123456789
					</td>
					<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetqiwi_purse">{qiwi_purse}</div></td>
				</tr>
			</tbody>
		</table>
		<button class="btn btn-small btn-success" style="margin-top: 10px;padding: 6px 0;width:90%;" name="button" data-form="user_cabine">Изменить</button>
	</div>

	<div id="panel_cabinet_useredit_info"></div>
</form>

<table class="btn_tabs_content sell-notif-sett" data-btn_tabs_content="3">
	<tr>
		<td class="sell-notif-sett__position">
			<i class="lnr lnr-envelope"></i>
			Email
		</td>
		<td class="sell-notif-sett__info">
			<span class="sell-notif-sett__head">Информирование об операциях</span>
			<br>
			<span>
				Уведомление о продаже товара и сообщение от покупателя приходят на {email}.
				<br>
				Это бесплатно.
			</span>
		</td>
		<td class="sell-notif-sett__action">
			<button class="btn btn-default" data-value="{emailInformingValue}" onclick="panel.user.cabinet.emailInforming(this);return false">{emailInformingButton}</button>
		</td>
	</tr>
</table>

<div class="btn_tabs_content twin-auth-settings" data-btn_tabs_content="4">
	Двухфакторная аутентификация повысит безопасность вашего аккаунта. При входе, дополнительно к паролю, будет запрашиваться специальный код, который генерирует ваш телефон.
	<br>
    {{IF:TWIN_AUTH}}
		1. Установите приложение Google Authenticator на свой телефон
		<br>
		2. Отсканируйте им QR-код или введите секрет вручную
		<br>
		3. Подтвердите включение двухфакторной аутентификации верификационным кодом и паролем
		<br>
		<div class="twin-auth-settings__qr"><img src="{googleQrUrl}"></div>
		<form onsubmit="panel.user.cabinet.twinAuthenticateSwitch(this);return false;">
			<table class="table table-striped table_page table_page_b">
				<tr>
					<td>Секрет</td>
					<td>{googleSecret}</td>
				</tr>
				<tr>
					<td>Код</td>
					<td><input type="text" name="code"></td>
				</tr>
				<tr>
					<td>Пароль</td>
					<td><input type="password" name="pass"></td>
				</tr>
			</table>
			<div id="panel_cabinet_twin_auth_info"></div>
			<button class="btn btn-default" name="button">Включить</button>
			<input type="hidden" name="secret" value="{googleSecret}">
			<input type="hidden" name="action" value="on">
		</form>
    {{ELSE:TWIN_AUTH}}
	<form onsubmit="panel.user.cabinet.twinAuthenticateSwitch(this);return false;">
		<table class="table table-striped table_page table_page_b">
			<tr>
				<td>Код</td>
				<td><input type="text" name="code"></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" name="pass"></td>
			</tr>
		</table>
		<div id="panel_cabinet_twin_auth_info"></div>
		<button class="btn btn-default" name="button">Выключить</button>
		<input type="hidden" name="secret">
		<input type="hidden" name="action" value="off">
	</form>
	{{ENDIF:TWIN_AUTH}}
</div>

<!--<table class="table table-striped table_page table_page_b table_page_input1 btn_tabs_content" data-btn_tabs_content="3">
	<tbody>
		<tr>
			<td class="font_weight_700 font_size_14 padding_10 text_align_r">Изображение:
				<div data-avatar='{avatar}' class="padding_10 vertical_align" id="SWFuploadPictureDiv">
				<div id="uploadPictureButton"></div>
				<div id="SWFuploadPictureMessage"></div>
			</div>
			</td>
		</tr>
	</tbody>
</table>-->
