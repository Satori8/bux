<?php

if(!isset($_GET["page"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}elseif(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">������! ��� ������� � ���� �������� ���������� ��������������!</span>';
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

	require_once ("bbcode/bbcode.lib.php");

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

			echo '<h3 class="sp">������� #'.$row["id"].' �� �������� '.$row["username"].'</h3>';
			echo '<table class="tables">';
			echo '<thead><tr><th align="center" colspan="3">��� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>��� ��������:</b></td>';
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
						echo '���������� ������������ ����������';
					}elseif($row["type_kon"]==6) {
						echo '����� ������������ ��� ��������';
					}else{
						echo '<b style="color: #FF0000;">������! �� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>�������� ��������:</b></td>';
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
						echo '-&nbsp;����������� ����� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������� ������� - 3 �����;<br>';
						echo '-&nbsp;���������� ������������ ������� - 5 ������;<br>';
						echo '-&nbsp;����������� �������� - 7 ������.';
					}elseif($row["type_kon"]==5) {
						echo '�������� ����� ������ ��, ��� ������ ��������� ������������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==6) {
						echo '�������� ����� ������ ��, ��� �������� �������, ������, �����, ������� ���������� ��� ������ �������� ���������� ����� ����� �� ����� ���������� ��������.';
					}else{
						echo '<b style="color: #FF0000;">�� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>���������� �������� ����</b></td>';
				echo '<td colspan="2">'.$row["count_kon"].'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>�����:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'���.</b>, ';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>�������� ����:</b></td>';
				echo '<td colspan="2">'.$p.' ���.</td>';
			echo '</tr>';

			echo '<tr>';
				if($row["type_kon"]==1) {
					echo '<td width="30%" align="right"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==2) {
					echo '<td width="30%" align="right"><b>��� ������� ��������� ��������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==3) {
					echo '<td width="30%" align="right"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==4) {
					echo '<td width="30%" align="right"><b>��� ������� ��������� ������� ������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}elseif($row["type_kon"]==5) {
					echo '<td width="30%" align="right"><b>��� ������� ��������� ���������� ������������ �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon"].'</b></td>';
				}
				
			echo '</tr>';
			
			if($row["type_kon"]==6) {
				echo '<tr>';
					echo '<td align="right"><b>��� ������� ��������� ���������� �� �����:</b></td>';
					echo '<td colspan="2"><b>'.$row["limit_kon_sum"].'</b></td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["glubina"]==0) {
						echo '��� ��������� 1-�� ������';
					}else{
						echo '��� ��������� ���� ������<br>';
						echo '� �������� ��������� ��������� �������� �� 3-�� �������';
					}
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td width="30%" align="right" height="30px"><b>����� ���������� ��������:</b></td>';
				echo '<td colspan="2">c '.DATE("d.m.Y�. H:i:s", $row["date_s"]).' ��  '.DATE("d.m.Y�. H:i:s", $row["date_e"]).' ('.@$d.')</td>';
			echo '</tr>';

			echo '<tr>';
				if(time() < $row["date_s"]) {
					echo '<td width="30%" align="right" height="30px"><b>�� ������ ��������:</b></td>';
				}else{
					echo '<td width="30%" align="right" height="30px"><b>�� ��������� ��������:</b></td>';
				}

				if(time() > $row["date_e"]) {
					echo '<td colspan="2">������� ��������</td>';
				}elseif(time() < $row["date_s"]) {
					echo '<td colspan="2">'.date_ost($row["date_s"]-time()).'</td>';
				}else{
					echo '<td colspan="2">'.date_ost($row["date_e"]-time()).'</td>';
				}
			echo '</tr>';

			echo '<thead><tr><th align="center" colspan="3">�������� ��������</th></tr></thead>';
			echo '<tr>';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';
			echo '</table><br>';

			$sql_s = mysql_query("SELECT `username`,`referer`, SUM(`kolvo`) as `sum_kolvo` FROM `tb_refkonkurs_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_kon"]."' GROUP BY `username` ORDER BY SUM(`kolvo`) DESC LIMIT 100");
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
			echo '<span class="msg-error">������!<br>������� �� ������.</span>';
		}
	}else{
		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">������� �� �������� 1-��� ������ <b>'.$my_referer_1.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">�</th>';
			echo '<th align="center">��� ��������</th>';
			echo '<th align="center">������</th>';
			echo '<th align="center">���������</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_1' ORDER BY `id` DESC");
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
						$type_kon="<b>���������� ������������ ���������� </b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>����� ������������ ��� �������� </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">��� �������� �� ���������!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">�������, ���� �������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">������� ��������, ����� ���������, ����� ���������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', �������� ���� <b>'.$p.' ���.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

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

		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">������� �� �������� 2-��� ������ <b>'.$my_referer_2.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">�</th>';
			echo '<th align="center">��� ��������</th>';
			echo '<th align="center">������</th>';
			echo '<th align="center">���������</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_2' ORDER BY `id` DESC");
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
						$type_kon="<b>���������� ������������ ����������</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>����� ������������ ��� �������� </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">��� �������� �� ���������!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">�������, ���� �������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">������� ��������, ����� ���������, ����� ���������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', �������� ���� <b>'.$p.' ���.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

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

		echo '<br>';
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:normal;">������� �� �������� 3-��� ������ <b>'.$my_referer_3.'</b></h5>';
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th align="center">�</th>';
			echo '<th align="center">��� ��������</th>';
			echo '<th align="center">������</th>';
			echo '<th align="center">���������</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`>'0' AND `username`='$my_referer_3' ORDER BY `id` DESC");
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
						$type_kon="<b>���������� ������������ ����������</b>";
					}elseif($row["type_kon"]=="6") {
						$type_kon="<b>����� ������������ ��� �������� </b>";
					}else{
						$type_kon='<b style="color:#FF0000;">��� �������� �� ���������!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">�������, ���� �������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">������� ��������, ����� ���������, ����� ���������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}else{
						$status="";
						$statistik = "";
					}

					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', �������� ���� <b>'.$p.' ���.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

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