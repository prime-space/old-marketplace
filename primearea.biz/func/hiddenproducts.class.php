<?php
	class hiddenproducts{
		private $db;
		private $user;
		private $data;
		function __construct(){
			global $db,$user,$data;
			
			$this->db = $db;
			$this->user = $user;
			$this->data = $data;
		}

		public function ajax($method){
			global $data;
			
			$this->data = $data;
			
			switch($method){
				case 'editstatus': return $this->edit_status();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}

		public function init(){
			global $user;
			$user_id = (int)$_GET['user_id'];
			
			$tpl = new tpl('hiddenproducts/page');
			
			if($user_id){
				$tpl->switchy('page','PAGEONE');

				$tpl->set('{login}', $user->getLogin($user_id));
				$tpl->set('{user_id}', $user_id);
			}else{
				$tpl->switchy('page','PAGEMAIN');
			}

			return array('content' => $tpl->content);
		}

		private function edit_status(){
			
			if( !($this->user->role == 'admin' || ( $this->user->role == 'moder' && $this->user->checkModerRight('panel_hiddenproducts'))) )
				return array('status' => 'error', 'message' => 'Доступ запрещён');
			
			$type      = $this->db->safesql($this->data['type']);
			$user_id   = (int)$this->data['userId'];

			$checked = [];
			$unchecked = [];

			if($this->data['checked']){
				foreach ($this->data['checked'] as $key => $value) {
					$checked[] = (int)$value;
				}
			}
			
			if($this->data['unchecked']){
				foreach ($this->data['unchecked'] as $key => $value) {
					$unchecked[] = (int)$value;
				}
			}

			if($type == 'allShow'){
				$status  =  0; 
			}elseif($type == 'allHide'){
				$status  =  1; 
			}

			if($type != 'choosen'){
				$query = 'UPDATE product SET hidden = '.$status.' WHERE idUser = '.$user_id.' AND block="ok" ';

				$this->db->query($query);
			}else{

				if($checked){
					$query1 = 'UPDATE product SET hidden = 1 WHERE id IN('.implode(',', $checked).') AND idUser = '.$user_id;

					$this->db->query($query1);
				}

				if($unchecked){
					$query2 = 'UPDATE product SET hidden = 0 WHERE id IN('.implode(',', $unchecked).') AND idUser = '.$user_id;

					$this->db->query($query2);
				}
			}
			

			return array('status' => 'ok', 'message' => 'Успешно');
		}

	}
?>