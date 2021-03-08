<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/mysql.php";
  include "../../../func/main.php";
  
  $request = readMysql("SELECT `group` FROM `product` WHERE `id` = ".realMysql($_POST['id'],8)." LIMIT 1");
  
  $return = subGroupGet(mysql_result($request,0,0));
  
  exit($return);
?>