<div class="pa_middle_c_r_blockside">
<div class="popular_item">Популярные  <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Самый продаваемый товар дня">?</span></div>
<ul class="bxslider">
	{{FOR:ITEMS}}
	<li>
		<div class="popular_sales">
			<div class="position-{position}"></div>
			<div class="popular_times">Продано: {count}</div>
			<div class="popular_images"><a href="/product/{id}/"> <img src="{image_url}"/></a></div>
			<div class="popular_name"><a href="/product/{id}/"> {title}</a></div>
		</div>
	</li>
	{{ENDFOR:ITEMS}}	
</ul>
</div>