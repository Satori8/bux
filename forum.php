<?php
$pagetitle="�����";
include("header.php");
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="msg-error">������! ��� ������� � ���� �������� ���������� ��������������!</fieldset>';
}else{
require("config.php");

error_reporting (E_ALL);
require("forum/index.php");
}
include("footer.php");
?>