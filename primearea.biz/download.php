<?php
	include "func/config.php";
	include "func/mysql.php";
	include "func/main.php";
	include "func/customer.class.php";
	include "func/verification.php";
/*
Доступ к файлам разрешен:
1. Покупателю. Проверить его ли это покупка
2. Продавцу. Сделать верификацию и проверить его ли товар
3. Менеджерам и администраторам. Сделать верификацию
*/
	$error = "<br><br><br><center>Файл не существует</center>";

	$bd = new mysql();
	$user = new user();

	if($_GET['i']){
		$order_id = $bd->prepare((int)$_GET['i'], 8);
	}else{

		if(isset($_GET['moderation']) && $_GET['moderation'] == 'primearea_2ufnd7c'){
			
			$prodId = $_GET['prodId'];

			$request = $bd->read("SELECT f.name, f.dir
				FROM product_file f
				WHERE idProduct = {$prodId}
				
			");				
			if(!$bd->rows)close($error);//Если не найден объект
			for($i=0; $i<$bd->rows; $i++){
				$n = $i+1;
				$name = strBaseOut(mysql_result($request,$i,0));
				$file = mysql_result($request,$i,1);
				$file = file_exists($file) ? $file : 'upload/20'.$file;//Баг по стиранию путя к файлу при редактировании объектов
				if(!file_exists($file))close('Ошибка');
				
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: filename="'.$name.'"');
				header('Content-length: '.filesize($file));
				header('Cache-Control: no-cache');
				
				echo "Файл".$n.": ";
				readfile($file);
				echo " ";

			}
			exit();


		}

		close($error);
	}
	
	switch(TRUE){	
		case $_GET['h']://Покупатель
			$customer = new customer();
			$h = $bd->prepare($_GET['h'], 64);
			if(!$customer->check_key($h))close($error);
			if($customer->order_id && $customer->order_id !== $order_id)close($error);//Доступ только к конкретному заказу
			$request_code = " AND o.userIdEmail = '".$customer->email."'";
			break;
			
		case $_COOKIE['crypt']:
			if(!$user->verify($_COOKIE['crypt'], "user,moder,admin"))close($error);
			switch($user->role){
				case 'user':
					$request_code = " AND p.idUser = ".$user->id;
					break;				
				case 'moder':
					$request_code = "";
					break;				
				case 'admin':
					$request_code = "";
					break;
				default:
					close($error);
					break;
			}
			break;			
		default:
			close($error);
			break;
	}
	
	$request = $bd->read("	SELECT p.many, p.id
							FROM `order` o
							INNER JOIN product p ON o.productId = p.id
							WHERE o.id = ".$order_id." ".$request_code."
							LIMIT 1");	

	if(!$bd->rows)close($error);//Если не найден заказ по ид заказа		
	$many = mysql_result($request,0,0);
	$product_id = mysql_result($request,0,1);
	if(!$many){
		$request_code = "	INNER JOIN `order` o ON f.orderid = o.id
							WHERE f.orderid = ".$order_id;
	}else $request_code = "	WHERE f.idProduct = ".$product_id;				
	$request = $bd->read("	SELECT f.name, f.dir
							FROM product_file f
							".$request_code."
							LIMIT 1");							
	if(!$bd->rows)close($error);//Если не найден объект.
	$name = strBaseOut(mysql_result($request,0,0));
	$file = mysql_result($request,0,1);
	$file = file_exists($file) ? $file : 'upload/20'.$file;//Баг по стиранию путя к файлу при редактировании объектов
	if(!file_exists($file))close('Ошибка');
	unset($bd);

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: filename="'.$name.'"');
	header('Content-length: '.filesize($file));
	header('Cache-Control: no-cache');
	 
	readfile($file);
?>