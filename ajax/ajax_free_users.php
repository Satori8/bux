<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
//sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
	$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
	return $json_arr;
}

function my_json_encode($ajax_json, $result_text, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
	$mensaje = str_replace("?", "&#063;", $mensaje);
	$mensaje = str_replace("$", "&#036;", $mensaje);

	$mensaje = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mensaje);
	$mensaje = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mensaje);
	$mensaje = preg_replace("'<[^>]*?>.*?'si", "", $mensaje);

	$mensaje = mysql_real_escape_string(trim($mensaje));
//	$mensaje = str_replace("\\", "", $mensaje);

	$mensaje = iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($mensaje), NULL, "CP1251"));
	$mensaje = htmlspecialchars(trim($mensaje), NULL, "CP1251");
	$mensaje = str_replace("  ", " ", $mensaje);
	$mensaje = str_replace("&amp amp ", "&", $mensaje);
	$mensaje = str_replace("&amp;amp;", "&", $mensaje);
	$mensaje = str_replace("&&", "&", $mensaje);
	$mensaje = str_replace("http://http://", "http://", $mensaje);
	$mensaje = str_replace("https://https://", "https://", $mensaje);
	$mensaje = str_replace("&#063;", "?", $mensaje);
	$mensaje = str_replace("&amp;", "&", $mensaje);

	return $mensaje;
}

function InfoWall($id, $username, $wall_com_p, $wall_com_o) {
	$wall_com = ($wall_com_p - $wall_com_o);
	if($wall_com > 0) {
		$wall_com = '<b style="color:#008000;">'.$wall_com.'</b>';
	}elseif($wall_com < 0) {
		$wall_com = '<b style="color:#FF0000;">-'.abs($wall_com).'</b>';
	}else{
		$wall_com = '<b>0</b>';
	}
	return '<a href="/wall?uid='.$id.'" target="_blank" style="text-decoration:none;"><img src="img/wall20.png" title="������� �� ����� ������������ '.$username.'" width="16" height="16" border="0" align="absmiddle" style="margin-left:3px;" />&nbsp;'.$wall_com.'</a>';
}

function InfoReit($reiting) {
	return '<img src="img/reiting.png" border="0" alt="" align="absmiddle" title="�������" style="margin:0; padding:0;" />&nbsp;<span style="color:#2E8B57;">'.round($reiting, 2).'</span>';
}

function InfoStatus($reiting){
	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='".$reiting."' AND `r_do`>='".floor($reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_assoc($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_assoc($sql_rang);
	}
	return '<span style="cursor:help; color: #006699; font-weight:normal;" title="������">'.$row_rang["rang"].'</span>';
}

function ShowHideList($user_freeus, $cena_hide_free_users){
	$text = "";
	$text.= '<span id="adv-title-info-sh" class="adv-title-'.((isset($_COOKIE["fu_spoiler_sh"]) && filter_var($_COOKIE["fu_spoiler_sh"], FILTER_VALIDATE_INT)) ? "open" : "close").'" onclick="ShowHideBlock(\'-info-sh\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:0px;">������ ���� � ������ ���������</span>';
	$text.= '<div id="adv-block-info-sh" style="display:'.((isset($_COOKIE["fu_spoiler_sh"]) && filter_var($_COOKIE["fu_spoiler_sh"], FILTER_VALIDATE_INT)) ? "block" : "none").'; padding:5px 7px 10px 7px; text-align:justify; background-color:#F0F8FF;">';
		$text.= '��� ������� ��������� ��� ������ ���� �� ������ ��������� �������������. ';
		$text.= '����������� ��� ������ �� ������ ��������� ������������� ���� ���� � �� ��������� ��� ����� <a href="//'.$_SERVER["HTTP_HOST"].'/tos.php" target="_blank">������ ������� - 1.8</a><br>';
		$text.= '��������� ������ ���������� <b>'.$cena_hide_free_users.'</b> ���. ��� ����� ����� ������� � ���������� �����.<br>';
		if($user_freeus == 0) {
			$text.= '<div align="center"><span class="news_comment" onclick="if(!confirm(\'�� ������������� ������ �� ������� � ����� ��������� �������������?\n�������� ������ '.$cena_hide_free_users.' ���. \')) {return false;}else{ShowHideList(); return false;}" style="float:none;">'.($user_freeus==0 ? "������ ���� � ������" : "�������� ���� � ������").'</span></div>';
		}else{
			$text.= '<div align="center"><span class="news_comment" onclick="if(!confirm(\'�� ������������� ����������� ���� � ����� ��������� �������������?\')) {return false;}else{ShowHideList(); return false;}" style="float:none;">'.($user_freeus==0 ? "������ ���� � ������" : "�������� ���� � ������").'</span></div>';
		}
	$text.= '</div>';
	return $text;
}

function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
	$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
	$message_text = false;
	$errfile = str_replace(ROOT_DIR, "", $errfile);
	switch($errno) {
		case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
		case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
		case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
		default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
	}
	$message_text = "$message_text";
	exit(my_json_encode($ajax_json, "ERROR", $message_text));
}
$set_error_handler = set_error_handler("myErrorHandler", E_ALL);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");
		require(ROOT_DIR."/ajax/ajax_navigator/ajax_navigator.php");

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? htmlentities(stripslashes(trim($_SESSION["userPas"]))) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$page = ( isset($_POST["page"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["page"]))) ) ? htmlspecialchars(trim($_POST["page"])) : 0;
		$my_lastiplog = getRealIP();

		$sql_user = mysql_query("SELECT `id`,`username`,`money_rb`,`referer`,`freeus` FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user["id"];
			$user_name = $row_user["username"];
			$user_money_rb = $row_user["money_rb"];
			$user_referer = $row_user["referer"];
			$user_freeus = $row_user["freeus"];
		}else{
			$result_text = "ERROR"; $message_text = "������������ �� ���������������.";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_hide_free_users'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		$cena_hide_free_users = number_format(mysql_result($sql,0,0), 2, ".", "");

		$attestat[100] = '<img src="/img/att/att_100.ico"  alt="" title="�������� ����������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[110] = '<img src="/img/att/att_110.ico"  alt="" title="���������� ��������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[120] = '<img src="/img/att/att_120.ico"  alt="" title="��������� ��������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[130] = '<img src="/img/att/att_130.ico"  alt="" title="������������ ��������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[135] = '<img src="/img/att/att_135.ico"  alt="" title="�������� ��������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[136] = '<img src="/img/att/att_136.ico"  alt="" title="�������� Capitaller" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[1]   = '<img src="/img/att/att_1.ico"    alt="" title="�������� ���������� ��������" align="absmiddle" width="16" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[140] = '<img src="/img/att/att_140.ico"  alt="" title="�������� ������������" width="16" align="absmiddle" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[150] = '<img src="/img/att/att_150.ico"  alt="" title="�������� ������������" width="16" align="absmiddle" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[170] = '<img src="/img/att/att_170.ico"  alt="" title="�������� ������� WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[190] = '<img src="/img/att/att_190.ico"  alt="" title="�������� ������� WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';
		$attestat[300] = '<img src="/img/att/att_300.ico"  alt="" title="�������� ��������� WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; margin-right:3px; border:none;" />';

		if($option == "FreeUsers") {
			$sort_param_arr = array('id','visits','visits_tests','visits_m','visits_t','reiting','referals','referals2','referals3','kol_log','wall_com_p','lastlogdate2');
			$sort_param = ( isset($_COOKIE["sort_param"]) && array_search(htmlspecialchars(trim($_COOKIE["sort_param"])), $sort_param_arr)!==false ) ? htmlspecialchars(trim($_COOKIE["sort_param"])) : $sort_param_arr[0];
			$sort_table = "ORDER BY `$sort_param` DESC";
			$message_text = "";

			$perpage = 20;
			$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='' AND `freeus`='0' AND `ban_date`='0'")) or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			$pages_count = ceil($count / $perpage);
			if ($page > $pages_count) $page = $pages_count;
			if ($page <= 0) $page = 1;
			$start_pos = intval(($page-1) * $perpage);
			SETCOOKIE("fu_page", $page, (time()+30*24*60*60), "/", $_SERVER["HTTP_HOST"]);

			if($user_referer == false) {
				$message_text.= '<div id="InfoSHList" style="display:block; align:justify; margin-bottom:15px;">';
					$message_text.= ShowHideList($user_freeus, $cena_hide_free_users);
				$message_text.= '</div>';
			}

			if($count>1) {
				$message_text.= '<div align="center"><form id="newform"><b>����������� ��:</b> <select id="sort_param" onChange="SortFU();" class="ok" style="width:auto;">';
				$message_text.= '<optgroup label="���������">';
					$message_text.= '<option value="visits" '.($sort_param=="visits" ? 'selected="selected"' : false).'>���������� ������ � ��������</option>';
					$message_text.= '<option value="visits_tests" '.($sort_param=="visits_tests" ? 'selected="selected"' : false).'>���������� ���������� ������</option>';
					$message_text.= '<option value="visits_m" '.($sort_param=="visits_m" ? 'selected="selected"' : false).'>���������� ����������� �����</option>';
					$message_text.= '<option value="visits_t" '.($sort_param=="visits_t" ? 'selected="selected"' : false).'>���������� ����������� �������</option>';
				$message_text.= '</optgroup>';
				$message_text.= '<optgroup label="��������">';
					$message_text.= '<option value="referals" '.($sort_param=="referals" ? 'selected="selected"' : false).'>�� ���������� ��������� I ������</option>';
					$message_text.= '<option value="referals2" '.($sort_param=="referals2" ? 'selected="selected"' : false).'>�� ���������� ��������� II ������</option>';
					$message_text.= '<option value="referals3" '.($sort_param=="referals3" ? 'selected="selected"' : false).'>�� ���������� ��������� III ������</option>';
				$message_text.= '</optgroup>';
				$message_text.= '<optgroup label="������">';
					$message_text.= '<option value="id" '.($sort_param=="id" ? 'selected="selected"' : false).'>id ������������</option>';
					$message_text.= '<option value="reiting" '.($sort_param=="reiting" ? 'selected="selected"' : false).'>���������� ������ �������� (�� �������)</option>';
					$message_text.= '<option value="lastlogdate2" '.($sort_param=="lastlogdate2" ? 'selected="selected"' : false).'>���� ����������</option>';
					$message_text.= '<option value="wall_com_p" '.($sort_param=="wall_com_p" ? 'selected="selected"' : false).'>���������� ������� �� �����</option>';
				$message_text.= '</optgroup>';
				$message_text.= '</select></form></div>';
			}

			if($count>$perpage) $message_text.= universal_link_bar($count, $page, "/free_users", $perpage, 7, "", "");
			$message_text.= '<table class="tables_inv" style="margin:1px auto;">';
			$message_text.= '<thead><tr align="center">';
				$message_text.= '<th colspan="2">������������</th>';
				$message_text.= '<th width="140">����������</th>';
				$message_text.= '<th width="150">����������</th>';
				$message_text.= '<th width="100">��������</th>';
			$message_text.= '</tr></thead>';
			$message_text.= '<tbody>';

			$sql_fu = mysql_query("SELECT 
				`id`,`username`,`joindate`,`lastlogdate2`,`referals`,`referals2`,`referals3`,
				`reiting`,`avatar`,`attestat`,`country_cod`,`wall_com_p`,`wall_com_o`,
				`visits`,`visits_t`,`visits_tests`,`visits_m`
				FROM `tb_users` WHERE `referer`='' AND `freeus`='0' AND `ban_date`='0' $sort_table LIMIT $start_pos, $perpage") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql_fu)>0) {
				while ($row_fu = mysql_fetch_assoc($sql_fu)) {
					$message_text.= '<tr align="center">';

						$message_text.= '<td align="center" valign="middle" width="45px" style="border-right:none; padding-top:8px;">';
							$message_text.= '<img class="avatar" src="/avatar/'.$row_fu["avatar"].'" style="width:45px; height:45px;" border="0" alt="avatar" title="avatar" />';
						$message_text.= '</td>';

						$message_text.= '<td align="left" valign="middle" style="border-left:none; padding-left:0px;">';
							$message_text.= '<b style="cursor:help; color:#0080EC; text-shadow:0 1px 0 #FFF,1px 2px 2px #AAA;" title="�����">'.$row_fu["username"].'</b><br>';
							$message_text.= InfoStatus($row_fu["reiting"])."<br>";
							$message_text.= isset($attestat[$row_fu["attestat"]]) ? $attestat[$row_fu["attestat"]] : false;
							if(trim($row_fu["country_cod"]) != false) {
								$message_text.= '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower(trim($row_fu["country_cod"])).'.gif" alt="" title="'.get_country(trim($row_fu["country_cod"])).'" width="16" height="11" style="margin:0; padding:0;" align="absmiddle" />';
							}
							$message_text.= InfoWall($row_fu["id"], $row_fu["username"], $row_fu["wall_com_p"], $row_fu["wall_com_o"]);
							$message_text.= '<a href="/newmsg.php?name='.$row_fu["username"].'" target="_blank"><img src="/img/mail.gif" border="0" align="absmiddle" alt="" title="�������� ��������� ������������ '.$row_fu["username"].'" style="margin:0; padding:0; margin-left:3px;" /></a>';
						$message_text.= '</td>';

						$message_text.= '<td>';
							$message_text.= '<table class="tables_inv" style="margin:0; padding:0;"><tr>';
								$message_text.= '<tr><td align="left" style="border:none; padding:0; margin:0; height:16px;">�������</td><td align="right" style="border:none; padding:0; margin:0; height:16px;">'.number_format($row_fu["visits"],0,".","'").'</td></tr>';
								$message_text.= '<tr><td align="left" style="border:none; padding:0; margin:0; height:16px;">������</td><td align="right" style="border:none; padding:0; margin:0; height:16px;">'.number_format($row_fu["visits_m"],0,".","'").'</td></tr>';
								$message_text.= '<tr><td align="left" style="border:none; padding:0; margin:0; height:16px;">�����</td><td align="right" style="border:none; padding:0; margin:0; height:16px;">'.number_format($row_fu["visits_tests"],0,".","'").'</td></tr>';
								$message_text.= '<tr><td align="left" style="border:none; padding:0; margin:0; height:16px;">�������</td><td align="right" style="border:none; padding:0; margin:0; height:16px;">'.number_format($row_fu["visits_t"],0,".","'").'</td></tr>';
			        			$message_text.= '</table>';
						$message_text.= '</td>';

						$message_text.= '<td>';
							if($row_fu["lastlogdate2"] == 0) {
								$message_text.= '<span style="color:#FF0000;">�� ������</span>';
							}elseif(DATE("d.m.Y", $row_fu["lastlogdate2"]) == DATE("d.m.Y")) {
								$message_text.= '<span style="color:green;">�������, '.DATE("� H:i", $row_fu["lastlogdate2"]).'</span>';
							}else{
								$message_text.= '<span>'.DATE("d.m.Y�. � H:i", $row_fu["lastlogdate2"]).'</span>';
							}
						$message_text.= '</td>';

						$message_text.= '<td>'.$row_fu["referals"].'-'.$row_fu["referals2"].'-'.$row_fu["referals3"].'</td>';

					$message_text.= '</tr>';
				}
			}else{
				$message_text.= '<tr align="center"><td colspan="5"><b>��� ��������� �������������</b></td></tr>';
			}
			$message_text.= '</tbody>';
			$message_text.= '</table>';
			if($count>$perpage) $message_text.= universal_link_bar($count, $page, "/free_users", $perpage, 7, "", "");

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));


		}elseif($option == "ShowHideList") {
			$message_text = "";

			if($user_freeus == 1 && $user_referer == false) {
				mysql_query("UPDATE `tb_users` SET `freeus`='0' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','0','����������� � ������ ��������� �������������','OK','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				$user_freeus = 0;
				$message_text.= ShowHideList($user_freeus, $cena_hide_free_users);

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($user_freeus == 0 && $user_referer == false) {
				if($user_money_rb < $cena_hide_free_users) {
					$result_text = "ERROR"; $message_text = "�� ����� ��������� ����� ������������ �������!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}else{
					mysql_query("UPDATE `tb_users` SET `freeus`='1', `money_rb`=`money_rb`-'$cena_hide_free_users' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
					VALUES('1','$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','$cena_hide_free_users','������ �� ������� � ������ ��������� �������������','�������','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					$user_freeus = 1;
					$message_text.= ShowHideList($user_freeus, $cena_hide_free_users);

					$result_text = "OK";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}

			}elseif($user_referer != false) {
				$result_text = "ERROR"; $message_text = "������! �� �� ��������� ��������� �������������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				$result_text = "ERROR"; $message_text = "ERROR: ���-�� ����� �� ���!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}
		}else{
			$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "��� ����������� AJAX �������.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>