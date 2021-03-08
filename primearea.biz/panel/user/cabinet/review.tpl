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
			<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {review_dt} <i class="sprite sprite_otz_time"></i> {review_tm}</div>
		</div>

		{{IF:REVIEW_ANSWER}}
		<div class="middle_lot_o_one admin admin2">
			<div class="middle_lot_o_t1">Ваш ответ</div>
			<div class="middle_lot_o_t2">{review_answer_text}</div>
			<div class="middle_lot_o_t1 btn newbtn-success" onclick="panel.user.review.change({review_id}, this); return false;" style="display:inline-block;padding: 2px;margin-bottom: 5px;">Изменить</div>
			<div class="middle_lot_o_t1 btn newbtn-danger" onclick="panel.user.review.delete({review_id}, this); return false;" style="display:inline-block;padding: 2px;margin-bottom: 5px;">Удалить</div>
		</div>
		{{ELSE:REVIEW_ANSWER}}
		<div class="middle_lot_o_one middle_lot_o_one_def">
			<div class="middle_lot_o_t1">Дать ответ</div>
			<div class="middle_lot_o_t2">
				<form name="review_answer" onsubmit="panel.user.cabinet.review_answer(this); return false;">
					<table class="table" style="background: transparent;margin: 0;">
						<tbody>
							<tr>
								<td class="padding_10 text_align_c" style="border: 0 solid rebeccapurple;">
									<textarea class="textarea_def form-control	" name="text" maxlength="500" rows="3"></textarea>
									<input name="review_id" type="hidden" value="{review_id}">
								</td>
							</tr>
							<tr>
								<td class="padding_10 text_align_r" style="border: 0 solid rebeccapurple;">
									<div id="review_answer_error"  class="form_error"></div>
									<button name="button" class="btn btn-sm btn-success">Ответить</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>	
			</div>
		</div>
		{{ENDIF:REVIEW_ANSWER}}
			
	{{ENDCASE:REVIEW}}
{{ENDSWITCH:REVIEW}}