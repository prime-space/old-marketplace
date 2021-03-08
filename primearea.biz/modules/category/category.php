<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	$category = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/categories.html");
	
	if($p === 'category'){
		$a = $bd->prepare($_GET['a'], 64);
		$b = $bd->prepare($_GET['b'], 64);
		$c = $bd->prepare($_GET['c'], 64);
		$request = $bd->read("
			SELECT pgb.id, pga.name, pgb.name, pgc.name, pgc.id
			FROM productgroup pga
			JOIN productgroup pgb ON pgb.subgroup = pga.id
			JOIN productgroup pgc ON pgc.subgroup = pgb.id
			WHERE
				pga.muu = '".$a."' AND
				pgb.muu = '".$b."' AND
				pgc.muu = '".$c."'
			LIMIT 1
		");
		if($bd->rows){
			$this->jsconfig['category']['id'] = mysql_result($request,0,4);
			$this->jsconfig['category']['sg'] = mysql_result($request,0,0);
			$aname = mysql_result($request,0,1);
			$bname = mysql_result($request,0,2);
			$cname = mysql_result($request,0,3);
			$breadcrumbs = '<a href="/">Все товары</a> &#10137; '.$aname.' &#10137 '.$bname.' &#10137 '.$cname;
		}
	}
?>