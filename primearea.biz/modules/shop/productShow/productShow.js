function showproductReady(){
           document.getElementById('currentListSel').style.visibility = "visible";	
           var result = ajaxxx('modules/shop/productShow/productShow.php', '&id=' + urlVar('productid', 'get'));
		   //console.log(result);
		   try{result = JSON.parse(result);}catch (e){console.log('Ошибка'); return false;}
		   document.getElementById("page").innerHTML = result.content;
		   document.title = result.title;
           showproduct_review_list.get(0, false, true);
		   get_share_button();
}
function productBuy(event){
	var res = ajaxxx("modules/shop/productShow/func/productAvailability.php", "&id=" + urlVar("productid", "get"));
	//alert(res);
	if(res == '0'){
		alert("Товара нет в наличии");
		return;
	}
	if(event == 'first'){
		document.getElementById("productButFirst").style.display = "none";
		document.getElementById("productButSecond").style.display = "block";
	}
	if(event == 'second'){
       if(checkRegex(document.getElementById("clientEmail").value, "email"))alert("Неверный формат email");
	   else{
	   	  var resTwo;
	   	  $.when(resTwo = ajaxxx("modules/shop/productShow/func/orderNew.php", "&crypt=" + getCookie("crypt") + "&email=" + document.getElementById("clientEmail").value + "&productId=" + urlVar("productid", "get"))).done(function(){
	   	     resTwo = resTwo.split(';');
			 document.getElementById("LMI_PAYMENT_NO").value = resTwo[0];
			 document.getElementById("LMI_PAYMENT_AMOUNT").value = resTwo[1];
			 document.getElementById("productButSecond").style.display = "none";
	   	     document.getElementById("productBuyAnswer").style.display = "block";
		     document.getElementById("productBuyForm").submit();          
		  });
	   }
	}
}