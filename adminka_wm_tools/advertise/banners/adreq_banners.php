<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ����������� ��������</b></h3>';

$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[10] = "����. ����";

mysql_query("UPDATE `tb_ads_banner` SET `status`='3' WHERE `status`>'0' AND `date_end`<'".time()."'") or die(mysql_error());
mysql_query("DELETE FROM `tb_ads_banner` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

$type_banner_arr = array(
	"468x60" 	=> "(��� ��������, � ����� �����)", 
	"468x60_frm" 	=> "(�� ������ ��������� �������)", 
	"200x300" 	=> "(��� ��������)", 
	"100x100" 	=> "(��� ��������)",
	"728x90" 	=> "(������� ��������)"
);

if(isset($_GET["id"])) {

	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="add") {
		$sql = mysql_query("SELECT `id` FROM `tb_ads_banner` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("UPDATE `tb_ads_banner` SET `status`='1', `date`='".time()."', `date_end`=`plan`*'".(24*60*60)."'+'".time()."' WHERE `id`='$id'") or die(mysql_error());

			require_once($_SERVER["DOCUMENT_ROOT"]."/merchant/func_cache.php");
			cache_banners();

			echo '<span id="info-msg" class="msg-ok">������� ���������.</span>';
		}
	}

	if($option=="dell") {
		$sql = mysql_query("SELECT `id` FROM `tb_ads_banner` WHERE `id`='$id' AND `status`='0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_banner` WHERE `id`='$id'") or die(mysql_error());

			require_once($_SERVER["DOCUMENT_ROOT"]."/merchant/func_cache.php");
			cache_banners();

			echo '<span id="info-msg" class="msg-error">������� ������� �������!</span>';
		}

	}

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1000);
		HideMsg("info-msg", 1100);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th></th>';
echo '</tr></thead>';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `status`='0' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		$size_banner_arr = explode("_", $row["type"]);
		$size_banner = $size_banner_arr[0];

		$wh = explode("x", $size_banner);
		$w = $wh["0"];
		$h = $wh["1"];

		echo '<tr align="center">';
			echo '<td>'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
			echo '<td>'.($row["wmid"]!=false ? $row["wmid"]."<br>".$row["username"] : $row["username"]).'</td>';
			echo '<td>'.$system_pay[$row["method_pay"]].'</td>';
			echo '<td>';
				echo '<a href="'.$row["url"].'"><img src="//'.$_SERVER["HTTP_HOST"].''.$row["urlbanner_load"].'" width="'.($w/2).'" height="'.($h/2).'" border="0" alt="" title="" /></a>';
				echo '<br><br>������: <b>'.$size_banner.'</b> '.(array_key_exists($row["type"], $type_banner_arr) ? $type_banner_arr[$row["type"]] : false);
			echo '</td>';
			echo '<td align="left">';
				echo 'URL �����: <a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>40 ? "<b>".limitatexto($row["url"], 40)."</b>...." : "<b>".$row["url"]."</b>").'</a><br>';
				echo 'URL �������: <a href="'.$row["urlbanner"].'" target="_blank" title="'.$row["urlbanner"].'">'.(strlen($row["urlbanner"])>40 ? "<b>".limitatexto($row["urlbanner"], 40)."</b>...." : "<b>".$row["urlbanner"]."</b>").'</a><br>';
				echo '���� ������: <b>'.DATE("d.m.Y H:i", $row["date"]).'</b><br>';
				echo '���� ���������: <b>'.DATE("d.m.Y H:i", $row["date_end"]).'</b><br>';
				echo '��������, ����: <b>'.$row["plan"].'</b><br>';
				echo 'IP: <b>'.$row["ip"].'</b>';
			echo '</td>';
			echo '<td>'.number_format($row["money"], 2, ".", " ").' ���.</td>';

			echo '<td width="95">';
				echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
					echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="add">';
					echo '<input type="submit" value="��������" class="sub-green">';
				echo '</form>';
				echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
					echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="hidden" name="option" value="dell">';
					echo '<input type="submit" value="�������" class="sub-red">';
				echo '</form>';
			echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="10"><b>�� ���������� ������� ������� ���!</b></td>';
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>