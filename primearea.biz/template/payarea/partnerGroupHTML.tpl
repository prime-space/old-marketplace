{{SWITCH:html}}
	{{CASE:style}}
.primeareaGroup{font-family:Arial,sans-serif;font-size:0;margin:0 auto;text-align:center;width:100%;}
.primeareaGroup .primeareaLot{display:inline-block;font-size:13px;margin:2px;position:relative;vertical-align:top;text-align:center;width:202px;}
.primeareaGroup .primeareaLot .img{border:1px solid #C2C2C2;margin:0 auto;overflow:hidden;height:125px;width:200px;}
.primeareaGroup .primeareaLot .img img{border:0;display:block;margin:0 auto;height:125px;width:200px;}
.primeareaGroup .primeareaLot .name{left:50%;margin-left:-100px;overflow:hidden;position:absolute;text-align:center;top:1px;height:125px;width:200px;z-index:1;}
.primeareaGroup .primeareaLot .name a{background-color:rgba(0,0,0,0.25);color:#ffffff;display:block;font-size:16px;font-weight:700;line-height:16px;padding:5px 8px;text-decoration:none;text-shadow:0 1px 2px #000000;transition-duration:0.2s;height:115px;width:184px;}
.primeareaGroup .primeareaLot:hover .name a{background-color:rgba(0,0,0,0.1);color:#B8EEFF;}
.primeareaGroup .primeareaLot .price{bottom:1px;right:50%;margin-right:-100px;position:absolute;z-index:2;}
.primeareaGroup .primeareaLot .price a{background-color:#BD1717;border-left:1px solid #C2C2C2;border-top:1px solid #C2C2C2;color:#ffffff;display:block;font-size:15px;font-weight:700;padding:5px;text-decoration:none;}
	{{ENDCASE:style}}
	{{CASE:body}}
<div class="primeareaGroup">
	{{FOR:list}}<div class="primeareaLot">
		<div class="img"><img src="{imgUrl}"></div>
		<div class="name"><a target="_blank" href="{url}">{name}</a></div>
		<div class="price"><a target="_blank" href="{url}">{price}</a></div>
	</div>
	{{ENDFOR:list}}
</div>
	{{ENDCASE:body}}
{{ENDSWITCH:html}}