<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/mysql.php";
  include "../../../func/verification.php";
  include "../../../func/main.php";
  if(verification($_POST['crypt'], "user, moder, admin") == FALSE)exit(FALSE);
  
  $request = readMysql("SELECT `userId` FROM `usersession`  WHERE `crypt` = '".realMysql($_POST['crypt'],40)."'");
  $id = mysql_result($request,0,0);
  
  $request = readMysql("SELECT `topic` FROM `help` ORDER BY `topic` DESC LIMIT 1");
  if(mysql_num_rows($request) == 0)$topic = 1;
  else $topic = mysql_result($request,0,0) + 1;

  $title = realMysql($_POST['title'], 256);
  $text = realMysql(nl2br($_POST['text']), 1600);
  
  $date = date('Y-m-d H:i:s');
  
  writeMysql("INSERT INTO `help` VALUES (NULL, '".$id."', '".$topic."', '".$title."', '".$text."', '".$date."', 0)");
  
  exit(TRUE);
?>