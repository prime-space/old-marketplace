<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	if(!$user->role)$page = 'Необходимо авторизоваться'; 
	elseif(in_array($user->role, array('admin', 'moder'))){
		include "func/messageview.class.php";
		$messageview = new messageview();
		$content = $messageview->page();
		$page = $content['content'];
		$HEAD['title'] = 'PRIMEAREA.RU - Просмотр сообщений';

		if($user->role === 'moder' && !$user->checkModerRight('messageview')){
			$page = 'Доступ запрещён';
		}
	}else $page = 'Доступ запрещён'; 
?>