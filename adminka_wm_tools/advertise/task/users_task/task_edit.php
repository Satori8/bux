<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


echo '<b>�������������� ������������� �������:</b><br><br>';


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$cena_task = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
$nacenka_task = mysql_result($sql,0,0);

if(count($_POST) > 0) {
	$zdname = (isset($_POST["zdname"])) ? limpiarez($_POST["zdname"]) : false;
	$zdname = limitatexto($zdname,100);
	$zdtext = (isset($_POST["zdtext"])) ? limpiarez($_POST["zdtext"]) : false;
	$zdtext = limitatexto($zdtext,5000);
	$zdurl = (isset($_POST["zdurl"])) ? limpiarez($_POST["zdurl"]) : false;
	$zdurl = limitatexto($zdurl,160);
	$zdtype = ( isset($_POST["zdtype"]) && preg_match("/^[0-9]{1,2}$/", trim($_POST["zdtype"])) && intval($_POST["zdtype"])>=1 && intval($_POST["zdtype"])<=11 ) ? intval(trim($_POST["zdtype"])) : 8;
	$zdre = (isset($_POST["zdre"]) && (intval($_POST["zdre"])==0 | intval($_POST["zdre"])==3 | intval($_POST["zdre"])==6 | intval($_POST["zdre"])==12 | intval($_POST["zdre"])==24 | intval($_POST["zdre"])==48 | intval($_POST["zdre"])==72)) ? intval(limpiarez($_POST["zdre"])) : "0";
	$zdcountry = (isset($_POST["zdcountry"]) && (intval($_POST["zdcountry"])==0 | intval($_POST["zdcountry"])==1 | intval($_POST["zdcountry"])==2)) ? intval(limpiarez($_POST["zdcountry"])) : "0";
	$zdcheck = (isset($_POST["zdcheck"]) && (intval($_POST["zdcheck"])==1 | intval($_POST["zdcheck"])==2)) ? intval(limpiarez($_POST["zdcheck"])) : "1";
	$zdquest = (isset($_POST["zdquest"])) ? limpiarez($_POST["zdquest"]) : false;
	$zdquest = limitatexto($zdquest,255);
	$zdotv = (isset($_POST["zdotv"])) ? limpiarez($_POST["zdotv"]) : false;
	$zdotv = limitatexto($zdotv,255);
	$zdprice = (isset($_POST["zdprice"])) ? p_floor(abs(floatval(str_replace(",",".",trim($_POST["zdprice"])))),2) : "$cena_task";
	$zdreit = (isset($_POST["zdreit"]) && (intval($_POST["zdreit"])>=0 && intval($_POST["zdreit"])<=100)) ? intval(limpiarez($_POST["zdreit"])) : "0";

	if(strlen($zdname) < 1)
		echo '<fieldset class="errorp">������! �� ������� ��������!</fieldset>';
	elseif($zdtext==false)
		echo '<fieldset class="errorp">������! �� ������� ��������!</fieldset>';
	elseif($zdurl==false)
		echo '<fieldset class="errorp">������! �� ������� ������ �� ����!</fieldset>';
	elseif(substr($zdurl, 0, 7) != "http://" && substr($zdurl, 0, 8) != "https://")
		echo '<fieldset class="errorp">������! �� ����� ������� ������ �� ����!</fieldset>';
	elseif($zdcheck==2 && ($zdquest==false | $zdotv==false | strlen($zdquest) < 4 | strlen($zdotv) < 2) ) {
		if(strlen($zdquest) < 4)
			echo '<fieldset class="errorp">������! �� ������ ����������� ������!</fieldset>';
		elseif(strlen($zdotv) < 2)
			echo '<fieldset class="errorp">������! �� ������ ����� �� ����������� ������!</fieldset>';
		else {}
	}elseif($zdprice<$cena_task) {
			echo '<fieldset class="errorp">������! ����������� ��������� �� ���������� ������� '.number_format($cena_task,2,"."," ").' ���.</fieldset>';
	}else{
		$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			mysql_query("UPDATE `tb_ads_task` SET `country_targ`='$zdcountry',`zdname`='$zdname',`zdtext`='$zdtext',`zdurl`='$zdurl',`zdtype`='$zdtype',`zdre`='$zdre',`zdquest`='$zdquest',`zdotv`='$zdotv',`zdprice`='$zdprice',`zdreit_us`='$zdreit' WHERE  `id`='$rid'") or die(mysql_error());

			echo '<fieldset class="okp">��������� ������� ���������! <a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">������� � ������ ������� &gt;&gt;</a></fieldset>';
		}else{
			echo '<fieldset class="errorp">������! ������ ������� ���! <a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">������� � ������ ������� &gt;&gt;</a></fieldset>';
			exit();
		}
	}
}else{
	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$id = $row["id"];
		$zdname = $row["zdname"];
		$zdtext = $row["zdtext"];
		$zdurl = $row["zdurl"];
		$zdtype = $row["zdtype"];
		$zdre = $row["zdre"];
		$zdreit = $row["zdreit_us"];
		$zdcheck = $row["zdcheck"];
		$zdquest = $row["zdquest"];
		$zdotv = $row["zdotv"];
		$zdprice = $row["zdprice"];
		$zdcountry = $row["country_targ"];
	}else{
		echo '<fieldset class="errorp">������! ������ ������� ���! <a href="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'">������� � ������ ������� &gt;&gt;</a></fieldset>';
		exit();
	}
}

if($zdcountry==1) {$sel_country1='selected="selected"';}else{$sel_country1="";}
if($zdcountry==2) {$sel_country2='selected="selected"';}else{$sel_country2="";}
if($zdtype==1) {$sel_type1='selected="selected"';}else{$sel_type1="";}
if($zdtype==2) {$sel_type2='selected="selected"';}else{$sel_type2="";}
if($zdtype==3) {$sel_type3='selected="selected"';}else{$sel_type3="";}
if($zdtype==4) {$sel_type4='selected="selected"';}else{$sel_type4="";}
if($zdtype==5) {$sel_type5='selected="selected"';}else{$sel_type5="";}
if($zdtype==6) {$sel_type6='selected="selected"';}else{$sel_type6="";}
if($zdtype==7) {$sel_type7='selected="selected"';}else{$sel_type7="";}
if($zdtype==8) {$sel_type8='selected="selected"';}else{$sel_type8="";}
if($zdtype==9) {$sel_type9='selected="selected"';}else{$sel_type9="";}
if($zdtype==10) {$sel_type10='selected="selected"';}else{$sel_type10="";}
if($zdtype==11) {$sel_type11='selected="selected"';}else{$sel_type11="";}
if($zdre==3)  {$sel_re3='selected="selected"';}else{$sel_re3="";}
if($zdre==6)  {$sel_re6='selected="selected"';}else{$sel_re6="";}
if($zdre==12) {$sel_re12='selected="selected"';}else{$sel_re12="";}
if($zdre==24) {$sel_re24='selected="selected"';}else{$sel_re24="";}
if($zdre==48) {$sel_re48='selected="selected"';}else{$sel_re48="";}
if($zdre==72) {$sel_re72='selected="selected"';}else{$sel_re72="";}

echo '<div id="form">';
echo '<form id="form" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;option='.limpiar($_GET["option"]).'&amp;rid='.$rid.'" method="POST">';
echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="2" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
	echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">�������� ������������� �������</th></tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>��������:</b></td>';
		echo '<td>&nbsp;<input type="text" style="width:95%;" name="zdname" maxlength="100" value="'.$zdname.'"></td>';
	echo '</tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>�������� �������:</b></td>';
		echo '<td>&nbsp;<textarea rows="7" name="zdtext" style="width:95%;">'.str_replace("<br>","\r\n", $zdtext).'</textarea></td>';
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>������ �� ����:</b></td>';
		echo '<td>&nbsp;<input type="text" style="width:95%;" name="zdurl" maxlength="160" value="'.$zdurl.'"></td>';
	echo '</tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>��� �������:</b></td>';
		echo '<td>&nbsp;<select name="zdtype">
			<option value="1" '.$sel_type1.'>�����</option>
			<option value="2" '.$sel_type2.'>����������� ��� ����������</option>
			<option value="3" '.$sel_type3.'>����������� � �����������</option>
			<option value="4" '.$sel_type4.'>������� � �����</option>
			<option value="5" '.$sel_type5.'>������� � �����</option>
			<option value="6" '.$sel_type6.'>�����������</option>
			<option value="7" '.$sel_type7.'>�������� ������</option>
			<option value="9" '.$sel_type9.'>���������� ����</option>
			<option value="10" '.$sel_type10.'>YouTube</option>
			<option value="11" '.$sel_type11.'>������ � ������</option>
			<option value="8" '.$sel_type8.'>������</option>
		</select></td>';
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>������ ������ XX �. :</b></td>';
		echo '<td>&nbsp;<select name="zdre">
			<option value="0">���</option>
			<option value="3" '.$sel_re3.'>3 ����</option>
			<option value="6" '.$sel_re6.'>6 �����</option>
			<option value="12" '.$sel_re12.'>12 �����</option>
			<option value="24" '.$sel_re24.'>24 ���� (1 �����)</option>
			<option value="48" '.$sel_re48.'>48 ���� (2-� �����)</option>
			<option value="72" '.$sel_re72.'>72 ���� (3-� �����)</option>
		</select></td>';
	echo '</tr>';
	echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">�������� �������� �������</th></tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>�������� ��������:</b></td>';
			if($zdcheck==1) {
				echo '<td>&nbsp;������ �����<input type="hidden" name="zdcheck" value="1"></td>';
			}else{
				echo '<td>&nbsp;�������������� �����<input type="hidden" name="zdcheck" value="2"></td>';
			}
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		if($zdcheck==1) {
			echo '<td></td><td>&nbsp;<b style="color:#FF0000;">��������! � �������� ������� �� ������ ������� ����������, ������� ������������ ������ ��� ��������� ��� �������� ���������� �������.</b></td>';
		}else{
			echo '<td></td><td>&nbsp;<b style="color:#FF0000;">��������! ���� �������� �� ����������� ����� ����� �� ������, ���� ����������� ����� �� ����� �������������� �������, ������������� ������� �� ������ ���������� ����� ������� �� ������ ����� �������, �� � �������� ����� �� �������.</b></td>';
		}
	echo '</tr>';


	if($zdcheck==2) {
		echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">����������� ������</th></tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td width="200" align="right"><b>����������� ������:</b><br>(��&nbsp;4&nbsp;��&nbsp;255&nbsp;��������)</td>';
			echo '<td>&nbsp;<textarea rows="3" name="zdquest" style="width:95%;">'.str_replace("<br>","\r\n", $zdquest).'</textarea></td>';
		echo '</tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="200" align="right"><b>�����:</b><br>(��&nbsp;2&nbsp;��&nbsp;255&nbsp;��������)</td>';
			echo '<td>&nbsp;<input type="text" style="width:95%;" name="zdotv" maxlength="255" value="'.$zdotv.'"></td>';
		echo '</tr>';
	}

	echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">��������� ���������� �������</th></tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>��������� ����������:</b></td>';
		echo '<td>&nbsp;<input type="text" size="7" style="text-align:right;" name="zdprice" maxlength="10" value="'.number_format($zdprice,2,".","").'">(�������&nbsp;'.number_format($cena_task,2,".","").'&nbsp;���.)</td>';
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>������� ������������:</b></td>';
		echo '<td>&nbsp;<input type="text" size="7" style="text-align:right;" name="zdreit" maxlength="3" value="'.$zdreit.'">(��&nbsp;0&nbsp;��&nbsp;100)</td>';
	echo '</tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>��������� �� �������:</b></td>';
		echo '<td>&nbsp;<select name="zdcountry">
			<option value="0">����� ������</option>
			<option value="1" '.$sel_country1.'>������ ������</option>
			<option value="2" '.$sel_country2.'>������ �������</option>
		</select></td>';
	echo '</tr>';
	echo '<tr align="center" bgcolor="#ADD8E6">';
		echo '<td colspan="2"><input type="submit" class="submit" value="&nbsp;&nbsp;&nbsp;���������&nbsp;&nbsp;&nbsp;"></td>';
	echo '</tr>';
echo '</table>';
echo '</form>';
echo '</div>';

exit();
?>