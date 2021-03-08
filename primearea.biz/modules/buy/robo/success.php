<?php
	include '../../../func/config.php';
	
	if($_POST['shp_userid'])header("Location: ".$siteAddr."panel/cashout/");
	else header("Location: ".$siteAddr."customer/".$_POST['InvId']."/".$_POST['shph']."/");
?>