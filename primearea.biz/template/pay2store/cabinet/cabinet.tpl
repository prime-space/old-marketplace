{{IF:merchant}}
<!--<div class="row">
	<div class="col-md-12">
		<div class="panel panel-headline" style="background-color: #fb8484;color: #fff;font-size: 16px;font-weight: bold;">
			<div class="panel-body">
				<div class="spoiler" >
					Внимание!<br>
					С 21.05 мы добавляем еще один сервер уведомлений о платежах. Его ip-адрес: 145.239.84.249. Просим вас добавить его в список разрешенных в ваших скриптах!
				</div>
			</div>
		</div>
	</div>
</div>-->
{{ELSE:merchant}}{{ENDIF:merchant}}
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-headline" style="background-color: #5cb85c;color: #fff;font-size: 16px;font-weight: bold;">
			<div class="panel-body">
				<div style="display:table;width:100%;margin-top: 3px;">
					<div style="display:table-cell;vertical-align: middle;">
						Уважаемые пользователи!
					</div>
					<div style="display:table-cell;text-align:right">
						<button class="btn" style="color: #212529;background-color: #f8f9fa;border-color: #f8f9fa;" onclick="$('#newsBox').collapse('toggle')">
							<i class="fa fa-unsorted"></i>
						</button>
					</div>
				</div>
				<div id="newsBox" class="spoiler collapse {expandWishes}" aria-expanded="true">
					Хорошие новости для вас!<br>
					Показываем нашу крутую разработку. Новая тогровая площадка с улучшенным движком <br>
					 Добро пожаловать на - <a href="https://crocuspay.com/" target="_blank" style="color:white;text-decoration:underline;">CROCUSPAY.COM</a>

					<div style="height:114px;width:100%;display:table">
						<div id="wishSended" style="display:none;text-align:center;height:114px;vertical-align:middle;width:100%">
							Пожелание отправлено!<br>
							<a onclick="panel.user.wishes.oneMore();" style="cursor:pointer;color: #ffffff;text-decoration: underline;">Отправить еще одно</a>
						</div>
						<div id="wishForm" style="display:table-cell">
							<form onsubmit="panel.user.wishes.send(this);return false;">
								<div><textarea name="text" style="width:100%" rows="3" maxlength="1000"></textarea></div>
								<div id="panel_wishsend_info"></div>
								<div><button class="btn btn-info" name="button">Отправить пожелание</button></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">

	<div class="col-md-3">

		<div class="panel block1">
			<div class="panel-body no-padding">
				<p class="title" style="color: #27a75e;margin-top: 28.5px;">Мой баланс:</p>
				<p class="balanceadmin" style="font-size: 22px;">{wmr} руб.</p>
				<hr>
				<p class="title" style="margin-top: 28.5px;">На удержании: <span class="span_info span_watch hidden-xs" style="margin-top: -2px;height: 18px;width: 18px;line-height: 18px;" data-toggle="tooltip" data-placement="top" data-original-title="Средства на удержании. В скором времени зачислятся на ваш баланс">?</span></p>
				<p class="balancewait" style="font-size: 18px;">{wmr_left} руб.</p>
			</div>
		</div>

	</div>

	<div class="col-md-9">
		<div class="panel panel-headline panel-blue">
			<div class="panel-heading">
				<div class="col-md-5">
					<h3 class="panel-title">Показатели статистики</h3>
				</div>
				<div class="col-md-7 block-1" style="max-height:1000px;min-height:32px;">
					<a onclick="showSpoiler(this, true, 'stat'); return false;" class="btn btn-success"><i class="sprite sprite_panel_mt"></i><span class="center">Показатели статистики</span></a>
					<div class="spoiler" style="display:inline-block;padding:0;">
						<select class="chartPeriod">
							<option value="0">Сегодня</div></option>
							<option value="1"><div class="btn btn-primary btn-sm">Вчера</div></option>
							<option value="2"><div class="btn btn-primary btn-sm">Неделя</div></option>
							<option value="3"><div class="btn btn-primary btn-sm">Месяц</div></option>
							<option value="4"><div class="btn btn-primary btn-sm">Квартал</div></option>
							<option value="5"><div class="btn btn-primary btn-sm">Год</div></option>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="panel-body">
				<div class="row" id="user_cabinetStat">

				</div>
			</div>
		</div>
	</div>

</div>


<div class="row" style="overflow:hidden;">

	<div class="col-md-12">
		<div class="panel panel-headline spoiler_graph">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="#" onclick="showSpoiler2(this, true, 'graph'); return false;"><i class="fa fa-chevron-down margin-right-10"></i>Продажи на общую сумму</a></h3>
			</div>
			<div class="panel-body">
				<div class="spoiler">
					<div id="salesCharts">
						<div id="salesGraph"></div>
						<div style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
						<div style="display:inline-block;margin-top:15px;vertical-align:top;"></div>
					</div>
					<div id="panel_user_blacklist_add_info"></div>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="row" id="reviews-top-sales" style="overflow:hidden;">

	<div class="col-md-6">
		<div class="panel panel-headline spoiler_reviews">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="#" onclick="showSpoiler2(this, true, 'reviews'); return false;"><i class="fa fa-chevron-down margin-right-10"></i>Отзывы</a></h3>
			</div>
			<div class="panel-body">
				<div class="spoiler" id="reviewsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;width:100%;"></div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-headline spoiler_topsales">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="#" onclick="showSpoiler2(this, true, 'topsales'); return false;"><i class="fa fa-chevron-down margin-right-10"></i>Топ продаж</a></h3>
			</div>
			<div class="panel-body">
				<div class="spoiler" id="productsGraph" style="display:inline-block;margin-top:15px;vertical-align:top;width:100%;"></div>
			</div>
		</div>
	</div>

</div>
<div class="row">

	<div class="col-md-12">
		<div class="panel panel-headline spoiler_sales">
			<div class="panel-heading">
				<h3 class="panel-title"><a href="#" onclick="showSpoiler2(this, true, 'sales'); return false;"><i class="fa fa-chevron-down margin-right-10"></i>Последние операции</a></h3>
			</div>
			<div class="panel-body">
				<div class="spoiler" >
					<table class="table table-striped table_page">
						<tbody id="panel_user_cabinet_list">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="text-center">
			<div style="height: 100px"><a href="{more_operations}" class="btn btn-default" target="_BLANK">Показать больше операций</a></div>
		</div>
	</div>


</div>
