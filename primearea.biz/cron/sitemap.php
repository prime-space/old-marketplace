<?php
	include '../func/config.php';
	include '../func/mysql.php';
	include "../func/main.php";
	include "../func/cron.class.php";

	$bd = new mysql();
	
	$cron = new cron('sitemap', 86400);
	if($cron->stop)close();

	$request = $bd->read("SELECT `id`, `date` FROM `product` WHERE showing = 1");

	if($bd->rows > 45000){
		$bd->write("UPDATE `setting` SET `value` = 'limit' WHERE `ids` = 1");
		$cron->end();
		close();
	}

   $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <url>
      <loc>http://primearea.biz/?p=info</loc>
      <changefreq>monthly</changefreq>
   </url>
   <url>
      <loc>http://primearea.biz/?p=contact</loc>
      <changefreq>monthly</changefreq>
   </url>
   <url>
      <loc>http://primearea.biz/?p=faq</loc>
      <changefreq>monthly</changefreq>
   </url>
   <url>
       <loc>http://primearea.biz/?p=sogl</loc>
       <changefreq>monthly</changefreq>
   </url>

';   
   for($i=0;$i<$bd->rows;$i++){
   	  $id = mysql_result($request,$i,0);
   	  $date = substr(mysql_result($request,$i,1), 0, 10);
	  $sitemap .= '   <url>
      <loc>'.$siteAddr.'product/'.$id.'/</loc>
      <lastmod>'.$date.'</lastmod>
      <changefreq>weekly</changefreq>
   </url>
';
   }
   $sitemap .= '</urlset>
';

   $fp = fopen("../sitemap.xml", "w"); // Открываем файл в режиме записи
   $test = fwrite($fp, $sitemap); // Запись в файл
   fclose($fp); //Закрытие файла
   
   $time = time();
   
   $bd->write("UPDATE `setting` SET `value` = 'good', `date` = '".$time."' WHERE `ids` = 1");

	$cron->end();
	close();
?>