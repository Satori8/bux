<?php
session_start();

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	$pages = isset($_GET["page"]) ? htmlspecialchars(trim($_GET["page"])) : false;
	if($pages == "myview") {
		$pagetitle = "�������� ��� ���������";
	}elseif($pages == "view") {
		$pagetitle = "�������� �� ��������";
	}else{
		$pagetitle = "�������� ��� ���������";
	}
	include("header.php");
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
	include('footer.php');
	exit();
}else{
	if(isset($_GET["page"]) && htmlspecialchars($_GET["page"])!="") {
		if(htmlspecialchars($_GET["page"])=="myview") {
			$pagetitle="�������� ��� ���������";
			include('header.php');
			require_once('.zsecurity.php');
			require('config.php');
			error_reporting (E_ALL);
			require("ref_konkurs.php");
			include("footer.php");
		}
		if(htmlspecialchars($_GET["page"])=="view") {
			$pagetitle="�������� �� ��������";
			include('header.php');
			require_once('.zsecurity.php');
			require('config.php');
			error_reporting (E_ALL);
			echo '<a href="/refkonkurs.php?page=myview"><img src="img/add.png" border="0" alt="" align="middle" title="������� ����� ������� ��� ���������" /> ������� ������� ��� ����� ��������� &gt;&gt;</a><br><br>';
			echo ' * <b>�������� ��������:</b> <span style="color:#FF0000;">� ������������ �� ����������� ����� �� ����� ������� � ����� �� ������� � ������� ���������� ������������.</span><br><br>';
			require("ref_konkurs2.php");
			include("footer.php");
		}
	}else{
		echo '<script type="text/javascript">location.replace("/");</script>';
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/">';
	}
}
?>