<?php
$pagetitle = "�������� ��� ���������";
include("header.php");

if(!isset($_GET["referer"])) {
	echo '<script type="text/javascript">location.replace("/");</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
}else{
	require_once ("bbcode/bbcode.lib.php");

	$referer = (isset($_GET["referer"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_GET["referer"]))) ? htmlspecialchars(stripslashes(trim($_GET["referer"]))) : false;

	if(isset($_GET["op"]) && limpiar($_GET["op"])=="statkonkurs") {

		$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `id`='$id' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` DESC LIMIT 1");
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
			//$desc_kon = $row["desc"];

			echo '<br><b>������� #'.$row["id"].' �� �������� '.$referer.'</b><br><br>';
			echo '<table align="center" border="0" width="100%" class="tables">';
			echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="3" class="top">��� ��������</th></tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="30%" align="right" height="30px"><b>��� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '���������� �������';
					}elseif($row["type_kon"]==2) {
						echo '���������� ������������ ������';
					}elseif($row["type_kon"]==3) {
						echo '����������� ���������';
					}else{
						echo '<b style="color: #FF0000;">������! �� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr bgcolor="#AFEEEE">';
				echo '<td width="30%" align="right" height="30px"><b>�������� ��������:</b></td>';
				echo '<td colspan="2">';
					if($row["type_kon"]==1) {
						echo '������� ����� ���������: ��� ������ �������� �������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==2) {
						echo '������� ����� ���������: ��� ������ �������� ������������ ������, �� ����� ���������� ��������';
					}elseif($row["type_kon"]==3) {
						echo '������� ����� ���������: ��� ������ ��������� ���������, �� ����� ���������� ��������';
					}else{
						echo '<b style="color: #FF0000;">������! �� ������ ��� ��������</b>';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="30%" align="right" height="30px"><b>���������� �������� ����</b></td>';
				echo '<td colspan="2">'.$row["count_kon"].'</td>';
			echo '</tr>';
			echo '<tr bgcolor="#AFEEEE">';
				echo '<td width="30%" align="right" height="30px"><b>�����:</b></td>';
				echo '<td colspan="2">';
					for($i=1; $i<=$row["count_kon"]; $i++) {
						echo '<b>'.$row["p$i"].'���.</b>, ';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="30%" align="right" height="30px"><b>�������� ����:</b></td>';
				echo '<td colspan="2">'.$p.' ���.</td>';
			echo '</tr>';

			if($row["type_kon"]==1) {
				echo '<tr bgcolor="#ADD8E6">';
					echo '<td width="30%" align="right" height="30px"><b>��� ������� ��������� ��������� ������� �� �����:</b></td>';
					echo '<td colspan="2">'.$row["limit_kon"].'</td>';
				echo '</tr>';
			}elseif($row["type_kon"]==3) {
				echo '<tr bgcolor="#ADD8E6">';
					echo '<td width="30%" align="right" height="30px"><b>��� ������� ��������� �������� �� �����:</b></td>';
					echo '<td colspan="2">'.$row["limit_kon"].'</td>';
				echo '</tr>';
			}

			echo '<tr bgcolor="#AFEEEE">';
				echo '<td width="30%" align="right" height="30px"><b>����� ���������� ��������:</b></td>';
				echo '<td colspan="2">c '.DATE("d.m.Y�. H:i:s", $row["date_s"]).' ��  '.DATE("d.m.Y�. H:i:s", $row["date_e"]).' ('.@$d.')</td>';
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
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
			echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="3" class="top">�������� ��������</th></tr>';
			echo '<tr bgcolor="#AFEEEE">';
				echo '<td colspan="3">'.$desc_kon.'</td>';
			echo '</tr>';

			$sql_s = mysql_query("SELECT * FROM `tb_refkonkurs_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_kon"]."' ORDER BY `kolvo` DESC LIMIT 100");
			$kol = mysql_num_rows($sql_s);
			if(mysql_num_rows($sql_s) > 0) {
				echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="3" class="top">TOP 100 ������������� �� ��������</th></tr>';
				echo '<tr bgcolor="#AFEEEE">';
					echo '<td colspan="3">�������������, ����������� � ��������: '.$kol.'</td>';
				echo '</tr>';
				echo '<tr bgcolor="#ADD8E6">';
					echo '<td colspan="3">';
					$i=0;
					while ($row_s = mysql_fetch_array($sql_s)) {
						$i++;
						echo ''.$i.'. <b>'.$row_s["username"].'</b> - '.$row_s["kolvo"].'<br>';
					}
					echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
		}else{
			echo '<fieldset class="errorp">�������� �� �������.</fieldset>';
		}
	}else{
		echo '<br><b>�������� �� �������� '.$referer.'</b><br><br>';

		echo '<br><table align="center" border="0" width="100%" class="tables">';
		echo '<tr align="center">';
			echo '<th align="center" class="top">�</th>';
			echo '<th align="center" class="top">��� ��������</th>';
			echo '<th align="center" class="top">���� ������</th>';
			echo '<th align="center" class="top">���� ���������</th>';
		echo '</tr>';

		$sql = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$referer' AND `date_s`<='".time()."' AND `date_e`>='".time()."' ORDER BY `id` DESC");
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

					if($row["type_kon"]=="1")
						$type_kon="<b>���������� �������</b>";
					elseif($row["type_kon"]=="2")
						$type_kon="<b>���������� ������������ ������</b>";
					elseif($row["type_kon"]=="3")
						$type_kon="<b>����������� ���������</b>";
					else{
						$type_kon='<b style="color:#FF0000;">��� �������� �� ���������!</b>';
					}

					if($row["status"]==1) {
						$status='<span style="color: #009200;">�������, ���� �������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?referer='.$referer.'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}elseif($row["status"]==2) {
						$status='<span style="color: #009CFF;">������� ��������, ����� ���������, ����� ���������</span>';
						$statistik = '[<a href="'.$_SERVER["PHP_SELF"].'?referer='.$referer.'&op=statkonkurs&id='.$row["id"].'" target="_blank">����������</a>]';
					}else{
						$status="";
						$statistik = "";
					}
					echo '<td>'.$type_kon.' ('.$row["count_kon"].' '.$m.', �������� ���� <b>'.$p.' ���.</b>)<br>'.$status.'<br>'.$statistik.'</td>';

					if($row["date_s"]==0) {
						echo '<td align="center">-</td>';
						echo '<td align="center">-</td>';
					}else{
						echo '<td align="center">'.DATE("d.m.Y", $row["date_s"]).'<br>'.DATE("H:i:s", $row["date_s"]).'</td>';
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

include('footer.php');
?>