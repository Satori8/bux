<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ��������� �����</b></h3>';

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
$min_mails = mysql_result($sql,0,0);

function limpiarez($mensaje){
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace("  ", " ", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));

	$mensaje = htmlspecialchars(trim($mensaje), ENT_QUOTES, "CP1251");
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);
	return $mensaje;
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	$id_pay = ( isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"])) ) ? intval(limpiarez(trim($_POST["id_pay"]))) : false;

	$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_id)>0) {
		$row = mysql_fetch_array($sql_id);
		$plan = $row["plan"];

		mysql_query("UPDATE `tb_ads_mails` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
        
		echo '<span class="msg-ok">������ ������� ���������!</span>';
		echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'")\', 1000); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiarez($_GET["op"]).'"></noscript>';
		exit();
	}else{
		echo '<span class="msg-error">������! ������� �� �������.</span>';
		exit();
	}
}


$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),255) : false;
$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),300) : false;
$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),5000) : false;
if (get_magic_quotes_gpc()) {$description = stripslashes($description);}
$question = (isset($_POST["question"])) ? limitatexto(limpiarez($_POST["question"]),255) : false;
$answer_t = (isset($_POST["answer_t"])) ? limitatexto(limpiarez($_POST["answer_t"]),255) : false;
$answer_f_1 = (isset($_POST["answer_f_1"])) ? limitatexto(limpiarez($_POST["answer_f_1"]),255) : false;
$answer_f_2 = (isset($_POST["answer_f_2"])) ? limitatexto(limpiarez($_POST["answer_f_2"]),255) : false;
$plan = ( isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["plan"])) && intval(limpiarez(trim($_POST["plan"]))) > $min_mails ) ? intval(limpiarez(trim($_POST["plan"]))) : "$min_mails";
$tarif = (isset($_POST["tarif"]) && (intval($_POST["tarif"])==1 | intval($_POST["tarif"])==2 | intval($_POST["tarif"])==3)) ? intval(trim($_POST["tarif"])) : "2";
$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(trim($_POST["color"])) : "0";
$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(trim($_POST["active"])) : "0";
$gotosite = (isset($_POST["gotosite"]) && (intval($_POST["gotosite"])==0 | intval($_POST["gotosite"])==1)) ? intval(trim($_POST["gotosite"])) : "0";
$mailsre = (isset($_POST["mailsre"]) && (intval($_POST["mailsre"])==0 | intval($_POST["mailsre"])==1)) ? intval(trim($_POST["mailsre"])) : "0";
$method_pay = 0;
$laip = getRealIP();
$black_url = @getHost($url);


if(count($_POST)>0) {
	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row = mysql_fetch_array($sql_bl);
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).'!<br>�������: '.$row["cause"].'</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
	}elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif($title==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ��������� ������.</span><br>';
	}elseif($description==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ���������� ������.</span><br>';
	}elseif($question==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ����������� ������.</span><br>';
	}elseif($answer_t==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ������ �����.</span><br>';
	}elseif($answer_f_1==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ������ ����� �1.</span><br>';
	}elseif($answer_f_2==false) {
		echo '<span class="msg-error">������! �� ��������� ���� ������ ����� �2.</span><br>';
	}else{
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
		$cena_mails_color = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
		$cena_mails_active = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
		$cena_mails_gotosite = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_mails' AND `howmany`='1'");
		$nacenka_mails = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
		$min_mails = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='$tarif'");
		$cena_mails = mysql_result($sql,0,0);

		$price_one = ($cena_mails + ($color*$cena_mails_color) + ($active*$cena_mails_active) + ($gotosite*$cena_mails_gotosite)) * ($nacenka_mails+100)/100;
		$summa = round(($price_one * $plan),2);

	        mysql_query("DELETE FROM `tb_ads_mails` WHERE `status`='0' AND `date`<'".(time()-(12*60*60))."'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`='0' AND `wmid`='$wmid_user' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_mails` SET `date`='".time()."',`title`='$title',`url`='$url',`description`='$description',`question`='$question',`answer_t`='$answer_t',`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`plan`='$plan',`tarif`='$tarif',`color`='$color',`active`='$active',`gotosite`='$gotosite',`mailsre`='$mailsre',`method_pay`='$method_pay',`totals`='$plan',`money`='$summa',`ip`='$laip' WHERE `status`='0' AND `wmid`='$wmid_user' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_mails` (`status`,`date`,`wmid`,`username`,`title`,`url`,`description`,`question`,`answer_t`,`answer_f_1`,`answer_f_2`,`plan`,`tarif`,`color`,`active`,`gotosite`,`mailsre`,`method_pay`,`totals`,`money`,`ip`) 
			VALUES('0','".time()."','$wmid_user','$username','$title','$url','$description','$question','$answer_t','$answer_f_1','$answer_f_2','$plan','$tarif','$color','$active','$gotosite','$mailsre','$method_pay','$plan','$summa','$laip')") or die(mysql_error());
		}

        	$sql_id = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`='0' AND `wmid`='$wmid_user' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	        $id_zakaz = mysql_result($sql_id,0,0);

		mysql_query("ALTER TABLE `tb_ads_mails` ORDER BY `id`") or die(mysql_error());
		mysql_query("OPTIMIZE TABLE `tb_ads_mails`") or die(mysql_error());

		$sql_desc = mysql_query("SELECT `description` FROM `tb_ads_mails` WHERE `id`='$id_zakaz'");
		$description_to = mysql_result($sql_desc,0,0);

		require_once(DOC_ROOT."/bbcode/bbcode.lib.php");
		$description_to = new bbcode($description_to);
		$description_to = $description_to->get_html();
		$description_to = str_replace("&amp;", "&", $description_to);

		$tarif_to[1]="VIP (60 ������)";
		$tarif_to[2]="STANDART (40 ������)";
		$tarif_to[3]="LITE (20 ������)";

		$color_to[0]="���";
		$color_to[1]="��";

		$active_to[0]="���";
		$active_to[1]="��";

		$gotosite_to[0]="���";
		$gotosite_to[1]="��";

		$mailsre_to[0]="�������� ��� ��������� 1 ��� � �����";
		$mailsre_to[1]="�������� ��� ��������� ������ 24 ����";


		echo '<form action="" method="post">';
			echo '<input type="hidden" name="id_pay" value="'.$id_zakaz.'">';
			echo '<table style="width:900px;">';
				echo '<tr><th colspan="2">��������������� ��������</th></tr>';
				echo '<tr><td width="180px">����� �:</td><td class="left">'.$id_zakaz.'</td></tr>';
				echo '<tr><td>��������� ������</td><td>'.$title.'</td></tr>';
				echo '<tr><td>URL �����</td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
				echo '<tr><td>���������� ������</td><td>'.$description_to.'</td></tr>';
				echo '<tr><td>����������� ������ � ������:</td><td>'.$question.'</td></tr>';
				echo '<tr><td>������� ������ <span style="color: #009125;">(����������)</span></td><td>'.$answer_t.'</td></tr>';
				echo '<tr><td>������� ������ <span style="color: #FF0000;">(������)</span></td><td>'.$answer_f_1.'</td></tr>';
				echo '<tr><td>������� ������ <span style="color: #FF0000;">(������)</span></td><td>'.$answer_f_2.'</td></tr>';
				echo '<tr><td>���������� ����������</td><td>'.$plan.'</td></tr>';
				echo '<tr><td>�������� ����</td><td>'.$tarif_to[$tarif].'</td></tr>';
				echo '<tr><td>��������� ������</td><td>'.$color_to[$color].'</td></tr>';
				echo '<tr><td>�������� ����</td><td>'.$active_to[$active].'</td></tr>';
				echo '<tr><td>����������� ������� �� ����</td><td>'.$gotosite_to[$gotosite].'</td></tr>';
				echo '<tr><td>���������� ��������� ������</td><td>'.$mailsre_to[$mailsre].'</td></tr>';
				echo '<tr><td colspan="2"><div align="center"><input type="submit" class="sub-blue160" name="submitall" value="��������"></td></tr>';
			echo '</table><br/>';
		echo '</form>';

		exit();
	}
}
?>

<script type="text/javascript" language="JavaScript"> 
	function SbmForm() {
	        if (document.forms['formzakaz'].title.value == '') {
        	    alert('�� �� ������� ��������� ������');
	            document.forms['formzakaz'].title.focus();
        	    return false;
	        }
		if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')) {
			alert('�� �� ������� URL-����� �����');
			document.forms["formzakaz"].url.focus();
			return false;
		}
	        if (document.forms['formzakaz'].description.value == '') {
        	    alert('�� �� ������� ���������� ������');
	            document.forms['formzakaz'].description.focus();
        	    return false;
	        }
	        if (document.forms['formzakaz'].question.value == '') {
        	    alert('�� �� ������� ����������� ������');
	            document.forms['formzakaz'].question.focus();
        	    return false;
	        }
	        if (document.forms['formzakaz'].answer_t.value == '') {
        	    alert('�� �� ������� ������ �����');
	            document.forms['formzakaz'].answer_t.focus();
        	    return false;
	        }
	        if (document.forms['formzakaz'].answer_f_1.value == '') {
        	    alert('�� �� ������� ������ ����� �1');
	            document.forms['formzakaz'].answer_f_1.focus();
        	    return false;
	        }
	        if (document.forms['formzakaz'].answer_f_2.value == '') {
        	    alert('�� �� ������� ������ ����� �2');
	            document.forms['formzakaz'].answer_f_2.focus();
        	    return false;
	        }
		if ((document.forms["formzakaz"].plan.value == '')|(document.forms["formzakaz"].plan.value < <?=$min_mails;?>)) {
			alert('�� �� ������� ���������� ����������, ������� <?=$min_mails;?>');
			document.forms["formzakaz"].plan.focus();
			return false;
		}
		document.forms["formzakaz"].submit();
		return true;
	}

	function descchange() {
		var description = gebi('description').value;
		if(description.length > 5000) {
			gebi('description').value = description.substr(0,5000);
			gebi('count').innerHTML = '�������� <b>0</b> ��������';
		} else {
			gebi('count').innerHTML = '�������� <b>'+(5000-description.length)+'</b> ��������';
		}
	}

	function InsertTags(text1, text2, descId) {
		var textarea = gebi(descId);
		 if (typeof(textarea.selectionStart) != "undefined") {
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

</script><?php

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th class="top" width="250">��������</a>';
		echo '<th class="top">��������</a>';
	echo '</thead></tr>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left"><b>��������� ������</b></td>';
			echo '<td align="left"><input class="ok" name="title" maxlength="255" value="'.$title.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>URL �����</b></td>';
			echo '<td align="left"><input class="ok" name="url" maxlength="160" value="'.$url.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>���������� ������ &darr;</b></td>';
			echo '<td align="left">';
				echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
				echo '<span id="count" style="display: block; float:right; color:#696969; margin-top:3px; margin-right:3px;"></span>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2" align="left">';
				echo '<textarea name="description" id="description" class="ok" style="height:150px; width:99%;" onkeydown="this.style.background=\'#FFFFFF\';" onkeyup="descchange();">'.$description.'</textarea>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>����������� ������ � ������</b></td>';
			echo '<td align="left"><input class="ok" name="question" maxlength="255" value="'.$question.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #009125;">(����������)</span></td>';
			echo '<td align="left"><input class="ok" name="answer_t" maxlength="255" value="'.$answer_t.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input class="ok" name="answer_f_1" maxlength="255" value="'.$answer_f_1.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td align="left"><input class="ok" name="answer_f_2" maxlength="255" value="'.$answer_f_2.'" type="text" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>���������� ����������</b></td>';
			echo '<td align="left"><input class="ok" name="plan" id="plan" maxlength="7" value="'.$plan.'" type="text" autocomplete="off" onChange="obsch();" onKeyUp="obsch();" ></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� ����</b></td>';
			echo '<td align="left">';
				echo '<select name="tarif" id="tarif" onChange="obsch();" onClick="obsch();" >';
					echo '<option value="1" '.("1" == $tarif ? 'selected="selected"' : false).'>VIP (60 ������)</option>';
					echo '<option value="2" '.("2" == $tarif ? 'selected="selected"' : false).'>STANDART (40 ������)</option>';
					echo '<option value="3" '.("3" == $tarif ? 'selected="selected"' : false).'>LITE (20 ������)</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">�������� ������</td>';
			echo '<td align="left">';
				echo '<select name="color" id="color" onChange="obsch();" onClick="obsch();" >';
					echo '<option value="0" '.("0" == $color ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $color ? 'selected="selected"' : false).'>��</option>';
				echo '</select>';
		        echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">�������� ����</td>';
			echo '<td align="left">';
				echo '<select name="active" id="active" onChange="obsch();" onClick="obsch();" >';
					echo '<option value="0" '.("0" == $active ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $active ? 'selected="selected"' : false).'>��</option>';
				echo '</select>';
		        echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">����������� ������� �� ����</td>';
			echo '<td align="left">';
				echo '<select name="gotosite" id="gotosite" onChange="obsch();" onClick="obsch();" >';
					echo '<option value="0" '.("0" == $gotosite ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $gotosite ? 'selected="selected"' : false).'>��</option>';
				echo '</select>';
		        echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>���������� ��������� ������</b></td>';
			echo '<td align="left">';
				echo '<select name="mailsre" >';
					echo '<option value="0" '.("0" == $mailsre ? 'selected="selected"' : false).'>�������� ��� ��������� 1 ��� � �����</option>';
					echo '<option value="1" '.("1" == $mailsre ? 'selected="selected"' : false).'>�������� ��� ��������� ������ 24 ����</option>';
				echo '</select>';
		        echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2" align="center"><input type="submit" value="���������" class="sub-blue160" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';

?>

<script language="JavaScript"> descchange(); </script>