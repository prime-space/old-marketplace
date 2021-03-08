<?php
	class logs{
		private $db;
		function __construct(){
			global $db;
			$this->db = $db;
		}
		public function add($method, $method_id, $data = ''){
			$method_id = $method_id ? $method_id : 'NULL';
			$this->db->query	("
				INSERT INTO logs
				(ip, method, method_id, log)
				VALUES(
					'".REMOTE_ADDR."',
					'".$method."',
					".$method_id.",
					'".$data."'
				)
			");
		}
	}
?>