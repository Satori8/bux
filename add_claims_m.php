<?php
$pagetitle="������ ������ �� ����";
include('header.php');
require_once('.zsecurity.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">������! ��� ������� � ������ �������� ���������� ������������������!</fieldset><br>';
	include('footer.php');
	exit();
}else{
	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");

	$username = uc($_SESSION["userLog"]);

	if(isset($_GET["id"])) {
		if(preg_match("|^[\d]*$|",trim($_GET["id"]))) {
			$id = limpiar(trim($_GET["id"]));

			$checkid = mysql_query("SELECT `url` FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1'");
			if(mysql_num_rows($checkid)<1){
				echo '<fieldset class="errorp">������! ��������� ������ �� �������!</fieldset><br>';
				include('footer.php');
				exit();
			}else{
				$row_id = mysql_fetch_array($checkid);
				$url = $row_id["0"];

				$sql = mysql_query("SELECT `id` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `ident`='$id'");
				if(mysql_num_rows($sql)>0){
					echo '<fieldset class="errorp">������! �� ��� �������� ������ �� ���� ����!</fieldset><br>';
					include('footer.php');
					exit();
				}else{

					if(count($_POST) > 0) {
						if(preg_match("|^[1-4]$|",trim($_POST["claims"]))) {
							$claims = intval(trim($_POST["claims"]));

							mysql_query("INSERT INTO `tb_ads_mails_visits` (`username`,`ident`,`type`,`date`,`ip`, `money`,`test`) VALUES('$username','$id','1','".time()."','$ip','0','������')") or die(mysql_error());
							mysql_query("UPDATE `tb_ads_mails` SET `claims`=`claims`+'1' WHERE `id`='$id'") or die(mysql_error());

							echo '<fieldset class="okp">������ ������� ���������!</fieldset><br>';
							include('footer.php');
							exit();
						}else{
							echo '<img src="img/close.png" width="18" height="18" border="0" align="absmiddle" alt="" /><font color="red"><b>������! �� ������ ������������ ������!</b></font><br><br>';
							echo '<fieldset class="errorp">������! �� ������ ������������ ������!</fieldset><br>';
							include('footer.php');
							exit();
						}
					}else{
						echo '<form method="post" action="">';
							echo '<table border="0">';
								echo '<tr><td>��� �����: <b>'.$username.'</b></td></tr>';
								echo '<tr><td>������ �� ����: <b><a href="'.$url.'" target="_blank">'.$url.'</a></b></td></tr>';
								echo '<tr><td>�������:<br>';
									echo '<select name="claims">';
										echo '<option value="1">��������� ������</option>';
										echo '<option value="2">�������� �����</option>';
										echo '<option value="3">����������� ������ ����� �� ����������� ������</option>';
										echo '<option value="4">������...</option>';
									echo '</select>';
								echo '</td></tr>';
								echo '<tr><td><input type="submit" class="submit" value="�������� ������ &gt;"></td></tr>';
							echo '</table>';
						echo '</form><br><br>';

						echo '<fieldset class="okp">��������! ��� ���������� ������ ����� �� ����, ��� ������� ����� ����������!</fieldset><br>';
					}
				}
			}
		}else{
			echo '<fieldset class="errorp">������! ��������� ������ �� �������!</fieldset><br>';
			include('footer.php');
			exit();
		}
	}else{
		echo '<fieldset class="errorp">������!</fieldset><br>';
	}
}

include('footer.php');
?>