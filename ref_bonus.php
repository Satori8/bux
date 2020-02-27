<?php
$pagetitle="Бонусы рефералам";
require_once(".zsecurity.php");
include("header.php");
require("navigator/navigator.php");

//echo '<script type="text/javascript" src="../scripts/jquery.min.js"></script>';

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	require_once ("config.php");
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	$sql_comis = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
	$comis_sys_bon = mysql_result($sql_comis,0,0);

	if(isset($_GET["op"]) && limpiar($_GET["op"])=="add_bonus") {
		echo '<h3 class="sp" style="border:none; margin-top:0px; margin-bottom:6px; font-weight:bold;">Создание нового бонуса для рефералов:</h3>';

		$type_bon = ( isset($_POST["type_bon"]) && preg_match("|^[\d]{1}$|", trim($_POST["type_bon"])) ) ? intval(limpiar(trim($_POST["type_bon"]))) : 0;
		$savebon = isset($_POST["savebon"]) ? limpiar(trim($_POST["savebon"])) : false;
		$desc_bon = isset($_POST["desc_bon"]) ? limitatexto(trim($_POST["desc_bon"]),250) : false;
		$money_bon = (isset($_POST["money_bon"]) && number_format(floatval(abs(str_replace(",",".",trim($_POST["money_bon"])))),2,".","")>0) ? number_format(floatval(abs(str_replace(",",".",trim($_POST["money_bon"])))),2,".","") : "0";
		$count_nado = ( isset($_POST["count_nado"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_nado"])) ) ? abs(intval(limpiar(trim($_POST["count_nado"])))) : 0;
		if (get_magic_quotes_gpc()) { $desc_bon = stripslashes($desc_bon); }

		if(count($_POST)>0 && $type_bon>=1 && $type_bon<=6) {
			$s = "OK";

			if(isset($_POST["money_bon"])) {
				$sql_c = mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `username`='$username' AND `type_bon`='$type_bon'");

				if($money_bon == 0) {
					echo '<span class="msg-error">Не указана сумма бонуса.</span><br>';
					$s="NO";
				}elseif($money_bon>10 | $money_bon<0.10) {
					echo '<span class="msg-error">Бонус может быть между 0.10 руб. и 10.00 руб. включительно.</span><br>';
					$s="NO";
				}elseif($type_bon==3 && $count_nado==0) {
					echo '<span class="msg-error">Количество должно быть больше 0.</span><br>';
					$s="NO";
				}elseif($type_bon==3 && ($count_nado % 10) != 0) {
					echo '<span class="msg-error">Количество должно быть кратно 10.</span><br>';
					$s="NO";
				}elseif($type_bon==4 && $count_nado==0) {
					echo '<span class="msg-error">Количество должно быть больше 0.</span><br>';
					$s="NO";
				}elseif($type_bon==4 && ($count_nado % 10) != 0) {
					echo '<span class="msg-error">Количество должно быть кратно 10.</span><br>';
					$s="NO";
				}elseif($type_bon==5 && $count_nado==0) {
					echo '<span class="msg-error">Количество должно быть больше 0.</span><br>';
					$s="NO";
				}elseif($type_bon==5 && ($count_nado % 10) != 0) {
					echo '<span class="msg-error">Количество должно быть кратно 10.</span><br>';
					$s="NO";
				}elseif($type_bon==6 && $count_nado==0) {
					echo '<span class="msg-error">Количество должно быть больше 0.</span><br>';
					$s="NO";
				}elseif($type_bon==6 && ($count_nado % 1) != 0) {
					echo '<span class="msg-error">Количество должно быть кратно 1.</span><br>';
					$s="NO";
				}elseif(mysql_num_rows($sql_c)>0) {
					echo '<span class="msg-error">Вы уже создали бонус этого типа.</span><br>';
					$s="NO";
				}else{
					$s="OK";
				}
			}

			if($savebon=="savebonus2" && $s=="OK") {
				mysql_query("INSERT INTO `tb_refbonus` (`status`,`username`,`type_bon`,`date`,`count_nado`,`description`,`bonus`,`ip`) 
				VALUES('1','$username','$type_bon','".time()."','$count_nado','$desc_bon','$money_bon','$ip')") or die(mysql_error());

				echo '<span class="msg-ok">Бонус успешно создан!</span><br>';
				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';

				include('footer.php');
				exit();
			}

			echo '<form action="" method="POST" id="newform">';
				echo '<input type="hidden" name="savebon" value="savebonus2">';
				echo '<input type="hidden" name="type_bon" value="'.$type_bon.'">';

				echo '<table class="tables">';
				echo '<thead><tr><th align="center" colspan="3">Описание бонуса</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Бонус:</b></td>';
					echo '<td colspan="2">';
						if($type_bon==1) {
							echo 'За регистрацию ';
						}elseif($type_bon==2) {
							echo 'За привлечение рефералов';
						}elseif($type_bon==3) {
							echo 'За просмотр каждых ХХ сайтов в серфинге';
						}elseif($type_bon==4) {
							echo 'За выполнения ХХ заданий';
						}elseif($type_bon==5) {
							echo 'За просмотр каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге';
						}elseif($type_bon==6) {
							echo 'За сумму заработанную для реферера';
						}else{
							echo '<b style="color: #FF0000;">Ошибка! Не выбран тип бонуса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Описание бонуса:</b></td>';
					echo '<td colspan="2">';
						if($type_bon==1) {
							echo 'Ваш реферал получит бонус, после регистрации и проверки e-mail';
						}elseif($type_bon==2) {
							echo 'Ваш реферал получит бонус, если у него зарегистрируется новый реферал';
						}elseif($type_bon==3) {
							echo 'Ваш реферал получит бонус, после просмотра каждых ХХ сайтов в серфинге';
						}elseif($type_bon==4) {
							echo 'Ваш реферал получит бонус, после выполнения каждых ХХ заданий';
						}elseif($type_bon==5) {
							echo 'Ваш реферал получит бонус, после просмотра каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге';
						}elseif($type_bon==6) {
							echo 'Ваш реферал получит бонус, как только он заработает вам ХХ рублей';
						}else{
							echo '<b style="color: #FF0000;">Ошибка! Не выбран тип бонуса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap" width="20%"><b>Размер бонуса:</b></td>';
					echo '<td nowrap="nowrap" width="30%"><input type="text" name="money_bon" value="'.$money_bon.'" maxlength="5" class="ok12"> руб.</td>';
					echo '<td align="left" width="50%">(от <b>0.10</b> руб. до <b>10.00</b> руб.)</td>';
				echo '</tr>';

				if($type_bon==3) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество сайтов:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						echo '<td align="left">укажите количество сайтов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==4) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество заданий:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						echo '<td align="left">укажите количество заданий, которое нужно выполнить, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==5) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество видеороликов:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						echo '<td align="left">укажите количество видеороликов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==6) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Сумма заработка:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 1)</td>';
						echo '<td align="left">укажите сумму которую должен заработать реферал, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==2) {
				    echo '<tr>';
					    echo '<td align="right" nowrap="nowrap"><b>Минимум кликов для получения бонуса:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (0 - отключение)</td>';
						echo '<td align="left">укажите количество сайтов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}
				

				echo '<thead><tr><th align="center" colspan="3">Сообщение рефералу</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Текст сообщения:</b></td>';
					echo '<td colspan="2">Длина сообщения не должна превышать 255 символов, не используйте в тексте теги HTML</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3" align="center"><textarea class="ok" name="desc_bon">'.$desc_bon.'</textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3">Если Вы укажите сообщение рефералу, то как только он получит данный бонус, ему будет отправлено это сообщение в аккаунт.</td>';
				echo '</tr>';

				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
				echo '</tr>';
				echo '</table>';
			echo '</form>';

			if($type_bon==3) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = gebi("count_nado").value;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда просмотрит <b>"+count_nado+"</b> сайтов, <b>"+(count_nado*2)+"</b> сайтов, <b>"+(count_nado*3)+"</b> сайтов, <b>"+(count_nado*4)+"</b> сайтов и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				</script>
				<?php
			 }elseif($type_bon==4) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = gebi("count_nado").value;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда выполнит <b>"+count_nado+"</b> заданий, <b>"+(count_nado*2)+"</b> заданий, <b>"+(count_nado*3)+"</b> заданий, <b>"+(count_nado*4)+"</b> заданий и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				</script>
				<?php
			}elseif($type_bon==5) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = gebi("count_nado").value;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда посмотрит <b>"+count_nado+"</b> видеороликов, <b>"+(count_nado*2)+"</b> видеороликов, <b>"+(count_nado*3)+"</b> видеороликов, <b>"+(count_nado*4)+"</b> видеороликов и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				</script>
				<?php
			}elseif($type_bon==6) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = gebi("count_nado").value;
					if (count_nado > 0){
						if (Math.round(count_nado/1) == count_nado/1) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда заработает вам <b>"+count_nado+"</b> рублей, <b>"+(count_nado*2)+"</b> рублей, <b>"+(count_nado*3)+"</b> рублей, <b>"+(count_nado*4)+"</b> рублей и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				</script>
				<?php
			}					
			

		}else{
			if(count($_POST)>0) {
				if($type_bon<1 && $type_bon>6) {
					echo '<span class="msg-error">Не выбран тип бонуса.</span><br>';
				}
			}

			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
				echo '<thead><tr><th align="center" colspan="3">Тип бонуса</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right" width="20%" nowrap="nowrap"><b>Тип бонуса:</b></td>';
					echo '<td width="30%">';
						echo '<select name="type_bon" onChange="viewinfo(this.value);" onKeyUp="viewinfo(this.value);" style="">';
							echo '<option value="0" disabled="disabled" style="color:#000; font-weight:bold;" '.('0' == $type_bon ? 'selected="selected"' : '').'>Выберите бонус</option>';
							echo '<option value="1" '.('1' == $type_bon ? 'selected="selected"' : '').'>За регистрацию</option>';
							echo '<option value="2" '.('2' == $type_bon ? 'selected="selected"' : '').'>За привлечение рефералов</option>';
							echo '<option value="3" '.('3' == $type_bon ? 'selected="selected"' : '').'>За просмотр каждых ХХ сайтов в серфинге</option>';
							echo '<option value="4" '.('4' == $type_bon ? 'selected="selected"' : '').'>За выполнение каждых ХХ заданий</option>';
							echo '<option value="5" '.('5' == $type_bon ? 'selected="selected"' : '').'>За просмотр каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге</option>';
							echo '<option value="6" '.('6' == $type_bon ? 'selected="selected"' : '').'>За заработок каждых ХХ рублей';
						echo '</select>';
					echo '</td>';
					echo '<td width="50%" align="left">&nbsp;</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Описание бонуса:</b></td>';
					echo '<td id="bonus_info" colspan="2">&nbsp;</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
				echo '</tr>';
			echo '</table>';
			echo '</form>';

			?><script language="JavaScript">
				var bonusinfo = new Array();
				bonusinfo[0] = '';
				bonusinfo[1] = 'Ваш реферал получит бонус, после регистрации и проверки e-mail';
				bonusinfo[2] = 'Ваш реферал получит бонус, если у него зарегистрируется новый реферал';
				bonusinfo[3] = 'Ваш реферал получит бонус, после просмотра каждых ХХ сайтов в серфинге';
				bonusinfo[4] = 'Ваш реферал получит бонус, после выполнения каждых ХХ заданий';
				bonusinfo[5] = 'Ваш реферал получит бонус, после просмотра каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге';
				bonusinfo[6] = 'Ваш реферал получит бонус, после заработка вам каждых ХХ рублей';

				function gebi(id){
					return document.getElementById(id)
				}
				function viewinfo(i){
					if (bonusinfo[i]){
						gebi("bonus_info").innerHTML = bonusinfo[i];
					}
				}
			</script><?php
		}

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="edit_bonus") {
		echo '<h3 class="sp" style="border:none; margin-top:0px; margin-bottom:6px; font-weight:bold;">Редактирование бонуса для рефералов:</h3>';

		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
		$money_bon = (isset($_POST["money_bon"]) && number_format(floatval(abs(str_replace(",",".",trim($_POST["money_bon"])))),2,".","")>0) ? number_format(floatval(abs(str_replace(",",".",trim($_POST["money_bon"])))),2,".","") : "0";
		$desc_bon = isset($_POST["desc_bon"]) ? limitatexto(trim($_POST["desc_bon"]),250) : false;
		$count_nado = ( isset($_POST["count_nado"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["count_nado"])) ) ? abs(intval(limpiar(trim($_POST["count_nado"])))) : 0;
		if (get_magic_quotes_gpc()) { $desc_bon = stripslashes($desc_bon); }

		/*if(count($_POST)>0) {
			$s = "OK";
			list($my_type_bon)=mysql_fetch_array(mysql_query("SELECT type_bon FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1"));

			if(isset($_POST["money_bon"])) {
				if($money_bon == 0) {
					echo '<span class="msg-error">Не указана сумма бонуса.</span><br>';
					$s="NO";
				}elseif($money_bon>10 || $money_bon<0.10) {
					echo '<span class="msg-error">Бонус может быть между 0.10 руб. и 10.00 руб. включительно.</span></br>';
					$s="NO";
				}elseif($my_type_bon==2 && ($count_nado<1 || $count_nado>25)) {
					echo '<span class="msg-error">Минимум кликов для получения бонуса может быть между 1 и 25 включительно.</span></br>';
					$s="NO";
				}else{
					$s="OK";
				}
			}

			if($s=="OK") {
				if ($my_type_bon==2) {
				    $up_count_nado=", `count_nado`='$count_nado'";
				} else {
				    $up_count_nado="";
				}
				mysql_query("UPDATE `tb_refbonus` SET `date`='".time()."', `description`='$desc_bon', `bonus`='$money_bon', `ip`='$ip', $up_count_nado WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

				echo '<span class="msg-ok">Бонус для рефералов успешно отредактирован!</span><br>';
				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';

				include('footer.php');
				exit();
			}
		}*/
		if(count($_POST)>0) {
			$s = "OK";
			list($my_type_bon)=mysql_fetch_array(mysql_query("SELECT type_bon FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1"));

			if(isset($_POST["money_bon"])) {
				if($money_bon == 0) {
					echo '<span class="msg-error">Не указана сумма бонуса.</span><br>';
					$s="NO";
				}elseif($money_bon>10 || $money_bon<0.10) {
					echo '<span class="msg-error">Бонус может быть между 0.10 руб. и 10.00 руб. включительно.</span></br>';
					$s="NO";
				}elseif($my_type_bon==1 && $my_type_bon==2 && $my_type_bon==3 && $my_type_bon==4 && $my_type_bon==5 && $my_type_bon==6 && ($count_nado<1 || $count_nado>5000)) {
					echo '<span class="msg-error">Минимум кликов для получения бонуса может быть между 1 и 25 включительно.</span></br>';
					$s="NO";
				}else{
					$s="OK";
				}
			}

			if($s=="OK") {
				if ($my_type_bon==1 | $my_type_bon==2 | $my_type_bon==3 | $my_type_bon==4 | $my_type_bon==5 | $my_type_bon==6) {
				    $up_count_nado=", `count_nado`='$count_nado'";
				} else {
				    $up_count_nado="";
				}
				mysql_query("UPDATE `tb_refbonus` SET `date`='".time()."', `description`='$desc_bon', `bonus`='$money_bon', `ip`='$ip' $up_count_nado WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

				echo '<span class="msg-ok">Бонус для рефералов успешно отредактирован!</span><br>';
				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';

				include("footer.php");
				exit();
			}
		}


		$sql = mysql_query("SELECT * FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);
			$type_bon = $row["type_bon"];
			$money_bon = $row["bonus"];
			$count_nado = $row["count_nado"];
			$desc_bon = $row["description"];

			echo '<form action="" method="POST" id="newform">';
			echo '<table class="tables">';
				echo '<thead><tr><th align="center" colspan="3">Описание бонуса</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Бонус:</b></td>';
					echo '<td colspan="2">';
						if($type_bon==1) {
							echo 'За регистрацию ';
						}elseif($type_bon==2) {
							echo 'За привлечение рефералов';
						}elseif($type_bon==3) {
							echo 'За просмотр каждых ХХ сайтов в серфинге';
						}elseif($type_bon==4) {
							echo 'За выполнения ХХ заданий';
						}elseif($type_bon==5) {
							echo 'За просмотр каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге';
						}elseif($type_bon==6) {
							echo 'За сумму заработанную для реферера';
						}else{
							echo '<b style="color: #FF0000;">Ошибка! Не выбран тип бонуса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right" nowrap="nowrap"><b>Описание бонуса:</b></td>';
					echo '<td colspan="2">';
						if($type_bon==1) {
							echo 'Ваш реферал получит бонус, после регистрации и проверки e-mail';
						}elseif($type_bon==2) {
							echo 'Ваш реферал получит бонус, если у него зарегистрируется новый реферал';
						}elseif($type_bon==3) {
							echo 'Ваш реферал получит бонус, после просмотра каждых ХХ сайтов в серфинге';
						}elseif($type_bon==4) {
							echo 'Ваш реферал получит бонус, после выполнения каждых ХХ заданий';
						}elseif($type_bon==5) {
							echo 'Ваш реферал получит бонус, после просмотра каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге';
						}elseif($type_bon==6) {
							echo 'Ваш реферал получит бонус, как только он заработает вам ХХ рублей';
						}else{
							echo '<b style="color: #FF0000;">Ошибка! Не выбран тип бонуса</b>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td align="right" width="20%" nowrap="nowrap"><b>Размер бонуса</b></td>';
					echo '<td width="30%"><input type="text" name="money_bon" value="'.$money_bon.'" class="ok12"> руб.</td>';
					echo '<td align="left" width="50%">(от <b>0.10</b> руб. до <b>10.00</b> руб.)</td>';
				echo '</tr>';

				if($type_bon==3) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество сайтов:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						//echo '<td nowrap="nowrap">'.$count_nado.'</td>';
						echo '<td align="left">количество сайтов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==4) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество заданий:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						echo '<td align="left">укажите количество заданий, которое нужно выполнить, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==5) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Количество видеороликов:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 10)</td>';
						echo '<td align="left">количество видеороликов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==6) {
					echo '<tr>';
						echo '<td align="right" nowrap="nowrap"><b>Сумма заработка:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (кратное 1)</td>';
						echo '<td align="left">укажите сумму которую должен заработать реферал, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}elseif($type_bon==2) {
				    echo '<tr>';
					    echo '<td align="right" nowrap="nowrap"><b>Минимум кликов для получения бонуса:</b></td>';
						echo '<td nowrap="nowrap"><input type="text" id="count_nado" name="count_nado" value="'.$count_nado.'" maxlength="5" class="ok12"  onChange="bonushelp();" onKeyUp="bonushelp();"> (минимум 1)</td>';
						echo '<td align="left">укажите количество сайтов, которое нужно просмотреть, для получения бонуса</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td align="left" colspan="3" id="bonus_info">Этот бонус может быть получен пользователем много раз</td>';
					echo '</tr>';
				}

				echo '<thead><tr><th align="center" colspan="3">Сообщение рефералу</th></tr></thead>';
				echo '<tr>';
					echo '<td align="right"><b>Текст сообщения:</b></td>';
					echo '<td colspan="2">Длина сообщения не должна превышать 255 символов, не используйте в тексте теги HTML</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3" align="center"><textarea style="width:99%;height:100px;" name="desc_bon">'.$desc_bon.'</textarea></td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td colspan="3">Если Вы укажите сообщение рефералу, то как только он получит данный бонус, ему будет отправлено это сообщение в аккаунт.</td>';
				echo '</tr>';

				echo '<tr>';
					echo '<td align="center" height="25px" colspan="3"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;Далее&nbsp;&nbsp;" /></td>';
				echo '</tr>';
			echo '</table>';
			echo '</form>';

			if($type_bon==3) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = <?=$count_nado;?>;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда просмотрит <b>"+count_nado+"</b> сайтов, <b>"+(count_nado*2)+"</b> сайтов, <b>"+(count_nado*3)+"</b> сайтов, <b>"+(count_nado*4)+"</b> сайтов и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				bonushelp();
				</script><?php
			}elseif($type_bon==4) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = <?=$count_nado;?>;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда выполнит <b>"+count_nado+"</b> заданий, <b>"+(count_nado*2)+"</b> заданий, <b>"+(count_nado*3)+"</b> заданий, <b>"+(count_nado*4)+"</b> заданий и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				bonushelp();
				</script><?php
			}elseif($type_bon==5) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = <?=$count_nado;?>;
					if (count_nado > 0){
						if (Math.round(count_nado/10) == count_nado/10) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда посмотрит <b>"+count_nado+"</b> видеороликов, <b>"+(count_nado*2)+"</b> видеороликов, <b>"+(count_nado*3)+"</b> видеороликов, <b>"+(count_nado*4)+"</b> видеороликов и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				bonushelp();
				</script><?php
			}elseif($type_bon==6) {
				?><script language="JavaScript">
				function gebi(id) { return document.getElementById(id); }

				function bonushelp() {
					var count_nado = gebi("count_nado").value;
					if (count_nado > 0){
						if (Math.round(count_nado/1) == count_nado/1) {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз, например реферал получил бонус, когда заработает вам <b>"+count_nado+"</b> рублей, <b>"+(count_nado*2)+"</b> рублей, <b>"+(count_nado*3)+"</b> рублей, <b>"+(count_nado*4)+"</b> рублей и т. п.";
						} else {
							gebi("bonus_info").innerHTML = "Этот бонус может быть получен пользователем много раз";
						}
					}
				}
				</script>
				<?php
			}						
			

		}else{
			echo '<span class="msg-error">Бонус не найден.</span><br>';
		}

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="del_bonus") {
		echo '<h3 class="sp" style="border:none; margin-top:0px; margin-bottom:6px; font-weight:bold;">Удаление бонуса для рефералов:</h3>';

		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

		$sql = mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);
			$id = $row["id"];

			$sql_stat = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='0' AND `ident`='$id'");
			if(mysql_num_rows($sql_stat)>0) {

				echo '<span class="msg-error">Бонус не возможно удалить. У вас есть не погашенные бонусы рефералам, для удаления погасите задолженность перед вашими рефералами</span><br>';
				echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'")\', 7000); </script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="7;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
			}else{
				mysql_query("DELETE FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("DELETE FROM `tb_refbonus_stat` WHERE `ident`='$id'") or die(mysql_error());

				echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'")\', 0); </script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
			}
		}

	}elseif(isset($_GET["op"]) && limpiar($_GET["op"])=="viewstat_bonus") {
		$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
		echo '<h3 class="sp" style="margin-top:0px; margin-bottom:6px; font-weight:bold;">Статистика по бонусу для рефералов #'.$id.':</h3>';

		mysql_query("DELETE FROM `tb_refbonus_stat` WHERE `status`='0' AND `username` NOT IN (SELECT `username` FROM `tb_users`)") or die(mysql_error());


		$sql = mysql_query("SELECT * FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if(count($_POST)>0) {
				$bonus_ident = $row["id"];
				$pay_id = (isset($_POST["pay_id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["pay_id"]))) ? intval(limpiar(trim($_POST["pay_id"]))) : false;

				$sql_pay_b = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='0' AND `id`='$pay_id' AND `ident`='$bonus_ident'") or die(mysql_error());
				if(mysql_num_rows($sql_pay_b) > 0) {
					$row_pay_b = mysql_fetch_array($sql_pay_b);
					$username_pay_b = $row_pay_b["username"];
					$money_pay_b = $row_pay_b["money"];

					$sql_chk_user = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$username_pay_b'") or die(mysql_error());
					if(mysql_num_rows($sql_chk_user)>0) {
						$row_chk_user = mysql_fetch_row($sql_chk_user);
						$user_id_pay_b = $row_chk_user["0"];

						$money_nado_pay = ($money_pay_b * ($comis_sys_bon+100)/100);
						$money_nado_pay = round($money_nado_pay, 2);

						if($my_money_rb>=$money_nado_pay) {
							mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='$bonus_ident'") or die(mysql_error());
							mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `date`='".time()."' WHERE `id`='$pay_id'") or die(mysql_error());

							mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_pay_b' WHERE `username`='$username_pay_b'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_nado_pay' WHERE `username`='$username'") or die(mysql_error());

							if($row["type_bon"]==1) {
								$text_history_1 = "Реф-Бонус от реферера $username за регистрацию на проекте";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за регистрацию на проекте";
							}elseif($row["type_bon"]==2) {
								$text_history_1 = "Реф-Бонус от реферера $username за регистрацию реферала #".$row_pay_b["stat_info"]."";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за привлечение реферала #".$row_pay_b["stat_info"]."";
							}elseif($row["type_bon"]==3) {
								$text_history_1 = "Реф-Бонус от реферера $username за ".$row["count_nado"]." кликов в серфинге";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за ".$row["count_nado"]." кликов в серфинге";
							}elseif($row["type_bon"]==4) {
								$text_history_1 = "Реф-Бонус от реферера $username за ".$row["count_nado"]." выполнение задания";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за ".$row["count_nado"]." выполнение задания";
							}elseif($row["type_bon"]==5) {
								$text_history_1 = "Реф-Бонус от реферера $username за ".$row["count_nado"]." просмотров видеороликов в <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span> серфинге";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за ".$row["count_nado"]." просмотров видеороликов в <span style=\"color: #3F3F3F;\">You</span><span style=\"border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;\">Tube</span> серфинге";
							}elseif($row["type_bon"]==6) {
								$text_history_1 = "Реф-Бонус от реферера $username за заработок ".number_format($row["count_nado"],2,".","`")." руб.";
								$text_history_2 = "Реф-Бонус рефералу $username_pay_b за заработок ".number_format($row["count_nado"],2,".","`")." руб.";
							}

							mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
							VALUES('$username_pay_b','$user_id_pay_b','".DATE("d.m.Y H:i")."','".time()."','$money_pay_b','$text_history_1','Зачислено','rashod')") or die(mysql_error());

							mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
							VALUES('$username','$partnerid','".DATE("d.m.Y H:i")."','".time()."','$money_nado_pay','$text_history_2','Списано','rashod')") or die(mysql_error());

							if(trim($row["description"])!=false) {
								mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
								VALUES('$username_pay_b','Система','$text_history_1','".$row["description"]."','0','".time()."','0.0.0.0')");
							}
						}else{
							echo '<span class="msg-error">На вашем рекламном счету недостаточно средств для выплаты бонуса рефералу <b>'.$username_pay_b.'</b>.<br>Необходимая сумма <b>'.number_format($money_nado_pay, 2, ".", "").'</b> руб. (c учетом комиссии сайта '.$comis_sys_bon.' %)</span><br>';
						}

					}else{
						echo '<span class="msg-error">Пользователь с логином <b>'.$username_pay_b.'</b> не найден в системе, возможно он был удален!</span><br>';
					}
				}
			}

			$_where_arr = array("","1","0","-1");
			$_WHERE = (isset($_GET["status"]) && intval($_GET["status"])>0 && intval($_GET["status"])<=3) ? " AND `status`='".$_where_arr[intval($_GET["status"])]."'" : false;
			$_PAGE_GET = (isset($_GET["status"]) && intval($_GET["status"])>0 && intval($_GET["status"])<=3) ? "&status=".intval($_GET["status"]) : false;

			$perpage = 30;
			$sql_p = mysql_query("SELECT `id` FROM `tb_refbonus_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_bon"]."' $_WHERE");
			$count = mysql_numrows($sql_p);
			$pages_count = ceil($count / $perpage);
			$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
			if ($page > $pages_count | $page<0) $page = $pages_count;
			$start_pos = ($page - 1) * $perpage;
			if($start_pos<0) $start_pos = 0;

			echo '<table class="tables" style="margin:5px auto;">';
			echo '<tr align="center">';
				echo '<td align="left"><b>Показать:</b></td>';
				echo '<td align="center"><a href="?op=viewstat_bonus&id='.$id.'" '.($_PAGE_GET==false ? 'style="color:#FF0000;"' : false).'><b>Все</b></a></td>';
				echo '<td align="center"><a href="?op=viewstat_bonus&id='.$id.'&status=1" '.((isset($_GET["status"]) && intval($_GET["status"])==1) ? 'style="color:#FF0000;"' : false).'><b>Выплаченные</b></td>';
				echo '<td align="center"><a href="?op=viewstat_bonus&id='.$id.'&status=2" '.((isset($_GET["status"]) && intval($_GET["status"])==2) ? 'style="color:#FF0000;"' : false).'><b>Не выплаченные</b></td>';
				echo '<td align="center"><a href="?op=viewstat_bonus&id='.$id.'&status=3" '.((isset($_GET["status"]) && intval($_GET["status"])==3) ? 'style="color:#FF0000;"' : false).'><b>Не выполнены условия</b></td>';
			echo '</tr>';
			echo '</table>';

			if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=viewstat_bonus&id=$id".$_PAGE_GET);}
			echo '<table class="tables">';
			echo '<thead><tr align="center">';
				echo '<th align="center">Дата</th>';
				echo '<th align="center">Логин</th>';
				echo '<th align="center">Статистика</th>';
				echo '<th align="center">Бонус</th>';
				echo '<th align="center">Действие</th>';
			echo '</tr></thead>';

			$sql_s = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `ident`='".$row["id"]."' AND `type`='".$row["type_bon"]."' $_WHERE ORDER BY `id` DESC LIMIT $start_pos,$perpage");
			if(mysql_num_rows($sql_s) > 0) {
				while ($row_s = mysql_fetch_array($sql_s)) {

					echo '<tr align="center">';

						echo '<td>'.DATE("d.m.Yг. H:i", $row_s["date"]).'</td>';

						echo '<td>'.$row_s["username"].'</td>';

						echo '<td>';
							if($row["type_bon"]==1) {
								echo 'регистрация по вашей реф.ссылке';
							}elseif($row["type_bon"]==2) {
								echo 'регистрация реферала #'.$row_s["stat_info"].'';
							}elseif($row["type_bon"]==3) {
								echo 'просмотрено ссылок: <b>'.$row_s["stat_info"].'</b> шт.';
							}elseif($row["type_bon"]==4) {
								echo 'выполненно заданий: <b>'.$row_s["stat_info"].'</b> шт.';
							}elseif($row["type_bon"]==5) {
								echo 'просмотрено видеороликов: <b>'.$row_s["stat_info"].'</b> шт.';
							}elseif($row["type_bon"]==6) {
								echo 'Заработано: <b>'.number_format($row_s["stat_info"],2,'.','`').'</b> руб.';
							
							}
						echo '</td>';

						echo '<td>'.$row_s["money"].'</td>';

						echo '<td>';
							if($row_s["status"]==-1 && $row["type_bon"]==1) {
								echo '<span style="color: #FF0000;">e-mail еще не подтвержден</span>';
							}elseif($row_s["status"]==-1 && $row["type_bon"]==2) {
								echo '<span style="color: #FF0000;">еще не выполнены условия</span>';
							}elseif($row_s["status"]==-1 && $row["type_bon"]==3) {
								echo '<span style="color: #FF0000;">еще не выполнены условия</span>';
							}elseif($row_s["status"]==-1 && $row["type_bon"]==4) {
								echo '<span style="color: #FF0000;">еще не выполнены условия</span>';
							}elseif($row_s["status"]==-1 && $row["type_bon"]==5) {
								echo '<span style="color: #FF0000;">еще не выполнены условия</span>';
							}elseif($row_s["status"]==-1 && $row["type_bon"]==6) {
								echo '<span style="color: #FF0000;">еще не выполнены условия</span>';
							}elseif($row_s["status"]==0) {
								echo '<span style="color: #FF0000;">бонус не передан, недостаточно средств</span><br>';

								echo '<form method="POST" action="" id="newform">';
									echo '<input type="hidden" name="pay_id" value="'.$row_s["id"].'">';
									echo '<input type="submit" value="Оплатить" class="proc-btn" style="float:none;">';
								echo '</form>';

							}elseif($row_s["status"]==1) {
								echo '<span style="color: #008000;">бонус передан</span><br>';
							}else{
								echo "";
							}
						echo '</td>';

					echo '</tr>';
				}
			}else{
				echo '<tr><td colspan="5" align="center"><b>Раздачи бонусов еще не было!</b></td></tr>';
			}

			echo '</table>';
			if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=viewstat_bonus&id=$id".$_PAGE_GET);}

		}else{
			echo '<span class="msg-error">Бонус не найден.</span><br>';
		}

	}else{

		if(isset($_GET["op"]) && limpiar($_GET["op"])=="onoff_bonus") {
			$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;
			$v = (isset($_GET["v"]) && (intval($_GET["v"])==0 | intval($_GET["v"])==1)) ? intval(trim($_GET["v"])) : "1";

			$sql = mysql_query("SELECT `id` FROM `tb_refbonus` WHERE `id`='$id' AND `username`='$username'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);
				$id = $row["id"];

				$sql_stat = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='0' AND `ident`='$id'");
				if(mysql_num_rows($sql_stat)>0 && $v==0) {

					echo '<span class="msg-error">Бонус не возможно отключить. У вас есть не погашенные бонусы рефералам, для отключения погасите задолженность перед вашими рефералами</span><br>';
					echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'")\', 7000); </script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="7;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
				}else{
					mysql_query("UPDATE `tb_refbonus` SET `status`='$v', `date`='".time()."', `ip`='$ip' WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				}
			}

		}

		?>
		<!-- <script type="text/javascript" src="../scripts/jquery.min.js"></script> -->
		<script src="../view_task/tack_info.js?v=1.00"></script>
		<!-- <script type="text/javascript" src="../scripts/jqpooop.js"></script> -->
		<?php

        echo '<div style="color:#107; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f5e7d3" align="justify">';
		echo '<h3 class="sp" style="border:none; margin-top:0px; margin-bottom:3px; font-weight:bold;">На данный момент доступны для создания следующие бонусы:</h3>';
		echo '<ul class="grey">';
			echo '<li><b>За регистрацию</b> &mdash; Ваш реферал получит бонус, после регистрации и проверки e-mail;</li>';
			echo '<li><b>За привлечение рефералов</b> &mdash; Ваш реферал получит бонус, если у него зарегистрируется новый реферал;</li>';
			echo '<li><b>За просмотр каждых ХХ сайтов в серфинге</b> &mdash; Ваш реферал получит бонус, после просмотра каждых ХХ сайтов в серфинге.</li>';
			echo '<li><b>За выполнение каждых ХХ заданий</b> &mdash; Ваш реферал получит бонус, после выполнения каждых ХХ заданий.</li>';
			echo '<li><b>За просмотр каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге</b> &mdash; Ваш реферал получит бонус, после просмотра каждых ХХ видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге.</li>';
		    echo '<li><b>За сумму заработанную для реферера</b> &mdash; Ваш реферал получит бонус, после заработка вам каждых ХХ рублей.</li>';
		
		echo '</ul><br>';
		echo '</div>';
		
		echo '<div class="text-justify">';
echo '<img src="/img/ukrah/cveta-1011.gif" alt="" title="" style="float: left;margin-top: 40px; width="140px" />';
echo '</div><br>';


		echo '<a href="'.$_SERVER["PHP_SELF"].'?op=add_bonus"><img src="img/add.png" border="0" alt="" align="absmiddle" title="Создать новый бонус для рефералов" /></a><a href="'.$_SERVER["PHP_SELF"].'?op=add_bonus" title="Создать новый бонус для рефералов"><b>Создать новый бонус для рефералов</b></a><br><br>';

		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			//echo '<th align="center">№</th>';
			echo '<th align="center">Статус</th>';
			echo '<th align="center">Тип бонуса</th>';
			echo '<th align="center">Бонус (руб.)</th>';
			echo '<th align="center">Выплачено бонусов</th>';
		echo '</tr></thead>';

		$sql = mysql_query("SELECT * FROM `tb_refbonus` WHERE `username`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';

					//echo '<td align="center">'.$row["id"].'</td>';

					echo '<td align="center">';
						if($row["status"]==0) {
							echo '<a href="'.$_SERVER["PHP_SELF"].'?op=onoff_bonus&id='.$row["id"].'&v='.($row["status"]+1).'">';
								echo '<img src="/img/adv/icon-play.png" alt="" border="0" aling="absmiddle" title="Запустить" />';
							echo '</a>';
						}elseif($row["status"]==1) {
							echo '<a href="'.$_SERVER["PHP_SELF"].'?op=onoff_bonus&id='.$row["id"].'&v='.($row["status"]-1).'">';
								echo '<img src="/img/adv/icon-pause.png" alt="" border="0" aling="absmiddle" title="Приостановить" />';
							echo '</a>';
						}else{
							echo "";
						}
					echo '</td>';


					echo '<td align="left">';
						echo "ID:<b>".$row["id"]."</b>, ";
						if($row["type_bon"]==1) {
							echo 'За регистрацию<br>';
						}elseif($row["type_bon"]==2) {
							echo 'За привлечение рефералов<br>';
						}elseif($row["type_bon"]==3) {
							echo 'За просмотр каждых <b>'.$row["count_nado"].'</b> сайтов в серфинге<br>';
						}elseif($row["type_bon"]==4) {
							echo 'За выполнение каждых <b>'.$row["count_nado"].'</b> заданий<br>';
						}elseif($row["type_bon"]==5) {
							echo 'За просмотр каждых <b>'.$row["count_nado"].'</b> видеороликов в <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге<br>';
						}elseif($row["type_bon"]==6) {
							echo 'За заработок каждых <b>'.number_format($row["count_nado"],2,'.','`').'</b> руб.<br>';
						}else{
							echo '<b style="color:#FF0000;">Тип бонуса не определен!</b><br>';
						}
						
						echo '<a href="'.$_SERVER["PHP_SELF"].'?op=del_bonus&id='.$row["id"].'" onclick=\'if(!confirm("Вы уверены, что хотите удалить бонус?"))return false;\'"><img src="/style/img/adv/icon-dell.png" alt="" border="0" aling="absmiddle" title="Удалить" style="margin:0 0 0 4px; padding:0; float:right;"></a>&nbsp;&nbsp;';
						echo '<a href="'.$_SERVER["PHP_SELF"].'?op=edit_bonus&id='.$row["id"].'"><img src="/style/img/adv/icon-edit.png" alt="" border="0" aling="absmiddle" title="Редактировать" style="margin:0 0 0 5px; padding:0; float:right;"></a>&nbsp;&nbsp;';
						echo '<a href="'.$_SERVER["PHP_SELF"].'?op=viewstat_bonus&id='.$row["id"].'"><img src="/style/img/adv/icon-statistics.png" alt="" border="0" aling="absmiddle" title="Просмотреть статистику" style="margin:0 0 0 4px; padding:0; float:right;"></a>';
					echo '</td>';

					echo '<td align="center">'.$row["bonus"].'</td>';

					echo '<td align="center">';
						echo ''.$row["count_bon"].'';
						if($row["type_bon"]=="2") {
							echo '<br><a href="javascript: void(0);" onclick="info_bon();" style="color:#FF0000; text-decoration:underline; font-size:85%;">обязательно прочитайте</a>';
						}
					echo '</td>';

				echo '</tr>';
			}
		}else{
			echo '<tr><td colspan="4" align="center"><b>Созданных бонусов нет!</b></td></tr>';
		}
		echo '</table>';

		echo '<br><b style="color:#FF0000;">*</b> - Комиссия сайта составляет <b>'.$comis_sys_bon.'%</b>, удерживается в момент получения бонуса рефералом';
	}
}

include('footer.php');
?>