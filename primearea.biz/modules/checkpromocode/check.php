<?php
	session_start();
	include '../../func/config.php';
	include '../../func/main.php';
	include '../../func/mysql.php';
	include "../currency/currclass.php";
	if($_POST['data'])$data = json_decode($_POST['data'], true);
	
	if(!$data['captcha'] || !$_SESSION['captcha'] || $_SESSION['captcha'] !== $data['captcha']){
		unset($_SESSION['captcha']);
		close('{"status": "error", "message": "Неверное число с картинки"}');
	}
	unset($_SESSION['captcha']);
	
	if(!$data['code'])close('{"status": "error", "message": "Введите промо-код"}');
	
	$bd = new mysql();
	$current_convert = new current_convert();
	$tpl = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/checkpromocode/check.tpl"));
	
	$code = $bd->prepare($data['code'], 16);
	
	$request = $bd->read("
		SELECT prp.percent, p.id, p.name, p.price, p.curr
		FROM promocode_el pre
		JOIN promocode_products prp ON prp.promocode_id = pre.promocode_id
		JOIN product p ON p.id = prp.product_id
		WHERE BINARY pre.code = '".$code."' 
		LIMIT 500
	");
	$nolist = $tpl->ify('NOLIST');
	if(!$bd->rows)$tpl->content = str_replace($nolist['orig'], $nolist['if'], $tpl->content);
	else{
		$tpl->content = str_replace($nolist['orig'], $nolist['else'], $tpl->content);
		$tpl->fory('LIST');
		for($i=0;$i<$bd->rows;$i++){
			$percent = mysql_result($request,$i,0);
			$preprice = mysql_result($request,$i,3);
			$curr = mysql_result($request,$i,4);
			$price = $current_convert->curr($preprice,$curr,$curr);
			$lowprice = $current_convert->curr(($preprice - ($preprice * $percent / 100)),$curr,$curr);
			$tpl->fory_cycle(array(	'name' => mysql_result($request,$i,2),
									'price' => $price,
									'discount' => $percent,
									'lowprice' => $lowprice,
									'product_id' => mysql_result($request,$i,1)
			));
		}
		$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);			
	}
	
	close(json_encode(array('status' => 'ok', 'message' => '', 'content' => $tpl->content)));
?>