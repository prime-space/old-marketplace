<?php

class signin{
	public function signuppage(){
		global $db;

		$tpl = new templating( file_get_contents( TPL_DIR . 'signin.tpl' ) );

		return $tpl->content;

	}

}

?>