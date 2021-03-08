<?php

  include "../../../func/config.php";
  include "../../../func/mysql.php";
  include "../../../func/main.php";

	$bd = new mysql();
	$user = new user();
  
	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));

	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	$category_id = (int)$data['category_id'];
	$elementLoadInPage = $bd->prepare((int)$data['elements_on_page'], 8);

	$category = 'AND shopsite ' . (!$category_id ? '!= -1' : '= '.$category_id);
	
	$listPage = (int)$data['current_list'];
	if($listPage == 0)$elementLoadInPageStart = 0;
	else $elementLoadInPageStart = $listPage * $elementLoadInPage;  

	$select_request = $bd->read("SELECT id, name FROM shopsite_category WHERE user_id = ".$user->id." LIMIT 100");
	$select_rows = $bd->rows;
  
  	$request = $bd->read("	SELECT id
							FROM product 
							WHERE 	idUser = ".$user->id." 
								AND showing = 1
								".$category);
  	$rows_count = $bd->rows;
	$request = $bd->read("	SELECT id, name, shopsite
							FROM product 
							WHERE 	idUser = ".$user->id." 
								AND showing = 1
								".$category."
							ORDER BY id DESC limit ".$elementLoadInPageStart.", ".$elementLoadInPage);
	if(!$bd->rows)close(json_encode(array('status' => 'ok', 'content' => '')));
	$list = "";
  

	for($i=0; $i<$bd->rows; $i++){
		$id =  mysql_result($request,$i,0);
		$name =  strBaseOut(mysql_result($request,$i,1));
		$current_category_id =  mysql_result($request,$i,2);
	
		$select = '<select onchange="panel.user.shopsite.product.category_change('.$id.', this.value);" style="width:205px;"><option value="0">Без категории</option>';
		
		for($y=0;$y<$select_rows;$y++){
			$category_id = mysql_result($select_request,$y,0);
			$category_name = mysql_result($select_request,$y,1);
			$selected = $current_category_id == $category_id ? 'selected' : '';
			$select .= '<option value="'.$category_id.'" '.$selected.'>'.$category_name.'</option>';
		}
		
		$select .= "</select>";		

		$list .= <<<HTML
		<tr id="productShopsiteAddDelHide{$id}">
			<td class="padding_10 text_align_c vertical_align">{$id}</td>
			<td class="padding_10 vertical_align"><a href="/product/{$id}/" target="_blank">{$name}</a></td>
			<td style="width: 75px;" class="padding_10 text_align_c vertical_align">{$select}</td>
			<td style="width: 75px;" class="padding_10 text_align_c vertical_align">
				<button class="btn btn-danger btn-sm" onclick="panel.user.shopsite.product.move({$id},'del', this);return false;"><i class="icon-remove icon-white"></i> Убрать</button>
			</td>
		</tr>
HTML;

	}
	if( $data['method'] != 'add' ) {
		$pagination = pagination::getPanel($rows_count, $elementLoadInPage, $listPage, 'panel.user.shopsite.product.list.get');
		//$list .= '<div id="list_add_module.seller.productlist.get"></div>';
		$pagination = str_replace( 'colspan="6"', 'colspan="4"', $pagination );
		$list .= $pagination;
	}

	if( $data['method'] != 'add' ) {
      $list .= <<<HTML
			</tbody>
		</table>
HTML;

	}
close(json_encode(array('status' => 'ok', 'content' => $list)));
?>