<?php
	class productedit{
		public static function page(){
			global $bd, $user, $construction;
			
			$product_id = (int)$_GET['id'];
			
			$construction->jsconfig['productedit']['id'] = $product_id;
			
			$return = array(
				'content' => '',
				'title' => ''
			);
			$request = $bd->read("SELECT idUser, name, `group`, many, price, curr, promocode_id, descript, info, typeObject, picture, partner FROM product WHERE `id` = ".$product_id." LIMIT 1");
			$idUser = mysql_result($request,0,0);
			$name = mysql_result($request,0,1);
			$groupNum = mysql_result($request,0,2);
			$many = mysql_result($request,0,3);
			$price = mysql_result($request,0,4);
			$curr = mysql_result($request,0,5);
			$promocode_id = mysql_result($request,0,6);
			$descript = str_replace("&lt;br /&gt;", "", mysql_result($request,0,7));
			$info = str_replace("&lt;br /&gt;", "", mysql_result($request,0,8));
			$typeObject = mysql_result($request,0,9);
			$picture = mysql_result($request,0,10);	
			$partner = mysql_result($request,0,11);	
			
			$info = shop::url_replace_out($info);
			$descript = shop::url_replace_out($descript);
			
			if($idUser !== $user->id && $user->role !== 'admin')return(array('content' => 'Ошибка доступа'));
			
			$tpl = new templating(file_get_contents(TPL_DIR.'productedit.tpl'));

			if($picture != 0){
				$request = $bd->read("SELECT name, path FROM picture WHERE `id` = ".$picture." LIMIT 1");
				$picture_address = mysql_result($request,0,0) ? mysql_result($request,0,1).mysql_result($request,0,0) : '/picture/'.mysql_result($request,$i,1).'recommended.jpg';
				$picture = $picture.':'.$picture_address;
			}
		   $groupArray = json_decode(subGroupGet_new($groupNum), true);
		   $iend = $groupArray['numSub'];
		   for($i=($iend-1);$i>=0;$i--){
			  $group[$i] = '<select id="group'.$i.'" onchange="groupChange(this.value, '.$i.')">';
			  $group[$i] .= subGroupGett($groupNum);
			  $group[$i] .= '</select>';
			  $request = $bd->read("SELECT `subgroup` FROM `productgroup` WHERE `id` = ".$groupNum);
			  if(mysql_num_rows($request) == 0){//удаленная категория
				$group = "";
				$iend = 1;
				$group[0] = '<select id="group0" onchange="groupChange(this.value, 0)">';
				$group[0] .= subGroupGett(0);
				$group[0] .= '</select>';		
				break;
			  }
			  $groupNum = mysql_result($request,0,0);
		   }
		   $groupOut="";
		   for($i=0;$i<count($group);$i++){
			  $groupOut .= $group[$i];
		   }
		   if($iend<3)$groupOut .= '<select onchange="groupChange(this.value, 1)" id="group1" style="display:none;"></select>';
		   if($iend<2)$groupOut .= '<select onchange="groupChange(this.value, 2)" id="group2" style="display:none;"></select>';
		  
		   $currOut = "";
		   $currList = array("", "USD", "ГРН", "EUR", "РУБ");
		   for($i=1;$i<5;$i++){
			  if($curr == $i)$currTrue = "selected=\"selected\"";
			  else $currTrue = "";
			  $currOut .= "<option ".$currTrue." value=\"".$i."\">".$currList[$i]."</option>";
		   } 
		   
		   if($many == 1){
			   $many = <<<HTML
				Универсальный <i>(продается неоднократно)</i> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Универсальный — загружается в одном экземпляре, а продается бесконечное число раз. Типичные примеры универсальных товаров: программа, электронная книга, база данных. Так, чтобы продать электронную книгу 100 раз, достаточно загрузить ее  лишь единожды.">?</span>
HTML;

		   } else {
			   $many = <<<HTML
				Уникальный <i>(продается 1 раз)</i> <span class="span_info span_watch" data-toggle="tooltip" data-placement="top" data-original-title="Уникальный — каждый экземпляр которого продается только один раз, без возможности тиражирования: ПИН-код, пароль, реквизиты доступа к чему-либо и т.д. При этом, чтобы продать 100 пин-кодов пополнения мобильного телефона, вам понадобится загрузить все 100 кодов, и каждый покупатель получит свой уникальный код.">?</span>
HTML;

		   }
			 
		   if($typeObject == 1)$typeObject = "<p>Текстовая информация</p>";
		   else $typeObject = "<p>Файл</p>";
			
			
			$request = $bd->read("SELECT id, name FROM promocodes WHERE user_id = ".$user->id." LIMIT 100");
			$nopromocodes = $tpl->ify('NOPROMOCODES');
			if(!$bd->rows)$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['if'], $tpl->content);
			else{
				$tpl->content = str_replace($nopromocodes['orig'], $nopromocodes['else'], $tpl->content);
				$tpl->fory('PROMOCODES');
				for($i=0;$i<$bd->rows;$i++){
					$tpl->fory_cycle(array(	
						'promocode_id' => mysql_result($request,$i,0),
						'name' => mysql_result($request,$i,1),
						'selected' => mysql_result($request,$i,0) == $promocode_id ? 'selected' : ''
					));
				}
				$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
				
				$request = $bd->read("SELECT id FROM promocodes WHERE user_id = ".$user->id." AND id = ".$promocode_id." LIMIT 1");
				$tpl->content = str_replace("{checked}", $bd->rows ? 'checked' : '', $tpl->content);
			}
			
			$tpl->content = str_replace("{group}", $groupOut, $tpl->content);
			$tpl->content = str_replace("{many}", $many, $tpl->content);
			$tpl->content = str_replace("{typeObject}", $typeObject, $tpl->content);
			$tpl->content = str_replace("{name}", $name, $tpl->content);
			$tpl->content = str_replace("{price}", $price, $tpl->content);
			$tpl->content = str_replace("{info}", $info, $tpl->content);
			$tpl->content = str_replace("{descript}", $descript, $tpl->content);
			$tpl->content = str_replace("{curr}", $currOut, $tpl->content);
			$tpl->content = str_replace("{picture}", $picture, $tpl->content);
			$tpl->content = str_replace("{partner}", $partner, $tpl->content);

			
			$return['content'] = $tpl->content;
			$return['title'] = 'PRIMEAREA.RU - Редактирование товара - '.$name;
				
			return $return;
		}
	}
?>