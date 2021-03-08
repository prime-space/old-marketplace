<?php
	class recommended{
		public static function page(){
			global $bd, $user;
			$order = new order;

			$tpl = new templating(file_get_contents(TPL_DIR.'recommended.tpl'));
			
			$request = $bd->read("
				SELECT id, name
				FROM product 
				WHERE
					hidden = 0 AND
					idUser = '".$user->id."' AND
					block  = 'ok' AND 
					(moderation = 'ok' OR moderation IS NULL)
				ORDER BY id DESC
			");
			
			$tpl->fory('PRODUCT_SELECT');
			$c = $bd->rows;
			for($i=0;$i<$c;$i++){
				if($order->product_availability(mysql_result($request,$i,0))){
					$tpl->fory_cycle(array(
						'id' => mysql_result($request,$i,0),
						'name' => mysql_result($request,$i,1)
					));
				}
				
			}
			
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);

			$request = $bd->read("SELECT value FROM setting WHERE ids IN (4,5,12) ORDER BY id LIMIT 3");
			$tpl->content = str_replace('{price_day}', mysql_result($request,0,0), $tpl->content);
			$tpl->content = str_replace('{price_click}', mysql_result($request,1,0), $tpl->content);

			$priv_settings = mysql_result($request,2,0);
			if($priv_settings){
				$priv_settings = unserialize($priv_settings);
			
				foreach ($priv_settings as $key => $value) {
					if($key == 'priv_amount_3'){
						$amount_3_real = $value;
						$value = $value - 1;
						$tpl->content = str_replace("{amount_3_real}", $amount_3_real, $tpl->content);
					}
					$tpl->content = str_replace("{".$key."}", $value, $tpl->content);
				}

				$ups = $user->up_interval($priv_settings['up_interval_'.$user->priv_type()], $priv_settings['up_count_'.$user->priv_type()]);
				$ups = (int)$priv_settings['up_count_'.$user->priv_type()] - (int)$ups;

				$tpl->content = str_replace("{up_count}", $ups, $tpl->content);
			}
			if($user->active_privileges() && $user->priv_type() != 1){
				$tpl->set('{active_priv}', 'block');
			}else{
				$tpl->set('{active_priv}', 'none');
			}

			if($user->active_privileges()){
				$tpl->content = str_replace("{pro}", 'PRO SELLER'.'<img width="20px" height="20px" src="/style/img/man2.jpg">', $tpl->content);

				$tpl->content = str_replace("{priv_text_1}", '<h2>Тариф</h2><div class="hidden-xs" style="background: black;position: absolute;color: white;padding: 5px;margin-top: -30px;width: 120px;font-size: 12px;border-radius: 3px;">PRO SELLER</div>', $tpl->content);
				$tpl->content = str_replace("{priv_text_2}", 'На Ваших товарах значок и надпись PRO SELLER.', $tpl->content);
				$tpl->content = str_replace("{priv_text_3}", 'Товар выставляется без модерации.', $tpl->content);

				if($user->priv_type() == 3 || $user->priv_type() == 4){
					$tpl->content = str_replace("{priv_text_4}", "Удержание средств системой - ".$priv_settings['com_day_'.$user->priv_type()]."ч.", $tpl->content);
				}else{
					$tpl->content = str_replace("{priv_text_4}", 'Удержание средств системой - 85ч.', $tpl->content);
				}
				
				if($user->priv_type() == 4){
					$tpl->content = str_replace("{priv_text_5}", "Комиссия системы - ".$priv_settings['system_com_'.$user->priv_type()]."%.", $tpl->content);
				}else{
					$tpl->content = str_replace("{priv_text_5}", 'Комиссия системы - 6%.', $tpl->content);
				}
				
				
			}else{
				$tpl->content = str_replace("{pro}", 'UNKNOWN SELLER'.'<img width="20px" height="20px" src="/style/img/man1.jpg">', $tpl->content);
				
				$tpl->content = str_replace("{priv_text_1}", '<h2>Тариф по умолчанию</h2><div class="hidden-xs" style="background: black;position: absolute;color: white;padding: 5px;margin-top: -30px;width: 120px;font-size: 12px;border-radius: 3px;">UNKNOWN SELLER</div>', $tpl->content);
				$tpl->content = str_replace("{priv_text_2}", 'Ваши товары без значка и надписи PRO SELLER', $tpl->content);
				$tpl->content = str_replace("{priv_text_3}", 'Товар выставляется после проверки модератором.', $tpl->content);
				$tpl->content = str_replace("{priv_text_4}", 'Удержание средств системой - 72ч.', $tpl->content);
				$tpl->content = str_replace("{priv_text_5}", 'Комиссия системы - 6%.', $tpl->content);
			}

			$tpl->content = str_replace('{wmrbal}', $user->wmrbal, $tpl->content);
			$tpl->content = str_replace('{priv}', $user->priv_type(), $tpl->content);
			$tpl->content = str_replace('{until}', $user->priv_until(), $tpl->content);
			
			return(array('content' => $tpl->content));
		}
	}
