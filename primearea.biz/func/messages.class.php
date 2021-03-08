<?php
	class messages{
		public function numMessages($to){
			global $db;
			
			return $db->super_query_value("SELECT COUNT(id) FROM help WHERE see = 0 AND `to` = '".$to."'");
		}
		public static function page(){
			global $bd, $user, $construction;
			
			if(in_array($user->role, array('admin', 'moder'))){

				if($user->role === 'moder' && !$user->checkModerRight('messages')){
					$modering = false;
				}else{
					$modering = true;
				}

			}else{
				$modering = false;
			}

			if($_GET['method'] === 'view'){

				$id = (int)$_GET['id'];
				
				$construction->jsconfig['messages']['topic'] = $id;
				
				$request = $bd->read("
					SELECT h.title, h.text, h.date, h.to, h.from, u.login
					FROM help h 
					LEFT JOIN user u ON u.id = h.from
					WHERE h.topic = '".$id."'
					ORDER BY h.date DESC
				");
					
				if(!$bd->rows)return(array('content' => 'Тема не найдена'));

				$title = mysql_result($request,($bd->rows-1),0);
				$to = mysql_result($request,($bd->rows-1),3);
				$from = mysql_result($request,($bd->rows-1),4);

				if(!($user->id == $to || $user->id == $from || $modering))return(array('content' => 'Тема не найдена'));

				$messages = '';
				for($i=0;$i<$bd->rows;$i++){
					$text = strBaseOut(mysql_result($request,$i,1));
					$date = mysql_result($request,$i,2);
					$messageAuthor = mysql_result($request,$i,5) ? mysql_result($request,$i,5) : 'Поддержка';

					$date = mysql_result($request,$i,2);

					$date_strtotime = strtotime( $date );
					$date = @date( "Y-m-d", $date_strtotime );
					$time = @date( "H:i:s", $date_strtotime );

					$messages .= <<<HTML
						<div class="middle_lot_o_one middle_lot_o_one_def">
							<div class="middle_lot_o_t1"><i class="sprite sprite_lot_flag sprite_msg_user"></i> Автор: {$messageAuthor}</div>
							<div class="middle_lot_o_t2">{$text}</div>
							<div class="middle_lot_o_t3"><i class="sprite sprite_otz_date"></i> {$date} <i class="sprite sprite_otz_time"></i> {$time}</div>
						</div>
HTML;

				}
				
				$adminOR = $modering ? '=' : '!=';
				$bd->write("UPDATE `help` SET `see` = 1 WHERE `topic` = ".$id." AND `to` ".$adminOR." 0");

				$tpl = new templating(file_get_contents(TPL_DIR.'messageview.tpl'));
				$tpl->content = str_replace("{messages}", $messages, $tpl->content);
				$tpl->content = str_replace("{title}", $title, $tpl->content);

				return(array('content' => $tpl->content));
			}
			
			$construction->jsconfig['messages']['to'] = $_GET['to'] ? $_GET['to'] : 0;
			
			$tplnew = new templating(file_get_contents(TPL_DIR.'messagenew.tpl'));
			
			if($modering && !$_GET['to'])$tplnew->content = 'Для создания темы выберите отправителя в разделе "Пользователи"';
			
			if($modering){
				$to_id = (int)$_GET['to'];
				$request = $bd->read("SELECT login FROM user WHERE id = '".$to_id."' LIMIT 1");
				if($bd->rows)$to = mysql_result($request,0,0);
			}else $to = "Администратору";
			$tplnew->content = str_replace("{to}", $to, $tplnew->content);
			

			$tpl = new templating(file_get_contents(TPL_DIR.'messages.tpl'));
			
			$tpl->content = $tplnew->content.$tpl->content;
			
			return(array('content' => $tpl->content));
		}

		public function getRecipient($user){
			if(in_array($user->role, array('admin', 'moder'))){
		
				if($user->role == 'moder'){

					if($user->checkModerRight('messages')){
						$to = 0;
					}else{
						$to = $user->id;
					}

				}else{
					$to = 0;
				}

			}else{
				$to = $user->id;
			}

			return $to;
		}
	}
?>