<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������� � �������������� ��������� �����</b></h3>';
mysql_query("UPDATE `tb_ads_mails` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND `totals`<'1' ") or die(mysql_error());

$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[10] = "����. ����";

$metode = false;
$search = false;
$operator = 0;
$WHERE_ADD = false;
$WHERE_ADD_to_get = false;

if(isset($_POST["search"]) && isset($_POST["metode"])) {
	$metode = isset($_POST["metode"]) ? mysql_real_escape_string(trim($_POST["metode"])) : false;
	$search = isset($_POST["search"]) ? mysql_real_escape_string(trim($_POST["search"])) : false;
	$operator = isset($_POST["operator"]) ? intval(mysql_real_escape_string(trim($_POST["operator"]))) : 0;

	if($metode != "" && $search != false) {
		if($operator == "0") {
			$WHERE_ADD = " AND `$metode`='$search'";
		}else{
			$WHERE_ADD = " AND `$metode` LIKE '%$search%'";
		}
		$WHERE_ADD_to_get = "&metode=$metode&operator=$operator&search=$search";
	}
}
if(isset($_GET["search"]) && isset($_GET["metode"])) {
	$metode = isset($_GET["metode"]) ? mysql_real_escape_string(trim($_GET["metode"])) : false;
	$search = isset($_GET["search"]) ? mysql_real_escape_string(trim($_GET["search"])) : false;
	$operator = isset($_GET["operator"]) ? intval(mysql_real_escape_string(trim($_GET["operator"]))) : false;

	if($metode != "" && $search != false) {
		if($operator == "0") {
			$WHERE_ADD = " AND `$metode`='$search'";
		}else{
			$WHERE_ADD = " AND `$metode` LIKE '%$search%'";
		}
		$WHERE_ADD_to_get = "&metode=$metode&operator=$operator&search=$search";
	}
}

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_mails` WHERE `status`>'0' $WHERE_ADD");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
$min_mails = mysql_result($sql,0,0);

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace("'","&#039;",$mensaje);
	$mensaje = str_replace("`","&#096;",$mensaje);
	$mensaje = str_replace("$","&#036;",$mensaje);
	$mensaje = str_replace('"',"&#034;",$mensaje);
	$mensaje = str_replace("  "," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	$mensaje = str_replace("&amp;amp;","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	return $mensaje;
}

if(isset($_GET["option"])) {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$option = (isset($_GET["option"])) ? limpiar($_GET["option"]) : false;

	if($option=="edit") {

		if(count($_POST)>0) {
			$id = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? intval(limpiarez(trim($_POST["id"]))) : false;
			$merch_tran_id = ( isset($_POST["merch_tran_id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["merch_tran_id"])) ) ? intval(limpiarez(trim($_POST["merch_tran_id"]))) : false;
			$wmid = (isset($_POST["wmid"]) && preg_match("|^[\d]{12}$|", trim($_POST["wmid"]))) ? limpiarez(trim($_POST["wmid"])) : false;
			$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),255) : false;
			$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]),255) : false;
			$description = (isset($_POST["description"])) ? limitatexto(trim($_POST["description"]),5000) : false;
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
			$black_url = @getHost($url);

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
			}elseif($url==false || (substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
				echo '<span class="msg-error">������! �� ��������� ���� URL ������.</span><br>';
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
				$save = "ok";

				$sql_totals = mysql_query("SELECT `plan`,`totals` FROM `tb_ads_mails` WHERE `id`='$id'");
				if(mysql_num_rows($sql_totals)>0) {
					$row_totals = mysql_fetch_array($sql_totals);
					$plan_table = $row_totals["plan"];
					$totals_table = $row_totals["totals"];

					if($plan==$plan_table) {
						$new_plan = $plan;
						$new_totals = $totals_table;
					}elseif($plan>$plan_table) {
						$new_plan = $plan;
						$new_totals = $totals_table + ($plan - $plan_table);
					}elseif($plan<$plan_table) {
						echo '<span class="msg-error">���������� ��������� ������ ���������.</span><br>';
						exit();
					}else{
						$new_plan = $plan;
						$new_totals = $totals_table;
					}

					mysql_query("UPDATE `tb_ads_mails` SET `title`='$title',`url`='$url',`description`='$description',`question`='$question',`answer_t`='$answer_t',`answer_f_1`='$answer_f_1',`answer_f_2`='$answer_f_2',`plan`='$new_plan',`totals`='$new_totals',`tarif`='$tarif',`color`='$color',`active`='$active',`gotosite`='$gotosite',`mailsre`='$mailsre' WHERE `id`='$id'") or die(mysql_error());
				}

				echo '<span id="info-msg" class="msg-ok">��������� ������� ���������.</span>';

				echo '<script type="text/javascript">
					setTimeout(function() {
						window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
					}, 1550);
					HideMsg("info-msg", 1500);
				</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
			}
		}

		if(!isset($save)) {

			?><script type="text/javascript" language="JavaScript"> 
			function SbmFormB() {
		        	if (document.forms['formzakaz'].title.value == '') {
	        		    alert('�� �� ������� ��������� ������');
			            document.forms['formzakaz'].title.focus();
	        		    return false;
		        	}
				if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')) {
					alert('�� �� ������� ����� ������. URL ������ ���������� � �������� �http://� ��� �https://�.');
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
		        	    alert('�� �� ������� ������� ������ (����������)');
			            document.forms['formzakaz'].answer_t.focus();
	        		    return false;
			        }
			        if (document.forms['formzakaz'].answer_f_1.value == '') {
	        		    alert('�� �� ������� ������� ������ (������)');
			            document.forms['formzakaz'].answer_f_1.focus();
	        		    return false;
		        	}
			        if (document.forms['formzakaz'].answer_f_2.value == '') {
	        		    alert('�� �� ������� ������� ������ (������)');
		        	    document.forms['formzakaz'].answer_f_2.focus();
		        	    return false;
			        }
				if (document.forms["formzakaz"].plan.value == '' | document.forms["formzakaz"].plan.value < <?=$min_mails;?>) {
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


			$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`>'0' ");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);

				echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
				echo '<table class="tables">';
				echo '<thead><tr>';
					echo '<th class="top" width="250">��������</a>';
					echo '<th class="top">��������</a>';
				echo '</thead></tr>';
				echo '<tbody>';
				echo '<tr>';
					echo '<td width="160"><b>�</b></td>';
					echo '<td><input type="hidden" name="id" value="'.$row["id"].'">'.$row["id"].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td><b>���� �</b></td>';
					echo '<td><input type="hidden" name="merch_tran_id" value="'.$row["merch_tran_id"].'">'.$row["merch_tran_id"].'</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>��������� ������</b></td>';
					echo '<td align="left"><input class="ok" name="title" maxlength="255" value="'.$row["title"].'" type="text" ></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>URL �����</b></td>';
					echo '<td align="left"><input class="ok" name="url" maxlength="160" value="'.$row["url"].'" type="text" ></td>';
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
						echo '<textarea name="description" id="description" class="ok" style="height:150px; width:99%;" onkeydown="this.style.background=\'#FFFFFF\';" onkeyup="descchange();">'.$row["description"].'</textarea>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>����������� ������ � ������</b></td>';
					echo '<td align="left"><input class="ok" name="question" maxlength="255" value="'.$row["question"].'" type="text" ></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">������� ������ <span style="color: #009125;">(����������)</span></td>';
					echo '<td align="left"><input class="ok" style="color: #009125;" name="answer_t" maxlength="255" value="'.$row["answer_t"].'" type="text" ></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
					echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_1" maxlength="255" value="'.$row["answer_f_1"].'" type="text" ></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">������� ������ <span style="color: #FF0000;">(������)</span></td>';
					echo '<td align="left"><input class="ok" style="color: #FF0000;" name="answer_f_2" maxlength="255" value="'.$row["answer_f_2"].'" type="text" ></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>���������� ���������:</b></td>';
					echo '<td align="left">';
						echo '<input class="ok12" name="plan" id="plan" maxlength="7" value="'.$row["plan"].'" type="text" autocomplete="off" onKeyUp="obsch();" >';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>����� ���������</b></td>';
					echo '<td align="left"><div id="bl2">';
						echo '<select name="tarif" id="tarif" >';
							echo '<option value="1" '.($row["tarif"] == "1" ? 'selected="selected"' : false).'>60 ������</option>';
							echo '<option value="2" '.($row["tarif"] == "2" ? 'selected="selected"' : false).'>40 ������</option>';
							echo '<option value="3" '.($row["tarif"] == "3" ? 'selected="selected"' : false).'>20 ������</option>';
						echo '</select>';
					echo '</div><div id="bl21" style="text-align:left;"></div></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">�������� ������</td>';
					echo '<td align="left"><div id="bl3">';
						echo '<select name="color" id="color" >';
							echo '<option value="0" '.($row["color"] == "0" ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.($row["color"] == "1" ? 'selected="selected"' : false).'>��</option>';
						echo '</select>';
				        echo '</div><div id="bl31" style="text-align:left;"></div></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">�������� ����</td>';
					echo '<td align="left"><div id="bl4">';
						echo '<select name="active" id="active" >';
							echo '<option value="0" '.($row["active"] == "0" ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.($row["active"] == "1" ? 'selected="selected"' : false).'>��</option>';
						echo '</select>';
				        echo '</div><div id="bl41" style="text-align:left;"></div></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left">����������� ������� �� ����</td>';
					echo '<td align="left"><div id="bl5">';
						echo '<select name="gotosite" id="gotosite" >';
							echo '<option value="0" '.($row["gotosite"] == "0" ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.($row["gotosite"] == "1" ? 'selected="selected"' : false).'>��</option>';
						echo '</select>';
				        echo '</div><div id="bl51" style="text-align:left;"></div></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="left"><b>���������� ��������� ������</b></td>';
					echo '<td align="left"><div id="bl6">';
						echo '<select name="mailsre" >';
							echo '<option value="1" '.($row["mailsre"] == "1" ? 'selected="selected"' : false).'>�������� ��� ��������� ������ 24 ����</option>';
							echo '<option value="0" '.($row["mailsre"] == "0" ? 'selected="selected"' : false).'>�������� ��� ��������� 1 ��� � �����</option>';
						echo '</select>';
				        echo '</div><div id="bl61" style="text-align:left;"></div></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="2" align="center"><input type="submit" value="���������" class="sub-blue160" /></td>';
				echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</form>';

			?><script language="JavaScript"> descchange(); </script><?php

			}else{
				echo '<span class="msg-error">������� �� �������.</span>';
			}
			exit();
		}
	}

	if($option=="dell") {
		$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`>'0'");
		if(mysql_num_rows($sql)>0) {
			mysql_query("DELETE FROM `tb_ads_mails` WHERE `id`='$id'") or die(mysql_error());

			echo '<span id="info-msg" class="msg-error">������� ������� �������!</span>';
		}

		echo '<script type="text/javascript">
			setTimeout(function() {
				window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");
			}, 1550);
			HideMsg("info-msg", 1500);
		</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'"></noscript>';
	}
}

$sql_ads = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`>'0' $WHERE_ADD ORDER BY `id` DESC LIMIT $start_pos, $perpage") or die(mysql_error());
$all_ads = mysql_num_rows($sql_ads);

echo '<table class="adv-cab" style="margin:0; padding:0; margin-bottom:1px;"><tr>';
echo '<td align="left" width="230" valign="middle" style="border-right:solid 1px #DDDDDD;">';
	if($WHERE_ADD=="") {
		echo '�����: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_ads.'</b> �� <b>'.$count.'</b>';
	}else{
	 	echo '�������: <b>'.$count.'</b><br>�������� ������� �� ��������: <b>'.$all_ads.'</b> �� <b>'.$count.'</b>';
	}
echo '</td>';
echo '<td align="center" valign="middle" style="border-left:solid 2px #FFFFFF;">';
	echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'" id="newform">';
	echo '<table class="adv-cab" style="width:auto; margin:0; padding:0;">';
	echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="60" style="margin:0; padding:2px; border:none;"><b>����� ��:</b></td>';
		echo '<td nowrap="nowrap" width="100" align="center" style="margin:0; padding:2px; border:none;">';
			echo '<select name="metode" style="text-align:left; padding-left:3px;">';
				echo '<option value="id" '.("id" == $metode ? 'selected="selected"' : false).'>ID</option>';
				echo '<option value="merch_tran_id" '.("merch_tran_id" == $metode ? 'selected="selected"' : false).'>� �����</option>';
				echo '<option value="status" '.("status" == $metode ? 'selected="selected"' : false).'>������</option>';
				echo '<option value="wmid" '.("wmid" == $metode ? 'selected="selected"' : false).'>WMID</option>';
				echo '<option value="username" '.("username" == $metode ? 'selected="selected"' : false).'>�����</option>';
			echo '</select>';
		echo '</td>';

		echo '<td nowrap="nowrap" width="100" align="center" style="margin:0; padding:2px; border:none;">';
			echo '<select name="operator" style="text-align:center;">';
				echo '<option value="0" '.($operator == "0" ? 'selected="selected"' : false).' style="text-align:center;">=</option>';
				echo '<option value="1" '.($operator == "1" ? 'selected="selected"' : false).' style="text-align:center;">��������</option>';
			echo '</select>';
		echo '</td>';

		echo '<td nowrap="nowrap" width="135" align="center" style="margin:0; padding:2px; border:none;"><input type="text" class="ok" style="height:18px; text-align:center;" name="search" value="'.$search.'"></td>';
		echo '<td nowrap="nowrap" width="85"  align="center" style="margin:0; padding:3px 0px 2px 6px; border:none;"><input type="submit" value="�����" class="sub-green" style="float:none;"></td>';
	echo '</tr>';

	echo '</table>';
	echo '</form>';
echo '</td>';

echo '<td align="center" width="160">';
	echo '<form method="get" action="">';
		echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
		echo '<input type="submit" value="�������� �����" class="sub-blue160" style="float:none;">';
	echo '</form>';
echo '</td>';

echo '</tr>';
echo '</table>';
echo '<div align="center" style="margin-bottom:20px;">��� ������ �� <b>�������</b> �������: <b>1</b> [��������], <b>2</b> [�� �����], <b>3</b> [��������� �����]</div>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}

echo '<table class="tables" style="margin:1px auto;">';
echo '<thead><tr align="center">';
echo '<tr>';
	echo '<th>������</th>';
	echo '<th>ID ����&nbsp;�</th>';
	echo '<th>WMID �����</th>';
	echo '<th>������ ������</th>';
	echo '<th>����������</th>';
	echo '<th>����������</th>';
	echo '<th>����</th>';
	echo '<th></th>';
echo '</tr></thead>';
echo '<tbody>';

if(mysql_num_rows($sql_ads)>0) {
	while ($row = mysql_fetch_assoc($sql_ads)) {
		echo '<tr align="center">';

		echo '<td align="center" width="30" class="noborder1">';
			echo '<div id="playpauseimg'.$row["id"].'">';
				if($row["status"]=="1") {
					echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				}elseif($row["status"]=="2") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'mails\');"></span>';
				}elseif($row["status"]=="3") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
				}
			echo '</div>';
		echo '</td>';

		echo '<td align="center">'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';
		echo '<td>'.$row["wmid"].'<br>'.$row["username"].'</td>';
		echo '<td align="center">'.$system_pay[$row["method_pay"]].'</td>';

		echo '<td align="left">';
			echo ("1" == $row["tarif"] ? '������: <b>60 ���.</b><br>' : false);
			echo ("2" == $row["tarif"] ? '������: <b>40 ���.</b><br>' : false);
			echo ("3" == $row["tarif"] ? '������: <b>20 ���.</b><br>' : false);

			echo '��������:&nbsp;<b>'.number_format($row["plan"], 0, ".", "'").'</b><br>';
			echo '����������:&nbsp;<b>'.number_format($row["members"], 0, ".", "'").'</b><br>';
			echo '��������:&nbsp;<b>'.number_format($row["totals"], 0, ".", "'").'</b><br>';
		echo '</td>';

		echo '<td align="left" width="320">';
			echo '���������:&nbsp;'.(strlen($row["title"])>40 ? "<b ".($row["color"]=="1" ? 'style="color:#FF0000;"' : false).">".limitatexto($row["title"],40)."</b>...." : "<b ".($row["color"]=="1" ? 'style="color:#FF0000;"' : false).">".$row["title"]."</b>").'</b><br>';
			echo 'URL&nbsp;�����:&nbsp;<a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>40 ? "<b>".limitatexto($row["url"], 40)."</b>...." : "<b>".$row["url"]."</b>").'</a><br>';
			echo '����&nbsp;���������:&nbsp;'.DATE("d.m.Y H:i", $row["date"]).'</b><br>';
			echo 'IP:&nbsp;'.$row["ip"];
		echo '</td>';

		echo '<td align="center">'.number_format($row["money"], 2, ".", " ").' ���.</td>';

		echo '<td width="95">';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="edit">';
				echo '<input type="submit" value="��������" class="sub-green">';
			echo '</form>';
			echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("����������� �������� �������\nID �������: '.$row["id"].'")) return false;\'>';
				echo '<input type="hidden" name="op" value="'.limpiarez($_GET["op"]).'">';
				echo '<input type="hidden" name="page" value="'.$page.'">';
				echo '<input type="hidden" name="id" value="'.$row["id"].'">';
				echo '<input type="hidden" name="option" value="dell">';
				echo '<input type="submit" value="�������" class="sub-red">';
			echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="8"><b>������� �� �������!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) {universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op$WHERE_ADD_to_get");}

?>