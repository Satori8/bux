<?php
$pagetitle="Пользователи, автоматически заблокированные системой:";
include('header.php');
require("navigator/navigator.php");
require('config.php');

$sql = mysql_query("SELECT count(id) FROM `tb_black_users`");
$ban_users = mysql_result($sql,0,0);

echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="center"> ';
echo '<div align="center" style="font-size:14px; color:#ff0000;">';     
	echo 'Всего нарушителей: <b>'.number_format($ban_users,0,".","'").'</b> чел.<br>';
echo '<table class="tables"><tbody>';
echo '<tr><td align="center" class="tdserf" colspan="3"><h2 class="sp" style="margin:0; padding:0;"><strong>Пользователи которые заблокированы за использование автокликеров в последствии удаляются и заносятся в черный список проекта без предупреждения!</strong></h2></td></tr>';
echo '</tbody></table>';
echo '</div>';
echo '</div>';

$perpage = 30;
$count = $ban_users;
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");


//echo '<table width="100%" border="1" bordercolor="#42AAFF" cellspacing="0" cellpadding="2" class="tables">';
echo '<table class="tables">';
echo '<thead>';
echo '<tr align="center">';
echo '<th>№</th>';
echo '<th>Логин</th>';
echo '<th>Причина блокировки</th>';
echo '<th>Дата блокировки</th>';
echo '</tr>';
echo '</thead>';
//echo '<tr>';
//echo '<td width="10%"><center><strong>№</strong></center></td>';
//echo '<td width="10%"><center><strong>Логин</strong></center></td>';
//echo '<td width="50%"><center><strong>Причина</strong></center></td>';
//echo '<td width="15%"><center><strong>Дата</strong></center></td>';
//echo '</tr>';
$sql="SELECT * FROM tb_black_users ORDER BY ID DESC limit $start_pos,$perpage";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)){
echo'<tbody>';
echo '<tr align="center">';
echo '<td>'.$row["id"].'</td>';
echo '<td align="center" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><b>'.$row["name"].'</b></td>';
echo '<td align="center" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle">'.$row["why"].'</td>';
echo '<td>'.$row["date"].'</td>';
echo '</tr>';


}
echo'</tbody>';
echo'</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');?>