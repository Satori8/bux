<?php

$pagetitle = "Здесь зарабатывают на выполнении тестов";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

echo '<script type="text/javascript" src="js/js_tests_work.js"></script>';

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для выполнения оплачиваемых тестов необходимо авторизоваться!</span>';
}else{
	echo '<div id="ScrollTestWork"></div>';
	?><script language="JavaScript">ScrollTo();</script><?php

	require(ROOT_DIR."/config.php");

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	$my_country = $my_country;
	$my_lastiplog_ex = explode(".", $my_lastiplog);
	$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiar($_GET["id"]))))) ? abs(intval(limpiar($_GET["id"]))) : false;
	$token_get = ( isset($_GET["token"]) && preg_match("/^[0-9a-fA-F\$\!\/]{32}$/i", htmlspecialchars(limpiar(trim($_GET["token"]))))==1 ) ? htmlspecialchars(limpiar(trim($_GET["token"]))) : false;
	$check_token = strtolower(md5(strtolower($username).session_id().DATE("H").$_SERVER["HTTP_HOST"].$id."SecurityTestsWork"));

	$sql_test = mysql_query("SELECT * FROM `tb_ads_tests` 
		WHERE `id`='$id' AND `status`='1' AND `balance`>=`cena_advs` 
		AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') 
		AND (`sex_user`='0' OR `sex_user`='$my_sex') 
		AND (`date_reg_user`='0' 
			OR (`date_reg_user`='1' AND '$my_joindate'>='".(time()-7*86400)."') 
			OR (`date_reg_user`='2' AND '$my_joindate'<='".(time()-7*86400)."') 
			OR (`date_reg_user`='3' AND '$my_joindate'<='".(time()-30*86400)."') 
			OR (`date_reg_user`='4' AND '$my_joindate'<='".(time()-90*86400)."') 
		) 
	");
	$count_tests = mysql_num_rows($sql_test);
	if($count_tests>0 && $token_get==$check_token) {
		$row_test = mysql_fetch_assoc($sql_test);
		$id = $row_test["id"];
		$rek_name = $row_test["username"];
		$title = $row_test["title"];
		$url = $row_test["url"];
		$unic_ip_user = $row_test["unic_ip_user"];

		require_once (ROOT_DIR."/bbcode/bbcode.lib.php");
		$description = new bbcode($row_test["description"]);
		$description = $description->get_html();
		$description = str_replace("&amp;", "&", $description);
		$questions = unserialize($row_test["questions"]);
		$answers = unserialize($row_test["answers"]);

		if(!isset($_SESSION["start_tests"]["$id"])) $_SESSION["start_tests"]["$id"] = time();

		echo '<div style="margin:0 auto; margin-bottom:20px; background:#F0F8FF; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">';
			echo 'Внимательно прочтите и следуйте инструкции к выполнению теста. Последовательно ответьте на вопросы в тесте. Помните, все ответы должны быть верными, иначе тест будет закрыт для вас на 24 часа.<br>';
			echo '<div style="color:#E32636; text-align:center; margin-top:5px;">При попытках угадывать ответы на вопросы, вам может быть заблокирован доступ к тестам.</div>';
		echo '</div>';

		$sql_ban = mysql_query("SELECT `id` FROM `tb_ads_tests_bl` WHERE `user_name`='$username' AND `rek_name`='$rek_name'");
		if(mysql_num_rows($sql_ban)>0) {
			echo '<span class="msg-error">Вы не можете выполнить этот тест.<br>Рекламодатель запретил вам выполнять его тесты (черный список).</span>';
			include(ROOT_DIR."/footer.php");
			exit();
		}

		echo '<div class="test-blank">';
			echo '<div class="test-blank-title">№'.$id.': &laquo;'.$title.'&raquo;</div>';
			echo '<div class="test-block">';
				echo '<span class="test-desc">';
					echo '<b>Инструкции:</b><br><br>';
					echo "$description<br><br>";
					echo 'Ссылка для начала теста: <a href="'.$url.'" target="_blank" class="golinktest">'.$url.'</a>';
				echo '</span>';

				echo '<div id="test-content">';
					if($unic_ip_user==1) {
						$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND (`user_name`='$username' OR `ip`='$my_lastiplog') ORDER BY `id` DESC LIMIT 1");
					}elseif($unic_ip_user==2) {
						$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND (`user_name`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ORDER BY `id` DESC LIMIT 1");
					}else{
						$sql_visit = mysql_query("SELECT `user_name` FROM `tb_ads_tests_visits` WHERE `ident`='$id' AND `time_next`>'".time()."' AND `user_name`='$username' ORDER BY `id` DESC LIMIT 1");
					}
					if(mysql_num_rows($sql_visit)>0) {
						if($unic_ip_user==0) {
							echo '<br><br><span class="msg-error">Вы уже проходили этот тест за требуемый период!</span>';
						}else{
							$row_visit = mysql_fetch_assoc($sql_visit);

							if(strtolower($row_visit["user_name"])==strtolower($username)) {
								echo '<br><br><span class="msg-error">Вы уже проходили этот тест за требуемый период!</span>';
							}else{
								echo '<br><br><span class="msg-error">Тест на данный момент недоступен, попробуйте позже!</span>';
							}
						}
					}else{
						echo '<span class="test-sub-title">Ответьте на вопросы теста:</span>';

						$token_form = array();
						for($i=1; $i<=count($questions); $i++) {
							echo '<span class="test-quest"><b>Вопрос '.$i.':</b> '.$questions[$i].'</span>';

							$token_form[].= $answers[$i][1];
							shuffle($answers[$i]);

							echo '<div class="test-block-answ">';
								for($y=0; $y<3; $y++) {
									$get_answ = md5(mb_strtolower($answers[$i][$y].$username.session_id().$_SERVER["HTTP_HOST"].$id."SecurityCheckAnsw"));
									echo '<span id="answ-sel'.$i.($y+1).'" class="test-answ-sel" onClick="SetAnsw(\''.$get_answ.'\', '.$i.', '.($y+1).')">'.$answers[$i][$y].'</span>';
								}
							echo '</div>';
						}

						echo '<form name="FormTest">';
							echo '<input type="hidden" id="token" value="'.md5(implode(" | ", $token_form)."SecurityCheckAnsw").'" />';
							echo '<input type="hidden" id="id_test" value="'.$id.'" />';
							echo '<input type="hidden" id="answer1" value="" />';
							echo '<input type="hidden" id="answer2" value="" />';
							echo '<input type="hidden" id="answer3" value="" />';
							echo '<input type="hidden" id="answer4" value="" />';
							echo '<input type="hidden" id="answer5" value="" />';
							echo '<input type="hidden" id="cqa" value="'.count($questions).'" />';
						echo '</form><br>';

						echo '<div id="info-msg-test" style="display: none;"></div>';
						echo '<div align="center"><span class="proc-btn" title="Отправить тест на проверку" onClick="CheckTest(document);">Отправить отчёт</span></div>';
					}

				echo '</div>';
			echo '</div>';
		echo '</div>';

	}elseif($count_tests>0 && $token_get!=$check_token) {
		echo '<script type="text/javascript"> location.replace("/"); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/"></noscript>';
		exit();
	}else{
		echo '<script type="text/javascript"> location.replace("/tests-views"); </script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL=/tests-views"></noscript>';
		exit();
	}
}

include(ROOT_DIR."/footer.php");
?>