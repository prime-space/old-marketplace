<?php
header("Content-type: text/html; charset=utf-8");
include '../../../func/mysql.php';
include '../../../func/verification.php';
if(verification($_POST['crypt'], "user, moder, admin") == FALSE) exit('<p>Ошибка доступа</p>');
$fileName = explode("/", $_POST['file']);
$fileName = $fileName[(count($fileName)-1)];
$dir = "../../../upload/";
if(file_exists($dir.date('Y')."/".date('m')."/".date('d')."/".$fileName)){
	unlink($dir.date('Y')."/".date('m')."/".date('d')."/".$fileName);
	exit(1);
}
else{
	$yesterday = strtotime("-1 day");
	if(file_exists($dir.date('Y', $yesterday)."/".date('m', $yesterday)."/".date('d', $yesterday)."/".$fileName)){
		unlink($dir.date('Y', $yesterday)."/".date('m', $yesterday)."/".date('d', $yesterday)."/".$fileName);
		exit(1);
	}
	else exit(0);
}

?>