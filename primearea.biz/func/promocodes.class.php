<?php
	class promocodes{
		public static function page(){
			global $bd, $user, $construction;
			
			$type_arr = array('', 'Однократное', 'Многократное', 'Не более ');
			
			if($_GET['a']  === 'add'){
				$construction->jsconfig['promocodes']['a'] = 'add';
				$tpl = new templating(file_get_contents(TPL_DIR.'promocodes_add.tpl'));
			}elseif($_GET['a']  === 'edit'){
				$tpl = new templating(file_get_contents(TPL_DIR.'promocodes_edit.tpl'));
				if(!$_GET['id'])return(array('content' => 'Недостаточно данных'));
				
				$promocode_id = (int)$_GET['id'];
				
				$construction->jsconfig['promocodes'] = array(
					'a' => 'edit', 
					'promocode_id' => $promocode_id
				);
				
				$request = $bd->read("	
					SELECT 	p.name, p.type, p.maxuse, p.datestart, p.dateend,
							(SELECT COUNT(id) FROM promocode_el WHERE promocode_id = p.id),
							(SELECT COUNT(id) FROM promocode_products WHERE promocode_id = p.id)
					FROM promocodes p
					WHERE 	p.user_id = ".$user->id." 
						AND p.id = ".$promocode_id."
					LIMIT 1
				");
				if(!$bd->rows)return(array('content' => 'Не найден или нет доступа'));
				
				$type = $type_arr[mysql_result($request,0,1)];
				if(mysql_result($request,0,1) == 3)$type .= mysql_result($request,0,2).' раз';
				$date = mysql_result($request,0,3).' - '.mysql_result($request,0,4);
				
				$tpl->content = str_replace('{name}', mysql_result($request,0,0), $tpl->content);
				$tpl->content = str_replace('{type}', $type, $tpl->content);
				$tpl->content = str_replace('{date}', $date, $tpl->content);
				$tpl->content = str_replace('{products_count}', mysql_result($request,0,6), $tpl->content);
				$tpl->content = str_replace('{promocodes_count}', mysql_result($request,0,5), $tpl->content);
				
			}else{
				$tpl = new templating(file_get_contents(TPL_DIR.'promocodes.tpl'));
				$request = $bd->read("	
					SELECT 	p.id, p.name, p.type, p.maxuse, p.datestart, p.dateend,
							(SELECT COUNT(id) FROM promocode_el WHERE promocode_id = p.id),
							(SELECT SUM(used) FROM promocode_el WHERE promocode_id = p.id),
							(SELECT COUNT(id) FROM promocode_products WHERE promocode_id = p.id)
					FROM promocodes p
					WHERE p.user_id = ".$user->id." 
					ORDER BY p.id DESC 
					LIMIT 100
				");
				
				$nolist = $tpl->ify('NOLIST');
				if(!$bd->rows)$tpl->content = str_replace($nolist['orig'], $nolist['else'], $tpl->content);
				else{
					$tpl->content = str_replace($nolist['orig'], $nolist['if'], $tpl->content);
					$tpl->fory('LIST');
					for($i=0;$i<$bd->rows;$i++){
						$date = mysql_result($request,$i,4).' - '.mysql_result($request,$i,5);
						$type = $type_arr[mysql_result($request,$i,2)];
						if(mysql_result($request,$i,2) == 3)$type .= mysql_result($request,$i,3).' раз';
						$tpl->fory_cycle(array(	
							'promocode_id' => mysql_result($request,$i,0),
							'name' => mysql_result($request,$i,1),
							'date' => $date,
							'count' => mysql_result($request,$i,6),
							'type' => $type,
							'sale' => mysql_result($request,$i,7),
							'product_count' => mysql_result($request,$i,8)
						));
					}
					$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
				}
			}
			return(array('content' => $tpl->content));
		}
		public function checkpage(){
			
			$tpl = new templating(file_get_contents(TPL_DIR.'checkpromocode.tpl'));
			$tpl->content = str_replace('{code}', $_GET['code'], $tpl->content);
			
			
			return $tpl->content;
		}
	}
?>