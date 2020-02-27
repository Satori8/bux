<?php
session_start();

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	$pages = isset($_GET["page"]) ? htmlspecialchars(trim($_GET["page"])) : false;
	if($pages == "myview") {
		$pagetitle = "Конкурсы для рефералов";
	}elseif($pages == "view") {
		$pagetitle = "Конкурсы от реферера";
	}else{
		$pagetitle = "Конкурсы для рефералов";
	}
	include("header.php");
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	if(isset($_GET["page"]) && htmlspecialchars($_GET["page"])!="") {
		if(htmlspecialchars($_GET["page"])=="myview") {
			$pagetitle="Конкурсы для рефералов";
			include('header.php');
			require_once('.zsecurity.php');
			require('config.php');
			error_reporting (E_ALL);
			require("ref_konkurs.php");
			include("footer.php");
		}
		if(htmlspecialchars($_GET["page"])=="view") {
			$pagetitle="Конкурсы от реферера";
			include('header.php');
			require_once('.zsecurity.php');
			require('config.php');
			error_reporting (E_ALL);
			echo '<a href="/refkonkurs.php?page=myview"><img src="img/add.png" border="0" alt="" align="middle" title="Создать новый конкурс для рефералов" /> Создать конкурс для своих рефералов &gt;&gt;</a><br><br>';
			echo ' * <b>Обратите внимание:</b> <span style="color:#FF0000;">В рефконкурсах не учитываются клики по своей рекламе и клики по ссылкам у которых установлен геотаргетинг.</span><br><br>';
			require("ref_konkurs2.php");
			include("footer.php");
		}
	}else{
		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/">';
	}
}
?>