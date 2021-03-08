<?php
  header("Content-type: text/html; charset=utf-8");

  $tpl = file_get_contents($_SERVER['DOCUMENT_ROOT']."/modules/shop/productList/productListMyShop.tpl");

  exit($tpl);

?>