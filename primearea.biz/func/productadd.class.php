<?php
	class productadd{
		public static function page(){
			global $bd, $user;
			
			if($user->status == 'blocked')return(array('content' => 'Вы заблокированы'));
			
			$tpl = new templating(file_get_contents(TPL_DIR.'productadd.tpl'));
			
			$request = $bd->read("SELECT `id`, `name` FROM `productgroup` WHERE `subgroup` IS NULL ORDER BY `name`");
			$group = "";
			for($i=0;$i<$bd->rows;$i++){
				$name = mysql_result($request,$i,1);
				$group .= '<option value="'.mysql_result($request,$i,0).'">'.$name.'</option>';
			}

			$request = $bd->read("SELECT id, name FROM promocodes WHERE user_id = ".$user->id." LIMIT 100");
			$nopromocodes = $tpl->ify('NOPROMOCODES');
			if(!$bd->rows)$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['if'], $tpl->content);
			else{
				$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['else'], $tpl->content);
				$tpl->fory('PROMOCODES');
				for($i=0;$i<$bd->rows;$i++){
					$tpl->fory_cycle(array(	
						'promocode_id' => mysql_result($request,$i,0),
						'name' => mysql_result($request,$i,1)
					));
				}
				$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);				
			}
			
			
			$tpl->content = str_replace("{group}", $group, $tpl->content);			
			return(array('content' => $tpl->content));
		}
	}
?>