<?php
	class duplicate{
		private $name;
		public $access;
		function __construct($name){
			global $db;
			
			$this->name = $name;
			for($i=0;$i<10;$i++){
				$db->query("
					UPDATE duplicate
					SET status = 1
					WHERE
						name = '".$name."' AND
						status = 0
					LIMIT 1
				");
				
				if(!$db->get_affected_rows()){
					usleep(1000);
					continue;
				}
				
				$this->access = true;
				break;
			}
			if(!$this->access){
				$db->query("
					UPDATE duplicate
					SET wait = wait + 1
					WHERE name = '".$this->name."'
					LIMIT 1
				");
			}
		}
		public function end($exit){
			global $db;
			
			$db->query("
				UPDATE duplicate
				SET status = 0
				WHERE name = '".$this->name."'
				LIMIT 1
			");
			
			if($exit)exit($exit);
		}
	}
?>