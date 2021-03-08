<?php
	class interkassa{
		private $db;
		function __construct(){
			global $db;
			
			$this->db = $db;
		}
		
		public function ajax($method){
			global $data;
			
			$this->data = $data;
			
			switch($method){
				case 'signature': return $this->signature();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		
		private function signature(){
			return array('status' => 'ok','ik_sign' => $this->sign($this->data));
		}
		public function sign($data){
			global $CONFIG;
			
			unset($data['ik_sign']);
			unset($data['token']);
            $data['ik_desc'] = html_entity_decode($data['ik_desc'], ENT_QUOTES, 'UTF-8');
            $data['ik_desc'] = htmlspecialchars($data['ik_desc'], ENT_QUOTES);
			ksort($data,SORT_STRING);
			array_push($data,$CONFIG['interkassa']['key']);
			$ik_sign = implode(':',$data);
			$ik_sign = base64_encode(hash('sha256',$ik_sign,true));
			
			return $ik_sign;
		}
	}
