<a name="head_panel.user.partner.find.list.get"></a>
<div class="panel panel-headline">
    <div class="panel-heading">
        <h3 class="panel-title"><a href="/panel/cabinet/">ЛИЧНЫЙ КАБИНЕТ</a> » Пополнить счет</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table_page">
            <tbody>
            <tr>
                <td colspan="2" class="text_align_c padding_10">
                    <div class="info_red_form">
                        <span class="span_info_red"><i>!</i></span>
                        <span class="span_text_b"></span>
                        <span class="span_text">Для пополнения вашего личного счета введите требуемую сумму и нажмите кнопку "Пополнить".<br>После чего выберите способ оплаты и подтвердите платеж.<br>Средства будут зачислены на Ваш личный счет сразу после подтверждения оплаты.</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text_align_c padding_10 vertical_align">
                    <form method="POST" onsubmit="panel.user.addmoney.send(this);return false;" action= "https://primepayer.com/pay">
                        <input type="text" name="amount" value="100" maxlength="10" style="text-align: center;width: 90px;">
                        <input type="hidden" name="shop" value="{primePayerShopId}">
                        <input type="hidden" name="description" value="Пополнение">
                        <input type="hidden" name="currency" value="3">
                        <input type="hidden" name="success" value="{primePayerSuccess}">
                        <input type="hidden" name="payment">
                        <input type="hidden" name="sign">
                        <button name="button" class="btn btn-success btn-sm">Пополнить</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

