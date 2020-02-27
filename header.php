<?php
@session_start();
if(!DEFINED("SP_HEADER")) DEFINE("SP_HEADER", true);
$sysstart = microtime(true);
$domen = isset($_SERVER["HTTP_HOST"]) ? strtoupper($_SERVER["HTTP_HOST"]) : false;
function check_xss() {
	$url = html_entity_decode( urldecode( $_SERVER['QUERY_STRING'] ) );
	$url = str_replace( "\\", "/", $url );
	if(isset($url) && (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, './' ) !== false) || (strpos( $url, '../' ) !== false) || (strpos( $url, '\'' ) !== false) || (strpos( $url, '.php' ) !== false) ) {
		die("Hacking attempt!");
	}
	$url = html_entity_decode( urldecode( $_SERVER['REQUEST_URI'] ) );
	$url = str_replace( "\\", "/", $url );
	if(isset($url) && (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, '\'' ) !== false) ) {
		die("Hacking attempt!!!");
	}
}
check_xss();

require("config.php");
$check_status_site = mysql_result(mysql_query("SELECT `site_status` FROM `tb_site` WHERE `id`='1'"),0,0);

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(strtolower(stripslashes(trim($_SESSION["userLog"])))) : false;
if($check_status_site==0 && !isset($_SESSION["userLog_a"]) && !isset($_SESSION["userPas_a"])) {

	$site_status_txt = mysql_result(mysql_query("SELECT `site_status_txt` FROM `tb_site` WHERE `id`='1'"),0,0);
	$site_status_txt = str_ireplace("\r", "<br>", $site_status_txt);
	$site_status_txt = str_ireplace("\n", "<br>", $site_status_txt);

	echo '<!DOCTYPE html>';
	echo '<html lang="ru">';
	echo '<head>';
		echo '<title>'.$domen.': ТЕХНИЧЕСКИЕ РАБОТЫ!</title>';
		//echo '<link rel="icon" type="image/x-icon" href="//'.$_SERVER["HTTP_HOST"].'/favicon.ico?v=1.02" />';
	echo '</head>';
	echo '<body style="width:100%; height:100%; position:absolute; margin:0; padding:0; background: url(/style/img/bg-header2.jpg);">';
	echo '<table style="width:100%; height:80%; margin:0 auto; padding:0; border:none;">';
	echo '<tr><td align="center">';
		echo '<table align="center" border="0" cellpadding="0" cellspacing="0" width="500" style="border: 1px solid #008B8B; box-shadow: 1px 2px 4px 1px rgba(0, 0, 0, 0.4);">';
		echo '<tr align="center"><td>';
			echo '<table align="center" border="0" cellpadding="3" cellspacing="1" width="500">';
			echo '<tr><td style="background:#008B8B; border:1px solid #008B8B; padding:10px; text-align:center; color:#FFFFFF; font:14px; font-family:Palatino;"><b>'.$domen.'</b></td></tr>';
			echo '<tr><td style="background:#F5F5DC; padding:15px; text-align:center; color:#363636; font-size:16px; font-family:times, serif;"><b>'.$site_status_txt.'</b></td></tr>';
			echo '<tr><td style="background:#F5F5DC; padding:7px; text-align:center; color:#4F4F4F; font-size:12px; font-family:times, serif;">Время на сервере: '.DATE("d.m.Yг. H:i", time()).'</td></tr>';
			echo '</table>';
		echo '</td></tr>';
		echo '</table>';
	echo '</td></tr>';
	echo '</table>';
	echo '</body>';
	echo '</html>';
	exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<meta name="description" content="Новости о биткоинах, Самое главное в интернете btc,биткоин,заработай биткоины,earn bitcoins,bitcoin,dogecoin,litecoin" />
        <meta name="keywords" content="btc,биткоин,заработай биткоины,earn bitcoins,bitcoin,dogecoin,litecoin,market,продвижение,раскрутка,оптимизация,сео,серфинг,задания,bux,webmoney,wmr,surfing,surf,work,money,ptc,букс,заработок,сар,чат,раскрутка сайта,раскрутка,рейтинги,рейтинг,рекламные услуги,продвижение сайтов,эффективная раскрутка,сервис активной рекламы,ваша реклама наша забота,тиц сайта,трафик на сайт,Качественная реклама,достойный заработок,работа без вложений,работа на дому,работа в интернете,рекламное агентство,только уникальные посетители на ваш сайт,несколько видов заработка,реферальная система,чтение писем,автосерф" />
	<meta name="rating" content="General" />
	<meta name="owner" content="Supreme-Garden" />
	<meta name="copyright" content="2018-<?php echo @DATE("Y");?>" />
	<meta name="author" content="Supreme-Garden" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
	<meta name="yandex-verification" content="980ec5cffeedb221" />
	<meta name="webmoney" content="727437A3-9074-4813-A1A7-FD86044F4F3E"/>
	<title><?php echo "$domen | ".@$pagetitle;?></title>

	<noscript><meta http-equiv="refresh" content="2; URL=bot.php"></noscript>
	<link rel="stylesheet" type="text/css" href="/style/style.css?v=3.08" />
	<link rel="stylesheet" type="text/css" href="/style/block.css?v=1.05" />
	<link rel="stylesheet" type="text/css" href="/style/modalpopup.css?v=0.01" />
	<link rel="stylesheet" type="text/css" href="/forum/style/style.css?v=1.00" />
	<link rel="stylesheet" type="text/css" href="/style/progress.css" />
	<link rel="stylesheet" type="text/css" href="/cabinet/style/cabinet.css" />
	<script src="/js/jquery/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/js_online.js?v=1.07"></script>
	<script type="text/javascript" src="/js/js_modalpopup-0.3.min.js"></script>
	
	<?php
		if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			echo '<script type="text/javascript" src="/js/js_new_notif.js?v=1.01"></script>'."\n";
			echo '	<script type="text/javascript" src="/js/js-menu-users.js?v=1.02"></script>'."\n";
			echo '	<script type="text/javascript" src="/js/js_rtpls.js"></script>'."\n";
			echo '	<script type="text/javascript" src="js/js_bonus.js"></script>'."\n";

			if ((strpos($_SERVER['PHP_SELF'], '/profile.php' ) !== false)) {
				echo '	<script src="/js/js_upload_av.js?v=1.03"></script>'."\n";
			}

			if ((strpos($_SERVER['PHP_SELF'], '/vacation.php' ) !== false)) {
				echo '	<script type="text/javascript" src="js/js_vac.js"></script>'."\n";
			}

			if ((strpos($_SERVER['PHP_SELF'], '/cabinet.php' ) !== false)) {
				echo '	<script type="text/javascript" src="/cabinet/js/cabinet.js?v=1.02"></script>'."\n";
			}

			if (
				(strpos($_SERVER['PHP_SELF'], '/auction.php')	!== false) | 
				(strpos($_SERVER['PHP_SELF'], '/cabinet.php')	!== false) | 
				(strpos($_SERVER['PHP_SELF'], '/null_referer.php') !== false) | 
				(strpos($_SERVER['PHP_SELF'], '/ref.php') 	!== false) | 
				(strpos($_SERVER['PHP_SELF'], '/refbirj.php') 	!== false) | 
				(strpos($_SERVER['PHP_SELF'], '/referals1.php') !== false) | 
				(strpos($_SERVER['PHP_SELF'], '/top100.php') 	!== false)
			) {
				echo '	<script type="text/javascript" src="/js/js_stats_users.js"></script>'."\n";
			}
		}else{
			if (strpos($_SERVER['PHP_SELF'], '/top100.php') !== false) {
				echo '	<script type="text/javascript" src="/js/js_stats_users_w.js"></script>'."\n";
			}
		}
	?>
	
</head>

<?php

require_once('configsite/config_site.php');
$ip = (isset($_POST["ip"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiarez($_POST["ip"])) ? limpiarez($_POST["ip"]) : false);
$ip = getRealIP();
		$ip_arr = explode(".", $ip);
		$ip_m[1] = isset($ip_arr[0]) ? $ip_arr[0] : false;
		$ip_m[2] = isset($ip_arr[1]) ? $ip_arr[1] : false;
		$ip_m[3] = isset($ip_arr[2]) ? $ip_arr[2] : false;
		$ip_m[4] = isset($ip_arr[3]) ? $ip_arr[3] : false;
		$ip_m_4 = $ip_m[1].".".$ip_m[2].".".$ip_m[3].".".$ip_m[4];
		$ip_m_3 = $ip_m[1].".".$ip_m[2].".".$ip_m[3].".";
		$ip_m_2 = $ip_m[1].".".$ip_m[2].".";
$sql_b_ip_4 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_4'");
			$sql_b_ip_3 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_3'");
			$sql_b_ip_2 = mysql_query("SELECT `id` FROM `tb_black_ip` WHERE `ip_block`='$ip_m_2'");
			if(mysql_num_rows($sql_b_ip_4)>0 | mysql_num_rows($sql_b_ip_3)>0 | mysql_num_rows($sql_b_ip_2)>0) {
echo"<script type='text/javascript'>var t=0;function refr_time(){if (t>0){t--;document.getElementById('timer').innerHTML=t;}else{clearInterval(tm);location.href='https://www.google.com';}}var tm=setInterval('refr_time();',1000);</script>";
}
echo '<body>';
	echo '<noscript><span class="msg-error" style="position:fixed; width:100%">Включите поддержку Java-Script, в противном случае система будет не корректно реагировать на Ваши запросы.</span></noscript>';
	echo '<div id="maincontainer">';
		include('includes/headers.php');
	echo '</div>';
	echo '<div id="contentwrapper">';
		include('includes/pay_row.php');
        include('includes/reklama.php');		
		include('includes/rek_cep.php');
		include('includes/reklama2.php');	
                include('includes/beg_stroka.php');
		include('menuleft.php'); 
		include('menuright.php');

		echo '<div id="maincolumn"><div class="text">';

			if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
				$wask = uc($_SESSION["userLog"]);
				$wazk = ($_SESSION["userPas"]);
				$agent_now = getenv("HTTP_USER_AGENT");
				if($wesk!=$wask|$wezk!=$wazk) {
					if(count($_SESSION)>0) {
						foreach($_SESSION as $key => $val) {
							unset($_SESSION["$key"]);
						}
					}
					echo '<script type="text/javascript">location.replace("/err_log.php?s=1");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/err_log.php?s=1"></noscript>';
				}
				if($block_agent==1 && ($ip!=$lastiplog | $agent_now!=$agent) ) {
					if(count($_SESSION)>0) {
						foreach($_SESSION as $key => $val) {
							unset($_SESSION["$key"]);
						}
					}
					echo '<script type="text/javascript">location.replace("/err_log.php?s=2");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/err_log.php?s=2"></noscript>';
				}
				
			}

			echo '<div id="block">';
					
				echo '<div id="PageTitle"></div>';
				echo '<div id="block-title-page">'.@$pagetitle.'</div>';
				include('includes/banner728x90.php');
				
					echo '<span id="info_rtpls" style="display:none;"></span>';
					echo '<div id="loading" style="display:none;"></div>';
					echo '<span id="info-msg" style="display:none;"></span>';
					echo '<div id="LoadModal" style="display:none;"></div>';

					if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
						$vac1 = $row_user["vac1"];
						if($vac1>0 && $vac1>time()) {
							if ((strpos($_SERVER['PHP_SELF'], '/vacation.php' ) === false)) {
								echo '<script type="text/javascript">location.replace("/vacation.php");</script>';
								echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/vacation.php"></noscript>';
							}

							echo '<span style="color:#FF0000; font-size:18px;">Аккаунт в отпуске до '.DATE("d.m.Y",$vac1).'</span>';

							echo '<span class="beach" title="Аккаунт в отпуске до '.DATE("d.m.Y",$vac1).'"></span>';

							echo '<h2 class="sp">Для выхода из отпуска введите ваш пароль для операций!</h2>';
							echo '<div align="center"><form name="vacationout" onsubmit="return false;" id="newform">';
								echo '<input type="password" name="pass_oper" maxlength="10" value="" class="ok12" style="text-align:center;"><br><br>';
								echo '<span class="sub-blue160" style="float:none; font-size:12px;" onclick="javascript:vac_out();" />Выйти из отпуска</a><br>';
							echo '</form></div>';

							echo '<span id="info-msg-out" style="display:none;"></span>';

							include('footer.php');
							exit();
						}

						if(!isset($_COOKIE["ModBLock"])) {
							if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
							if(!DEFINED("cache_not_file")) DEFINE("cache_not_file", ROOT_DIR."/cache/cache_notification.inc");
							if(is_file(cache_not_file) && $_SERVER["PHP_SELF"]!="/news.php") {
								$not_arr = unserialize(file_get_contents(cache_not_file));
								if(count($not_arr)>0) {
									echo '<div id="LoadModalNot" style="display:none;"></div>';

									?><script type="text/javascript">
									$(document).ready(function(){
										$.ajax({
											type: "POST", url: "/ajax/ajax_modalpopup.php?=rnd"+Math.random(), 
											data: { 'op' : 'LoadNot' }, 
											success: function(data) {if (data) $('#LoadModalNot').html(data);}
										});
									});
									</script><?php

									$_Load_Modal = "YES";
								}
							}
						}

						$_URL_Ban_Arr = array("/members.php","/newmsg.php","/contact.php","/index.php");

						if(isset($my_ban_date) && $my_ban_date>0 && array_search($_SERVER["PHP_SELF"], $_URL_Ban_Arr)===false) {
							$sql_ban = mysql_query("SELECT * FROM `tb_black_users` WHERE `name`='$username' ORDER BY `id` DESC");
							if(mysql_num_rows($sql_ban)>0) {
								$row_ban = mysql_fetch_assoc($sql_ban);

								echo '<span class="msg-error">';
									echo 'Ваш аккаунт заблокирован!<br>Доступ к некоторым разделам сайта Вам запрещен!<br>';
									echo 'Причина блокировки: <b>'.$row_ban["why"].'</b><br>Дата блокировки: '.$row_ban["date"];
								echo '</span>';
								include('footer.php');
								exit();
							}
						}
						
					$_URL_Email_Arr = array("/members.php","/index.php","/profile.php","/reg_email.php");
					
						//if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
			if(isset($email_ok) && $email_ok!=1 && array_search($_SERVER["PHP_SELF"], $_URL_Email_Arr)===false) {
				echo '<span class="msg-error">Для работы на проекте Вам необходимо подтвердить ваш E-mal. <span onClick="document.location.href=\'/profile.php?op=mod_email\'">[подтвердить]</span></span>';
				include('footer.php');
			exit();
			//}
		}

					}

?>