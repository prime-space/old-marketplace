<?php
	if( ! defined( 'PRIMEAREARU' ) ) {
		exit('Доступ запрещён' );

	}

	include 'func/signin.class.php';

	$signin = new signin();

	$HEAD['title'] = strtoupper( $CONFIG['site_domen'] ) . ' - Авторизация';

	$page = $signin->signuppage();

?>