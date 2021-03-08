<?php
	class cabinet{
		public static function page(){
			global $bd, $db, $user, $construction;

			$tpl = new templating(file_get_contents(TPL_DIR.'cabinet/cabinet.tpl'));

			$tpl->ify('merchant', $_COOKIE['panel_mode'] === 'merchant' ? 1 : 2);

            $wishesNum = $db->super_query_value("SELECT COUNT(*) FROM `wish` WHERE `userId` = ".$user->id);
            $tpl->set('{expandWishes}', $wishesNum > 0 ? '' : 'in');

			//текущий балланс
			$request = $db->super_query("SELECT `wmrBal` FROM `user` WHERE `id` = '".$user->id."' LIMIT 1");
			$wmrBal = $request['wmrBal'];
			$tpl->set('{wmr}', $wmrBal);

			//На удержании
			$request = $db->super_query("SELECT SUM(amount) as amount FROM `transactions` WHERE `user_id` = '".$user->id."' AND (`method` = 'sale' OR `method` = 'merchant') AND `executed` IS NULL ");
			$sum_left = $request['amount'];
			!$sum_left ? $sum_left = '0' : '';
			$tpl->set('{wmr_left}', $sum_left);
			
			if($_COOKIE['panel_mode'] == 'merchant'){

				if($_COOKIE['mshop_id']){
					$curr_shop = $_COOKIE['mshop_id'];
				}else{
					$request = $db->super_query("SELECT id FROM mshops WHERE userId = ".$user->id." ORDER BY id DESC LIMIT 1");
					$curr_shop = $request['id'];
				}

				$more_operations = '/merchant/shop/'.$curr_shop.'/#anchor'; 
			}else{
				$more_operations = '/panel/sales/';
			}
			
			$tpl->set('{more_operations}', $more_operations);

			return array('content' => $tpl->content);
		}
	}
