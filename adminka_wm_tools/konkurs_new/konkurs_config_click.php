<script type="text/javascript" src="js/jquery_min.js" ></script>
<link rel="stylesheet" href="css/ui.datepicker.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="js/ui.datepicker-ru.js"></script>
<script type="text/javascript">
	function gebi(id){
		return document.getElementById(id)
	}

/*<![CDATA[*/

	var $d = jQuery.noConflict();

	$d(document).ready(function() {

		$d.datepicker.setDefaults($d.datepicker.regional['ru']);

		$d("#startDate1,#endDate1").datepicker({
		    beforeShow: customRange,
		    yearRange: "<?php echo (DATE("Y")-1).":".(DATE("Y")+1);?>",
		    showOn: "both",
		    buttonImageOnly: true
		});

		$d("#startDate2,#endDate2").datepicker({
		    beforeShow: customRange,
		    yearRange: "<?php echo (DATE("Y")-1).":".(DATE("Y")+1);?>",
		    showOn: "both",
		    buttonImageOnly: true
		});

		$d("#startDate3,#endDate3").datepicker({
		    beforeShow: customRange,
		    yearRange: "<?php echo (DATE("Y")-1).":".(DATE("Y")+1);?>",
		    showOn: "both",
		    buttonImageOnly: true
		});

		$d("#startDate4,#endDate4").datepicker({
		    beforeShow: customRange,
		    yearRange: "<?php echo (DATE("Y")-1).":".(DATE("Y")+1);?>",
		    showOn: "both",
		    buttonImageOnly: true
		});
	});

	function customRange(input) {
		return {

		}
	}

/* ]]> */

</script>

<?php
echo '<h3 class="sp" style="margin:0; padding:0;"><b>������� ��������</b></h3>';

$free_users = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='' AND `user_status`='user' AND `not_get_ref`='0' AND `ban_date`='0'"));
echo "�� ������ ������ � ������� ��������� ��������� ������� ����� ������ � �������� �����: <b>".($free_users)."</b>";

$konk_click_type_prize_arr = array("","������","�����","��������","% �� ����������� �����");

### ������� �������� ###
if( isset($_POST["type"]) && limpiar($_POST["type"])=="click" ) {

	$konk_click_date_start = (isset($_POST["konk_click_date_start"])) ? abs(intval(strtotime(limpiar($_POST["konk_click_date_start"])))) : "0";
	$konk_click_date_end = (isset($_POST["konk_click_date_end"])) ? abs(intval(strtotime(limpiar($_POST["konk_click_date_end"])))) : "0";
	$konk_click_min = (isset($_POST["konk_click_min"]) && abs(round( intval(str_replace(",",".", limpiar($_POST["konk_click_min"]))), 2))>0) ? abs(round( intval(str_replace(",",".", limpiar($_POST["konk_click_min"]))), 2)) : "1";
	$konk_click_autostart = (isset($_POST["konk_click_autostart"]) && abs(intval(limpiar($_POST["konk_click_autostart"])))==1) ? "1" : "0";

	$error = 0; $konk_click_type_prize_error = 0; $konk_click_all_count_prize = 0;
	for($y=1; $y<=3; $y++) {
		$konk_click_count_prize[$y] = 0;

		$konk_click_type_prize[$y] = (isset($_POST["konk_click_type_prize"][$y]) && abs(intval(limpiar($_POST["konk_click_type_prize"][$y])))==1) ? "1" : "0";

		for($i=1; $i<=10; $i++) {
			$konk_click_prizes[$y][$i] = (isset($_POST["konk_click_prizes"][$y][$i]) && $konk_click_type_prize[$y]!=0) ? abs(round( floatval(str_replace(",",".", limpiar($_POST["konk_click_prizes"][$y][$i]))), 2)) : "0";

			if(($y==2 | $y==3) && $konk_click_type_prize[$y]==1) $konk_click_prizes[$y][$i] = intval($konk_click_prizes[$y][$i]);

			if($konk_click_prizes[$y][$i]>0) {
				$konk_click_count_prize[$y]++;
			}
		}

		if(array_sum($konk_click_prizes[$y])==0) $konk_click_type_prize[$y] = 0;

		if($konk_click_type_prize[$y]==0) $konk_click_type_prize_error++;
	}

	for($i=1; $i<=10; $i++) {
		if(($konk_click_prizes[1][$i] + $konk_click_prizes[2][$i] + $konk_click_prizes[3][$i])!=0) $konk_click_all_count_prize++;
	}

	$konk_summ_get_free_users = array_sum($konk_click_prizes[3]);

	for($y=1; $y<=3; $y++) {
		for($i=1; $i<=10; $i++) {
			if($error>0) break;

			if($konk_click_type_prize_error==3) {
				$error++;
				echo '<span class="msg-error">���������� ������� ������ ���� ��� �����!</span>';
				break;
			}elseif($i<=3 && $konk_click_prizes[1][$i]==0 && $konk_click_prizes[2][$i]==0 && $konk_click_prizes[3][$i]==0) {
				for($z=1; $z<=3; $z++) {
					if($konk_click_type_prize[$z]==1 && $konk_click_prizes[$z][$i]==0) {
						$error++;
						echo '<span class="msg-error">���������� ������� ����('.$konk_click_type_prize_arr[$z].') �� '.$i.' �����!</span>';
						break;
					}
				}
			}elseif($i>1 && $i<10 && ($konk_click_prizes[1][$i]==0 && $konk_click_prizes[2][$i]==0 && $konk_click_prizes[3][$i]==0) && ($konk_click_prizes[1][$i+1]>0 | $konk_click_prizes[2][$i+1]>0 | $konk_click_prizes[3][$i+1]>0)) {
				$error++;
				echo '<span class="msg-error">���������� ������� ���� �� '.$i.' �����!</span>';
				break;
			}elseif($i>1 && $konk_click_prizes[$y][$i]>$konk_click_prizes[$y][$i-1] && $konk_click_prizes[$y][$i-1]!=0) {
				$error++;
				echo '<span class="msg-error">����('.$konk_click_type_prize_arr[$y].') �� '.$i.' ����� �� ����� ���� ������ ��� �� '.($i-1).' �����</span>';
				break;
			}
		}
	}

	$konk_click_type_prize = implode("; ", $konk_click_type_prize);
	$konk_click_prizes_1 = implode("; ", $konk_click_prizes[1]);
	$konk_click_prizes_2 = implode("; ", $konk_click_prizes[2]);
	$konk_click_prizes_3 = implode("; ", $konk_click_prizes[3]);
	$konk_click_count_prize = implode("; ", $konk_click_count_prize);

	$minus_free_users = explode("; ", $konk_click_count_prize);
	$minus_free_users = $minus_free_users[2];

	if($konk_click_date_end<$konk_click_date_start) {
		$konk_click_date_end_new = $konk_click_date_start;
		$konk_click_date_start_new = $konk_click_date_end;
		$konk_click_date_start = $konk_click_date_start;
		$konk_click_date_end = $konk_click_date_end_new;
	}

/*
	if($error==0 && $konk_click_date_start < (strtotime(date("d.m.Y"))) ) {
		echo '<span class="msg-error">���� ������ �� ����� ���� ������ '.DATE("d.m.Y").'�.</span>';
	}elseif($error==0 && $konk_click_date_end < (strtotime(date("d.m.Y"))) ) {
		echo '<span class="msg-error">���� ��������� �� ����� ���� ������ '.DATE("d.m.Y").'�.</span>';
	}elseif($error==0 && $konk_click_date_end == (strtotime(date("d.m.Y"))) ) {
		echo '<span class="msg-error">���� ��������� �� ����� ���� ����� '.DATE("d.m.Y").'�.</span>';
	}elseif($error==0 && $konk_click_date_end<$konk_click_date_start) {
		echo '<span class="msg-error">���� ��������� �� ����� ���� ������ ���� ������.</span>';
*/
	if($error==0 && $konk_click_date_end==$konk_click_date_start) {
		echo '<span class="msg-error">���� ��������� �� ����� ���� ����� ���� ������.</span>';
	}elseif(($konk_summ_get_free_users+$minus_free_users)>$free_users) {
		echo '<span class="msg-error">�������� ���� �� ��������� = '.$konk_summ_get_free_users.' ���. �� ������ ������ � ������� ��� ������� ��������� ��������� ������� ����� ������ � �������� �����</span>';
	}elseif($error==0) {

		mysql_query("UPDATE `tb_konkurs_conf` SET `price`='$konk_click_date_start' WHERE `type`='click' AND `item`='date_start'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price`='$konk_click_date_end' WHERE `type`='click' AND `item`='date_end'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price`='$konk_click_min' WHERE `type`='click' AND `item`='min_do'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price`='$konk_click_autostart' WHERE `type`='click' AND `item`='autostart'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price`='$konk_click_all_count_prize' WHERE `type`='click' AND `item`='all_count_prize'");

		mysql_query("UPDATE `tb_konkurs_conf` SET `price_array`='$konk_click_count_prize' WHERE `type`='click' AND `item`='count_prize'");

		mysql_query("UPDATE `tb_konkurs_conf` SET `price_array`='$konk_click_type_prize' WHERE `type`='click' AND `item`='type_prize'");

		mysql_query("UPDATE `tb_konkurs_conf` SET `price_array`='$konk_click_prizes_1' WHERE `type`='click' AND `item`='prizes' AND `howmany`='1'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price_array`='$konk_click_prizes_2' WHERE `type`='click' AND `item`='prizes' AND `howmany`='2'");
		mysql_query("UPDATE `tb_konkurs_conf` SET `price_array`='$konk_click_prizes_3' WHERE `type`='click' AND `item`='prizes' AND `howmany`='3'");

		if($konk_click_date_end>=time()) {
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='1' WHERE `type`='click' AND `item`='status'");
		}else{
			mysql_query("UPDATE `tb_konkurs_conf` SET `price`='0' WHERE `type`='click' AND `item`='status'");
		}

		echo '<span id="info-msg" class="msg-ok">��������� ������� ���������!</span>';
		echo '<script type="text/javascript"> setTimeout(function() {hidemsg()}, 1500); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
	}
}

$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='status'");
$konk_click_status = mysql_result($sql,0,0);

if(count($_POST)>0) {
	$konk_click_type_prize = explode("; ", $konk_click_type_prize);

	$konk_click_prizes[1] = explode("; ", $konk_click_prizes_1);
	$konk_click_prizes[2] = explode("; ", $konk_click_prizes_2);
	$konk_click_prizes[3] = explode("; ", $konk_click_prizes_3);

	$konk_click_count_prize = explode("; ", $konk_click_count_prize);
}else{
	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_start'");
	$konk_click_date_start = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='date_end'");
	$konk_click_date_end = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='count_prize'");
	$konk_click_count_prize = explode("; ", mysql_result($sql,0,0));

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='min_do'");
	$konk_click_min = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='autostart'");
	$konk_click_autostart = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='type_prize'");
	$konk_click_type_prize = explode("; ", mysql_result($sql,0,0));

	for($y=1; $y<=3; $y++) {
		$sql = mysql_query("SELECT `price_array` FROM `tb_konkurs_conf` WHERE `type`='click' AND `item`='prizes' AND `howmany`='$y'");
		$konk_click_prizes[$y] = explode("; ", mysql_result($sql,0,0));
	}
}

echo '<form method="POST" action="" id="newform" autocomplete="off">';
	echo '<input type="hidden" name="type" value="click">';
	echo '<table style="width:auto;">';
		echo '<tr>';
			echo '<th width="300">��������</th>';
			echo '<th>��������</th>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>������ ��������</b></td>';
			echo '<td align="left">'.($konk_click_status==1 ? '<b style="color:green;">�������</b>' : '<b style="color:red;">�� �������</b>').'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>���� ������</b></td>';
			echo '<td align="left"><input type="text" name="konk_click_date_start" id="startDate1" value="'.DATE("d.m.Y", $konk_click_date_start).'" class="ok12" style="text-align:center;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>���� ���������</b></td>';
			echo '<td align="left"><input type="text" name="konk_click_date_end" id="endDate1" value="'.DATE("d.m.Y", $konk_click_date_end).'" class="ok12" style="text-align:center;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>����������� ���-�� ������ ��� �������</b></td>';
			echo '<td align="left"><input type="text" name="konk_click_min" value="'.$konk_click_min.'" class="ok12" style="text-align:center;"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>����������</b></td>';
			echo '<td align="left">';
				echo '<input type="checkbox" name="konk_click_autostart" value="1" '.($konk_click_autostart == 1 ? 'checked="checked"' : false).'>&nbsp;-&nbsp;';
				echo '<b>���������� �������� ����� ��� ���������</b>, ������� ���������� ������������� � ��� �� ����������� �������';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td align="left"><b>��� �����</b></td>';
			echo '<td align="center">';
				echo '<table style="margin:0; padding:0;"><tr align="center">';
					for($y=1; $y<=3; $y++) {
						echo '<td align="center" valign="top" width="25%">';
							echo '<input type="checkbox" name="konk_click_type_prize['.$y.']" value="1" '.($konk_click_type_prize[$y-1] == 1 ? 'checked="checked"' : false).'><br>';
							echo "<b>$konk_click_type_prize_arr[$y]</b>";
						echo '</td>';
					}
				echo '</tr></table>';
			echo '</td>';
		echo '</tr>';


		for($i=1; $i<=10; $i++) {
			echo '<tr>';
				echo '<td><b>���� �� '.$i.' �����</b></td>';
				echo '<td align="center" style="margin:0; padding:0;">';
					echo '<table style="margin:0; padding:0;"><tr align="center">';
						for($y=1; $y<=3; $y++) {
							echo '<td align="center" width="25%">';
								echo '<input type="text" name="konk_click_prizes['.$y.']['.$i.']" value="'.$konk_click_prizes[$y][$i-1].'" class="ok" style="width:70%; text-align:center;" />';
							echo '</td>';
						}
					echo '</tr></table>';
				echo '</td>';
			echo '</tr>';
		}

		echo '<tr><td align="center" colspan="2"><input type="submit" class="sub-blue160" value="C�������� ���������"></td></tr>';
	echo '</table>';
echo '</form>';
### ������� �������� ###

?>