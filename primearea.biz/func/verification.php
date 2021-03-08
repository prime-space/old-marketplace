<?php
   function verification($crypt, $roleStr){
   	  include $_SERVER['DOCUMENT_ROOT'].'/func/config.php';
      $flag = FALSE;
	  //$timeLastVisit = time() - ($userSessionLifeTime * 60 * 60 * 24);
	  //writeMysql("DELETE FROM `usersession` WHERE `lastVisit` < '".$timeLastVisit."'");
	  $crypt = realMysql($crypt, 40);	  
	  $requestOne = readMysql("SELECT `userId`, `crypt`, `lastVisit` FROM `usersession` WHERE `crypt` = '".$crypt."'");
	  if(mysql_num_rows($requestOne) == 0) return FALSE;
	  writeMysql("UPDATE `usersession` SET `lastVisit` = '".time()."' WHERE `crypt` = '".$crypt."'");
	  
	  $role = explode(", ", $roleStr);
      $request = readMysql("SELECT `id`, `login`, `pass`, `role` FROM `user` WHERE `id` = '".mysql_result($requestOne,0,0)."'");
	  foreach ($role as $value) {
	     if($value == mysql_result($request,0,3)) $flag = TRUE;
      }
	  return $flag;
   }
?>