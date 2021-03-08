{{SWITCH:template}}
	{{CASE:notFound}}
	<a name="head_panel.user.partner.products.group.addproducts.list.get"></a>

	<div class="panel panel-headline">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/products/">Группы товаров</a> » Группа не найдена</h3>
		</div>
	</div>

	{{ENDCASE:notFound}}
	{{CASE:page}}
	<a name="head_panel.user.partner.products.group.addproducts.list.get"></a>
	<div class="panel panel-headline">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/products/">Группы товаров</a> » <a href="/panel/partner/products/{groupId}">{groupName}</a> » Добавление товаров</h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped table_page table_partnerB">
				<thead>
				<tr>
					<td colspan="2">Параметры поиска</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="width: 200px;" class="font_size_14 font_weight_700 text_align_r padding_10 vertical_align">Выбрать из партнеров:</td>
					<td class="padding_10">
						<select id="productSeller" onchange="panel.user.partner.products.group.addproducts.list.get(0, false, true);">
							<option value="0">Все продавцы</option>
							{{FOR:userlist}}<option value="{userId}">{login} ({count})</option>{{ENDFOR:userlist}}
						</select>
					</td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10 vertical_align">Категории:</td>
					<td class="padding_10">
						<label><input type="radio" name="groupsFind" onclick="panel.user.partner.products.group.addproducts.chooseGroup.action(false, 0);" checked> Во всех группах</label>&nbsp;&nbsp;
						<label><input type="radio" name="groupsFind" onclick="panel.user.partner.products.group.addproducts.chooseGroup.action(0, 0);"> Выбрать группу</label>
						<div class="row" id="addproductGroupsRow">
							<div id="addproductGroups"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="padding_10">
						Пометьте галочками  товары, которые желаете поместить в группу. Не добавляйте в одну группу большое количество товаров, т.к. это осложнит поиск для покупателя!
						Лучше создайте несколько небольших групп, в которых товары будут объединены по тематике или типам услуг, например группа «Интернет-провайдеров», группа «операторов
						сотовой связи,» группа «операторов IP-телефонии» и т.д. и т.п. Позаботьтесь о добавление товаров, которые будут интересны целевой аудитории вашего сайта. Пожалуйста
						уделите выбору товаров особое внимание, потому, что от его правильности будет во многом зависеть ваш финансовый доход.
					</td>
				</tr>
				<tr>
					<td class="font_size_14 font_weight_700 text_align_r padding_10 vertical_align">Сортировать:</td>
					<td class="padding_10">
						<select id="addProductsListSort" onchange="panel.user.partner.products.group.addproducts.list.get(0, false, true);">
							<option value="0">По дате поступления</option>
							<option value="1">По количеству продаж</option>
							<option value="2">По цене - возрастанию</option>
							<option value="3">По цене - убыванию</option>
							<option value="4">По алфавиту</option>
						</select>
					</td>
				</tr>
				</tbody>
			</table>

			<hr>

			<form onsubmit="panel.user.partner.products.group.addproducts.add(this);return false;">
				<table class="table table-striped table_page">
					<thead>
					<tr>
						<td>Название</td>
						<td class="text_align_c">Продавец</td>
						<td class="text_align_c">Цена</td>
						<td class="text_align_c">Комиссия</td>
						<td class="text_align_c">Продаж</td>
						<td></td>
					</tr>
					</thead>
					<tbody id="addproductList">
					</tbody>
					<tfoot id="addproductPanel" style="display: none;">
					<tr>
						<td style="background: #4E4E4E;border-top: 0 dashed transparent;padding: 10px;text-align: right;vertical-align: middle;" colspan="6">
							<button class="btn btn-success" name="button">Добавить отмеченные</button>
						</td>
					</tr>
					</tfoot>
				</table>
			</form>
		</div>
	</div>


	{{ENDCASE:page}}
{{ENDSWITCH:template}}
