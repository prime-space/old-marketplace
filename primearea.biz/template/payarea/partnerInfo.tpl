<div id="partnerInfo">
	<form onsubmit="panel.user.partner.find.offerPartnership(this);return false;">
		<table class="table table-striped table_page_popup">
			<tbody>
				<tr>
					<td style="width: 180px;" class="font_weight_700 vertical_align text_align_r padding_10">Ник</td>
					<td class="vertical_align padding_10 text_align_l">{login}</td>
				</tr>
				<tr>
					<td class="font_weight_700 vertical_align text_align_r padding_10">Дата регистрации</td>
					<td class="vertical_align padding_10 text_align_l">{registered}</td>
				</tr>
				<tr>
					<td class="font_weight_700 vertical_align text_align_r padding_10">Рейтинг</td>
					<td class="vertical_align padding_10 text_align_l">{rating}</td>
				</tr>
				<tr>
					<td class="font_weight_700 vertical_align text_align_r padding_10">Продаж (всего/месяц)</td>
					<td class="vertical_align padding_10 text_align_l">{sales}/{salesMonth}</td>
				</tr>
				<tr>
					<td colspan="2" class="font_size_12 vertical_align padding_10 text_align_l">
						Описание предоставляемых услуг и методов реализации товаров:
						<div style="background: #337AB7;color: #ffffff;margin-top: 5px;padding: 10px 6px;">{description}</div>
					</td>
				</tr>
				<tr>
					<td class="font_weight_700 vertical_align text_align_r padding_10">Дополнительная комиссия</td>
					<td class="vertical_align padding_10 text_align_l"><input style="text-align: center;width: 50px;" type="text" name="fee" value="0"> %</td>
				</tr>
				<tr>
					<td colspan="2" class="vertical_align text_align_r padding_10">
						<button name="button" class="btn btn-success">Предложить партнерство</button>
						<input type="hidden" name="partnerId" value="{partnerId}">
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>