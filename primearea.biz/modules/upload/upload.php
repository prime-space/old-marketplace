<?php
  if(verification($_SESSION['crypt'], "user, admin") == FALSE) header("Location:modules/exit.php");
  $header .= "";
  $tplUpload = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/upload/upload.tpl");
  $page .= $tplUpload;
?>