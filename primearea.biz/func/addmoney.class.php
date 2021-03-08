<?php
	class addmoney{
		public static function page(){
			global $user,$CONFIG,$siteAddr;

			$tpl = new templating(file_get_contents(TPL_DIR.'addmoney.tpl'));

			$returnUrl = "{$CONFIG['site_address']}panel/cabinet/";
			$tpl->content = str_replace("{primePayerShopId}", $CONFIG['primePayerAddMoney']['id'], $tpl->content);
			$tpl->content = str_replace("{primePayerSuccess}", $returnUrl, $tpl->content);

			return(array('content' => $tpl->content));
		}

		public function isAmountValid($orderId, $amount)
        {
            global $bd;
            $request = $bd->read("SELECT money FROM `addmoney` WHERE id = ".$orderId);

            return mysql_result($request,0,0) === $amount;
        }
	}
