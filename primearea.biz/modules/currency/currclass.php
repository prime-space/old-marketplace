<?php
class current_convert{
	private $c = array();
	private $r;
	public function __construct(){
		global $bd;
       $request = $bd->read("SELECT `usd`, `eur`, `uah` FROM `currency` ORDER BY `id` DESC LIMIT 1");
	   $this->c[1] = mysql_result($request,0,0);
	   $this->c[2] = mysql_result($request,0,2);
	   $this->c[3] = mysql_result($request,0,1);
	}
	public function curr($p,$cu,$cl,$suff=1){
	  switch($cu){
   	    case 1:
	       $r = round($p * $this->c[1], 2);
		   break;
        case 2:
	       $r = round($p * $this->c[2], 2);
		   break;
        case 3:
	       $r = round($p * $this->c[3], 2);
		   break;
        case 4:
	       $r = $p;
		   break;
     }
	  switch($cl){
   	    case 1:
	       $r = round($r / $this->c[1], 2);
		   if($suff)$r .= ' $';
		   break;
        case 2:
	       $r = round($r / $this->c[2], 2);
		   if($suff)$r .= ' грн.';
		   break;
        case 3:
	       $r = round($r / $this->c[3], 2);
		   if($suff)$r .= ' €';
		   break;
        case 4:
		   if($suff)$r .= ' руб.';
		   break;
     }	 
     return $r;
   }
}
class currConvFPayC{
	public $c = array();
	private $r;
	public function __construct(){
		include_once $_SERVER['DOCUMENT_ROOT'].'/func/db.class.php';
		$db = new db();
		$request = $db->super_query("SELECT `usd`, `eur`, `uah` FROM `currency` ORDER BY `id` DESC LIMIT 1");
		$this->c['usd'] = $request['usd'];
		$this->c['uah'] = $request['uah'];
		$this->c['eur'] = $request['eur'];
	}
	public function curr($p,$cu,$cl){
	  switch($cu){
   	    case 1:
	       $r = round($p * $this->c['usd'], 2);
		   break;
        case 2:
	       $r = round($p * $this->c['uah'], 2);
		   break;
        case 3:
	       $r = round($p * $this->c['eur'], 2);
		   break;
        case 4:
	       $r = $p;
		   break;
     }
	  switch($cl){
   	    case 1:
	       $r = round($r / $this->c['usd'], 2).' $';
		   break;
        case 2:
	       $r = round($r / $this->c['uah'], 2).' грн.';
		   break;
        case 3:
	       $r = round($r / $this->c['eur'], 2).' €';
		   break;
        case 4:
	       $r .= ' руб.';
		   break;
     }	 
     return $r;
   }
}
?>