

<nav class="navbar navbar-default navbar-fixed-top btn-toggle-fullwidth">
	<div class="container-fluid">
		<div class="navbar-btn">
			<button type="button" class=""><i class="lnr lnr-arrow-left-circle"></i> Меню</button>
		</div>
	</div>
</nav>

<div id="sidebar-nav" class="sidebar">
	<div class="sidebar-scroll">

		<!-- Привилегии, настройки, выход -->
		<div class="pa_head_c_r">
			{userinfo}
		</div>

		<!-- Аватар -->
		<div class="avatar-menu">
			<div class="col-md-5">
				<div data-avatar='{avatar}' class="img-avatar-menu" id="SWFuploadPictureDiv3">
					<div id="uploadPictureButton"></div>
					<div id="SWFuploadPictureMessage"></div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="fio">{full_name}</div>
				<div class="extra">{login}, #{id}</div>
			</div>
			<div class="clear"></div>

			<div class="my-switcher">
				<div class="col-md-4">
					<span data-toggle="tooltip" data-placement="left" data-original-title="Удобный инструмент для создания Интернет-Магазина с одновременной передачей оплаченного товара или услуги." style="margin-top: -3px;cursor:pointer;margin-right: 2px;"><span class="hidden-xs span_info span_watch" style="line-height: 15px;margin-top: -5px;">?</span> Магазин</span>
				</div>
				<div class="col-md-4">
					<div class="form-switcher form-switcher-sm">
						<input type="checkbox" name="panel_mode" id="panel_mode" onclick="panel.user.cabinet.panel_mode_switch(this, event)">
						<label class="switcher" for="panel_mode"></label>
					</div>
				</div>
				<div class="col-md-4">
					<span data-toggle="tooltip" data-placement="left" data-original-title=" Универсальное решение по приему онлайн платежей. Позволяет организовать оплату на Вашем сайте со всех стран мира!" style="margin-top: -3px;cursor:pointer;margin-right: 2px;"><span class="hidden-xs span_info span_watch" style="line-height: 15px; margin-top: -5px;">?</span> Мерчант</span>
				</div>
				<div class="clear"></div>
			</div>


		</div>

		<nav>
			<ul class="nav" id="panel_menu">
				{menu_admin}
				{menu_user}
			</ul>
		</nav>


		<!-- Логгирование безпасности -->
		<div class="spoiler_4">
			<hr>
			<a href="#" id="logs_spoiler_show" onclick="panel.user.cabinet.show_logs(this);return false;"><span>Логирование безопасности</span></a>
		</div>

	</div>
</div>


<div class="main" id="main-block">
	<div class="main-content">

<!-- HEAD -->
<div id="pa_head" class="row">
	<div class="col-md-6 block-logo">
		<a href="/panel/cabinet/">
			<div class="col-md-2">
				<img src="/style/img/logopriv_pay2store.png">
			</div>
			<div class="col-md-9">
				<h2>PAY2STORE.COM</h2>
				<p>УНИВЕРСАЛЬНОЕ ПЛАТЕЖНОЕ РЕШЕНИЕ</p>
			</div>
		</a>
	</div>

	{{IF:merchant_menu}}
	<div class="col-md-4 text-center">
		<div class="choice-shop"><label for="status">Выбрать магазин</label></div>
		<select id="currentShop" onchange="panel.user.cabinet.mshop_set(this);">
			{{FOR:shops}}
			<option {selected} value="{shopid}">{mshopName}</option>
			{{ENDFOR:shops}}
		</select>
	</div>
	<style>#reviews-top-sales{display:none;}</style>
	{{ELSE:merchant_menu}}
		<div class="col-md-4"></div>
	{{ENDIF:merchant_menu}}


	<div class="col-md-2">
		<a onclick="panel.user.cabinet.news_popup(); return false;" href="/news/" class="pa_head_c_btn btn btn-default "><i class="sprite sprite_sog"></i> Новости</a>
	</div>



	</div>

	<div class="clear"></div>
	<hr>
	{email_activation_message}
	{blocked_user}
	{no_merchant_shop}

<!-- /HEAD -->

<!-- CENTER -->
<div id="pa_middle">

	<!-- CONTENT -->
	<div class="" id="Center_Form">

		<div class="" id="page">
			{page}
		</div>

	</div>
	<!-- /CONTENT -->

</div>
<!-- /CENTER -->

		</div>
	</div>
