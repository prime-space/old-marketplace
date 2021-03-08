<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$page = <<<HTML
<div class="pa_middle_c_l_b_head">
	<div class="sprite sprite_page"></div>
	<div class="pa_middle_c_l_b_head_7">404</div>
</div>
<table class="table table-striped table_page">
	<thead>
		<tr>
			<td>Ошибка платежа.</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="font_size_14 padding_10 text_align_c bg_red">Платеж не выполнен.</td>
	</tbody>
</table>
HTML;

	$HEAD['title'] = 'PRIMEREA.RU - Ошибка платежа';

?>