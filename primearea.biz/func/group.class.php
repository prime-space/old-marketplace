<?php
	class group{
		private $db;
		function __construct(){
			global $db;
			$this->db = $db;
		}
		public function getGroups($groupId){
			
			$deep = 0;
			if($groupId){
				$groupRecurs = $groupId;
				while($groupRecurs){
					$deep++;
					$groupRecurs = $this->db->super_query_value("SELECT subgroup FROM productgroup WHERE id = ".$groupRecurs." LIMIT 1");
				}
			}
			
			$groupsRequest = $this->db->query("
				SELECT id, name 
				FROM productgroup 
				WHERE subgroup ".($groupId ? '= '.$groupId : 'IS NULL')."
				ORDER BY name
			");
			if(!$this->db->num_rows())return false;
			$groups = array();
			while($group = $this->db->get_row($groupsRequest))
				$groups[] = array('name' => $group['name'], 'groupId' => $group['id']);
			
			return array('groups' => $groups, 'deep' => $deep);
		}
		public static function page(){
			global $user;

			$tpl = new templating(file_get_contents(TPL_DIR.'group.tpl'));
			
			return(array('content' => $tpl->content));
		}
	}
?>