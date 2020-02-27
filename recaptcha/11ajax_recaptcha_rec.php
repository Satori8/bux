<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
sleep(0);

$json_result = array();
$json_result["result"] = "";
$json_result["message"] = "";
$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(ROOT_DIR."/config.php");
	require_once(ROOT_DIR."/funciones.php");
	require_once(ROOT_DIR."/recaptcha/config_recaptcha.php");
	require_once(ROOT_DIR."/recaptcha/lib/autoload.php");
	$laip = getRealIP();

	function json_encode_cp1251($json_arr) {
		$json_arr = json_encode($json_arr);
		$arr_replace_cyr = array("\u0410", "\u0430", "\u0411", "\u0431", "\u0412", "\u0432", "\u0413", "\u0433", "\u0414", "\u0434", "\u0415", "\u0435", "\u0401", "\u0451", "\u0416", "\u0436", "\u0417", "\u0437", "\u0418", "\u0438", "\u0419", "\u0439", "\u041a", "\u043a", "\u041b", "\u043b", "\u041c", "\u043c", "\u041d", "\u043d", "\u041e", "\u043e", "\u041f", "\u043f", "\u0420", "\u0440", "\u0421", "\u0441", "\u0422", "\u0442", "\u0423", "\u0443", "\u0424", "\u0444", "\u0425", "\u0445", "\u0426", "\u0446", "\u0427", "\u0447", "\u0428", "\u0448", "\u0429", "\u0449", "\u042a", "\u044a", "\u042d", "\u044b", "\u042c", "\u044c", "\u042d", "\u044d", "\u042e", "\u044e", "\u042f", "\u044f");
		$arr_replace_utf = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
		$json_arr = str_replace($arr_replace_cyr, $arr_replace_utf, $json_arr);
		return $json_arr;
	}

	function myErrorHandler($errno, $errstr, $errfile, $errline, $js_result) {
		$ajax_json = (isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) ? "json" : "nojson";
		$message_text = false;
		$errfile = str_replace($_SERVER["DOCUMENT_ROOT"], "", $errfile);

		switch ($errno) {
			case(1): $message_text = "Fatal error[$errno]: $errstr in line $errline in $errfile"; break;
			case(2): $message_text = "Warning[$errno]: $errstr in line $errline in $errfile"; break;
			case(8): $message_text = "Notice[$errno]: $errstr in line $errline in $errfile"; break;
			default: $message_text = "[$errno] $errstr in line $errline in $errfile"; break;
		}
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}
	$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

	if(!isset($siteKey) | !isset($secret) | ( isset($siteKey) && isset($secret) && ($siteKey == false | $secret == false) ) ) {
		$message_text = "ERROR: ��� ������!";
		$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
		exit($js_result);
	}else{
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			$message_text = "�� ��� ��������������!";
			$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
			exit($js_result);
		}else{
			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
			$response = $recaptcha->verify(strip_tags(htmlspecialchars(trim($_POST["recaptcha"]))), $laip);

			if($response->isSuccess()) {
				$username = (isset($_POST["log_user"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_POST["log_user"]))) ? htmlentities(stripslashes(trim($_POST["log_user"]))) : false;
				$email_user = (isset($_POST["email_user"]) && preg_match("|^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,4}$|", trim($_POST["email_user"]))) ? htmlentities(stripslashes(trim($_POST["email_user"]))) : false;
				$wmid_user = (isset($_POST["wmid_user"]) && preg_match("|^[\d]{12}$|", trim($_POST["wmid_user"]))) ? htmlentities(stripslashes(trim($_POST["wmid_user"]))) : false;
				$send_wmid = (isset($_POST["send_wmid"]) && preg_match("|^[\d]{1}$|", intval(trim($_POST["send_wmid"])))) ? intval(trim($_POST["send_wmid"])) : 0;

				if($username == false && $email_user == false && $wmid_user == false) {
					$message_text = "�� ������� ������ ��� �������������� ������!";
					$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
					exit($js_result);
				}else{

					$sql_chk_alls = mysql_query("SELECT `id`,`username`,`password`,`pass_oper`,`email`,`wmid` FROM `tb_users` WHERE `username`='$username' OR `email`='$email_user' OR (`wmid`!='' AND `wmid`='$wmid_user')") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					//$sql_chk_logs = mysql_query("SELECT `id`,`username`,`password`,`pass_oper`,`email`,`wmid` FROM `tb_users` WHERE `username`='$username'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					//$sql_chk_mail = mysql_query("SELECT `id`,`username`,`password`,`pass_oper`,`email`,`wmid` FROM `tb_users` WHERE `email`='$email_user'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());
					//$sql_chk_wmid = mysql_query("SELECT `id`,`username`,`password`,`pass_oper`,`email`,`wmid` FROM `tb_users` WHERE `wmid`!='' AND `wmid`='$wmid_user'") or die(($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))) : mysql_error());

					if(mysql_num_rows($sql_chk_alls)>0) {
						$row = mysql_fetch_assoc($sql_chk_alls);

						$next_time = (isset($_SESSION["recoverpwd"]) && preg_match("|^[\d]{10,11}$|", trim($_SESSION["recoverpwd"]))) ? intval(trim($_SESSION["recoverpwd"])) : false;

						if($next_time>time()) {
							$message_text = "��������� ������ �� �������������� ��������������� ������ ����� �������� ����� ".ceil(($next_time-time())/60)." ���.";
							$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
							exit($js_result);
						}else{
							$email = $row["email"];
							$subject = "�������������� ������ ��� �����������";
							$from = explode(".", $_SERVER["HTTP_HOST"]);
							$headers = "Content-Type: text/html; charset=windows-1251\r\n";
							$headers.= "From: ".strtoupper($from[0])." <".$from[0]."@".$_SERVER["HTTP_HOST"].">\r\n";
							$headers.= "FromName: ".strtoupper($from[0])."\r\n";

							$message = '
                                                        <html>
                                                        <head>
		                                        <style type="text/css">
                                                        html, tbody {
                                                        display: table-row-group;
                                                        vertical-align: middle;
                                                        border-color: inherit;
	                                                }
                                                        </style>
                                                        </head>
                                                        <table align="center" border="0" cellpadding="5" cellspacing="0" style="width:100%;background-color:#e5e5e5;">
                                                        <tbody>
                                                        <tr><td align="center">
                                                        <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD;width:100%;background-color:#1b3c71;">
                                                        <tbody>        
                                                        <tr><td align="left" style="background:url(http://supreme-garden.ru/img/logo/logo.png) no-repeat bottom left;padding:46px;"></td></tr>
                                                        <tr><td align="center">
                                                        <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD;width:100%;background-color:#FFF;">
                        
                                                        <tr><td style="background-color:#48a0f7;font-size:16px;line-height:16px;text-align:center;padding:15px;color:#FFF;font-weight:normal;">�������������� ������ ��� ����������� �� ������� <b>Supreme-Garden.ru</b></td></tr>
                                                        <tr><td align="left" style="font-size:12px;font-family:Arial,Helvetica,sans-serif;line-height:20px;padding:20px;">
                                                        ������������!<br>
						        �� ��������� ������ ��� ����������� �� ������� <b>Supreme-Garden.ru</b></a><br>
						        ������ ��� ����������� � IP ������:<b> '.$laip.'</b><br>
						        ��� ID:<b> '.$row['id'].'</b><br>
						        ��� �����:<b> '.$row['username'].'</b><br>
						        ��� ������:<b> '.$row['password'].'</b><br>
						        ��� ������ ��� ��������:<b> '.$row['pass_oper'].'</b><br>
						        <span style="color:#FF0000;">��������!</span> ����������� ��������� ������. ������ ����� �������� � ������� �������.
                                                        </td></tr>
                                                        <tr><td align="left" style="border-top:1px solid #DDD;font-size:12px;padding:10px 20px;">
                                                        � ���������, �������������� ������ ����������� <b>Supreme-Garden.ru</b>
                                                        </td></tr>
                                                        </tbody>
                                                        </table>
                                                        </td></tr>
                                                        </tbody>
                                                        </table>
                                                        </html>';

							$_SESSION["recoverpwd"] = (time() + 10*60);

							if( $send_wmid==1 && isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) {
								require_once(ROOT_DIR."/auto_pay_req/wmxml.inc.php");

								$subject_wm = "�������������� ������ ��� �����������";
								$message_wm = "������������!\n\n";
								$message_wm.= "�� ��������� ������ ��� ����������� �� ������� http://".$_SERVER["HTTP_HOST"]."/\n";
								$message_wm.= "������ ��� ����������� � IP ������: $laip\n\n";
								$message_wm.= "<b>��� ID:</b> ".$row["id"]."\n";
								$message_wm.= "<b>��� �����:</b> ".$row["username"]."\n";
								$message_wm.= "<b>��� ������:</b> ".$row["password"]."\n";
								$message_wm.= "<b>��� ������ ��� ��������:</b> ".$row["pass_oper"]."\n\n";
								$message_wm.= "<q>��������: ����������� ��������� ������. ������ ����� �������� � ������� �������.</q>\n\n";
								$message_wm.= "---------------------------\n";
								$message_wm.= "� ���������, �������������� ������ ����������� http://".$_SERVER["HTTP_HOST"]."/";

								$_RES_WM = _WMXML6(trim($row["wmid"]), $message_wm, $subject_wm);

								if($_RES_WM["retval"]==0) {
									$message_text = "������ ������� ���������� �� ��� WMID";
									$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
								}else{
									if(mail($email, $subject, $message, $headers)) {
										$message_text = "������ ������� ���������� �� ��� e-mail.";
										$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
									}else{
										$message_text = "�� ������� ��������� ������ �� e-mail. ��������� ������� �����!";
										$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
									}
								}

								exit($js_result);

							}else{
								if(mail($email, $subject, $message, $headers)) {
									$message_text = "������ ������� ���������� �� ��� e-mail.";
									$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "OK", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
								}else{
									$message_text = "�� ������� ��������� ������ �� e-mail. ��������� ������� �����!";
									$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
								}
								exit($js_result);
							}
						}
					}else{
						$message_text = "������������ � ���������� ������� �� ����������������!";
						$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
						exit($js_result);
					}
				}
			}else{
				$_ERR_CODE = false;
				foreach($response->getErrorCodes() as $code) {
					$_ERR_CODE.= $code;
				}

				$message_text = ( isset($_ERR_CODE) && htmlspecialchars(trim($_ERR_CODE))!=false ) ? "���������� �����������, ��� �� �� �����!" : "�������� ��������!";
				$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
				exit($js_result);
			}
		}
	}
}else{
	$message_text = "ERROR: �� ���������� ������!";
	$js_result = ($ajax_json=="json") ? json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", $message_text))) : $message_text;
	exit($js_result);
}

?>