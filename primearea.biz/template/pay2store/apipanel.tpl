<div class="pa_middle_c_l_b_head">
    <div class="sprite sprite_page"></div>
    <div class="pa_middle_c_l_b_head_7">API</div>
</div>

<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td style="width: 200px;" class="font_weight_700 padding_10 text_align_r vertical_align">Идентификатор(ID):</td>
        <td class="padding_10 vertical_align">{userId}</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Секретный ключ(key):</td>
        <td class="padding_10 vertical_align">{key}</td>
    </tr>
    <tr>
        <td class="padding_10"></td>
        <td class="padding_10 vertical_align">
            <form onsubmit="panel.user.apipanel.generateKey(this, {generateAgain});return false;">
                <button class="btn btn-small btn-info" name="button">{buttonGenerate}</button>
            </form>
        </td>
    </tr>
    </tbody>
</table>

<table class="table table-striped table_page">
    <thead>
    <tr>
        <td colspan="2" class="padding_10 text_align_c">Все запросы к API принимаются по следующему виду URL: <input type="text" readonly="readonly" style="font-weight: 300;padding: 4px;text-align: center;width: 430px;" value="{siteAddr}api/version/module/method"></td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 200px;" class="font_weight_700 padding_10 text_align_r vertical_align">Метод запросов:</td>
        <td class="padding_10 vertical_align">POST</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Формат ответа:</td>
        <td class="padding_10 vertical_align">JSON</td>
    </tr>
    <tr>
        <td colspan="2" class="padding_10 vertical_align">
            Каждый запрос должен содержать POST параметры <strong>ID</strong> - ваш идентификатор и <strong>key</strong> - ваш секретный ключ в 64 символа, который вы можете сгенерировать на этой странице.<br>
            Каждый ответ содержит статус обработки запроса <strong>status</strong> и может быть <strong>ok</strong> и <strong>error</strong>.<br>
            При статусе обработки <strong>error</strong> ответ так же содержит номер ошибки <strong>code</strong> и ее описание <strong>message</strong>.
        </td>
    </tr>
    </tbody>
</table>

<div class="pa_middle_c_l_b_head no_radius_border">
    <div class="sprite sprite_page"></div>
    <div class="pa_middle_c_l_b_head_7">Список общих ошибок</div>
</div>

<table class="table table-striped table_page">
    <thead>
    <tr>
        <td class="padding_10 text_align_c">code</td>
        <td class="padding_10">Описание</td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 50px;" class="font_weight_700 padding_10 text_align_c vertical_align">51</td>
        <td class="padding_10 vertical_align">Отстутствует версия API</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">52</td>
        <td class="padding_10 vertical_align">Неизвестная версия API</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">53</td>
        <td class="padding_10 vertical_align">Отстутствует название модуля</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">54</td>
        <td class="padding_10 vertical_align">Отстутствует название метода</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">55</td>
        <td class="padding_10 vertical_align">Неизвестный модуль</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">56</td>
        <td class="padding_10 vertical_align">Неизвестный метод</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">60</td>
        <td class="padding_10 vertical_align">Ошибка авторизации. Отстутствует id</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">61</td>
        <td class="padding_10 vertical_align">Ошибка авторизации. Отстутствует key</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_c vertical_align">62</td>
        <td class="padding_10 vertical_align">Ошибка авторизации. Неверный id или key</td>
    </tr>
    </tbody>
</table>

<div class="pa_middle_c_l_b_head no_radius_border">
    <div class="sprite sprite_page"></div>
    <div class="pa_middle_c_l_b_head_7">Пример запроса на языке php</div>
</div>

<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td class="padding_10">
<pre class="php" style="margin: 0 auto;">
$url = '{siteAddr}api/v1/partner/getGroup/';
$params = array(
    'id'        => '1',
    'key'        => '9fef71c1ed04c0fe4ff8291dfd72d6007d12fec17ab10dae70cc358f012ee304',
    'groupId'    => '29'
);
$result = file_get_contents( $url, false, stream_context_create( array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query( $params )
    )
)));
echo $result;
</pre>
        </td>
    </tr>
    </tbody>
</table>

<div class="pa_middle_c_l_b_head no_radius_border">
    <div class="sprite sprite_page"></div>
    <div class="pa_middle_c_l_b_head_7">Документация API Версия v1</div>
</div>

<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td style="width: 160px;" class="font_weight_700 padding_10 text_align_r vertical_align">Название:</td>
        <td class="padding_10 vertical_align">Модуль партнерской программы</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Метод:</td>
        <td class="padding_10 vertical_align"><b>getGroup</b></td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
        <td class="padding_10 vertical_align">Запрос позволяет получить список товаров из определенной группы.</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">URL:</td>
        <td class="padding_10 vertical_align">{siteAddr}api/v1/partner/getGroup/</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Список параметров:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">groupId</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Идентификатор группы</td>
                </tr>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">hideMissing (Опционально)</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(1 или 0) Скрыть товары без доступных объектов продажи</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Успешный ответ содержит параметры:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">group</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">

                        <table class="table table-striped table_page table_page_b">
                            <thead>
                            <tr>
                                <td colspan="2" style="width: 100px;" class="font_weight_700 padding_10 vertical_align">(array) Массив со списком товаров:</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">productId:</td>
                                <td class="padding_10 vertical_align">Идентификатор</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">name:</td>
                                <td class="padding_10 vertical_align">Название</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">price:</td>
                                <td class="padding_10 vertical_align">Цена</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">currency:</td>
                                <td class="padding_10 vertical_align">Код Валюты</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">inStock:</td>
                                <td class="padding_10 vertical_align">Наличие(1 или 0)</td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Возможные ошибки:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <thead>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_c vertical_align">code</td>
                    <td class="padding_10 vertical_align">Описание</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">1</td>
                    <td class="padding_10 vertical_align">Отсутствует идентификатор группы</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">2</td>
                    <td class="padding_10 vertical_align">Группа не найдена</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    </tbody>
</table>
<hr>
<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td style="width: 160px;" class="font_weight_700 padding_10 text_align_r vertical_align">Название:</td>
        <td class="padding_10 vertical_align">Модуль партнерской программы</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Метод:</td>
        <td class="padding_10 vertical_align"><b>getProduct</b></td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
        <td class="padding_10 vertical_align">Запрос позволяет получить детальную информацию по товару.</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">URL:</td>
        <td class="padding_10 vertical_align">{siteAddr}api/v1/partner/getProduct/</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Список параметров:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">productId</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Идентификатор товара</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Успешный ответ содержит параметры:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">product</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">

                        <table class="table table-striped table_page table_page_b">
                            <thead>
                            <tr>
                                <td colspan="2" style="width: 100px;" class="font_weight_700 padding_10 vertical_align">(array) Массив с детальной информацией:</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">id:</td>
                                <td class="padding_10 vertical_align">Идентификатор</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">name:</td>
                                <td class="padding_10 vertical_align">Название</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">description:</td>
                                <td class="padding_10 vertical_align">Описание</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">info:</td>
                                <td class="padding_10 vertical_align">Дополнительная информация</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">sale:</td>
                                <td class="padding_10 vertical_align">Кол-во продаж</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">currency:</td>
                                <td class="padding_10 vertical_align">Код Валюты</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">price:</td>
                                <td class="padding_10 vertical_align">Цена</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">picture:</td>
                                <td class="padding_10 vertical_align">Url изображения</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">buy:</td>
                                <td class="padding_10 vertical_align">Url страницы покупки</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">inStock:</td>
                                <td class="padding_10 vertical_align">Наличие(1 или 0)</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">sellerId:</td>
                                <td class="padding_10 vertical_align">Идентификатор продавца</td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Возможные ошибки:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <thead>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_c vertical_align">code</td>
                    <td class="padding_10 vertical_align">Описание</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">1</td>
                    <td class="padding_10 vertical_align">Отсутствует идентификатор группы</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">2</td>
                    <td class="padding_10 vertical_align">Группа не найдена</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    </tbody>
</table>
<hr>
<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td style="width: 160px;" class="font_weight_700 padding_10 text_align_r vertical_align">Название:</td>
        <td class="padding_10 vertical_align">Модуль партнерской программы</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Метод:</td>
        <td class="padding_10 vertical_align"><b>getReviews</b></td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
        <td class="padding_10 vertical_align">Запрос позволяет получить отзывы по товару.</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">URL:</td>
        <td class="padding_10 vertical_align">{siteAddr}api/v1/partner/getReviews/</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Список параметров:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">productId</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Идентификатор товара</td>
                </tr>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">limit (Опционально)</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Кол-во отзывов от 1 до 50, по умолчанию 10</td>
                </tr>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">offset (Опционально)</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Смещение, по умолчанию 0</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Успешный ответ содержит параметры:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">
            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">total</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">Отзывов всего</td>
                </tr>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">reviews</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">

                        <table class="table table-striped table_page table_page_b">
                            <thead>
                            <tr>
                                <td colspan="2" style="width: 100px;" class="font_weight_700 padding_10 vertical_align">(array) Массив с отзывами:</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">id:</td>
                                <td class="padding_10 vertical_align">Идентификатор</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">text:</td>
                                <td class="padding_10 vertical_align">Текст</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">good:</td>
                                <td class="padding_10 vertical_align">Положительный отзыв (1 или 0)</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">date:</td>
                                <td class="padding_10 vertical_align">Дата</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">answer:</td>
                                <td class="padding_10 vertical_align">
                                    Если к отзыву есть ответ продавца, то ответ будет содержать этот параметр,
                                    содержащий в себе элементы text и date
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Возможные ошибки:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <thead>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_c vertical_align">code</td>
                    <td class="padding_10 vertical_align">Описание</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">1</td>
                    <td class="padding_10 vertical_align">Отсутствует идентификатор товара</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">2</td>
                    <td class="padding_10 vertical_align">Товар не наден</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">3</td>
                    <td class="padding_10 vertical_align">Неверное значение параметра limit</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">4</td>
                    <td class="padding_10 vertical_align">Неверное значение параметра offset</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    </tbody>
</table>
<hr>
<table class="table table-striped table_page">
    <tbody>
    <tr>
        <td style="width: 160px;" class="font_weight_700 padding_10 text_align_r vertical_align">Название:</td>
        <td class="padding_10 vertical_align">Модуль партнерской программы</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Метод:</td>
        <td class="padding_10 vertical_align"><b>getSeller</b></td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
        <td class="padding_10 vertical_align">Запрос позволяет получить данные по продавцу.</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r vertical_align">URL:</td>
        <td class="padding_10 vertical_align">{siteAddr}api/v1/partner/getSeller/</td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Список параметров:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">sellerId</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r vertical_align">Описание:</td>
                    <td class="padding_10 vertical_align">(integer) Идентификатор продавца</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Успешный ответ содержит параметры:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">
            <table class="table table-striped table_page table_page_b">
                <tbody>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">total</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">Отзывов всего</td>
                </tr>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">Параметр:</td>
                    <td class="padding_10 vertical_align">reviews</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_r">Описание:</td>
                    <td class="padding_b_0">

                        <table class="table table-striped table_page table_page_b">
                            <thead>
                            <tr>
                                <td colspan="2" style="width: 100px;" class="font_weight_700 padding_10 vertical_align">(array) Массив с детальной информацией:</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 100px;" class="font_weight_700 padding_10 text_align_r vertical_align">id:</td>
                                <td class="padding_10 vertical_align">Идентификатор</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">nick:</td>
                                <td class="padding_10 vertical_align">Псевдоним</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">fio:</td>
                                <td class="padding_10 vertical_align">ФИО</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">regDate:</td>
                                <td class="padding_10 vertical_align">Дата регистрации</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">skype:</td>
                                <td class="padding_10 vertical_align">Skype</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">rating:</td>
                                <td class="padding_10 vertical_align">Рейтинг</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">salesNum:</td>
                                <td class="padding_10 vertical_align">Количество продаж</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">reviewsNum:</td>
                                <td class="padding_10 vertical_align">Количество отзывов</td>
                            </tr>
                            <tr>
                                <td class="font_weight_700 padding_10 text_align_r vertical_align">reviews:</td>
                                <td class="padding_10 vertical_align">
                                    Последние 20 отзывов (text, good, date) и если есть, то ответы по ним answer (text, date)
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td class="font_weight_700 padding_10 text_align_r">Возможные ошибки:</td>
        <td class="padding_b_5 padding_l_10 padding_r_5 padding_t_5">

            <table class="table table-striped table_page table_page_b">
                <thead>
                <tr>
                    <td style="width: 100px;" class="font_weight_700 padding_10 text_align_c vertical_align">code</td>
                    <td class="padding_10 vertical_align">Описание</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">1</td>
                    <td class="padding_10 vertical_align">Отсутствует идентификатор продавца</td>
                </tr>
                <tr>
                    <td class="font_weight_700 padding_10 text_align_c vertical_align">2</td>
                    <td class="padding_10 vertical_align">Продавец не наден</td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    </tbody>
</table>
