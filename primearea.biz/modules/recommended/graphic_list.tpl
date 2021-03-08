{{IF:NOGRAPHIC}}
<div class="pa_middle_t2">
		{{FOR:GRAPHIC}}
		<div class="pa_middle_t_lot_f">
			<a href="/link/{id}/" class="pa_middle_t_lot" title="{name}">
				<img src="{picture}" alt="{name}">
				<div class="pa_middle_t_lot_r">
					<div class="sprite sprite_t_lot_user"  data-toggle="tooltip" data-placement="left" title="Продавец: {user}"></div>
					<div class="sprite sprite_t_lot_cart" data-toggle="tooltip" data-placement="left" title="Покупок: {sale}"></div>
					<div class="sprite sprite_t_lot_comments" data-toggle="tooltip" data-placement="left" title="Отзывов: {reviews}"></div>
				</div>
				<div class="pa_middle_t_lot_b">
					{name}<div class="pa_middle_t_lot_b_price">{price}</div>
				</div>
			</a>
		</div>
		{{ENDFOR:GRAPHIC}}
{{ELSE:NOGRAPHIC}}
</div>
{{ENDIF:NOGRAPHIC}}