<?php
$pagetitle="������������ on-line";
include('header.php');
require_once('.zsecurity.php');
?>

<center><b>������������</b></center>
<table class="tables">
<thead><tr><th class="top" width="33%">IP</th><th class="top" width="33%">���</th><th class="top"  width="33%">��������</th></tr></thead>
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

<center><b>�����</b></center>
<table class="tables">
<thead><tr><th class="top" width="33%">IP</th><th class="top" width="33%">���</th><th class="top"  width="33%">��������</th></tr></thead>
    <?php
    $tabla = mysql_query("SELECT * FROM `tb_online` WHERE `username`=''");
    while ($row = mysql_fetch_assoc($tabla))
        {
            $ip = $row["ip"];
            $urlpage = $row["pagetitle"];


            echo'<tr><td align="center">'.$ip.'</td><td align="center">*����������*</td><td align="center">'.$urlpage.'</td></tr>';
        }
    ?>
</table>

<p>&nbsp;</p>

<?php include('footer.php');?>
