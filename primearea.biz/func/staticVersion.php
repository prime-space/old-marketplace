<?php
	$HEAD['title'] = 'Торговая площадка цифровых товаров | PRIMEAREA.RU';
	$HEAD['keywords'] = 'купить steam аккаунт, купить аккаунты steam, купить аккаунт origin, купить steam cs 1.6, гифты стим купить, купить стим гифт, товары, продавцы, заработать, заработать webmoney, wmz, цифровые товары, электронные книги, програмное обеспечение, торговая площадка, Webmoney, интернет-витрина, интернет-магазин, PIN-код, VISA, Paysafecard, iTunes, Xbox, Ukash, Playstation';
	$HEAD['description'] = 'PRIMEAREA.RU - предлагает без малейших затрат и усилий с нуля построить свой собственный бизнес в интернете и начать зарабатывать деньги';
	
	switch($_GET['p']){
		case "info":
			$page = file_get_contents($siteAddr."modules/pages/info/info.php");
			break;
		case "faq":
			$page = file_get_contents($siteAddr."modules/pages/faq/faq.php");
			break;
          case "sogl":
			$page = file_get_contents($siteAddr."modules/pages/sogl/sogl.php");
            break;
		case "contact":
			$page = file_get_contents($siteAddr."modules/pages/contact/contact.php");
			break;
		case "showproduct":
			$product_id = $bd->prepare((int)$_GET['productid'], 8);
			$out = shop::show_product($product_id);
			$HEAD['title'] = $out['title'];
			$HEAD['keywords'] = $out['keywords'];
			$HEAD['description'] = $out['description'];
			$page = $out['content'];
		 break;
	}
	if(!$_GET['p'] || $_GET['p'] == 'main'){
		$page = shop::product_listing_show_static_version($_GET['n']);
	}
  ?>