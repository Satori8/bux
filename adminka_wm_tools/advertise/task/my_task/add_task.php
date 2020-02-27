<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("'"," ",$mensaje);
	$mensaje = str_replace(";"," ",$mensaje);
	$mensaje = str_replace("$","$",$mensaje);
	$mensaje = str_replace("<","&#60;",$mensaje);
	$mensaje = str_replace(">","&#62;",$mensaje);
	$mensaje = str_replace("\\","",$mensaje);
	$mensaje = str_replace("&amp amp ","&amp;",$mensaje);
	$mensaje = str_replace("&amp quot ","&quot;",$mensaje);
	$mensaje = str_replace("&amp gt ","&gt;",$mensaje);
	$mensaje = str_replace("&amp lt ","&lt;",$mensaje);
	$mensaje = str_replace("\r\n","<br>",$mensaje);
	return $mensaje;
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$cena_task = mysql_result($sql,0,0);


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

$sel1=""; $sel="";
if($zdcheck==2){
	$display = 'style="display:block;"';
	$sel1="";
	$sel2='selected="selected"';
}else{
	$display = 'style="display:none;"';
	$sel1='selected="selected"';
	$sel2="";
}

if(count($_POST) > 0) {
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
		if($zdcheck==1) {$zdquest=""; $zdotv="";}

		mysql_query("INSERT INTO `tb_ads_task` (`status`,`date_up`,`country_targ`,`username`,`user_id`,`zdname`,`zdtext`,`zdurl`,`zdtype`,`zdre`,`zdcheck`,`zdquest`,`zdotv`,`zdprice`,`zdreit_us`,`date_add`,`date_act`,`ip`) VALUES('wait','".time()."','$zdcountry','$username','$partnerid','$zdname','$zdtext','$zdurl','$zdtype','$zdre','$zdcheck','$zdquest','$zdotv','$zdprice','$zdreit','".time()."','".time()."','$ip')") or die(mysql_error());

		echo '<fieldset class="okp">������� ���������!</fieldset>';

		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view");</script>';
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view">';

		
		exit();
	}
}

echo '<div id="form">';
echo '<form id="form" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page='.limpiar($_GET["page"]).'" method="POST">';
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
			<option value="1">�����</option>
			<option value="2">����������� ��� ����������</option>
			<option value="3">����������� � �����������</option>
			<option value="4">������� � �����</option>
			<option value="5">������� � �����</option>
			<option value="6">�����������</option>
			<option value="7">�������� ������</option>
			<option value="9">���������� ����</option>
			<option value="10">YouTube</option>
			<option value="11">������ � ������</option>
			<option value="8">������</option>
		</select> ���� �� ����������� �������� ������ - <b>����� 1.00 ���.</b></td>';
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>������ ������ XX �. :</b></td>';
		echo '<td>&nbsp;<select name="zdre">
			<option value="0">���</option>
			<option value="3">3 ����</option>
			<option value="6">6 �����</option>
			<option value="12">12 �����</option>
			<option value="24">24 ���� (1 �����)</option>
			<option value="48">48 ���� (2-� �����)</option>
			<option value="72">72 ���� (3-� �����)</option>
		</select></td>';
	echo '</tr>';
	echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">�������� �������� �������</th></tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>�������� ��������:</b></td>';
		echo '<td>&nbsp;<select name="zdcheck">
			<option value="1" onclick="document.getElementById(\'quest\').style.display=\'none\'" '.$sel1.'>������ �����</option>
			<option value="2" onclick="document.getElementById(\'quest\').style.display=\'block\'" '.$sel2.'>�������������� �����</option>
		</select></td>';
	echo '</tr>';
echo '</table>';

echo '<div id="quest" '.$display.'>';
echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="2" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
	echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">����������� ������</th></tr>';
	echo '<tr bgcolor="#AFEEEE">';
		echo '<td width="200" align="right"><b>����������� ������:</b><br>(��&nbsp;4&nbsp;��&nbsp;255&nbsp;��������)</td>';
		echo '<td>&nbsp;<textarea rows="3" name="zdquest" style="width:95%;">'.str_replace("<br>","\r\n", $zdquest).'</textarea></td>';
	echo '</tr>';
	echo '<tr bgcolor="#ADD8E6">';
		echo '<td width="200" align="right"><b>�����:</b><br>(��&nbsp;2&nbsp;��&nbsp;255&nbsp;��������)</td>';
		echo '<td>&nbsp;<input type="text" style="width:95%;" name="zdotv" maxlength="255" value="'.$zdotv.'"></td>';
	echo '</tr>';
echo '</table>';
echo '</div>';

echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="2" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
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
			<option value="1">������ ������</option>
			<option value="2">������ �������</option>
		</select></td>';
	echo '</tr>';
	echo '<tr align="center" bgcolor="#ADD8E6">';
		echo '<td colspan="2"><input type="submit" class="submit" value="�������� �������"></td>';
	echo '</tr>';
echo '</table>';
echo '</form>';
echo '</div>';

?>