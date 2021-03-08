<?php
	class mail{
		private $db;
		function __construct(){
			global $db;
			
			$this->db = $db;
		}
		public function unsubscribe($email){
			$unsubscribe = $this->db->super_query("
				SELECT *
				FROM unsubscribe
				WHERE email = '".$email."'
				LIMIT 1
			");
			if(!$unsubscribe['key']){
				$overlap = true;
				while($overlap){
					$unsubscribe['key'] = hash('sha512', microtime());
					$overlap = $this->db->super_query_value("
						SELECT id
						FROM unsubscribe
						WHERE `key` = '".$unsubscribe['key']."'
						LIMIT 1
					");
				}
				$this->db->query("
					INSERT INTO unsubscribe
					(email, `key`)
					VALUES ('".$email."','".$unsubscribe['key']."')
				");
			}
			return array('key' => $unsubscribe['key'], 'un' => $unsubscribe);
		}
	}
?>