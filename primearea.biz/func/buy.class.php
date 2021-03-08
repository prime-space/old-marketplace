<?php
	class buy{
		public static function page(){
			global $bd, $construction, $robokassa_MrchLogin,$CONFIG;
			
			if($_GET['partner']){
				include 'func/partner.class.php';
				$partner = new partner();
				$partner->fixClick();
			}
			
			$current_convert = new current_convert();
			$order = new order();
			$id =  (int)$_GET['productid'];
			$email = $bd->prepare($_GET['email'],32);
			$email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
			
			$construction->jsconfig['buy']['id'] = $id;
			
			$tpl = new templating(file_get_contents(TPL_DIR."buy.tpl"));

			if(!$order->product_availability($id))return array('content' => 'Товара нет в наличии');


			$request = $bd->read("SELECT name, price, curr, info, block FROM product WHERE id=".$id." LIMIT 1");
			$product_name = mysql_result($request,0,0);
			$price = mysql_result($request,0,1);
			$curr = mysql_result($request,0,2);
			$block = mysql_result($request,0,4);
			
			if($block == 'blocked')return array('content' => 'Товар заблокирован');
			if($block == 'deleted')return array('content' => 'Товар удален');
			
			$price = $current_convert->curr($price,$curr,$curr);

			$request = $bd->read("
                SELECT id, relate, name, code, pic, info
                FROM pay_method
                WHERE enabled = 1
                ORDER BY position
            ");
			$info = mysql_result($request,0,5);
			$pay_pic = '';	
			$pay_method = '';
			$more_id = false;	
			for($i=0;$i<$bd->rows;$i++){

				$relate = mysql_result($request,$i,1);
				if($relate == 17){
					$more_id = $i;
					continue;
				}

				$method_id = mysql_result($request,$i,0);
				$name = mysql_result($request,$i,2);
				$code = mysql_result($request,$i,3);
				$pic = mysql_result($request,$i,4);
				if($pic)$pay_pic .= '<div onclick="module.buy.change(\''.$method_id.'-'.$relate.'\');" class="buy_method_btn " id="buy_method'.$method_id.'" ><img src="/style/img/pay_method/'.$pic.'.png" style="max-width: 170px;height:60px; margin-left: 10px;" title="'.$name.'"></div>';
				$pay_method .= '<option value="'.$method_id.'-'.$relate.'">'.$name.'</option>';
			}
			if (false !== $more_id) {
                // for "other" method
                $relate = mysql_result($request, $more_id, 1);
                $method_id = mysql_result($request, $more_id, 0);
                $name = mysql_result($request, $more_id, 2);
                $code = mysql_result($request, $more_id, 3);
                $pic = mysql_result($request, $more_id, 4);
                if ($pic) {
                    $pay_pic .= '<div onclick="module.buy.change(\'' . $method_id . '-' . $relate . '\');" class="buy_method_btn" id="buy_method' . $method_id . '"><img src="/style/img/pay_method/' . $pic . '.png" style="width: 70px;height: 60px;margin-left: 60px;" title="' . $name . '"></div>';
                }
                $pay_method .= '<option value="' . $method_id . '-' . $relate . '">' . $name . '</option>';
            }
			$request = $bd->read("SELECT money, percent 
							FROM discount 
							WHERE userId = (SELECT idUser FROM product WHERE id = ".$id." LIMIT 1)
							ORDER BY percent
							LIMIT 10");
			if($bd->rows > 0){
			$discountDisplay = "block";
			$discount = "";
			for($i=0;$i<$bd->rows;$i++){
			$money = mysql_result($request,$i,0);
			$percent = mysql_result($request,$i,1);
			$discount .= '<div>'.$money.'руб. скидка составляет '.$percent.'%</div>';
			}
			}else $discountDisplay = "none";

			$nopromocodeuse = $tpl->ify('NOPROMOCODEUSE');
			$request = $bd->read("	SELECT id FROM promocode_products WHERE product_id = ".$id." LIMIT 1"); 
			if(!$bd->rows)$tpl->content = str_replace($nopromocodeuse['orig'], $nopromocodeuse['if'], $tpl->content);
			else$tpl->content = str_replace($nopromocodeuse['orig'], $nopromocodeuse['else'], $tpl->content);

			$tpl->content = str_replace("{email}", $email, $tpl->content);
			$tpl->content = str_replace("{product_name}", $product_name, $tpl->content);
			$tpl->content = str_replace("{product_name_base64}", base64_encode($product_name), $tpl->content);
			$tpl->content = str_replace("{price}", $price, $tpl->content);
			$tpl->content = str_replace("{pay_pic}", $pay_pic, $tpl->content);
			$tpl->content = str_replace("{pay_method}", $pay_method, $tpl->content);
			$tpl->content = str_replace("{info}", $info, $tpl->content);
			$tpl->content = str_replace("{discount}", $discount, $tpl->content);
			$tpl->content = str_replace("{discountDisplay}", $discountDisplay, $tpl->content);
			$tpl->content = str_replace("{robokassa_MrchLogin}", $robokassa_MrchLogin, $tpl->content);
			$tpl->content = str_replace("{interkassaId}", $CONFIG['interkassa']['id'], $tpl->content);
			$tpl->content = str_replace("{paypalReciever}", $CONFIG['paypal']['receiver'], $tpl->content);
			$tpl->content = str_replace("{primePayerId}", $CONFIG['primePayer']['id'], $tpl->content);
			$tpl->content = str_replace("{product_id}", $id, $tpl->content);

			$title = 'Купить '.$product_name.' на '.strtoupper($CONFIG['site_domen']);
			
			return array('content' => $tpl->content, 'title' => $title);
		}
	}
?>
