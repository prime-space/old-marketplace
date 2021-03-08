<?php
	class blacklist{
		public static function page(){
			global $bd, $user;
			
			$tpl = new templating(file_get_contents(TPL_DIR.'blacklist.tpl'));
			
			$request = $bd->read("	SELECT * 
									FROM (
										SELECT bl.id AS blacklist_id, bl.email AS blacklist_email, o.id AS order_id
										FROM blacklist bl
										LEFT JOIN  `order` o ON o.user_id = 3 AND bl.email = o.userIdEmail
										WHERE 
											bl.user_id = ".$user->id."
											AND (
													o.id IS NULL
													OR (
															o.status =  'review'
														OR 	o.status =  'sended'
														OR 	o.status =  'paid'
													)
											)
										ORDER BY o.date DESC 
										LIMIT 100
									) AS a
									GROUP BY a.blacklist_email");
			
			$noblacklist = $tpl->ify('NOBLACKLIST');
			if(!$bd->rows)$tpl->content = str_replace($noblacklist['orig'], $noblacklist['else'], $tpl->content);
			else{
					$tpl->content = str_replace($noblacklist['orig'], $noblacklist['if'], $tpl->content);
					$tpl->fory('BLACKLIST');
					for($i=0;$i<$bd->rows;$i++){
						$info = mysql_result($request, $i, 2) ? '<a target="_blank" href="/panel/cabinet/'.mysql_result($request, $i, 2).'/">подробнее</a>' : '';
						$tpl->fory_cycle(array(	'email' => mysql_result($request, $i, 1),
												'blacklist_id' => mysql_result($request, $i, 0),
												'info' => $info,
												'color' => $i&1==1 ? 'a' : 'b'));
					}
					$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);	
			}
			return(array('content' => $tpl->content));
		}
	}
?>