<?php

chdir(__DIR__);

include "../func/config.php";
include "../func/mysql.php";
include "../func/main.php";
include "../modules/currency/currclass.php";
include "../modules/currency/currency.php";
include "../func/cron.class.php";
include '../func/db.class.php';

$currency = new currConvFPayC();
$bd = new mysql();

// param example /cron/day.php?date=2017-09-21
if (isset($_GET['date'])) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $_GET['date']);
    if ($dateTime === false) {
        close('incorrect date');
    }
} else {
    $dateTime = new DateTime('-1 day');
    $cron = new cron('reportCounter', 86400);
    if($cron->stop)close();
}
$date = $dateTime->format('Y-m-d');


$sales_r = $bd->read("	SELECT 	SUM(totalBuyer * CASE
                                                WHEN curr = 1 THEN ".$currency->c['usd']."
                                                WHEN curr = 2 THEN ".$currency->c['uah']."
                                                WHEN curr = 3 THEN ".$currency->c['eur']."
                                                ELSE 1 END), 
                                SUM(comm * CASE
                                                WHEN curr = 1 THEN ".$currency->c['usd']."
                                                WHEN curr = 2 THEN ".$currency->c['uah']."
                                                WHEN curr = 3 THEN ".$currency->c['eur']."
                                                ELSE 1 END) 
                        FROM `order` 
                        WHERE 	date > '".$date." 00:00:00' 
                            AND date < '".$date." 23:59:59' 
                            AND (	status = 'sended' 
                                OR 	status = 'paid' 
                                OR 	status = 'review')");
$sales = (float)mysql_result($sales_r,0,0);
$sales_profit = (float)mysql_result($sales_r,0,1);

$ad_graphic = $bd->read("SELECT SUM(price * graphic_duration) FROM ad WHERE datetime > '".$date." 00:00:00' AND datetime < '".$date." 23:59:59'");
$ad_graphic = (float)mysql_result($ad_graphic,0,0);

$ad_text = $bd->read("SELECT SUM(ad.price) FROM ad_pay_click adc INNER JOIN ad ON ad.id = adc.ad_id WHERE adc.datetime > '".$date." 00:00:00' AND adc.datetime < '".$date." 23:59:59'");
$ad_text = (float)mysql_result($ad_text,0,0);

$ad = (float)($ad_graphic + $ad_text);

$bd->write("INSERT INTO report_money VALUES(NULL, ".$sales.", ".$sales_profit.", ".$ad_graphic.", ".$ad_text.", ".$ad.", '".$date."', 0) ON DUPLICATE KEY UPDATE `sales` = ".$sales.", `sales_profit` = ".$sales_profit.", `ad_graphic` = ".$ad_graphic.", `ad_text` = ".$ad_text.", `ad` = ".$ad);

//for merchant
$sales_r_Merchant = $bd->read("	SELECT 	SUM(amountProfit), 
                                SUM(feeAmount) 
                        FROM `mpayments` 
                        WHERE 	`ts` > '".$date." 00:00:00' 
                            AND `ts` < '".$date." 23:59:59' 
                            AND `status` = 'success'
                            AND NOT (`viaId` = 1) ");
$all_sum  = mysql_result($sales_r_Merchant,0,0) ? mysql_result($sales_r_Merchant,0,0) : '0';
$profit_sum = mysql_result($sales_r_Merchant,0,1) ? mysql_result($sales_r_Merchant,0,1) : '0';

$bd->write("INSERT INTO report_money VALUES(NULL, ".$all_sum.", ".$profit_sum.", 0,0,0, '".$date."', 1) ON DUPLICATE KEY UPDATE `sales` = ".$all_sum.", `sales_profit` = ".$profit_sum);

if(isset($cron))$cron->end();
close();
