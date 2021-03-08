<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin', 'moder'))){
		include "func/review.class.php";
		$review = new review();
		$content = $review->deletepage();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Удаление отзывов';

		if($user->role === 'moder' && !$user->checkModerRight('reviewdelete')){
			$page = 'Доступ запрещён';
		}	
	}else $page = 'Доступ запрещён'; 
?>