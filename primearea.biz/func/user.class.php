<?php
	class user{
		public $id;
        public $login;
        public $pass;
		public $role;
		public $wmrbal;
		public $status;
		public $googleSecret;
		public $token;
		function __construct($access_roles){
			global $db;
			
			if(!$_COOKIE['s'])return;
			
			$crypt = $db->safesql($_COOKIE['s']);
			$user = $db->super_query("
				SELECT u.id, u.login, u.pass, u.role, u.wmrbal, u.status, u.googleSecret, us.token
				FROM usersession us
				INNER JOIN user u ON u.id = us.userid
				WHERE 
					us.crypt = '".$crypt."'
				LIMIT 1
			");
			if(!$user)return;
			if(!in_array($user['role'], $access_roles))return;
			
			$this->id = (int)$user['id'];
			$this->login = $user['login'];
			$this->pass = $user['pass'];
			$this->role = $user['role'];
			$this->wmrbal = $user['wmrbal'];
			$this->status = $user['status'];
			$this->googleSecret = $user['googleSecret'];
			$this->token = $user['token'];
			
			$db->query("UPDATE `usersession` SET `lastVisit` = '".time()."' WHERE `crypt` = '".$crypt."' LIMIT 1");
			$db->query("UPDATE user SET last_action = NOW() WHERE id = ".$this->id." LIMIT 1");
		}

		public function checkModerRight($module){
			global $db;

			if($this->id){
				$request = $db->super_query("SELECT `moder_rights` FROM `user` WHERE `id` = ".$this->id);
				$rights =  $request['moder_rights'];
				$rights = explode(',', $rights);
				$rights = array_flip($rights);

				return isset($rights[$module]);
			}else{
				return false;
			}
		}
	}
