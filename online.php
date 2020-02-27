<?php
$pagetitle="Пользователи on-line";
include('header.php');
require_once('.zsecurity.php');
?>

<center><b>Пользователи</b></center>
<table class="tables">
<thead><tr><th class="top" width="33%">IP</th><th class="top" width="33%">Имя</th><th class="top"  width="33%">Страница</th></tr></thead>
    <?php
    require('config.php');
    $tabla = mysql_query("SELECT * FROM `tb_online` WHERE `username`!='' AND `username`!='Admin'");
    while ($row = mysql_fetch_assoc($tabla)) {
            $ip = $row["ip"];
            $user = $row["username"];
            $urlpage = $row["pagetitle"];

            echo'<tr><td align="center">'.$ip.'</td><td align="center">'.$user.'</td><td align="center">'.$urlpage.'</td></tr>';
        }
    ?>
</table>

<br><br>

<center><b>Гости</b></center>
<table class="tables">
<thead><tr><th class="top" width="33%">IP</th><th class="top" width="33%">Имя</th><th class="top"  width="33%">Страница</th></tr></thead>
    <?php
    $tabla = mysql_query("SELECT * FROM `tb_online` WHERE `username`=''");
    while ($row = mysql_fetch_assoc($tabla))
        {
            $ip = $row["ip"];
            $urlpage = $row["pagetitle"];


            echo'<tr><td align="center">'.$ip.'</td><td align="center">*Неизвестно*</td><td align="center">'.$urlpage.'</td></tr>';
        }
    ?>
</table>

<p>&nbsp;</p>

<?php include('footer.php');?>
