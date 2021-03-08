<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Вывод средств</h3>
	</div>
	<div class="panel-body">
		<table class="table table_page">
			<tbody>
			<tr>
				<td class="text_align_c padding_10">
					<p>Доступны счета для вывода:</p>
					<div class="btn btn-success" >Баланс счета: <span id="user_cashoutWMRbalance">{wmr}</span> руб.</div>
					<div class="btn btn-success" >+ <span id="user_cashoutWMRbalance">{wmr_left}</span> руб.</div>
					<span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Средства на удержании. В скором времени зачислятся на ваш баланс">?</span>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>



<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Форма вывода средств</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page label-margin td-table">
			<tbody>



			<tr>
				<td class="font_weight_700 padding_10 text_align_r vertical_align">Выводить на:</td>

				<td class="padding_10 vertical_align">
                    {{FOR:CASHOUT_SYSTEMS}}
					<label>{cashout_system_name} <input type="radio" class="cashout_type" name="cashout_type" value="{cashout_system_key}"></label>
                    {{ENDFOR:CASHOUT_SYSTEMS}}
				</td>
			</tr>
			<tr>
				<td class="font_size_14 font_weight_700 text_align_r padding_10">Сумма: </td>
				<td class="padding_10">
					<form onsubmit="panel.user.cashout.add(this); return false;" class="form_content_def">
						{cashOutForm}
					</form>
					<div id="panel_user_cashout_add_error" style="text-align: left;"></div>

				</td>

			</tr>

			</tbody>
		</table>
	</div>
</div>




<a name="head_panel.user.cashout.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Список запросов</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page table-responsive tr-border">
			<thead>
			<tr>
				<td style="text-align: center;width: 20%;">Дата</td>
				<td style="text-align: center;width: 20%;">Сумма</td>
				<td style="text-align: center;width: 20%;">Валюта</td>
				<td style="text-align: center;width: 20%;">Статус</td>
				<td style="text-align: center;width: 20%;">Протекция</td>
			</tr>
			</thead>
			<tbody id="user_cashoutRequestListDiv">
			</tbody>

		</table>
	</div>
</div>
