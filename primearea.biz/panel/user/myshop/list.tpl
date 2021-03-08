{{IF:NOPRODUCTLIST}}
	{{FOR:PRODUCTLIST}}
		<tr>
			<td data-label="ID" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{id}</td>
			<td data-label="Название" class="font_size_12 padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align" style="width:200px;"><a href="/product/{id}/" target="_blank">{name}</a></td>
			<td data-label="Продажи" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{sold}</td>
			<td data-label="Цена" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align">{price}</td>
			<td data-label="В наличие" class="font_size_12 text_align_c padding_b_10 padding_l_5 padding_r_5 padding_t_10 vertical_align only-mobil">{pcs}</td>

			<td data-label="" class="font_size_12 text_align_r padding_10 vertical_align">

				<div class="select-mobil text-center">
					<div class="computer">

						<a class="btn btn-primary btn-sm{disabled}"{onclick} title="{text_title}"  style="margin:2px 0;padding:6px 15px;">{text}</a>
						<a class="btn btn-success btn-sm only-computer" href="/panel/productobj/edit/{id}" title="Объекты продажи"  style="margin:0;padding:6px 15px;width:55px;">{pcs}</a>
						<a class="btn btn-info btn-sm" href="/panel/productobj/add/{id}" title="Загрузить"  style="margin:2px 0;padding:6px 15px;">Добавить</a>
						<a  class="btn btn-warning btn-sm" href="/panel/productedit/{id}/" title="Изменить"  style="margin:2px 0;padding:6px 15px;">Изменить</a>
						<a class="btn btn-danger btn-sm" onclick="panel.user.myshop.del({id}, this); return false;" title="Удалить"  style="margin:2px 0;padding:6px 15px;">Удалить</a>
						<span data-type="{type}" onclick="common.popup.open('{refuse_message}', false, '{_message}', this);" style="background-color: {_color}" class="btn btn-my btn-sm" title="Публикация"  style="margin:2px 0;background-color: #505050;padding:6px 15px;">{desc}</span>

						<a href="#" class="close-select">X</a>
					</div>
					<div class="mobil">
						<a class="btn btn-primary open-computer"><i class="lnr lnr-arrow-down-circle"></i> Выбрать действие</a>
					</div>
				</div>

			</td>
		</tr>
	{{ENDFOR:PRODUCTLIST}}
{{ELSE:NOPRODUCTLIST}}<div  class="panel_user_myshop_list_str"><div colspan="8" style="text-align:center;">Список пуст</div></div>{{ENDIF:NOPRODUCTLIST}}