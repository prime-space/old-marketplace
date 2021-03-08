<div class="uinteretested">Вы интересовались</div>
{{IF:LAST_VISITS}}
	{{FOR:ITEMS}}
	<div class="uinterest_items">
<a href="/product/{id}/">
					<div class="each_interested">
						<img src="{image_url}"/>
						<div class="price_interest">Цена: {amount}</div>
						<div class="name_interest"> {title}</div>
					</div>
</a>
				</div> 
	{{ENDFOR:ITEMS}}
{{ELSE:LAST_VISITS}}
	<div class="each_notinterested">Нет просмотренных</div>
{{ENDIF:LAST_VISITS}}


