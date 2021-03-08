<?php
	class review{
		public static function page(){
			global $user,$bd;
			$tpl = new templating(file_get_contents(TPL_DIR.'review.tpl'));
			
			require_once $_SERVER['DOCUMENT_ROOT'].'/func/customer.class.php';
			
			$customer = new customer;
			$cabinetNegMessages = $customer->numNegMessages($user->id);

			$cabinetMessages = $customer->numNewReviews($user->id);

			if($cabinetMessages == 0){
				$cabinetMessages = '';
			}

			$tpl->set('{badReviewCount}', $cabinetNegMessages);
			$tpl->set('{newReviewCount}', $cabinetMessages);
			return(array('content' => $tpl->content));
		}
		public function deletepage(){
			$tpl = new templating(file_get_contents(TPL_DIR.'reviewdelete.tpl'));
			
			return(array('content' => $tpl->content));
		}
	}
?>