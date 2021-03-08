<?php
	class currencies{
		public static function page(){
			global $bd;
			
			$request = $bd->read("SELECT `usd`, `eur`, `uah` FROM `currency` ORDER BY `id` DESC LIMIT 1");
			$usd = mysql_result($request,0,0);
			$eur = mysql_result($request,0,1);
			$uah = mysql_result($request,0,2);

			$request = $bd->read("SELECT * FROM `paymethod`");
			$pMethod = '';
			$pMethodArray = array();
			$pMethodN = 1;
			for( $i=0; $i < $bd->rows; $i++ ) {
				$name = mysql_result($request,$i,1);
				$url = mysql_result($request,$i,2);
				$imgurl = mysql_result($request,$i,3);

				$pMethodArray[$pMethodN] = <<<HTML
					<a class="One_pMethod" target="_blank" href="{$url}">
						<div class="pMethod_img"><img width="100px" src="/{$imgurl}"></div>
						<p class="btn btn-default">{$name}</p>
					</a>
HTML;

				if ( $pMethodN == 4 ) {
					$pMethod .= '<tr><td style="padding: 10px;">' . implode( '</td><td style="padding: 10px;">', $pMethodArray ) . '</td></tr>';
					$pMethodArray = array();
					$pMethodN = 1;

				} else {
					$pMethodN++;

				}

			}

			$tpl = new templating(file_get_contents(TPL_DIR.'currencies.tpl'));
			$tpl->set('{usd}', $usd);
			$tpl->set('{eur}', $eur);
			$tpl->set('{uah}', $uah);
			$tpl->set('{pMethod}', $pMethod);
			
			return(array('content' => $tpl->content));
		}
	}
?>