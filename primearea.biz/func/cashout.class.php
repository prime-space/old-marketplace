<?php
class cashout{
	public static function page(){
		global $bd, $user, $CONFIG;

		$request = $bd->read("SELECT value FROM `setting` WHERE `id` = 19");
		$qiwiAutopaymentsFee = mysql_result($request,0,0);

		$request = $bd->read("SELECT `wmzBal`, `wmrBal`, `wmeBal`, `wmuBal`, `wmr` FROM `user` WHERE `id` = '".$user->id."' LIMIT 1");
		$wmzBal = mysql_result($request,0,0);
		$wmrBal = mysql_result($request,0,1);
		$wmeBal = mysql_result($request,0,2);
		$wmuBal = mysql_result($request,0,3);
		$wmr = mysql_result($request,0,4);

		$cashOutForm = '<input type="text" name="amount" maxlength="16" /> <button class="btn btn-success btn-sm" name="button">Перевести на кошелек</button>';

		$tpl = new templating(file_get_contents(TPL_DIR.'cashout.tpl'));
		$tpl->fory('CASHOUT_SYSTEMS');
		foreach($CONFIG['cashout'] as $cashout_system_key => $cashout_system) {
			if ($cashout_system['enabled']) {
                $tpl->fory_cycle([
                    'cashout_system_name' => $cashout_system['name'],
                    'cashout_system_key' => $cashout_system_key,
                ]);
            }
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
		$tpl->set('{wmz}', $wmzBal);
		$tpl->set('{wmr}', $wmrBal);
		$tpl->set('{wme}', $wmeBal);
		$tpl->set('{wmu}', $wmuBal);
		$tpl->set('{cashOutForm}', $cashOutForm);
		$tpl->set('{qiwi_autopayments_fee}', $qiwiAutopaymentsFee);

		$request = $bd->read("SELECT SUM(amount) FROM `transactions` WHERE `user_id` = '".$user->id."' AND (`method` = 'sale' OR `method` = 'merchant') AND `executed` IS NULL ");
		$sum_left = mysql_result($request,0,0);
		if(!$sum_left){
			$sum_left = '0';
		}
		$tpl->set('{wmr_left}', $sum_left);

		return(array('content' => $tpl->content));
	}
}
?>
