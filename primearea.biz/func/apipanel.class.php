<?php
	class apipanel{
		private $db;
		private $user;
		private $key;
		function __construct(){
			global $db, $user;
			
			$this->db = $db;
			$this->user = $user;
			
			$this->key = $this->db->super_query_value("SELECT `key` FROM apiusers WHERE userId = ".$this->user->id." LIMIT 1");
		}
		
		public function ajax($method){
			global $data;
			
			$this->data = $data;
			
			switch($method){
				case 'generateKey': return $this->generateKey();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		
		private function generateKey(){
			$try = 1;
			while($try){
				$newKey = hash('sha256', $this->user->login.microtime());
				$try = $this->db->super_query_value("SELECT id FROM apiusers WHERE `key` = '".$newKey."' LIMIT 1");
			}
			
			if(!$this->key)$query = "INSERT INTO apiusers (userId, `key`) VALUES(".$this->user->id.", '".$newKey."')";
			else $query = "UPDATE apiusers SET `key` = '".$newKey."' WHERE userId = ".$this->user->id." LIMIT 1";
				
			$this->db->query($query);
			
			return array('status' => 'ok');
		}
		
		public function page(){
			global $CONFIG;
			
			$tpl = new tpl('apipanel');
			
			if($this->key){
				$tpl->set('{key}', $this->key);
				$tpl->set('{buttonGenerate}', 'Перегенерировать');
				$tpl->set('{generateAgain}', '1');
			}else{
				$tpl->set('{key}', 'Отсутствует');
				$tpl->set('{buttonGenerate}', 'Генерировать');
				$tpl->set('{generateAgain}', '0');
			}
			
			$tpl->set('{userId}', $this->user->id);
			$tpl->set('{siteAddr}', $CONFIG['site_address']);
			
			return array(
				'content' => $tpl->content,
				'title' => 'API'
			);
		}
	}
?>