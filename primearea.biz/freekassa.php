<?php
echo '<html><head><title>Оплата</title></head><body>';
if ($_GET['action'] === 'success') {
    echo 'Успешная оплата. Вернитесь на предыдущую вкладку для продолжения';
} else {
    echo 'Ошибка оплаты. Вернитесь на предыдущую вкладку для продолжения';
}
echo '</body></html>';
