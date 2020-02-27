<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
sleep(0);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	function limpiarez($mensaje) {
		$mensaje = trim($mensaje);
		$mensaje = mysql_real_escape_string(trim($mensaje));
		$mensaje = iconv("UTF-8", "CP1251", trim($mensaje));
		$mensaje = htmlspecialchars(trim($mensaje));
		$mensaje = str_replace("'", "", $mensaje);
		$mensaje = str_replace("`", "", $mensaje);
		$mensaje = str_replace(">", "", $mensaje);
		$mensaje = str_replace("<", "", $mensaje);
		$mensaje = str_replace("?", "&#063;", $mensaje);
		$mensaje = str_replace("$", "&#036;", $mensaje);
		$mensaje = str_replace("null", "", $mensaje);
		return $mensaje;
	}

	require($_SERVER['DOCUMENT_ROOT']."/config.php");
	require($_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$id_z = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval(limpiar($_POST["id"])))) ? intval(limpiar($_POST["id"])) : false;
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiar($_POST["op"])) ) ? limpiar($_POST["op"]) : false;
	$price_v = (isset($_POST["price_v"])  && preg_match("|^[-+]?\d*\.?\d*$|", str_replace(",",".", limpiar($_POST["price_v"]))) ) ? round(abs(floatval(str_replace(",",".", limpiar($_POST["price_v"])))),2) : false;
	$comment = (isset($_POST["comment"])) ? limitatexto(limpiarez($_POST["comment"]), 300) : false;
	$my_ip = getRealIP();

	if(isset($username) && $username!=false) {
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='null_ref_cena_min' AND `howmany`='1'");
		$null_ref_cena_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='null_ref_comis' AND `howmany`='1'");
		$null_ref_comis = mysql_result($sql,0,0);

		$sql_user = mysql_query("SELECT `id`,`username`,`referer`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_array($sql_user);
			$my_user_id = $row_user["id"];
			$my_user_name = $row_user["username"];
			$my_referer = $row_user["referer"];
			$my_money_rb = $row_user["money_rb"];
		}else{
			exit("Необходимо авторизоваться!");
		}

		if($option=="new_applic") {

			$sql_check = mysql_query("SELECT `id` FROM `tb_null_referer` WHERE `status`='0' AND `username`='$my_user_name'");
			if(mysql_num_rows($sql_check)>0) {
				exit("У вас уже есть заявка на выкуп!");
			}elseif($my_referer == false) {
				exit("У вас нет реферера!");
			}elseif($price_v == false) {
				exit("Укажите сумму выкупа!");
			}elseif($price_v > $my_money_rb) {
				exit("На вашем рекламном счету недостаточно средств!");
			}elseif($price_v < $null_ref_cena_min) {
				exit("Минимальная сумма выкупа $null_ref_cena_min руб.");
			}else{
				$text_applic = "Ваш реферал <b>$my_user_name</b> предлагает Вам за себя выкуп в размере $price_v руб.<br>";
				$text_applic.= "Для одобрения или отклонения заявки перейдите в раздел <a href=\"null_referer.php\"><b>&gt;&gt;выкуп&lt;&lt;</b></a>";

				mysql_query("INSERT INTO `tb_null_referer` (`status`,`date_time`,`username`,`referer`,`comment`,`money`,`ip`) 
				VALUES('0','".time()."','$my_user_name','$my_referer','','$price_v','$my_ip')") or die(mysql_error());

				$sql_l_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_null_referer`");
				$last_id_tb_null = mysql_result($sql_l_id,0,0);

				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$price_v' WHERE `username`='$my_user_name'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
				VALUES('$my_referer','Система','Выкуп','$text_applic','0','".time()."','$my_ip')") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$my_user_name','".DATE("d.m.Yг. H:i")."','".time()."','$price_v','Оплата заявки ID:$last_id_tb_null на выкуп себя у реферера $my_referer','Списано','rashod')") or die(mysql_error());

				exit("OK");
			}

		}elseif($option=="dell_applic") {
			$sql_check = mysql_query("SELECT `id`,`money` FROM `tb_null_referer` WHERE `id`='$id_z' AND `status`='0' AND `username`='$my_user_name'");
			if(mysql_num_rows($sql_check)>0) {
				$row_z = mysql_fetch_array($sql_check);
				$money_back = $row_z["money"];

				mysql_query("DELETE FROM `tb_null_referer` WHERE `id`='$id_z' AND `status`='0' AND `username`='$my_user_name'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back' WHERE `username`='$my_user_name'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$my_user_name','".DATE("d.m.Yг. H:i")."','".time()."','$money_back','Возврат средств по отозванной заявке №$id_z на выкуп у реферера $my_referer','Зачислено','rashod')") or die(mysql_error());

				exit("OK");
			}else{
				exit("Ошибка! У вас нет заявки с ID: $id_z");
			}

		}elseif($option=="good_applic") {
			$sql_check = mysql_query("SELECT `id`,`username`,`money` FROM `tb_null_referer` WHERE `id`='$id_z' AND `status`='0' AND `referer`='$my_user_name'");
			if(mysql_num_rows($sql_check)>0) {
				$row_z = mysql_fetch_array($sql_check);
				$money_add = ($row_z["money"] * (100-$null_ref_comis)/100);
				$username_del_ref = $row_z["username"];

				mysql_query("UPDATE `tb_null_referer` SET `status`='1' WHERE `id`='$id_z' AND `status`='0' AND `referer`='$my_user_name'") or die(mysql_error());

				mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_add' WHERE `username`='$my_user_name'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `referer`='',`referer2`='',`referer3`='' WHERE `username`='$username_del_ref' AND `referer`='$my_user_name'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `referer2`='',`referer3`='' WHERE `referer`='$username_del_ref' AND `referer2`='$my_user_name'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `referer3`='' WHERE `referer2`='$username_del_ref' AND `referer3`='$my_user_name'") or die(mysql_error());

				$sql_r_1 = mysql_query("SELECT count(id) FROM `tb_users` WHERE `referer`='$my_user_name'");
				$col_r_1 = mysql_result($sql_r_1,0,0);

				$sql_r_2 = mysql_query("SELECT count(id) FROM `tb_users` WHERE `referer2`='$my_user_name'");
				$col_r_2 = mysql_result($sql_r_2,0,0);

				$sql_r_3 = mysql_query("SELECT count(id) FROM `tb_users` WHERE `referer3`='$my_user_name'");
				$col_r_3 = mysql_result($sql_r_3,0,0);

				mysql_query("UPDATE `tb_users` SET `referals`='$col_r_1',`referals2`='$col_r_2',`referals3`='$col_r_3' WHERE `username`='$my_user_name'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
				VALUES('$username_del_ref','Система','Выкуп','Ваша заявка на выкуп вас у реферера $my_user_name одобрена','0','".time()."','$my_ip')") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$my_user_name','".DATE("d.m.Yг. H:i")."','".time()."','$money_add','Зачисление средств за выкуп по заявке №$id_z от вашего реферала $username_del_ref','Зачислено','rashod')") or die(mysql_error());

				exit("OK");
			}else{
				exit("Ошибка! У вас нет заявки с ID: $id_z");
			}

		}elseif($option=="bad_applic") {
			$sql_check = mysql_query("SELECT * FROM `tb_null_referer` WHERE `id`='$id_z' AND `status`='0' AND `referer`='$my_user_name'");
			if(mysql_num_rows($sql_check)>0) {
				$row_z = mysql_fetch_array($sql_check);
				$money_back = $row_z["money"];

				mysql_query("DELETE FROM `tb_null_referer` WHERE `id`='$id_z' AND `status`='0' AND `referer`='$my_user_name'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_back' WHERE `username`='".$row_z["username"]."'") or die(mysql_error());

				$text_applic = "Ваш реферер <b>$my_user_name</b> отклонил вашу заявку №$id_z на выкуп <br>";
				if($comment!=false) $text_applic.= "Причина: $comment";

				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
				VALUES('".$row_z["username"]."','Система','Выкуп','$text_applic','0','".time()."','$my_ip')") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
				VALUES('".$row_z["username"]."','".DATE("d.m.Yг. H:i")."','".time()."','$money_back','Возврат средств по отклоненной заявке №$id_z на выкуп у реферера $my_user_name','Зачислено','rashod')") or die(mysql_error());

				exit("OK");
			}else{
				exit("Ошибка! У вас нет заявки с ID: $id_z");
			}

		}else{
			exit("Не удалось обработать запрос!");
		}
	}else{
		exit("Необходимо авторизоваться!");
	}
}else{
	exit("Не удалось обработать запрос!");
}

?>