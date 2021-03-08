<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	if($user->role){
		include "func/review.class.php";
		$review = new review();
		$content = $review->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.BIZ - Отзывы';
	}else $page = 'Необходимо авторизоваться'; 
?>