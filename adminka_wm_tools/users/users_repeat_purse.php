<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
require("navigator/navigator.php");
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������� ��������� ����������</b></h3>';

if ($_POST[show_check])
{
    if (is_array($_POST[sel]))
    {
		foreach ($_POST[sel] as $v)
        {
			mysql_query("UPDATE purselog SET status='1' WHERE id='".$v."'");
        }
		echo '<center><b style=color:blue;>��������� ���� �������� ��� �������������.</b></center>';
    }
    else
    {
        echo '<center><b style=color:red;>�� ������ �� �������.</b></center>';
    }
}

$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `purselog` WHERE `status`=0");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sql = mysql_query("SELECT * FROM `purselog` WHERE `status`=0 ORDER BY `create_date` DESC LIMIT $start_pos,$perpage");
$all_users = mysql_num_rows($sql);


echo '<table style="margin:0; padding:0; margin-bottom:20px;"><tr>';
echo '<td valign="top">';
    echo '�����: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_users.'</b> �� <b>'.$count.'</b>';
echo '</td>';
echo '</tr>';
echo '</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");

echo '<table class="tables" style="margin:1px auto;"><form name="FormName" action="" method="post">';
echo '<thead><tr align="center">';
	echo '<th style="text-align:center;">#</th>';
	echo '<th style="text-align:center;">����</th>';
	echo '<th style="text-align:center;">����� [ ID ]</th>';
	echo '<th style="text-align:center;">���������</th>';
	echo '<th style="text-align:center;">����������</th>';
	echo '<th style="text-align:center;">���</th>';
	echo '<th style="text-align:center;"><input OnClick="check(chall)" type="checkbox" value=""></th>';
echo '</tr>';

if($all_users>0) {
	while ($row = mysql_fetch_array($sql)) {
	    if ($row[wm_purse]=="") { $wm_purse="-"; } else { $wm_purse=$row[email]; }
		if ($row[wmid]=="") { $wmid="-"; } else { $wmid=$row[wmid]; }
		if ($row[ym_purse]=="") { $ym_purse="-"; } else { $ym_purse=$row[ym_purse]; }
		if ($row[py_purse]=="") { $py_purse="-"; } else { $py_purse=$row[py_purse]; }
		if ($row[qw_purse]=="") { $qw_purse="-"; } else { $qw_purse=$row[qw_purse]; }
		if ($row[pm_purse]=="") { $pm_purse="-"; } else { $pm_purse=$row[pm_purse]; }
		if ($row[mb_purse]=="") { $mb_purse="-"; } else { $mb_purse=$row[mb_purse]; }
		if ($row[ac_purse]=="") { $ac_purse="-"; } else { $ac_purse=$row[ac_purse]; }
		$rekvizit="WMR �������: {$wm_purse}<br>WMID: {$wmid}<br>������ �������: {$ym_purse}<br>Payeer �������: {$py_purse}<br>Qiwi �������: {$qw_purse}<br />PerfectMoney �������: {$pm_purse}<br/>�������: {$mb_purse}<br>AdvCash: {$ac_purse}";
		
		echo '<tr align="center">';
			echo '<td>'.$row["id"].'</td>';
			echo '<td><b>'.DATE("d.m.Y H:i", $row["create_date"]).'</b></td>';
			echo '<td><b>'.get_username($row["uid"]).' [ '.$row["uid"].' ]</b></td>';
			echo '<td><b>'.$rekvizit.'</b></td>';
			echo '<td><b>'.$row["numblocked"].'</b></td>';
			echo '<td><textarea cols=70 rows=7>'.$row["blockedstr"].'</textarea></td>';
            echo '<td><input name="sel[]" id=chall type="checkbox" value="'.$row["id"].'"></td>';
		echo '</tr>';
	}
	echo '<tr align="center"><td colspan="7" style="background:none;"><input type="submit" name=show_check value="�������� ��������" class="sub-blue160"></td></tr>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");
}else{
	echo '<tr>';
		echo '<td colspan="7" align="center" style="padding:0;"><b>��������������� ������� ���</b></td>';
	echo '</tr>';
}
echo '</form></table>';

?>