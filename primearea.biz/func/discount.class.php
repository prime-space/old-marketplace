<?php
	class discount{
		public static function page(){
			
			$tpl = new templating(file_get_contents(TPL_DIR.'discount.tpl'));
			$tpl->set('{random}', md5(time()));
			
			return(array('content' => $tpl->content));
		}
	}
?>