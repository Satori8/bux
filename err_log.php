<?php
$pagetitle="������ �����������";
include('header.php');
require_once('.zsecurity.php');
require('config.php');

$error_s = (isset($_GET["s"])) ? intval(limpiar($_GET["s"])) : "0";

if(isset($_GET["s"]) && intval(limpiar($_GET["s"]))=="1") {
	echo '<span class="msg-error">���������� ��������������!</span>';
}

if(isset($_GET["s"]) && intval(limpiar($_GET["s"]))=="2") {
	echo '<span class="msg-error">� ��� ��������� IP-����� ��� �������, ���������� ��������� � �������!</span>';
}

if($error_s<1|$error_s>2) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}

include("footer.php");
?>