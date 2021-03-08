<?php
	class messageview{
		public static function page(){
			global $bd;

			$tpl = new templating(file_get_contents(TPL_DIR.'moderMessageView.tpl'));

			if(!$_GET['id']){
				$tpl->switchy('MAIN', 'LIST');
				return(array('content' => $tpl->content));
			}
			
			$order_id = (int)$_GET['id'];
			
			$tpl->switchy('MAIN', 'MESSAGE');
			$request = $bd->read("	SELECT 	p.name, p.typeObject,
											(CASE 	WHEN p.typeObject = 1 AND p.many = 0 THEN (SELECT text FROM product_text WHERE orderid = ".$order_id." LIMIT 1)
													WHEN p.typeObject = 0 AND p.many = 0 THEN (SELECT name FROM product_file WHERE orderid = ".$order_id." LIMIT 1)
													WHEN p.typeObject = 1 AND p.many = 1 THEN (SELECT text FROM product_text WHERE idProduct = o.productId LIMIT 1)
													WHEN p.typeObject = 0 AND p.many = 1 THEN (SELECT name FROM product_file WHERE idProduct = o.productId LIMIT 1) END), o.date, o.price, o.rating, o.userIdEmail, o.discount
									FROM `order` o
									INNER JOIN product p ON o.productId = p.id
									WHERE o.id = ".$order_id."
									LIMIT 1");
			if(!$bd->rows)return(array('content' => 'Ошибка'));
			$product_name = strBaseOut(mysql_result($request,0,0));
			$tpl->content = str_replace('{order_id}', 		$order_id, 				$tpl->content);
			$tpl->content = str_replace('{product_name}', 		$product_name,			$tpl->content);
			$tpl->content = str_replace('{date_order}', mysql_result($request,0,3), $tpl->content);
			$tpl->content = str_replace('{price}', mysql_result($request,0,4), $tpl->content);
			$tpl->content = str_replace('{rating}', mysql_result($request,0,5), $tpl->content);
			$tpl->content = str_replace('{email}', mysql_result($request,0,6), $tpl->content);
			$tpl->content = str_replace('{discount}', mysql_result($request,0,7) ? mysql_result($request,0,7) : 'отсутствует', $tpl->content);

			$object_content = $tpl->ify('OBJECT');
			$typeObject = mysql_result($request,$i,1);
			$object = strBaseOut(mysql_result($request,$i,2));
			if($typeObject)$tpl->content = str_replace($object_content['orig'], $object_content['if'], $tpl->content);
			else $tpl->content = str_replace($object_content['orig'], $object_content['else'], $tpl->content);
			$tpl->content = str_replace('{object}', $object, $tpl->content);


				$review = $tpl->ify('REVIEW');
				$request = $bd->read("	SELECT r.id, r.text, r.good, r.date, (SELECT text FROM review_answer WHERE reviewId = r.id), r.del, r.datedel
										FROM review r
										WHERE r.orderId = ".$order_id."
										LIMIT 1");
				$tpl_review = new templating(file_get_contents(TPL_DIR.'moderMessageViewReview.tpl'));
				if($bd->rows){
					$review_del = mysql_result($request,0,5);
					$review_text = strBaseOut(mysql_result($request,0,1));
					$review_good = mysql_result($request,0,2);
					$tpl_review->content = str_replace('{review_text}', $review_text, $tpl_review->content);

					if(!$review_del){
						$tpl_review->switchy('REVIEW', 'REVIEW');
						$tpl_review->content = str_replace("{review_id}", mysql_result($request,0,0), $tpl_review->content);
						$tpl_review->content = str_replace("{review_date}", mysql_result($request,0,3), $tpl_review->content);
						$tpl_review->content = str_replace("{evaluation}", mysql_result($request,0,2), $tpl_review->content);
						
						$review_answer_text = strBaseOut(mysql_result($request,0,4));
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


			$request = $bd->read("	SELECT 	m.author, m.text, m.date, m.person, m.status,
											(CASE WHEN m.person = 'Продавец' THEN 
												(SELECT login FROM user WHERE id = m.author LIMIT 1) END)
									FROM message m
									WHERE m.order_id = ".$order_id." 
									ORDER BY m.id DESC 
									LIMIT 30");
			$messages = $tpl->ify('MESSAGES');
			if(!$bd->rows){
				$tpl->content = str_replace($messages['orig'], $messages['else'], $tpl->content);
				return(array('content' => $tpl->content));
			}
			
			$tpl->content = str_replace($messages['orig'], $messages['if'], $tpl->content);	
			$tpl->fory('MESSAGES');
			for($i=0;$i<$bd->rows;$i++){
				$message_status = mysql_result($request,$i,4) == 'read' ? 'Прочитано' : 'Не прочитано';
				$message_person  = mysql_result($request,$i,3);
				$message_author = $message_person == 'Продавец' ? mysql_result($request,$i,5) : mysql_result($request,$i,0);
				
				$_text = strBaseOut(mysql_result($request,$i,1));

				$testString = explode('/', $_text);
				if($testString[1] == 'picture'){
					$extTest = explode('.',$testString[5]);
					if(end($extTest) == 'zip' || end($extTest) == 'rar'){
						$_text = '<div><a href="'.$_text.'">'.$testString[5].'</a></div>';
					}else{
						$_text = '<div><a href="'.$_text.'"><img style="max-width: 220px" height="86px;" src="'.$_text.'"></a></div>';
					}							
				}
				
				$tpl->fory_cycle(array(	'date' => mysql_result($request,$i,2),
										'author' =>$message_author,
										'person' => $message_person,
										'status' => $message_status,
										'text' => $_text));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);	

			return(array('content' => $tpl->content));
		}
	}
?>