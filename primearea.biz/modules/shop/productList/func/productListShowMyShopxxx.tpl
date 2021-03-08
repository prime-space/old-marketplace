<div class="gen_tablelist_message" style="text-align:left;line-height:15px;">
	<div style="display:inline-block;">
		<div class="productListTHeadP"><i class="icon-warning-sign icon-red"></i> Рекомендуем проверить свои товары на правильность выбранной категории и соответствует ли они правилам.</div>
		<div class="productListTHeadP"><i class="icon-warning-sign icon-red"></i> Все товары, которые нарушают правила системы будут заблокированы до выяснения причин.</div>
	</div>
	<div style="display:inline-block;padding-left:120px;padding-top:5px;vertical-align:top;position:absolute;">
		<a href="?p=user_recommended" style="width:130px;" class="btn btn-success" onclick="changePage('p','user_recommended'); return false;">Рекомендуемое</a>
	</div>
</div>

<table class="gen_tablelist">
	<tr>
		<th>id</th>
		<th style="width:50%">Название</th>
		<th colspan="2">Изменить</th>
		<th style="width:98px;">Наличие</th>
		<th style="width:22px;"><img src="stylisation/images/trash_22.png"></th>
		<th style="width:70px;color:#DD2A2A;text-align:center;">Продаж</th>
		<th style="width:80px;text-align:center;">Цена</th>
	</tr>
	{{IF:NOPRODUCTLIST}}
		{{FOR:PRODUCTLIST}}
			<tr class="gen_tablelist_tr_{color}">
				<td>{id}</td>
				<td><a href="?p=showproduct&productid={id}" target="_blank">{name}</a></td>
				<td style="text-align:center;width:30px;">
					<a onclick="changePage('p,productid','productred,{id}'); return false;" title="Редактировать">
						<i class="icon-pencil icon-green"></i>
					</a>
				</td>
				<td style="text-align:center;width:30px;">{pcs}</td>
				<td>
					<a onclick="changePage('p,productid','productredobjsale,{id}'); return false;" title="Загрузить">
						<div style="display:inline-block;vertical-align:middle;">добавить</div> 
						<div style="display:inline-block;vertical-align:middle;"><i class="icon-download-alt icon-green"></i></div> 
					</a>
					&nbsp;
					<a id="productShopsiteAddDelHide{id}" style="visibility:{show_addmyshop}" onclick="productShopsiteAddDel({id},'add'); return false;" title="Добавить в Мой магазин">
						<i class="icon-th-list icon-green"></i>
					</a>
				</td>
				<td style="text-align:center;">
					<a onclick="productListDel({id}); return false;" title="Удалить">
						<i class="icon-remove icon-red"></i>
					</a>
				</td>
				<td style="text-align:center;color:#DD2A2A;">{sold}</td>
				<td style="text-align:center;">{price}</td>
			</tr>
		{{ENDFOR:PRODUCTLIST}}
	{{ELSE:NOPRODUCTLIST}}<tr class="gen_tablelist_tr_a"><td colspan="8" style="text-align:center;">Список пуст</td></tr>{{ENDIF:NOPRODUCTLIST}}
</table>
