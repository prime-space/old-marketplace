<?php
	class currency{
		private $db;
		public $c;
		public function __construct(){
			global $db;
			
			$this->db = $db;
			
			$currencies = $this->db->super_query("
				SELECT usd, eur, uah
				FROM currency
				ORDER BY id DESC
				LIMIT 1
			");
			$this->c = array(
				1 => $currencies['usd'],
				2 => $currencies['uah'],
				3 => $currencies['eur'],
			);
		}
		public function convert($p,$cu,$cl,$suff=1){
			switch($cu){
				case 1: $r = round($p * $this->c[1], 2); break;
				case 2: $r = round($p * $this->c[2], 2); break;
				case 3: $r = round($p * $this->c[3], 2); break;
				case 4: $r = $p;break;
			}
			switch($cl){
				case 1:
					$r = round($r / $this->c[1], 2);
					if($suff)$r = $this->priceFormat($r).' $';
					break;
				case 2:
					$r = round($r / $this->c[2], 2);
					if($suff)$r = $this->priceFormat($r).' грн.';
					break;
				case 3:
					$r = round($r / $this->c[3], 2);
					if($suff)$r = $this->priceFormat($r).' €';
					break;
				case 4: if($suff)$r = $this->priceFormat($r).' руб.'; break;
			}
			
			return $r;
		}
		private function priceFormat($price){
			return number_format((float)$price, 2, '.', '');
		}
	}
?>