<div class="panel panel-headline">
	<div class="panel-heading">
		<h3 class="panel-title">Документация (Merchant)</h3>
	</div>
	<div class="panel-body">


		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="padding_10">1. Основные сведения</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="font_size_14 padding_10">
					Мерчант - интерфейс для приема платежей через интернет. Через единую точку входа он позволяет организовать оплату на Вашем сайте множеством платежных систем.
					Имеет простой протокол взаимодействия и легкую интеграцию в новый или уже работающий бизнес.<br>
				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">
					Для начала работы необходимо сделать несколько шагов:
					<ul class="ul_grecia">
						<li>зарегистрироваться на сайте {domain};</li>
						<li>добавить и настроить магазин в разделе Мерчант;</li>
						<li>установить на своем сайте HTML-форму для перенаправления на оплату;</li>
					</ul>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="padding_10">2. Настройка магазина</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="padding_10">

					<table class="table table-striped table_page_z table_page_b" style="margin-bottom: 10px;">
						<thead>
						<tr>
							<td class="font_size_12 padding_10" style="/*width: 280px;*/">Параметр</td>
							<td class="font_size_12 padding_10 text_align_c vertical_align" style="/*width: 135px;*/">Значение</td>
							<td class="font_size_12 padding_10">Описание</td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="padding_10 vertical_align">Название магазина</td>
							<td class="padding_10 text_align_c vertical_align">до 64 символов</td>
							<td class="padding_10 vertical_align">Название вашего магазина, которое будут видеть пользователи</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Адрес магазина</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Ссылка на главную страницу вашего магазина</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Краткое описание</td>
							<td class="padding_10 text_align_c vertical_align">до 256 символов</td>
							<td class="padding_10 vertical_align">Обобщенное описание продаваемых товаров вашим магазином</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Комиссию оплачивает</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align">Начислять комиссию сверх вашей цены пользователю или вычитать из вашей прибыли</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Секретная фраза</td>
							<td class="padding_10 text_align_c vertical_align">32 латинские буквы или цифры</td>
							<td class="padding_10 vertical_align">Секретная фраза для подписи ваших запросов</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Адрес перенаправления при успешной оплате</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Адрес, куда будет переадресован пользователь при успешной оплате</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Адрес перенаправления при ошибке оплаты</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Адрес, куда будет переадресован пользователь при ошибке оплаты</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Адрес отправки уведомлений</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Адрес, на который наша система будет отправлять уведомления о успешном проведении платежа</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Метод отправки данных</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align"></td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Разрешить переопределение адресов в запросе</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align">Разрешение на изменение адресов перенаправления пользователя в форме оплаты на вашем сайте</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">Тестовый режим</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align">Добавляет в список платежных систем кнопку тестовой оплаты</td>
						</tr>
						</tbody>
					</table>
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">После настройки магазина вы уже можете принимать платежи. Для получения всех платежных систем вам необходимо отправить магазин на проверку.</span>
					</div>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="padding_10">3. Форма перенаправления на оплату</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="font_size_14 padding_10">
					<div class="info_red_form">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Для перенаправления пользователя на оплату вы должны создать HTML-форму с методом POST на адрес {siteaddress}merchant/pay/</span>
					</div>
				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">

					<div style="font-weight: 700;margin-bottom: 10px;">3.1. Обязательные параметры</div>

					<table class="table table-striped table_page table_page_z table_page_b">
						<thead>
						<tr>
							<td class="padding_10 vertical_align">Параметр</td>
							<td class="padding_10 text_align_c vertical_align">Значение</td>
							<td class="padding_10 vertical_align">Описание</td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="padding_10 vertical_align">shopid</td>
							<td class="padding_10 text_align_c vertical_align">integer</td>
							<td class="padding_10 vertical_align">Идентификатор вашего магазина</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">payno</td>
							<td class="padding_10 text_align_c vertical_align">integer</td>
							<td class="padding_10 vertical_align">Номер платежа, не должен повторяться</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">amount</td>
							<td class="padding_10 text_align_c vertical_align">decimal(12,2)</td>
							<td class="padding_10 vertical_align">Цена товара</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">description</td>
							<td class="padding_10 text_align_c vertical_align">до 128 символов</td>
							<td class="padding_10 vertical_align">Описание товара</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">sign</td>
							<td class="padding_10 text_align_c vertical_align">64 символа</td>
							<td class="padding_10 vertical_align">Подпись платежа</td>
						</tr>
						</tbody>
					</table>

				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">

					<div style="font-weight: 700;margin-bottom: 10px;">3.2. Дополнительные параметры</div>

					<table class="table table-striped table_page table_page_z table_page_b">
						<thead>
						<tr>
							<td class="padding_10 vertical_align">Параметр</td>
							<td class="padding_10 text_align_c vertical_align">Значение</td>
							<td class="padding_10 vertical_align">Описание</td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="padding_10 vertical_align">via</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align" style="    word-break: break-all;">Автоматически выбранная платежная система. Список платежных систем для вашего магазина вы можете узнать на этой странице: {siteaddress}merchant/paysys/{shopid} <br> (выберите значение "via" из массива)</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">code</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align" style="    word-break: break-all;">Параметр только для методов skrill. Список платежных систем для вашего магазина вы можете узнать на этой странице: {siteaddress}merchant/paysys/{shopid} <br> (выберите значение "code" из массива)</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">success</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Адрес перенаправления пользователя в случае успешной оплаты</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">fail</td>
							<td class="padding_10 text_align_c vertical_align">URL</td>
							<td class="padding_10 vertical_align">Адрес перенаправления пользователя в случае ошибки оплаты</td>
						</tr>
						<tr>
							<td class="padding_10 vertical_align">uv_*</td>
							<td class="padding_10 text_align_c vertical_align"></td>
							<td class="padding_10 vertical_align">Пользовательские параметры</td>
						</tr>
						</tbody>
					</table>

				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">

					<div style="font-weight: 700;margin-bottom: 10px;">3.3. Пример создания формы на языке php</div>

<pre class="php" style="margin: 0 auto;">$secret = 'f6482bd9a166bf2s43ssc9fe60eb4774';

$data = array(
	'shopid' => 1,
	'payno' => 42,
	'amount' => 150.30,
	'description' => 'Книга Уи́льям Шекспи́р Гамлет'
);
ksort($data,SORT_STRING);
$sign = hash('sha256',implode(':',$data).':'.$secret);
echo '
	&lt;form method=&quot;POST&quot; action={siteaddress}merchant/pay/&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;shopid&quot; value=&quot;&apos;.$data[&apos;shopid&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;payno&quot; value=&quot;&apos;.$data[&apos;payno&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;amount&quot; value=&quot;&apos;.$data[&apos;amount&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;description&quot; value=&quot;&apos;.$data[&apos;description&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;sign&quot; value=&quot;&apos;.$sign.&apos;&quot;&gt;&lt;br&gt;
	&lt;button&gt;&Ocy;&pcy;&lcy;&acy;&tcy;&icy;&tcy;&softcy;&lt;/button&gt;
	&lt;/form&gt;
';
</pre>

				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">

					<div style="font-weight: 700;margin-bottom: 10px;">3.4. Пример с выбраной платежной системой</div>
					<p>В этом случае добавляется параметр "via" (см. пункт 3.2)</p>

<pre class="php" style="margin: 0 auto;">$secret = 'f6482bd9a166bf2s43ssc9fe60eb4774';

$data = array(
	'shopid' => 1,
	'payno' => 42,
	'amount' => 150.30,
	'description' => 'Книга Уи́льям Шекспи́р Гамлет',
	'via' => 'yandexmoney'
);
ksort($data,SORT_STRING);
$sign = hash('sha256',implode(':',$data).':'.$secret);
echo '
	&lt;form method=&quot;POST&quot; action={siteaddress}merchant/pay/ &gt;
	&lt;input type=&quot;hidden&quot; name=&quot;shopid&quot; value=&quot;&apos;.$data[&apos;shopid&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;payno&quot; value=&quot;&apos;.$data[&apos;payno&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;amount&quot; value=&quot;&apos;.$data[&apos;amount&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;description&quot; value=&quot;&apos;.$data[&apos;description&apos;].&apos;&quot;&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;sign&quot; value=&quot;&apos;.$sign.&apos;&quot;&gt;&lt;br&gt;
	&lt;input type=&quot;hidden&quot; name=&quot;via&quot; value=&quot;&apos;.$data[&apos;via&apos;].&apos;&quot;&gt;&lt;br&gt;
	&lt;button&gt;&Ocy;&pcy;&lcy;&acy;&tcy;&icy;&tcy;&softcy;&lt;/button&gt;
	&lt;/form&gt;
';
</pre>

				</td>
			</tr>
			<tr>
				<td class="font_size_14 padding_10">

					<div style="font-weight: 700;margin-bottom: 10px;">3.5. Подпись платежа</div>

					Для создания подписи платежа необходимо:
					<ul class="ul_grecia">
						<li>отсортировать массив отправляемых параметров в алфавитном порядке по ключу;</li>
						<li>соединить их значения через двоеточие, добавить секретный ключ через двоеточие;</li>
						<li>получить хеш-код sha256 от этой строки;</li>
					</ul>

				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="padding_10">4. Страницы возврата клиента</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="font_size_14 padding_10">

					Страницы, на которые переадресовывается пользователь после осуществления платежа.

					<div class="info_red_form" style="margin: 10px 0;">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">ВАЖНО! переадресация на SUCCESS URL не гарантирует успешное завершение платежа.<br>Перед передачей товара вы должны получить и проверить уведомление от нашей системы.</span>
					</div>

					На эти страницы передаются следующие параметры:
					<ul class="ul_circle">
						<li>shopid</li>
						<li>payno</li>
						<li>amount</li>
						<li>via</li>
						<li>uv_*</li>
					</ul>

				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table_page">
			<thead>
			<tr>
				<td class="padding_10">5. Уведомления о успешном платеже</td>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="font_size_14 padding_10">

					После зачисления средств на ваш счет в системе {domain} вам будет отправлено уведомление на указанный в настройках адрес.<br><br>

					Для принятия уведомления вам необходимо проверить следующие параметры:
					<ul class="ul_grecia" style="margin-bottom: 10px;">
						<li>ip-адреса серверов - всегда 109.120.152.109 или 145.239.84.249</li>
						<li>сумма платежа</li>
						<li>подпись уведомления</li>
					</ul>

					Для проверки подписи уведомления вам необходимо:
					<ul class="ul_grecia">
						<li>Убрать параметр sign</li>
						<li>Отсортировать полученные массив параметров в алфавитном порядке по ключу</li>
						<li>Соединить значения параметров через двоеточие, добавить секретный ключ через двоеточие</li>
						<li>Получить хеш-код sha256 от этой строки</li>
					</ul>

					<div class="info_red_form" style="margin: 10px 0;">
						<span class="span_info_red"><i>!</i></span>
						<span class="span_text_b"></span><span class="span_text">Если ваш сервер отвечает кодом 200, то система считает это принятием уведомления.<br>В противном случае, система отправляет еще 4 уведомления через каждые 10 минут, после чего отправляет сообщение на ваш email.</span>
					</div>

					На страницу передаются следующие параметры:
					<ul class="ul_circle" style="margin-bottom: 10px;">
						<li>shopid</li>
						<li>payno</li>
						<li>system_payno</li>
						<li>amount</li>
						<li>via</li>
						<li>date</li>
						<li>sign</li>
						<li>uv_*</li>
					</ul>

					Пример проверки уведомления на языке php:
<pre class="php" style="margin: 10px auto 0;">$secret = 'f6482bd9a166bf2s43ssc9fe60eb4774';
$amount = '150.30';
                
if(!in_array($_SERVER["REMOTE_ADDR"], ['109.120.152.109', '145.239.84.249'], true))exit();

if($_POST["amount"] !== $amount)exit();
                
$sign = $_POST['sign'];
unset($_POST['sign']);
ksort($_POST,SORT_STRING);
$signi = hash('sha256',implode(':',$_POST).':'.$secret);
if($signi !== $sign)exit();
exit();
</pre>
				</td>
			</tr>
			</tbody>
		</table>



	</div>
</div>
