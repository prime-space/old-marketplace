{{SWITCH:template}}
	{{CASE:notFound}}
	<a name="head_panel.user.partner.products.list.get"></a>
	<div class="panel panel-headline">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/products/">Группы товаров</a> » Группа не найдена</h3>
		</div>
	</div>

	{{ENDCASE:notFound}}
	{{CASE:page}}
	<a name="head_panel.user.partner.products.list.get"></a>
	<div class="panel panel-headline">
		<div class="panel-heading">
			<h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » <a href="/panel/partner/">Партнерская программа</a> » <a href="/panel/partner/products/">Группы товаров</a> » {groupName}</h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped table_page table_partnerB">
				<tbody>
				<tr>
					<td colspan="2" class="padding_10">
						Помимо переноса списка товаров на свой сайт Вы можете получить партнерскую ссылку на конкретный товар, а затем отправить ее своим друзьям по Email, ICQ или опубликовать на форуме,
						в блоге или социальной сети. Для получения партнерской ссылки откройте список добавленных товаров из группы и нажмите на иконку "прямая ссылка". Со всех покупок совершенных
						вашими друзьями, на ваш личный счет вы будете получать комиссионные.
					</td>
				</tr>
				<tr>
					<td style="width: 150px;" class="font_size_14 text_align_r font_weight_700 padding_10">CSS-код</td>
					<td class="padding_10">
						Разместите между тегами &lt;style&gt;&lt;/style&gt; в head или в файле стилей:
						<div class="spoiler">
							<div class="btn btn-info spoiler_title" data-spoiler_title="1" data-spoiler_title_open="Показать код" data-spoiler_title_closed="Скрыть код">Показать код</div>
							<div class="spoiler_content" style="display:none;" data-spoiler_content="1"><div style="display: block;
    padding: 9.5px;
    margin: 0 0 10px;
    font-size: 13px;
    line-height: 1.42857143;
    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;" class="css">{htmlCodeHead}</div></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="font_size_14 text_align_r font_weight_700 padding_10">HTML-код</td>
					<td class="padding_10">
						Разместите между тегами &lt;body&gt;&lt;/body&gt;:
						<div class="spoiler">
							<div class="btn btn-info spoiler_title" data-spoiler_title="2" data-spoiler_title_open="Показать код" data-spoiler_title_closed="Скрыть код">Показать код</div>
							<div class="spoiler_content" style="display:none;" data-spoiler_content="2"><div style="display: block;
																											 padding: 9.5px;
																											 margin: 0 0 10px;
																											 font-size: 13px;
																											 line-height: 1.42857143;
																											 color: #333;
																											 word-break: break-all;
																											 word-wrap: break-word;
																											 background-color: #f5f5f5;
																											 border: 1px solid #ccc;
																											 border-radius: 4px;" class="html">{htmlCodeBody}</div></div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="padding_10">
						<a class="btn btn-success" id="htmlButt" onclick="panel.user.partner.products.showMl('ht');">HTML-код</a>
						<a href="/panel/api/" target="_blank" class="btn btn-success">API</a>
						<a class="btn btn-info" href="/panel/partner/addproducts/{groupId}">Добавить товары</a>
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
				</tr>
				</tbody>
			</table>
			<hr>
			<table class="table table-striped table_page">
				<thead>
				<tr>
					<td>Название</td>
					<td class="text_align_c">Продавец</td>
					<td class="text_align_c">Прямая ссылка</td>
					<td class="text_align_c">Цена</td>
					<td class="text_align_c">Комиссия</td>
					<td></td>
				</tr>
				</thead>
				<tbody id="productsList">
				</tbody>
			</table>
		</div>
	</div>


	{{ENDCASE:page}}
{{ENDSWITCH:template}}
