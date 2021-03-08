<?php
	class sales{
		private $db;
		private $bd;
		private $user;
		private $config;
		private $construction;
		private $data;
		private $curr;
		function __construct(){
			global $db, $bd, $user,$CONFIG,$construction,$data;
			
			$this->db = $db;
			$this->bd = $bd;
			$this->user = $user;
			$this->config = $CONFIG;
			$this->construction = $construction;
			$this->data = $data;
		}
		
		public function ajax($method){
			global $data;
			
			$this->data = $data;
			
			switch($method){
				case 'charts': return $this->charts();
				case 'chartsMerchant': return $this->chartsMerchant();
				default: return array('status' => 'error', 'message' => 'Unknown method');
			}
		}
		
		private function charts(){
			
			$user_login = false;
			if($this->data['user_login']){
				$user_login = $this->db->safesql($this->data['user_login']);
			}

			$periods = array(0,1,7,30,90,365);

			$period = $periods[(int)$this->data['period']] ? $periods[(int)$this->data['period']] : $periods[0];
			$dt = $period == 1 ? " BETWEEN DATE(ADDDATE(NOW(),INTERVAL -1 DAY)) AND NOW()" : "BETWEEN DATE(ADDDATE(NOW(),INTERVAL -".$period." DAY)) AND NOW()";
			
			$sales = array(
				'dt' => array(),
				'sales' => array(),
				'profit' => array()
			);
			$this->db->query("SET lc_time_names = 'ru_RU'");
			
			if($user_login){

				$res = $this->db->super_query("
					SELECT id FROM user
					WHERE
						login = '".$user_login."'
				");
				$this->user->id = $res['id'];
	
			}

			
			$curr = new currConvFPayC();
            $userId = empty($this->user->id) ? 0 : $this->user->id;
			$this->db->query("
				SELECT DATE_FORMAT(d.dt,'%d %b %Y') AS dt,COUNT(o.id) AS sales,(CASE WHEN SUM(o.totalSeller) IS NULL THEN 0 ELSE SUM(o.totalSeller * CASE
													WHEN o.curr = 1 THEN ".$curr->c['usd']."
													WHEN o.curr = 2 THEN ".$curr->c['uah']."
													WHEN o.curr = 3 THEN ".$curr->c['eur']."
													ELSE 1 END)  END) AS profit, o.curr as currency
				FROM(
					SELECT dt
					FROM dates
					WHERE dt ".$dt."
				)d
				LEFT JOIN(
					SELECT id,totalSeller,DATE(date) AS dt, curr
					FROM `order`
					WHERE
						user_id = $userId AND
						status IN('sended','review') AND
						date ".$dt."
				)o ON o.dt = d.dt
				GROUP BY d.dt
				ORDER BY d.dt
			");
			
			
			while($row = $this->db->get_row()){
				
				$sales['dt'][] = $row['dt'];
				$sales['sales'][] = (int)$row['sales'];
				
				$sales['profit'][] = round($row['profit']); 
			}

			$reviews = $this->db->super_query("
				SELECT 1 AS gr,SUM(r.good) AS good,SUM((CASE WHEN good = 1 THEN 0 ELSE 1 END)) AS bad
				FROM review r
				JOIN product p ON p.id = r.idProduct
				WHERE
					p.idUser = $userId AND
					r.date ".$dt."
				GROUP BY gr
			");
			
			$products = array();
			$this->db->query("
				SELECT p.name,COUNT(p.id) AS num
				FROM `order` o
				JOIN product p ON p.id = o.productId
				WHERE
					o.user_id = $userId AND
					o.status IN('sended','review') AND
					o.date ".$dt."
				GROUP BY p.id
				ORDER BY num DESC
				LIMIT 7
			");
			
			while($row = $this->db->get_row()){
				$products[] = array(
					'name' => $row['name'],
					'y' => (int)$row['num']
				);
			}
			
			$allProfit = 0;
			foreach ($sales['profit'] as $key => $value) {
				$allProfit += $value;
			}

			return array(
				'status' => 'ok',
				'sales' => $sales,
				'reviews' => array(
					'good' => (int)$reviews['good'],
					'bad' => (int)$reviews['bad']
				),
				'products' => $products,
				'allProfit' =>round($allProfit)
			);
		}

		private function chartsMerchant(){
			$periods = array(0,1,7,30,90,365);
			$data = json_decode($_POST['data'], true);
			$data['id'] = (int)$data['id'];
			
			$user_login = false;
			$user_id = false;
			if($this->data['user_login']){
				$user_login = $this->db->safesql($this->data['user_login']);
				
				$res = $this->db->super_query("
					SELECT id FROM user
					WHERE
						login = '".$user_login."'
				");
				$user_id = $res['id'];

				$this->db->query("
					SELECT id FROM mshops
					WHERE
						userId = '".$user_id."'
				");

				$shopIds = array();
				while($row = $this->db->get_row()){
				
					$shopIds[] = $row['id'];
				}
				$shopIds = implode(',', $shopIds);
				
			}


			$period = $periods[(int)$this->data['period']] ? $periods[(int)$this->data['period']] : $periods[0];
			$dt = $period == 1 ? " BETWEEN DATE(ADDDATE(NOW(),INTERVAL -1 DAY)) AND NOW()" : "BETWEEN DATE(ADDDATE(NOW(),INTERVAL -".$period." DAY)) AND NOW()";
			
			$sales = array(
				'dt' => array(),
				'sales' => array(),
				'profit' => array()
			);
			$this->db->query("SET lc_time_names = 'ru_RU'");
			
			if($user_id){

				if(!$shopIds){
					$shopIds = '0';
				}

				$this->db->query("
					SELECT DATE_FORMAT(d.dt,'%d %b %Y') AS dt,COUNT(o.id) AS sales,(CASE WHEN SUM(o.amountProfit) IS NULL THEN 0 ELSE SUM(o.amountProfit) END) AS profit
					FROM(
						SELECT dt
						FROM dates
						WHERE dt ".$dt."
					)d
					LEFT JOIN(
						SELECT id,amountProfit,DATE(ts) AS dt
						FROM `mpayments`
						WHERE
							mshopId  IN(".$shopIds.") AND
							status = 'success' AND
							ts ".$dt." AND
							NOT (viaID = 1) 
					)o ON o.dt = d.dt
					GROUP BY d.dt
					ORDER BY d.dt
				");
			}else{
				$this->db->query("
					SELECT DATE_FORMAT(d.dt,'%d %b %Y') AS dt,COUNT(o.id) AS sales,(CASE WHEN SUM(o.amountProfit) IS NULL THEN 0 ELSE SUM(o.amountProfit) END) AS profit
					FROM(
						SELECT dt
						FROM dates
						WHERE dt ".$dt."
					)d
					LEFT JOIN(
						SELECT id,amountProfit,DATE(ts) AS dt
						FROM `mpayments`
						WHERE
							mshopId = ".$data['id']." AND
							status = 'success' AND
							ts ".$dt." AND
							NOT (viaID = 1) 
					)o ON o.dt = d.dt
					GROUP BY d.dt
					ORDER BY d.dt
				");
			}
			

			while($row = $this->db->get_row()){
				
				$sales['dt'][] = $row['dt'];
				$sales['sales'][] = (int)$row['sales'];
				$sales['profit'][] = (float)$row['profit'];
			}
			
			$allProfit = 0;
			foreach ($sales['profit'] as $key => $value) {
				$allProfit += $value;
			}
			
			return array(
				'status' => 'ok',
				'sales' => $sales,
				'allProfit' => $allProfit
			);
		}
		public function page(){
			global $bd;
			
			$tpl = new tpl('sales/sales');
			$curr = new current_convert();
			
			if($_GET['order_id']){
				$order_id = (int)$_GET['order_id'];
				
				$this->construction->jsconfig['sales']['order_id'] = $order_id;
				
				$request = $bd->write("SET lc_time_names = 'ru_RU'");
				$request = $bd->read("	SELECT 	o.userIdEmail, o.totalSeller, o.curr, o.date, p.name, o.rating, o.customer_purse, o.discount, o.discountper, p.typeObject,
												(CASE 	WHEN p.typeObject = 1 AND p.many = 0 THEN (SELECT text FROM product_text WHERE orderid = ".$order_id." LIMIT 1)
														WHEN p.typeObject = 0 AND p.many = 0 THEN (SELECT name FROM product_file WHERE orderid = ".$order_id." LIMIT 1)
														WHEN p.typeObject = 1 AND p.many = 1 THEN (SELECT text FROM product_text WHERE idProduct = o.productId LIMIT 1)
														WHEN p.typeObject = 0 AND p.many = 1 THEN (SELECT name FROM product_file WHERE idProduct = o.productId LIMIT 1) END),
												o.promocode_el_id_use, preuse.code, preissued.code, uu.login AS partnerLogin
										FROM `order` o
										JOIN product p ON o.productId = p.id
										JOIN user u ON p.idUser = u.id
										LEFT JOIN promocode_el preuse ON preuse.id = o.promocode_el_id_use
										LEFT JOIN promocode_el preissued ON preissued.id = o.promocode_el_id_issued
										LEFT JOIN user uu on uu.id = o.partner
										WHERE o.id = ".$order_id." AND (o.status='sended' OR o.status='paid' OR o.status='review') AND p.idUser = ".$this->user->id."
										LIMIT 1");
				if(!$bd->rows){$tpl->switchy('sales', 'ERRORORDER'); return array('content' => $tpl->content);}	
				
				$current = mysql_result($request,0,2);
				$discount_money = mysql_result($request,0,7);
				$discount_percent = mysql_result($request,0,8);
				$promocode_el_id_use = mysql_result($request,0,11);
				$promocode_el_code_use = mysql_result($request,0,12);
				$promocode_el_code_issued = mysql_result($request,0,13);
				$partnerLogin = mysql_result($request,0,14);
				
				$price = $curr->curr(mysql_result($request,0,1), mysql_result($request,0,2), 4);
				
				$tpl->switchy('sales', 'ORDER');
				
				$partner = $tpl->ify('PARTNER');
				if($partnerLogin){
					$partner['if'] = str_replace("{partnerLogin}", $partnerLogin, $partner['if']);
					$tpl->content = str_replace($partner['orig'], $partner['if'], $tpl->content);
				}else $tpl->content = str_replace($partner['orig'], $partner['else'], $tpl->content);
				
				$customer_purse = $tpl->ify('CUSTOMER_PURSE');
				if(mysql_result($request,0,6)){
					$customer_purse['if'] = str_replace("{customer_purse}", mysql_result($request,0,6), $customer_purse['if']);
					$tpl->content = str_replace($customer_purse['orig'], $customer_purse['if'], $tpl->content);
				}else $tpl->content = str_replace($customer_purse['orig'], $customer_purse['else'], $tpl->content);
				
				$discount_ify = $tpl->ify('DISCOUNT');
				if($discount_money){
					$discount_money_out = $curr->curr($discount_money, $current, $current);
					if($promocode_el_id_use)$discount_html = $discount_money_out.' ('.$discount_percent.'%) по промо-коду <span style="font-weight:bold;">'.$promocode_el_code_use.'</span>';
					else $discount_html = $discount_money_out." (".$discount_percent."%) основная";
					$discount_ify['if'] = str_replace("{discount}", $discount_html, $discount_ify['if']);
					$tpl->content = str_replace($discount_ify['orig'], $discount_ify['if'], $tpl->content);
				}else $tpl->content = str_replace($discount_ify['orig'], $discount_ify['else'], $tpl->content);

				$nopromocodebonus = $tpl->ify('NOPROMOCODEBONUS');
				if($promocode_el_code_issued){
					$nopromocodebonus['else'] = str_replace("{promocodebonus}", $promocode_el_code_issued, $nopromocodebonus['else']);
					$tpl->content = str_replace($nopromocodebonus['orig'], $nopromocodebonus['else'], $tpl->content);
				}else $tpl->content = str_replace($nopromocodebonus['orig'], $nopromocodebonus['if'], $tpl->content);
				
				$object_content = $tpl->ify('OBJECT');
				$typeObject = mysql_result($request,0,9);
				$object = mysql_result($request,0,10);
				if($typeObject)$tpl->content = str_replace($object_content['orig'], $object_content['if'], $tpl->content);
				else $tpl->content = str_replace($object_content['orig'], $object_content['else'], $tpl->content);
				$object = str_replace("&lt;br /&gt;", "<br />", $object);
				$tpl->content = str_replace('{object}', $object, $tpl->content);
				
				$tpl->content = str_replace("{i}", 					$order_id, 									$tpl->content);
				$tpl->content = str_replace("{date}", 				mysql_result($request,0,3), 				$tpl->content);
				$tpl->content = str_replace("{price}", 				$price, 									$tpl->content);
				$tpl->content = str_replace("{name}", 				mysql_result($request,0,4), 	$tpl->content);
				$tpl->content = str_replace("{rating}", 			mysql_result($request,0,5), 				$tpl->content);
				$tpl->content = str_replace("{customer_email}", 	mysql_result($request,0,0), 				$tpl->content);
				
				$review = $tpl->ify('REVIEW');
				$request = $bd->read("	SELECT r.id, r.text, r.good, r.date, (SELECT text FROM review_answer WHERE reviewId = r.id), r.del, r.datedel,DATE_FORMAT(r.date,'%e %b %Y') AS dt,DATE_FORMAT(r.date,'%H:%i') AS tm
										FROM review r
										WHERE r.orderId = ".$order_id."
										LIMIT 1");
				$tpl_review = new templating(file_get_contents($_SERVER['DOCUMENT_ROOT']."/panel/user/cabinet/review.tpl"));
				if($bd->rows){
					$review_del = mysql_result($request,0,5);
					$review_text = norlzbr(mysql_result($request,0,1));
					$review_good = mysql_result($request,0,2);
					$tpl_review->content = str_replace('{review_text}', $review_text, $tpl_review->content);

					if(!$review_del){
						$tpl_review->switchy('REVIEW', 'REVIEW');
						$tpl_review->content = str_replace("{review_id}", mysql_result($request,0,0), $tpl_review->content);
						$tpl_review->content = str_replace("{review_dt}", mysql_result($request,0,7), $tpl_review->content);
						$tpl_review->content = str_replace("{review_tm}", mysql_result($request,0,8), $tpl_review->content);
						$tpl_review->content = str_replace("{evaluation}", mysql_result($request,0,2), $tpl_review->content);
						
						$review_answer_text = norlzbr(mysql_result($request,0,4));
						$review_answer = $tpl_review->ify('REVIEW_ANSWER');
						if($review_answer_text){
							$review_answer['if'] = str_replace('{review_answer_text}', $review_answer_text, $review_answer['if']);
							$tpl_review->content = str_replace($review_answer['orig'], $review_answer['if'], $tpl_review->content);
						}else $tpl_review->content = str_replace($review_answer['orig'], $review_answer['else'],$tpl_review->content);
					}else{
						$tpl_review->switchy('REVIEW', 'DEL');
						$review_del_who = $review_del == 1 ? 'покупателем' : 'администратором';			
						$tpl_review->content = str_replace('{review_del_date}', mysql_result($request,0,6), $tpl_review->content);
						$tpl_review->content = str_replace('{review_del_who}', $review_del_who, $tpl_review->content);				
					}
				}else $tpl_review->switchy('REVIEW', 'NOREVIEW');
				$tpl->content = str_replace("{review}", $tpl_review->content, $tpl->content);
				
				$message_content = $tpl->ify('MESSAGES');//Общение с покупателем
				$bd->write("UPDATE message SET status = 'read' WHERE order_id = ".$order_id." AND `status` = 'not_read' AND author != '".$this->user->id."'");
				$request = $bd->read("	SELECT m.author, m.text, m.date, m.person, m.status, 
											(CASE WHEN m.person = 'Продавец' THEN 
												(SELECT login FROM user WHERE id = m.author LIMIT 1) END),
											DATE_FORMAT(m.date,'%e %b %Y') AS dt,DATE_FORMAT(m.date,'%H:%i') AS tm
										FROM message m WHERE m.order_id = ".$order_id." 
										ORDER BY m.id DESC 
										LIMIT 30");
				if(!$bd->rows)$tpl->content = str_replace($message_content['orig'], $message_content['else'], $tpl->content);
				else{
					$tpl->content = str_replace($message_content['orig'], $message_content['if'], $tpl->content);
					$tpl->fory('MESSAGES');
					for($i=0;$i<$bd->rows;$i++){
						$message_status = mysql_result($request,$i,4) == 'read' ? 'Прочитано' : 'Не прочитано';
						$message_person  = mysql_result($request,$i,3);
						$message_author = $message_person == 'Продавец' ? mysql_result($request,$i,5) : mysql_result($request,$i,0);
						$message_text = str_replace("&lt;br /&gt;", "<br />", mysql_result($request,$i,1));

					
						$testString = explode('/', $message_text);
						
						if($testString[1] == 'picture'){

							$extTest = explode('.',$testString[6]);
							
							if(end($extTest) == 'zip' || end($extTest) == 'rar'){
								$message_text = '<div><a href="'.$message_text.'">Скачать архив</a></div>';
							}else{
								$message_text = '<div><a href="'.$message_text.'"><img style="max-width: 220px" height="86px;" src="'.$message_text.'"></a></div>';
							}

							
						}

						$tpl->foryCycle(array(	
												'mdt' => mysql_result($request,$i,6),
												'mtm' => mysql_result($request,$i,7),
												'author' =>$message_author,
												'person' => $message_person,
												'status' => $message_status,
												'text' => $message_text));
					}
					$tpl->foryEnd();
				}
				return array('content' => $tpl->content);
			}
			
			$tpl->switchy('sales', 'MAIN');
			
			return array(
				'content' => $tpl->content
			);
		}
	}
?>