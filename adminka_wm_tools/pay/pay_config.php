<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0; margin-bottom:0; padding-bottom:0; border:none;"><b>��������� ������</b></h3>';

$_ARR_EPS = array("wm", "ym", "pm", "pe", "qw", "ac", "me", "vs", "ms", "be", "mt", "mg", "tl");

if(count($_POST)>0) {
	foreach($_ARR_EPS as $val) {
		$pay_status[$val] = isset($_POST["pay_status"][$val]) ? abs(intval(floatval(trim($_POST["pay_status"][$val])))) : false;
		$pay_comis[$val]  = isset($_POST["pay_comis"][$val])  ? abs(round(number_format(floatval(trim($_POST["pay_comis"][$val])), 2, ".", ""), 2)) : false;
		$pay_min[$val]    = isset($_POST["pay_min"][$val])    ? abs(number_format(floatval(trim($_POST["pay_min"][$val])), 2, ".", "")) : false;
		$pay_max[$val]    = isset($_POST["pay_max"][$val])    ? abs(number_format(floatval(trim($_POST["pay_max"][$val])), 2, ".", "")) : false;

		mysql_query("UPDATE `tb_config_pay` SET `price`='$pay_status[$val]' 	WHERE `item`='pay_status' AND `eps`='$val'") or die(mysql_error());
		mysql_query("UPDATE `tb_config_pay` SET `price`='$pay_comis[$val]' 	WHERE `item`='pay_comis' AND `eps`='$val'") or die(mysql_error());
		mysql_query("UPDATE `tb_config_pay` SET `price`='$pay_min[$val]' 	WHERE `item`='pay_min' AND `eps`='$val'") or die(mysql_error());
		mysql_query("UPDATE `tb_config_pay` SET `price`='$pay_max[$val]' 	WHERE `item`='pay_max' AND `eps`='$val'") or die(mysql_error());
	}
}

foreach($_ARR_EPS as $val) {
	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_status' AND `eps`='$val'") or die(mysql_error());
	$pay_status[$val] = number_format(mysql_result($sql,0,0), 0, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_comis' AND `eps`='$val'") or die(mysql_error());
	$pay_comis[$val] = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_min' AND `eps`='$val'") or die(mysql_error());
	$pay_min[$val] = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

	$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_max' AND `eps`='$val'") or die(mysql_error());
	$pay_max[$val] = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);
}

echo '<form method="post" action="" id="newform">';
echo '<table class="adv-cab">';
echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:0 5px 0 0; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/wm16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">WebMoney</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[wm]">';
					echo '<option value="0" '.($pay_status["wm"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["wm"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["wm"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[wm]" style="text-align:center;" value="'.$pay_comis["wm"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[wm]" style="text-align:center;" value="'.$pay_min["wm"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[wm]" style="text-align:center;" value="'.$pay_max["wm"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';

	echo '<td width="33%" valign="top" style="margin:0 auto; padding:0 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/yandexmoney16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:#DE1200">�</span>�����.������</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[ym]">';
					echo '<option value="0" '.($pay_status["ym"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["ym"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["ym"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[ym]" style="text-align:center;" value="'.$pay_comis["ym"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[ym]" style="text-align:center;" value="'.$pay_min["ym"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[ym]" style="text-align:center;" value="'.$pay_max["ym"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';

	echo '<td width="33%" valign="top" style="margin:0 auto; padding:0 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0; color:#DE1200;"><img src="../img/perfectmoney16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">PerfectMoney</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[pm]">';
					echo '<option value="0" '.($pay_status["pm"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["pm"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["pm"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[pm]" style="text-align:center;" value="'.$pay_comis["pm"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[pm]" style="text-align:center;" value="'.$pay_min["pm"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[pm]" style="text-align:center;" value="'.$pay_max["pm"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
echo '</tr>';

echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/payeer16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[pe]">';
					echo '<option value="0" '.($pay_status["pe"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["pe"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["pe"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[pe]" style="text-align:center;" value="'.$pay_comis["pe"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[pe]" style="text-align:center;" value="'.$pay_min["pe"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[pe]" style="text-align:center;" value="'.$pay_max["pe"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';

	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/qiwi16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:orange;">QIWI �������</span></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[qw]">';
					echo '<option value="0" '.($pay_status["qw"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["qw"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["qw"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[qw]" style="text-align:center;" value="'.$pay_comis["qw"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[qw]" style="text-align:center;" value="'.$pay_min["qw"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[qw]" style="text-align:center;" value="'.$pay_max["qw"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';

	/*echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/mobile16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">��������� �������</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[mb]">';
					echo '<option value="0" '.($pay_status["mb"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["mb"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["mb"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[mb]" style="text-align:center;" value="'.$pay_comis["mb"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[mb]" style="text-align:center;" value="'.$pay_min["mb"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[mb]" style="text-align:center;" value="'.$pay_max["mb"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
echo '</tr>';*/

//echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
	echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/advcash_18x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span>></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[ac]">';
					echo '<option value="0" '.($pay_status["ac"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["ac"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["ac"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[ac]" style="text-align:center;" value="'.$pay_comis["ac"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[ac]" style="text-align:center;" value="'.$pay_min["ac"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[ac]" style="text-align:center;" value="'.$pay_max["ac"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/me16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:green;">MAESTRO</span></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[me]">';
					echo '<option value="0" '.($pay_status["me"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["me"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["me"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[me]" style="text-align:center;" value="'.$pay_comis["me"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[me]" style="text-align:center;" value="'.$pay_min["me"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[me]" style="text-align:center;" value="'.$pay_max["me"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
	
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/vs16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:green;">VISA</span></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[vs]">';
					echo '<option value="0" '.($pay_status["vs"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["vs"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["vs"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[vs]" style="text-align:center;" value="'.$pay_comis["vs"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[vs]" style="text-align:center;" value="'.$pay_min["vs"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[vs]" style="text-align:center;" value="'.$pay_max["vs"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
	//echo '</tr>';
	
	//echo '<tr>';
	//echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
	//echo '</td>';
	
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/ms16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle"><span style="color:green;">MasterCard</span></h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[ms]">';
					echo '<option value="0" '.($pay_status["ms"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["ms"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["ms"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[ms]" style="text-align:center;" value="'.$pay_comis["ms"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[ms]" style="text-align:center;" value="'.$pay_min["ms"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[ms]" style="text-align:center;" value="'.$pay_max["ms"].'" /></td>';
		echo '</tr>';
		echo '</table>';
	echo '</td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/be16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">������</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[be]">';
					echo '<option value="0" '.($pay_status["be"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["be"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["be"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[be]" style="text-align:center;" value="'.$pay_comis["be"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[be]" style="text-align:center;" value="'.$pay_min["be"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[be]" style="text-align:center;" value="'.$pay_max["be"].'" /></td>';
			echo '</tr>';
		echo '</table>';
	echo '</td>';
	
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/mt16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">���</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[mt]">';
					echo '<option value="0" '.($pay_status["mt"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["mt"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["mt"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[mt]" style="text-align:center;" value="'.$pay_comis["mt"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[mt]" style="text-align:center;" value="'.$pay_min["mt"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[mt]" style="text-align:center;" value="'.$pay_max["mt"].'" /></td>';
			echo '</tr>';
		echo '</table>';
	echo '</td>';
	//echo '</tr>';
	
	//echo '<tr>';
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/mg16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">�������</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[mg]">';
					echo '<option value="0" '.($pay_status["mg"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["mg"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["mg"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[mg]" style="text-align:center;" value="'.$pay_comis["mg"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[mg]" style="text-align:center;" value="'.$pay_min["mg"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[mg]" style="text-align:center;" value="'.$pay_max["mg"].'" /></td>';
			echo '</tr>';
		echo '</table>';
	echo '</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
	echo '</td>';
	
	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
		echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><img src="../img/tl16x16.png" alt="" style="margin:0px; padding:3px 3px 3px 0px;" align="absmiddle">TELE2</h3>';
		echo '<table class="tables" style="margin:0 auto; padding:0;">';
		echo '<tr align="center"><th>��������</th><th width="125">��������</th></tr>';
		echo '<tr>';
			echo '<td align="left"><b>������ �������</b></td>';
			echo '<td>';
				echo '<select style="width:125px;" name="pay_status[tl]">';
					echo '<option value="0" '.($pay_status["tl"]==0 ? "selected": false).'>���������</option>';
					echo '<option value="1" '.($pay_status["tl"]==1 ? "selected": false).'>�������������</option>';
					echo '<option value="2" '.($pay_status["tl"]==2 ? "selected": false).'>�������</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������</b>, %</td>';
			echo '<td><input type="text" class="ok12" name="pay_comis[tl]" style="text-align:center;" value="'.$pay_comis["tl"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_min[tl]" style="text-align:center;" value="'.$pay_min["tl"].'" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� � �������</b>, ���.</td>';
			echo '<td><input type="text" class="ok12" name="pay_max[tl]" style="text-align:center;" value="'.$pay_max["tl"].'" /></td>';
			echo '</tr>';
		echo '</table>';
	echo '</td>';

	echo '<td width="33%" valign="top" style="margin:0 auto; padding:10px 5px 0 5px; border:none; background:none;">';
	echo '</td>';
echo '</tr>';

echo '<tr align="center"><td colspan="3" style="border:none; background:none; padding-top:10px;"><input type="submit" value="C�������� ���������" class="sub-blue160"></td></tr>';

echo '</table>';
echo '</form>';


if(count($_POST)>0) {
	echo '<span id="info-msg" class="msg-ok">��������� ������� ���������!</span>';

	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1500);
		HideMsg("info-msg", 1510);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

?>