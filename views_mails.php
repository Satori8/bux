<?php
$pagetitle="Просмотр рекламных писем";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}

require('config.php');
include('includes/stat_links.php');
echo "<br>";

?><script type="text/javascript">
function ShowHide(id) {
	if(document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
	return false;
}

function test_virus(id, tid, op) { 
	$.ajax({  
		type: "POST", url: "/ajax/test_virus.php", data: { 'op' : $.trim(op), 'id' : $.trim(id) }, 
		beforeSend: function(){	$('#loading').show(); }, success: function(data) { $('#loading').hide(); $('#t_virus'+tid).hide(); alert(data); }  
	});
}

</script><?php


$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(strtolower(stripslashes(trim($_SESSION["userLog"])))) : false;
$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='1'");
$cena_mails_1 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='2'");
$cena_mails_2 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='3'");
$cena_mails_3 = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
$cena_mails_color = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
$cena_mails_active = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
$cena_mails_gotosite = mysql_result($sql,0,0);


if($id!=false) {
	$sql_id = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `status`='1'");
	if(mysql_num_rows($sql_id)>0) {
		$rowid = mysql_fetch_array($sql_id);

		$sql_v = mysql_query("SELECT `id` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `ident`='$id' AND `date`>=UNIX_TIMESTAMP()-24*60*60 ORDER BY `id` DESC") or die(mysql_error());
		if(mysql_num_rows($sql_v)>0) {
			echo '<span class="msg-error">Вы уже просматривали это письмо в течении 24 часов!</span><br>';
		}else{
			$title = $rowid["title"];
			$url = $rowid["url"];
			$description = $rowid["description"];
			$question = $rowid["question"];
			$answer_t = $rowid["answer_t"];
			$answer_f_1 = $rowid["answer_f_1"];
			$answer_f_2 = $rowid["answer_f_2"];
			$plan = $rowid["plan"];
			$tarif = $rowid["tarif"];
			$mailsre = $rowid["mailsre"];
			$totals = $rowid["totals"];

			if($mailsre==0) {
				$next_time = "2147483647";
			}else{
				$next_time = time();
			}

			if(count($_POST)>0) {
				$answer = ( isset($_POST["answer"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{32,48}$/i", trim($_POST["answer"]))==1 ) ? limpiar(trim($_POST["answer"])) : false;

				if($answer!=false && $answer==md5("$answer_t+2205+$username")) {
					echo '<script type="text/javascript">location.replace("http://'.$_SERVER["HTTP_HOST"].'/view_mails.php?id='.$id.'&hash='.$answer.'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=http://'.$_SERVER["HTTP_HOST"].'/view_mails.php?id='.$id.'&hash='.$answer.'"></noscript>';
					include('footer.php');
					exit();
				}else{
					mysql_query("INSERT INTO `tb_ads_mails_visits` (`username`,`ident`,`type`,`date`,`ip`, `money`,`test`) 
					VALUES('$username','$id','1','$next_time','$ip','0','Неверно введен ответ на контрольный вопрос')") or die(mysql_error());

					echo '<span class="msg-error">Вы указали неверный ответ! Письмо Вам не засчитано! Сегодня письмо не будет доступно Вам для просмотра!</span><br>';
					include('footer.php');
					exit();
				}
			}

			$answers = array($answer_t, $answer_f_1, $answer_f_2);
			shuffle($answers);

			require_once ("bbcode/bbcode.lib.php");
			$description = new bbcode($description);
			$description = $description->get_html();
			$description = str_replace("&amp;", "&", $description);

			echo '<table class="tables" style="box-shadow: 1px 2px 4px 3px rgba(0, 0, 0, 0.4);">';
			echo '<thead><tr><th align="center">'.$title.'</th></tr></thead>';

			echo '<tr><td align="left">';
				echo '<h2 class="sp" style="margin-top:5px">Содержание письма:</h2>';
				echo "$description<br><br>";

				echo '<div style="font-size: 12px; color:#89A688; text-align:center;">Будет произведён переход на сайт<br>'.$url.'</div><br>';
			echo '</td></tr>';

			echo '<thead><tr><th align="center">Контрольный вопрос</th></tr></thead>';

			echo '<tr><td align="center">';
				echo '<h2 class="sp" style="margin-top:5px">Для прочтения письма ответьте на вопрос:</h2><br>'.$question.'<br><br>';

				echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?id='.$id.'">';
				echo '<table class="tables" style="width:auto; margin:0 auto;">';
					echo '<input type="hidden" name="id" value="'.$id.'">';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5("$answers[0]+2205+$username").'"></td><td style="border:none;">'.$answers[0].'</td></tr>';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5("$answers[1]+2205+$username").'"></td><td style="border:none;">'.$answers[1].'</td></tr>';
					echo '<tr><td align="right" width="10" style="border:none;"><input type="radio" name="answer" value="'.md5("$answers[2]+2205+$username").'"></td><td style="border:none;">'.$answers[2].'</td></tr>';
				echo '</table><br>';
				echo '<div align="center"><input type="submit" class="submit" value="Подтвердить ответ"></div>';
				echo '</form><br>';

			echo '</td></tr>';
			echo '</table><br><br>';
		}
	}else{
		echo '<span class="msg-error">Рекламное письмо не найдено.</span><br>';
	}

	include('footer.php');
	exit();
}


function format_mails(
	$id, $title, $url, $plan, $tarif, $color, $active, $gotosite, $mailsre, $totals, $members, $claims, $pay_user, 
	$cena_mails_color, $cena_mails_active, $cena_mails_gotosite, $virus_id
){
	if($tarif==1) {
		$timer=60;
	}elseif($tarif==2){
		$timer=40;
	}elseif($tarif==3){
		$timer=20;
	}else{
		$timer=20;
	}

	$cena_click = $pay_user + ($color * $cena_mails_color) + ($active * $cena_mails_active) + ($gotosite * $cena_mails_gotosite) ;

	$status_color[0] = 'class="blocks1"';
	$status_color[1] = 'class="blocks2"';
	$status_active[0] = '<img src="img/goserf.png" alt="" title="Стандартный просмотр" width="16" height="16" style="margin:1px 0; padding:0;" />';
	$status_active[1] = '<img src="img/active.png" alt="" title="Просмотр ссылки только в активном окне" width="16" height="16" style="margin:1px 0; padding:0;" />';

	echo '<tr>';
		echo '<td class="td-serf" valign="top" style="text-align:center; width:18px; padding-left:10px;">';
			echo $status_active[$active];
			if($claims>10) {
				echo '<img src="img/protect_red.png" alt="" title="Пожаловаться на рекламу" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}else{
				echo '<img src="img/protect_green.png" alt="" title="Пожаловаться на рекламу" width="16" height="16" style="margin:1px 0; padding:0;" />';
			}
		echo '</td>';

		echo '<td align="left" valign="top" class="td-serf" style="cursor:pointer;">';
			echo '<div id="serf_none'.$id.'">';
				echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" src="https://www.google.com/s2/favicons?domain='.@gethost($url).'" align="absmiddle" />';
				echo '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'" '.$status_color[$color].' style="cursor: pointer; font-weight:normal;" target="_blank">'.$title.'<br>';
					echo '<span style="color:#89A688;">'.@gethost($url).'</span>';
/*
					if($tarif==1) echo '<span style="color:#FF0000; text-shadow: 1px 1px 1px #86B5D2;">vip</span>';
					if($tarif==2) echo '<span style="color:#0000FF; text-shadow: 1px 1px 1px #86B5D2;">standart</span>';
					if($tarif==3) echo '<span style="color:#198719; text-shadow: 1px 1px 1px #86B5D2;">lite</span>';
*/
				echo '</a>';
			echo '</div>';

			echo '<div id="info_'.$id.'" style="display: none; margin:0 20px; color:#778899;">';
				echo 'Сайт: <a href="'.$url.'" target="_blank">'.$url.'</a><br>';
				echo 'Заказано: '.number_format($plan, 0, ".", " ").'.<br>';
				echo 'Осталось: '.number_format($totals, 0, ".", " ").'.<br>';
				echo 'Прочитали: '.number_format($members, 0, ".", " ").'.<br>';
				echo 'Таймер: '.$timer.'. Жалобы: '.$claims.'.';
			echo '</div>';
		echo '</td>';

		echo '<td class="td-serf" nowrap="nowrap" valign="top" style="text-align:right; width:110px; padding-right:10px;">';
			echo '<span title="Осталось визитов '.number_format($totals, 0, ".", " ").'" style="cursor: help; font-size: 11px; color: #89A688;">('.number_format($totals, 0, ".", " ").')</span>&nbsp;';
			echo '<span title="Стоимость просмотра '.$cena_click.' руб." style="cursor:help; color:#135B11;">'.$cena_click.'</span><br>';
			echo '<a class="workcomp" href="/add_claims_m.php?id='.$id.'" onClick="add_claims(\''.$id.'\');" title="Пожаловаться на рекламу" target="_blank"></a>';
			echo '<a id="t_virus'.$virus_id.'" class="workvir" onClick="test_virus(\''.$id.'\', \''.$virus_id.'\', \'mails\');" title="Проверить ссылку на вирусы" target="_blank"></a>';
			echo '<a class="workquest" onClick="ShowHide(\'info_'.$id.'\');" title="Подробная информация" target="_blank"></a>';
		echo '</td>';
	echo '</tr>';
}

$count_sites = 0;
$virus_id = 0;

echo '<br><table class="table-serf"><tbody>';
echo '<tr><td align="center" class="td-serf" colspan="3"><h2 class="sp" style="margin:0; padding:0;"><strong>Зарабатывай, читая письма рекламодателей.</strong></h2></td></tr>';
echo '</tbody></table>';

echo '<table class="table-serf"><tbody>';
$sql_links = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='1' AND `tarif`='1' AND `totals`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `date`>=UNIX_TIMESTAMP()-24*60*60) ORDER BY `id` DESC");
if(mysql_num_rows($sql_links)>0) {
	while($links_row = mysql_fetch_assoc($sql_links)) {
		$virus_id++;

		format_mails(
			$links_row['id'],
			$links_row['title'],
			$links_row['url'],
			$links_row['plan'],
			$links_row['tarif'],
			$links_row['color'],
			$links_row['active'],
			$links_row['gotosite'],
			$links_row['mailsre'],
			$links_row['totals'],
			$links_row['members'],
			$links_row['claims'],
			$cena_mails_1, $cena_mails_color, $cena_mails_active, $cena_mails_gotosite, $virus_id
		);
	}
	$count_sites++;
}
echo '</tbody></table>';


echo '<table class="table-serf"><tbody>';
$sql_links = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='1' AND `tarif`='2' AND `totals`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `date`>=UNIX_TIMESTAMP()-24*60*60) ORDER BY `id` DESC");
if(mysql_num_rows($sql_links)>0) {
	while($links_row = mysql_fetch_assoc($sql_links)) {
		$virus_id++;

		format_mails(
			$links_row['id'],
			$links_row['title'],
			$links_row['url'],
			$links_row['plan'],
			$links_row['tarif'],
			$links_row['color'],
			$links_row['active'],
			$links_row['gotosite'],
			$links_row['mailsre'],
			$links_row['totals'],
			$links_row['members'],
			$links_row['claims'],
			$cena_mails_2, $cena_mails_color, $cena_mails_active, $cena_mails_gotosite, $virus_id
		);
	}
	$count_sites++;
}
echo '</tbody></table>';

echo '<table class="table-serf"><tbody>';
$sql_links = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `status`='1' AND `tarif`='3' AND `totals`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_mails_visits` WHERE `username`='$username' AND `type`='1' AND `date`>=UNIX_TIMESTAMP()-24*60*60) ORDER BY `id` DESC");
if(mysql_num_rows($sql_links)>0) {
	while($links_row = mysql_fetch_assoc($sql_links)) {
		$virus_id++;

		format_mails(
			$links_row['id'],
			$links_row['title'],
			$links_row['url'],
			$links_row['plan'],
			$links_row['tarif'],
			$links_row['color'],
			$links_row['active'],
			$links_row['gotosite'],
			$links_row['mailsre'],
			$links_row['totals'],
			$links_row['members'],
			$links_row['claims'],
			$cena_mails_3, $cena_mails_color, $cena_mails_active, $cena_mails_gotosite, $virus_id
		);
	}
	$count_sites++;
}
echo '</tbody></table>';

if($count_sites==0) {
	echo '<div align="center" style="background: url(img/light.jpg) no-repeat top center; font-size: 14px; color: #135B11; text-align: center; padding-top: 120px; margin-top: 20px; display: block;">Доступных к просмотру рекламных писем нет.<br>Заходите позже</div><br>';
	echo '<div align="center"><a href="/advertise.php?ads=mails" class="sub-blue160" style="float:none; width:160px">Разместить письмо</a></div><br><br><br>';
}else{
	echo '<br><br><br>';
	echo '<table align="center">';
	echo '<tr>';
	echo '<td width="16" align="center"><img src="img/goserf.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
		echo '<td align="left">Обычный просмотр сайта</td>';
		echo '<td width="50"></td>';
		echo '<td width="16" align="center"><img src="img/active.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
		echo '<td align="left">Просмотр сайта только в активном окне</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td width="16" align="center"><img src="img/protect_green.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
		echo '<td align="left">Реклама безопасна для просмотра</td>';
		echo '<td width="50"></td>';
		echo '<td width="16" align="center"><img src="img/protect_red.png" alt="" width="16" height="16" style="margin:0; padding:0;" /></td>';
		echo '<td align="left">Реклама опасна для просмотра</td>';
	echo '</tr>';
	echo '</table>';
}

include("footer.php");?>