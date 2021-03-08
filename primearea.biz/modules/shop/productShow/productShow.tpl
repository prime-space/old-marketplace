<div class="middle_lot_h clearfix">
	<div class="middle_lot_h_l">
		<div class="middle_lot_h_spidbar">{group2} / {group1} / {group0}</div>
		<div class="middle_lot_h_name">{name}</div>
	</div>
	<div class="middle_lot_h_r">
		<div class="middle_lot_h_social">
			<div class="share42init" data-path="/style/img/" data-icons-file="share_icons.png"></div>
		</div>
		<select class="select_style" id="currentListSel" onchange="currentList(this.value);" style="visibility:hidden;">
			<option value="1">USD</option>
			<option value="2">ГРН</option>
			<option value="3">EUR</option>
			<option value="4" selected="true">РУБ</option>
		</select>
	</div>
</div>
<div class="middle_lot_c">
	<div class="middle_lot_c_img"><a onclick="main.fullviewimage('{fullviewimage}');"><img src="{picture}" alt="{name}"></a></div>seller-percents
	<div class="middle_lot_c_info">
		<div class="middle_lot_c_info_p">
			<i class="sprite sprite_lot_user"></i> 
			<span class="strong">Продавец:</span> 
			<a href="/seller/{sellerid}/">{seller}</a>
			<div class="productRateProd">
			    <div class="{minusClass}" style="width: {seller-stars}"></div> 
			</div>
			{seller-percents} <span class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="Общий процент хороших/плохих отзывов. Рейтинг является основным индикатором репутации продавца. На рейтинг могут влиять только непосредственные покупатели, путем написания отзывов о купленном товаре.">?</span>
			<div style="position: absolute;top: -2px;left: 342px;width: 150px;">{pro}</div>
			</div>
		<div class="middle_lot_c_info_p"><i class="sprite sprite_lot_user"></i> <span class="strong">Рейтинг продавца: {seller-rating}</span> </div>
		<div class="middle_lot_c_info_p"><i class="sprite sprite_lot_list"></i> <span class="strong">Объект продажи:</span> {typeObject}</div>
		<div class="middle_lot_c_info_p"><i class="sprite sprite_lot_pay"></i> <span class="strong">Продано раз:</span> {sale}</div>
		<div class="middle_lot_c_info_p"><i class="sprite sprite_lot_comm"></i> <span class="strong">Отзывы о товаре:</span> <span class="color_green">{reviewGood}</span> / <span class="color_red">{reviewBad}</span></div>
		<div class="middle_lot_c_info_p">
			<i class="sprite sprite_lot_comm"></i> 
			<span class="strong">Рейтинг товара:</span>
			<div class="productRateProd">
			    <div class="{minusClass2}" style="width: {stars}"></div>
			</div>
			{percents}
<span class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="Рейтинг является основным индикатором репутации товара. На рейтинг могут влиять только непосредственные покупатели, путем написания отзывов о купленном товаре.">?</span>
		</div>
		<div class="middle_lot_c_info_p"><i class="sprite sprite_lot_nal"></i> <span class="strong">Наличие:</span> {availableInfo}</div>
	</div>
	<div class="middle_lot_c_pay_f" style="padding-top:30px;padding-left: 16px;">
		<div class="middle_lot_c_pay">
			{buy_button}
			<div class="middle_lot_c_pay_btn2">
				<a href="/garant" target="_blank"><i class="sprite sprite_lot_bomb"></i><span>Гарантии</span></a></div>

			<div class="middle_lot_c_pay_form">
				<div class="middle_lot_c_pay_mega_pay"><span>Мега цена</span><i class="sprite sprite_lot_bomb"></i></div>
				<div class="middle_lot_c_pay_pay">{price}</div>
			</div>

		</div>
	</div>
</div>
<div class="middle_lot_t">
	<div class="middle_lot_t_l">

		<div class="middle_lot_t_l_btn active" data-tabs-btn="1">Описание</div>
		<div class="middle_lot_t_l_btn" data-tabs-btn="2">Доп. информация</div>
		{partnertab}
		<div class="middle_lot_t_l_btn" data-tabs-btn="4" style="display:{discountDisplay};">Скидки</div>

	</div>
	<div class="middle_lot_t_r">

		<div class="middle_lot_t_r_content active" data-tabs-content="1">{descript}</div>
		<div class="middle_lot_t_r_content" data-tabs-content="2">{info}</div>
		<div class="middle_lot_t_r_content" data-tabs-content="3">{partner}</div>
		<div class="middle_lot_t_r_content" data-tabs-content="4">
			Скидки: На товар предоставляется скидка постоянным покупателям.<br /> Если общая сумма покупок у продавца <strong>{productUserLogin}</strong> больше чем:<br /><strong>{discount}</strong>
			<strong style="display: block;margin-top: 10px;"> Узнать вашу скидку: </strong>
			<form class="middle_lot_t_r_content_f" onsubmit="return false;">
				<input type="text" id="productShowDiscountEmail" name="story" value="Введите email" onblur="if(this.value=='') this.value='Введите email';" onfocus="if(this.value=='Введите email') this.value='';" maxlength="32">
				<button class="btn btn-small btn-success" type="submit" onclick="module.product.checkdiscount();">Узнать</button>
			</form>
		</div>

	</div>
</div>
<div class="info_red_form">
				<span class="span_info_red"><i>!</i></span>
					<span class="span_text_b"></span><span class="span_text">Внимание, жалоба в арбитраж принимается только при наличии видеозаписи от начала покупки, до проверки товара.</span><br><br>
				</div><br>
<div class="middle_lot_o">
	<div class="middle_lot_o_title">Отзывы о товаре</div>
	<div class="middle_lot_o_l">

		<a name="head_module.product.review.list.get"></a>
		<div id="currentListSel"></div>
		<div id="review_list"></div>

	</div>
<!--
	<div class="middle_lot_o_r">
<script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>

<script type="text/javascript">
  VK.init({apiId: 5454841, onlyWidgets: true});
</script>

<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 10, width: "455", attach: "false", autoPublish: "1"});
</script>
	</div>
-->
</div>

{review}
<!--
<script type="text/javascript" src="//vk.com/js/api/openapi.js?136"></script>

<div id="vk_community_messages"></div>
<script type="text/javascript">
VK.Widgets.CommunityMessages("vk_community_messages", 11565719, {});
</script>
-->