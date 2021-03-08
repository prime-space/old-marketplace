<?php
  header("Content-type: text/html; charset=utf-8");
  include "../../../func/mysql.php";
  include "../../../func/verification.php";
  include "../../../func/main.php";
  if(verification($_POST['crypt'], "user, moder, admin") == FALSE)exit("Ошибка доступа");
  
  
  $tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/help/help.tpl");
  //$tpl = str_replace("{wmz}", $wmzBal, $tpl);
  
  exit($tpl);
?>