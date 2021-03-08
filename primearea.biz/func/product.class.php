<?php
	class product{
        const SPHINX_INDEX_NAME = 'products';

		private $ajaxdata;
		private $db;
		private $user;
		private $uvar;
		private $engine;
		private $tpl;
		private $config;
		private $mpayment;
		private $mshop;

		function __construct(){
			global $db,$data,$user,$construction,$CONFIG;
			$this->db = $db;
			$this->ajaxdata = $data;
			$this->user = $user;
			$this->uvar = $construction->uvar;
			$this->engine = $construction;
			$this->config = $CONFIG;
			bcscale(2);
		}

		public function ajax($method){

			switch($method){
				
				case 'showprod':return $this->showprod();
				case 'moderate':return $this->moderate();
				default:return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		public static function newproductspage(){
			global $user;
			
			$tpl = new templating(file_get_contents(TPL_DIR.'newProductsModer.tpl'));
			if($user->role === 'admin'){
				$tpladmin = new templating(file_get_contents(TPL_DIR.'newProductsAdmin.tpl'));
				$tpl->content = $tpladmin->content.$tpl->content;
			}
			
			
			return(array('content' => $tpl->content));
		}
		public static function moderatePage(){
			
			
			$tpl = new templating(file_get_contents(TPL_DIR.'moderate.tpl'));
			$tpl->content = $tpl->content;
			
			
			return(array('content' => $tpl->content));
		}

		public  function moderateOne(){
			global $CONFIG;

			$prod_id = (int)$_GET['id'];

			$mprod = $this->db->super_query("SELECT pr.group,pr.many,pr.typeObject,pr.name,pr.price,pr.picture, pr.partner, pr.descript, pr.info, pr.curr, pr.idUser FROM `product` pr WHERE pr.id = ".$prod_id." LIMIT 1");
			
			$group = $this->db->super_query("SELECT name,subgroup FROM productgroup WHERE id = ".$mprod['group']." LIMIT 1");
			$subgroup = $this->db->super_query("SELECT name,subgroup FROM productgroup WHERE id = ".$group['subgroup']." LIMIT 1");
			if($subgroup['subgroup']){
				$subgroup2 = $this->db->super_query("SELECT name FROM productgroup WHERE id = ".$subgroup['subgroup']." LIMIT 1");
			}
			$user = $this->db->super_query("SELECT login FROM user WHERE id = ".$mprod['idUser']." LIMIT 1");

			require_once 'func/tpl.class.php';
			$tpl = new tpl('showprod');
			
			
			if($mprod['many'] == '0'){
				$many_type = 'Уникальный';
			}elseif($mprod['many'] == '1'){
				$many_type = 'Универсальный';
			}

			if($mprod['typeObject'] == '0'){
				// $_url = '/download.php?moderation=primearea_2ufnd7c&prodId='.$prod_id;
				$type = 'файлы';
				$_file = "Нет доступа";
			}elseif($mprod['typeObject'] == '1'){
				$file_txt = $this->db->super_query("SELECT `text` FROM product_text WHERE idProduct = ".$prod_id." AND status='sale' ", true);
				
				$_file = 'Содержание файлов: ';
				foreach ($file_txt as $key => $value) {
					$n = $key + 1;
					$_file = $_file.'<br>'.'<b>Файл '.$n.'</b>: '.str_replace("&lt;br /&gt;", "",$value['text']);
				}
				$type = 'текст';
			}
			
			if($mprod['curr'] == '1'){
				$price = $mprod['price'].' $';
			}elseif($mprod['curr'] == '2'){
				$price = $mprod['price'].' грн.';
			}elseif($mprod['curr'] == '3'){
				$price = $mprod['price'].' €';
			}elseif($mprod['curr'] == '4'){
				$price = $mprod['price'].' руб.';
			}

			if($mprod['picture'] == '0'){
				$picture = '/stylisation/images/no_img.png';
			}else{
				$pic = $this->db->super_query("SELECT `name`, `path` FROM picture WHERE id = ".$mprod['picture']." LIMIT 1");
				if($pic['name']){
					$picture = $CONFIG['cdn'].'/picture/'.$pic['path'].$pic['name'];
				}else{
					$picture = $CONFIG['cdn'].'/picture/'.$pic['path'].'productshow.jpg';
				}
				
			}
			

			$tpl->set(array(
				'id' => $prod_id,
				'user_id' => $mprod['idUser'],
				'name' => $mprod['name'],
				'login' => $user['login'],
				'group' => $subgroup2['name'].'->'.$subgroup['name'].'->'.$group['name'],
				'many' => $many_type,
				'typeObject' => $type ,
				'price' => $price,
				'picture' => $picture,
				'partner' => $mprod['partner'],
				'descript' => str_replace("&lt;br /&gt;", "",$mprod['descript']),
				'info' => str_replace("&lt;br /&gt;", "",$mprod['info']),
				'file' => $_file,
			));
			
			
			return(array('content' => $tpl->content));
		}

		public function moderate(){

			$prod_id = (int)$this->ajaxdata['prodId'];
			$msg = NULL;
			
			if($this->ajaxdata['result']){
				$result = 'ok';
			}else{
				$result = 'refuse';
			}

			$res = $this->db->query("UPDATE product SET moderation = '".$result."', moderation_message = '".$msg."'  WHERE id = ".$prod_id."");
			
			return array('status' => 'ok', 'result'=>$this->ajaxdata['result']);			
		}

		/*
			for upfate one product rating
		*/
		public function updateRatings($product_id){
		
			$result = $this->db->super_query('SELECT p.id as id, COUNT(DISTINCT rg.id) AS reviewGood, COUNT(DISTINCT rb.id) AS reviewBad
				FROM product p
				LEFT JOIN review rg ON rg.idProduct = p.id AND rg.good = 1 AND rg.del = 0
				LEFT JOIN review rb ON rb.idProduct = p.id AND rb.good = 0 AND rb.del = 0
				WHERE p.id = '.$product_id.'
				GROUP BY p.id
			');
	
			$this->updateRatingByData($result['id'], (int)$result['reviewGood'], (int)$result['reviewBad']);
				
		}

		/*
			for update all products ratings
		*/
		public function updateRatingsAll(){

			$offset = 0;
			do {
		
				$result = $this->db->super_query('SELECT p.id as id, COUNT(DISTINCT rg.id) AS reviewGood, COUNT(DISTINCT rb.id) AS reviewBad
					FROM product p
					LEFT JOIN review rg ON rg.idProduct = p.id AND rg.good = 1 AND rg.del = 0
					LEFT JOIN review rb ON rb.idProduct = p.id AND rb.good = 0 AND rb.del = 0
					GROUP BY p.id
					ORDER BY p.id
					LIMIT '.$offset.', 100', true
				);
		
				foreach ($result as $value) {
					$this->updateRatingByData($value['id'], (int)$value['reviewGood'], (int)$value['reviewBad']);
				}

			   $offset += 100;
                sleep(10);
			} while (count($result) > 0);
			
		}

		private function updateRatingByData($product_id, $numGood, $numBad){
			$numReviews = $numGood + $numBad;
			$rating 	= 0;
			if($numReviews > 0){
				$rating = ($numGood - $numBad) * 100 / $numReviews;
			}

			$this->db->query('UPDATE product set rating = '.$rating.'
				WHERE id = '.$product_id
			);
		}


		public static function getPercentLabels($rating){

			$minusClass2 = '';
			if($rating > 0){//положительная шкала
				$stars = $rating.'%';
				$percents = '<span style="color:green">'.$rating.'%</span>';
			}elseif($rating < 0){//отрицательная шкала
				$stars = $rating.'%';
				$percents = '<span style="color:red">'.$rating.'%</span>';
				$minusClass2 = 'minus';
			}elseif($rating == 0){// нету отзывов
				$stars = 0;
				$percents = '0%';
			}

			return ['minusClass2' => $minusClass2, 'stars' => $stars, 'percents' => $percents];
		}

		public static function getCountReviews($product_id){
			global $db;

			$result = $db->super_query('SELECT p.id as id, COUNT(DISTINCT rg.id) AS reviewGood, COUNT(DISTINCT rb.id) AS reviewBad
				FROM product p
				LEFT JOIN review rg ON rg.idProduct = p.id AND rg.good = 1 AND rg.del = 0
				LEFT JOIN review rb ON rb.idProduct = p.id AND rb.good = 0 AND rb.del = 0
				WHERE p.id = '.$product_id.'
				GROUP BY p.id
			');

			return ['reviewGood' => $result['reviewGood'], 'reviewBad' => $result['reviewBad']];
		
		}
        public static function searchIndex($data, $create = false) {
            global $CONFIG, $db;

            $command = $create ? 'INSERT' : 'REPLACE';

            try {
                $sphinx = new Sphinx($CONFIG['sphinx']['host']);
                $sphinx->request("$command INTO ".self::SPHINX_INDEX_NAME." (id, name) VALUES (:id, :name)", $data);
            } catch (Exception $e) {
                $logs = new logs();
                $logs->add('indexingProductError', $data['id'], $db->safesql($e->getMessage()));
            }
        }
        public static function findInIndex($query, $ids) {
            global $CONFIG, $db;

            try {
                $sphinx = new Sphinx($CONFIG['sphinx']['host']);
                $idsStr = implode(',', $ids);
                $query = substr($query, 0, 100);
                $explodedQuery = explode(' ', $query);
                $words = [];
                foreach ($explodedQuery as $queryPart) {
                    $queryPart = trim($queryPart);
                    if (!empty($queryPart)) {
                        $words[] = $queryPart;
                    }
                }

                if (count($words) === 0) {
                    return [];
                }

                $condition = implode(' | ', $words);
                $stm = $sphinx->request(
                    "SELECT id FROM ".self::SPHINX_INDEX_NAME." WHERE id IN($idsStr) AND MATCH(:condition)",
                    ['condition' => $condition]
                );

                $result = [];
                foreach ($stm->fetchAll() as $row) {
                    $result[] = $row['id'];
                }
            } catch (Exception $e) {
                $logs = new logs();
                $logs->add('findInIndexError', 0, $db->safesql($e->getMessage()));
            }

            return $result;
        }
        public static function reindex() {
            global $CONFIG, $db;

            try {
                $sphinx = new Sphinx($CONFIG['sphinx']['host']);
                $sphinx->request("DELETE FROM ".self::SPHINX_INDEX_NAME." WHERE id <> 0");

                $request = $db->query("SELECT id, name FROM product");
                while($row = $db->get_row($request)){
                    echo "{$row['id']}\n";
                    $sphinx->request("INSERT INTO ".self::SPHINX_INDEX_NAME." (id, name) VALUES (:id, :name)", $row);
                }

            } catch (Exception $e) {
                $logs = new logs();
                $logs->add('reindexingError', 0, $db->safesql($e->getMessage()));
            }
        }
	}
