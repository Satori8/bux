<?php
$pagetitle="Копилка";
include('header.php');
require("navigator/navigator.php");
require('config.php');

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Zа-яА-Я0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql_u = mysql_query("SELECT `id`,`user_status`,`forum_status` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_u)>0) {
		$row_u = mysql_fetch_array($sql_u);
		$id_tab = $row_u["id"];
		$users_status = $row_u["user_status"];
		$forum_status = $row_u["forum_status"];

		if($users_status==1 | $forum_status==1 | $forum_status==2) {
			?>	
			<script type="text/javascript">
				function kop_dll_coment(id) {
					$.ajax({
						type: "POST", url: "/kopilka/ajax_kopilka.php", data: { 'op':'kop_dll_coment', 'id':id }, 
						beforeSend: function() { $('#loading').show(); }, 
						success: function(data) { 
							$('#loading').hide();
							if(data=="OK") {
								$('#coment'+id).html("В копилку на развитие"); 
							}else{
								alert(data);
							}
						}
					});
				}
			</script>
			<?php
		}else{
			$users_status = false;
			$forum_status = false;
		}
	}else{
		$users_status = false;
		$forum_status = false;
	}
}else{
	$users_status = false;
	$forum_status = false;
}

$sql = mysql_query("SELECT `id` FROM `tb_kopilka_in`");
$all_kop = mysql_num_rows($sql);

$perpage = 50;
$count = $all_kop;
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

echo '<table class="tables">';
echo '<thead>';
	echo '<tr align="center">';
		echo '<th class="top" width="20">#</th>';
		echo '<th class="top" width="100">Пользователь</th>';
		echo '<th class="top">Комментарий</th>';
		echo '<th class="top" width="100">Дата</th>';
		echo '<th class="top" width="75">Сумма</th>';
	echo '</tr>';
echo '</thead>';

$sql = mysql_query("SELECT * FROM `tb_kopilka_in` ORDER BY `id` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr>';
			echo '<td align="right">'.$row["id"].'</td>';
			echo '<td align="left">'.$row["username"].'</td>';

			if(($users_status==1 | $forum_status==1 | $forum_status==2) && ($row["comment"]!=false && $row["comment"]!="В копилку на развитие")) {
				echo '<td align="left">';
					echo '<div id="coment'.$row["id"].'">'.$row["comment"].'<a onClick="kop_dll_coment('.$row["id"].')" class="workcomp" title="Удалить комментарий"></a></div>';
				echo '</td>';
			}else{
				if($row["comment"]==false) $row["comment"]="В копилку на развитие";
				echo '<td align="left">'.$row["comment"].'</td>';
			}

			echo '<td nowrap="nowrap" align="right">';
				if(DATE("d.m.Y",$row["time"])==DATE("d.m.Y",time())) {
					echo '<span style="color:#006400;">Сегодня</span>, '.DATE("в H:i",$row["time"]);
				}elseif(DATE("d.m.Y",$row["time"])==DATE("d.m.Y",(time()-24*60*60))) {
					echo '<span style="color:#000080;">Вчера</span>, '.DATE("в H:i",$row["time"]);
				}else{
					echo '<span style="color:#363636;">'.DATE("d.m.Y", $row["time"]).'</span>, '.DATE("в H:i",$row["time"]);
				}
			echo '</td>';
			echo '<td align="center">'.number_format($row["money"],2,".","`").'</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center"><td colspan="5">Пополнений копилки еще не было!</td></tr>';
}
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');?>