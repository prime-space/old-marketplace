<?php
	$tpl = file_get_contents("reg.tpl");
	exit(json_encode(array('content' => $tpl)));
?>