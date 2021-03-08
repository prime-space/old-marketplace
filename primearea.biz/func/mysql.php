<?php
function readMysql($str){
	     include 'config.php';
		 $int = mysql_connect($mySQLhost, $mySQLname, $mySQLpass);
		 mysql_query('SET NAMES utf8', $int);
         mysql_query("set names utf8"); 
		 mysql_query("SET time_zone = '+03:00';");
         mysql_query("SET CHARACTER SET utf8");	
		 mysql_query("Set charset utf8");
		 mysql_query("Set character_set_client = utf8");
         mysql_query("Set character_set_connection = utf8");
         mysql_query("Set character_set_results = utf8");
         mysql_query("Set collation_connection = utf8_general_ci");		 
         mysql_query("SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
         mysql_select_db($mySQLdbname, $int);
         $return = mysql_query($str);
         mysql_close($int);
         return $return;
 }
 function writeMysql($str){
 	     include 'config.php';
         $int = mysql_connect($mySQLhost, $mySQLname, $mySQLpass);
		 mysql_query('SET NAMES utf8', $int);
         mysql_query("set names utf8"); 		 
         mysql_query("SET CHARACTER SET utf8");	
		 mysql_query("SET time_zone = '+03:00';");
		 mysql_query("Set charset utf8");
		 mysql_query("Set character_set_client = utf8");
         mysql_query("Set character_set_connection = utf8");
         mysql_query("Set character_set_results = utf8");
         mysql_query("Set collation_connection = utf8_general_ci");
         mysql_query("SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
         mysql_select_db($mySQLdbname, $int);
         mysql_query($str);
		$return = mysql_insert_id();
         mysql_close($int);
		return $return;
 }
  function realMysql($str, $len){
  	     include 'config.php';
         $int = mysql_connect($mySQLhost, $mySQLname, $mySQLpass);
		 mysql_query('SET NAMES utf8', $int);
         mysql_query("set names utf8"); 		 
         mysql_query("SET CHARACTER SET utf8");	
		 mysql_query("SET time_zone = '+03:00';");
		 mysql_query("Set charset utf8");
		 mysql_query("Set character_set_client = utf8");
         mysql_query("Set character_set_connection = utf8");
         mysql_query("Set character_set_results = utf8");
         mysql_query("Set collation_connection = utf8_general_ci");
         mysql_query("SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
         mysql_select_db($mySQLdbname, $int);
		 $return = addslashes($str);
		 $return = htmlspecialchars($return);
         $return = mysql_real_escape_string($return);
         if(mb_strlen($str,'utf-8') > $len){
            $return = mb_substr($return,0,$len,'utf-8');
         } 
         mysql_close($int);
		 return $return;
 }
 
 class mysql{
	private $request;
	private $connect;
	public $rows;
	public $total_rows;
	public function __construct(){
		global $mySQLname;
		global $mySQLpass;
		global $mySQLhost;
		global $mySQLdbname;
		$this->connect = mysql_connect($mySQLhost, $mySQLname, $mySQLpass);
		mysql_query('SET NAMES utf8', $this->connect);
		mysql_query("set names utf8"); 
		mysql_query("SET CHARACTER SET utf8");	
		mysql_query("Set charset utf8");
		mysql_query("Set character_set_client = utf8");
		mysql_query("Set character_set_connection = utf8");
		mysql_query("SET time_zone = '+03:00';");
		mysql_query("Set character_set_results = utf8");
		mysql_query("Set collation_connection = utf8_general_ci");
        mysql_query("SET sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
		mysql_select_db($mySQLdbname, $this->connect);
	}
	public function __destruct(){
		mysql_close($this->connect);
	}
	public function read($str){
		$this->request = mysql_query($str);
		$this->rows = mysql_num_rows($this->request);
		$this->total_rows = mysql_result(mysql_query("SELECT FOUND_ROWS()"),0,0);
		return $this->request;
	}
	public function write($str){
		mysql_query($str);
		return mysql_insert_id();
	}
	public function prepare($str, $len, $html = true){
		 //$str = addslashes($str);
        if ($html) {
            $str = htmlspecialchars($str);
        }
         $str = mysql_real_escape_string($str);
         if(mb_strlen($str,'utf-8') > $len){
            $str = mb_substr($str,0,$len,'utf-8');
         }
		 return $str;
	}
}
 ?>
