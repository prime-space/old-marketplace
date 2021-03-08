<?php


	include '../../func/config.php';
	include '../../func/mysql.php';
	include '../../func/main.php';
  	
	$bd = new mysql();

	$picture = (int) $_POST['picture'];
	
	$user = new user(array('user', 'moder', 'admin'));
	$user->verify(array('user', 'moder', 'admin'));
	
	$bd->write("UPDATE user SET picture =  '".$picture."'
		WHERE id = ".$user->id);
	
?>