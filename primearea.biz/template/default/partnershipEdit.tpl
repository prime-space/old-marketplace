

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/partnerships/">Мои партнеры</a> » {login}</h3>
	</div>
	<div class="panel-body">
		{{IF:NOFOUND}}<ul class="nolist">Партнер не найден</ul>{{ELSE:NOFOUND}}

		<div id="graph"></div>

		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td class="padding_10 vertical_align text_align_c">
					<div id="personalPercentError"></div>
					Дополнительный процент:
					<form onsubmit="panel.user.partner.mypartner.editPercent(this);return false;" class="form_content_def">
						<input type="text" maxlength="5" style="text-align: center;width: 70px;" name="percent" value="{percent}">
						<button class="btn btn-success btn-sm" name="button">Изменить</button>
						<input type="hidden" value="{partnershipId}" name="partnershipId">
					</form>
				</td>
			</tr>
			</tbody>
		</table>

		{messages}

		{{ENDIF:NOFOUND}}
	</div>
</div>

