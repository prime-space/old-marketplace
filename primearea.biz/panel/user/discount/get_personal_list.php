<?php
	include "../../../func/config.php";
	include "../../../func/mysql.php";
	include "../../../func/main.php";
  
	$bd = new mysql();
	$user = new user();  

	if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close(json_encode(array('status' => 'error', 'content' => 'Ошибка доступа')));

	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/discount/personal_list.tpl"));
	
	$request = $bd->read("SELECT id, email, type, percent, money FROM discount_personal WHERE user_id = ".$user->id." LIMIT 500");
	
	$nolist = $tpl->ify('NOLIST');
	if(!$bd->rows)$tpl->content = str_replace($nolist['orig'], $nolist['else'], $tpl->content);
	else{
		$tpl->content = str_replace($nolist['orig'], $nolist['if'], $tpl->content);
		$tpl->fory('LIST');
		for($i=0;$i<$bd->rows;$i++){
			$type = mysql_result($request,$i,2);
			$percent = $type == 'percent' ? mysql_result($request,$i,3).'%' : NULL;
			$money = $type == 'money' ? mysql_result($request,$i,4).'руб.' : NULL;

			if ( $percent ) {
				$percent = '<span class="span_green_bg">' . $percent . '</psan>';
			}
			if ( $money ) {
				$money = '<span class="span_blue_bg">' . $money . '</psan>';
			}
			$tpl->fory_cycle(array(	'id' => mysql_result($request,$i,0),
									'email' => mysql_result($request,$i,1),
									'percent' => $percent,
									'money' => $money
			));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
	}
	
	close(json_encode(array('status' => 'ok', 'content' => $tpl->content)));
?>