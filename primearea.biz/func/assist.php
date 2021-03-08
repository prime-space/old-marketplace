<?php
	function urlReplace($str,$return = false){
		if($return)return preg_replace('#<a href="(.*)" target="_blank">(.*)</a>#iuU', '$2', $str);
		else return preg_replace('#((https?|ftp)://)(.*)((<br)|(\s)|$)#iuU', '<a href="$1$3" target="_blank">$1$3</a>$4', $str);
	}
?>