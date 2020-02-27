<?php
$pagetitle="Мои рефералы";
require_once('.zsecurity.php');
include('header.php');
require("navigator.php");

error_reporting (E_ALL);
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	require('config.php');

	function ref_status($my_reiting){
		$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_array($sql_rang);
		}else{
			$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
			$row_rang = mysql_fetch_array($sql_rang);
		}
		return '<span style="cursor:pointer; color: #006699;" title="Статус"><b>'.$row_rang["rang"].'</b></span>';
	}

	$attestat[0]   = '';
	$attestat[-1]   = '';
	$attestat[1]   = '<img src="/img/att/att_1.ico"    alt="" title="Аттестат Расчетного автомата" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[100] = '<img src="/img/att/att_100.ico"  alt="" title="Аттестат Псевдонима" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[110] = '<img src="/img/att/att_110.ico"  alt="" title="Формальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[120] = '<img src="/img/att/att_120.ico"  alt="" title="Начальный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[130] = '<img src="/img/att/att_130.ico"  alt="" title="Персональный Аттестат" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[135] = '<img src="/img/att/att_135.ico"  alt="" title="Аттестат Продавца" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[136] = '<img src="/img/att/att_136.ico"  alt="" title="Аттестат Capitaller" align="absmiddle" width="16" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[140] = '<img src="/img/att/att_140.ico"  alt="" title="Аттестат Разработчика" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[150] = '<img src="/img/att/att_150.ico"  alt="" title="Аттестат Регистратора" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[170] = '<img src="/img/att/att_170.ico"  alt="" title="Аттестат Сервиса WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[190] = '<img src="/img/att/att_190.ico"  alt="" title="Аттестат Гаранта WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';
	$attestat[300] = '<img src="/img/att/att_300.ico"  alt="" title="Аттестат Оператора WMT" width="16" align="absmiddle" height="16" style="margin:0; padding:0; border:none;">';

	$metode = false;
	$search = false;
	if(isset($_POST["search"]) && isset($_POST["metode"])) {
		$metode = isset($_POST["metode"]) ? limpiar(trim($_POST["metode"])) : false;
		$search = isset($_POST["search"]) ? limpiar(trim($_POST["search"])) : false;;

		if($search!="" && $metode!="") {
			$WHERE = "`$metode` LIKE '%$search%' AND ";
			$WHERE_to_get = "&metode=$metode&search=$search";
		}else{
			$WHERE = false;
			$WHERE_to_get = false;
		}
	}else{
		$WHERE = false;
		$WHERE_to_get = false;
	}
	if(isset($_GET["search"]) && isset($_GET["metode"])) {
		$metode = isset($_GET["metode"]) ? limpiar(trim($_GET["metode"])) : false;
		$search = isset($_GET["search"]) ? limpiar(trim($_GET["search"])) : false;;

		if($search!="" && $metode!="") {
			$WHERE = "`$metode` LIKE '%$search%' AND ";
			$WHERE_to_get = "&metode=$metode&search=$search";
		}else{
			$WHERE = false;
			$WHERE_to_get = false;
		}
	}


	$page = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];

	echo '<script type="text/javascript" src="../scripts/jqpooop.js"></script>';

	function limpiarez($mensaje){
		$mensaje = trim($mensaje);
		$mensaje = str_replace("?","&#063;",$mensaje);
		$mensaje = str_replace(">","&#062;",$mensaje);
		$mensaje = str_replace("<","&#060;",$mensaje);
		$mensaje = str_replace("'","&#039;",$mensaje);
		$mensaje = str_replace("$","&#036;",$mensaje);
		$mensaje = str_replace('"',"&#034;",$mensaje);
		return $mensaje;
	}

	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
	$uid = (isset($_POST["uid"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["uid"]))) ? intval(limpiar(trim($_POST["uid"]))) : false;
	$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
	//echo '<a href="javascript: void(0);" onclick="reactref(\''.$id.'\');"></a>';
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');

  $mail_out = new mailPHP();

	if(isset($_POST["uids"])) {
		$mess_for_ref = (isset($_POST["mess_for_ref"])) ? limitatexto(limpiarez($_POST["mess_for_ref"]),2000).". " : false;
		$mess_for_ref = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mess_for_ref);
		$mess_for_ref = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mess_for_ref);
		$mess_for_ref = preg_replace("#<a\s+href=['|\"]?([^\#]+?)['|\"]?\s.*>(?!<img).+?</a>#i","",$mess_for_ref);
		$mess_for_ref = preg_replace("#http://[^\s]+#i", "", $mess_for_ref);
		$mess_for_ref = preg_replace("#www\.[-\d\w\._&\?=%]+#i", "", $mess_for_ref);

		$domen = strtoupper($_SERVER['HTTP_HOST']);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref' AND `howmany`='1'");
		$cena_mail_reactref = mysql_result($sql,0,0);

		$sql_c = mysql_query("SELECT `id`,`username`,`email` FROM `tb_users` WHERE `referer`='$username' AND `lastlogdate2`<'".(time()-7*24*60*60)."' AND `lastdate_reactref`<'".(time()-5*24*60*60)."'");
		$all = mysql_num_rows($sql_c);
		if($all>0) {
			$summa = $all * $cena_mail_reactref;

			if($money_rb<$summa) {
				echo '<span class="msg-error">Ошибка! На вашем рекламном счету недостаточно средств!</span>';
			}else{
				$subj = "Информационное сообщение системы $domen";

				$headers = "MIME-Version: 1.0\r\n";
				$headers.= "Content-Type: text/html; charset=windows-1251\r\n";
				$headers.= "From: $domen <$domen@$domen>\r\n";
				$headers.= "FromName: $domen\r\n";
                                $headers.= "X-Mailer: PHP/".phpversion();

				while ($row_c = mysql_fetch_array($sql_c)) {
					$referal = $row_c["username"];
					$email_tab = $row_c["email"];
					
					$var = array('{LOGIN}','{LOGIN_REF}','{TEXT}');
		      $zamena = array($referal, $username, $mess_for_ref);
		      $msgtext = str_replace($var, $zamena, $email_temp['budil']);
	      	
					if(!$mail_error = $mail_out->send($email_tab,$referal,iconv("CP1251", "UTF-8", 'Информационное сообщение системы '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
						mysql_query("UPDATE `tb_users` SET `lastdate_reactref`='".time()."' WHERE `username`='$referal'") or die(mysql_error());

						$s="OK";
					}else{
						$s="NO";
					}
				}

				if($s="OK") {
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$summa' WHERE `username`='$username'") or die(mysql_error());
					mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$summa', 'Оплата за функцию - разбудить всех рефералов','Списано','rashod')") or die(mysql_error());

					echo '<span class="msg-ok">Сообщение успешно отправлено на e-mail!</span>';
				}else{
					echo '<span class="msg-error">Ошибка! Не удалось отправить сообщение!</span>';
				}
			}
		}else{
			echo '<span class="msg-error">Нет рефералов для отправки им сообщений!</span>';
		}
	}

	if($uid!=false) {
		$mailtowm = (isset($_POST["mailtowm"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["mailtowm"]))) ? intval(limpiarez(trim($_POST["mailtowm"]))) : false;
		$mess_for_ref = (isset($_POST["mess_for_ref"])) ? limitatexto(limpiarez($_POST["mess_for_ref"]),2000).". " : false;

		$mess_for_ref = preg_replace("#([-0-9a-z_\.]+@[-0-9a-z_\.]+\.[a-z]{2,6})#i", "", $mess_for_ref);
		$mess_for_ref = preg_replace("'<script[^>]*?>.*?</script>'si", "", $mess_for_ref);
		$mess_for_ref = preg_replace("#<a\s+href=['|\"]?([^\#]+?)['|\"]?\s.*>(?!<img).+?</a>#i","",$mess_for_ref);
		$mess_for_ref = preg_replace("#http://[^\s]+#i", "", $mess_for_ref);
		$mess_for_ref = preg_replace("#www\.[-\d\w\._&\?=%]+#i", "", $mess_for_ref);

		$domen = $_SERVER["HTTP_HOST"];

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref' AND `howmany`='1'");
		$cena_mail_reactref = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_reactref_wm' AND `howmany`='1'");
		$cena_mail_reactref_wm = mysql_result($sql,0,0);


		$sql_c = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$uid' AND `referer`='$username'");
		if(mysql_num_rows($sql_c)>0) {
			$row_c = mysql_fetch_array($sql_c);

			$referal = $row_c["username"];
			$lastdate_reactref = $row_c["lastdate_reactref"];
			$email_tab = $row_c["email"];
			$wmid_tab =  $row_c["wmid"];

			if($lastdate_reactref>(time()-5*24*60*60)) {
				echo '<span class="msg-error">Вы уже отправляли этому рефералу сообщение за последние 5 дней!</span>';
			}else{
				$subj = "Информационное сообщение системы $domen";
				$msg = "Здравствуйте!\nВы зарегистрированы на сайте http://$domen/ ваш логин <b>$referal</b>.\nВы давно не заходили в свой аккаунт и ваш реферер <b>$username</b> отправил вам напоминание.\n$mess_for_ref\n\n";
				$msg.= "* Это автоматическое информационное сообщение, отвечать на него необязательно (http://$domen/)";

				if($mailtowm==1) {
					if($money_rb<$cena_mail_reactref_wm) {
						echo '<span class="msg-error">Ошибка! На вашем рекламном счету недостаточно средств!</span>';
					}else{
						require_once("auto_pay_req/wmxml.inc.php");
						$r = _WMXML6($wmid_tab,$msg,$subj);

						if($r["retval"]==0) {
							mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_mail_reactref_wm' WHERE `username`='$username'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `lastdate_reactref`='".time()."' WHERE `username`='$referal'") or die(mysql_error());
							mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$cena_mail_reactref_wm', 'Оплата за функцию - разбудить реферала $referal','Списано','rashod')") or die(mysql_error());

							echo '<span class="msg-ok">Сообщение успешно отправлено на WMID!</span>';
						}else{
							$var = array('{LOGIN}','{LOGIN_REF}','{TEXT}');
				      $zamena = array($referal, $username, $mess_for_ref);
				      $msgtext = str_replace($var, $zamena, $email_temp['budil']);
			      	
							if(!$mail_error = $mail_out->send($email_tab, $referal, iconv("CP1251", "UTF-8", 'Информационное сообщение системы '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
								mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_mail_reactref' WHERE `username`='$username'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `lastdate_reactref`='".time()."' WHERE `username`='$referal'") or die(mysql_error());
								mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$cena_mail_reactref', 'Оплата за функцию - разбудить реферала $referal','Списано','rashod')") or die(mysql_error());

								echo '<span class="msg-ok">Сообщение успешно отправлено на e-mail!</span>';
							}else{
								echo '<span class="msg-error">Ошибка! Не удалось отправить сообщение!</span>';
							}
						}
					}
				}else{
					if($money_rb<$cena_mail_reactref_wm) {
						echo '<span class="msg-error">Ошибка! На вашем рекламном счету недостаточно средств!</span>';
					}else{
						$var = array('{LOGIN}','{LOGIN_REF}','{TEXT}');
			      $zamena = array($referal, $username, $mess_for_ref);
			      $msgtext = str_replace($var, $zamena, $email_temp['budil']);
		      	
						if(!$mail_error = $mail_out->send($email_tab, $referal, iconv("CP1251", "UTF-8", 'Информационное сообщение системы '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
							mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_mail_reactref' WHERE `username`='$username'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `lastdate_reactref`='".time()."' WHERE `username`='$referal'") or die(mysql_error());
							mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$cena_mail_reactref', 'Оплата за функцию - разбудить реферала $referal','Списано','rashod')") or die(mysql_error());

							echo '<span class="msg-ok">Сообщение успешно отправлено на e-mail!</span>';
						}else{
							echo '<span class="msg-error">Ошибка! Не удалось отправить сообщение!</span>';
						}
					}
				}
			}
		}else{
			echo '<span class="msg-error">Ошибка! Реферал не найден!</span>';
		}
	}

         // Аукцион
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time' AND `howmany`='1'");
	$auc_time = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_end_add' AND `howmany`='1'");
	$auc_time_end_add = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_time_add' AND `howmany`='1'");
	$auc_time_add = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_comis' AND `howmany`='1'");
	$auc_comis = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_click_user' AND `howmany`='1'");
	$auc_limit_click_user = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_last_user' AND `howmany`='1'");
	$auc_limit_activ_last_user = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_max' AND `howmany`='1'");
	$auc_max = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='auc_limit_activ_all_user' AND `howmany`='1'");
	$auc_limit_activ_all_user = mysql_result($sql,0,0);
	// END Аукцион


	if(isset($_GET["rid"]) && isset($_GET["op"]) && limpiar($_GET["op"])=="add_auc") {

		$rid = (isset($_GET["rid"]) && preg_match("|^[\d]*$|", trim($_GET["rid"]))) ? intval(limpiarez(trim($_GET["rid"]))) : false;

		if($rid!=false) {

			$sql = mysql_query("SELECT `username` FROM `tb_users` WHERE `id`='$rid' AND `referer`='$username'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$ref_name = $row["username"];

				$sql_b = mysql_query("SELECT * FROM `tb_refbirj` WHERE `ref`='$ref_name' AND `name`='$username'");
				if(mysql_num_rows($sql_b)>0) {
					echo '<fieldset class="errorp">Ошибка! Реферал уже выставлен на биржу рефералов!</fieldset>';
				}else{
					$sql_a = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `username`='$username' AND `referal`='$ref_name'");
					if(mysql_num_rows($sql_a)>0) {
						echo '<fieldset class="errorp">Ошибка! Реферал уже выставлен на аукцион рефералов!</fieldset>';
					}else{
						if($all_auc>=$auc_max) {
						echo '<fieldset class="errorp">Ошибка! Максимально количество активных аукционов не более '.$auc_max.'!</fieldset>';
						include('footer.php');
						exit();
						}else


						if(count($_POST)>0 && isset($_POST["stavka"])) {
							$stavka = (isset($_POST["stavka"])) ? abs(floatval(str_replace(",",".", limpiarez($_POST["stavka"])))) : false;
							$inforef = (isset($_POST["inforef"])) ? limpiarez($_POST["inforef"]) : false;
							$inforef = limitatexto($inforef, 255);

							if($stavka!=false && $stavka>=0.1 && $stavka<=50) {
								mysql_query("INSERT INTO `tb_auction` (`status`,`username`,`referal`,`inforef`,`timer_end`,`stavka`,`kolstv`,`lider`,`date_end`) VALUES('1','$username','$ref_name','$inforef','".(time()+$auc_time*60)."','$stavka','0','','0')") or die(mysql_error());

								echo '<fieldset class="okp">Реферал успешно выставлен на аукцион рефералов!</fieldset>';
							}else{
								echo '<fieldset class="errorp">Ошибка! Не верно указана ставка!</fieldset>';
							}
						}else{
							echo '<div id="form">';
							echo '<form action="" method="POST">';
							echo '<table class="tables">';
                                                        echo '<thead>';
							echo '<tr align="center"><th align="center" colspan="3" class="top">Создание лота аукциона</th></tr>';
							echo '<tr>';
								echo '<td width="40%" align="right" style="padding: 5px;"><b>Реферал:</b></td>';
								echo '<td align="left" style="padding: 5px;"><b>'.$ref_name.'</b></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td width="40%" align="right" style="padding: 5px;"><b>Размер ставки:</b></td>';
								echo '<td align="left" style="padding: 5px;">';
									echo '<select name="stavka">';
										for($i=0.1; $i<=50; $i=$i+0.10){
											echo '<option value="'.number_format($i,2,".","").'">'.number_format($i,2,".","").' руб.</option>';
										}
									echo '</select>';
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td width="40%" align="right" style="padding: 5px;"><b>Дополнительная информация:</b><br>(макс.&nbsp;255&nbsp;символов)</td>';
								echo '<td align="left" style="padding: 5px;"><textarea rows="4" name="inforef" style="width:85%;"></textarea></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td width="40%" align="right" style="padding: 5px;"><b>Продолжительность аукциона:</b></td>';
								echo '<td align="left" style="padding: 5px;"><b>'.$auc_time.' мин.</b>&nbsp;(при отсутствии ставок)</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td width="40%" align="right" style="padding: 5px;"><b>Комиссия системы:</b></td>';
								echo '<td align="left" style="padding: 5px;"><b>'.$auc_comis.'%</b> (будет удержана с каждой ставки)</td>';
							echo '</tr>';
							echo '<tr align="center">';
								echo '<td colspan="2" style="padding: 5px;"><input type="submit" class="submit" value="Создать аукцион"></td>';
							echo '</tr>';
                                                        echo '<t/head>';
							echo '</table>';
							echo '</form>';
							echo '</div>';
						}
					}
				}
			}else{
				echo '<fieldset class="errorp">Ошибка! У Вас нет такого реферала!</fieldset>';
			}
		}else{
			echo '<fieldset class="errorp">Ошибка! У Вас нет такого реферала!</fieldset>';
		}

		include('footer.php');
		exit();
	}
	
	?>
	<script type="text/javascript" src="/js/highcharts.js"></script>
	<script type="text/javascript">
	var LoadBlock = false;
	
	
	function FuncStatRef(op, token, title_win) {
	//alert('fff');
		if(!LoadBlock){$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_stat_ref.php", dataType: 'json', data: { 'op':op, 'token':token }, 
			error: function(request, status, errortext) { LoadBlock = false; $("#loading").slideToggle(); alert("Ошибка Ajax! \n"+status+"\n"+errortext); console.log(status, errortext, request); },
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false; $("#loading").slideToggle();
				var result = data.result!=undefined ? data.result : data;
				var message = data.message!=undefined ? data.message : data;
				title_win = (!title_win | result!="OK") ? "Ошибка" : title_win;

				if (result == "OK") {
					ModalStart(title_win, (title_win=="Ошибка" ? StatusMsg('ERROR', message) : message), 700, true, true, false);
				} else {
					ModalStart(title_win, StatusMsg(result, message), 500, true, true, false);
				}
			}
		});}
		return false;
	}
	</script>
	

	<?php

	$s = (isset($_GET["s"]) && preg_match("|^[\d]{1,2}$|", trim($_GET["s"])) && intval(limpiar(trim($_GET["s"])))>=0 && intval(limpiar(trim($_GET["s"])))<=21) ? abs(intval(limpiar(trim($_GET["s"])))) : "0";
	$u = (isset($_GET["u"]) && preg_match("|^[\d]{1}$|", trim($_GET["u"])) && intval(limpiar(trim($_GET["u"])))>=0 && intval(limpiar(trim($_GET["u"])))<=1) ? abs(intval(limpiar(trim($_GET["u"])))) : "1";
	$s_arr = array('id','username','reiting','wall_com_p','wall_com_o','statusref','visits','visits_a','visits_m','visits_t','autoref','money_rek','joindate2','lastlogdate2','referals','referals2','referals3','referals4','referals5','ref_back','ref_bonus_add','dohod_r_s','dohod_r_v','dohod_r_all');
	$u_arr = array('ASC','DESC');
	$s_tab = $s_arr[$s];
	$u_tab = $u_arr[$u];

	$perpage = 25;
	$count1 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username'"));
	$count2 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$username'"));
	$count3 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'"));
	//$count4 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer4`='$username'"));
	//$count5 = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE `referer5`='$username'"));

	$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_users` WHERE $WHERE `referer`='$username'"));
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	$security_key = "jbmiym,oi5//bmiyt";
		$token_stat_ref1 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL1".$security_key));
		$token_stat_ref2 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL2".$security_key));
		$token_stat_ref3 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL3".$security_key));
		//$token_stat_ref4 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL4".$security_key));
		//$token_stat_ref5 = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."StatRefL5".$security_key));
	echo '<table class="tables">';
	echo '<tr align="center">';
		echo '<td colspan="5" valign="top" class="adv">Навигация по уровням рефералов</td>';
	echo '</tr>';
	echo '<tr align="center">';
		echo '<td class="adv"><a href="/referals1.php" class="menuheader">1-го ур. ['.$count1.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами I уровня" onClick="FuncStatRef(\'StatRefCashL1\', \''.$token_stat_ref1.'\', \'Статистика заработка рефералами I уровня\')"></span></td>';
		echo '<td class="adv"><a href="/referals2.php" class="menuheader">2-го ур. ['.$count2.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами II уровня" onClick="FuncStatRef(\'StatRefCashL2\', \''.$token_stat_ref2.'\', \'Статистика заработка рефералами II уровня\')"></span></td>';
		echo '<td class="adv"><a href="/referals3.php" class="menuheader">3-го ур. ['.$count3.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами III уровня" onClick="FuncStatRef(\'StatRefCashL3\', \''.$token_stat_ref3.'\', \'Статистика заработка рефералами III уровня\')"></span></td>';
		//echo '<td class="adv"><a href="/referals4.php" class="menuheader">4-го ур. ['.$count4.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами IV уровня" onClick="FuncStatRef(\'StatRefCashL4\', \''.$token_stat_ref4.'\', \'Статистика заработка рефералами IV уровня\')"></span></td>';
		//echo '<td class="adv"><a href="/referals5.php" class="menuheader">5-го ур. ['.$count5.']</a><span class="adv-statistics" style="margin-top:1px;" title="Статистика заработка рефералами V уровня" onClick="FuncStatRef(\'StatRefCashL5\', \''.$token_stat_ref5.'\', \'Статистика заработка рефералами V уровня\')"></span></td>';
	echo '</tr>';
	echo '</table><br>';


	if(isset($_POST["op"]) && limpiar($_POST["op"])=="4") {
		$id_r = (isset($_POST["id_r"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_r"])) ) ? intval(limpiar(trim($_POST["id_r"]))) : "0";
		$user = (isset($_POST["user"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_POST["user"]))) ? uc(trim($_POST["user"])) : false;

		$sql_r = mysql_query("SELECT `id` FROM `tb_users` WHERE `id`='$id_r' AND `username`='$user' AND `referer`='$username'");
		if(mysql_num_rows($sql_r)>0) {
			mysql_query("UPDATE `tb_users` SET `referer`='',`referer2`='',`referer3`='', `dohod_r_s`='0', `dohod_r_v`='0', `dohod_r_all`='0' WHERE `id`='$id_r' AND `referer`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `referer2`='',`referer3`='' WHERE `referer`='$user' AND `referer2`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `referer3`='' WHERE `referer2`='$user' AND `referer3`='$username'") or die(mysql_error());

			mysql_query("UPDATE `tb_users` SET `referals`=`referals`-'1' WHERE `username`='$username'") or die(mysql_error());
		}else{
			echo '<span class="msg-error">Ошибка! У Вас нет такого реферала!</span>';
		}
	}

	$sql_all = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username' AND `lastlogdate2`<'".(time()-7*24*60*60)."' AND `lastdate_reactref`<'".(time()-5*24*60*60)."'");
	$all_reactref = mysql_num_rows($sql_all);
	if($all_reactref>0) {echo '<a href="javascript: void(0);" onclick="reactref_all();"><img src="/img/clock.png" border="0" align="absmiddle" alt="" title="Разбудить всех рефералов 1ур." /> - разбудить всех рефералов 1ур.</a> (<b>рассылка сообщений на e-mail</b>)';}

	echo '<form method="GET" action="" id="newform">';
	echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap" width="100">Сортировать по:</td>';
		echo '<td align="center">';
			echo '<select name="s" class="ok">';
				echo '<option value="0" '.("0" == "$s" ? 'selected="selected"' : false).'>ID</option>';
				echo '<option value="1" '.("1" == "$s" ? 'selected="selected"' : false).'>Логину</option>';
				echo '<option value="2" '.("2" == "$s" ? 'selected="selected"' : false).'>Опыту</option>';
				echo '<option value="3" '.("3" == "$s" ? 'selected="selected"' : false).'>Кол-ву положительных отзывов</option>';
				echo '<option value="4" '.("4" == "$s" ? 'selected="selected"' : false).'>Кол-ву отрицательных отзывов</option>';
				echo '<option value="5" '.("5" == "$s" ? 'selected="selected"' : false).'>Типу(Привлечен, автореферал, куплен)</option>';
				echo '<option value="6" '.("6" == "$s" ? 'selected="selected"' : false).'>Серфингу</option>';
				echo '<option value="7" '.("7" == "$s" ? 'selected="selected"' : false).'>Авто-серфингу</option>';
				echo '<option value="8" '.("8" == "$s" ? 'selected="selected"' : false).'>Письмам</option>';
				echo '<option value="9" '.("9" == "$s" ? 'selected="selected"' : false).'>Заданиям</option>';
				echo '<option value="10" '.("10" == "$s" ? 'selected="selected"' : false).'>Функции Автореферал (Вкл/Выкл)</option>';
				echo '<option value="11" '.("11" == "$s" ? 'selected="selected"' : false).'>Затратам на рекламу</option>';
				echo '<option value="12" '.("12" == "$s" ? 'selected="selected"' : false).'>Дате регистрации</option>';
				echo '<option value="13" '.("13" == "$s" ? 'selected="selected"' : false).'>Дате активности</option>';
				echo '<option value="14" '.("14" == "$s" ? 'selected="selected"' : false).'>Рефералам 1 уровня</option>';
				echo '<option value="15" '.("15" == "$s" ? 'selected="selected"' : false).'>Рефералам 2 уровня</option>';
				echo '<option value="16" '.("16" == "$s" ? 'selected="selected"' : false).'>Рефералам 3 уровня</option>';
				//echo '<option value="17" '.("17" == "$s" ? 'selected="selected"' : false).'>Рефералам 4 уровня</option>';
				//echo '<option value="18" '.("18" == "$s" ? 'selected="selected"' : false).'>Рефералам 5 уровня</option>';
				echo '<option value="19" '.("19" == "$s" ? 'selected="selected"' : false).'>Рефбеку</option>';
				echo '<option value="20" '.("20" == "$s" ? 'selected="selected"' : false).'>Бонусу (Вкл/Выкл)</option>';
				echo '<option value="21" '.("21" == "$s" ? 'selected="selected"' : false).'>Доходу сегодня</option>';
				echo '<option value="22" '.("22" == "$s" ? 'selected="selected"' : false).'>Доходу вчера</option>';
				echo '<option value="23" '.("23" == "$s" ? 'selected="selected"' : false).'>Доходу всего</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="center" width="150">';
			echo '<select name="u" class="ok">';
				echo '<option value="0" '.("0" == "$u" ? 'selected="selected"' : false).'>По возрастанию</option>';
				echo '<option value="1" '.("1" == "$u" ? 'selected="selected"' : false).'>По убыванию</option>';
			echo '</select>';
			echo '<input type="hidden" name="page" value="'.$page.'">';
		echo '</td>';
		echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" class="proc-btn" value="Сортировать"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';

	echo '<form method="post" action="" id="newform">';
	echo '<table class="tables">';
	echo '<tr>';
	echo '<td align="left" nowrap="nowrap" width="100">Поиск по:</th>';
	echo '<td nowrap="nowrap"><select name="metode" class="ok">';
		echo '<option value="id" '.("id" == $metode ? 'selected="selected"' : false).'>ID</option>';
		echo '<option value="username" '.("username" == $metode ? 'selected="selected"' : false).'>Логину</option>';
		echo '<option value="joindate" '.("joindate" == $metode ? 'selected="selected"' : false).'>Дате регистрации</option>';
		echo '<option value="lastlogdate" '.("lastlogdate" == $metode ? 'selected="selected"' : false).'>Дате активности</option>';
	echo '</select></td>';
	echo '<td align="center" width="150"><input type="text" class="ok" name="search" value="'.$search.'" style="width:96%;"></td>';
	echo '<td align="center" nowrap="nowrap" width="100"><input type="submit" value="Поиск" class="proc-btn"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form><br>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?s=$s&u=$u");

	echo '<table class="tables">';
	echo '<thead><tr align="center">';
	echo '<th colspan="2">ID, Логин</th>';
	echo '<th>Доход</th>';
	echo '<th>Рег-ция&nbsp;|&nbsp;Активность</th>';
	echo '<th>Рефералы</th>';
	echo '<th>Действия</th>';
        //echo '<th>Аукцион</th>';
	echo '</tr></thead>';

	if(isset($_GET["op"]) && limpiar($_GET["op"])=="onoff_bonus") {
		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
		$v = (isset($_GET["v"]) && (intval($_GET["v"])==0 | intval($_GET["v"])==1)) ? intval(trim($_GET["v"])) : "1";

		mysql_query("UPDATE `tb_users` SET `ref_bonus_add`='$v' WHERE `id`='$id' AND `referer`='$username' LIMIT 1") or die(mysql_error());
	}

	if(isset($_POST["op"]) && limpiar($_POST["op"])=="1") {
		$ref_back = (isset($_POST["ref_back"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["ref_back"])) && (intval($_POST["ref_back"])>=0 | intval($_POST["ref_back"])<=90) ) ? intval(limpiar(trim($_POST["ref_back"]))) : "0";

		mysql_query("UPDATE `tb_users` SET `ref_back`='$ref_back' WHERE `referer`='$username'") or die(mysql_error());
	}

	if(isset($_POST["op"]) && limpiar($_POST["op"])=="2") {
		$ref_back_all = (isset($_POST["ref_back_all"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["ref_back_all"])) && (intval($_POST["ref_back_all"])>=0 | intval($_POST["ref_back_all"])<=90) ) ? intval(limpiar(trim($_POST["ref_back_all"]))) : "0";

		mysql_query("UPDATE `tb_users` SET `ref_back_all`='$ref_back_all' WHERE `username`='$username'") or die(mysql_error());
	}

	if(isset($_POST["op"]) && limpiar($_POST["op"])=="3") {
		$id_r = (isset($_POST["id_r"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_r"])) ) ? intval(limpiar(trim($_POST["id_r"]))) : "0";
		$ref_back = (isset($_POST["ref_back"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["ref_back"])) && (intval($_POST["ref_back"])>=0 | intval($_POST["ref_back"])<=90) ) ? intval(limpiar(trim($_POST["ref_back"]))) : "0";

		mysql_query("UPDATE `tb_users` SET `ref_back`='$ref_back' WHERE `id`='$id_r' AND `referer`='$username'") or die(mysql_error());
	}


	$sql = mysql_query("SELECT `ref_back_all` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_row($sql);
		$ref_back_all = $row[0];
	}

	$sql = mysql_query("SELECT * FROM `tb_users` WHERE $WHERE `referer`='$username' ORDER BY $s_tab $u_tab LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {

			echo '<tr align="center">';

				$wall_com = $row["wall_com_p"] - $row["wall_com_o"];

				$info_user = '<a href="/wall.php?uid='.$row["id"].'" target="_blank"><img src="img/wall20.png" title="Стена пользователя '.$row["username"].'" width="16" border="0" style="margin:0; padding:0;" align="absmiddle" />';

				if($wall_com>0) {
					$info_user.= '&nbsp;<b style="color:#008000;">+'.$wall_com.'</b></a>';
				}elseif($wall_com<0) {
					$info_user.= '&nbsp;<b style="color:#FF0000;">-'.abs($wall_com).'</b></a>';
				}else{
					$info_user.= '&nbsp;<b style="color:#000000;">0</b></a>';
				}

				echo '<td width="60px" style="border-right:none;">';
					echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" border="0" alt="avatar" title="avatar" />';
				echo '</td>';

				echo '<td align="left" style="border-left:none;">';
					echo '<b style="cursor:pointer;" title="Логин">'.$row["username"].'</b> - <span style="cursor:pointer;" title="ID">'.$row["id"].'</span><br>';
					echo ref_status($row["reiting"])."&nbsp;";
					echo '<img src="img/reiting.png" border="0" alt="" align="middle" title="Опыт" style="margin:0; padding:0;" />&nbsp;<span style="color:blue;">'.round($row["reiting"], 2).'</span><br>';

					if($row["statusref"]=="0") {echo 'Привлечен<br>';}
					if($row["statusref"]=="1") {echo 'Авто-реферал<br>';}
					if($row["statusref"]=="2") {echo 'Куплен на бирже<br>';}
					if($row["statusref"]=="3") {echo 'Куплен на аукционе<br>';}
					if($row["http_ref"]!="") {echo 'Пришел с '.$row["http_ref"].'<br>';}
                                        echo 'Потрачено на рекламу '.number_format($row["money_rek"],2,".","'").'<br>';
					echo $attestat[$row["attestat"]]."&nbsp;";
					if($row["country_cod"]!="") {
						echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($row["country_cod"]).'.gif" alt="" title="'.get_country($row["country_cod"]).'" width="16" height="12" style="margin:0; padding:0;" align="absmiddle" />&nbsp;';
					}
					echo $info_user."&nbsp;";

					if($row["lastlogdate2"] < (time()-7*24*60*60)) {
						if($row["lastdate_reactref"]<=(time()-5*24*60*60)) {echo '<a href="javascript: void(0);" onclick="reactref(\''.$row["id"].'\');"><img src="/img/clock20.png" width="18" border="0" alt="" style="margin:0; padding:0;" align="absmiddle" title="Разбудить реферала №'.$row["id"].' '.$row["username"].'" /></a>&nbsp;';}
					}elseif($row["lastlogdate2"] == 0) {
						if($row["lastdate_reactref"]<(time()-5*24*60*60)) {echo '<a href="javascript: void(0);" onclick="reactref(\''.$row["id"].'\');"><img src="/img/clock20.png" width="18" border="0" alt="" style="margin:0; padding:0;" align="absmiddle" title="Разбудить реферала №'.$row["id"].' '.$row["username"].'" /></a>&nbsp;';}
					}else{
						echo '';
					}
				echo '</td>';

				echo '<td>';
					echo '<table align="center" border="0" width="100%" class="notables" style="border:0px; margin:0px; padding:0px;"><tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Сегодня:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["dohod_r_s"],4,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Вчера:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["dohod_r_v"],4,".","'").'</td></tr>';
						echo '<tr><td align="left" style="border:0px; margin:0px; padding:0px;">Всего:</td><td align="right" style="border:0px; margin:0px; padding:0px;">'.number_format($row["dohod_r_all"],4,".","'").'</td></tr>';
					echo '</table>';
				echo '</td>';

				echo '<td>';
					echo DATE("d.m.Yг. в H:i",$row["joindate2"])."<br>";

					if($row["lastlogdate2"] == 0) {
						echo '<span style="color:#FF0000;">нет</span>';
					}elseif(DATE("d.m.Y", $row["lastlogdate2"]) == DATE("d.m.Y")) {
						echo '<span style="color:green;">Сегодня, '.DATE("в H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] < (time()-7*24*60*60)) {
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}elseif($row["lastlogdate2"] > (time()-7*24*60*60)) {
						echo '<span style="color:green;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}else{
						echo '<span style="color:#FF0000;">'.DATE("d.m.Yг. в H:i",$row["lastlogdate2"]).'</span>';
					}

					if($row["ban_date"]>0) echo '<br><b style="color:#FF0000;">Штрафник</b>';
				echo '</td>';

				echo '<td>'.$row["referals"].'-'.$row["referals2"].'-'.$row["referals3"].'</td>';

				echo '<td align="center" nowrap="nowrap" style="width:155px;">';
					echo '<div style="float:right;"><form action="" method="post">';
						echo '<input type="hidden" value="4" name="op">';
						echo '<input type="hidden" value="'.$row["id"].'" name="id_r">';
						echo '<input type="hidden" value="'.$row["username"].'" name="user">';
						echo '<input type="image" style="" src="img/close.png" alt="Удалить реферала из списка своих рефералов" title="Удалить реферала из списка своих рефералов" onClick=\'if(!confirm("Вы уверены что хотите удалить реферала из списка своих рефералов?")) return false;\'>';
					echo '</form></div>';

					echo '<div style="float:right; margin-left:5px;"><a class="sub-black-l" style="width:40px;" onclick="return s_h(\'id_'.$row["id"].'\');" title="Установлен рефбек: '.$row["ref_back"].'%">'.$row["ref_back"].'%</a></div>';

                                        

					$token_ajax = md5($username.$row["username"].$row["id"]);
					echo '<div style="float:right;"><span onclick="ShowHideStats(\''.$row["id"].'\', \''.$token_ajax.'\')" style="cursor: pointer;"><img src="/img/icon-stats.png" border="0" alt="" style="margin:0; padding:0;" align="absmiddle" title="Посмотреть расширенную статистику пользователя '.$row["username"].'" /></span></div>';

					echo '<div style="float:right;"><a href="/newmsg.php?name='.$row["username"].'"><img src="images/mail.gif" border="0" align="absmiddle" style="margin:0 padding:0; padding-top:3px;" title="Отправить сообщение рефералу '.$row["username"].'" alt="" /></a></div>';

					
                                                $sql_b = mysql_query("SELECT * FROM `tb_refbirj` WHERE `ref`='".$row["username"]."' AND `name`='$username'");
			$status_birj = mysql_num_rows($sql_b);

			$sql_a = mysql_query("SELECT * FROM `tb_auction` WHERE `status`='1' AND `username`='$username' AND `referal`='".$row["username"]."'");
			$status_auc = mysql_num_rows($sql_a);

			if($status_auc>0 | $status_birj>0) {
						echo '<div style="float:right; margin-left:50px;"><b>Выставлен<br>на аукцион</b></div>';
					}elseif($row["visits"]>=$auc_limit_click_user && $auc_limit_activ_last_user>((time()-$row["lastlogdate2"])/86400) && $auc_limit_activ_all_user<((time()-$row["joindate2"])/86400) && $status_birj<1 ) {
						echo '<div style="float:right;"><a href="'.$_SERVER['PHP_SELF'].'?page='.$page.'&rid='.$row["id"].'&op=add_auc" title="Выставить на аукцион"><img src="/img/molotok.gif" alt="" title="Выставить на аукцион" border="0" /></a></div>';
					}else{
						echo '';
						
					}
					
					echo '<div style="float:right; clear:both;">Бонус: ';
						if($row["ref_bonus_add"]==0) {
							echo '[<a style="color:red;" href="'.$_SERVER["PHP_SELF"].'?s='.$s.'&u='.$u.'&page='.$page.'&op=onoff_bonus&id='.$row["id"].'&v='.($row["ref_bonus_add"]+1).'">Выкл.</a>]';
						}elseif($row["ref_bonus_add"]==1) {
							echo '[<a style="color:green;" href="'.$_SERVER["PHP_SELF"].'?s='.$s.'&u='.$u.'&page='.$page.'&op=onoff_bonus&id='.$row["id"].'&v='.($row["ref_bonus_add"]-1).'">Вкл.</a>]';
						}
					echo '</div>';

					echo '<div style="float:right; clear:both;">';
						if($row["autoref"]=="1" && $row["autorefend"]>time()) {
							echo 'Автореферал:&nbsp;<span style="color:green">Вкл</span>';
						}else{
							echo 'Автореферал:&nbsp;<span style="color:red">Выкл</span>';
						}
					echo '</div>';
				echo '</td>';

			echo '</tr>';

			echo '<tr align="center"><td id="id_'.$row["id"].'" style="display: none;" colspan="6">';
				echo '<form action="" method="post" style="padding-bottom:10px;">';
					echo '<select name="ref_back">';
						for($i=0; $i<=80; $i=$i+5){
							echo '<option style="text-align:right;" value="'.$i.'"'; if($row["ref_back"]==$i){echo ' selected="selected"';} echo '>'.$i.'%</option>';
						}
					echo '</select>';
					echo '<input type="hidden" value="3" name="op">';
					echo '<input type="hidden" value="'.$row["id"].'" name="id_r">';
					echo '<input type="submit" class="proc-btn" value="Установить рефбэк">';
				echo '</form>';
			echo '</td></tr>';
			echo '<tr align="center"><td colspan="6" id="usersstat'.$row["id"].'" style="display: none;"></td></tr>';
		}
	}else{
		if($WHERE==false) {
			echo '<tr align="center"><td colspan="6"><b>У Вас нет рефералов!</b></td></tr>';
		}else{
			echo '<tr align="center"><td colspan="6"><b>Ошибка! У Вас нет такого реферала!</b></tb></tr>';
		}
	}
	echo '</table>';

	if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?s=$s&u=$u");

	echo '<br><br><table class="tables">';
	echo '<thead><tr><th colspan="2">Настройки авто-рефбека</th></tr></thead>';
	echo '<tr align="center">';
		echo '<td width="50%">';
			echo '<br><form action="" method="post" id="newform">';
				echo '<select name="ref_back" class="ok" style="width:100px; text-align:center;">';
					for($i=0; $i<=80; $i=$i+5){
						echo '<option style="text-align:right;" value="'.$i.'">'.$i.'%</option>';
					}
				echo '</select>';
				echo '<input type="hidden" value="1" name="op">';
				echo '<input type="submit" class="proc-btn" value="Рефбэк всем имеющимся">';
			echo '</form><br>';
			echo '"Рефбэк всем имеющимся" - будет установлен одинаковый рефбэк ВСЕМ вашим имеющимся рефералам.';
		echo '</td>';
		echo '<td width="50%">';
			echo '<br><form action="" method="post" id="newform">';
				echo '<select name="ref_back_all" class="ok" style="width:100px; text-align:center;">';
					for($i=0; $i<=80; $i=$i+5){
						echo '<option style="text-align:right;" value="'.$i.'"'; if($ref_back_all==$i){echo ' selected="selected"';} echo '>'.$i.'%</option>';
					}
				echo '</select>';
				echo '<input type="hidden" value="2" name="op">';
				echo '<input type="submit" class="proc-btn" value="Рефбэк всем новым">';
			echo '</form><br>';
			echo '"Рефбэк всем новым" - рефбэк будет установлен ВСЕМ вашим будущим рефералам автоматически при их регистрации.';
		echo '</td>';
	echo '</tr>';
	echo '</table>';
}

?><script type="text/javascript">
	function s_h(ID){
		if(document.getElementById(ID).style.display == '') {
			document.getElementById(ID).style.display = 'none';
		}else{
			document.getElementById(ID).style.display = '';
		}
		return false;
	}
</script><?php 

include('footer.php');?>