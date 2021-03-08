<?php
	define('PRIMEAREARU', TRUE);
	
	if(!preg_match('#^https?://([^/]+\.)?primearea\.biz|([^/]+\.)?webvisor\.com/#i',$_SERVER['HTTP_REFERER']))header('X-Frame-Options:DENY');

	$header = "";
	include "func/mysql.php";
	include "func/config.php";
	include "func/main.php";
	include "func/db.class.php";
	include "func/tpl.class.php";
	include "func/construction.class.php";
	include "modules/currency/currclass.php";


	if($CONFIG['site_domen'] == 'steamsells.ru'){
        include "func/http-login.php";

        $auth = new HttpAuth();
        $auth->check();
    }
	
	$bd = new mysql();
	$db = new db();
		
	$construction = new construction();
	$construction->init();
	
	close();
?>
