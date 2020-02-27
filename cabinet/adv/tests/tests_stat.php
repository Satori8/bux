<?php
if(!DEFINED("TESTS_STAT")) {die ("Hacking attempt!");}
echo '<div id="GoTop"></div>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:left;">Список исполнителей теста № '.$id.'</h5>';
?>

<script type="text/javascript" language="JavaScript">
function ScrollTo(){
	$("html, body").animate({scrollTop: $("#GoTop").offset().top-345}, 700);
}

function BanIdGet(id){
	$("#iduser").val(id);
}

function BanTestUser(){
	var iduser = $.trim($("#iduser").val());

	if (iduser == "") {
		alert("Укажите ID пользователя!");
	} else if (confirm("Вы уверены что хотите заблокировать\n пользователя c ID:" + iduser + " ?")) {
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
						alert(data.message);
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
</script>

<?php

echo 'Здесь вы можете увидеть список исполнителей ваших тестов, а так же внести в ЧС исполнителей, после чего им будет запрещен доступ в ваши тестовые задания.<br><br>';

echo '<div id="newform"><table class="tables">';
echo '<thead><tr align="center"><th colspan="3">Заблокировать исполнителя</th></tr></thead>';
echo '<tr>';
	echo '<td width="140" align="center" style="border-left:none; border-right:none;"><b>ID исполнителя:</b></td>';
	echo '<td width="130" style="border-left:none; border-right:none;"><input type="text" id="iduser" maxlength="30" value="" class="ok12" style="text-align:center;" /></td>';
	echo '<td align="left" style="border-left:none; border-right:none;"><span onClick="BanTestUser();" class="sub-black" style="float:none;">Заблокировать</span></td>';
echo '</tr>';
echo '</table></div><br><br>';

$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	$status = $row["status"];

	include(DOC_ROOT."/geoip/geoipcity.inc");
	include(DOC_ROOT."/geoip/geoipregionvars.php");
	$gi = geoip_open(DOC_ROOT."/geoip/GeoLiteCity.dat", GEOIP_STANDARD);

	require(DOC_ROOT."/navigator/navigator.php");
	$perpage = 30;
	$sql_p = mysql_query("SELECT `id` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `money`>'0'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=statistics&id=".$id, $perpage, 10, '&page=', "");
	echo '<table class="tables">';
	echo '<thead><tr align="center">';
		echo '<th>Логин</th>';
		echo '<th>Дата</th>';
		echo '<th>Выполнялся</th>';
		echo '<th>IP</th>';
		echo '<th width="70">Статус</th>';
	echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM `tb_ads_tests_visits` WHERE `ident`='$id' ORDER BY `id` DESC LIMIT $start_pos, $perpage");
	if(mysql_num_rows($sql)>0) {
		while ($row_v = mysql_fetch_array($sql)) {
			echo '<tr align="center">';
				echo '<td align="left" style="border-left:none;" nowrap="nowrap"><span style="color: #1874CD; cursor: pointer;" onClick="BanIdGet('.$row_v["user_id"].');"><b>'.$row_v["user_name"].'</b> (ID: '.$row_v["user_id"].')</span></td>';

				echo '<td nowrap="nowrap" align="center">';
					if( DATE("d.m.Y", $row_v["time_end"]) == DATE("d.m.Y", time()) ) {
						echo '<span style="color:#006400;">Сегодня</span>, '.DATE("в H:i", $row_v["time_end"]);
					}elseif( DATE("d.m.Y", $row_v["time_end"]) == DATE("d.m.Y", (time()-24*60*60)) ) {
						echo '<span style="color:#000080;">Вчера</span>, '.DATE("в H:i", $row_v["time_end"]);
					}else{
						echo '<span style="color:#363636;">'.DATE("d.m.Y", $row_v["time_end"]).'</span> '.DATE("H:i", $row_v["time_end"]);;
					}
				echo '</td>';

				echo '<td nowrap="nowrap" align="center">';
					echo date_ost(($row_v["time_end"]-$row_v["time_start"]), 1);
				echo '</td>';

				$record = geoip_record_by_addr($gi, $row_v["ip"]);

				if($record==false) {
					$country_code="";
				}else{
					$country_code = @$record->country_code;
				}

				echo '<td align="left" style="padding-left:25px;"><img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($country_code).'.gif" alt="'.get_country($country_code).'" title="'.get_country($country_code).'" align="absmiddle" width="16" height="11" style="margin:0; padding:0; border:none;" />&nbsp;'.$row_v["ip"].'</td>';

				echo '<td align="center">'.($row_v["status"]==-1 ? '<span style="color: #E32636;">Провалил</span>' : '<span style="color: #03C03C;">Прошёл</span>').'</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr align="center">';
			echo '<td colspan="5">Нет данных</td>';
		echo '</tr>';
	}
	echo '</table>';
	if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=statistics&id=".$id, $perpage, 10, '&page=', "");


	?><script language="JavaScript">ScrollTo();</script><?php
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>