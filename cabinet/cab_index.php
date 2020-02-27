<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

$auth_user = auth_log_pass(1);
$ads = ( isset($_GET["ads"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", limpiar(htmlspecialchars(trim($_GET["ads"])))) ) ? limpiar(htmlspecialchars(trim($_GET["ads"]))) : "dlink";
$op =  ( isset($_GET["op"])  && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|",  limpiar(htmlspecialchars(trim($_GET["op"])))) ) ? limpiar(htmlspecialchars(trim($_GET["op"]))) : false;
$id =  ( isset($_GET["id"])  && preg_match("|^[\d]{1,11}$|", intval(limpiar(htmlspecialchars(trim($_GET["id"]))))) ) ? intval(limpiar(htmlspecialchars(trim($_GET["id"])))) : false;

if($auth_user["status"] == "FALSE") {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';

}elseif($auth_user["status"] == "TRUE") {
	$username = $auth_user["user_log"];

/*
	echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="center"><b><a href="/cabinet_ads">Кабинет рекламодателя</a></b></td>';
		echo '<td align="center"><b><a href="javascript: void(0);" onclick="info_cab();">Скидки рекламодателям</a></b></td>';
		echo '<td align="center"><b><a href="/advertise.php">Заказать рекламу</a></b></td>';
	echo '</tr>';
	echo '</table>';
*/
	$sql_cab = mysql_query("SELECT * FROM `tb_cabinet` WHERE `id`='1'");
	if(mysql_num_rows($sql_cab)>0) {
		$row_cab = mysql_fetch_assoc($sql_cab);

		$cab_status = $row_cab["status"];
		$cab_start_sum = $row_cab["start_sum"];
		$cab_shag_sum = $row_cab["shag_sum"];
		$cab_start_proc = $row_cab["start_proc"];
		$cab_max_proc = $row_cab["max_proc"];
		$cab_shag_proc = $row_cab["shag_proc"];

		$money_user_rb = $auth_user["user_money_rb"];
		$money_user_rek = $auth_user["user_money_rek"];

		if($money_user_rek<0) {
			echo '<span class="msg-error">Ошибка! Пожалуйста, обратитесь к администратору.</span>';
			include(DOC_ROOT."/footer.php");
			exit();
		}

		if($cab_status==1) {
			if($money_user_rek>=$cab_start_sum) {
				$cab_skidka = $cab_start_proc + (floor(($money_user_rek - $cab_start_sum)/$cab_shag_sum) * $cab_shag_proc);
				$cab_skidka_next = $cab_skidka + $cab_shag_proc;
				$nado_moneys = ( ($cab_skidka - $cab_start_proc + $cab_shag_proc) * $cab_shag_sum + $cab_start_sum) - $money_user_rek;
				if($cab_skidka>=$cab_max_proc) $cab_skidka = $cab_max_proc;
			}else{
				$cab_skidka = 0;
				if($cab_start_proc==0) { $cab_skidka_next = $cab_shag_proc; }else{ $cab_skidka_next = $cab_start_proc; }
				$nado_moneys = ($cab_start_sum - $money_user_rek);
			}
		}else{
			$cab_skidka = 0;
			$cab_skidka_next = 0;
			$nado_moneys = 0;
		}
	
		echo '<table class="tables">';
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("dlink"==$ads ? "_act" : false).'"><a href="?ads=dlink">Серфинг</a></td>';
			echo '<td width="33%" class="cab_menu'.("statlink"==$ads ? "_act" : false).'"><a href="?ads=statlink">Статические ссылки</a></td>';
			echo '<td width="33%" class="cab_menu'.("frmlink"==$ads ? "_act" : false).'"><a href="?ads=frmlink">Ссылки во фрейме</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("begstroka"==$ads ? "_act" : false).'"><a href="?ads=begstroka">Бегущая строка</a></td>';
			echo '<td width="33%" class="cab_menu'.("banners"==$ads ? "_act" : false).'"><a href="?ads=banners">Баннеры</a></td>';
			echo '<td width="33%" class="cab_menu'.("rekcep"==$ads ? "_act" : false).'"><a href="?ads=rekcep">Рекламная цепочка</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("autoserf"==$ads ? "_act" : false).'"><a href="?ads=autoserf">Авто-серфинг </a></td>';
			echo '<td width="33%" class="cab_menu'.("kontext"==$ads ? "_act" : false).'"><a href="?ads=kontext">Контекстная реклама</a></td>';
			echo '<td width="33%" class="cab_menu'.("mails"==$ads ? "_act" : false).'"><a href="?ads=mails">Письма</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("psevdo"==$ads ? "_act" : false).'"><a href="?ads=psevdo">Псевдо ссылки</a></td>';
			echo '<td width="33%" class="cab_menu'.("txtob"==$ads ? "_act" : false).'"><a href="?ads=txtob">Текстовые объявления</a></td>';
			echo '<td width="33%" class="cab_menu'.("sentmails"==$ads ? "_act" : false).'"><a href="?ads=sentmails">Рассылка на e-mail</a></td>';
		echo '</tr>';
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("tests"==$ads ? "_act" : false).'"><a onClick="cab_link(\'tests\');">Оплачиваемые тесты</a></td>';
            echo '<td width="33%" class="cab_menu'.("statkat"==$ads ? "_act" : false).'"><a href="?ads=statkat">Каталог ссылок</a></td>';
            echo '<td width="33%" class="cab_menu'.("pay_row"==$ads ? "_act" : false).'"><a href="?ads=pay_row">Платная строка</a></td>';
		echo '</tr>';
		
		echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu'.("youtube"==$ads ? "_act" : false).'"><a onClick="cab_link(\'youtube\');"><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>(серфинг)</a></td>';
			//echo '<td width="33%" class="cab_menu'.("youautoserf"==$ads ? "_act" : false).'"><a href="?ads=youautoserf">Авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></a></td>';
				echo '<td width="33%" class="cab_menu'.("pay_visits"==$ads ? "_act" : false).'"><a href="?ads=pay_visits">Оплачиваемые посещения</a></td>';
				echo '<td width="33%" class="cab_menu'.("articles"==$ads ? "_act" : false).'"><a onClick="cab_link(\'articles\');">Каталог статей</a></td>';
			echo '<td width="33%" class="cab_menu"></td>';
		echo '</tr>';
		
		/*echo '<tr align="center">';
			echo '<td width="33%" class="cab_menu"></td>';
			echo '<td width="33%" class="cab_menu'.("pay_visits"==$ads ? "_act" : false).'"><a href="?ads=pay_visits">Оплачиваемые посещения</a></td>';
				//echo '<td width="33%" class="cab_menu'.("articles"==$ads ? "_act" : false).'"><a onClick="cab_link(\'articles\');">Каталог статей</a></td>';
			echo '<td width="33%" class="cab_menu"></td>';
		echo '</tr>';*/
		
		echo '</table>';

		echo '<br><table class="tables">';
		if($cab_status==1) {
			echo '<tr>';
				echo '<td align="left" style="border-right:0px;">Ваша скидка на размещение рекламы [ <a onclick="InfoCab();" style="cursor:pointer;">подробнее</a> ] :</td>';
				echo '<td align="right" style="border-left:0px; border-right:0px; padding-right:0px;"><b style="color:#008B00;">'.$cab_skidka.'</b></td>';
				echo '<td align="left" style="border-left:0px; width:5px; padding-left:3px;"">%</td>';
			echo '</tr>';
		}
		echo '<tr>';
			echo '<td align="left" style="border-right:0px;">Потрачено на размещение рекламы:</td>';
			echo '<td align="right" style="border-left:0px; border-right:0px; padding-right:0px;"><b style="color:#114C5B;">'.number_format($money_user_rek, 2, ".", " ").'</b></td>';
			echo '<td align="left" style="border-left:0px; width:5px; padding-left:3px;">руб.</td>';
		echo '</tr>';
		if($cab_skidka<$cab_max_proc && $cab_status==1) {
			echo '<tr>';
				echo '<td align="left" style="border-right:0px;">Для получения скидки <b style="color:#008B00;">'.$cab_skidka_next.'</b>% необходимо потратить:</td>';
				echo '<td align="right" style="border-left:0px; border-right:0px; padding-right:0px;"><b style="color:#114C5B;">'.number_format($nado_moneys, 2, ".", " ").'</b></td>';
				echo '<td align="left" style="border-left:0px; width:5px; padding-left:3px;"">руб.</td>';
			echo '</tr>';
		}
		echo '<tr>';
			echo '<td align="left" style="border-right:0px;">Бюджет рекламного счета [ <a href="/money_add.php">пополнить</a> ] :</td>';
			echo '<td align="right" style="border-left:0px; border-right:0px; padding-right:0px;"><b style="color:#00649E; font-size:14px;">'.number_format($money_user_rb, 2, ".", " ").'</b></td>';
			echo '<td align="left" style="border-left:0px; width:5px; padding-left:3px;"">руб.</td>';
		echo '</tr>';
		echo '</table><br><br>';
		

		switch($ads) {
			case("dlink"): 		include(DOC_ROOT."/cabinet/adv/dlink/dlink.php"); break;
			case("psevdo"): 	include(DOC_ROOT."/cabinet/adv/psevdo/psevdo.php"); break;
			case("autoserf"): 	include(DOC_ROOT."/cabinet/adv/autoserf/autoserf.php"); break;
			case("statlink"): 	include(DOC_ROOT."/cabinet/adv/statlink/statlink.php"); break;
			case("kontext"): 	include(DOC_ROOT."/cabinet/adv/kontext/kontext.php"); break;
			case("banners"): 	include(DOC_ROOT."/cabinet/adv/banners/banners.php"); break;
			case("txtob"): 		include(DOC_ROOT."/cabinet/adv/txtob/txtob.php"); break;
			case("frmlink"): 	include(DOC_ROOT."/cabinet/adv/frmlink/frmlink.php"); break;
			case("sentmails"): 	include(DOC_ROOT."/cabinet/adv/sentmails/sentmails.php"); break;
			case("rekcep"): 	include(DOC_ROOT."/cabinet/adv/rekcep/rekcep.php"); break;
            case("pay_row"): 	include(DOC_ROOT."/cabinet/adv/pay_row/pay_row.php"); break;
			case("loadlink"): 	include(DOC_ROOT."/cabinet/adv/loadlink/loadlink.php"); break;
			case("quest"): 		include(DOC_ROOT."/cabinet/adv/quest/quest.php"); break;
			case("mails"): 		include(DOC_ROOT."/cabinet/adv/mails/mails.php"); break;
			case("begstroka"): 	include(DOC_ROOT."/cabinet/adv/begstroka/begstroka.php"); break;
            case("statkat"): 	include(DOC_ROOT."/cabinet/adv/statkat/statkat.php"); break;
			case("vopros"): 	include(DOC_ROOT."/cabinet/adv/vopros/vopros.php"); break;
			case("articles"): 	include(DOC_ROOT."/cabinet/adv/articles/articles.php"); break;
			case("tests"):	 	include(DOC_ROOT."/cabinet/adv/tests/tests.php"); break;
			case("youtube"):	 	include(DOC_ROOT."/cabinet/adv/youtube/youtube.php"); break;
			case("youautoserf"): 	include(DOC_ROOT."/cabinet/adv/autoserf_you/autoserf_you.php"); break;
			case("pay_visits"):	include(DOC_ROOT."/cabinet/adv/pay_visits/pay_visits.php"); break;
			default: 		include(DOC_ROOT."/cabinet/adv/dlink/dlink.php"); break;
		}
	}else{
		echo '<span class="msg-error">Ошибка чтения данных!</span>';
		include(DOC_ROOT."/footer.php");
		exit();
	}
}else{
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(DOC_ROOT."/footer.php");
	exit();
}

?>