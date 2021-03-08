<div class="newmaingoods">
{{IF:ADD}}{{ELSE:ADD}}{{ENDIF:ADD}}
{{FOR:TEXT}}
<div class="newgoodview">
		<div class="newnameanddiscount">
			<div class="newnamegood"><a href="/link/{id}/" class="lot_name">{name}</a></div>
			<div class="newdiscountgood">{percent}</div>
			<span class="mainPageTooltip" class="span_info span_watch" data-toggle="tooltip" data-placement="right" data-original-title="{pro}">{proIcon}</span>
		</div>
			<div class="newdiscrgood {proClass}">
				<div class="newpicturegood">
					<div class="newpicturegood"><a href="/link/{id}/" title="{name}"><img src="{picture}" alt="{name}"></a></div>
				</div>
					<div class="newpriceandname">
						<div class="newpricegood"><a href="/link/{id}/">{price}</a></div>
						<div class="newnameseller">
							<a href="/seller/{seller_id}/">{seller}</a>
						</div>
					</div>
						<div class="newsalesandrating">
							<div class="newsalesgood"><a href="/link/{id}/">Продаж: {sale}</a></div>
								<div class="newratinggood">
									<div class="newstars"><div class="productRate">
					    <div class="{minusClass}" style="width: {stars}"></div>
					</div></div>
									<div class="newpercent">{percents}</div>
			    				</div>
						</div>
			</div>
	</div>
{{ENDFOR:TEXT}}
</div>