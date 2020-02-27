<?php
$pagetitle="Ошибка 500!";
include('header.php');

echo '<span class="msg-error">Внутренняя ошибка сервера!</span>';
echo '<a href="/">Вернуться на главную страницу</a>';

include('footer.php');
?>