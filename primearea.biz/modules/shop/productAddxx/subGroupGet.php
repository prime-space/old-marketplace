<?php
  include "../../../func/config.php";
  include "../../../func/mysql.php";
  include "../../../func/main.php";
  
  $bd = new mysql();
  $user = new user();
  
  $id = (int)$_POST['id'];
  
  if($user->verify($_COOKIE['crypt'], "user,moder,admin"))close(subGroupGet_new($id));
  else close('Ошибка доступа');
?>