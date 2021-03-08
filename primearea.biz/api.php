<?php

	$request = $_SERVER['REQUEST_URI'];
	list($a, $a, $version, $module, $method) = explode('/',$request);

	if(!$version)exit(json_encode(array('status' => 'error', 'code' => '51', 'message' => 'API version is missing')));
	elseif($version == 'v1')include 'func/apiv1.class.php';
	else exit(json_encode(array('status' => 'error', 'code' => '52', 'message' => 'Unknown API version')));

	include 'func/config.php';
	include 'func/db.class.php';

	$db = new db();
	$api = new api($module, $method);

?>