<?php
$pagetitle="Несуществующая страница";
include('header.php');

echo '<center><div align="center" class="msg-error" style="color:#fff;font-size:13px;font-family:Verdana, Geneva, sans-serif;text-align:center;font-weight:bold;font-style:italic;">Извините, запрошенная Вами страница не найдена.<br />Возможно Вы ошиблись в написании адреса, либо страница была переименована или удалена.</div></center>';
echo '<h2>Ошибка 404</h2><p><div align="center"><a href="/">Вернутся на главную страницу</a></div>';

include('footer.php');
?>