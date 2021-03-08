
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Настройки продавца</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.user.partner.sellerSettings.save(this);return false;">
			<table class="table table-striped table_page table_pss">
				<thead>
				<tr>
					<td>При продаже товара через НЕ партнера:</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td class="padding_10">
						<div class="label_f"><label><input type="radio" name="notifi" {notifi0}> Не уведомлять</label></div>
						<div class="label_f"><label><input type="radio" name="notifi" {notifi1}> Уведомлять</label></div>
						<div class="label_f"><label><input type="radio" name="notifi" {notifi2}> Автоматически предлагать партнерство и дополнительную комиссию</label> <input type="text" name="fee" value="{autoFee}" maxlength="2" class="fee"> %</div>
					</td>
				</tr>
				<tr>
					<td id="saveSettError"></td>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td style="background: #4E4E4E;border-top: 0 dashed transparent;padding: 10px;text-align: right;vertical-align: middle;"><button name="button" class="btn btn-success">Сохранить</button></td>
				</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>

