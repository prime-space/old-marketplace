<?php
	class seller{
		public static function page(){
			global $construction, $bd, $user, $CONFIG;
			
			$userid = (int)$_GET['sellerid'];
			
			$construction->jsconfig['seller']['id'] = $userid;
			
			$request = $bd->read("SELECT u.login, u.date, u.rating, u.fio, u.phone, u.skype, u.site, u.wm, 
						   (SELECT COUNT(o.id) FROM `order` o 
							INNER JOIN product p
							ON p.id = o.productId
							WHERE p.idUser = ".$userid." AND (o.status = 'sended' OR o.status = 'review')), p.path, u.id
					FROM `user` u
                    LEFT JOIN picture p ON p.id = u.picture
                    WHERE u.id = '".$userid."' LIMIT 1");
			if(mysql_num_rows($request) == 0)return array('content' => 'Такого продавца не существует');
			
			$login = strBaseOut(mysql_result($request,0,0));
			$date = mysql_result($request,0,1);
			$rating = mysql_result($request,0,2);
			$fio = strBaseOut(mysql_result($request,0,3));
			$phone = strBaseOut(mysql_result($request,0,4));
			$skype = strBaseOut(mysql_result($request,0,5));
			$site = strBaseOut(mysql_result($request,0,6));
			$wm = strBaseOut(mysql_result($request,0,7));
			$rows_sale = mysql_result($request,0,8);
			$picturePath = mysql_result($request,0,9);
			$id = mysql_result($request,0,10);

			$tpl = file_get_contents(TPL_DIR.'seller.tpl');

			if($user->active_privileges($id)){
				$tpl = str_replace("{pro}", 'PRO SELLER', $tpl);
			}else{
				$tpl = str_replace("{pro}", 'UNKNOWN SELLER', $tpl);
			}

			$tpl = str_replace("{login}", $login, $tpl);
			$tpl = str_replace("{rating}", $rating, $tpl);
			$tpl = str_replace("{date}", $date, $tpl);
			$tpl = str_replace("{fio}", $fio, $tpl);
			$tpl = str_replace("{phone}", $phone, $tpl);
			$tpl = str_replace("{skype}", $skype, $tpl);
			$tpl = str_replace("{site}", $site ? '<a href="'.$site.'" target="_blank">'.$site.'</a>' : '', $tpl);
			$tpl = str_replace("{wm}", $wm, $tpl);
			$tpl = str_replace("{rows_sale}", $rows_sale, $tpl);

			if($picturePath){
				$avatar = '<img src="'.$CONFIG['cdn'].'/picture/'.$picturePath.'recommended.jpg" alt="Продавец">';
			}else{
				$avatar = '<img src="'.$CONFIG['cdn'].'/style/img/man2.jpg" alt="Продавец">';
			}

			$tpl = str_replace("{avatar}", $avatar, $tpl);
			

			return array('content' => $tpl, 'seller' => $login);
		}
	}
?>
