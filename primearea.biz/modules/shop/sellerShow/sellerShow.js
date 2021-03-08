/*var sellerShow_list = "product";
function sellerShow_ready(){
	document.getElementById('sortListSel').style.visibility = "visible";
	document.getElementById('currentListSel').style.visibility = "visible";
	document.getElementById('sortListSel').options[4].selected = 'true';
	seller_product_list.get(0, false, true);
	
}
var seller_product_list = {
	pagination: 0,
	get: function(list_number, method, newly){
		if(newly)this.pagination = new Pagination('seller_product_list', 'modules/shop/sellerShow/getListProduct.php', 'sellerShow_list', 10);
		var data = {sort: document.getElementById('sortListSel').value,
					user_id: urlVar("sellerid", "get"),
					method: method};
		this.pagination.get(data, list_number, method);
	}
};
var seller_review_list = {
	pagination: 0,
	get: function(list_number, method, newly){
		if(newly)this.pagination = new Pagination('seller_review_list', 'modules/shop/sellerShow/getListReview.php', 'sellerShow_list', 10);
		var data = {user_id: urlVar("sellerid", "get"),
					method: method};
		this.pagination.get(data, list_number, method);
	}
};
function sellerShow_getListReview(){
	var listdiv = document.getElementById('sellerShow_list');
	if(!listdiv)return;
	
	sellerShow_list = "review";
	if(productListPage == 0)listdiv.innerHTML = "";
	
	var res = ajaxxx('modules/shop/sellerShow/getListReview.php','&crypt='+getCookie("crypt")+"&listPage="+productListPage+"&userid="+urlVar("sellerid", "get"));
	listdiv.innerHTML += res;
}*/