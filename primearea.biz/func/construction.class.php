<?php
	class construction{
		public $jsconfig = array();
		public $uvar = array();
		function __construct(){
			$part = explode('?', $_SERVER['REQUEST_URI']);
			$this->uvar = explode('/', $part[0]);
			array_shift($this->uvar);
		}
		public function init(){
			global $CONFIG, $construction, $bd, $db;

			$p = $_GET['p'] ? $_GET['p'] : $CONFIG['main_page_name'];
			$this->lastVisitsSetCookies($p, $_GET['productid'], $CONFIG);
			$this->jsconfig['module'] = $p;

			$is_merchant_panel = $p === 'merchant' && $this->uvar[1] !== 'pay' && $this->uvar[1] !== 'toreturn';

			if ( ($p === 'panel' && $_GET['sp'] !== 'addmoney_success') || $p === 'news' || $is_merchant_panel) {
				$tpl = new templating(file_get_contents(TPL_DIR . '/main/index_panel.tpl'));
				$w = new templating(file_get_contents(TPL_DIR.'/main/head/index_head_panel.tpl'));
				$wrapper = 'admin';
			}else{
				$tpl = new templating(file_get_contents(TPL_DIR . '/main/index.tpl'));
				if ( $p === 'merchant' && in_array($this->uvar[1], ['pay', 'toreturn']) ) {
					$w = new templating(file_get_contents(TPL_DIR.'/main/head/merchant_head.tpl'));
					$wrapper = 'merchant';
				}elseif ($p === 'promo') {
					$w = new templating(file_get_contents(TPL_DIR.'/main/head/index_head_promo.tpl'));
					$wrapper = 'promo';
				}else{
					 if ($p === 'customer' || $p === 'checkpromocode' || $p === 'buy'){
                        $w = new templating(file_get_contents(TPL_DIR.'/main/head/second_head.tpl'));
						$wrapper = 'second';
				}else{
                        $w = new templating(file_get_contents(TPL_DIR.'/main/head/index_head.tpl'));
						$wrapper = 'main';
     				}
				}
			}
			$w->set('{cdn}', $CONFIG['cdn']);
			$tpl->set('{index_head}', $w->content);

			$wrappertpl = new templating(file_get_contents(TPL_DIR.'/main/wrapper/wrapper'.$wrapper.'.tpl'));
			$tpl->set('{wrapper}', $wrappertpl->content);

			$wrapper !== 'second' ? include "modules/login/login.php" : '';
			if($wrapper === 'main'){
				$tpl->set('{userinfo}', $userinfo);
				include "modules/category/category.php";
				$tpl->set('{category}', $category);
				include "modules/search/search.php";
				$tpl->set('{search}', $search);
				include "modules/stat/stat.php";
				$tpl->set('{statistic}', $statistic);
			}elseif($wrapper === 'second'){
				$tpl->set('{h}', $_GET['h']);
			}elseif($wrapper === 'admin' && $user->id){
				include "modules/sidebar/adminPanelSidebar.php";

				//variables from "adminPanelSidebar.php"
				$tpl->set('{userinfo}', $userinfo);
				$tpl->set('{avatar}', $avatar);
				$tpl->set('{login}', $user->login);
				$tpl->set('{id}', $user->id);
				$tpl->set('{full_name}', $user->fio);
				$tpl->set('{email_activation_message}', $acivation_message);
				$tpl->set('{blocked_user}', $blocked_user);
				$tpl->set('{no_merchant_shop}', $no_merchant_shop);
				$tpl->set('{menu_user}', $menuUser);
				$tpl->set('{menu_admin}', $menuAdmin);
				$tpl->set('{current_shop}',  $current_shop);
				$tpl->set('{numMessages}',$numMessages ? $numMessages : '');
				$tpl->set('{partnerNotifycation}',$partnerMessages || $partnerNotifications ? '!' : '');
				$tpl->set('{clientNotifycation}',$cabinetNegMessages || $cabinetMessages ? '!' : '');
				$tpl->set('{cabinetNotifycation}',$cabinetMsg ? '!' : '');
				$tpl->set('{cabinetMessages}', $cabinetMessages ? $cabinetMessages : '');
				$tpl->set('{cabinetNegMessages}', $cabinetNegMessages ? $cabinetNegMessages : '');
				$tpl->set('{cabinetMsg}', $cabinetMsg ? $cabinetMsg : '');

			}

			switch($p){
				case 'main': include 'modules/main/main.php'; break;
				case 'main2': include 'modules/main/main2.php'; break;
				case 'category': include 'modules/main/main.php'; break;

				case 'activation': include 'modules/main/activation.php'; break;
				case 'recover': include 'modules/main/recover.php'; break;
				case 'reset': include 'modules/main/reset.php'; break;
				case 'contact': include 'modules/pages/contact/contact.php'; break;
				case 'faq': include 'modules/pages/faq/faq.php'; break;
				case 'info': include 'modules/pages/info/info.php'; break;
				case 'sogl': include 'modules/pages/sogl/sogl.php'; break;
				case 'garant': include 'modules/pages/garant.php'; break;
				case 'custquestion': include 'modules/pages/custquestion.php'; break;
				case 'rules': include 'modules/pages/rules.php'; break;
				case 'faq-cust': include 'modules/pages/faq-cust.php'; break;
				case 'sellquestion': include 'modules/pages/sellquestion.php'; break;
				case 'faq-seller': include 'modules/pages/faq-seller.php'; break;
				case 'recom': include 'modules/pages/recom.php'; break;
				case 'arbitrage': include 'modules/pages/arbitrage.php'; break;

				case 'partnerfaq': include 'modules/pages/partnerfaq.php'; break;
				case 'customer': include 'modules/customer/customer.php'; break;
				case 'showproduct': include 'modules/shop/productShow/productShow.php'; break;
				case 'sellershow': include 'modules/shop/sellerShow/sellerShow.php'; break;
				case 'news': include 'modules/news.php'; break;
				case 'buy': include 'modules/buy/buy.php'; break;
				case 'checkpromocode': include 'modules/checkpromocode/checkpromocode.php'; break;
				case 'signup': include 'modules/reg/signup.php'; break;
				case 'signin': include 'modules/login/signin.php'; break;
				case 'merchant': include 'modules/merchant.php'; break;
				case 'panel':
					$this->jsconfig['sp'] = $_GET['sp'];
					switch($_GET['sp']){
						case 'myshop': include 'panel/user/myshop/myshop.php'; break;
						case 'productedit': include 'panel/user/productedit/productedit.php'; break;
						case 'productadd': include 'panel/user/productadd/productadd.php'; break;
						case 'productobj': include 'panel/user/productobj/productobj.php'; break;
						case 'currencies': include 'panel/user/currencies/currencies.php'; break;
						case 'cabinet': include 'panel/user/cabinet/cabinet.php'; break;
						case 'cashout': include 'panel/user/cashout/cashout.php'; break;
						case 'review': include 'panel/user/review/review.php'; break;
						case 'blacklist': include 'panel/user/blacklist/blacklist.php'; break;
						case 'discount': include 'panel/user/discount/discount.php'; break;
						case 'promocodes': include 'panel/user/promocodes/promocodes.php'; break;
						case 'shopsite': include 'panel/user/shopsite/shopsite.php'; break;
						case 'recommended': include 'panel/user/recommended/recommended.php'; break;
						case 'messages': include 'panel/glob/messages/messages.php'; break;
						case 'news': include 'panel/glob/news.php'; break;
						case 'addmoney': include 'panel/user/addmoney/addmoney.php'; break;
						case 'addmoney_success': include 'panel/user/addmoney/addmoney_success.php'; break;
						case 'partner': include 'panel/user/partner/partner.php'; break;
						case 'api': include 'panel/user/api.php'; break;
						case 'sales': include 'panel/user/sales.php'; break;

						case 'group': include 'panel/admin/group/group.php'; break;
						case 'bookkeeping': include 'panel/admin/bookkeeping/bookkeeping.php'; break;
						case 'order': include 'panel/admin/order/order.php'; break;

						case 'messageview': include 'panel/moder/messageview/messageview.php'; break;
						case 'reviewdelete': include 'panel/moder/reviewdelete/reviewdelete.php'; break;
						case 'newproducts': include 'panel/moder/newproducts/newproducts.php'; break;
						case 'moderate': include 'panel/moder/newproducts/moderate.php'; break;
						case 'newusers': include 'panel/moder/newusers/newusers.php'; break;
                        case 'hiddenproducts': include 'panel/moder/hiddenproducts/page.php'; break;

						default: header("Location:/error404/");
					}break;
				case 'partcode': include 'modules/pages/partcode.php'; break;
				case 'error404': include 'modules/pages/error404/error404.php'; break;
				case 'payment-error': include 'modules/pages/payment/payment.php'; break;
				case 'promo': include 'template/default/pages/promo.tpl'; break;
				case 'twitch': include 'modules/pages/twitch.php'; break;
				default: header("Location:/error404/");
			}

            if($wrapper === 'merchant'){
                $tpl->set('{mshopUrl}', $merchantShopUrl);
            }

			include "modules/popup/popup.php";
			include "modules/main/sidebar.php";

			$HEAD['title'] = $HEAD['title'] ? $HEAD['title'] : $CONFIG['title'];
			$HEAD['keywords'] = $HEAD['keywords'] ? $HEAD['keywords'] : 'купить ключи, ,steam, origin, uplay, gifts, cs go, gta v, rust, norton, fifa, random key, Dead by Daylight,ключи steam оптом, norton internet security купить, купить цифровые товары, steam аккаунты купить дешево, магазин цифровых товаров steam, стим аккаунт купить,купить рандом ключи steam,купить ключ для nod32, купить компьютерные игры, магазин steam, магазин стим, онлайн магазин игр, primearea, прайм, праймареа, магазин компьютерных игр, лицензионные игры, купить ключ активации, купить steam, steam игры, купить ключи steam, купить игры на пк, купить ключ стим, продать игры, продать товар, онлайн продажа игр';
			$HEAD['description'] = $HEAD['description'] ? $HEAD['description'] : 'Торговая площадка '.$CONFIG['site_domen'].' – уникальное место встречи продавца и покупателя, которые заинтересованы в оперативных, удобных и надежных операциях купли/продажи цифровых товаров steam, origin, uplay, gifts, cs go, gta v, rust, norton, fifa, random key, Dead by Daylight. Здесь можно как выставить товар на продажу, так и приобрести его.';

			$tpl->set('{jsconfig}', json_encode($this->jsconfig));
			$tpl->set('{version}', $CONFIG['version']);

			$tpl->set('{breadcrumbs}', $breadcrumbs);
			$tpl->set('{page}', $page);

			if( $page == 'Необходимо авторизоваться' ){
				header('Location: /signin/');
				exit;
			}


			if( $this->uvar[1] == 'pay' ) {
				$tpl->set('{popup}', '' );
			} else {
				$tpl->set('{popup}', $popup->content);
			}

			$tpl->set('{sellerbuttons}', $sellerbuttons);

			//Сайдбар
			$tpl->set('{visited}', $tpl_last_visits->content);

			$sidebarPopular = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/sidebar_popular_sells.html");
			$tpl->set('{popular}', $sidebarPopular);

			$currency = $_COOKIE['curr'];
			if(!$currency){
				$currency = 4;
			}

			if($currency == 4){
				$sidebarlast_sells = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/sidebar_last_sells.html");
			}elseif($currency == 1){
				$sidebarlast_sells = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/sidebar_last_sells_usd.html");
			}elseif($currency == 2){
				$sidebarlast_sells = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/sidebar_last_sells_grn.html");
			}elseif($currency == 3){
				$sidebarlast_sells = file_get_contents($_SERVER['DOCUMENT_ROOT']."/cache/sidebar_last_sells_eur.html");
			}

			$tpl->set('{last_sells}', $sidebarlast_sells);
			// /сайдбар

			$tpl->set('{title}', $HEAD['title']);
			$tpl->set('{keywords}', $HEAD['keywords']);
			$tpl->set('{description}', $HEAD['description']);
			echo $tpl->content;
		}
		public function urlReplace($str,$return = false){
			if($return)return preg_replace('#<a href="(.*)" target="_blank">(.*)</a>#iuU', '$2', $str);
			else return preg_replace('#((https?|ftp)://)(.*)((<br)|(\s)|$)#iuU', '<a href="$1$3" target="_blank">$1$3</a>$4', $str);
		}
		public function errorpage(){
			header("Location:/error404/");
			exit();
		}

		public function lastVisitsSetCookies($p, $product_id, $CONFIG){

			if ($p == 'showproduct') {
				$product_id = (int)$product_id;
				$last_visits = $_COOKIE['visits'];
				$last_visits = explode(',', $last_visits);

				if ($last_visits[0] != $product_id ) {
					array_unshift($last_visits, $product_id);
				}

				if (count($last_visits) > 5) {
					array_pop($last_visits);
				}

				$last_visits = implode(',', $last_visits);

				SetCookie('visits', $last_visits, (time()+86400*365), '/', $CONFIG['site_domen'], $CONFIG['cookie_SSL'], true);
			}
		}

	}
?>
