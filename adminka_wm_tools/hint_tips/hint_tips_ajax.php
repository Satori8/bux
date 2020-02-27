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

function my_json_encode_page($ajax_json, $result_text, $page, $message_text) {
	return ($ajax_json=="json") ? json_encode_cp1251(array("result" => iconv("CP1251", "UTF-8", $result_text), "page" => intval($page), "ajax_code" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
}

function limpiarez($mensaje) {
	$mensaje = trim($mensaje);
	$mensaje = str_replace("'", "", $mensaje);
	$mensaje = str_replace("`", "", $mensaje);
//	$mensaje = str_replace('"', "&#34;", $mensaje);
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
	if(isset($_SESSION["userLog_a"]) && isset($_SESSION["userPas_a"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");
		require_once(ROOT_DIR."/merchant/func_cache.php");
		require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

		if(function_exists('desc_bb')===false) {
			function desc_bb($desc) {
				if($desc !== false) {
					$desc = new bbcode($desc);
					$desc = $desc->get_html();
					$desc = str_replace("&amp;", "&", $desc);
					return $desc;
				}
			}
		}

		$user_name = (isset($_SESSION["userLog_a"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog_a"]))) ? htmlspecialchars(trim($_SESSION["userLog_a"])) : false;
		$user_pass = (isset($_SESSION["userPas_a"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas_a"]))) ? htmlspecialchars(trim($_SESSION["userPas_a"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
		$my_lastiplog = getRealIP();

		if($option == "AddHint") {
			$title = isset($_POST["title"]) ? limitatexto(limpiarez($_POST["title"]), 30) : false;
			$description = isset($_POST["description"]) ? limpiarez($_POST["description"]) : false;
			$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;

			if($title==false) {
				$result_text = "ERROR"; $message_text = "�� �� ������� ��������� ���������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}elseif($description==false) {
				$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));

			}else{
				mysql_query("INSERT INTO `tb_hint_tips` (`title`,`description`) 
				VALUES('$title','$description')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				cache_hints();

				$result_text = "OK"; $message_text = "��������� ������� ���������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}elseif($option == "DelHint") {
			$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_POST["id"])))) ? intval(trim($_POST["id"])) : false;

			$sql = mysql_query("SELECT `id` FROM `tb_hint_tips` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql)>0) {
				mysql_query("DELETE FROM `tb_hint_tips` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

				cache_hints();

				$result_text = "OK"; $message_text = "��������� �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "��������� �� �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "EditHint") {
			$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_POST["id"])))) ? intval(trim($_POST["id"])) : false;

			$sql = mysql_query("SELECT * FROM `tb_hint_tips` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);

				$message_text = false;
				$message_text.= '<div id="FormHint">';
				$message_text.= '<table class="tables" id="newform">';
				$message_text.= '<thead><tr align="center"><th width="220">��������</th><th>��������</th></thead></tr>';
				$message_text.= '<tbody>';
					$message_text.= '<tr>';
						$message_text.= '<td align="left"><b>��������� ���������</b></td>';
						$message_text.= '<td align="left"><input type="text" id="title" value="'.(isset($row["title"]) ? $row["title"] : false).'" maxlength="30" class="ok" autocomplete="off" placeholder="��������� ���������" onKeyDown="$(this).attr(\'class\', \'ok\');" /></td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td colspan="2"><b>����� ��������� &darr;</b></td>';
					$message_text.= '</tr>';
					$message_text.= '<tr>';
						$message_text.= '<td colspan="2">';
							$message_text.= '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
							$message_text.= '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
							$message_text.= '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
							$message_text.= '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
							$message_text.= '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
							$message_text.= '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
							$message_text.= '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
							$message_text.= '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
							$message_text.= '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
							$message_text.= '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:7px;">�������� ��������: 500</span>';
							$message_text.= '<br>';
							$message_text.= '<div style="display: block; clear:both; padding-top:4px">';
								$message_text.= '<textarea id="description" class="ok" style="height:120px; width:99.2%;" placeholder="����� ���������" onKeyup="descchange(\'1\', this, \'500\');" onKeydown="descchange(\'1\', this, \'500\'); $(this).attr(\'class\', \'ok\');" onClick="descchange(\'1\', this, \'500\');">'.(isset($row["description"]) ? $row["description"] : false).'</textarea>';
							$message_text.= '</div>';
						$message_text.= '</td>';
					$message_text.= '</tr>';
				$message_text.= '</tbody>';
				$message_text.= '</table>';
				$message_text.= '<div align="center"><span onClick="SaveHint(\''.$row["id"].'\');" class="sub-blue160" style="float:none; width:160px;">��������� ���������</span></div>';
				$message_text.= '</div>';
				$message_text.= '<div id="info-msg-hint" style="display:none;"></div>';

				$result_text = "OK";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}else{
				$result_text = "ERROR"; $message_text = "��������� �� �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}


		}elseif($option == "SaveHint") {
			$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_POST["id"])))) ? intval(trim($_POST["id"])) : false;
			$title = isset($_POST["title"]) ? limitatexto(limpiarez($_POST["title"]), 30) : false;
			$description = isset($_POST["description"]) ? limpiarez($_POST["description"]) : false;
			$description = get_magic_quotes_gpc() ? stripslashes($description) : $description;

			$sql = mysql_query("SELECT * FROM `tb_hint_tips` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);

				if($title==false) {
					$result_text = "ERROR"; $message_text = "�� �� ������� ��������� ���������!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}elseif($description==false) {
					$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������!";
					exit(my_json_encode($ajax_json, $result_text, $message_text));

				}else{
					mysql_query("UPDATE `tb_hint_tips` SET `title`='$title', `description`='$description' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

					cache_hints();

					$sql = mysql_query("SELECT * FROM `tb_hint_tips` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
					if(mysql_num_rows($sql)>0) {
						$row = mysql_fetch_assoc($sql);
						$description = $row["description"];
					}else{
						$description = $description;
					}

					$message_text = false;
					$message_text.= '<div style="padding:1px 5px; display:block; margin-top:3px; margin-bottom:3px; color:#008B8B; font-size:13px; font-weight:bold;">'.$row["title"].'</div>';
					$message_text.= '<div style="padding:1px 5px; display:block; font-size:12px;">'.desc_bb($row["description"]).'</div>';
					$message_text.= '<div style="margin-top:0px;">';
						$message_text.= '<span class="adv-dell" onClick="DelHint(\''.$row["id"].'\');" title="������� ���������"></span>';
						$message_text.= '<span class="adv-edit" onClick="EditHint(\''.$row["id"].'\');" title="������������� ���������"></span>';
					$message_text.= '</div>';

					$result_text = "OK";
					exit(my_json_encode($ajax_json, $result_text, $message_text));
				}
			}else{
				$result_text = "ERROR"; $message_text = "��������� �� �������!";
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

		}else{
			$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}else{
		$result_text = "ERROR"; $message_text = "������������ �� ���������������. ���������� ��������������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

$result_text = "ERROR"; $message_text = "��� ����������� AJAX �������.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>