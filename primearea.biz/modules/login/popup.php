<?php
	$tpl = file_get_contents("popup.tpl");
	exit(json_encode(array('content' => $tpl)));
?>