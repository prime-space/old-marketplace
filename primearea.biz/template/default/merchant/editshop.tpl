
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/merchant/admin/">Администрирование Мерчанта</a> » Магазин "{shopName}"</h3>
	</div>
	<div class="panel-body">

		<form method="post" action="">
			<table class="table table-striped table_page">
				<thead>
				<tr>
					<td class="text_align_c">Индивидуальные настройки</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="border-bottom: none" colspan="3" class="padding_10">
						<div id="admin_userUserInfo_settings">

							<table class="table table-striped table_page table_page_b table_page_input1 btn_tabs_content active" data-btn_tabs_content="1">
								<tbody>
								<tr>
									<td style="width: 355px" class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Время удержания средств:</td>
									<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetId"> <input id="user_reservation" type="text" value="{reservation}" name="reserv_time"> час.</div></td>
								</tr>

								</tbody>
							</table>


						</div>
					</td>
				</tr>
				</tbody>
			</table>

			<table class="table table-striped table_page">

				<thead>
				<tr>
					<td class="text_align_c">Переопределение процентов</td>
				</tr>
				</thead>

				<tbody>
				<tr>
					<td class="padding_10 vertical_align">
						<div style="float: left;margin-left: 7px">Состояние</div>
						<div style="float: right;margin-right: 200px">
							<input type="checkbox" name="paysyss_override" {paysyss_override} style="text-align: center;width: 50px;">
						</div>
					</td>
				</tr>
				</tbody>

			</table>

			<table class="table table-striped table_page">

				<thead>
				<tr>
					<td class="text_align_c">Доступные способы оплат</td>
				</tr>
				</thead>

				<tbody>
				{{FOR:list}}
				<tr>
					<td class="padding_10 vertical_align">
						<div style="float: left;margin-left: 7px">{paysyss}</div>
						<div style="float: right;margin-right: 200px">
							<input type="checkbox" name="{id}" {isChecked} style="text-align: center;width: 50px;">
						</div>
						<div style="float: right;margin-right: 180px">
							<input type="number" min="0" step="0.01" maxlength="3" type="text" name="sys_{id}_fee" value="{fee}" style="text-align: center;width: 50px;"> %
						</div>

					</td>
				</tr>
				{{ENDFOR:list}}
				</tbody>

			</table>
			<div id="loginError">{error}</div>

			<div id="panel_cabinet_useredit_info"></div>
			<button class="btn btn-small btn-success" style="padding: 6px 0;width: 12%;display:block;margin: 10px auto 0 auto;" name="button" data-form="user_cabine">Сохранить</button>
		</form>


	</div>
</div>


