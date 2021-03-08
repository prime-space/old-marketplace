var category = new Array(0,0);
function categoryShow(id, deep){
	if(urlVar('p','get') != undefined && urlVar('p','get') != 'main')changePage('');
	nowcategory = 0;
	document.getElementById('sortListSel').options[0].selected = 'true';
	if(id == 0){
		//console.log(category);
		if(category[1] != 0){
		   document.getElementById("categoryDiv"+category[1]).style.display = "none";
		   category[1] = 0;			
		}
		return;
	}
	if(category[deep] == id){
		document.getElementById("categoryDiv"+id).style.display = "none";
		category[deep] = 0;
		product_list.get(0, false, true);
		user_recommended.show_recommended();
		return;
	}
	if(category[deep] != 0){
		document.getElementById("categoryDiv"+category[deep]).style.display = "none";
		product_list.get(0, false, true);
		user_recommended.show_recommended();
	}
	category[deep] = id;
	document.getElementById("categoryDiv"+id).style.display = "block";
}
function categoryChange(id){
	if(urlVar('p','get') != undefined && urlVar('p','get') != 'main')changePage('');
  nowcategory = id;
  document.getElementById('sortListSel').options[4].selected = 'true';
  product_list.get(0, false, true);
  user_recommended.show_recommended();
  scroll(0,0);
}