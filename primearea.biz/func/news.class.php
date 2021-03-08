<?php
	class news{
		private $data;
		private $db;
		private $user;
		function __construct(){
			global $db,$data,$user;
			$this->db = $db;
			$this->data = $data;
			$this->user = $user;
		}
		public function ajax($method){
			switch($method){
				case 'add': return $this->add();
				case 'list': return $this->listing();
				case 'editpopup': return $this->editpopup();
				case 'edit': return $this->edit();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		public function moderpage(){
			$tpl = new tpl('news/moder');
			
			return(array('content' => $tpl->content));
		}
		public function page(){
			if(!in_array($this->user->role,array('user','moder','admin')))return 'Необходимо авторизоваться';
			$tpl = new tpl('news/all');
			
			return $tpl->content;
		}
		private function add(){
			if(!in_array($this->user->role,array('moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			if(!$this->data['text'])return array('status' => 'error','message' => 'Введите текст');
			
			$text = urlReplace($this->db->safesql($this->data['text'],true,true));
			
			$this->db->query("INSERT INTO news (text)VALUES('".$text."')");
			
			return array('status' => 'ok', 'message' => 'Новость добавлена');
		}
		private function listing(){
			require_once 'func/tpl.class.php';
			require_once 'func/pagination.class.php';

			$elements_on_page = (int)$this->data['elements_on_page'];
			$current_list = (int)$this->data['current_list'];
			$elementLoadInPageStart = !$current_list ? 0 : $current_list * $elements_on_page;
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS id,text,DATE_FORMAT(ts,'%d-%m-%Y') AS ts
				FROM news
				ORDER BY id DESC
				LIMIT ".$elementLoadInPageStart.", ".$elements_on_page
			);
			$listCount = $this->db->super_query_value("SELECT FOUND_ROWS()");
			
			$tpl = new tpl('news/list');
			if($this->data['page'] == 'news')$tpl->switchy('page','news');
			else $tpl->switchy('page','panel');
			
			if(!$this->db->num_rows($request))$tpl->ify('nolist',2);
			else{
				$tpl->ify('nolist',1);
				$tpl->fory('list');
				while($row = $this->db->get_row($request)){
					$tpl->foryCycle(array(
						'id' => $row['id'],
						'text' => $row['text'],
						'ts' => $row['ts']
					));
				}
				$tpl->foryEnd();
			}
		
			if($this->data['method'] != 'add'){
				$pagination = pagination::getPanel($listCount, $elements_on_page, $current_list, $this->data['func']);
				$tpl->content .= '<tr id="table_pagination" class="table_pagination"><td colspan="2" style="background: #ffffff;">' . $pagination . '</td></tr>';
			}
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		public function shortlist(){
			$tpl = new tpl('news/shortlist');
			
			$request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS text,DATE_FORMAT(ts,'%d-%m-%Y') AS ts
				FROM news
				ORDER BY id DESC
				LIMIT 3
			");
			$tpl->fory('list');
			while($row = $this->db->get_row($request)){
				if(mb_strlen($row['text'],'UTF-8') > 300)
					$row['text'] = mb_substr($row['text'],0,300,'UTF-8').' <a href="/news/">...</a>';
				$tpl->foryCycle(array(
					'text' => $row['text'],
					'ts' => $row['ts']
				));
			}
			$tpl->foryEnd();
			return $tpl->content;
		}
		private function editpopup(){
			require_once 'func/tpl.class.php';
			if(!in_array($this->user->role,array('moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			$news = $this->db->super_query("SELECT id,text FROM news WHERE id = ".(int)$this->data['newsId']." LIMIT 1");
			if(!$news)return array('status' => 'error','message' => 'Новость не найдена');
			$tpl = new tpl('news/edit');
			$tpl->set('{newsId}',$news['id']);
			$tpl->set('{text}',str_replace('<br>','',urlReplace($news['text'],true)));
			
			return array('status' => 'ok', 'content' => $tpl->content);
		}
		private function edit(){
			if(!in_array($this->user->role,array('moder','admin')))return array('status' => 'error','message' => 'Нет доступа');
			if(!$this->data['text'])return array('status' => 'error','message' => 'Введите текст');
			$newsId = (int)$this->data['newsId'];
			$news = $this->db->super_query_value("SELECT id FROM news WHERE id = ".$newsId." LIMIT 1");
			if(!$news)return array('status' => 'error','message' => 'Новость не найдена');
			$text = urlReplace($this->db->safesql($this->data['text'],true,true));
			$this->db->query("UPDATE news SET text = '".$text."' WHERE id = ".$newsId." LIMIT 1");
			return array('status' => 'ok');
		}
	}
?>