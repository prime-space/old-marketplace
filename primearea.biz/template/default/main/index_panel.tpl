<!DOCTYPE html>
<html>
	<head>

		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>{title}</title>
		<meta name="Description" content="{description}">
		<meta name="Keywords" content="{keywords}">
		<meta name="webmoney" content="4D53B2B3-10C8-4133-96AE-E1FF2D05A4E7">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

		<link rel="SHORTCUT ICON" href="/style/img/favicon.ico" type="image/x-icon">
		<script type="text/javascript">var jsconfig = '{jsconfig}';</script>

		{index_head}

		<link href="/style/merchant/css/bootstrap.min.css?v={version}" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="/style/css/font-awesome.min.css?v={version}">
		<link rel="stylesheet" href="/style/linearicons/style.min.css?v={version}">
		<link rel="stylesheet" href="/style/css/chartist-custom.min.css?v={version}">
		<link rel="stylesheet" href="/style/css/main-panel.min.css?v={version}">
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">


	</head>
	<body>
	<div id="wrapper">
		{popup}
		{wrapper}

		<div class="clear"></div>
		<footer>
			<div class="col-md-9">
				<div class="footer-links">
					<a href="/">Главная</a>
					<a href="/news">Новости</a>
					<a href="https://primearea.biz/contact/">Контакты</a>
				</div>
				<p class="copyright">© 2017 <a href="/" target="_blank">PRIMEAREA.BIZ</a>. All Rights Reserved.</p>
			</div>
			<div class="col-md-3">
				<div class="social-icons">
					<p>Мы в социальных сетях</p>
					<a href="https://vk.com/primearea_biz"><i class="fa fa-vk">&nbsp;</i></a>
					<a href="#"><i class="fa fa-facebook">&nbsp;</i></a>
					<a href="#"><i class="fa fa-instagram">&nbsp;</i></a>

				</div>
			</div>
		</footer>


		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery.slimscroll.min.js"></script>
		<script src="/js/jquery.easypiechart.min.js"></script>
		<script src="/js/chartist.min.js"></script>
		<script src="/js/klorofil-common.min.js"></script>
		<script src="/js/jquery.nicescroll.min.js"></script>
		<script>
			$(document).ready(function(){
				if( $('#accountMerchantLink').length ){
					$('#blockProSeller').hide();
					$('#blockProSeller').parents('.pa_head_c_r').css({'text-align':'right'});
				}
				if( $('#Center_Form .pa_middle_c_l_b_head_7').text() == 'НОВОСТИ' ){
					main.swfUploadReady(false, 'SWFuploadPictureDiv3');
				}
				var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
				if(mobile){
					$('#popup').css('position','absolute');
					$('#popup').height();
				}
			});
		</script>


		<!-- Yandex.Metrika counter --><script type="text/javascript">(function(d,w,c){(w[c] = w[c]||[]).push(function(){try{w.yaCounter22637731=new Ya.Metrika({id:22637731,webvisor:true,clickmap:true,trackLinks:true,accurateTrackBounce:true,trackHash:true});}catch(e){}});var n=d.getElementsByTagName("script")[0],s=d.createElement("script"),f=function(){n.parentNode.insertBefore(s,n);};s.type="text/javascript";s.async=true;s.src=(d.location.protocol=="https:"?"https:":"http:")+"//mc.yandex.ru/metrika/watch.js";if(w.opera=="[object Opera]"){d.addEventListener("DOMContentLoaded",f,false);}else{f();}})(document,window,"yandex_metrika_callbacks");</script>
		<noscript><div><img src="//mc.yandex.ru/watch/22637731" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- uptolike --><script async="async" src="https://w.uptolike.com/widgets/v1/zp.js?pid=1307775" type="text/javascript"></script>
		<!-- GoogleAnalytics --><script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create','UA-56242302-1','auto');ga('send','pageview');</script>
		</div>
	</body>
</html>
