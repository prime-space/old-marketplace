<?php
	if(!defined('PRIMEAREARU'))exit('Доступ запрещён');
	
	include_once $_SERVER['DOCUMENT_ROOT']."/modules/currency/currclass.php";

	$currency = $_COOKIE['curr'];
	if(!$currency){
		$currency = 4;
	}

	$current_convert = new current_convert();

	//Блок "Вы интересовались"
	$last_visits = $_COOKIE['visits'];
	$last_visits = explode(',', $last_visits);
	$tpl_last_visits = new templating(file_get_contents(TPL_DIR.'last_visits.tpl'));	

	$last_visits_content = $tpl_last_visits->ify('LAST_VISITS');
	
	if($last_visits[0]){//Последние просмотренные
		$tpl_last_visits->content = str_replace($last_visits_content['orig'], $last_visits_content['if'], $tpl_last_visits->content);
		$tpl_last_visits->fory('ITEMS');
		foreach ($last_visits as $key => $value) {
			
			if($value){
				$value = (int)$value;
				$request = $db->super_query("SELECT p.name, p.price, pic.path, pic.name as picname, p.curr, p.picture
                    FROM product p  
					JOIN picture pic ON pic.id = CASE WHEN p.picture = 0 THEN 1 ELSE p.picture END
					WHERE p.id = ".$value." LIMIT 1");
			
				if(!$request){
					continue;
				}

				$product_name = $request['name'];
				$product_price = $request['price'];
				$picture_path = $request['path'];
				$picture_name = $request['picname'];
				$curr = $request['curr'];
				$picture = $request['picture'];

				if($picture){
					$picture_name = $picture_name.'productshow.jpg';
					$pic_path = '/picture/'.$picture_path.$picture_name;
				}else{
					$pic_path = '/'.$picture_path.$picture_name;
				}
				$pic_path = $CONFIG['cdn'].$pic_path;
				$product_price = $current_convert->curr($product_price,$curr,$currency);

				$tpl_last_visits->fory_cycle(array(	
						'title' => $product_name,
						'image_url' => $pic_path,
						'amount' => $product_price,
						'id'     => $value
					)
				);
			}
		}
		
		$tpl_last_visits->content = str_replace($tpl_last_visits->fory_arr['model_tags'], $tpl_last_visits->fory_arr['content'], $tpl_last_visits->content);	
	
	}else{//нет последних
		$tpl_last_visits->content = str_replace($last_visits_content['orig'], $last_visits_content['else'], $tpl_last_visits->content);
	}


	//last_sells


	//Блок популярное		
?>
