<?php
$pagetitle="Оплачиваемые задания";
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
include(DOC_ROOT."/header.php");
require_once(DOC_ROOT."/.zsecurity.php");
require(DOC_ROOT."/merchant/func_mysql.php");
error_reporting (E_ALL);

 


if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_b = mysql_query("SELECT * FROM `tb_black_users` WHERE `name`='$username' ORDER BY `id` DESC");
	if(mysql_num_rows($sql_b)>0) {
		$row_b = mysql_fetch_assoc($sql_b);
		echo '<span class="msg-error">Ваш аккаунт заблокирован! Вы не можете просматривать рекламу!<br><u>Дата блокировки</u>: '.$row_b["date"].'</span>';
		include('footer.php');
		exit();
	}
}






if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	require(DOC_ROOT."/config.php");

	if(isset($_GET["option"]) && limpiar($_GET["option"])!="") {

		if(limpiar($_GET["option"])=="add_task") {
			require("ads_task/add_task.php");
		}
		if(limpiar($_GET["option"])=="edit_task") {
			require("ads_task/edit_task.php");
		}
		if(limpiar($_GET["option"])=="task_view") {
			require("ads_task/view_task.php");
		}
		if(limpiar($_GET["option"])=="del_task") {
			require("ads_task/del_task.php");
		}
		if(limpiar($_GET["option"])=="addmoney_task") {
			require("ads_task/addmoney_task.php");
		}
		if(limpiar($_GET["option"])=="active_task") {
			require("ads_task/active_task.php");
		}
		if(limpiar($_GET["option"])=="pause_task") {
			require("ads_task/pause_task.php");
		}
		if(limpiar($_GET["option"])=="up_task") {
			require("ads_task/up_task.php");
		}
		if(limpiar($_GET["option"])=="color_task") {
			require("ads_task/color_task.php");
		}
		if(limpiar($_GET["option"])=="up_vip") {
			require("ads_task/up_vip.php");
		}
		if(limpiar($_GET["option"])=="task_stat") {
			require("ads_task/view_task_stat.php");
		}
		if(limpiar($_GET["option"])=="task_get") {
			require("ads_task/view_task_pay.php");
		}
		if(limpiar($_GET["option"])=="task_mod") {
			require("ads_task/view_task_mod.php");
		}

	}else{
		require("ads_task/view_task.php");
/*
		$sql = mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `username`='$username'");
		$all_task = mysql_num_rows($sql);

		echo "<br>";
		echo '<table width="100%" border="0" style="border: 1px solid #4682B4;">';
		echo '<tr><td width="18"><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&option=add_task"><img src="img/add.png" border="0" alt="" align="middle" title="Создать новое задание" /></a></td><td><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&option=add_task" title="Создать новое задание">Создать новое задание</a></td></tr>';
		echo '<tr><td colspan="2">Количество оплачиваемых заданий: <b>'.$all_task.'</b> [<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&option=task_view">подробнее</a>]</td></tr>';

		echo '</table><br><br>';
*/
	}
}

include('footer.php');?>