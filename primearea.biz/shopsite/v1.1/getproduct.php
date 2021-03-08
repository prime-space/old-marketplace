<?php
	header('Access-Control-Allow-Origin: *');
	
    include "../../func/config.php";
    include "../../func/mysql.php";
    include "../../func/main.php";
	include "../../modules/currency/currency.php";
	include "../../modules/currency/currclass.php";
	
	$bd = new mysql();
	
    $id = $bd->prepare((int)$_GET["id"], 8);
    $product_id = $bd->prepare((int)$_GET["product_id"], 8);
	$curr = $bd->prepare((int)$_GET["currency"], 5);
	$return['err'] = 0;
	
  $request = $bd->read("SELECT p.id, p.name, p.descript, p.info, p.sale, p.block, p.curr, p.price, p.picture, pic.name, pic.path
                        FROM product p  
						JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
						WHERE p.id = ".$product_id." LIMIT 1");
  if(mysql_num_rows($request) == 0)close('{"err":1}');
  
  $return['id'] = mysql_result($request,0,0);
  $return['name'] = strBaseOut(mysql_result($request,0,1));
  $return['descript'] = strBaseOut(mysql_result($request,0,2));
  $return['info'] = strBaseOut(mysql_result($request,0,3));
  $return['sale'] = mysql_result($request,0,4);
  $return['picture'] = mysql_result($request,0,10).mysql_result($request,0,9);
  if($return['picture'] == "" || $return['picture'] == "0")$return['picture'] = '<img src="'.$siteAddr.'stylisation/images/no_img.png" />';
  else $return['picture'] = '<img src="'.$siteAddr.$return['picture'].'" />';
  
  $block = mysql_result($request,0,5);
  if($block == 'blocked' || $block == 'deleted')close('{"err":1}');
  
  $product_price = mysql_result($request,0,7);
  $product_curr = mysql_result($request,0,6);
  $current_convert = new current_convert();
  
  $return['price'] = $current_convert->curr($product_price,$product_curr,$curr);
  
  $available = order::product_availability($product_id);
  $return['available'] = $available ? 'available' : 'not_available';
  $return['available_text'] = $available ? 'Товар в наличии' : 'Товар временно отсутствует (закончился)';
  $return['button'] = $available ? 'block' : 'none';

  close(json_encode($return));
?>
