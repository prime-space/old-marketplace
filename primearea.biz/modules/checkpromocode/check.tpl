{{IF:NOLIST}}
<div class="promo-warning">
   <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> По данному промокоду товары для покупки отсутствуют.</p>       
 </div>
{{ELSE:NOLIST}}
<div class="promo-warning">
   <p><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Используя введенный промокод, вы можете купить следующие товары со скидкой:</p>       
 </div>
   <div class="promocode-table">
 <table>
  <thead>
  <tr class="table-header">
   <th>Наименование</th>    
   <th>Цена</th>    
   <th>Скидка</th>    
   <th>Цена со скидкой</th>    
  </tr> 
  </thead>
		{{FOR:LIST}}
			  <tbody>
  <tr class="tr-table">
	  <td aria-label="Наименование" class="game-name"><a target="_blank" href="/product/{product_id}/">{name}</a></td>
	  <td aria-label="Цена" class="table-price">{price}</td>
	  <td aria-label="Скидка" class="date-name">{discount} %</td>
	  <td aria-label="Цена со скидкой" class="table-price">{lowprice}</td></tr>
  </tbody> 
 </table>  
		{{ENDFOR:LIST}}
	</div>
{{ENDIF:NOLIST}}
