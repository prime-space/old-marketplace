var product_list = {
	pagination: 0,
	get: function(list_number, method, newly){
		if(newly)this.pagination = new Pagination('product_list', 'modules/shop/productList/func/productListShow.php', 'productList', 50);
		var data = {sort: document.getElementById('sortListSel_all_product').value,
					search: document.getElementById("search").value,
					cat: nowcategory,
					page: urlVar("p", "get")};
		this.pagination.get(data, list_number, method);
	}
};
function productListDel(id){
	if(confirm("Удалить товар "+id)){
		rez = ajaxxx('modules/shop/productProced/productDel.php', '&id=' + id + '&crypt=' + getCookie("crypt"));
		if(rez == true){
			popupOpen("Товар успешно удален");
			changePage('p','myShop');
		}else alert("Ошибка");
	}
}