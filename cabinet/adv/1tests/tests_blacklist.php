<?php
if(!DEFINED("TESTS_BL")) {die ("Hacking attempt!");}
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:left;">Черный список исполнителей тестов.</h5>';
?>

<script type="text/javascript" language="JavaScript">
function BanIdGet(id){
	$("#iduser").val(id);
}

function BanTestUser(){
	var iduser = $.trim($("#iduser").val());

	if (iduser == "") {
		alert("Укажите ID пользователя!");
	} else if (confirm("Вы уверены что хотите заблокировать пользователя c ID:" + iduser + " ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
			data: {'op':'BanUser', 'iduser':iduser, 'type':'tests'}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				alert("Ошибка обработки данных! Если ошибка повторяется, сообщите Администрации сайта.");
				return false;
			}, 
			success: function(data) {
				$("#loading").slideToggle();

				if (data.result == "OK") {
					if(data.message) {
						BanIdGet("");
						alert(data.message);
						//location.replace("");
						return false;
					} else {
						alert("Ошибка обработки данных!");
						return false;
					}
				} else {
					if(data.message) {
						alert(data.message);
						return false;
					} else {
						alert("Ошибка обработки данных!");
						return false;
					}
				}
			}
		});

	}
}

function UnBanTestUser(id, trid){
	if (confirm("Вы уверены что хотите разблокировать пользователя c ID:" + id + " ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
			data: {'op':'UnBanUser', 'iduser':id, 'type':'tests'}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				alert("Ошибка обработки данных! Если ошибка повторяется, сообщите Администрации сайта.");
				return false;
			}, 
			success: function(data) {
				$("#loading").slideToggle();

				if (data.result == "OK") {
					$("#trid1"+trid).remove();
					$("#trid2"+trid).remove();
					$("#count_bl").html(data.message);
				} else {
					if(data.message) {
						alert(data.message);
						return false;
					} else {
						alert("Ошибка обработки данных!");
						return false;
					}
				}
			}
		});

	}
}

</script>

<?php

echo 'Здесь вы можете увидеть свой черный список исполнителей, а так же внести в черный список исполнителей.<br><br>';

echo '<div id="newform"><table class="tables">';
echo '<thead><tr align="center"><th colspan="3">Заблокировать исполнителя</th></tr></thead>';
echo '<tr>';
	echo '<td width="140" align="center" style="border-left:none; border-right:none;"><b>ID исполнителя:</b></td>';
	echo '<td width="130" style="border-left:none; border-right:none;"><input type="text" id="iduser" maxlength="30" value="" class="ok12" style="text-align:center;" /></td>';
	echo '<td align="left" style="border-left:none; border-right:none;"><span onClick="BanTestUser();" class="sub-black" style="float:none;">Заблокировать</span></td>';
echo '</tr>';
echo '</table></div><br><br>';

require(DOC_ROOT."/navigator/navigator.php");
$perpage = 50;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_tests_bl` WHERE `rek_name`='$username'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>0) echo '<div style="padding-bottom:3px;">Всего в черном списке: <span id="count_bl">'.$count.'</span></div>';

if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=".$op, $perpage, 10, '&page=', "");
echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th width="70">ID</th>';
	echo '<th width="150">Логин</th>';
	echo '<th>Дата блокировки</th>';
	echo '<th width="30"></th>';
echo '</tr></thead>';

$sql_bl = mysql_query("SELECT * FROM `tb_ads_tests_bl` WHERE `rek_name`='$username' ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql_bl)>0) {
	while ($row_bl = mysql_fetch_array($sql_bl)) {
		$token_ajax = md5($username.$row_bl["user_name"].$row_bl["user_id"]."token2205");

		echo '<tr align="center" id="trid1'.$row_bl["id"].'">';
			echo '<td align="center">'.$row_bl["user_id"].'</td>';
			echo '<td align="center"><span style="font-weight: bold; color: #1874CD; cursor: pointer;" onclick="ShowHideStats(\''.$row_bl["user_id"].'\', \''.$token_ajax.'\')" title="Посмотреть расширенную статистику пользователя '.$row_bl["user_name"].'">'.$row_bl["user_name"].'</span></td>';
			echo '<td align="center">'.DATE("d.m.Yг. в H:i", strtotime($row_bl["date"])).'</td>';
			echo '<td align="center">';
				echo '<a class="workcomp" onClick="UnBanTestUser(\''.$row_bl["user_id"].'\', \''.$row_bl["id"].'\');" title="Удалить из черного списка" style="float:none;"></a>';
			echo '</td>';
		echo '</tr>';
		echo '<tr align="center" id="trid2'.$row_bl["id"].'"><td colspan="4" id="usersstat'.$row_bl["user_id"].'" style="display: none; padding:0; margin:0;"></td></tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="4">В черном списке нет пользователей!</td>';
	echo '</tr>';
}
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=statistics&id=".$id, $perpage, 10, '&page=', "");

?>

<script language="JavaScript">ScrollTo();</script>