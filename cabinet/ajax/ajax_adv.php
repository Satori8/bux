<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("CABINET")) DEFINE("CABINET", true);
if(!DEFINED("ADVERTISE")) DEFINE("ADVERTISE", true);
sleep(0);

$json_result = array();
$json_result["result"] = "";
$json_result["status"] = "";
$json_result["message"] = "";

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require(DOC_ROOT."/config.php");
	require(DOC_ROOT."/funciones.php");
	require(DOC_ROOT."/cabinet/cab_func.php");
	require(DOC_ROOT."/merchant/func_mysql.php");
	require(DOC_ROOT."/merchant/func_cache.php");

	$auth_user = auth_log_pass(2);

	if($auth_user["status"] == "FALSE") {
		if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
			$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Необходимо авторизоваться!"));
			exit(json_encode_cp1251($json_result));
		}else{
			exit("Необходимо авторизоваться!");
		}

	}elseif($auth_user["status"] == "TRUE") {
		$user_id = $auth_user["user_id"];
		$username = $auth_user["user_log"];
		$money_user_rb = $auth_user["user_money_rb"];
		$money_user_rek = $auth_user["user_money_rek"];
		$my_referer_1 = $auth_user["user_referer_1"];
		$my_referer_2 = $auth_user["user_referer_2"];
		$my_referer_3 = $auth_user["user_referer_3"];
		$wmid_user = $auth_user["user_wmid"];
		$wmr_user = $auth_user["user_wm_purse"];

		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiarez($_POST["id"])))) ? intval(limpiarez($_POST["id"])) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["op"])) ) ? limpiarez($_POST["op"]) : false;
		$type_ads = ( isset($_POST["type"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", limpiarez($_POST["type"])) ) ? limpiarez($_POST["type"]) : false;
		$laip = getRealIP();

		$sql_cab = mysql_query("SELECT * FROM `tb_cabinet` WHERE `id`='1'");
		if(mysql_num_rows($sql_cab)>0) {
			$row_cab = mysql_fetch_assoc($sql_cab);

			$cab_status = $row_cab["status"];
			$cab_start_sum = $row_cab["start_sum"];
			$cab_shag_sum = $row_cab["shag_sum"];
			$cab_start_proc = $row_cab["start_proc"];
			$cab_max_proc = $row_cab["max_proc"];
			$cab_shag_proc = $row_cab["shag_proc"];

			if($money_user_rek>=$cab_start_sum && $cab_status==1) {
				$cab_skidka = $cab_start_proc + (floor(($money_user_rek - $cab_start_sum)/$cab_shag_sum) * $cab_shag_proc);
				if($cab_skidka>=$cab_max_proc) $cab_skidka = $cab_max_proc;
			}else{
				$cab_skidka = 0;
			}
		}else{
			if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
				$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Ошибка! Не удалось загрузить данные кабинета!"));
				exit(json_encode_cp1251($json_result));
			}else{
				exit("Ошибка! Не удалось загрузить данные кабинета!");
			}
		}


		if($type_ads!=false && $type_ads=="dlink") {
			if(!DEFINED("DLINK_AJAX")) DEFINE("DLINK_AJAX", true);
			include("ajax_adv/ajax_dlink.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="autoserf") {
			if(!DEFINED("AUTOSERF_AJAX")) DEFINE("AUTOSERF_AJAX", true);
			include("ajax_adv/ajax_autoserf.php");
			exit();
			
		}elseif($type_ads!=false && $type_ads=="youautoserf") {
			if(!DEFINED("YOUAUTOSERF_AJAX")) DEFINE("YOUAUTOSERF_AJAX", true);
			include("ajax_adv/ajax_autoserf_you.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="psevdo") {
			if(!DEFINED("PSEVDO_AJAX")) DEFINE("PSEVDO_AJAX", true);
			include("ajax_adv/ajax_psevdo.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="statlink") {
			if(!DEFINED("STATLINK_AJAX")) DEFINE("STATLINK_AJAX", true);
			include("ajax_adv/ajax_statlink.php");
			exit();

                }elseif($type_ads!=false && $type_ads=="statkat") {
			if(!DEFINED("STATKAT_AJAX")) DEFINE("STATKAT_AJAX", true);
			include("ajax_adv/ajax_statkat.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="banners") {
			if(!DEFINED("BANNERS_AJAX")) DEFINE("BANNERS_AJAX", true);
			include("ajax_adv/ajax_banners.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="kontext") {
			if(!DEFINED("KONTEXT_AJAX")) DEFINE("KONTEXT_AJAX", true);
			include("ajax_adv/ajax_kontext.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="txtob") {
			if(!DEFINED("TXTOB_AJAX")) DEFINE("TXTOB_AJAX", true);
			include("ajax_adv/ajax_txtob.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="frmlink") {
			if(!DEFINED("FRMLINK_AJAX")) DEFINE("FRMLINK_AJAX", true);
			include("ajax_adv/ajax_frmlink.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="rekcep") {
			if(!DEFINED("REKCEP_AJAX")) DEFINE("REKCEP_AJAX", true);
			include("ajax_adv/ajax_rekcep.php");
			exit();

                }elseif($type_ads!=false && $type_ads=="link") {
			if(!DEFINED("LINK_AJAX")) DEFINE("LINK_AJAX", true);
			include("ajax_adv/ajax_link.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="mails") {
			if(!DEFINED("MAILS_AJAX")) DEFINE("MAILS_AJAX", true);
			include("ajax_adv/ajax_mails.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="sentmails") {
			if(!DEFINED("SENTMAILS_AJAX")) DEFINE("SENTMAILS_AJAX", true);
			include("ajax_adv/ajax_sentmails.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="begstroka") {
			if(!DEFINED("BEGSTROKA_AJAX")) DEFINE("BEGSTROKA_AJAX", true);
			include("ajax_adv/ajax_begstroka.php");
			exit();

		}elseif($type_ads!=false && $type_ads=="vopros") {
			if(!DEFINED("VOPROS_AJAX")) DEFINE("VOPROS_AJAX", true);
			include("ajax_adv/ajax_vopros.php");
			exit();
			
		}elseif($type_ads!=false && $type_ads=="pay_row") {
			if(!DEFINED("PAY_ROW_AJAX")) DEFINE("PAY_ROW_AJAX", true);
			include("ajax_adv/ajax_pay_row.php");
			exit();
			
		}elseif($type_ads!=false && $type_ads=="youtube") {
			if(!DEFINED("YOUTUBE_AJAX")) DEFINE("YOUTUBE_AJAX", true);
			include("ajax_adv/ajax_youtube.php");
			exit();	

		}elseif($type_ads!=false && $type_ads=="tests") {
			if(!DEFINED("TESTS_AJAX")) DEFINE("TESTS_AJAX", true);
			include("ajax_adv/ajax_tests.php");
			exit();
			
		}elseif($type_ads!=false && $type_ads=="articles") {
			if(!DEFINED("ARTICLES_AJAX")) DEFINE("ARTICLES_AJAX", true);
			include("ajax_adv/ajax_articles.php");
			exit();

		}else{
			if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
				$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Ошибка! Тип рекламы не определен!"));
				exit(json_encode_cp1251($json_result));
			}else{
				exit("Ошибка! Тип рекламы не определен!");
			}
		}
	}else{
		if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
			$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Необходимо авторизоваться!"));
			exit(json_encode_cp1251($json_result));
		}else{
			exit("Ошибка! Необходимо авторизоваться!");
		}
	}
}else{
	if( isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false ) {
		$json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Ошибка! Не AJAX запрос!"));
		exit(json_encode_cp1251($json_result));
	}else{
		exit("Ошибка! Не AJAX запрос!");
	}
}

?>