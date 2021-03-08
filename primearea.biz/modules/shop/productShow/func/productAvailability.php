<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../../func/mysql.php";
  include "../../../../func/config.php";
  
  $id = realMysql($_POST['id'],8);
  
  $timeExp = time() - ($timeReserv * 60);
  writeMysql("UPDATE `product_text` SET `status` = 'sale', `timeReserv` = NULL WHERE `status` = 'reserved' AND `timeReserv` < ".$timeExp);
  writeMysql("UPDATE `product_file` SET `status` = 'sale', `timeReserv` = NULL WHERE `status` = 'reserved' AND `timeReserv` < ".$timeExp);
  
  $request = readMysql("SELECT `typeObject`, `many` FROM `product` WHERE `id` = ".$id." LIMIT 1");
  //if(mysql_result($request,0,1) == 1)exit("1");
  if(mysql_result($request,0,0) == 1)$tbForCountTI = "product_text";
  else $tbForCountTI = "product_file";
  
  $request = readMysql("SELECT COUNT(*) FROM `".$tbForCountTI."` WHERE `idProduct` = ".$id." AND `status` = 'sale' LIMIT 1");
  if(mysql_result($request,0,0) > 0)exit("1");
  else exit("0");
  
?>