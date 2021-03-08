<div class="pa_middle_c_r_blockside">
<div class="last_sales11" >
                    <div class="last_sale_name_before">Последние продажи</div>
     <div class="last_sales_group_changer last-prev prev_style_last_sales" data-group="3" ><img src="/style/img/left.png"/></div>
     <div class="last_sales_group_changer last-next next_style_last_sales" data-group="1" ><img src="/style/img/right.png"/></div>
    </div>
<div class="last_sales_group_parent last_sales_group1" data-group='0'></div>
<div class="last_sales_group_parent last_sales_group2" data-group='1'></div>
<div class="last_sales_group_parent last_sales_group3" data-group='2'></div>
<div class="last_sales_group_parent last_sales_group4" data-group='3'></div>
{{FOR:ITEMS}}
	<div class="last_sales_group" data-group="{count}">
<a href="/product/{id}/">
		<div class="last_sales_img"><img src="{image_url}"/></div>
		<div class="last_sales_img_clock"><img src="/style/img/clock.png"></div>
		<div class="last_sales_time">{date}</div>
		<div class="last_sales_price">за {amount}</div>
		<div class="last_sales_name">{title}</div>
</a>
	</div>
{{ENDFOR:ITEMS}}
</div>