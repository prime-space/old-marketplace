<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/mysql.php";
  include "../../../func/verification.php";
  include "../../../func/main.php";
  if(verification($_COOKIE['crypt'], "user, moder, admin") == FALSE) exit('<p>Ошибка доступа</p>');
  
  $id = realMysql($_POST['id'], 8);
  $request = readMysql("SELECT `userId` FROM `usersession` WHERE `crypt` = '".realMysql($_COOKIE['crypt'], 40)."' LIMIT 1");
  $userId = mysql_result($request,0,0);
  $request = readMysql("SELECT `idUser`, `typeObject`, `many` FROM `product` WHERE `id` = '".$id."' LIMIT 1");
  $productUserId = mysql_result($request,0,0);
  if($userId != $productUserId)exit('<p>Ошибка доступа</p>');

  $typeObject = mysql_result($request,0,1);
  $many = mysql_result($request,0,2);
  
  $typeObject == 1 ? $objectTable = "`product_text`" : $objectTable = "`product_file`";	
  
  $request = writeMysql("DELETE FROM ".$objectTable." WHERE `idProduct` = '".$id."' AND `status` = 'sale'");
  
  exit('ok');
?>