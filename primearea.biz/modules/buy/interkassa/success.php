<?php
	include '../../../func/config.php';
	$bh = $_COOKIE['bh'.$_POST['ik_pm_no']];
	$cabinet = $bh ? $_POST['ik_pm_no'].'/'.$bh.'/' : '';
	//exit($siteAddr.'customer/'.$cabinet);
	header('Location: '.$siteAddr.'customer/'.$cabinet);
?>