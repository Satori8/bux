<?php
require_once('.zsecurity.php');
$pagetitle = "Список рефералов 3ур.";
include('header.php');
require("navigator.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	function ref_status($my_reiting){
		$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_array($sql_rang);
		}else{
			$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
			$row_rang = mysql_fetch_array($sql_rang);
		}
		return '<span style="cursor:pointer; color: #006699;" title="Статус"><b>'.$row_rang["rang"].'</b></span>';
	}

	$attestat[0]   = '';
	$attestat[-1]   = '';
	$attestat[1]   = '<img src="/img/att/att_1.ico"    alt="" title="Аттестат Расчетного автомата" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[100] = '<img src="/img/att/att_100.ico"  alt="" title="Аттестат Псевдонима" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[110] = '<img src="/img/att/att_110.ico"  alt="" title="Формальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[120] = '<img src="/img/att/att_120.ico"  alt="" title="Начальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[130] = '<img src="/img/att/att_130.ico"  alt="" title="Персональный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[135] = '<img src="/img/att/att_135.ico"  alt="" title="Аттестат Продавца" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[136] = '<img src="/img/att/att_136.ico"  alt="" title="Аттестат Capitaller" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[140] = '<img src="/img/att/att_140.ico"  alt="" title="Аттестат Разработчика" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[150] = '<img src="/img/att/att_150.ico"  alt="" title="Аттестат Регистратора" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[170] = '<img src="/img/att/att_170.ico"  alt="" title="Аттестат Сервиса WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[190] = '<img src="/img/att/att_190.ico"  alt="" title="Аттестат Гаранта WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[300] = '<img src="/img/att/att_300.ico"  alt="" title="Аттестат Оператора WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';

	$username = uc($_SESSION["userLog"]);

	require('config.php');

	$metode = false;
	$search = false;
	if(isset($_POST["search"]) && isset($_POST["metode"])) {
		$metode = isset($_POST["metode"]) ? limpiar(trim($_POST["metode"])) : false;
		$search = isset($_POST["search"]) ? limpiar(trim($_POST["search"])) : false;;

		if($search!="" && $metode!="") {
			$WHERE = "`$metode` LIKE '%$search%' AND ";
			$WHERE_to_get = "&metode=$metode&search=$search";
		}else{
			$WHERE = false;
			$WHERE_to_get = false;
		}
	}else{
		$WHERE = false;
		$WHERE_to_get = false;
	}
	if(isset($_GET["search"]) && isset($_GET["metode"])) {
		$metode = isset($_GET["metode"]) ? limpiar(trim($_GET["metode"])) : false;
		$search = isset($_GET["search"]) ? limpiar(trim($_GET["search"])) : false;;

		if($search!="" && $metode!="") {
			$WHERE = "`$metode` LIKE '%$search%' AND ";
			$WHERE_to_get = "&metode=$metode&search=$search";
		}else{
			$WHERE = false;
			$WHERE_to_get = false;
		}
	}
	
	?>
	<script type="text/javascript" src="/js/highcharts.js"></script>
	<script type="text/javascript">
	var LoadBlock = false;
	
	
	function FuncStatRef(op, token, title_win) {
	//alert('fff');
		if(!LoadBlock){$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_stat_ref.php", dataType: 'json', data: { 'op':op, 'token':token }, 
			error: function(request, status, errortext) { LoadBlock = false; $("#loading").slideToggle(); alert("Ошибка Ajax! \n"+status+"\n"+errortext); console.log(status, errortext, request); },
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false; $("#loading").slideToggle();
				var result = data.result!=undefined ? data.result : data;
				var message = data.message!=undefined ? data.message : data;
				title_win = (!title_win | result!="OK") ? "Ошибка" : title_win;

				if (result == "OK") {
					ModalStart(title_win, (title_win=="Ошибка" ? StatusMsg('ERROR', message) : message), 700, true, true, false);
				} else {
					ModalStart(title_win, StatusMsg(result, message), 500, true, true, false);
				}
			}
		});}
		return false;
	}
	</script>
	

	<?php

	$s = (isset($_GET["s"]) && preg_match("|^[\d]{1,2}$|", trim($_GET["s"])) && intval(limpiar(trim($_GET["s"])))>=0 && intval(limpiar(trim($_GET["s"])))<=20) ? abs(intval(limpiar(trim($_GET["s"])))) : "0";
	$u = (isset($_GET["u"]) && preg_match("|^[\d]{1}$|", trim($_GET["u"])) && intval(limpiar(trim($_GET["u"])))>=0 && intval(limpiar(trim($_GET["u"])))<=1) ? abs(intval(limpiar(trim($_GET["u"])))) : "1";
	$s_arr = array('id','username','reiting','wall_com_p','wall_com_o','statusref','visits','visits_a','visits_m','visits_t','autoref','money_rek','joindate2','lastlogdate2','referals','referals2','referals3','referals4','referals5','ref_back','ref_bonus_add');
	$u_arr = array('ASC','DESC');
	$s_tab = $s_arr[$s];
	$u_tab = $u_arr[$u];

	$perpage = 25;
	$count1 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username'"));
	$count2 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$username'"));
	$count3 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'"));
	//$count4 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer4`='$username'"));
	//$count5 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer5`='$username'"));

	$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE $WHERE `referer3`='$username'"));
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	$security_key = "jbmiym,oi5//bmiyt";
		$token_stat_ref1 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL1".$security_key));
		$token_stat_ref2 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL2".$security_key));
		$token_stat_ref3 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL3".$security_key));
		//$token_stat_ref4 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL4".$security_key));
		//$token_stat_ref5 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL5".$security_key));
	echo '<table class="tables">';
	echo '<tr align="center">';
		echo '<td colspan="5" valign="top" class="adv">Навигация по уровням рефералов</td>';
	echo '</tr>';
	echo '<tr align="center">';
		echo '<td class="adv"><a href="/referals1.php" class="menuheader">1-го ур. ['.$count1.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами I уровня" onClick="FuncStatRef(\'StatRefCashL1\', \''.$token_stat_ref1.'\', \'Статистика заработка рефералами I уровня\')"></span></td>';
		echo '<td class="adv"><a href="/referals2.php" class="menuheader">2-го ур. ['.$count2.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами II уровня" onClick="FuncStatRef(\'StatRefCashL2\', \''.$token_stat_ref2.'\', \'Статистика заработка рефералами II уровня\')"></span></td>';
		echo '<td class="adv"><a href="/referals3.php" class="menuheader">3-го ур. ['.$count3.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами III уровня" onClick="FuncStatRef(\'StatRefCashL3\', \''.$token_stat_ref3.'\', \'Статистика заработка рефералами III уровня\')"></span></td>';
		//echo '<td class="adv"><a href="/referals4.php" class="menuheader">4-го ур. ['.$count4.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами IV уровня" onClick="FuncStatRef(\'StatRefCashL4\', \''.$token_stat_ref4.'\', \'Статистика заработка рефералами IV уровня\')"></span></td>';
		//echo '<td class="adv"><a href="/referals5.php" class="menuheader">5-го ур. ['.$count5.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами V уровня" onClick="FuncStatRef(\'StatRefCashL5\', \''.$token_stat_ref5.'\', \'Статистика заработка рефералами V уровня\')"></span></td>';
	echo '</tr>';
	echo '</table><br>';

	echo '<form method="GET" action="" id="newform">';
	echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap" width="100">Сортировать по:</td>';
		echo '<td align="center">';
			echo '<select name="s" class="ok">';
				echo '<option value="0" '.("0" == "$s" ? 'selected="selected"' : false).'>ID</option>';
				echo '<option value="1" '.("1" == "$s" ? 'selected="selected"' : false).'>Логину</option>';
				echo '<option value="2" '.("2" == "$s" ? 'selected="selected"' : false).'>Статусу</option>';
				echo '<option value="3" '.("3" == "$s" ? 'selected="selected"' : false).'>Кол-ву положительных отзывов</option>';
				echo '<option value="4" '.("4" == "$s" ? 'selected="selected"' : false).'>Кол-ву отрицательных отзывов</option>';
				echo '<option value="12" '.("12" == "$s" ? 'selected="selected"' : false).'>Дате регистрации</option>';
				echo '<option value="13" '.("13" == "$s" ? 'selected="selected"' : false).'>Дате активности</option>';
				echo '<option value="14" '.("14" == "$s" ? 'selected="selected"' : false).'>Рефералам 1 уровня</option>';
				echo '<option value="15" '.("15" == "$s" ? 'selected="selected"' : false).'>Рефералам 2 уровня</option>';
				echo '<option value="16" '.("16" == "$s" ? 'selected="selected"' : false).'>Рефералам 3 уровня</option>';
				//echo '<option value="17" '.("17" == "$s" ? 'selected="selected"' : false).'>Рефералам 4 уровня</option>';
				//echo '<option value="18" '.("18" == "$s" ? 'selected="selected"' : false).'>Рефералам 5 уровня</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="150">';
			echo '<select name="u" class="ok">';
				echo '<option value="0" '.("0" == "$u" ? 'selected="selected"' : false).'>По возрастанию</option>';
				echo '<option value="1" '.("1" == "$u" ? 'selected="selected"' : false).'>По убыванию</option>';
			echo '</select>';
			echo '<input type="hidden" name="page" value="'.$page.'">';
		echo '</td>';
		echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" class="proc-btn" value="Сортировать"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';

	echo '<form method="post" action="" id="newform">';
	echo '<table class="tables">';
	echo '<tr>';
	echo '<td align="left" nowrap="nowrap" width="100">Поиск по:</th>';
	echo '<td nowrap="nowrap"><select name="metode" class="ok">';
		echo '<option value="id" '.("id" == $metode ? 'selected="selected"' : false).'>ID</option>';
		echo '<option value="username" '.("username" == $metode ? 'selected="selected"' : false).'>Логину</option>';
		echo '<option value="joindate" '.("joindate" == $metode ? 'selected="selected"' : false).'>Дате регистрации</option>';
		echo '<option value="lastlogdate" '.("lastlogdate" == $metode ? 'selected="selected"' : false).'>Дате активности</option>';
	echo '</select></td>';
	echo '<td align="center" width="150"><input type="text" class="ok" name="search" value="'.$search.'" style="width:96%;"></td>';
	echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" value="Поиск" class="proc-btn"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form><br>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?s=$s&u=$u");

	echo '<table class="tables">';
		echo '<thead><tr align="center">';
		echo '<th colspan="2">ID, Логин</th>';
		echo '<th>Рег-ция&nbsp;|&nbsp;Активность</th>';
		echo '<th>Рефералы</th>';
	echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM `tb_users` WHERE $WHERE `referer3`='$username' ORDER BY $s_tab $u_tab LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {
			echo '<tr align="center">';

				$wall_com = $row["wall_com_p"] - $row["wall_com_o"];

				$info_user = '<a href="/wall?uid='.$row["id"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row["username"].'" width="16" border="0" style="margin:0; padding:0;" align="absmiddle" />';

				if($wall_com>0) {
					$info_user.= '&nbsp;<b style="color:#008000;">+'.$wall_com.'</b></a>';
				}elseif($wall_com<0) {
					$info_user.= '&nbsp;<b style="color:#FF0000;">-'.abs($wall_com).'</b></a>';
				}else{
					$info_user.= '&nbsp;<b style="color:#000000;">0</b></a>';
				}

				echo '<td width="60px" style="border-right:none;">';
					echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" border="0" alt="avatar" title="avatar" />';
				echo '</td>';

				echo '<td align="left" style="border-left:none;">';
					echo '<b style="cursor:pointer;" title="Логин">'.$row["username"].'</b> - <span style="cursor:pointer;" title="ID">'.$row["id"].'</span><br>';
					echo ref_status($row["reiting"])."<br>";

					if($row["http_ref"]!="") {echo 'Пришел с '.$row["http_ref"].'<br>';}

					echo $attestat[$row["attestat"]]."&nbsp;";
					if($row["country_cod"]!="") {
						echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($row["country_cod"]).'.gif" alt="" title="'.get_country($row["country_cod"]).'" width="16" height="12" style="margin:0; padding:0;" align="absmiddle" />&nbsp;';
					}
					echo $info_user;
				echo '</td>';

				echo '<td>';
					echo DATE("d.m.Yг. в H:i",$row["joindate2"])."<br>";

					if($row["lastlogdate2"] == 0) {
						echo '<span style="color:#FF0000;">нет</span>';
					}elseif(DATE("d.m.Y", $row["lastlogdate2"]) == DATE("d.m.Y")) {
						echo '<span style="color:green;">Сегодня, '.DATE("в H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] < (time()-7*24*60*60)) {
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] > (time()-7*24*60*60)) {
						echo '<span style="color:green;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}else{
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}

					if($row["ban_date"]>0) echo '<br><b style="color:#FF0000;">Штрафник</b>';
				echo '</td>';

				echo '<td>'.$row["referals"].'-'.$row["referals2"].'-'.$row["referals3"].'</td>';

			echo '</tr>';
		}
	}else{
		if($WHERE==false) {
			echo '<tr align="center"><td colspan="4"><b>У Вас нет рефералов!</b></td></tr>';
		}else{
			echo '<tr align="center"><td colspan="4"><b>Ошибка! У Вас нет такого реферала!</b></tb></tr>';
		}
	}
	echo '</table>';


	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?s=$s&u=$u");
}

include('footer.php');?>