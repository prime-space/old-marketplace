<form onsubmit="panel.moder.newusers.moder.save_settings({id}, this);return false;">

	<table class="table table-striped table_page table_page_b table_page_input1 btn_tabs_content active" data-btn_tabs_content="1">
		<tbody>
			<tr>
				<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Процент отчислений в систему:</td>
				<td style="width: 190px;" class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetlogin"><input id="user_percent" type="text" value="{percent}" name="percent"> %</div></td>
			</tr>
			<tr>
				<td class="font_size_12 font_weight_700 padding_b_10 padding_l_5 padding_r_5 padding_t_10">Время удержания средств:</td>
				<td class="padding_l_5 padding_r_5 vertical_align"><div id="user_cabinetId"> <input id="user_reservation" type="text" value="{time}" name="reserv_time"> час.</div></td>
			</tr>
			
		</tbody>
	</table>



	<div id="panel_cabinet_useredit_info"></div>
	<button class="btn btn-small btn-success" style="margin-top: 10px;padding: 6px 0;width: 100%;" name="button" data-form="user_cabine">Сохранить</button>

</form>