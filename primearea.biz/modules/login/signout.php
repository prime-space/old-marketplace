<?php
	include "../../func/config.php";
	SetCookie('s', '', time()-3600, '/', $CONFIG['site_domen'], $CONFIG['cookie_SSL'], true);
	header("Location:/");
?>