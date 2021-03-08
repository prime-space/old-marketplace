<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$popup = new templating(file_get_contents(TPL_DIR.'popup.tpl'));
	
	$popup->set('{wrapper}', $wrapper);
?>