

<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Партнерская программа v1 PRIMEAREA</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td style="width: 50%;" class="padding_10 text_align_c vertical_align">Партнерский виджет</td>
				<td class="padding_10 text_align_c vertical_align">Партнерское вознаграждение при продаже товара - <strong>{discount}</strong></td>
			</tr>
			<tr>
				<td class="padding_10 text_align_c vertical_align">

					<div class="primeareaGroup">
						<div class="primeareaLot">
							<div class="img">
								<img src="https://primearea.biz{img}">
							</div>
							<div class="name">
								<a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{title}</a>
							</div>
							<div class="price">
								<a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{price}</a>
							</div>
						</div>
					</div>

				</td>
				<td class="padding_10 text_align_c vertical_align">
					<div style="margin: 0 auto;width: 300px;">
						<a class="btn btn-sm btn-primary display_block margin_5" href="/panel/partner/become/">Стать партнером</a>
						<a class="btn btn-sm btn-primary display_block margin_5" href="/panel/partner/mysellers/">Продавцы</a>
						<a class="btn btn-sm btn-primary display_block margin_5" href="/panel/partner/products/">Товары продавцов</a>
						<a class="btn btn-sm btn-primary display_block margin_5" href="/panel/partner/partnerstat/">Моя статистика</a>
						<a class="btn btn-sm btn-primary display_block margin_5" href="/panel/cashout/">Вывод средств</a>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table class="table table-striped table_page">
			<tbody>
			<tr>
				<td style="width: 50px;" class="text_align_c font_weight_700 padding_10">1.</td>
				<td class="padding_10">
					Разместите между тегами &lt;style&gt;&lt;/style&gt; в head или файле стилей
				<pre class="css">.primeareaGroup{font-family:Arial,sans-serif;font-size:0;margin:0 auto;text-align:center;width:100%;}
					.primeareaGroup .primeareaLot{display:inline-block;font-size:13px;margin:2px;position:relative;vertical-align:top;text-align:center;width:202px;}
					.primeareaGroup .primeareaLot .img{border:1px solid #C2C2C2;margin:0 auto;overflow:hidden;height:125px;width:200px;}
					.primeareaGroup .primeareaLot .img img{border:0;display:block;margin:0 auto;height:125px;width:200px;}
					.primeareaGroup .primeareaLot .name{left:50%;margin-left:-100px;overflow:hidden;position:absolute;text-align:center;top:1px;height:125px;width:200px;z-index:1;}
					.primeareaGroup .primeareaLot .name a{background-color:rgba(0,0,0,0.25);color:#ffffff;display:block;font-size:16px;font-weight:700;line-height:16px;padding:5px 8px;text-decoration:none;text-shadow:0 1px 2px #000000;transition-duration:0.2s;height:115px;width:184px;}
					.primeareaGroup .primeareaLot:hover .name a{background-color:rgba(0,0,0,0.1);color:#B8EEFF;}
					.primeareaGroup .primeareaLot .price{bottom:1px;right:50%;margin-right:-100px;position:absolute;z-index:2;}
					.primeareaGroup .primeareaLot .price a{background-color:#BD1717;border-left:1px solid #C2C2C2;border-top:1px solid #C2C2C2;color:#ffffff;display:block;font-size:15px;font-weight:700;padding:5px;text-decoration:none;}</pre>
				</td>
			</tr>
			<tr>
				<td style="width: 50px;" class="text_align_c font_weight_700 padding_10">2.</td>
				<td class="padding_10">
					Разместите между тегами &lt;body&gt;&lt;/body&gt;
				<pre class="html"><div class="primeareaGroup">
						<div class="primeareaLot">
							<div class="img">
								<img src="https://primearea.biz{img}">
							</div>
							<div class="name">
								<a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{title}</a>
							</div>
							<div class="price">
								<a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{price}</a>
							</div>
						</div>
					</div></pre>
				</td>
			</tr>
			<tr>
				<td style="width: 50px;" class="text_align_c font_weight_700 padding_10">3.</td>
				<td class="padding_10">
					Прямая (партнерская) cсылка на страницу оплаты товара
					<pre class="html">https://primearea.biz/buy/{pid}/{uid}</pre>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

