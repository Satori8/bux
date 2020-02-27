<?php
session_start();
$pagetitle="История операций";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	require("navigator/navigator.php");
	require("config.php");

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$perpage = 25;
	$sql_p = mysql_query("SELECT `id` FROM `tb_history` WHERE `user`='$username'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	echo '<h3 class="sp">История операций</h3>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th class="top">№</th>';
		echo '<th class="top">Дата</th>';
		echo '<th class="top">Сумма</th>';
		echo '<th class="top">Операция</th>';
		echo '<th class="top">Состояние</th>';
	echo '</tr></thead>';
	
	if(isset($_GET["op"]) && isset($_POST["id"]) && $_GET["op"]=="dell_pay") {
			$id = (isset($_POST["id"])) ? intval($_POST["id"]) : false;

			$sql = mysql_query("SELECT * FROM `tb_history` WHERE `id`='$id' AND `status_pay`='0' AND `user`='$username'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$money_pay = abs($row["amount"]);

				mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_history` SET `status_pay`='2', `date`='".DATE("d.m.Y H:i",time())."', `time`='".time()."' WHERE `id`='$id' AND `status_pay`='0' AND `user`='$username'") or die(mysql_error());

				echo '<span class="msg-ok">Выпплата отменена, деньги возвращены на баланс аккаунта!</span>';
			}else{
				echo '<span class="msg-error">Выплаты с таким id #'.$id.' нет, либо выплата уже была сделана (или отменена)!</span>';
			}
		}

	$sql = mysql_query("SELECT * FROM `tb_history` WHERE `user`='$username' ORDER BY `id` DESC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {

			echo '<tr align="center">';
				echo '<td>'.$row["id"].'</td>';
				echo '<td>'.$row["date"].'</td>';
				echo '<td>'.($row["amount"]>0 ? number_format($row["amount"],2,".","'") : "-").'</td>';
			echo '<td>'.((strtolower($row["method"])=="webmoney" | strtolower($row["method"])=="yandexmoney" | strtolower($row["method"])=="perfectmoney" | strtolower($row["method"])=="payeer" | strtolower($row["method"])=="qiwi" | strtolower($row["method"])=="Megaphone" | strtolower($row["method"])=="sberbank" | strtolower($row["method"])=="paypal" | strtolower($row["method"])=="advcash") ? "Выплата на ".(strtolower($row["method"])!="Megphone" ? "платежную систему" : false)." <b>".str_ireplace("Megaphone", "Мобильный телефон", str_ireplace("SberBank", "СберБанк", $row["method"]))."</b>" : $row["method"]).'</td>';
				echo '<td>';
					if($row["tipo"]==0 && $row["status_pay"]==0 && $row["status"]==false) {
						echo "Ожидает обработки";
						echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?op=dell_pay"><input type="hidden" name="id" value="'.$row["id"].'"><input type="submit" value="Отменить" class="sub-blue"></form>';
					}elseif($row["status"]!=false){
						echo $row["status"];
					}elseif($row["status_pay"]==1){
						echo "Выплачено";
					}elseif($row["status_pay"]=="2") {
						echo 'Возвращено на баланс';
					}
				echo '</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr align="center"><td colspan="5">Записей не найдено!</td></tr>';
	}
	echo '</table>';
	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");
}

include('footer.php');?>