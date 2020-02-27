<?php
if(!isset($_SERVER["REDIRECT_URL"])) {echo '<script type="text/javascript"> location.replace("/tests-views"); </script>';echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/tests-views"></noscript>';
	exit();
}
$pagetitle = "Здесь зарабатывают на выполнении тестов";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

echo '<script type="text/javascript" src="js/js_tests.js?v=2.00"></script>';

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для выполнения оплачиваемых тестов необходимо авторизоваться!</span>';
}else{
	if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
	require(ROOT_DIR."/config.php");

	echo '<div style="margin:0 auto; background:#f7e9e3; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">';
		echo 'На <b style="color:coral;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> выполняя оплачиваемые тесты, внимательно прочтите и следуйте инструкции к выполнению теста. Последовательно ответьте на вопросы в тесте. ';
		echo 'Некоторые тесты могут потребовать от вас больше действий, но и оплата за такие тесты немного выше обычных, если вы уверены что тест невозможно пройти, оставляйте на него жалобу для администрации.';
	echo '</div>';

	echo '<div style="margin:7px auto; margin-bottom:50px; background:#f7e9e3; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#E32636;">';
		echo 'Обратите внимание.<br> При попытках угадывать ответы на вопросы, вам может быть заблокирован доступ к тестам.';
	echo '</div>';

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	$my_ref_back = $my_ref_back;
	$my_country = $my_country;
	$my_lastiplog_ex = explode(".", $my_lastiplog);
	$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
	$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

	if($my_referer_1!="") {
		$sql_ref_1 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
		if(mysql_num_rows($sql_ref_1)>0) {
			$reit_ref_1 = mysql_result($sql_ref_1,0,0);

			$sql_rang_1 = mysql_query("SELECT `test_1` FROM `tb_config_rang` WHERE `r_ot`<='$reit_ref_1' AND `r_do`>='".floor($reit_ref_1)."'");
			if(mysql_num_rows($sql_rang_1)>0) {
				$ref_proc_1 = mysql_result($sql_rang_1,0,0);
			}else{
				$ref_proc_1 = 0;
			}
		}else{
			$my_ref_back = 0; $ref_proc_1 = 0;
		}
	}else{
		$my_ref_back = 0; $ref_proc_1 = 0;
	}

	function count_text($count, $text1, $text2, $text3, $text) {
		if($count>0) {
			if( ($count>=10) && ($count<=20) ) {
				return "<b>$count</b> $text1";
			}else{
				switch(substr($count, -1, 1)){
					case 1: return "<b>$count</b> $text1"; break;
					case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
				}
			}
		}else{
			return "$text";
		}
	}

	function format_tests(
		$id, $rek_name, $title, $url, $color, $claims, $goods, $bads, $cena_user, $totals, 
		$username, $my_ref_back, $ref_proc_1, $tests_nacenka
	) {
		$token = strtolower(md5(strtolower($username).session_id().DATE("H").$_SERVER["HTTP_HOST"].$id."SecurityTests"));
		$uid = strrev(substr($token,0,-16)).strrev(strtolower(md5($id))).strrev(substr($token,16,16));

		$_SumNac = ($cena_user * $tests_nacenka / 100);
		$my_ref_back = ( $_SumNac * ($ref_proc_1/100) * ($my_ref_back/100) );
		$cena_tests = ($cena_user + $my_ref_back);
		$cena_tests = number_format($cena_tests, 4, ".", "");

		echo '<tr id="tr'.$id.'">';
			echo '<td class="td-serf" valign="top" style="text-align:center; width:34px; padding-left:10px;">';
				echo '<span class="'.($color==1 ? "test-img-up" : "test-img").'"></span>';
			echo '</td>';

			echo '<td id="'.$uid.'" align="left" valign="top" class="td-serfm" style="cursor:pointer;">';
				echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-bottom:2px; padding-right:5px;" src="//www.google.com/s2/favicons?domain='.@gethost($url).'" align="absmiddle" />';
				echo '<span style="'.($color==1 ? "color:#FF0000;" : "color:#135B11;").' font-size:14px; line-height:1.3em;">'.$title.'</span><br>';
				echo '<span style="font-size:11px">ID: '.$id.' &#183; Прошли тест: '.count_text($goods, "раз", "раза", "раз", "0").' &#183; Не прошли тест: '.count_text($bads, "раз", "раза", "раз", "0").'</span>';

				echo '<div id="info_'.$id.'" style="display: none; margin:0 13px; padding:0px 5px; color:#778899; text-align:left; font-size:11px; border-left: dashed 1px #CCCCCC;">';
					echo 'Ссылка на сайт: <a href="'.$url.'" target="_blank">'.$url.'</a><br>';
					echo (trim($rek_name)!=false ? "Рекламодатель: <b>$rek_name</b><br>": false);
					echo 'Количество жалоб: '.($claims>0 ? '<span style="color:#FF0000;">'.$claims.'</span>': 0).'<br>';
				echo '</div>';
			echo '</td>';

			echo '<td class="td-serf" nowrap="nowrap" valign="top" style="width:100px; text-align:right; padding-right:10px;">';
				echo '<span title="Осталось выполнений: '.number_format($totals, 0, ".", "`").'" style="cursor: help; font-size: 11px; color: #89A688; '.($totals<10 ? "text-decoration: blink; color: #F9A97C;" : false).'">('.number_format($totals, 0, ".", "`").')</span>&nbsp;';
				echo '<span title="Стоимость выполнения: '.$cena_tests.' руб." style="cursor:help; color:#135B11;">'.$cena_tests.'</span><br>';
				echo '<a class="workcomp" id="claims'.$id.'" onClick="ShowHideInfo(\'claims_'.$id.'\');" title="Пожаловаться на рекламу"></a>';
				echo '<a id="t_virus'.$id.'" class="workvir" onClick="test_virus(\''.$id.'\');" title="Проверить ссылку на вирусы"></a>';
				echo '<a class="workquest" onClick="ShowHideInfo(\'info_'.$id.'\');" title="Подробная информация"></a>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="claims_'.$id.'" style="display:none;">';
			echo '<td colspan="3" align="center" valign="middle" class="td-serf" style="color: #FFFFFF; background-color: #FF9966; padding: 5px 0px; font: 12px Tahoma, Arial, sans-serif;">';
				echo '<div id="info-claims-'.$id.'" style="display:none;"></div>';
				echo '<div id="formclaims'.$id.'"><table style="width:100%; margin:0; padding:0;">';
				echo '<tr>';
					echo '<td align="center" width="100" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;">Текст жалобы:</td>';
					echo '<td align="center" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><div id="newform"><input type="text" id="claimstext'.$id.'" maxlength="100" value="" class="ok" style="margin:0; padding:1px 5px;" /></div></td>';
					echo '<td align="center" width="120" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><span onClick="AddClaims(\''.$id.'\', \'tests\');" class="sub-red" style="float:none;">Отправить</span></td>';
				echo '</tr>';
				echo '</table></div>';
			echo '</td>';
		echo '</tr>';
	}

	$sql_test = mysql_query("SELECT `id`,`username`,`title`,`url`,`color`,`claims`,`goods`,`bads`,`cena_user`,`balance`,`cena_advs` FROM `tb_ads_tests` 
		WHERE `status`='1' AND `balance`>=`cena_advs` 
		AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') 
		AND (`sex_user`='0' OR `sex_user`='$my_sex') 
		AND (`date_reg_user`='0' 
			OR (`date_reg_user`='1' AND '$my_joindate'>='".(time()-7*86400)."') 
			OR (`date_reg_user`='2' AND '$my_joindate'<='".(time()-7*86400)."') 
			OR (`date_reg_user`='3' AND '$my_joindate'<='".(time()-30*86400)."') 
			OR (`date_reg_user`='4' AND '$my_joindate'<='".(time()-90*86400)."') 
		) 
		AND `id` NOT IN (
			SELECT `ident` FROM `tb_ads_tests_visits` 
			WHERE `time_next`>='".time()."' 
			AND (
				   ( `tb_ads_tests`.`unic_ip_user`='0' AND `user_name`='$username' ) 
				OR ( `tb_ads_tests`.`unic_ip_user`='1' AND (`user_name`='$username' OR `ip`='$my_lastiplog') ) 
				OR ( `tb_ads_tests`.`unic_ip_user`='2' AND (`user_name`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ) 
			)
		) 
		AND `username` NOT IN (SELECT `user_name` FROM `tb_black_list` WHERE `user_bl`='$username' AND `type`='usr_adv' AND `tests_usr`='1') 
		AND `username` NOT IN (SELECT `user_bl` FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='usr_adv' AND `tests_adv`='1') 
		ORDER BY `color` DESC, `id` DESC
	");
	if(mysql_num_rows($sql_test)>0) {
		echo '<table class="table-serf"><tbody>';
		echo '<tr>';
			echo '<td align="center" class="td-serf" colspan="3">';
				echo '<h2 class="sp" style="margin:0; padding:0;">Зарабатывай, выполняя интересные тесты</h1>';
			echo '</td>';
		echo '</tr>';

		while($row_test = mysql_fetch_assoc($sql_test)) {
			format_tests(
				$row_test["id"], 
				$row_test["username"], 
				$row_test["title"], 
				$row_test["url"], 
				$row_test["color"], 
				$row_test["claims"], 
				$row_test["goods"], 
				$row_test["bads"], 
				$row_test["cena_user"], 
				floor(bcdiv($row_test["balance"], $row_test["cena_advs"])), 
				$username, $my_ref_back, $ref_proc_1, $tests_nacenka
			);
		}

		echo '</tbody></table>';
	}else{
		echo '<div align="center" style="background: url(img/light.png) no-repeat top center; font-size: 14px; color: #ab0606; text-align: center; padding-top: 120px; margin-top: 20px; display: block;">Доступных к выполнению тестов нет.<br>Заходите позже</div><br>';
		echo '<div align="center"><span onClick="top.document.location.href=\'/advertise.php?ads=tests\';" class="proc-btn">Разместить тест</span></div>';
		echo '<br><br>';
	}
	
	}else{
echo '<span class="msg-warning">Для работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
}
}

include(ROOT_DIR."/footer.php");
?>