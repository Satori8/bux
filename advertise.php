<?php
//error_reporting (E_ALL);
if(!DEFINED("ADVERTISE")) DEFINE("ADVERTISE", true);
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("PHP_SELF")) DEFINE("PHP_SELF", $_SERVER["PHP_SELF"]);
$pagetitle = "Заказ рекламы";
include(DOC_ROOT."/header.php");
if(isset($_GET["ads"]) && htmlspecialchars(limpiar(trim($_GET["ads"])))!="rek_mails") require_once(DOC_ROOT."/.zsecurity.php");
require(DOC_ROOT."/merchant/func_mysql.php");
require_once(ROOT_DIR."/method_pay/method_pay_sys.php");

$ads = ( isset($_GET["ads"]) && preg_match("|^[a-zA-Z0-9\-_-]{1,20}$|", trim($_GET["ads"])) ) ? htmlspecialchars(limpiar(trim($_GET["ads"]))) : "dlinks";
$adv_link = ( isset($_COOKIE["adv_link"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", limpiar(htmlspecialchars(trim($_COOKIE["adv_link"])))) ) ? limpiar(htmlspecialchars(trim($_COOKIE["adv_link"]))) : false;

?><script type="text/javascript">
function adv_link(ads) {
	var expires = new Date();
	expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000));
	document.cookie="adv_link=" + ads + "; path=/; expires=" + expires.toUTCString(); location.href="<?php echo PHP_SELF;?>?ads=" + ads;
}
</script><?php

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_cab = mysql_query("SELECT * FROM `tb_cabinet` WHERE `id`='1'");
	if(mysql_num_rows($sql_cab)>0) {
		$row_cab = mysql_fetch_assoc($sql_cab);

		$cab_status = $row_cab["status"];
		$cab_start_sum = $row_cab["start_sum"];
		$cab_shag_sum = $row_cab["shag_sum"];
		$cab_start_proc = $row_cab["start_proc"];
		$cab_max_proc = $row_cab["max_proc"];
		$cab_shag_proc = $row_cab["shag_proc"];

		if($cab_status==1) {
			if(isset($my_money_rek) && $my_money_rek>=$cab_start_sum) {
				$cab_skidka = $cab_start_proc + (floor(($my_money_rek - $cab_start_sum)/$cab_shag_sum) * $cab_shag_proc);
				if($cab_skidka>$cab_max_proc) $cab_skidka = $cab_max_proc;
				$cab_text = '<tr><td><b>Ваша скидка рекламодателя:</b></td><td>'.$cab_skidka.' %</td></tr>';
			}else{
				$cab_skidka = 0;
				$cab_text = "";
			}
		}else{
			$cab_skidka = 0;
			$cab_text = "";
		}
	}else{
		$cab_skidka = 0;
		$cab_text = "";
	}
}else{
	$cab_skidka = 0;
	$cab_text = "";
}

echo '<table class="tables">';
		echo '<tr align="center">';
			echo '<td width="33%" class="adv_menu'.("dlink"==$ads ? "_act" : false).'"><a href="?ads=dlink">Серфинг</a></td>';
			echo '<td width="33%" class="adv_menu'.("test_drive"==$ads ? "_act" : false).'"><a href="?ads=test_drive">Серфинг - Test Drive</a></td>';
			echo '<td width="33%" class="adv_menu'.("bonus_surf"==$ads ? "_act" : false).'"><a href="?ads=bonus_surf">Бонусный серфинг</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="adv_menu'.("psevdo"==$ads ? "_act" : false).'"><a href="?ads=psevdo">Псевдо ссылки</a></td>';
			echo '<td width="33%" class="adv_menu'.("stat_links"==$ads ? "_act" : false).'"><a href="?ads=stat_links">Статические ссылки</a></td>';
			echo '<td width="33%" class="adv_menu'.("frm_links"==$ads ? "_act" : false).'"><a href="?ads=frm_links">Ссылки во фрейме</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="adv_menu'.("quick_mess"==$ads ? "_act" : false).'"><a href="?ads=quick_mess">Быстрые сообщения</a></td>';
			echo '<td width="33%" class="adv_menu'.("banners"==$ads ? "_act" : false).'"><a href="?ads=banners">Баннеры</a></td>';
			echo '<td width="33%" class="adv_menu'.("rek_cep"==$ads ? "_act" : false).'"><a href="?ads=rek_cep">Рекламная цепочка</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="adv_menu'.("auto_serf"==$ads ? "_act" : false).'"><a href="?ads=auto_serf">Авто-серфинг</a></td>';
			echo '<td width="33%" class="adv_menu'.("kontext"==$ads ? "_act" : false).'"><a href="?ads=kontext">Контекстная реклама</a></td>';
			//echo '<td width="33%" class="adv_menu'.("quest"==$ads ? "_act" : false).'"><a href="?ads=quest">Платные вопросы</a></td>';
			echo '<td width="33%" class="adv_menu'.("kat_links"==$ads ? "_act" : false).'"><a href="?ads=kat_links">Каталог сайтов</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="adv_menu'.("beg_stroka"==$ads ? "_act" : false).'"><a href="?ads=beg_stroka">Бегущая строка</a></td>';
			echo '<td width="33%" class="adv_menu'.("txt_ob"==$ads ? "_act" : false).'"><a href="?ads=txt_ob">Текстовые объявления</a></td>';
				echo '<td width="33%" class="adv_menu'.("pay_row"==$ads ? "_act" : false).'"><a href="?ads=pay_row">Платная строка</a></td>';
		echo '</tr>';
		echo '<tr align="center">';                     
			echo '<td width="33%" class="adv_menu'.("mails"==$ads ? "_act" : false).'"><a href="?ads=mails">Письма</a></td>';
			echo '<td width="33%" class="adv_menu'.("tests"==$ads ? "_act" : false).'"><a href="?ads=tests">Оплачиваемые тесты</a></td>';
			echo '<td width="33%" class="adv_menu'.("packet"==$ads ? "_act" : false).'"><a href="?ads=packet">Пакет рекламы</a></td>';
		echo '</tr>';
		echo '<tr align="center">'; 
		    echo '<td width="33%" class="adv_menu'.("test_drive_youtube"==$ads ? "_act" : false).'"><a href="?ads=test_drive_youtube">YouTube - Test Drive</a></td>';
			//echo '<td width="33%" class="adv_menu"></td>';
			echo '<td width="33%" class="adv_menu'.("youtube"==$ads ? "_act" : false).'"><a href="?ads=youtube">You Tube серфинг</a></td>';
			echo '<td width="33%" class="adv_menu'.("articles"==$ads ? "_act" : false).'"><a href="?ads=articles">Каталог статей</a></td>';
			//echo '<td width="33%" class="adv_menu'.("quick_mess"==$ads ? "_act" : false).'"><a href="?ads=quick_mess">Быстрые сообщения</a></td>';
			echo '<td width="33%" class="adv_menu"></td>';
		echo '</tr>';
		echo '<tr align="center">';
		//echo '<td width="33%" class="adv_menu"></td>';
		//echo '<td width="33%" class="adv_menu'.("auto_serf_you"==$ads ? "_act" : false).'"><a href="?ads=auto_serf_you">Авто-серфинг YouTube</a></td>';
		echo '<td width="33%" class="adv_menu'.("sent_emails"==$ads ? "_act" : false).'"><a href="?ads=sent_emails">Рассылка на e-mail</a></td>';
		
		echo '<td width="33%" class="adv_menu'.("pay_visits"==$ads ? "_act" : false).'"><a href="?ads=pay_visits">Оплачиваемые посещения</a></td>';
		
		//echo '<td width="33%" class="adv_menu"></td>';
			echo '</tr>';
		echo '</table><br>';

switch($ads) {
	case("dlinks"):		include(DOC_ROOT."/advertise/adv_dlinks.php"); break;
	case("test_drive"):	include(DOC_ROOT."/advertise/adv_test_drive.php"); break;
	case("test_drive_youtube"):	include(DOC_ROOT."/advertise/adv_test_drive_youtube.php"); break;
	case("auto_serf"):	include(DOC_ROOT."/advertise/adv_auto_serf.php"); break;
	case("auto_serf_you"):	include(DOC_ROOT."/advertise/adv_autoyou_serf.php"); break;
	case("psevdo"):		include(DOC_ROOT."/advertise/adv_psevdo.php"); break;
	case("stat_links"):	include(DOC_ROOT."/advertise/adv_stat_links.php"); break;
	case("kontext"):	include(DOC_ROOT."/advertise/adv_kontext.php"); break;
	case("banners"):	include(DOC_ROOT."/advertise/adv_banners.php"); break;
	case("txt_ob"):		include(DOC_ROOT."/advertise/adv_txt_ob.php"); break;
	case("frm_links"):	include(DOC_ROOT."/advertise/adv_frm_links.php"); break;
	case("mails"):		include(DOC_ROOT."/advertise/adv_mails.php"); break;
	case("rek_cep"):	include(DOC_ROOT."/advertise/adv_rek_cep.php"); break;
	case("youtube"):	include(DOC_ROOT."/advertise/adv_youtube.php"); break;
        case("tests"):     	include(DOC_ROOT."/advertise/adv_tests.php"); break;
	case("kat_links"):	include(DOC_ROOT."/advertise/adv_kat_links.php"); break;
	case("quest"):		include(DOC_ROOT."/advertise/adv_quest.php"); break;
        case("beg_stroka"):	include(DOC_ROOT."/advertise/adv_beg_stroka.php"); break;
	case("pay_row"):	include(DOC_ROOT."/advertise/adv_pay_row.php"); break; 
	case("sent_emails"):	include(DOC_ROOT."/advertise/adv_sent_emails.php"); break;
	case("articles"):	include(DOC_ROOT."/advertise/adv_articles.php"); break;
	case("packet"):		include(DOC_ROOT."/advertise/adv_packet.php"); break;
	case("quick_mess"):		include(DOC_ROOT."/advertise/adv_quick_mess.php"); break;
	case("bonus_surf"):	include(DOC_ROOT."/advertise/adv_bonus_surf.php"); break;
	case("pay_visits"):	include(ROOT_DIR."/advertise/adv_pay_visits.php"); break;

	default:		include(DOC_ROOT."/advertise/adv_dlinks.php"); break;
}

include('footer.php');?>