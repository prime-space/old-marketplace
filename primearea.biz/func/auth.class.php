<?php
	class auth{
		public function signuppage(){
			global $db;
			
			$tpl = new templating(file_get_contents(TPL_DIR.'signup.tpl'));
			
			return $tpl->content;
		}
		public static function encodePassword($pass)
        {
            global $CONFIG;

            $encodedPass = hash('sha256', $pass . $CONFIG['salt']);

            return $encodedPass;
        }
	}
?>
