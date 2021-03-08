<div class="pageName"><h1>Партнерская программа</h1></div>

<div class="wrapper">
	<div>
		<div style="float:left;" class="primeareaGroup">
			<div style="font-size:16px;">Партнерский виджет</div>
			<div class="primeareaLot">
				<div class="img"><img src="{img}"></div>
			</div>
		</div>

		<div class="userarea">
		<div>Партнерсоке вознаграждение при продаже товара - <strong>{discount}</strong></div>

		<div class="ulink">
			<p style="margin-bottom:10px;">Идентификатор партнера - <span style="color: red;">{uid}</span></p>
			
			<div style="float:left;border-right:1px solid #000;padding-right: 25px;padding-bottom:5px;">
				<div><a href="/panel/partner/become/">Стать партнером</a></div>
				<div><a href="/panel/partner/mysellers/">Продавцы</a></div>
				<div><a href="/panel/partner/products/">Товары продавцов</a></div>
			</div>
			<div style="float:right;">
				<div><a href="/panel/partner/partnerstat/">Моя статистика</a></div>
				<div><a  href="/panel/partner/partnerstat/">Вывод средств</a></div>
			</div>
		</div>

		</div>
	</div>
	<div style="clear:both;"></div>

	<div style="margin-top:30px;">
		<h2>HTML - код</h2>
		<p>Разместите между тегами &lt;style&gt;&lt;/style&gt; в head или файле стилей</p>
		<textarea rows="7">
.primeareaGroup{font-family:Arial,sans-serif;font-size:0;margin:0 auto;text-align:center;width:100%;}
.primeareaGroup .primeareaLot{display:inline-block;font-size:13px;margin:2px;position:relative;vertical-align:top;text-align:center;width:202px;}
.primeareaGroup .primeareaLot .img{border:1px solid #C2C2C2;margin:0 auto;overflow:hidden;height:125px;width:200px;}
.primeareaGroup .primeareaLot .img img{border:0;display:block;margin:0 auto;height:125px;width:200px;}
.primeareaGroup .primeareaLot .name{left:50%;margin-left:-100px;overflow:hidden;position:absolute;text-align:center;top:1px;height:125px;width:200px;z-index:1;}
.primeareaGroup .primeareaLot .name a{background-color:rgba(0,0,0,0.25);color:#ffffff;display:block;font-size:16px;font-weight:700;line-height:16px;padding:5px 8px;text-decoration:none;text-shadow:0 1px 2px #000000;transition-duration:0.2s;height:115px;width:184px;}
.primeareaGroup .primeareaLot:hover .name a{background-color:rgba(0,0,0,0.1);color:#B8EEFF;}
.primeareaGroup .primeareaLot .price{bottom:1px;right:50%;margin-right:-100px;position:absolute;z-index:2;}
.primeareaGroup .primeareaLot .price a{background-color:#BD1717;border-left:1px solid #C2C2C2;border-top:1px solid #C2C2C2;color:#ffffff;display:block;font-size:15px;font-weight:700;padding:5px;text-decoration:none;}
		</textarea>
		<br />
		<p>Разместите между тегами &lt;body&gt;&lt;/body&gt;</p>
		<textarea rows="7">
<div class="primeareaGroup">
<div class="primeareaLot">
<div class="img"><img src="{img}"></div>
<div class="name"><a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{title}</a></div>
<div class="price"><a target="_blank" href="https://primearea.biz/buy/{pid}/{uid}/">{price}</a></div>
</div>
</div>
		</textarea>
	</div>

	<div>
		<strong>Прямая ссылка на оплату</strong>
			<p class="plink">https://primearea.biz/buy/{pid}/{uid}/</p>
	</div>
</div>
<style>
	a{
		color:#333333;
	}
	.plink{
		background: #fff;
	    padding: 2px;
	    border: 1px solid #cccccc;
	    padding: 5px 8px;
	}
	.userarea{
		float:right;color: #333333 !Important;font-size:14px;
	}
	textarea{
		width:98%;
	}
	.ulink{
		background: rgba(211, 211, 211, 0.23);
		border:1px solid #333333;
		overflow:hidden;
		width:290px;
		    padding: 15px 25px 35px 25px;
	}
</style>