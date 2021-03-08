<?php
session_start();
header("Content-type: text/html; charset=utf-8");
echo $_SESSION["captcha"];
unset($_SESSION["captcha"]);
?>