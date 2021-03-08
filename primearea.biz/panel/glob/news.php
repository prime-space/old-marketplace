<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/news.class.php";
		$news = new news();
		$content = $news->moderpage();
		$page = $content['content'];
		$HEAD['title'] = strtoupper($CONFIG['site_domen']).' - Новости';
	}else $page = 'Необходимо авторизоваться'; 
?>