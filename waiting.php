<?php
$pagetitle = "Выплаты в ожидании";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}else{
	$perpage = 50;
	$sql = mysql_query("SELECT `id` FROM `tb_history` WHERE `status_pay`='0' AND `method` IN ('WebMoney','YandexMoney','PerfectMoney','Payeer','Qiwi','Mobile','SberBank','PayPal','AdvCash') AND `status`='' AND `tipo`='0'");
	$count = mysql_num_rows($sql);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");
	echo '<table class="tables_inv">';
	echo '<thead>';
	echo '<tr align="center">';
		echo '<th>Пользователь</th>';
		echo '<th>Дата и время</th>';
		echo '<th>Сумма, руб</th>';
		echo '<th>Платежная система</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$sql = mysql_query("SELECT `user`,`user_id`,`amount`,`time`,`method` FROM `tb_history` WHERE `status_pay`='0' AND `method` IN ('WebMoney','YandexMoney','PerfectMoney','Payeer','Qiwi','Mobile','SberBank','PayPal','AdvCash') AND `status`='' AND `tipo`='0' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_assoc($sql)) {
			echo '<tr>';
				echo '<td align="left" style="padding:3px 3px 4px 5px;"><img src="/img/hello.gif" style="margin:0px; padding:2px 5px;" width="16" align="absmiddle" /><a href="/wall?uid='.$row["user_id"].'">'.ucfirst($row["user"]).'</a></td>';
				echo '<td align="center" style="padding:3px 3px">';
					if(DATE("d.m.Y",$row["time"])==DATE("d.m.Y",time())) {
						echo DATE("Сегодня, H:i",$row["time"]);
					}else{
						echo DATE("d.m.Y H:i",$row["time"]);
					}
				echo '</td>';
				echo '<td align="left" style="padding:3px 3px 4px 25px;"><img src="/img/wmr16x16.png" style="margin:0px; padding:2px 5px;" align="absmiddle" />'.number_format($row["amount"],2,".","'").'</td>';
				echo '<td align="left" style="padding:3px 3px 4px 25px;">';
					if($row["method"]=="WebMoney") {
						echo '<img src="/img/wm16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#0084D1">WebMoney</span>';
					}elseif($row["method"]=="Qiwi") {
						echo '<img src="/img/qiwi16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#ff9933"><span style="color:#FC8000;">QIWI кошелек</span></span>';
					}elseif($row["method"]=="YandexMoney") {
						echo '<img src="/img/yandexmoney16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#006699;"><span style="color:#DE1200">Я</span>ндекс.Деньги</span>';
					}elseif($row["method"]=="Payeer") {
						echo '<img src="/img/payeer16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span>';
					}elseif($row["method"]=="PerfectMoney") {
						echo '<img src="/img/perfectmoney16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#DE1200;">PerfectMoney</span>';
					}elseif($row["method"]=="Mobile") {
						echo '<img src="/img/mobile16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#DE1200">Mobile Phone</span>';
					}elseif($row["method"]=="SberBank") {
						echo '<img src="/img/sber16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:green;">СберБанк</span>';
					}elseif($row["method"]=="AdvCash") {
						echo '<img src="/img/advcash_18x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span>';
					}else{
						echo $row["method"];
					}
				echo '</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr align="center"><td colspan="4">Выплат в ожидании нет</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

}

include(ROOT_DIR."/footer.php");
?>