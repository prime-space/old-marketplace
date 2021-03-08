<?php
	class api{
		private $db;
		private $config;
		private $userId;
		function __construct($module, $method){
			global $db, $CONFIG;

			$this->db = $db;
			$this->config = $CONFIG;

			if(!$module)exit(json_encode(array('status' => 'error', 'code' => '53', 'message' => 'API module is missing')));

			if(!$method)exit(json_encode(array('status' => 'error', 'code' => '54', 'message' => 'API method is missing')));
			
			$this->authorisation();
			
			if($module == 'partner')$out = $this->partner($method);
			else $out = array('status' => 'error', 'code' => '55', 'message' => 'Unknown API module');
			
			exit(json_encode($out));
		}
		private function authorisation(){
			if(!$_POST['id'])exit(json_encode(array('status' => 'error', 'code' => '60', 'message' => 'id is missing')));
			if(!$_POST['key'])exit(json_encode(array('status' => 'error', 'code' => '61', 'message' => 'key is missing')));

			$id = (int)$_POST['id'];
			$key = $this->db->safesql($_POST['key']);

			$this->userId = $this->db->super_query_value("
				SELECT userId
				FROM apiusers
				WHERE
					userId = ".$id." AND
					`key` = '".$key."'
				LIMIT 1
			");

            /*include 'func/logs.class.php';
            $logs = new logs();
            $logMethod = 'apiAuthorisation'.($this->userId ? 'Correct' : 'Incorrect');
            $logData = 'id: '.$this->db->safesql($_POST['id'].'; key: '.$key.';');
            $logs->add($logMethod, false, $logData);*/
			
			if(!$this->userId)exit(json_encode(array('status' => 'error', 'code' => '62', 'message' => 'Incorrect id or key')));
		}
		
		private function partner($method){
			switch($method){
				case 'getGroup': return $this->getGroup();
				case 'getProduct': return $this->getProduct();
				case 'getProducts': return $this->getProducts();
				case 'getReviews': return $this->getReviews();
				case 'getSeller': return $this->getSeller();
				default: return array('status' => 'error', 'code' => '56', 'message' => 'Unknown API method');
			}
		}
		private function getProducts(){
			$where = [
				"p.block = 'ok'",
				"p.moderation = 'ok'",
				"pp.partnerUserId = {$this->userId}"
			];
            if (!empty($_POST['groupId'])) {
                $groupId = $this->findGroup($_POST['groupId']);
                if(!$groupId)return array('status' => 'error', 'code' => '2', 'message' => 'Group not found');
                $where[] = "pp.groupId = $groupId";
			}
            $hideMissing = empty($_POST['hideMissing']) ? false : (bool) $_POST['hideMissing'];
			if ($hideMissing) {
				$where[] = 'p.inStock = 1';
			}

			$this->db->query("
				SELECT
					pp.productId,
				    p.name, p.price, p.curr AS currency, p.inStock, p.idUser as sellerId, p.partner,
				    ps.active, ps.percent,
				    pic.path as pictureUrl
				FROM partnerproducts pp
				JOIN product p ON p.id = pp.productId
				LEFT JOIN partnerships ps ON ps.sellerUserId = p.idUser AND ps.partnerUserId = {$this->userId}
				LEFT JOIN picture pic ON pic.id = p.picture
				WHERE ".implode(' AND ', $where)."
			");
            $group = array();
			while ($row = $this->db->get_row()) {
				$fee = 0;
				if ($row['partner']) {
					$fee += $row['partner'];
				}
				if ($row['percent'] && $row['active']) {
					$fee += $row['percent'];
				}
				$group[$row['productId']] = $row;
				$group[$row['productId']]['fee'] = $fee;
				$group[$row['productId']]['pictureUrl'] = $this->compilePictureUrl($row['pictureUrl']);
			}

			if (!empty($_POST['query'])) {
                include 'func/Sphinx.php';
                include 'func/product.class.php';
				$ids = product::findInIndex($_POST['query'], array_keys($group));
				$filtered = [];
				foreach ($ids as $id) {
					$filtered[] = $group[$id];
				}

				$group = $filtered;
			}

			return array('status' => 'ok', 'group' => array_values($group));
		}
		private function getGroup(){
			$group = array();

			if(!$_POST['groupId'])return array('status' => 'error', 'code' => '1', 'message' => 'Parameter groupId is missing');

            $hideMissing = empty($_POST['hideMissing']) ? false : (bool) $_POST['hideMissing'];

			$groupId = $this->findGroup($_POST['groupId']);
			if(!$groupId)return array('status' => 'error', 'code' => '2', 'message' => 'Group not found');

			$where = [
				"pp.groupId = $groupId",
                "p.block = 'ok'",
                "p.moderation = 'ok'",
			];
			if ($hideMissing) {
				$where[] = 'p.inStock = 1';
			}

			$this->db->query("
				SELECT pp.productId, p.name, p.price, p.curr AS currency, p.inStock
				FROM partnerproducts pp
				JOIN product p ON p.id = pp.productId
				WHERE ".implode(' AND ', $where)."
			");
			while($row = $this->db->get_row()){$group[] = $row;}

			return array('status' => 'ok', 'group' => $group);
		}
		private function getProduct(){
			if(!$_POST['productId'])return array('status' => 'error', 'code' => '1', 'message' => 'Parameter productId is missing');
            $productId = (int)$_POST['productId'];

			$productFound = (bool) $this->db->super_query_value("
				SELECT id
				FROM partnerproducts
				WHERE
					productId = $productId AND
					partnerUserId = {$this->userId}
				LIMIT 1
			");

			if(!$productFound)return array('status' => 'error', 'code' => '2', 'message' => 'Product not found in any of groups');

			$productRequest = $this->db->super_query("
				SELECT
				    p.id, p.name, p.descript, p.info, p.sale, p.curr, p.price, p.inStock, p.idUser as sellerId,
				    pic.path
                FROM product p  
                LEFT JOIN picture pic ON pic.id = p.picture
                WHERE
                    p.id = ".$productId."
                    AND p.block = 'ok'
                    AND p.moderation = 'ok'
                LIMIT 1
			");

            if(!$productRequest)return array('status' => 'error', 'code' => '2', 'message' => 'Product not found in any of groups');

			$pictureUrl = $this->compilePictureUrl($productRequest['path']);

			$product = [
			    'id' => $productRequest['id'],
			    'name' => $productRequest['name'],
			    'description' => $productRequest['descript'],
			    'info' => $productRequest['info'],
			    'sale' => $productRequest['sale'],
			    'currency' => $productRequest['curr'],
			    'inStock' => $productRequest['inStock'],
			    'sellerId' => $productRequest['sellerId'],
			    'price' => $productRequest['price'],
			    'picture' => $pictureUrl,
			    'buy' => "{$this->config['site_address']}buy/{$productRequest['id']}/{$this->userId}",
            ];

			return array('status' => 'ok', 'product' => $product);
		}

		private function compilePictureUrl($path)
		{
			$siteAddress = empty($this->config['cdn'])
				? $this->config['site_address']
				: $this->config['cdn'].'/';
			return empty($path)
                ? "{$siteAddress}stylisation/images/no_img.png"
                : "{$siteAddress}picture/{$path}fullview.jpg";
		}

		private function getReviews(){
			if(!$_POST['productId'])return array('status' => 'error', 'code' => '1', 'message' => 'Parameter productId is missing');
            $productId = (int)$_POST['productId'];

            $limit = $_POST['limit'] ?: 10;
            if (!preg_match('#^\d+$#', $limit) || $limit > 50 || $limit < 1) {
                return array('status' => 'error', 'code' => '3', 'message' => 'Parameter limit is wrong');
			}

            $offset = $_POST['offset'] ?: 0;
            if (!preg_match('#^\d+$#', $offset) || $offset > 4294967295) {
                return array('status' => 'error', 'code' => '4', 'message' => 'Parameter offset is wrong');
			}

			$productFound = (bool) $this->db->super_query_value("
				SELECT id
				FROM partnerproducts
				WHERE
					productId = $productId AND
					partnerUserId = {$this->userId}
				LIMIT 1
			");

			if(!$productFound)return array('status' => 'error', 'code' => '2', 'message' => 'Product not found in any of groups');

            $request = $this->db->query("
				SELECT SQL_CALC_FOUND_ROWS
			  		r.id, r.text, r.good, r.date,
			  		ra.id AS answerId, ra.text AS answerText, ra.date AS answerDate
				FROM review r
          		LEFT JOIN review_answer ra ON ra.reviewId = r.id
				WHERE
					r.idProduct = $productId
					AND r.del = 0
				ORDER BY r.id DESC
				LIMIT $offset, $limit
			");
            $total = $this->db->super_query_value("SELECT FOUND_ROWS()");
            $reviews = [];
            while ($row = $this->db->get_row($request)) {
                $review = [
            		'id' => $row['id'],
            		'text' => $row['text'],
            		'good' => $row['good'],
            		'date' => $row['date'],
				];
            	if (!empty($row['answerId'])) {
                    $review['answer'] = [
                    	'text' => $row['answerText'],
                    	'date' => $row['answerDate'],
					];
				}
                $reviews[] = $review;
            }

			return array('status' => 'ok', 'total' => $total, 'reviews' => $reviews);
		}

		private function getSeller(){
            if(!$_POST['sellerId'])return array('status' => 'error', 'code' => '1', 'message' => 'Parameter sellerId is missing');
            $sellerId = (int)$_POST['sellerId'];

            $seller = $this->db->super_query("
				SELECT u.id, u.login, u.fio, u.date, u.skype, u.rating
				FROM user u
				WHERE u.id = $sellerId
			");

            if(empty($seller))return array('status' => 'error', 'code' => '2', 'message' => 'Seller not found');

            $salesNum = $this->db->super_query_value("
            	SELECT SUM(p.sale)
            	FROM product p
            	WHERE p.idUser = $sellerId
            ");
            $reviewsNum = $this->db->super_query_value("
            	SELECT COUNT(*)
            	FROM review r
            	WHERE
            	    r.userId = $sellerId
            	    AND r.del = 0
            ");

            $reviews = [];
            $request = $this->db->query("
				SELECT
			  		r.id, r.text, r.good, r.date,
			  		ra.id AS answerId, ra.text AS answerText, ra.date AS answerDate
				FROM review r
          		LEFT JOIN review_answer ra ON ra.reviewId = r.id
				WHERE
					r.userId = $sellerId
					AND r.del = 0
				ORDER BY r.id DESC
				LIMIT 20
			");
            while ($row = $this->db->get_row($request)) {
                $review = [
                    'text' => $row['text'],
                    'good' => $row['good'],
                    'date' => $row['date'],
                ];
                if (!empty($row['answerId'])) {
                    $review['answer'] = [
                        'text' => $row['answerText'],
                        'date' => $row['answerDate'],
                    ];
                }
                $reviews[] = $review;
            }

            return [
            	'id' => $seller['id'],
            	'nick' => $seller['login'],
            	'fio' => $seller['fio'],
            	'regDate' => $seller['date'],
            	'skype' => $seller['skype'],
            	'rating' => $seller['rating'],
            	'salesNum' => $salesNum,
            	'reviewsNum' => $reviewsNum,
				'reviews' => $reviews,
			];
		}

		private function findGroup($groupIdRaw)
		{
            return $this->db->super_query_value("
				SELECT id
				FROM partnergroups
				WHERE
					id = ".(int)$groupIdRaw." AND
					userId = ".$this->userId."
				LIMIT 1
			");
		}
	}
?>
