<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;">Биржа рефералов [модерация]</h3>';

require("navigator/navigator.php");
$perpage = 30;
$sql_p = mysql_query("SELECT `id` FROM `tb_refbirj`");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if(isset($_GET["option"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="dell") {
		mysql_query("DELETE FROM `tb_refbirj` WHERE `id`='$id'") or die(mysql_error());

		echo '<span class="msg-error">Заявка на продажу успешно снята.</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}

echo '<table class="tables" style="margin:2px auto;">';
echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>Дата / Время</th>';
	echo '<th>Реферал</th>';
	echo '<th>Продавец</th>';
	echo '<th>Стоимость</th>';
	echo '<th></th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_refbirj` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr align="center">';
			echo '<td>'.$row["id"].'</td>';
			echo '<td>'.$row["date"].'</td>';
			echo '<td>'.$row["ref"].'</td>';
			echo '<td>'.$row["name"].'</td>';
			echo '<td>'.number_format($row["cena"], 2, ".", " ").'&nbsp;руб.</td>';

			echo '<td width="100">';
				echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("Подтвердите удаление")) return false;\'>';
					echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
					echo '<input type="hidden" name="page" value="'.$page.'">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="dell">';
					echo '<input type="submit" value="Снаять" class="sub-red">';
				echo '</form>';
			echo '</td>';
		echo '</tr>';
	}
}
echo '</table>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}
?>