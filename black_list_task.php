<?php
$pagetitle="������ ������";
include('header.php');
require_once('.zsecurity.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">������! ��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	echo '<fieldset style="width:auto; text-align:justify; margin:0px 0px 0px 0px; padding:5px 10px 5px 10px; border:1px solid #CCC; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;"><legend>&nbsp;<b>����������</b>&nbsp;</legend>';
		echo '�� ������ �������� ������������� � ������ ������.<br>';
		echo '����������� ������������ �� ������ �������� ������ � ����� ��������, ��� ����� �������� ����������� � ��� ��� ������������� �������� �� ��������� ���� �������.';
	echo '</fieldset><br><br>';

	function limpiarez($mensaje){
		$mensaje = trim($mensaje);
		$mensaje = str_replace("?","&#063;",$mensaje);
		$mensaje = str_replace(">","&#062;",$mensaje);
		$mensaje = str_replace("<","&#060;",$mensaje);
		$mensaje = str_replace("'","&#039;",$mensaje);
		$mensaje = str_replace("$","&#036;",$mensaje);
		$mensaje = str_replace('"',"&#034;",$mensaje);
		return $mensaje;
	}

	$rek_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	require('config.php');

	if(count($_POST)>0 && !isset($_POST["op"])) {
		$user_name = (isset($_POST["name"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["name"]))) ? uc($_POST["name"]) : false;

		$check_user_1 = mysql_query("SELECT `username` FROM `tb_users` WHERE `username`='$user_name'");
		$exist_user_1 = mysql_num_rows($check_user_1);

		$check_user_2 = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$user_name'");
		$exist_user_2 = mysql_num_rows($check_user_2);

		if(strtolower($user_name)==strtolower($rek_name)) {
			echo '<span class="msg-error">�� �� ������ �������� ���� � ������ ������!</span>';
		}elseif($exist_user_1<1) {
			echo '<span class="msg-error">������������ � ������� '.$user_name.' ��� � �������!</span>';
		}elseif($exist_user_2>0) {
			echo '<span class="msg-error">������������ ��� ���� � ����� ������ ������!</span>';
		}else{
			$row_add = mysql_fetch_row($check_user_1);

			mysql_query("INSERT INTO `tb_ads_task_bl`(`rek_name`,`user_name`,`date_add`,`ip`) VALUES('$rek_name','".$row_add["0"]."','".time()."','$ip')");

			echo '<span class="msg-ok">������������ � ������� '.$user_name.' ������� �������� � ������ ������!</span>';
		}

	}elseif(count($_POST)>0 && isset($_POST["op"])) {
		$id = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? intval(limpiarez(trim($_POST["id"]))) : false;

		$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `id`='$id' AND `rek_name`='$rek_name'");
		if(mysql_num_rows($check_user)>0) {

			mysql_query("DELETE FROM `tb_ads_task_bl` WHERE `id`='$id' AND `rek_name`='$rek_name'") or die(mysql_error("ERROR"));

			echo '<span class="msg-ok">������������ ������� ������ �� ������� ������!</span>';
		}else{
			echo '<span class="msg-error">� ��� ��� ������ ������������ � ������ ������!</span>';
		}
	}

	?>

	<script type="text/javascript" language="JavaScript"> 
		function gebi(id){
			return document.getElementById(id)
		}

		function ltrim(str) {
			var ptrn = /\s*((\S+\s*)*)/;
			return str.replace(ptrn, "$1");
		}

		function rtrim(str) {
			var ptrn = /((\s*\S+)*)\s*/;
			return str.replace(ptrn, "$1");
		}

		function trim(str) {
			return ltrim(rtrim(str));
		}

		function SbmFormB() {
		        regexp = /^[a-zA-Z0-9\-_-]{3,25}$/;
		        var users = trim(document.forms["formmls"].name.value);

			arrayElem = document.forms["formmls"];
			var col=0;

			for (var i=0;i<arrayElem.length;i++){
				if ((document.forms["formmls"].name.value == '')) {
					alert('�� �� ������� ����� ������������');
					arrayElem[i+0].style.background = "#FFDBDB";
					arrayElem[i+0].focus();
					return false;
				}else{
					arrayElem[i+0].style.background = "#FFFFFF";
				}
			        if(([regexp.test(users)])=="false") {
					alert('�� �� ����� ������� ����� ������������');
					arrayElem[i+0].style.background = "#FFDBDB";
					arrayElem[i+0].focus();
					return false;
				}else{
					arrayElem[i+0].style.background = "#FFFFFF";
				}
			}
			document.forms["formmls"].submit();
			return true;
		}
	</script>

	<?php

	echo '<form method="post" action="" id="newform" name="formmls" onsubmit="return SbmFormB(); return false;">';
	echo '<table class="tables">';
	echo '<thead><tr><th colspan="3" class="top">���������� ������������ � ������ ������</th></tr></thead>';
	echo '<tr>';
		echo '<td width="70" nowrap="nowrap" align="right"><b>�����:</b></td>';
		echo '<td align="center"><input type="text" name="name" value="" maxlength="25" class="ok"></td>';
		echo '<td width="160" align="center"><input type="submit" class="submit" style="float:none;" value="��������"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';


	echo '<br>';
	echo '<h2 class="sp">��� ������ ������</h2>';

	echo '<table class="tables">';
	echo '<thead><tr align="center">';
		echo '<th class="top">�����</th>';
		echo '<th class="top">���� ����������</th>';
		echo '<th class="top">[�������]</th>';
	echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name'");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {
			echo '<tr align="center">';
				echo '<td>'.$row["user_name"].'</td>';
				echo '<td>'.DATE("d.m.Y",$row["date_add"]).'</td>';
				echo '<td>';
					echo '<form action="" method="post">';
						echo '<input type="hidden" value="del" name="op">';
						echo '<input type="hidden" value="'.$row["id"].'" name="id">';
						echo '<input type="image" src="img/close.png" alt="�������" title="�������" onClick=\'if(!confirm("�� ������� ��� ������ ������� ������������ �� ������� ������?")) return false;\'>';
					echo '</form>';
				echo '</td>';
			echo '</tr>';
		}
	}else{
		echo '<tr align="center"><td colspan="3"><b style="color: #FF0000;">� ��� ��� ������������� � ������ ������!</b></td></tr>';
	}
	echo '</table>';
}

include('footer.php');
?>