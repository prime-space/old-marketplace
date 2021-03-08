{{SWITCH:REVIEW}}
	{{CASE:OLD_DEL}}
		<div class="middle_lot_o_one admin admin2">
			<div class="middle_lot_o_t2">Отзыв был удален</div>
		</div>
	{{ENDCASE:OLD_DEL}}
	{{CASE:DEL}}
		<div class="middle_lot_o_one middle_lot_o_one0">
			<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> Отзыв был удален {review_del_who}</div>
			<div class="middle_lot_o_t2">{review_text}</div>
			<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {review_del_date} <i class="sprite sprite_otz_time"></i> 00:00:00</div>
		</div>
	{{ENDCASE:DEL}}
	{{CASE:NOREVIEW}}
		<div class="middle_lot_o_one admin admin2">
			<div class="middle_lot_o_t2">Покупатель пока не оставил отзыв</div>
		</div>
	{{ENDCASE:NOREVIEW}}
	{{CASE:REVIEW}}
		<div class="middle_lot_o_one middle_lot_o_one{evaluation}">
			<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag"></i> ID: {review_id} | Оценка покупателя <span class="middle_lot_o_good">положительная</span><span class="middle_lot_o_bead">отрицательная</span></div>
			<div class="middle_lot_o_t2">{review_text}</div>
			<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {review_date} <i class="sprite sprite_otz_time"></i> 00:00:00</div>
		</div>
					
		{{IF:REVIEW_ANSWER}}
		<div class="middle_lot_o_one admin admin2">
			<div class="middle_lot_o_t1">Ответ продавца</div>
			<div class="middle_lot_o_t2">{review_answer_text}</div>
		</div>
		{{ELSE:REVIEW_ANSWER}}{{ENDIF:REVIEW_ANSWER}}			
	{{ENDCASE:REVIEW}}
{{ENDSWITCH:REVIEW}}