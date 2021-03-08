

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title"><div class="pa_middle_c_l_b_head_12"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Мерчант </div></h3>
	</div>
	<div class="panel-body">
		<div class="pa_middle_c_l_b_head_6"><a href="/merchant/docs/" target="_blank" class="btn btn-info btn-lg">Документация</a></div>
		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td colspan="4" class="font_size_12">Магазины</td>
			</tr>
			</thead>
			<tbody>
			{{IF:shops}}
			{{FOR:shops}}
			<tr>
				<td class="padding_10 vertical_align">{mshopName}</td>
				<td class="padding_10 text_align_c vertical_align" style="width: 160px;">
					{{IF:mshopToChecking}}<div class="btn btn-warning btn-sm" onclick="merchant.tochecking(this,{mshopId});">Отправить на проверку</div>{{ELSE:mshopToChecking}}{{ENDIF:mshopToChecking}}
				</td>
				<td class="padding_10 text_align_r vertical_align" >
					<a class="btn btn-primary btn-sm" href="/merchant/shop/{mshopId}/#anchor">Операции</a>
					<a class="btn btn-primary btn-sm" href="/merchant/shop/{mshopId}/settings/#anchor">Настройки</a>
					<a class="btn btn-primary btn-sm" href="/merchant/shop/{mshopId}/methods/#anchor">Методы оплат</a>
				</td>
				<td class="padding_10 text_align_c vertical_align" style="width: 80px;">
					{{IF:mshopDel}}<div class="btn btn-danger btn-sm" onclick="merchant.delshop(this,{mshopId});">Удалить</div>{{ELSE:mshopDel}}{{ENDIF:mshopDel}}
				</td>
			</tr>
			{{ENDFOR:shops}}
			{{ELSE:shops}}{{ENDIF:shops}}
			<tr>
				<td colspan="4" class="padding_10 text_align_r"><div class="btn btn-success" onclick="merchant.createshop(this);">Добавить магазин</div></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

