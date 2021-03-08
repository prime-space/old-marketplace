<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/db.class.php";
	include "../../../func/main.php";

	$bd = new mysql();
	$db = new db();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "admin"))close(json_encode(array('status' => 'error', 'message' => 'Ошибка доступа')));
	
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	else close('{"status": "error", "message": "Нет данных"}');
	
	if($data['token'] !== $user->token)close('{"status": "error", "message": "Ошибка доступа"}');


	$user_id = (int)$data['user_id'];


	$request = $db->super_query("SELECT `moder_rights` FROM `user` WHERE `id` = ".$user_id);
	
	$rights =  $request['moder_rights'];
	if(!$rights){

		$content = <<<HTML

		<form class="moderRightsPopup" onsubmit="panel.moder.newusers.moder.rightsSet({$user_id}, this); return false;">
			<table class="table table-striped table_page table_page_input1">
				<thead>
					<tr>
						<td>Название</td>
						<td>url</td>
						<td class="text_align_c" style="width: 55px;">Задействован</td>
					</tr>
				</thead>
				<tbody>
				
					<tr>
						<td class="padding_10 vertical_align">Заказы</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/order/">https://primearea.biz/panel/order/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label ><input type="checkbox" name="order" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Бухгалтерия</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/bookkeeping/">https://primearea.biz/panel/bookkeeping/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label ><input type="checkbox" name="bookkeeping" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Сообщения</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/messages/">https://primearea.biz/panel/messages/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label ><input type="checkbox" name="messages" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Модерация</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/moderate/">https://primearea.biz/panel/moderate/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="moderate" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Пользователи</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/newusers/">https://primearea.biz/panel/newusers/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="newusers" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Пользователи (статисктика)</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/newusers/">https://primearea.biz/panel/newusers/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="newusers_stat" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Просмотр сообщений</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/messageview/">https://primearea.biz/panel/messageview/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="messageview" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Удаление отзывов</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/reviewdelete/">https://primearea.biz/panel/reviewdelete/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="reviewdelete" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Заказы</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/merchant/admin/orders/">https://primearea.biz/merchant/admin/orders/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="merchant_orders" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Магазины</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/merchant/admin/">https://primearea.biz/merchant/admin/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="merchant_admin" ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Скрытые товары</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/hiddenproducts/">https://primearea.biz/panel/hiddenproducts/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="panel_hiddenproducts" ></label>
						</td>
					</tr>
				
					<tr>
						<td class="padding_10 vertical_align">Скрытые товары</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/hiddenproducts/">https://primearea.biz/panel/hiddenproducts/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="panel_hiddenproducts" ></label>
						</td>
					</tr>

					<tr>
						<td colspan="2" class="padding_10 text_align_r">
							<div data-name="info" id="info" style="text-align: center;"></div>
							<button class="btn btn-success btn-sm" name="button">Сохранить</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
HTML;

	}else{

		$rights = explode(',', $rights);
		$rights = array_flip($rights);
		
		if(isset($rights['order'])){
			$_order = true;
			$_order_ch = 'checked';
		}
		if(isset($rights['bookkeeping'])){
			$_bookkeeping = true;
			$_bookkeeping_ch = 'checked';
		}
		if(isset($rights['messages'])){
			$_messages = true;
			$_messages_ch = 'checked';
		}
		if(isset($rights['moderate'])){
			$_moderate = true;
			$_moderate_ch = 'checked';
		}
		if(isset($rights['newusers'])){
			$_newusers = true;
			$_newusers_ch = 'checked';
		}
		if(isset($rights['newusers_stat'])){
			$_newusers_stat = true;
			$_newusers_stat_ch = 'checked';
		}
		if(isset($rights['messageview'])){
			$_messageview = true;
			$_messageview_ch = 'checked';
		}
		if(isset($rights['reviewdelete'])){
			$_reviewdelete = true;
			$_reviewdelete_ch = 'checked';
		}
		if(isset($rights['merchant_orders'])){
			$_merchant_orders = true;
			$_merchant_orders_ch = 'checked';
		}
		if(isset($rights['merchant_admin'])){
			$_merchant_admin = true;
			$_merchant_admin_ch = 'checked';
		}
		if(isset($rights['panel_hiddenproducts'])){
			$_panel_hiddenproducts = true;
			$_panel_hiddenproducts_ch = 'checked';
		}


		$content = <<<HTML

		<form class="moderRightsPopup" onsubmit="panel.moder.newusers.moder.rightsSet({$user_id}, this); return false;">
			<table class="table table-striped table_page table_page_input1">
				<thead>
					<tr>
						<td>Название</td>
						<td>url</td>
						<td class="text_align_c" style="width: 55px;">Задействован</td>
					</tr>
				</thead>
				<tbody>
				
					<tr>
						<td class="padding_10 vertical_align">Заказы</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/order/">https://primearea.biz/panel/order/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="order" value="{$_order}" {$_order_ch} ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Бухгалтерия</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/bookkeeping/">https://primearea.biz/panel/bookkeeping/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="bookkeeping"  value="{$_bookkeeping}"  {$_bookkeeping_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Сообщения</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/messages/">https://primearea.biz/panel/messages/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="messages"  value="{$_messages}" {$_messages_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Модерация</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/moderate/">https://primearea.biz/panel/moderate/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="moderate"  value="{$_moderate}" {$_moderate_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Пользователи</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/newusers/">https://primearea.biz/panel/newusers/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="newusers"  value="{$_newusers}" {$_newusers_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Пользователи (статисктика)</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/newusers/">https://primearea.biz/panel/newusers/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="newusers_stat" value="{$_newusers_stat}" {$_newusers_stat_ch} ></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Просмотр сообщений</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/messageview/">https://primearea.biz/panel/messageview/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="messageview" value="{$_messageview}"  {$_messageview_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Удаление отзывов</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/reviewdelete/">https://primearea.biz/panel/reviewdelete/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="reviewdelete"  value="{$_reviewdelete}" {$_reviewdelete_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Заказы</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/merchant/admin/orders/">https://primearea.biz/merchant/admin/orders/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="merchant_orders"  value="{$_merchant_orders}" {$_merchant_orders_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Магазины</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/merchant/admin/">https://primearea.biz/merchant/admin/</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label><input type="checkbox" name="merchant_admin"  value="{$_merchant_admin}"  {$_merchant_admin_ch}></label>
						</td>
					</tr>

					<tr>
						<td class="padding_10 vertical_align">Скрытые товары</td>
						<td class="padding_10 vertical_align"><a href="https://primearea.biz/panel/hiddenproducts">https://primearea.biz/panel/hiddenproducts</a></td>
						<td class="padding_10 text_align_c vertical_align">
							<label style="background: none;border: 1px solid #DBDBDB;padding: 5px 6px;"><input type="checkbox" name="panel_hiddenproducts"  value="{$_panel_hiddenproducts}"  {$_panel_hiddenproducts_ch}></label>
						</td>
					</tr>
				
					<tr>
						<td colspan="2" class="padding_10 text_align_r">
							<div data-name="info" id="info" style="text-align: center;"></div>
							<button class="btn btn-success btn-sm" name="button">Сохранить</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
HTML;

	}



	close(json_encode(array('status' => 'ok', 'content' => $content)));

?>