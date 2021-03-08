<?php
	class shopsite{
		public static function page(){
			global $user;

			$tpl = new templating(file_get_contents(TPL_DIR.'shopsite.tpl'));
			$tpl->set('{userid}', $user->id);
			
			return(array('content' => $tpl->content));
		}
	}
?>