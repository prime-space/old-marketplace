<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/mysql.php";
  include "../../../func/verification.php";
  include "../../../func/main.php";
  if(verification($_POST['crypt'], "user, moder, admin") == FALSE) exit('<p>Ошибка доступа</p>');
  
  $id = realMysql($_POST['id'], 8);   //id удаляемого товара
  
  $request = readMysql("SELECT `userId` FROM `usersession` WHERE `crypt` = '".realMysql($_POST['crypt'], 40)."' LIMIT 1");
  $userId = mysql_result($request,0,0);//узнаем id вызвавшего
  
  $request = readMysql("SELECT `idUser`, `typeObject` FROM `product` WHERE `id` = '".$id."' LIMIT 1");
  $productUserId = mysql_result($request,0,0);//узнаем id, чей товар
  
  if($userId != $productUserId){ //если они не равны
  	$requestSecond = readMysql("SELECT `role` FROM `user` WHERE `id` = '".$userId."' LIMIT 1");
	$role = mysql_result($requestSecond,0,0);//узнаем роль вызвавшего
	if($role != 'admin')exit('<p>Ошибка доступа</p>');//если не админ - выходим
  }
  //$typeObject = mysql_result($request,0,1);
  //$typeObject == 1 ? $objectTable = "`product_text`" : $objectTable = "`product_file`";//из какой таблицы удалять объекты		  
  
  //writeMysql("DELETE FROM ".$objectTable." WHERE `idProduct` = '".$id."' AND `status` = 'sale'");//удаляем объекты
  //writeMysql("DELETE FROM `product` WHERE `id` = '".$id."' LIMIT 1");//удаляем товар
  writeMysql("UPDATE `product` SET `block` = 'deleted' WHERE `id` = '".$id."' LIMIT 1");
  exit(true);

?>