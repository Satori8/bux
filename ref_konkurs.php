<?php

if(!isset($_GET["page"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	function get_avatar($USER_NAME, $AVATAR=false) {
		$sql_us = mysql_query("SELECT `id`,`avatar` FROM `tb_users` WHERE `username`='$USER_NAME'");
		if(mysql_num_rows($sql_us)>0) {
			$row_us = mysql_fetch_row($sql_us);
			if($AVATAR!=false) {
				return '<a href="/wall.php?uid='.$row_us["0"].'" target="_blank" title="������� �� ����� ������������ '.$USER_NAME.'"><img class="avatar" src="/avatar/'.$row_us["1"].'" style="width:30px; height:30px" border="0" alt="" /></a>';
			}else{
				return '<a href="/wall.php?uid='.$row_us["0"].'" target="_blank" title="������� �� ����� ������������ '.$USER_NAME.'">'.$USER_NAME.'</a>';
			}
			//return $row_us["1"];
		}else{
			if($AVATAR!=false) {
				return '<img class="avatar" src="/avatar/no.png" style="width:30px; height:30px" border="0" alt="" />';
			}else{
				return ''.$USER_NAME.' - <span style="color:#FF0000;">������������ ������</span>';
			}
			//return "no.png";
		}
	}

	require_once ("config.php");
	require_once ("bbcode/bbcode.lib.php");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting_konk' AND `howmany`='1'");
	$tests_reiting_konk = number_format(mysql_result($sql,0,0), 0, ".", "");


	if(isset($_GET["op"]) && limpiar($_GET["op"])=="statkonkurs") {
		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `status`>'0' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			switch(substr($row["time_kon"], -1, 1)){
				case 1: $d=$row["time_kon"]." ����"; break;
				case 2: case 3: case 4: $d=$row["time_kon"]." ���"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." ����"; break;
			}

			$p = 0;
			for($i=1; $i<=$row["count_kon"]; $i++) {
				$p = $p + $row["p$i"];
			}

			$desc_kon = new bbcode($row["desc"]);
			$desc_kon = $desc_kon->get_html();

			echo '<h3 class="sp">������� #'.$row["id"].' �� ��������</h3>';
			echo '<table class="tables">';
			echo '<thead><tr><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '���������� �������';
					}elseif($row["type_kon"]==2) {
						echo '���������� ������������ ������';
					}elseif($row["type_kon"]==3) {
						echo '����������� ���������';
					}elseif($row["type_kon"]==4) {
						echo '����������� �� ������';
					}elseif($row["type_kon"]==5) {
						echo '���������� ������������ ���������� ������������';
					}elseif($row["type_kon"]==6) {
						echo '����� ������������ ��� ��������';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������';
					}elseif($row["type_kon"]==2) {
						echo '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==3) {
						echo '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������';
					}elseif($row["type_kon"]==4) {
						echo '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>';
						echo '-&nbsp;�������� ������ � �������� - 1 ����;<br>';
						echo '-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>';
						echo '-&nbsp;������ ������������� ������ - 2 �����;<br>';
						echo '-&nbsp;����������� ����� - '.$tests_reiting_konk.' �����;<br>';						
						echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
						echo '-&nbsp;����������� �������� - 7 ������.';
					}elseif($row["type_kon"]==5) {
						echo '�������� ����� ������ ��, ��� ������ ���������� ������������ ������������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==6) {
						echo '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������,������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>���������� �������� ����</b></td>';
				echo '<td colspan="2">'.$row["count_kon"].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�����:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'���.</b>, ';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ����:</b></td>';
				echo '<td colspan="2">'.$p.' ���.</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>��� ������� ��������� ���������� ������������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ������� ������ ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo '��� ��������� 1-�� ������';
					}else{
						echo '��� ��������� ���� ������<br>';
						echo '� �������� ��������� ��������� ��� �������� �� 3-�� �������';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>����� ���������� ��������:</b></td>';
				echo '<td colspan="2">c '.DATE("d.m.Y�. H:i:s", $row["date_s"]).' ��  '.DATE("d.m.Y�. H:i:s", $row["date_e"]).' ('.@$d.')</td>';
			echo '</tr>';
			echo '<tr>';
				if(time() < $row["date_s"]) {
					echo '<td align="right"><b>�� ������ ��������:</b></td>';
				}else{
					echo '<td align="right"><b>�� ��������� ��������:</b></td>';
				}

				if(time() > $row["date_e"]) {
					echo '<td colspan="2">������� ��������</td>';
				}elseif(time() < $row["date_s"]) {
					echo '<td colspan="2">'.date_ost($row["date_s"]-time()).'</td>';
				}else{
					echo '<td colspan="2">'.date_ost($row["date_e"]-time()).'</td>';
				}
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';
			echo '</table><br>';

			$sql_s = mysql_query("SELECT `username`, `referer`, SUM(`kolvo`) as `sum_kolvo` FROM `tb_refkonkurs_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_kon"]."' GROUP BY `username` ORDER BY SUM(`kolvo`) DESC LIMIT 100");
			$kol = mysql_num_rows($sql_s);
			if($kol > 0) {
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="4">TOP 100 ������������� �� ��������</th></tr></thead>';
				echo '<tr>';
					echo '<td colspan="4">�������������, ����������� � ��������: '.$kol.'</td>';
				echo '</tr>';

				echo '<thead><tr align="center">';
					echo '<th class="top">�����</th>';
					echo '<th class="top" colspan="2">�����</th>';
					/*if($row["type_kon"]==3) {
					echo '<th class="top">���������� ���������</th>';
					}*/
					echo '<th class="top">';
						if($row["type_kon"]==1) {
							echo '��������� �������';
						}elseif($row["type_kon"]==2) {
							echo '������� ������';
						}elseif($row["type_kon"]==3) {
							echo '���������� ���������';
						}elseif($row["type_kon"]==4) {
							echo '������� ������';
						}elseif($row["type_kon"]==5) {
							echo '������������ ������������';
						}elseif($row["type_kon"]==6) {
							echo '���������';
						}
					echo '</th>';
				echo '</tr></thead>';

				$i=0;
				while($row_s = mysql_fetch_array($sql_s)) {
					$i++; 
					if($row["type_kon"]==6) {
					    if($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon_sum"]) {
					    $style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}
					
					/*}elseif($row["type_kon"]==3) {
						if($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit2_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon"]) {
						$style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}*/
					
					}elseif($i<=$row["count_kon"] && $row_s["sum_kolvo"]>=$row["limit_kon"]) {
						$style1 = 'style="background-color:#fff3a4;"';
						$style2 = 'style="background-color:#fff3a4; border-right:none;"';
						$style3 = 'style="background-color:#fff3a4; border-left:none;"';
					}else{
						$style1 = '';
						$style2 = 'style="border-right:none;"';
						$style3 = 'style="border-left:none;"';
					}

					echo '<tr>';
						echo '<td align="center" width="10%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>$i</b>" : "$i").'</td>';
						echo '<td width="30px" align="center" '.$style2.'>';
							//echo '<img class="avatar" src="/avatar/'.get_avatar($row_s["username"]).'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar" />';
							echo get_avatar($row_s["username"], "avatar");
						echo '</td>';
						echo '<td align="left" width="45%" '.$style3.'>';
							echo (strtolower($row_s["username"])==strtolower($username) ? "<b>".get_avatar($row_s["username"])."</b>" : get_avatar($row_s["username"]));
							if($row["glubina"]==1) echo '&nbsp;&nbsp;<div style="float:right; font-size:11px; color:#9C9C9C;">������� '.str_replace("0", "1", $row_s["referer"]).' ��.</div>';
						echo '</td>';
        				if($row["type_kon"]==6) {
						echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".$row_s["sum_kolvo"]."</b>" : $row_s["sum_kolvo"]).'</td>';
						//}elseif($row["type_kon"]==3) {
						//echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".$row_s["sum_kolvo"]."</b>" : $row_s["sum_kolvo"]).'</td>';
						}else{
        					echo '<td align="center" width="45%" '.$style1.'>'.(strtolower($row_s["username"])==strtolower($username) ? "<b>".number_format($row_s["sum_kolvo"],0,'.','`')."</b>" : number_format($row_s["sum_kolvo"],0,'.','`')).'</td>';
					echo '</tr>';
						}
				}

				echo '</table>';
			}
		}else{
			echo '<span class="msg-error">������� �� ������.</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs3") {
		echo '<h3 class="sp">�������������� �������� ��� ���������</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			$type_kon = $row["type_kon"];
			$count_kon = $row["count_kon"];
			$time_kon = $row["time_kon"];
			$desc_kon = $row["desc"];

			switch(substr($row["time_kon"], -1, 1)){
				case 1: $d=$row["time_kon"]." ����"; break;
				case 2: case 3: case 4: $d=$row["time_kon"]." ���"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." ����"; break;
			}

			?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
			<script type="text/javascript">
				XBB.textarea_id = 'desc_kon';
				XBB.area_width = '98%';
				XBB.area_height = '200px';
				XBB.state = 'plain';
				XBB.lang = 'ru_cp1251';
				onload = function() {
				XBB.init();
			}
			</script><?php

			if(count($_POST)>0) {
				$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
				if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

				mysql_query("UPDATE `tb_refkonkurs` SET `desc`='$desc_kon',`ip`='$ip' WHERE `id`='$id' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
			}


			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo '���������� �������';
					}elseif($type_kon==2) {
						echo '���������� ������������ ������';
					}elseif($type_kon==3) {
						echo '����������� ���������';
					}elseif($type_kon==4) {
						echo '����������� (�� ������)';
					}elseif($type_kon==5) {
						echo '���������� ������������ ���������� ������������';
					}elseif($type_kon==6) {
						echo '����� ������������ ��� ��������';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������';
					}elseif($type_kon==2) {
						echo '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
					}elseif($type_kon==3) {
						echo '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������';
					}elseif($type_kon==4) {
						echo '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>';
						echo '-&nbsp;�������� ������ � �������� - 1 ����;<br>';
						echo '-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>';
						echo '-&nbsp;������ ������������� ������ - 2 �����;<br>';
						echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
						echo '-&nbsp;����������� ����� - '.$tests_reiting_konk.' �����;<br>';
						echo '-&nbsp;����������� �������� - 7 ������.';
					}elseif($type_kon==5) {
						echo '�������� ����� ������ ��, ��� ������ ���������� ������������ ������������, �� ����� ���������� ��������';
					}elseif($type_kon==6) {
						echo '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>���������� �������� ����</b></td>';
				echo '<td width="140px">'.$count_kon.'</td>';
				echo '<td align="left">���������� ��������� ����������� � ��������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>����� ��������</b></td>';
				echo '<td>'.@$d.'</td>';
				echo '<td align="left">���� ���������� �������� � ����</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>��� ������� ��������� ����������� ������������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ������� ������ ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo '��� ��������� 1-�� ������';
					}else{
						echo '��� ��������� ���� ������<br>';
						echo '� �������� ��������� ��������� ��� �������� �� 3-�� �������';
					}
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� �����</th></tr></thead>';
			for($i=1; $i<=$count_kon; $i++){
				echo '<tr>';
					echo '<td align="right"><b>���� �� '.$i.' �����:</b></td>';
					echo '<td>'.$row["p$i"].' ���.</td>';
					echo '<td align="left">�����, ������� ������� ������������, ���� ������ '.$i.' �����</td>';
				echo '</tr>';
			}

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>����� ���������:</b></td>';
				echo '<td colspan="2"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3">��� �� ������ ������� ����� ���������� �� ��������, ������� ������ ���� ��������, ���� ������� �������� ��������. ���� ���������� �������� � �������� ����� ����������� �������������.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;�����&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';
		}else{
			echo '<span class="msg-error">������� �� ������ ��� �� ����������� ���</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs2") {
		echo '<h3 class="sp">�������������� �������� ��� ���������</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if($row["status"]==1) {
				echo '<span class="msg-error">������ ������� ���������, ���� ������� �������.</span>';
			}else{
				$type_kon = $row["type_kon"];
				$count_kon = $row["count_kon"];
				$time_kon = $row["time_kon"];
				$desc_kon = $row["desc"];

				switch(substr($row["time_kon"], -1, 1)){
					case 1: $d=$row["time_kon"]." ����"; break;
					case 2: case 3: case 4: $d=$row["time_kon"]." ���"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." ����"; break;
				}

				?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
				<script type="text/javascript">
					XBB.textarea_id = 'desc_kon';
					XBB.area_width = '98%';
					XBB.area_height = '200px';
					XBB.state = 'plain';
					XBB.lang = 'ru_cp1251';
					onload = function() {
					XBB.init();
				}
				</script><?php

				if(count($_POST)>0) {
					for($i=1; $i<=20; $i++){$p[$i]=0;}
					$s = "OK";

					for($i=1; $i<=$count_kon; $i++){
						$p[$i] = (isset($_POST["p$i"])) ? round(abs(floatval(str_replace(",",".",trim($_POST["p$i"])))),2) : 0;
					}

					for($i=1; $i<=$count_kon; $i++){
						if(isset($_POST["p$i"])) {
							if($p[$i]==0){
								echo '<span class="msg-error">���� �� '.$i.' ����� �������.</span>'; $s="NO";
								break;
							}
							if($p[$i] < $p[$i+1]) {
								echo '<span class="msg-error">���� �� '.($i+1).' ����� ������, ��� �� '.$i.'.</span>'; $s="NO";
								break;
							}
						}
					}

					if($s=="OK") {
						$limit_kon = ( isset($_POST["limit_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon"])) ) ? intval(limpiar(trim($_POST["limit_kon"]))) : "0";
						$limit2_kon = ( isset($_POST["limit2_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit2_kon"])) ) ? intval(limpiar(trim($_POST["limit2_kon"]))) : "0";
						$limit_kon_sum = ( isset($_POST["$limit_kon_sum"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["$limit_kon_sum"])) ) ? intval(limpiar(trim($_POST["$limit_kon_sum"]))) : "0";
						$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
						if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

						mysql_query("UPDATE `tb_refkonkurs` SET `limit_kon`='$limit_kon',`limit2_kon`='$limit2_kon',`limit_kon_sum`='$limit_kon_sum',`desc`='$desc_kon',`p1`='$p[1]',`p2`='$p[2]',`p3`='$p[3]',`p4`='$p[4]',`p5`='$p[5]',`p6`='$p[6]',`p7`='$p[7]',`p8`='$p[8]',`p9`='$p[9]',`p10`='$p[10]',`p11`='$p[11]',`p12`='$p[12]',`p13`='$p[13]',`p14`='$p[14]',`p15`='$p[15]',`p16`='$p[16]',`p17`='$p[17]',`p18`='$p[18]',`p19`='$p[19]',`p20`='$p[20]',`ip`='$ip' WHERE `id`='$id' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

						echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
					}
				}


				echo '<form action="" method="POST" id="newform">';
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
				echo '<tr>';
					echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
					echo '<td colspan="2">';
						if($type_kon==1) {
							echo '���������� �������';
						}elseif($type_kon==2) {
							echo '���������� ������������ ������';
						}elseif($type_kon==3) {
							echo '����������� ���������';
						}elseif($type_kon==4) {
							echo '����������� (�� ������)';
						}elseif($type_kon==5) {
							echo '���������� ������������ ���������� ������������';
						}elseif($type_kon==6) {
							echo '����� ������������ ��� ��������';
						}else{
							echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>�������� ��������:</b></td>';
					echo '<td colspan="2">';
						if($type_kon==1) {
							echo '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������';
						}elseif($type_kon==2) {
							echo '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
						}elseif($type_kon==3) {
							echo '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������';
						}elseif($type_kon==4) {
							echo '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>';
							echo '-&nbsp;�������� ������ � �������� - 1 ����;<br>';
							echo '-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>';
							echo '-&nbsp;������ ������������� ������ - 2 �����;<br>';
							echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
							echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
							echo '-&nbsp;����������� ����� - '.$tests_reiting_konk.' �����;<br>';
							echo '-&nbsp;����������� �������� - 7 ������.';
						}elseif($type_kon==5) {
							echo '�������� ����� ������ ��, ��� ������ ���������� ������������ ������������, �� ����� ���������� ��������';
						}elseif($type_kon==6) {
							echo '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
						}else{
							echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>���������� �������� ����</b></td>';
					echo '<td width="115px">'.$count_kon.'</td>';
					echo '<td align="left">���������� ��������� ����������� � ��������</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>����� ��������</b></td>';
					echo '<td>'.@$d.'</td>';
					echo '<td align="left">���� ���������� �������� � ����</td>';
				echo '</tr>';

				echo '<tr>';
					if($row["type_kon"]==1) {
						echo '<td align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==2) {
						echo '<td align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==3) {
						echo '<td align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==4) {
						echo '<td align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}elseif($row["type_kon"]==5) {
						echo '<td align="right"><b>��� ������� ��������� ���������� ������������ �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$row["limit_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					}
					
				echo '</tr>';
				
				if($row["type_kon"]==3) {
					echo '<tr>';
						echo '<td align="right"><b>��� ������� ������� ������ ��������� ������ �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$row["limit2_kon"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					echo '</tr>';
				}
				
				if($row["type_kon"]==6) {
					echo '<tr>';
						echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
						echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$row["limit_kon_sum"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
					echo '</tr>';
				}

				echo '<tr>';
					echo '<td align="right"><b>������� ��������:</b></td>';
					echo '<td colspan="2">';
						echo '<input type="radio" name="glubina" value="0" checked="checked"> - ��� ��������� 1-�� ������<br>';
						echo '<input type="radio" name="glubina" value="1"> - ��� ��������� ���� ������<br>';
						echo '� �������� ��������� ��������� ��� �������� �� 3-�� �������';
					echo '</td>';
				echo '</tr>';

				echo '<thead><tr align="center"><th align="center" colspan="3">�������� �����</th></tr></thead>';
				for($i=1; $i<=$count_kon; $i++){
					echo '<tr>';
						echo '<td align="right"><b>���� �� '.$i.' �����:</b></td>';
						echo '<td><input type="text" name="p'.$i.'" value="'.$row["p$i"].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"> ���.</td>';
						echo '<td align="left">�����, ������� ������� ������������, ���� ������ '.$i.' �����</td>';
					echo '</tr>';
				}

				echo '<thead><tr align="center"><th align="center" colspan="3">�������� ��������</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right"><b>����� ���������:</b></td>';
					echo '<td colspan="2"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3">��� �� ������ ������� ����� ���������� �� ��������, ������� ������ ���� ��������, ���� ������� �������� ��������. ���� ���������� �������� � �������� ����� ����������� �������������.</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;�����&nbsp;&nbsp;" /></td>';
				echo '</tr>';
				echo '</table>';
				echo '</form>';
			}
		}else{
			echo '<span class="msg-error">������� �� ������ ��� �� ����������� ���</span>';
		}

		include('footer.php');
		exit();

	}if(isset($_GET["op"]) && limpiar($_GET["op"])=="editkonkurs") {
		echo '<h3 class="sp">�������������� �������� ��� ���������</h3>';

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if($row["status"]==1) {
				echo '<span class="msg-error">������ ������� ���������, ���� ������� �������.</span>';
			}else{

				if(count($_POST)>0) {
					$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
					$type_kon = ( isset($_POST["type_kon"]) && preg_match("|^[\d]{1}$|", trim($_POST["type_kon"])) ) ? intval(limpiar(trim($_POST["type_kon"]))) : false;
					$count_kon = ( isset($_POST["count_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["count_kon"])) ) ? intval(limpiar(trim($_POST["count_kon"]))) : false;
					$time_kon = ( isset($_POST["time_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["time_kon"])) ) ? intval(limpiar(trim($_POST["time_kon"]))) : false;

					if($type_kon<1 && $type_kon>5) {
						echo '<span class="msg-error">�� ������ ��� ��������.</span>';
					}elseif($count_kon<3) {
						echo '<span class="msg-error">���������� �������� ���� �� ����� ���� ������ 3.</span>';
					}elseif($count_kon>20) {
						echo '<span class="msg-error">���������� �������� ���� �� ����� ���� ������ 20.</span>';
					}elseif($time_kon<3) {
						echo '<span class="msg-error">����� ���������� �������� �� ����� ���� ����� 3 ����.</span>';
					}elseif($time_kon>60) {
						echo '<span class="msg-error">����� ���������� �������� �� ����� ���� ������ 60 ����.</span>';
					}else{
						mysql_query("UPDATE `tb_refkonkurs` SET `type_kon`='$type_kon', `count_kon`='$count_kon', `time_kon`='$time_kon' WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

						echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs2&id='.$id.'");</script>';
						echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs2&id='.$id.'"></noscript>';
					}
				}

				echo '<form action="" method="POST" id="newform">';
				echo '<input type="hidden" name="id" value="'.$id.'">';
				echo '<table class="tables">';
				echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
				echo '<tr>';
					echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
					echo '<td colspan="2">';
						echo '<select name="type_kon" onChange="viewinfo(this.value);" onKeyUp="viewinfo(this.value);">';
							echo '<option value="0" disabled="disabled" style="color:#000; font-weight:bold;" '.('0' == $type_kon ? 'selected="selected"' : '').'>�������� �������</option>';
							echo '<option value="1" '.('1' == $row["type_kon"] ? 'selected="selected"' : '').'>���������� �������</option>';
							echo '<option value="2" '.('2' == $row["type_kon"] ? 'selected="selected"' : '').'>���������� ������������ ������</option>';
							echo '<option value="3" '.('3' == $row["type_kon"] ? 'selected="selected"' : '').'>����������� ���������</option>';
							echo '<option value="4" '.('4' == $row["type_kon"] ? 'selected="selected"' : '').'>����������� (�� ������)</option>';
							echo '<option value="5" '.('5' == $row["type_kon"] ? 'selected="selected"' : '').'>���������� ������������ ���������� ������������)</option>';
							echo '<option value="6" '.('6' == $row["type_kon"] ? 'selected="selected"' : '').'>����� ������������ ��� ��������)</option>';
						echo '</select>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>�������� ��������:</b></td>';
					echo '<td id="konkurs_info" colspan="2"></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>���������� �������� ����</b></td>';
					echo '<td width="160px"><input type="text" name="count_kon" value="'.$row["count_kon"].'" class="ok12" maxlength="2" style="width:50px; text-align:center;"> (�� 3 �� 20)</td>';
					echo '<td align="left">���������� ��������� ����������� � ��������</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right"><b>����� ��������</b></td>';
					echo '<td><input type="text" name="time_kon" value="'.$row["time_kon"].'" class="ok12" maxlength="2" style="width:50px; text-align:center;"> ���� (�� 3 �� 60)</td>';
					echo '<td align="left">���� ���������� �������� � ����</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;�����&nbsp;&nbsp;" /></td>';
				echo '</tr>';
				echo '</table>';
				echo '</form>';

				?><script language="JavaScript">
					var konkursinfo = new Array();
					konkursinfo[0] = ''
					konkursinfo[1] = '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������'
					konkursinfo[2] = '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������'
					konkursinfo[3] = '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������'
					konkursinfo[4] = '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>-&nbsp;�������� ������ � �������� - 1 ����;<br>-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>-&nbsp;������ ������������� ������ - 2 �����;<br>-&nbsp;����������� ����� - 3 �����;<br>-&nbsp;���������� ������������� ������� - 3 �����;<br>-&nbsp;���������� ������������ ������� - 5 ������;<br>-&nbsp;����������� �������� - 7 ������.'
					konkursinfo[5] = '�������� ����� ������ ��, ��� ������ ��������� ������������, �� ����� ���������� ��������'
					konkursinfo[6] = '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.'
						
					function gebi(id){
						return document.getElementById(id)
					}
					function viewinfo(i){
						if (konkursinfo[i]){
							gebi("konkurs_info").innerHTML = konkursinfo[i];
						}
					}
					viewinfo(<?=$row["type_kon"];?>);
				</script><?php
			}
		}else{
			echo '<span class="msg-error">������� �� ������ ��� �� ����������� ���</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="delkonkurs") {

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		mysql_query("DELETE FROM `tb_refkonkurs` WHERE `id`='$id' AND `status`!='1' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="addmoneykonkurs") {

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			switch(substr($row["time_kon"], -1, 1)){
				case 1: $d=$row["time_kon"]." ����"; break;
				case 2: case 3: case 4: $d=$row["time_kon"]." ���"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d=$row["time_kon"]." ����"; break;
			}

			$p = 0;
			for($i=1; $i<=$row["count_kon"]; $i++) {
				$p = $p + $row["p$i"];
			}

			function day_w($m){
				$m = str_replace("Monday","�����������",$m);
				$m = str_replace("Tuesday","�������",$m);
				$m = str_replace("Wednesday","�����",$m);
				$m = str_replace("Thursday","�������",$m);
				$m = str_replace("Friday","�������",$m);
				$m = str_replace("Saturday","�������",$m);
				$m = str_replace("Sunday","�����������",$m);
				return $m;
			}

			$desc_kon = new bbcode($row["desc"]);
			$desc_kon = $desc_kon->get_html();
			$cena_kon = round(($p*1.1),2);

			$sql_b = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$username' AND `type_kon`='".$row["type_kon"]."' ORDER BY `id` DESC");
			if(mysql_num_rows($sql_b)>0) {
				echo '<span class="msg-error">��������!<br>� ��� ��� ���� �������� �������, ��������� ����� ������� ����� ������ ����� ����, ��� ������ ����� ��������.</span>';
			}else{
				if(count($_POST)>0) {
					$dstart = ( isset($_POST["dstart"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["dstart"])) ) ? intval(limpiar(trim($_POST["dstart"]))) : false;

					if($dstart!=false) {
						if($dstart<time()) {
							echo '<span class="msg-error">�������� ���� ������ ��������.</span>';
						}else{
							if($money_rb<$cena_kon) {
								echo '<span class="msg-error">�� ������� ����� �� ������� �������� ��� ������� ��������. [<a href="/money_add.php" target="_blank">��������� ������ �������� �� '.p_ceil(($cena_kon-$money_rb),2).' ���.</a>]</span>';
							}else{
								mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_kon' WHERE `username`='$username'") or die(mysql_error());
								mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username','".DATE("d.m.Y�. H:i")."','$cena_kon','������ �������� ��� ��������� �$id','�����','rashod')") or die(mysql_error());
								mysql_query("UPDATE `tb_refkonkurs` SET `status`='1', `date_s`='".$dstart."', `date_e`='".($dstart+$row["time_kon"]*24*60*60-1)."' WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

								echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'");</script>';
								echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'"></noscript>';
							}
						}
					}else{
						echo '<span class="msg-error">�������� ���� ������ ��������.</span>';
					}
				}
			}

			echo '<h3 class="sp">���������� ������� � ������ �������� ��� ���������</h3>';
			echo '<form action="" method="POST" id="newform">';
			echo '<input type="hidden" name="id" value="'.$id.'">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '���������� �������';
					}elseif($row["type_kon"]==2) {
						echo '���������� ������������ ������';
					}elseif($row["type_kon"]==3) {
						echo '����������� ���������';
					}elseif($row["type_kon"]==4) {
						echo '����������� (�� ������)';
					}elseif($row["type_kon"]==5) {
						echo '���������� ������������ ���������� ������������';
					}elseif($row["type_kon"]==6) {
						echo '����� ������������ ��� ��������';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������';
					}elseif($row["type_kon"]==2) {
						echo '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==3) {
						echo '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������';
					}elseif($row["type_kon"]==4) {
						echo '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>';
						echo '-&nbsp;�������� ������ � �������� - 1 ����;<br>';
						echo '-&nbsp;������ ������������� ������ - 2 �����;<br>';
						echo '-&nbsp;����������� ����� - '.$tests_reiting_konk.' �����;<br>';
						echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
						
						echo '-&nbsp;����������� �������� - 7 ������.';
					}elseif($row["type_kon"]==5) {
						echo '�������� ����� ������ ��, ��� ������ ���������� ������������ ������������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==6) {
						echo '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>���������� �������� ����</b></td>';
				echo '<td>'.$row["count_kon"].'</td>';
				echo '<td align="left">���������� ��������� ����������� � ��������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>����� ��������</b></td>';
				echo '<td>'.@$d.'</td>';
				echo '<td align="left">���� ���������� �������� � ����</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�����:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'���.</b>, ';
					}
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td align="right"><b>��� ������� ��������� ���������� ������������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==3) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ������� ������ ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit2_kon"].'</b></td>';
				echo '</tr>';
			}
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo '��� ��������� 1-�� ������';
					}else{
						echo '��� ��������� ���� ������<br>';
						echo '� �������� ��������� ��������� ��� �������� �� 3-�� �������';
					}
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">������ ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ����:</b></td>';
				echo '<td colspan="2">'.$p.' ���.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� �������:</b></td>';
				echo '<td colspan="2">10%</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>��������� ��������:</b></td>';
				if($money_rb<$cena_kon) {
					echo '<td><b>'.$cena_kon.' ���.</b> [<a href="/money_add.php" target="_blank" style="color: #FF0000;">��������� ������ �������� �� '.p_ceil(($cena_kon-$money_rb),2).' ���.</a>]</td>';
				}else{
					echo '<td><b>'.$cena_kon.' ���.</b></td>';
				}
				echo '<td align="left">��������� ���������� �������� � ��������� �������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>������ ��������:</b></td>';
				echo '<td>';
					echo '<select name="dstart">';
						$d1 = (strtotime(DATE("d.m.Y", time()))+24*60*60);
						$d2 = (strtotime(DATE("d.m.Y", time()))+10*24*60*60);
						$sh = (24*60*60);
						for($i=$d1; $i<=$d2; $i=$i+$sh) {
							echo '<option value="'.$i.'">'.day_w(DATE("d.m.Y l", $i)).'</option>';
						}
					echo '</select>';
				echo '</td>';
				echo '<td align="left">������� ���� ������ ��������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="��������� � ���������" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form><br>';
			echo '<p><b>��������!</b> ����������� ��������� ��� ������ ����� �������� ��������, �������� �� �� ����� �������� ����� ����������!</p>';
		}else{
			echo '<span class="msg-error">������� �� ������ ��� �� ����������� ���</span>';
		}

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="add_konkurs") {

		for($i=1; $i<=20; $i++){$p[$i]=0;}
		$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
		$type_kon = ( isset($_POST["type_kon"]) && preg_match("|^[\d]{1}$|", trim($_POST["type_kon"])) ) ? intval(limpiar(trim($_POST["type_kon"]))) : false;
		$count_kon = ( isset($_POST["count_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["count_kon"])) ) ? intval(limpiar(trim($_POST["count_kon"]))) : false;
		$time_kon = ( isset($_POST["time_kon"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["time_kon"])) ) ? intval(limpiar(trim($_POST["time_kon"]))) : false;
		$savekon = isset($_POST["savekon"]) ? limpiar(trim($_POST["savekon"])) : false;
		$limit_kon = ( isset($_POST["limit_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon"])) ) ? intval(limpiar(trim($_POST["limit_kon"]))) : "0";
		$limit2_kon = ( isset($_POST["limit2_kon"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit2_kon"])) ) ? intval(limpiar(trim($_POST["limit2_kon"]))) : "0";
		$limit_kon_sum = ( isset($_POST["limit_kon_sum"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["limit_kon_sum"])) ) ? intval(limpiar(trim($_POST["limit_kon_sum"]))) : "0";
		$desc_kon = isset($_POST["desc_kon"]) ? limitatexto(trim($_POST["desc_kon"]),1000) : false;
		$glubina = ( isset($_POST["glubina"]) && preg_match("|^[\d]{1}$|", trim($_POST["glubina"])) ) ? intval(limpiar(trim($_POST["glubina"]))) : "0";
		if (get_magic_quotes_gpc()) { $desc_kon = stripslashes($desc_kon); }

		for($i=1; $i<=$count_kon; $i++){
			$p[$i] = (isset($_POST["p$i"])) ? round(abs(floatval(str_replace(",",".",trim($_POST["p$i"])))),2) : 0;
		}

		if($time_kon >0) {
			switch(substr($time_kon, -1, 1)){
				case 1: $d="����"; break;
				case 2: case 3: case 4: $d="���"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: $d="����"; break;
			}
		}

		if(count($_POST)>0 && $type_kon>0 && $type_kon<=6 && $count_kon>=3 && $count_kon<=20 && $time_kon>=3 && $time_kon<=60) {
			$s = "OK";
			for($i=1; $i<=$count_kon; $i++){
				if(isset($_POST["p$i"])) {
					if($p[$i]==0){
						echo '<span class="msg-error">���� �� '.$i.' ����� �������.</span>'; $s="NO";
						break;
					}
					if($p[$i] < $p[$i+1]) {
						echo '<span class="msg-error">���� �� '.($i+1).' ����� ������, ��� �� '.$i.'.</span>'; $s="NO";
						break;
					}
				}
			}

			if($savekon=="savekonkurs2" && $s=="OK") {
				mysql_query("INSERT INTO `tb_refkonkurs` (`status`,`username`,`type_kon`,`glubina`,`date_s`,`date_e`,`count_kon`,`time_kon`,`limit_kon`,`limit2_kon`,`limit_kon_sum`,`desc`,`p1`,`p2`,`p3`,`p4`,`p5`,`p6`,`p7`,`p8`,`p9`,`p10`,`p11`,`p12`,`p13`,`p14`,`p15`,`p16`,`p17`,`p18`,`p19`,`p20`,`ip`) 
				VALUES('0','$username','$type_kon','$glubina','0','0','$count_kon','$time_kon','$limit_kon','$limit2_kon','$limit_kon_sum','$desc_kon','$p[1]','$p[2]','$p[3]','$p[4]','$p[5]','$p[6]','$p[7]','$p[8]','$p[9]','$p[10]','$p[11]','$p[12]','$p[13]','$p[14]','$p[15]','$p[16]','$p[17]','$p[18]','$p[19]','$p[20]','$ip')") or die(mysql_error());

				$sql = mysql_query("SELECT `id` FROM `tb_refkonkurs` WHERE `username`='$username' ORDER BY `id` DESC");
				if(mysql_num_rows($sql)>0) {
					$id_kon = mysql_result($sql,0,0);

					echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$id_kon.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$id_kon.'"></noscript>';
				}
			}

			?><script type="text/javascript" src="bbcode/xbb.js.php"></script>
			<script type="text/javascript">
				XBB.textarea_id = 'desc_kon';
				XBB.area_width = '98%';
				XBB.area_height = '200px';
				XBB.state = 'plain';
				XBB.lang = 'ru_cp1251';
				onload = function() {
				XBB.init();
			}
			</script><?php

			echo '<h3 class="sp">�������� ������ �������� ��� ���������</h3>';
			echo '<form action="" method="POST" id="newform">';
				echo '<input type="hidden" name="savekon" value="savekonkurs2">';
				echo '<input type="hidden" name="type_kon" value="'.$type_kon.'">';
				echo '<input type="hidden" name="count_kon" value="'.$count_kon.'">';
				echo '<input type="hidden" name="time_kon" value="'.$time_kon.'">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo '���������� �������';
					}elseif($type_kon==2) {
						echo '���������� ������������ ������';
					}elseif($type_kon==3) {
						echo '����������� ���������';
					}elseif($type_kon==4) {
						echo '����������� (�� ������)';
					}elseif($type_kon==5) {
						echo '���������� ������������ ���������� ������������';
					}elseif($type_kon==6) {
						echo '����� ������������ ��� ��������';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>�������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($type_kon==1) {
						echo '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������';
					}elseif($type_kon==2) {
						echo '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
					}elseif($type_kon==3) {
						echo '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������';
					}elseif($type_kon==4) {
						echo '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>';
						echo '-&nbsp;�������� ������ � �������� - 1 ����;<br>';
						echo '-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>';
						echo '-&nbsp;������ ������������� ������ - 2 �����;<br>';
						echo '-&nbsp;����������� ����� - '.$tests_reiting_konk.' �����;<br>';
						echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
						
						echo '-&nbsp;����������� �������� - 7 ������.';
					}elseif($type_kon==5) {
						echo '�������� ����� ������ ��, ��� ������ ���������� ������������ ������������, �� ����� ���������� ��������';
					}elseif($type_kon==6) {
						echo '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>���������� �������� ����</b></td>';
				echo '<td width="115px">'.$count_kon.'</td>';
				echo '<td align="left">���������� ��������� ����������� � ��������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>����� ��������</b></td>';
				echo '<td>'.$time_kon.' '.@$d.'</td>';
				echo '<td align="left">���� ���������� �������� � ����</td>';
			echo '</tr>';

			echo '<tr>';
				if($type_kon==1) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==2) {
					echo '<td align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==3) {
					echo '<td align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==4) {
					echo '<td align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}elseif($type_kon==5) {
					echo '<td align="right"><b>��� ������� ��������� ����������� ������������ �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon" value="'.$limit_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				}
			
			echo '</tr>';
			
			if($type_kon==3) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ������� ������ ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit2_kon" value="'.$limit2_kon.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				echo '</tr>';
			}
			
			if($type_kon==6) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
					echo '<td colspan="2"><input type="text" name="limit_kon_sum" value="'.$limit_kon_sum.'" class="ok12" maxlength="10" style="width:80px; text-align: center;"></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td align="right"><b>������� ��������:</b></td>';
				echo '<td colspan="2">';
					echo '<input type="radio" name="glubina" value="0" checked="checked"> - ��� ��������� 1-�� ������<br>';
					echo '<input type="radio" name="glubina" value="1"> - ��� ��������� ���� ������<br>';
					echo '� �������� ��������� ��������� ��� �������� �� 3-�� �������';
				echo '</td>';
			echo '</tr>';

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� �����</th></tr></thead>';
			for($i=1; $i<=$count_kon; $i++){
				echo '<tr>';
					echo '<td align="right"><b>���� �� '.$i.' �����:</b></td>';
					echo '<td><input type="text" name="p'.$i.'" value="'.$p[$i].'" class="ok12" maxlength="10" style="width:80px; text-align: center;"> ���.</td>';
					echo '<td align="left">�����, ������� ������� ������������, ���� ������ '.$i.' �����</td>';
				echo '</tr>';
			}

			echo '<thead><tr align="center"><th align="center" colspan="3">�������� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td align="right"><b>����� ���������:</b></td>';
				echo '<td colspan="2"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3"><textarea style="width:99%;height:200px;" name="desc_kon" id="desc_kon">'.$desc_kon.'</textarea></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td colspan="3">��� �� ������ ������� ����� ���������� �� ��������, ������� ������ ���� ��������, ���� ������� �������� ��������. ���� ���������� �������� � �������� ����� ����������� �������������.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;�����&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';
		}else{
			if(count($_POST)>0) {
				if($type_kon<1 && $type_kon>6) {
					echo '<span class="msg-error">�� ������ ��� ��������.</span>';
				}elseif($count_kon<3) {
					echo '<span class="msg-error">���������� �������� ���� �� ����� ���� ������ 3.</span>';
					$count_kon = 10;
				}elseif($count_kon>20) {
					echo '<span class="msg-error">���������� �������� ���� �� ����� ���� ������ 20.</span>';
					$count_kon = 10;
				}elseif($time_kon<3) {
					echo '<span class="msg-error">����� ���������� �������� �� ����� ���� ����� 3 ����.</span>';
					$time_kon = 7;
				}elseif($time_kon>60) {
					echo '<span class="msg-error">����� ���������� �������� �� ����� ���� ������ 60 ����.</span>';
					$time_kon = 7;
				}
			}

			if($count_kon<3 | $count_kon>20) {
				$count_kon = 10;
			}
			if($time_kon<3 | $time_kon>60) {
				$time_kon = 7;
			}

			echo '<h3 class="sp">�������� ������ �������� ��� ���������</h3>';
			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					echo '<select name="type_kon" onChange="viewinfo(this.value);" onKeyUp="viewinfo(this.value);" style="">';
						echo '<option value="0" disabled="disabled" style="color:#000; font-weight:bold;" '.('0' == $type_kon ? 'selected="selected"' : '').'>�������� �������</option>';
						echo '<option value="1" '.('1' == $type_kon ? 'selected="selected"' : '').'>���������� �������</option>';
						echo '<option value="2" '.('2' == $type_kon ? 'selected="selected"' : '').'>���������� ������������ ������</option>';
						echo '<option value="3" '.('3' == $type_kon ? 'selected="selected"' : '').'>����������� ���������</option>';
						echo '<option value="4" '.('4' == $type_kon ? 'selected="selected"' : '').'>����������� (�� ������)</option>';
						echo '<option value="5" '.('5' == $type_kon ? 'selected="selected"' : '').'>���������� ������������ ���������� ������������</option>';
						echo '<option value="6" '.('6' == $type_kon ? 'selected="selected"' : '').'>����� ������������ ��� ��������</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>�������� ��������:</b></td>';
				echo '<td id="konkurs_info" colspan="2">&nbsp;</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>���������� �������� ����</b></td>';
				echo '<td width="160px"><input type="text" name="count_kon" value="'.$count_kon.'" class="ok12" style="width:50px; text-align:center;" maxlength="2"> ���� (�� 3 �� 20)</td>';
				echo '<td align="left">���������� ��������� ����������� � ��������</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="200px" align="right"><b>����� ��������</b></td>';
				echo '<td><input type="text" name="time_kon" value="'.$time_kon.'" class="ok12" style="width:50px; text-align:center;" maxlength="2"> ���� (�� 3 �� 60)</td>';
				echo '<td align="left">���� ���������� �������� � ����</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;�����&nbsp;&nbsp;" /></td>';
			echo '</tr>';
			echo '</table>';
			echo '</form>';

			?><script language="JavaScript">
				var konkursinfo = new Array();
				konkursinfo[0] = ''
				konkursinfo[1] = '�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������'
				konkursinfo[2] = '�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������'
				konkursinfo[3] = '�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������'
				konkursinfo[4] = '�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������.<br><br>����� �������������� �� �����:<br>-&nbsp;�������� ������ � �������� - 1 ����;<br>-&nbsp;�������� ������������ � �������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> - 1 ����;<br>-&nbsp;������ ������������� ������ - 2 �����;<br>-&nbsp;���������� ������������� ������� - 3 �����;<br>-&nbsp;���������� ������������ ������� - 5 ������;<br>-&nbsp;����������� ����� - <?=$tests_reiting_konk;?> �����;<br>-&nbsp;����������� �������� - 7 ������.'
				konkursinfo[5] = '�������� ����� ������ ��, ��� ������ ��������� ������������ ������������, �� ����� ���������� ��������'
				konkursinfo[6] = '�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.'

				function gebi(id){
					return document.getElementById(id)
				}
				function viewinfo(i){
					if (konkursinfo[i]){
						gebi("konkurs_info").innerHTML = konkursinfo[i];
					}
				}
			</script><?php
		}

	}else{
	    echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">�������� ��� ���������</span>';
		        echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                        echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f3e7d8" align="left"> ';
		//echo '<h3 class="sp" style="margin-bottom:3px;">�� ������ ������ �������� ��� �������� ��������� ��������:</h3>';
		echo ' - <b>���������� �������</b> (�������� ����� ������ ��, ��� �������� ���������� ���������� ������� �� ����� ���������� ��������)<br>';
		echo ' - <b>���������� ������������ ������</b> (�������� ����� ������ ��, ��� ������ �������� ������������ ������, �� ����� ���������� ��������)<br>';
		echo ' - <b>����������� ���������</b> (�������� ����� ������ ��, ��� ��������� ���������� ���������� ��������� �� ����� ���������� ��������)<br>';
		echo ' - <b>�����������, �� ������</b> (�������� ����� ������ ��, ��� ������� ���������� ���������� ������ �� ����� ���������� ��������)<br>';
		echo ' - <b>���������� ������������ ���������� ������������ <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b> (������� ����� ���������: ��� ������ ��������� ������������ <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, �� ����� ���������� ��������)<br><br>';
		echo ' - <b>����� ������������ ��� ��������</b> (�������� ����� ������ ��, ��� �������� �������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������)<br>';

		echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=add_konkurs"><img src="img/add.png" border="0" alt="" align="absmiddle" title="������� ����� ������� ��� ���������" /></a><a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=add_konkurs" title="������� ����� ������� ��� ���������">������� ����� ������� ��� ���������</a><br><br>';
		echo '</div>';
                        echo '</div>';

		echo '<form action="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'" method="POST" id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center" rowspan="2">�</th>';
			echo '<th align="center" rowspan="2">��� ��������</th>';
			echo '<th align="center">������</th>';
			echo '<th align="center">���������</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `username`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';

					switch($row["count_kon"]){
						case 1: $m="�����"; break;
						case 2: case 3: case 4: $m="�����"; break;
						case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19:  case 20:$m="����"; break;
					}

					$p = 0;
					for($i=1; $i<=$row["count_kon"]; $i++) {
						$p = $p + $row["p$i"];
					}

					echo '<td align="center">'.$row["id"].'</td>';

					if($row["type_kon"]=="1") {
						$type_kon="<b>���������� �������</b>";
					}elseif($row["type_kon"]=="2") {
						$type_kon="<b>���������� ������������ ������</b>";
					}elseif($row["type_kon"]=="3") {
						$type_kon="<b>����������� ���������</b>";
					}elseif($row["type_kon"]=="4") {
						$type_kon="<b>����������� (�� ������)</b>";
					}elseif($row["type_kon"]=="5") {
						$type_kon="<b>���������� ������������ ���������� ������������</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>����� ������������ ��� ��������</b>";
					}else{
						$type_kon='<b style="color:#FF0000;">��� �������� �� ���������!</b>';
					}

					if($row["status"]==0) {
						$status='<span style="color: #808080;">�� �������, ��������� ������ ��������</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs&id='.$row["id"].'">�������������</a>]';
						$del = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=delkonkurs&id='.$row["id"].'" onclick=\'if(!confirm("�� �������, ��� ������ ������� �������?"))return false;\'>�������</a>]';
						$addmoney = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=addmoneykonkurs&id='.$row["id"].'">��������� ������ � ��������� �������</a>]';
						$statistik = "";
					}elseif($row["status"]==1) {
						$status='<span style="color: #009200;">�������, ���� �������</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs3&id='.$row["id"].'">�������������</a>]';
						$del = "";
						$addmoney = "";
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'">����������</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">������� ��������, ����� ���������, ����� ���������</span>';
						$edit = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=editkonkurs&id='.$row["id"].'">�������������</a>]';
						$del = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=delkonkurs&id='.$row["id"].'" onclick=\'if(!confirm("�� �������, ��� ������ ������� �������?"))return false;\'>�������</a>]';
						$addmoney = "";
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'">����������</a>]';
					}else{
						$status="";
						$edit = "";
						$del = "";
						$addmoney = "";
						$statistik = "";
					}

					echo '<td>'.$type_kon.'<br>['.$row["count_kon"].' '.$m.', �������� ���� <b>'.$p.' ���.</b>]<br>'.$status.'<br>'.$edit.' '.$del.' '.$addmoney.' '.$statistik.'</td>';

					if($row["date_s"]==0) {
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}else{
						echo '<td align="center">'.DATE("d.m.Y", $row["date_s"]).'<br>'.DATE("H:i", $row["date_s"]).'</td>';
						echo '<td align="center">'.DATE("d.m.Y", $row["date_e"]).'<br>'.DATE("H:i:s", $row["date_e"]).'</td>';
					}
				echo '</tr>';
			}
		}else{
			echo '<tr>';
				echo '<td colspan="4" align="center"><b>�������� �� �������!</b></td>';
			echo '</tr>';
		}
		echo '</table>';
	}
}


?>