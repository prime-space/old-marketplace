<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	


	$product = new Shop();

	$getProduct = $product->show_product();

	if($getProduct['discount'] == 0) header("Location:/error404/");
	

	$pid = $_GET['productid'];
	$uid = $user->id;
	$title = $getProduct['title'];
	$img = $getProduct['picture'];
	$price = $getProduct['price'];
	$discount = $getProduct['discount'];



	$construction->jsconfig['product']['id'] = $pid;

	if($uid){
  		$tplPage = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/template/default/partnersCodePage.tpl"));
	}else{
		$tplPage = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/template/default/pathnersCodePageNoAuth.tpl"));	
	}


	//$tplPage = new tpl('partnersCodePage');
	

	$t1 = array("{pid}","{uid}","{title}","{img}","{price}","{discount}");
	$t2 = array($pid,$uid,$title,$img,$price,$discount);

	$tplPage->content = str_replace($t1,$t2,$tplPage->content);
	
	$content = $tplPage->content;

	$page = $content;
	$HEAD['title'] = 'PRIMEREA.RU - Партнерская программа';
?>