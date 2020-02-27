<?php

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

$zdtype = (isset($_POST["zdtype"]) && (intval($_POST["zdtype"])==1 | intval($_POST["zdtype"])==2 | intval($_POST["zdtype"])==3 | intval($_POST["zdtype"])==4 | intval($_POST["zdtype"])==5 | intval($_POST["zdtype"])==6 | intval($_POST["zdtype"])==7 | intval($_POST["zdtype"])==8 | intval($_POST["zdtype"])==9 | intval($_POST["zdtype"])==10 | intval($_POST["zdtype"])==11 | intval($_POST["zdtype"])==12 | intval($_POST["zdtype"])==13 | intval($_POST["zdtype"])==14 | intval($_POST["zdtype"])==15 | intval($_POST["zdtype"])==16 | intval($_POST["zdtype"])==18 | intval($_POST["zdtype"])==19 | intval($_POST["zdtype"])==20 | intval($_POST["zdtype"])==21 | intval($_POST["zdtype"])==22 | intval($_POST["zdtype"])==23)) ? intval($_POST["zdtype"]) : "8";

$time = (isset($_POST["time"]) && (intval($_POST["time"])==1 | intval($_POST["time"])==2 | intval($_POST["time"])==3 | intval($_POST["time"])==4 | intval($_POST["time"])==5 | intval($_POST["time"])==6 | intval($_POST["time"])==7 | intval($_POST["time"])==8)) ? intval($_POST["time"]) : "8";
$zdre = (isset($_POST["zdre"]) && (intval($_POST["zdre"])==0 | intval($_POST["zdre"])==3 | intval($_POST["zdre"])==6 | intval($_POST["zdre"])==12 | intval($_POST["zdre"])==24 | intval($_POST["zdre"])==48 | intval($_POST["zdre"])==72)) ? intval(limpiarez($_POST["zdre"])) : "0";
$zdcountry = (isset($_POST["zdcountry"]) && (intval($_POST["zdcountry"])==0 | intval($_POST["zdcountry"])==1 | intval($_POST["zdcountry"])==2)) ? intval(limpiarez($_POST["zdcountry"])) : "0";
$zdcheck = (isset($_POST["zdcheck"]) && (intval($_POST["zdcheck"])==1 | intval($_POST["zdcheck"])==2)) ? intval(limpiarez($_POST["zdcheck"])) : "1";
$zdquest = (isset($_POST["zdquest"])) ? limpiarez($_POST["zdquest"]) : false;
$zdquest = limitatexto($zdquest,255);
$zdotv = (isset($_POST["zdotv"])) ? limpiarez($_POST["zdotv"]) : false;
$zotch = (isset($_POST["zotch"])) ? limpiarez($_POST["zotch"]) : false;

	$zddistrib_ch = (isset($_POST["zddistrib_ch"]) && (intval($_POST["zddistrib_ch"])>0)) ? intval(limpiarez($_POST["zddistrib_ch"])) : "0";
	$zddistrib_min = (isset($_POST["zddistrib_min"]) && (intval($_POST["zddistrib_min"])>0)) ? intval(limpiarez($_POST["zddistrib_min"])) : "0";	
	$distrib=$zddistrib_ch*60*60+$zddistrib_min*60;

$zdotv = limitatexto($zdotv,255);

$zdprice = (isset($_POST["zdprice"]) and strlen($_POST["zdprice"])<=10) ? p_floor(abs(floatval(str_replace(",",".",trim($_POST["zdprice"])))),2) : "$cena_task";

$zdreit = (isset($_POST["zdreit"]) && (intval($_POST["zdreit"])>=0 && intval($_POST["zdreit"])<=100)) ? intval(limpiarez($_POST["zdreit"])) : "0";
$mailwm = ( isset($_POST["mailwm"]) && (intval($_POST["mailwm"])==0 | intval($_POST["mailwm"])==1) ) ? intval($_POST["mailwm"]) : "0";

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
	elseif($zotch==false && $zdcheck=='1')
		echo '<fieldset class="errorp">������! ������� ��� ����� ������ �  ������!</fieldset>';
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
	}elseif(@getHost($zdurl)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($zdurl)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($zdurl).'</span>';

	}else{
		if($zdcheck==1) {$zdquest=""; $zdotv="";}else{$zotch='';}

		mysql_query("INSERT INTO `tb_ads_task` (`status`,`date_up`,`wmid`,`mailwm`,`country_targ`,`username`,`user_id`,`zdname`,`zdtext`,`zdurl`,`zdtype`,`zdre`,`zdcheck`,`zdquest`,`zdotv`,`zdprice`,`zdreit_us`,`date_add`,`date_act`,`ip`,`zotch`,`time`,`distrib`) VALUES('wait','".time()."','$wmiduser','$mailwm','$zdcountry','$username','$partnerid','$zdname','$zdtext','$zdurl','$zdtype','$zdre','$zdcheck','$zdquest','$zdotv','$zdprice','$zdreit','".time()."','".time()."','$ip','$zotch','$time','$distrib')") or die(mysql_error());

		echo '<fieldset class="okp">������� ���������!</fieldset>';

		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

		include('footer.php');
		exit();
	}
}



echo '<form id="newform" action="'.$_SERVER["PHP_SELF"].'?option='.limpiar($_GET["option"]).'" method="POST">';
echo '<table class="tables">';
	echo '<thead><tr align="center"><th class="top" align="center" colspan="2">�������� ������������� �������</th></tr></thead>';
	echo '<tr>';
		echo '<td align="right" width="150"><b>��������:</b></td>';
		echo '<td><input type="text" name="zdname" maxlength="100" value="'.$zdname.'" class="ok"></td>';
	echo '</tr>';
	echo '<tr>';
	
	echo '<td nowrap="nowrap" align="right"><b>�������� �������:</b></td>';
		
		echo '<td align="left" colspan="2" style="">';
			echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'message\'); return false;">�</span>';
			echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'message\'); return false;">�</span>';
			echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'message\'); return false;">�</span>';
			echo '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'message\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'message\'); return false;">URL</span>';
			echo '<span class="bbc-url" style="float:left;" title="�������� �����������" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'message\'); return false;">IMG</span>';
			echo '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">�������� ��������: 5000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
					
		echo '<textarea id="message" rows="7" name="zdtext" class="ok" onKeyup="descchange(\'1\', this, \'5000\');" onKeydown="descchange(\'1\', this, \'5000\');" onClick="descchange(\'1\', this, \'5000\');">'.str_replace("<br>","\r\n", $zdtext).'</textarea>';
		
		echo '</div>';
		echo '</td>';
	
	
		
               
	echo '</tr>';
	echo '<tr>';
		echo '<td align="right"><b>������ �� ����:</b></td>';
		echo '<td><input type="text" name="zdurl" maxlength="160" value="'.$zdurl.'" class="ok"></td>';
	echo '</tr>';
		echo '<tr>';
		echo '<td align="right"><b>��� ����� ������ �  ������:</b></td>';
		echo '<td><textarea name="zotch" maxlength="290" class="ok">'.str_replace("<br>","\r\n", $zotch).'</textarea></td>';
	echo '</tr>';
		echo '<tr>';
		echo '<td align="right"><b>����� �� ���������� �������:</b></td>';
		echo '<td><select name="time" class="ok">
			<option value="1">1 ���</option>
			<option value="2">3 ����</option>
			<option value="3">12 �����</option>
			<option value="4">24 ����</option>
			<option value="5">3 ���</option>
			<option value="6">7 ����</option>
			<option value="7">14 ����</option>
			<option value="8">30 ����</option>
		</select></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="right"><b>��������� �������:</b></td>';
		echo '<td><select name="zdtype" class="ok">
			<option value="1">�����</option>
			<option value="2">����������� ��� ����������</option>
			<option value="3">����������� � �����������</option>
			<option value="4">������� � �����</option>
			<option value="5">������� � �����</option>
			<option value="6">�����������</option>
			<option value="7">�������� ������</option>
			
			<option value="9">YouTube</option>
            <option value="10">���������� ����</option>
            <option value="11">�������� ������</option>
            <option value="12">�������� �����</option>
            <option value="13">������ � ����</option>
            <option value="14">�������������</option>
            <option value="15">����� ���� ��������� �� '.$_SERVER["HTTP_HOST"].'</option>
            <option value="16">������� ��������</option>

            <option value="18">Forex</option>
            <option value="19">��������� ����������</option>
            <option value="20">������ � ������</option>
            <option value="21">������ � ��������������</option>
            <option value="22">������������� ����/�����</option>
            <option value="23">���������� �����</option>
			<option value="8">������</option>
			
		</select></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="right"><b>��������� ����������:</b></td>';
		echo '<td><select name="zdre" class="ok">
			<option value="0">���</option>
			<option value="3">3 ����</option>
			<option value="6">6 �����</option>
			<option value="12">12 �����</option>
			<option value="24">24 ����</option>
			<option value="48">48 �����</option>
			<option value="72">72 ����</option>
		</select></td>';
	echo '</tr>';
	
		echo '<tr>';
		echo '<td nowrap="nowrap" width="150" align="right"><b>�������� ���������������� �������:<span class="status"><font color="red" title="������� ����� ������� ������ ������ ����� ������ ���������� ������� ����� ������������� � ������� ���������� ������ ������������� (������� ����� ������������)." style="cursor: help;">[?]</font></b></td>';
		echo '<td><select name="zddistrib" id="myselect">
			<option value="0" selected="selected">��� ���������</option>
			<option value="1">������������� ��������</option>
		</select><div id="mydiv">'; 
		echo '</tr>';
	?>

<script type="text/javascript">
    document.getElementById("myselect").addEventListener("change", function(){
	if(this.value==1){
      document.getElementById('mydiv').innerHTML = '����:<input type="text" class="ok" name="zddistrib_ch" maxlength="2" value="0"><br> ������:<input type="text" class="ok" name="zddistrib_min" maxlength="2" value="0">';  
	  }else{
	  document.getElementById('mydiv').innerHTML = '';  
	  }
    });

function InsertTags(text1, text2, descId) {
	var textarea = document.getElementById(descId);
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;
		if (temp_length == 0) {
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		} else {
			textarea.focus(caretPos);
		}
	} else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text1 + selection + text2 + end;
		if (textarea.setSelectionRange) {
			if (selection.length == 0) {
				textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			} else {
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			}
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	} else {
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
}
</script>
	
<?	
	echo '<tr>';
		echo '<td align="right"><b>�������� ��������:</b></td>';
		echo '<td><select id="zdcheck" name="zdcheck" class="ok">
			<option selected="selected" value="1">������ �����</option>
			<option value="2">�������������� �����</option>
		</select></td>';
	echo '</tr>';
echo '</table>';

echo '<div id="blokone" style="display:block"></div>';
echo '<div id="bloktwo"  style="display:none">';
echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="right" width="150"><b>����������� ������:</b><br>(��&nbsp;4&nbsp;��&nbsp;255&nbsp;��������)</td>';
		echo '<td>&nbsp;<textarea  name="zdquest" class="ok">'.str_replace("<br>","\r\n", $zdquest).'</textarea></td>';
	echo '</tr>';
	//echo '</div>';
	echo '<tr>';
		echo '<td align="right"><b>�����:</b><br>(��&nbsp;2&nbsp;��&nbsp;255&nbsp;��������)</td>';
		echo '<td>&nbsp;<input type="text" name="zdotv" maxlength="255" value="'.$zdotv.'" class="ok"></td>';
	echo '</tr>';
echo '</table>';
echo '</div>';

echo '<table class="tables">';
	echo '<thead><tr align="center"><th class="top" align="center" colspan="2">��������� ���������� �������</th></tr></thead>';
	echo '<tr>';
		echo '<td align="right" width="150"><b>��������� ����������:</b></td>';
		echo '<td><input type="text" name="zdprice" maxlength="10" value="'.number_format($zdprice,2,".","").'" class="ok12">(�������&nbsp;'.number_format($cena_task,2,".","").'&nbsp;���.)</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="right"><b>������� ������������:</b></td>';
		echo '<td><input type="text" name="zdreit" maxlength="3" value="'.$zdreit.'" class="ok12">(��&nbsp;0&nbsp;��&nbsp;100)</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="right"><b>��������� �� �������:</b></td>';
		echo '<td><select name="zdcountry" class="ok">
			<option value="0">����� ������</option>
			<option value="1">������ ������</option>
			<option value="2">������ �������</option>
		</select></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<tr align="center">';
		echo '<td colspan="2"><input type="submit" class="proc-btn" value="�������� �������"></td>';
	echo '</tr>';

echo '</table>';
echo '</form>';
echo '<tr>';
echo '<br><center><b><span style="color:#FF0000;">�������� ��������, ���� �� ������� �������� ��������� �������, � �� ���� �������� ������, � ��� ����� ������  ����� � ������� 1 ���. ��� ��������� ���������, ����� ������� ����� ��������� ����, ������� ���������� ��������. </span></b></center><br>';
echo '</tr>';

?>
<script type="text/javascript">
	document.getElementById("zdcheck")
    .onchange = function () {
        var b = {
            1: "blokone",
            2: "bloktwo",
        }, c = this.value,
            a;
        for (a in b) document.getElementById(b[a])
            .style.display = 0 == c || c == a ? "block" : "none"
};
</script>