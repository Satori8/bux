<?php
$pagetitle="�������� E-mail";
include("header.php");
require_once('.zsecurity.php');
require('config.php');

error_reporting (E_ALL);

$id = (isset($_GET["uid"])) ? intval(limpiar($_GET["uid"])) : false;
$check = (isset($_GET["check"])) ? limpiar($_GET["check"]) : false;

$check_tic = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $check );

if($id>0 && $check_tic==1 && $check!=false) {
	$sql = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$id'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$email_ok = $row["email_ok"];
		$email_kod = $row["email_kod"];

		if($email_ok==1) {
			echo '<span class="msg-error">������! �� ��� ��������� ���� E-mail.</span>';
		}else{
			if($check==$email_kod) {
				$email_kod="";
				mysql_query("UPDATE `tb_users` SET `email_ok`='1', `email_sent`='1', `email_kod`='$email_kod' WHERE `id`='$id'") or die(mysql_error());

				echo '<span class="msg-ok">�����������! �� ������� ����������� ���� E-mail � ������������ �������!</span>';
			}else{
				echo '<span class="msg-error">������! ����� ������ �� ����������!</span>';
			}
		}
	}else{
		echo '<span class="msg-error">������! ������������, � ��������� id �� ������!</span>';
	}
}else{
	echo '<span class="msg-error">������! ����� ������ �� ����������!</span>';
}

include('footer.php');?>