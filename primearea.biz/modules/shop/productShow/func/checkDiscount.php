<?php
include '../../../../func/config.php';
include '../../../../func/mysql.php';
include '../../../../func/main.php';

$bd = new mysql();

$email = $bd->prepare($_POST['email'],32);
$productId = $bd->prepare($_POST['productId'],8);

close(discountCheck($email, $productId).'');
?>