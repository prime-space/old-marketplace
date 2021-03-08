<a name="head_panel.user.partner.become.offers.list.get"></a>
<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » Стать партнером</h3>
	</div>
	<div class="panel-body">
		<form onsubmit="panel.user.partner.become.profile(this);return false;">
			<table class="table table-striped table_page table_partnerB label-margin">
				<thead>
				<tr>
					<td colspan="2">Идентификатор партнера (ID): {userId}</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width: 350px;" class="font_size_14 font_weight_700 padding_10">Рекламировать себя как партнера</td>
					<td class="font_size_14 padding_10"><label><input name="become" type="checkbox" {public}> сделать информацию о услугах публичной для продавцов</label></td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 padding_10">Краткое описание о услугах и методах реализации товаров:</td>
					<td class="font_size_14 padding_10"><textarea name="description" class="textarea_def textarea_500 form-control" rows="10">{description}</textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="text_align_r padding_10">
						<div id="becomePartnerError"></div>
						<button class="btn btn-success"  name="button">Сохранить</button>
					</td>
				</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>



<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Предложения от продавцов</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td>Имя</td>
				<td class="text_align_c">Доп. комиссия</td>
				<td colspan="2"></td>
			</tr>
			</thead>
			<tbody id="offersList">
			</tbody>
		</table>
	</div>
</div>



