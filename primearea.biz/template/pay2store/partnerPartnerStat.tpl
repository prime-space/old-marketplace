
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Статистика</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td class="font_size_14 font_weight_700 text_align_c padding_0">
					<div><div id="graph"></div></div>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td colspan="2">Мой рейтинг: {rating}</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td colspan="2"><div id="graph"></div></td>
			</tr>
			<tr>
				<td id="statTabs" style="width: 150px;" class="padding_10">
					<button style="width: 150px;" onclick="main.tab.change(this, panel.user.partner.partnerStat.tab);" class="btn btn-info btn-sm display_block" disabled>Уведомления</button>
					<button style="margin-top:10px;width: 150px;" onclick="main.tab.change(this, panel.user.partner.partnerStat.tab);" class="btn btn-info btn-sm display_block">Продажи</button>
				</td>
				<td class="padding_b_10 padding_r_10 padding_t_10">
					<a name="head_panel.user.partner.partnerStat.notifications.list.get"></a>
					<a name="head_panel.user.partner.partnerStat.sales.list.get"></a>
					<table class="table table-striped table_page" id="notifications">
						<thead>
						<tr>
							<td style="width: 110px;" class="text_align_c">Дата</td>
							<td>Действия</td>
						</tr>
						</thead>
						<tbody id="partnerNotificationsList">
						</tbody>
					</table>

					<table class="table table-striped table_page" id="sales" style="display: none;">
						<thead>
						<tr>
							<td>Товар</td>
							<td style="width: 100px;" class="text_align_c">Продавец</td>
							<td style="width: 80px;" class="text_align_c">Начислено</td>
							<td style="width: 110px;" class="text_align_c">Дата</td>
						</tr>
						</thead>
						<tbody id="salesList">
						</tbody>
					</table>

				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

