		<div class="pa_middle_c_l_b_pagination">
			{{IF:LOAD_MORE}}
			<div class="pagination_load_more" id="load_more_{function}">
				<div onclick="{add}" class="btn btn-default">Подгрузить ещё</div>
			</div>
			{{ELSE:LOAD_MORE}}{{ENDIF:LOAD_MORE}}
			<div class="pagination_pages">
				<div class="pagination_pages_head" id="pages_head_{function}"></div>
				<ul class="pagination">
					{{FOR:PAGE_NO}}
						{dats_previous}
						<li class="{class}"><a onclick="{page_no_event}">{p}</a></li>
						{dats_next}
					{{ENDFOR:PAGE_NO}}
				</ul>
				<div style="display:none;" id="pages_{function}">{pages}</div>
			</div>
			{{IF:SELECT_ELEMENTS_ON_PAGE}}
				<div class="pagination_amount_positions">
					элементов на странице:
					<select id="elements_on_page_{function}" onchange="{select_event}">
						{{FOR:POSITION_ON_PAGE}}
							<option value="{amount}" {selected}>{amount}</option>
						{{ENDFOR:POSITION_ON_PAGE}}
					</select>
				</div>
			{{ELSE:SELECT_ELEMENTS_ON_PAGE}}{{ENDIF:SELECT_ELEMENTS_ON_PAGE}}
		</div>