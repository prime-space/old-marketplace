<?php
header("Content-type: text/html; charset=utf-8");
include "../../func/mysql.php";
$request = readMysql("SELECT `id` FROM `user` WHERE `login` = '".$_POST['login']."'");
$requestSecond = readMysql("SELECT `id` FROM `user` WHERE `email` = '".$_POST['email']."'");

if((mysql_num_rows($requestSecond) > 0) && (mysql_num_rows($request) > 0)) exit("3");
if(mysql_num_rows($request) > 0)exit("1");
if(mysql_num_rows($requestSecond) > 0)exit("2");
exit("0");


?>