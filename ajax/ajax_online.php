<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
	if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
	require_once(ROOT_DIR."/config.php");

	function online_us($user, $ip, $page, $page_title) {
		mysql_query("DELETE FROM `tb_online` WHERE `ip`='$ip' OR `date`<'".time()."' ") or die(mysql_error());

		mysql_query("INSERT INTO `tb_online` (`username`,`date`,`ip`,`page`,`pagetitle`) 
		VALUES ('$user','".(time()+5*60)."','$ip','$page','$page_title') ");
	}

	function getRealIP() {
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$client_ip = (!empty($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : ((!empty($_ENV["REMOTE_ADDR"])) ? $_ENV["REMOTE_ADDR"] : "unknown" );
		        $entries = explode('[, ]', $_SERVER["HTTP_X_FORWARDED_FOR"]);
			reset($entries);
			while (list(, $entry) = each($entries)) {
				$entry = trim($entry);
				if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
					$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/');
					$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
					if($client_ip != $found_ip) {$client_ip = $found_ip; break;}
				}
			}
		}else{
			$client_ip = (!empty($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : ((!empty($_ENV["REMOTE_ADDR"])) ? $_ENV["REMOTE_ADDR"] : "unknown" );
		}
		return $client_ip;
	}

	$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;
	$page = ( isset($_POST["page_user"]) && filter_var(trim($_POST["page_user"]), FILTER_VALIDATE_URL) ) ? filter_var(trim($_POST["page_user"]), FILTER_VALIDATE_URL) : false;
	$page = ($page == false) ? (isset($_SESSION["page_user"]) ? filter_var(trim($_SESSION["page_user"]), FILTER_VALIDATE_URL) : false) : $page;
	$page_title = ( isset($_POST["page_title"])) ? iconv("UTF-8", "CP1251//TRANSLIT", htmlspecialchars(trim($_POST["page_title"]), NULL, "CP1251")) : false;
	$page_title = ($page_title != false) ? str_ireplace($_SERVER["HTTP_HOST"]." | ", "", $page_title) : false;
	$ip_log = getRealIP();

	online_us($user_name, $ip_log, $page, $page_title);
	$online_people = intval(mysql_result(mysql_query("SELECT COUNT(`id`) FROM `tb_online`"),0,0));

	exit("$online_people");
}else{
	exit("0");
}
?>   