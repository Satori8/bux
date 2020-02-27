<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$js_result = array("result" => "ERROR", "message" => "Access denied!");
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
sleep(0);

function json_encode_cp1251($json_arr) {
	$json_arr = json_encode($json_arr);
	$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042b", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
	$arr_replace_utf = array("À", "à", "Á", "á", "Â", "â", "Ã", "ã", "Ä", "ä", "Å", "å", "¨", "¸", "Æ","æ","Ç","ç","È","è","É","é","Ê","ê","Ë","ë","Ì","ì","Í","í","Î","î","Ï","ï","Ğ","ğ","Ñ","ñ","Ò","ò","Ó","ó","Ô","ô","Õ","õ","Ö","ö","×","÷","Ø","ø","Ù","ù","Ú","ú","Û","û","Ü","ü","İ","ı","Ş","ş","ß","ÿ");
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
	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/funciones.php");

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", htmlspecialchars(trim($_POST["op"]))) ) ? htmlspecialchars(trim($_POST["op"])) : false;
	$user_id = ( isset($_POST["uid"]) && preg_match("|^[\d]{1,11}$|", htmlspecialchars(trim($_POST["uid"]))) ) ? intval(trim($_POST["uid"])) : false;
	$token_post = (isset($_POST["token"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_POST["token"]))) ? strtolower(limpiarez($_POST["token"])) : false;
	$my_lastiplog = getRealIP();
	$security_key = "TsIi^&uw@if%YST630n(*7p0UFn#*hglkj?";

	if($option == "LoadStat") {
		$message_text = false;
		$week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
		$week_arr_ru = array("Âñ", "Ïí", "Âò", "Ñğ", "×ò", "Ïò", "Ñá");
		$week_ru = array();
		$week_en = array();
		$date_s = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
		$date_e = strtotime(DATE("d.m.Y"));
		$period = (24*60*60);
		$color_arr = array('serf'=>'#508ED3', 'auto_serf'=>'#CD5C5C', 'ban_serf'=>'#FA8072', 'mails'=>'#009126', 'task'=>'#ED5A62', 'tests'=>'#FF8000', 'you'=>'#0000ff', 'autoyoutube_ser'=>'#CD5C5C');
		$types_arr = array('serf'=>'ÑÅĞÔÈÍÃ', 'auto_serf'=>'ÀÂÒÎ-ÑÅĞÔÈÍÃ', 'ban_serf'=>'ÁÀÍÍÅĞÍÛÉ ÑÅĞÔÈÍÃ', 'mails'=>'ÏÈÑÜÌÀ', 'task'=>'ÇÀÄÀÍÈß', 'tests'=>'ÒÅÑÒÛ', 'you'=>'YOUTUBE-ÑÅĞÔÈÍÃ', 'autoyoutube_ser'=>'ÀÂÒÎ-ÑÅĞÔÈÍÃ YOUTUBE');
		$tab_in_arr = array_keys($types_arr);
		$tab_in = implode("', '", $tab_in_arr);
		for($i=$date_s; $i<=$date_e; $i=$i+$period) {
			$week_ru[] = $week_arr_ru[strtolower(DATE("w", $i))];
			$week_en[] = $week_arr_en[strtolower(DATE("w", $i))];
		}
		$data_graff = array();

		$sql_w = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `id`='$user_id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_w)>0) {
			$row_w = mysql_fetch_assoc($sql_w);
			$stat_user_id = $row_w["id"];
			$stat_user_name = $row_w["username"];
			$token_stat = strtolower(md5($stat_user_id.strtolower($stat_user_name).strtolower($user_name)."STAT_USER"));

			if($token_post == false | $token_post != $token_stat) {
				$result_text = "ERROR";
				$message_text = '<span class="msg-error">Îøèáêà äîñòóïà!</span>';
				exit(my_json_encode($ajax_json, $result_text, $message_text));
			}

			for($i=0; $i<count($tab_in_arr); $i++) {
				$StatUser[$tab_in_arr[$i]] = mysql_fetch_assoc(mysql_query("SELECT * FROM `tb_users_stat` WHERE `username`='$stat_user_name' AND `type`='".$tab_in_arr[$i]."'"));

				for($y=0; $y<count($week_en); $y++) {
					$data_graff[$tab_in_arr[$i]][] = isset($StatUser[$tab_in_arr[$i]][$week_en[$y]]) ? $StatUser[$tab_in_arr[$i]][$week_en[$y]] : 0;
				}
				$data_graff[$tab_in_arr[$i]] = implode(", ", $data_graff[$tab_in_arr[$i]]);
			}
			foreach($data_graff as $key=>$val) {
				$series_arr[] = "{name: '".mb_convert_case($types_arr[$key], MB_CASE_TITLE, "CP1251")."', data: [$val], color: '".$color_arr[$key]."'}";
			}
			$categories = isset($week_ru) ? "'".implode("', '", $week_ru)."'" : false;
			$series = isset($series_arr) ? implode(", ", $series_arr) : false;

			$message_text.= '<div style="width:710px; height:250px; margin:0 auto; background:#F7F7F7;" id="WallChart"></div>';
			$message_text.= "<script type=\"text/javascript\" language=\"JavaScript\">
			Highcharts.chart(\"WallChart\", {
				chart: 	{ backgroundColor: false, type: 'line', width: 710, height: 250 }, 
				credits: { enabled: false }, title: { text: '' }, subtitle: { text: '' }, 
				xAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, categories: [$categories] }, 
				yAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, title: { text: '' } }, 
				legend: { /*enabled: false*/ layout: 'vertical', align: 'right', verticalAlign: 'middle' }, 
				series: [$series]
			});
			</script>";

			$message_text.= '<table class="tables_inv" style="">';
			$message_text.= '<thead>';
			$message_text.= '<tr>';
				$message_text.= '<th></th>';
				$message_text.= '<th style="width:12%;">Âñåãî</th>';
				$message_text.= '<th style="width:10%;">Çà ìåñÿö</th>';
				for($i=0; $i<count($week_ru); $i++) {
					$message_text.= '<th style="width:8%; '.($week_en[$i]==strtolower(DATE("D")) ? "background:#127EB5;" : false).'">'.$week_ru[$i].'</th>';
				}
			$message_text.= '</tr>';
			$message_text.= '</thead>';
			$message_text.= '<tbody>';
			for($i=0; $i<count($tab_in_arr); $i++) {
				$message_text.= '<tr>';
					$message_text.= '<td align="center" style="color:'.$color_arr[$tab_in_arr[$i]].';">'.$types_arr[$tab_in_arr[$i]].'</td>';
					$message_text.= '<td align="center"><b>'.(isset($StatUser[$tab_in_arr[$i]]["all_views"]) ? $StatUser[$tab_in_arr[$i]]["all_views"] : "0").'</b></td>';
					$message_text.= '<td align="center"><b>'.(isset($StatUser[$tab_in_arr[$i]]["month"]) ? $StatUser[$tab_in_arr[$i]]["month"] : "0").'</b></td>';
					for($y=0; $y<count($week_en); $y++) {
						$message_text.= '<td align="center">'.(isset($StatUser[$tab_in_arr[$i]][$week_en[$y]]) ? $StatUser[$tab_in_arr[$i]][$week_en[$y]] : "0").'</td>';
					}
				$message_text.= '</tr>';
			}
			$message_text.= '</tbody>';
			$message_text.= '</table>';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR";
			$message_text = '<span class="msg-error">Ïîëüçîâàòåëü ñ òàêèìè äàííûìè íå íàéäåí!</span>';
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}

	}else{
		$result_text = "ERROR"; $message_text = "ERROR: NO OPTION";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}
}else{
	$result_text = "ERROR"; $message_text = "Access denied!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}


$result_text = "ERROR"; $message_text = "Íåò êîğğåêòíîãî AJAX çàïğîñà.";
exit(my_json_encode($ajax_json, $result_text, $message_text));

?>