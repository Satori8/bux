<?php
if(!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
?>

<script type="text/javascript" language="JavaScript"> 
	function ShowHideBlock(id) {
		if($("#adv-title"+id).attr("class") == "adv-title-open") {
			$("#adv-title"+id).attr("class", "adv-title-close")
		} else {
			$("#adv-title"+id).attr("class", "adv-title-open")
		}
		$("#adv-block"+id).slideToggle("slow");
	}
</script>

<?php
$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace("'","&#039;",$mensaje);
	$mensaje = str_replace("`","&#096;",$mensaje);
	$mensaje = str_replace("$","&#036;",$mensaje);
	$mensaje = str_replace('"',"&#034;",$mensaje);
	$mensaje = str_replace("  "," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	$mensaje = str_replace("&amp;amp;","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	$mensaje = str_replace("&amp;quot;","&quot;",$mensaje);
	return $mensaje;
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_status' AND `howmany`='1'");
$bonus_surf_status = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_money' AND `howmany`='1'");
$bonus_surf_cnt_money = round(mysql_result($sql,0,0), 2);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bonus_surf_cnt_visits' AND `howmany`='1'");
$bonus_surf_cnt_visits = number_format(mysql_result($sql,0,0), 0, ".", "");

$plan = isset($my_bonus_surf) ? $my_bonus_surf : 0;
$timer = 5;
$type_serf = 1;
$method_pay = "-3";

if(count($_POST)<=0) {
	echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:6px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Бонусный серфинг - что это?</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
			echo 'Для получения бонусных показов в серфинге на <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b> Вам необходимо потратить на размещения любого вида рекламы (кроме заданий и тестов) не менее <b>'.$bonus_surf_cnt_money.'</b> руб. ';
			echo 'За каждые полные <b>'.$bonus_surf_cnt_money.'</b> руб. потраченные на рекламу вам будет начислено <b>'.$bonus_surf_cnt_visits.'</b> бонусных показов в серфинге.<br>';
			echo 'Посмотреть количество бонусных показов в серфинге на сегодняшний день можно в кабинете управления рекламой.<br><br>';
			echo '<span class="text-red">Внимание!</span> Воспользоваться бонусными показами в серфинге предоставляется только в день заказа рекламы, тоесть, если вы закажите рекламу сегодня и получите бонусные показы согласно условиям предоставления бонусных показов, то воспользоваться бонусными показами в серфинге возможно только в этот день, на следующий день бонусные показы сгорают!';
		echo '</div>';
	echo '</div>';
}

if(!isset($_SESSION["userLog"], $_SESSION["userPas"])) {
	echo '<span class="msg-error">Для использования бонусных показов в серфинге необходимо авторизоваться!</span>';
	exit(include(ROOT_DIR."/footer.php"));

}elseif($bonus_surf_status != 1) {
	echo '<span class="msg-error">Функция использования бонусных показов в серфинге временно не доступна, заходите позже!</span>';
	exit(include(ROOT_DIR."/footer.php"));

}elseif(isset($my_ban_date) && $my_ban_date > 0) {
	echo '<span class="msg-error">Ваш аккаунт заблокирован за нарушение правил проекта, доступ в этот раздел Вам запрещен!</span>';
	exit(include(ROOT_DIR."/footer.php"));

}elseif(isset($my_bonus_surf) && $my_bonus_surf <= 0) {
	echo '<span class="msg-w">У Вас нет бонусных показов в серфинге, выполните условия предоставления бонусных показов!</span>';
	exit(include(ROOT_DIR."/footer.php"));
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	$id_pay = (isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"]))) ? intval(limpiar(trim($_POST["id_pay"]))) : false;

	$sql_id = mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_id)>0) {
		mysql_query("UPDATE `tb_users` SET `bonus_surf`='0' WHERE `username`='$username'") or die(mysql_error());
		mysql_query("UPDATE `tb_ads_dlink` SET `status`='1', `date`='".time()."' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

		ads_date();

		echo '<span class="msg-ok">Реклама успешно добавлена!<br>Спасибо, что пользуетесь услугами нашего сервиса!</span>';
		exit(include(ROOT_DIR."/footer.php"));
	}else{
		echo '<span class="msg-error">Реклама не найдена!</span>';
		exit(include(ROOT_DIR."/footer.php"));
	}
}

if(count($_POST)>0) {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),60) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$content = (isset($_POST["content"]) && (intval($_POST["content"])==0 | intval($_POST["content"])==1)) ? intval($_POST["content"]) : "0";
	$color = 0;
	$active = 0;
	$country = false;
	$revisit = 0;
	$nolimitdate = 0;
	$limit_d = $plan;
	$limit_h = $plan;
	$laip = getRealIP();
	$black_url = StringUrl($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen` IN ($black_url)");

	if($title==false) {
		echo '<span class="msg-error">Вы не указали заголовок ссылки!</span>';
	}elseif($description==false) {
		echo '<span class="msg-error">Вы не указали описание ссылки!</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">Вы не указали URL-адрес сайта!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">URL-адрес сайта указан неверно!</span>';
	}elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif(mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		echo '<span class="msg-error">Сайт '.$row_bl["domen"].' находится в черном списке проекта '.strtoupper($_SERVER["HTTP_HOST"]).'</span>';

	}else{
		$color_to[0]="НЕТ";
		$color_to[1]="ДА";

		$content_to[0]="НЕТ";
		$content_to[1]="ДА";

		$active_to[0]="НЕТ";
		$active_to[1]="ДА";

		$revisit_to[0] = "Каждые 24 часа";
		$revisit_to[1] = "Каждые 48 часов";
		$revisit_to[2] = "1 раз";

		$timer_to = "$timer";

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());
		mysql_query("DELETE FROM `tb_ads_dlink` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_dlink` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`type_serf`='$type_serf',`date`='".time()."',`wmid`='$wmid_user',`username`='$username',`geo_targ`='$country',`content`='$content',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`nolimit`='0',`limit_d`='$limit_d',`limit_h`='$limit_h',`limit_d_now`='$limit_d',`limit_h_now`='$limit_h',`url`='$url',`title`='$title',`description`='$description',`plan`='$plan',`totals`='$plan',`ip`='$laip',`money`='0' WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_dlink` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`type_serf`,`date`,`wmid`,`username`,`geo_targ`,`content`,`active`,`revisit`,`color`,`timer`,`nolimit`,`limit_d`,`limit_h`,`limit_d_now`,`limit_h_now`,`url`,`title`,`description`,`plan`,`totals`,`ip`,`money`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$type_serf','".time()."','$wmid_user','$username','$country','$content','$active','$revisit','$color','$timer','$nolimitdate','$limit_d','$limit_h','$limit_d','$limit_h','$url','$title','$description','$plan','$plan','$laip','0')") or die(mysql_error());
		}
		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_dlink` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">Параметры заказа!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="200"><b>Счет №:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>Заголовок ссылки:</b></td><td><a href="'.$url.'" target="_blank">'.$title.'</a></td></tr>';
			echo '<tr><td><b>Краткое описание ссылки:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>URL сайта:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>Количество визитов:</b></td><td>'.$plan.'</td></tr>';
			echo '<tr><td><b>Таймер, сек.:</b></td><td>'.$timer_to.'</td></tr>';
			echo '<tr><td><b>Выделение цветом:</b></td><td>'.$color_to[$color].'</td></tr>';
			echo '<tr><td><b>Активное окно:</b></td><td>'.$active_to[$active].'</td></tr>';
			echo '<tr><td><b>Доступно для просмотра:</b></td><td>'.$revisit_to[$revisit].'</td></tr>';
			echo '<tr><td><b>Контент "18+":</b></td><td>'.$content_to[$content].'</td></tr>';
			echo '<tr><td><b>Геотаргетинг:</b></td><td>Все страны</td></tr>';
		echo '</table>';

		echo '<table class="tables"><tr align="center"><td>';
			echo '<form action="" method="post" id="newform">';
				echo '<input type="hidden" name="id_pay" value="'.$id_zakaz.'">';
				echo '<input type="hidden" name="method_pay" value="'.$method_pay.'">';
				echo '<input type="submit" value="Разместить ссылку" class="sd_sub big green" style="float:none;">';
			echo '</form>';
		echo '</td></tr></table>';
	}
}else{
	?>
	<script type="text/javascript" language="JavaScript"> 

	function gebi(id){
		return document.getElementById(id)
	}

	function SbmFormB() {
		arrayElem = document.forms["formzakaz"];
		var col=0;

		for (var i=0;i<arrayElem.length;i++){
			if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')|(document.forms["formzakaz"].url.value == 'https://')) {
				alert('Вы не указали URL-адрес сайта');
				arrayElem[i+0].style.background = "#FFDBDB";
				arrayElem[i+0].focus();
				return false;
			}else{
				arrayElem[i+0].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].title.value == '')) {
				alert('Вы не указали заголовок ссылки');
				arrayElem[i+1].style.background = "#FFDBDB";
				arrayElem[i+1].focus();
				return false;
			}else{
				arrayElem[i+1].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].description.value == '')) {
				alert('Вы не указали краткое описание ссылки');
				arrayElem[i+2].style.background = "#FFDBDB";
				arrayElem[i+2].focus();
				return false;
			}else{
				arrayElem[i+2].style.background = "#FFFFFF";
			}
		}

		document.forms["formzakaz"].submit();
		return true;
	}

	</script>

	<?php

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
		echo '<thead><th colspan="2" class="top">Форма размещения бонусных показов в серфинге</th></thead>';
		echo '<tbody>';
		echo '<tr>';
			echo '<td width="240" align="left"><b>URL сайта</b> (включая http:// или https://)</td>';
			echo '<td align="left"><input type="url" name="url" maxlength="300" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Заголовок ссылки</b></td>';
			echo '<td align="left"><input type="text" name="title" maxlength="60" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>Краткое описание ссылки</b></td>';
			echo '<td align="left"><input type="text" name="description" maxlength="60" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Количество просмотров</td>';
			echo '<td align="left"><b>'.$plan.'</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Время просмотра</td>';
			echo '<td align="left"><b>'.$timer.'</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Выделение цветом</td>';
			echo '<td align="left"><b>Нет</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Активное окно</td>';
			echo '<td align="left"><b>Нет</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Доступно для просмотра</td>';
			echo '<td align="left"><b>Каждые 24 часа</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left">Контент 18+</td>';
			echo '<td align="left"><input type="checkbox" name="content" value="1"> - на моем сайте присутствуют материалы для взрослых</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2" align="center"><input type="submit" value="Сохранить" class="proc-btn" style="float:none;" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';
}

?>