<?php
	class pagination{
		/*Кол-во продуктов, кол-во позиций для пподгрузки, необходимая страница, функция для javascript*/
		public static function getPanel($product_count, $elements_on_page, $current_list, $function, $conf = false){
			global $CONFIG;
			$tpl = new tpl('pagination');
			$tpl->fory('PAGE_NO');
			$pages = ceil($product_count / $elements_on_page);
			
			$start_i = $current_list < 3 ? 1 : $current_list - 2;
			$end_i = ($pages-$start_i) > 5 ? $start_i+6 : $start_i+$pages;
			$end_i = $end_i <= $pages ? $end_i : $pages;
			
			if($current_list > 3){
				$dots = $current_list == 4 ? '' : '<li class=""><span>...</span></li>';
				$tpl->foryCycle(array(	'p' => '1',
												'dats_previous' => '',
												'class' => '',
												'page_no_event' => $function.'(0, \'gotoanchor\');',
												'dats_next' => $dots));
			}		
			for($i=$start_i;$i<=$end_i;$i++){
				$link_class = ($current_list+1) == $i ? 'active' : '';
				$tpl->foryCycle(array(	'p' => $i, 
												'class' => $link_class, 
												'page_no_event' => $function.'('.($i-1).', \'gotoanchor\');',
												'dats_previous' => '',
												'dats_next' => ''));
			}
			if($pages > 5 && ($current_list+1) < ($pages - 3)){
				$dots = $current_list == ($pages-5) ? '' : '<li class=""><span>...</span></li>';
				$tpl->foryCycle(array(	'p' => $pages,
												'page_no_event' => $function.'('.($pages-1).', \'gotoanchor\');',
												'dats_previous' => $dots,
												'class' => '',
												'dats_next' => ''));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);
			
			$tpl->fory('POSITION_ON_PAGE');
			$amount_variant = array(1, 10, 25, 50, 100);
			for($i=0;$i<count($amount_variant);$i++){
				$amount = $amount_variant[$i];
				$selected = $amount_variant[$i] == $elements_on_page ? $selected = "selected" : "";
				$tpl->foryCycle(array(	'amount' 	=> 	$amount,
										'selected' 	=> 	$selected));
			}
			$tpl->content = str_replace($tpl->fory_arr['model_tags'], $tpl->fory_arr['content'], $tpl->content);		
			
			
			$tpl->content = str_replace("{site_address}", $CONFIG['site_address'], $tpl->content);
			$tpl->content = str_replace("{pages}", $pages, $tpl->content);
			$tpl->content = str_replace("{function}", $function, $tpl->content);
			$tpl->content = str_replace("{select_event}", $function.'(0, \'gotoanchor\');', $tpl->content);
			$tpl->content = str_replace("{add}", $function.'('.($current_list+1).', \'add\');', $tpl->content);	
			
			$load_more = $tpl->ify('LOAD_MORE');
			$seop = $tpl->ify('SELECT_ELEMENTS_ON_PAGE');
			if($conf == 'only_pages'){
				$tpl->content = str_replace($load_more['orig'], $load_more['else'], $tpl->content);
				$tpl->content = str_replace($seop['orig'], $seop['else'], $tpl->content);
			}else{
				$tpl->content = str_replace($load_more['orig'], $load_more[$product_count?'if':'else'], $tpl->content);
				$tpl->content = str_replace($seop['orig'], $seop['if'], $tpl->content);		
			}
			
			return $tpl->content;
		}
	}
?>