<?php
	//exit('<pre>'.print_r($_POST).'</pre>');
	include '../../../func/config.php';

	if($_POST['shp_userid']){
		header("Location: ".$siteAddr."panel/cashout/");
	}else{
		header("Location: ".$siteAddr."customer/".$_POST['LMI_PAYMENT_NO']."/".$_POST['h']."/");
	}
	
?>