<?php
	class bookkeeping{
		public static function page(){
			global $user, $bd;

			$tpl = new templating(file_get_contents(TPL_DIR.'bookkeeping.tpl'));
			
			$request = $bd->read("SELECT `value` FROM `setting` WHERE `ids` IN (2,3,4,5,6,7,9,10,11,12,15,16,18,19)  ORDER BY id");
			$percent = mysql_result($request,0,0); 
			$time = mysql_result($request,1,0); 
			$graphic_ad = mysql_result($request,2,0); 
			$text_ad = mysql_result($request,3,0); 
			$wmx2_percent = mysql_result($request,4,0); 
			$wmx2_checked = mysql_result($request,5,0) ? 'checked' : '';
			$yandex_autopayments_fee = mysql_result($request,11,0); 
			$yandex_autopayments_checked = mysql_result($request,10,0) ? 'checked' : '';
			$qiwi_autopayments_fee = mysql_result($request,13,0);
			$qiwi_autopayments_checked = mysql_result($request,12,0) ? 'checked' : '';
			$rate_moder = mysql_result($request,6,0); 
			$paypal_fee_percent = mysql_result($request,7,0); 
			$paypal_fee_val = mysql_result($request,8,0); 
			$priv_settings = mysql_result($request,9,0);
			if($priv_settings){
				$priv_settings = unserialize($priv_settings);
				foreach ($priv_settings as $key => $value) {
					$tpl->content = str_replace("{".$key."}", $value, $tpl->content);
				}
			}

            $request = $bd->read("SELECT `fee` FROM `pay_method` WHERE id = 37");
            $qiwi_fee_percent = mysql_result($request,0,0);

            $tpl->content = str_replace("{fee}", $percent, $tpl->content);
			$tpl->content = str_replace("{time}", $time, $tpl->content);
			$tpl->content = str_replace("{graphic_ad}", $graphic_ad, $tpl->content);
			$tpl->content = str_replace("{text_ad}", $text_ad, $tpl->content);
			$tpl->content = str_replace("{wmx2_fee}", $wmx2_percent, $tpl->content);
			$tpl->content = str_replace("{wmx2_checked}", $wmx2_checked, $tpl->content);
			$tpl->content = str_replace("{yandex_autopayments_fee}", $yandex_autopayments_fee, $tpl->content);
			$tpl->content = str_replace("{yandex_autopayments_checked}", $yandex_autopayments_checked, $tpl->content);
			$tpl->content = str_replace("{qiwi_autopayments_fee}", $qiwi_autopayments_fee, $tpl->content);
			$tpl->content = str_replace("{qiwi_autopayments_checked}", $qiwi_autopayments_checked, $tpl->content);
			$tpl->content = str_replace("{rate_moder}", $rate_moder, $tpl->content);
			$tpl->content = str_replace("{paypal_fee_percent}", $paypal_fee_percent, $tpl->content);
			$tpl->content = str_replace("{paypal_fee_val}", $paypal_fee_val, $tpl->content);
			$tpl->content = str_replace("{qiwi_fee_percent}", $qiwi_fee_percent, $tpl->content);

			$nomoder = $tpl->ify('nomoder');

			if($user->role == 'moder'){
				$tpl->content = str_replace($nomoder['orig'], $nomoder['else'], $tpl->content);
			}else{
				$tpl->content = str_replace($nomoder['orig'], $nomoder['if'], $tpl->content);
			}
			
			return(array('content' => $tpl->content));
		}
	}
?>
