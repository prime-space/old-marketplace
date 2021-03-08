<?php

class ExchangeRatesCBRF
{
	var $rates;
	function __construct($date = null)
	//В PHP версии ниже 5 это метод объекта следует переименовать в ExchangeRatesCBRF
	{
		$client = new SoapClient("http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL"); 
		if (!isset($date)) $date = date("Y-m-d"); 
		$curs = $client->GetCursOnDate(array("On_date" => $date));
		$this->rates = new SimpleXMLElement($curs->GetCursOnDateResult->any);
	}
	
	function GetRate ($code)
	{
	//Этот метод получает в качестве параметра цифровой или буквенный код валюты и возвращает ее курс
		$code1 = (int)$code;
		if ($code1!=0) 
		{
			$result = $this->rates->xpath('ValuteData/ValuteCursOnDate/Vcode[.='.$code.']/parent::*');
		}
		else
		{
			$result = $this->rates->xpath('ValuteData/ValuteCursOnDate/VchCode[.="'.$code.'"]/parent::*');
		}
		if (!$result)
		{
			return false; 
		}
		else 
		{
			$vc = (float)$result[0]->Vcurs;
			$vn = (int)$result[0]->Vnom;
			return ($vc/$vn);
		}

	}
}
?>