<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<div align="center" style="color:#0000FF; font-weight: bold; padding-bottom:10px; font-size:14pt;">���������� ������</div>';

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("'","",$mensaje);
	$mensaje = str_replace(";","",$mensaje);
	$mensaje = str_replace("$","",$mensaje);
	$mensaje = str_replace("<","",$mensaje);
	$mensaje = str_replace(">","",$mensaje);
	$mensaje = str_replace("`","",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	return $mensaje;
}

if(count($_GET)>0) {
	$id_r = (isset($_GET["id_r"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id_r"]))) ? intval(limpiar(trim($_GET["id_r"]))) : false;
	$id_pr = (isset($_GET["id_pr"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id_pr"]))) ? intval(limpiar(trim($_GET["id_pr"]))) : false;
	$user_f = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	$option = (isset($_GET["option"])) ? limpiar(trim($_GET["option"])) : false;

	if($option=="add_pr") {

		$sql_r = mysql_query("SELECT `id`,`razdel` FROM `tb_forum_r` ORDER BY `id` ASC");
		if(mysql_num_rows($sql_r)>0) {

			$title_pr = (isset($_POST["title_pr"])) ? limitatexto(limpiarez($_POST["title_pr"]),100) : false;
			$opis_pr = (isset($_POST["opis_pr"])) ? limitatexto(limpiarez($_POST["opis_pr"]),100) : false;
			$to_razdel = (isset($_POST["to_razdel"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["to_razdel"]))) ? intval(limpiar(trim($_POST["to_razdel"]))) : false;
			$add_status_0 = "1";
			$add_status_1 = "1";
			$add_status_2 = "1";
			$add_status_3 = "1";
			$status_add = "NO";

			if(count($_POST)>0) {
				$add_status_0 = (isset($_POST["add_status_0"]) && preg_match("|^[\d]$|", trim($_POST["add_status_0"])) && intval(limpiar(trim($_POST["add_status_0"])))==1) ? "1" : "0";
				$add_status_1 = (isset($_POST["add_status_1"]) && preg_match("|^[\d]$|", trim($_POST["add_status_1"])) && intval(limpiar(trim($_POST["add_status_1"])))==1) ? "1" : "1";
				$add_status_2 = (isset($_POST["add_status_2"]) && preg_match("|^[\d]$|", trim($_POST["add_status_2"])) && intval(limpiar(trim($_POST["add_status_2"])))==1) ? "1" : "0";
				$add_status_3 = (isset($_POST["add_status_3"]) && preg_match("|^[\d]$|", trim($_POST["add_status_3"])) && intval(limpiar(trim($_POST["add_status_3"])))==1) ? "1" : "0";
				$add_status = $add_status_0.$add_status_1.$add_status_2.$add_status_3;

				if($title_pr==false) {
					echo '<fieldset class="errorp">������! �� ������ ��������� ����������!</fieldset>';
				}elseif($opis_pr==false) {
					echo '<fieldset class="errorp">������! �� ������� ������� �������� ����������!</fieldset>';
				}else{
					mysql_query("INSERT INTO `tb_forum_pr` (`status`,`username`,`ident_r`,`title`,`opis`,`date`) VALUES('$add_status','$user_f','$to_razdel','$title_pr','$opis_pr','".time()."')") or die(mysql_error());

					mysql_query("ALTER TABLE `tb_forum_r` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_pr` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_t` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_p` ORDER BY `id`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_r`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_pr`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_t`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_p`") or die(mysql_error());

					$status_add = "OK";

					echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");</script> ';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
					exit();
				}
			}

			if($status_add!="OK") {
				echo '<form method="POST" action="">';
				echo '<table>';
					echo '<tr><th colspan="2" class="center">�������� ����������</th></tr>';
					echo '<tr><td>���� ��������</td><td>'.DATE("d.m.Y�. � H:i", time()).'</td></tr>';
					echo '<tr><td>��������� ����������</td><td><input type="text" name="title_pr" value="'.$title_pr.'" maxlength="100" size="100"></td></tr>';
					echo '<tr><td>������� ��������</td><td><input type="text" name="opis_pr" value="'.$opis_pr.'" maxlength="100" size="100"></td></tr>';
					echo '<tr>';
						echo '<td>������� � �������</td>';
						echo '<td>';
							echo '<select name="to_razdel">';
								while ($row_r = mysql_fetch_row($sql_r)) {
									echo '<option value="'.$row_r["0"].'" '.("".$row_r["0"]."" == $to_razdel ? 'selected="selected"' : false).'>'.$row_r["1"].'</option>';
								}
							echo '</select>';
						echo '</td>';
					echo' </tr>';
					echo '<tr>';
						echo '<td>����� �� �������� ���</td>';
						echo '<td>';
							echo '<table style="width:auto;">';
								echo '<tr>';
									echo '<td><input type="checkbox" name="add_status_1" value="1" '.("1" == "$add_status_1" ? 'checked="checked"' : false).' disabled="disabled">�������������</td>';
									echo '<td><input type="checkbox" name="add_status_2" value="1" '.("1" == "$add_status_2" ? 'checked="checked"' : false).'>���������</td>';
								echo '</tr>';
									echo '<td><input type="checkbox" name="add_status_3" value="1" '.("1" == "$add_status_3" ? 'checked="checked"' : false).'>�����������</td>';
									echo '<td><input type="checkbox" name="add_status_0" value="1" '.("1" == "$add_status_0" ? 'checked="checked"' : false).'>������������</td>';
								echo '<tr>';
								echo '</tr>';
							echo '</table>';
						echo '</td>';
					echo '</tr>';
					echo '<tr><td></td><td><input type="submit" value="��������" class="sub-green"></td></tr>';
				echo '</table>';
				echo '</form>';
			}
		}else{
			echo '<fieldset class="errorp">������! ���������� ������� ������� ������</fieldset>';
		}
	}


	if($option=="edit_pr" && $id_pr!=false) {

		$sql_pr = mysql_query("SELECT * FROM `tb_forum_pr` WHERE `id`='$id_pr' ORDER BY `id` ASC");
		if(mysql_num_rows($sql_pr)>0) {
			$row_pr = mysql_fetch_array($sql_pr);
			$id_r = $row_pr["ident_r"];
			$id_pr = $row_pr["id"];
			$title_pr = $row_pr["title"];
			$opis_pr = $row_pr["opis"];

			$add_status_0 = isset($row_pr["status"][0]) ? $row_pr["status"][0] : "0";
			$add_status_1 = isset($row_pr["status"][1]) ? $row_pr["status"][1] : "1";
			$add_status_2 = isset($row_pr["status"][2]) ? $row_pr["status"][2] : "0";
			$add_status_3 = isset($row_pr["status"][3]) ? $row_pr["status"][3] : "0";

			$title_pr = (isset($_POST["title_pr"])) ? limitatexto(limpiarez($_POST["title_pr"]),100) : "$title_pr";
			$opis_pr = (isset($_POST["opis_pr"])) ? limitatexto(limpiarez($_POST["opis_pr"]),100) : "$opis_pr";
			$to_razdel = (isset($_POST["to_razdel"]) && (preg_match("|^[\d]{1,11}$|", trim($_POST["to_razdel"])) | intval(limpiar(trim($_POST["to_razdel"])))!=0)) ? intval(limpiar(trim($_POST["to_razdel"]))) : "$id_r";
			$status_add = "NO";

			if(count($_POST)>0) {
				$add_status_0 = (isset($_POST["add_status_0"]) && preg_match("|^[\d]$|", trim($_POST["add_status_0"])) && intval(limpiar(trim($_POST["add_status_0"])))==1) ? "1" : "0";
				$add_status_1 = (isset($_POST["add_status_1"]) && preg_match("|^[\d]$|", trim($_POST["add_status_1"])) && intval(limpiar(trim($_POST["add_status_1"])))==1) ? "1" : "1";
				$add_status_2 = (isset($_POST["add_status_2"]) && preg_match("|^[\d]$|", trim($_POST["add_status_2"])) && intval(limpiar(trim($_POST["add_status_2"])))==1) ? "1" : "0";
				$add_status_3 = (isset($_POST["add_status_3"]) && preg_match("|^[\d]$|", trim($_POST["add_status_3"])) && intval(limpiar(trim($_POST["add_status_3"])))==1) ? "1" : "0";
				$add_status = $add_status_0.$add_status_1.$add_status_2.$add_status_3;

				if($title_pr==false) {
					echo '<fieldset class="errorp">������! �� ������ ��������� ����������!</fieldset>';
				}elseif($opis_pr==false) {
					echo '<fieldset class="errorp">������! �� ������� ������� �������� ����������!</fieldset>';
				}else{
					if($to_razdel==$id_r) {
						mysql_query("UPDATE `tb_forum_pr` SET `status`='$add_status', `title`='$title_pr',`opis`='$opis_pr' WHERE `id`='$id_pr' AND `ident_r`='$id_r'") or die(mysql_error());
					}else{
						mysql_query("UPDATE `tb_forum_pr` SET `status`='$add_status', `ident_r`='$to_razdel',`title`='$title_pr',`opis`='$opis_pr' WHERE `id`='$id_pr'") or die(mysql_error());
						mysql_query("UPDATE `tb_forum_t` SET `ident_r`='$to_razdel' WHERE `ident_r`='$id_r' AND `ident_pr`='$id_pr'") or die(mysql_error());
						mysql_query("UPDATE `tb_forum_p` SET `ident_r`='$to_razdel' WHERE `ident_r`='$id_r' AND `ident_pr`='$id_pr'") or die(mysql_error());
					}

					mysql_query("ALTER TABLE `tb_forum_r` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_pr` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_t` ORDER BY `id`") or die(mysql_error());
					mysql_query("ALTER TABLE `tb_forum_p` ORDER BY `id`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_r`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_pr`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_t`") or die(mysql_error());
					mysql_query("OPTIMIZE TABLE `tb_forum_p`") or die(mysql_error());

					$status_add = "OK";
					echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");</script> ';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
					exit();
				}
			}

			if($status_add!="OK") {
				echo '<form method="POST" action="">';
				echo '<table>';
					echo '<tr><th colspan="2" class="center">�������������� ����������</th></tr>';
					echo '<tr><td width="250px">���� ��������</td><td>'.DATE("d.m.Y�. � H:i", $row_pr["date"]).'</td></tr>';
					echo '<tr><td>��������� ����������</td><td><input type="text" name="title_pr" value="'.$title_pr.'" maxlength="100" size="100"></td></tr>';
					echo '<tr><td>������� ��������</td><td><input type="text" name="opis_pr" value="'.$opis_pr.'" maxlength="100" size="100"></td></tr>';
					echo '<tr>';
						echo '<td>��������� � ������:<br><i style="font-size:95%; color:#CCC;">�� �������� ���� �� ������ ����������</i></td>';
						echo '<td>';
							echo '<select name="to_razdel">';
								$sql_r = mysql_query("SELECT `id`,`razdel` FROM `tb_forum_r` ORDER BY `id` ASC");
								if(mysql_num_rows($sql_r)>0) {
									while ($row_r = mysql_fetch_row($sql_r)) {
										echo '<option value="'.$row_r["0"].'" '.("".$row_r["0"]."" == $to_razdel ? 'selected="selected"' : false).'>'.$row_r["1"].'</option>';
									}
								}
							echo '</select>';
						echo '</td>';
					echo' </tr>';
					echo '<tr>';
						echo '<td>����� �� �������� ���</td>';
						echo '<td>';
							echo '<table style="width:auto;">';
								echo '<tr>';
									echo '<td><input type="checkbox" name="add_status_1" value="1" '.("1" == "$add_status_1" ? 'checked="checked"' : false).' disabled="disabled">�������������</td>';
									echo '<td><input type="checkbox" name="add_status_2" value="1" '.("1" == "$add_status_2" ? 'checked="checked"' : false).'>���������</td>';
								echo '</tr>';
									echo '<td><input type="checkbox" name="add_status_3" value="1" '.("1" == "$add_status_3" ? 'checked="checked"' : false).'>�����������</td>';
									echo '<td><input type="checkbox" name="add_status_0" value="1" '.("1" == "$add_status_0" ? 'checked="checked"' : false).'>������������</td>';
								echo '<tr>';
								echo '</tr>';
							echo '</table>';
						echo '</td>';
					echo '</tr>';
					echo '<tr><td></td><td><input type="submit" value="���������" class="sub-green"></td></tr>';
				echo '</table>';
				echo '</form>';
			}
		}else{
			echo '<fieldset class="errorp">������! ���������� ������� ������� ������</fieldset>';
		}
	}


	if($option=="del_pr" && $id_pr!=false) {

		$sql_r = mysql_query("SELECT `id` FROM `tb_forum_pr` WHERE `id`='$id_pr'");
		if(mysql_num_rows($sql_r)>0) {

			mysql_query("DELETE FROM `tb_forum_pr` WHERE `id`='$id_pr'") or die(mysql_error());
			mysql_query("DELETE FROM `tb_forum_t` WHERE `ident_pr`='$id_pr'") or die(mysql_error());
			mysql_query("DELETE FROM `tb_forum_p` WHERE `ident_pr`='$id_pr'") or die(mysql_error());

			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");</script> ';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
			exit();
		}else{
			echo '<fieldset class="errorp">������! ��������� �� ������!</fieldset>';
		}
	}
}

echo '<table>';
echo '<thead>';
	echo '<tr>';
		echo '<th colspan="2">�������� ����������</th>';
		echo '<th class="center" width="100px">����</th>';
		echo '<th class="center" width="100px">������</th>';
		echo '<th class="center" width="130px">���� ��������</th>';
		echo '<th width="50px"></th>';
		echo '<th width="100px"></th>';
	echo '</tr>';
echo '</thead>';

echo '<tbody>';
$sql_r = mysql_query("SELECT `id`,`razdel` FROM `tb_forum_r` ORDER BY `id` ASC");
if(mysql_num_rows($sql_r)>0) {
	while ($row_r = mysql_fetch_row($sql_r)) {
		echo '<tr align="left"><td colspan="7" style="background:#f5f5b5;">������:&nbsp;'.$row_r["1"].'</td></tr>';

		$sql_pr = mysql_query("SELECT * FROM `tb_forum_pr` WHERE `ident_r`='".$row_r["0"]."' ORDER BY `id` ASC");
		if(mysql_num_rows($sql_pr)>0) {
			while ($row_pr = mysql_fetch_array($sql_pr)) {
				echo '<tr>';
					echo '<td width="30px" style="background:#f5f5b5;">&nbsp;</td>';
					echo '<td><b>'.$row_pr["title"].'</b><br><span>'.$row_pr["opis"].'</span></td>';
					echo '<td class="center">'.$row_pr["count_t"].'</td>';
					echo '<td class="center">'.$row_pr["username"].'</td>';
					echo '<td class="center">'.DATE("d.m.Y�. � H:i",$row_pr["date"]).'</td>';

					echo '<td><div align="center"><form method="GET" action="">';
						echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
						echo '<input type="hidden" name="option" value="edit_pr">';
						echo '<input type="hidden" name="id_pr" value="'.$row_pr["id"].'">';
						echo '<input type="submit" value="��������" class="sub-green">';
					echo '</form></div></td>';

					echo '<td><div align="center"><form method="GET" action="" onsubmit=\'if(!confirm("�� ������� ��� ������ ������� ���������?\n\r��� �������� ���������� ����� ����� ������� ��� ���� � �����!")) return false;\'">';
						echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
						echo '<input type="hidden" name="option" value="del_pr">';
						echo '<input type="hidden" name="id_pr" value="'.$row_pr["id"].'">';
						echo '<input type="submit" value="�������" class="sub-red">';
					echo '</form></div></td>';

				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td colspan="7"><div align="center" style="color:#FF0000; font-weight: bold;">���������� �� �������!</div></td>';
			echo '</tr>';
		}
	}
}else{
	echo '<tr>';
		echo '<td colspan="7"><div align="center" style="color:#FF0000; font-weight: bold;">���������� ������� �������!</div></td>';
	echo '</tr>';
}
echo '<tr><th class="center" colspan="7">';
	echo '<div align="center">';
		echo '<form method="GET" action="">';
			echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
			echo '<input type="hidden" name="option" value="add_pr">';
			echo '<input type="submit" value="�������� ���������" class="sub-blue160">';
		echo '</form>';
	echo '</div>';
echo '</th></tr>';

echo '</tbody>';

echo '</table>';

?>