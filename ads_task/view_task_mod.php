<script language="JavaScript">

function saveform(){
		arrayElem = document.forms["formtask"];
		var col=0;
		for (var i=0;i<arrayElem.length;i++){
			if (arrayElem[i].getAttribute("TYPE")=="radio" && arrayElem[i].checked == true && arrayElem[i].getAttribute("taskfalse")==1){
				if (arrayElem[i+1].value.length < 5){
					alert("Вы не указали причину, почему задание не выполнено!");
					arrayElem[i+1].style.backgroundColor = "#ff9999";
					arrayElem[i+1].focus();
					return false;
				}
			}
			if (arrayElem[i].getAttribute("TYPE")=="radio" && arrayElem[i].checked == true && arrayElem[i].getAttribute("taskdorab")==1){
				if (arrayElem[i+4].value.length < 5){
					alert("Вы не указали причину, почему задание отправлено на доработку!");
					arrayElem[i+4].style.backgroundColor = "#ff9999";
					arrayElem[i+4].focus();
					return false;
				}
			}
		}
	return true;
}
function setallyes(){
		arrayElem = document.forms["formtask"];
		var col=0;
		for (var i=0;i<arrayElem.length;i++){
			if (arrayElem[i].getAttribute("TYPE")=="radio" && arrayElem[i].getAttribute("taskyes")==1){
				arrayElem[i].checked = true;
			}
		}
	return false;
}
	
</script>


<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

echo '<center><table><tr><td><span class="proc-btn" onClick="document.location.href=\'/ads_task.php?page=task&option=task_view\'" style="text-align:center;">Управление заданиями</a></td><td><span class="proc-btn" onClick="document.location.href=\'/ads_task.php?page=task&option=add_task\'" style="text-align:center;">Создать новое задание</a></td></table></center><br>';

$date_s = DATE("d.m.Y");
$date_v = DATE("d.m.Y", (time()-24*60*60));

include("geoip/geoipcity.inc");
include("geoip/geoipregionvars.php");
$gi = geoip_open("geoip/GeoLiteCity.dat",GEOIP_STANDARD);


function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("'"," ",$mensaje);
	$mensaje = str_replace(";"," ",$mensaje);
	$mensaje = str_replace("$","$",$mensaje);
	$mensaje = str_replace("<","&#60;",$mensaje);
	$mensaje = str_replace(">","&#62;",$mensaje);
	$mensaje = str_replace("\\","",$mensaje);
	$mensaje = str_replace("&amp amp ","&amp;",$mensaje);
	$mensaje = str_replace("&amp quot ","&quot;",$mensaje);
	$mensaje = str_replace("&amp gt ","&gt;",$mensaje);
	$mensaje = str_replace("&amp lt ","&lt;",$mensaje);
	$mensaje = str_replace("\r\n","<br>",$mensaje);
	return $mensaje;
}

if(count($_POST) > 0 && isset($_POST["checktask"])) {
	$checktask = (isset($_POST["checktask"])) ? array_map('mysql_real_escape_string', array_map('intval', $_POST["checktask"])) : false;
	$checkmess = (isset($_POST["checkmess"])) ? array_map('mysql_real_escape_string', array_map('limpiarez', $_POST["checkmess"])) : false;
	$banuser = (isset($_POST["banuser"])) ? array_map('mysql_real_escape_string', array_map('intval', $_POST["banuser"])) : false;


	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
	if(mysql_num_rows($sql)>0) {

		$row = mysql_fetch_array($sql);
		$zdre = $row["zdre"];
		$rek_name = $row["username"];
		$zdprice = $row["zdprice"];
		$zdtype = $row["zdtype"];
		$country_targ = $row["country_targ"];

		foreach ($checktask as $k => $d) {
			$checktask[] = array();
			$checkmess[] = array();
			$banuser[] = array();
			$id_pay = $k;
			$status_t = $checktask[$k];

			$sql_w = mysql_query("SELECT `user_name` FROM `tb_ads_task_pay` WHERE `id`='$id_pay' AND `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'");
			if(mysql_num_rows($sql_w)>0) {
				$row_w = mysql_fetch_array($sql_w);
				$user_name = $row_w["user_name"];

				if($status_t==1) {
					$status = "good"; $text_bad=""; $ban_user="0";
					
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
					$reit_task = mysql_result($sql,0,0);

					mysql_query("UPDATE `tb_ads_task` SET `goods`=`goods`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_task', `visits_t`=`visits_t`+1, `money`=`money`+'$zdprice', `money_t`=`money_t`+'$zdprice' WHERE `username`='$user_name'") or die(mysql_error());
					
					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###
					$cnt_unique_rep = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `user_name`='$user_name' AND `ident`='$rid'"));
					if($cnt_unique_rep==0) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`+'1' WHERE `username`='$user_name'") or die(mysql_error());
					#### РЕПУТАЦИЯ ИСПОЛНИТЕЛЯ ###

					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
					VALUES('0', 'good', '$rid', '$username', '$user_name', '".time()."', '')") or die(mysql_error());
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					
					## START СБОР СТАТИСТИКИ ####
					stats_users($user_name, strtolower(DATE("D")), 'task');
					### END СБОР СТАТИСТИКИ ######
					
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###
					$cnt_konk = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='good' AND `type`='task' AND `rek_name`='$rek_name' AND `user_name`='$user_name' AND `date_end`>='".(time()-24*60*60)."'"));
					#### КОЛИЧЕСТВО ВЫПОЛНЕНИЙ ОДНОГО РЕКЛАМОДАТЕЛЯ###


					$sql_r = mysql_query("SELECT `referer`,`referer2`,`referer3`,`ban_date` FROM `tb_users` WHERE `username`='$user_name'");
					$row_r = mysql_fetch_assoc($sql_r);

					$my_referer_1 = $row_r["referer"];
					$my_referer_2 = $row_r["referer2"];
					$my_referer_3 = $row_r["referer3"];
					//$my_referer_4 = $row_r["referer4"];
					//$my_referer_5 = $row_r["referer5"];
					$user_ban_date = $row_r["ban_date"];

					$add_money_r_1 = 0;
					$add_money_r_2 = 0;
					$add_money_r_3 = 0;
					//$add_money_r_4 = 0;
					//$add_money_r_5 = 0;

					/*#### ИНВЕСТ ПРОЕКТ ###
					stat_pay("task_pay", $zdprice);
					invest_stat($zdprice, 2);
					#### ИНВЕСТ ПРОЕКТ ###*/

					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
					konkurs_ads_new($wmiduser, $username, $zdprice);
					#### АДМИН-КОНКУРС РЕКЛАМОДАТЕЛЕЙ ###
					
					#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
					konkurs_ads_task_new($wmiduser, $username, $zdprice);
					#### КОНКУРС ПО ОПЛАТЕ ЗАДАНИЙ ###
					
					#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
					$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='status'");
					$konk_task_status = mysql_result($sql,0,0);

					if($konk_task_status==1 && $cnt_konk<=3) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_start'");
						$konk_task_date_start = mysql_result($sql,0,0);

						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='task' AND `item`='date_end'");
						$konk_task_date_end = mysql_result($sql,0,0);

						if($konk_task_date_end>=time() && $konk_task_date_start<time()) {
							mysql_query("UPDATE `tb_users` SET `konkurs_task`=`konkurs_task`+'1' WHERE `username`='$user_name' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)")or die(mysql_error());
						}
					}
					#### NEW КОНКУРС ПО ВЫПОЛНЕНИЮ ЗАДАНИЙ NEW ###
					
					### КОМПЛЕКСНЫЙ NEW ###
					if($country_targ == 0 && strtolower($rek_name) != strtolower($user_name) && $user_ban_date == 0) {
						$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='status'") or die(mysql_error());
						$konk_complex_status = mysql_result($sql,0,0);

						if($konk_complex_status==1 && $cnt_konk<=3) {
							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_start'") or die(mysql_error());
							$konk_complex_date_start = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='date_end'") or die(mysql_error());
							$konk_complex_date_end = mysql_result($sql,0,0);

							$sql = mysql_query("SELECT `price` FROM `tb_konkurs_conf` WHERE `type`='complex' AND `item`='point_".($zdre==0 ? "task" : "task_re")."'") or die(mysql_error());
							$konk_complex_point = mysql_result($sql,0,0);

							if($konk_complex_date_end>=time() && $konk_complex_date_start<=time()) {
								mysql_query("UPDATE `tb_users` SET `konkurs_complex`=`konkurs_complex`+'$konk_complex_point' WHERE `username`='$user_name' AND `username` NOT IN (SELECT `user_name` FROM `tb_konkurs_exp`)")or die(mysql_error());
							}
						}
					}
					### КОМПЛЕКСНЫЙ NEW ###
					

								if($my_referer_1!=false) {
					$sql_r_1 = mysql_query("SELECT `id`,`money_rb`,`reiting` FROM `tb_users` WHERE `username`='$my_referer_1'");
					$row_r_1 = mysql_fetch_array($sql_r_1);
					$user_id_ref_1 = $row_r_1["id"];
					$money_rb_ref_1 = $row_r_1["money_rb"];
					$reiting_1 = $row_r_1["reiting"];

						$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_1' AND `r_do`>='".floor($reiting_1)."'");
						if(mysql_num_rows($sql_rang)>0) {
							$row_rang = mysql_fetch_array($sql_rang);
						}else{
							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
							$row_rang = mysql_fetch_array($sql_rang);
						}
						$ref_proc_task_1 = $row_rang["t_1"];

						$add_money_r_1 = p_floor( ($zdprice * $ref_proc_task_1 / 100), 6);

						mysql_query("UPDATE `tb_users` SET `refmoney`=`refmoney`+'$add_money_r_1', `money`=`money`+'$add_money_r_1' WHERE `username`='$my_referer_1'") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_1' WHERE `username`='$user_name'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_1' AND `type`='ref1'");							
	               if(mysql_num_rows($sql_stat_re)>0) {
		               mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_1', `sum_week`=`sum_week`+'$add_money_r_1', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_1' WHERE `username`='$my_referer_1' AND `type`='ref1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				    }else{
					   mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_1','ref1','$add_money_r_1','$add_money_r_1','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				 }

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###

						### РЕФ КОНКУРС I ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='4' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_array($sql_t);

								if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
								}
							}
						}
						### РЕФ КОНКУРС I ур. ###
						
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###
					if($country_targ=="0") {
						$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_kon`='6' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_t)>0) {
							$row_t = mysql_fetch_assoc($sql_t);

							//if($row_t["limit_kon_sum"]!=0 && ($my_visits+$my_visits_bs+$add_money_r_1)==$row_t["limit_kon_sum"]) {
							$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_kon) > 0) {
								mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_1' WHERE `username`='$user_name' AND `referer`='1' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}else{
								mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
								VALUES('$user_name','1','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_1')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}
					}
				//}
					### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ I ур. ###

						if($my_referer_2!="") {
							$sql_r_2 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
							$row_r_2 = mysql_fetch_array($sql_r_2);
							$reiting_2 = $row_r_2["reiting"];

							$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_2' AND `r_do`>='".floor($reiting_2)."'");
							if(mysql_num_rows($sql_rang)>0) {
								$row_rang = mysql_fetch_array($sql_rang);
							}else{
								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
								$row_rang = mysql_fetch_array($sql_rang);
							}
							$ref_proc_task_2 = $row_rang["t_2"];

							$add_money_r_2 = p_floor( ($zdprice * $ref_proc_task_2 / 100), 6);

							mysql_query("UPDATE `tb_users` SET `ref2money`=`ref2money`+'$add_money_r_2', `money`=`money`+'$add_money_r_2' WHERE `username`='$my_referer_2'") or die(mysql_error());
							mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_2' WHERE `username`='$my_referer_1'") or die(mysql_error());
							$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_2' AND `type`='ref2'");
							
	                       if(mysql_num_rows($sql_stat_re)>0) {
	                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_2', `sum_week`=`sum_week`+'$add_money_r_2', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_2' WHERE `username`='$my_referer_2' AND `type`='ref2'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }else{
					           mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_2','ref2','$add_money_r_2','$add_money_r_2','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				         }

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###

							### РЕФ КОНКУРС II ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_array($sql_t);

									if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
									}
								}
							}
							### РЕФ КОНКУРС II ур. ###
							
							### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###
						if($country_targ=="0") {
							$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_2' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							if(mysql_num_rows($sql_t)>0) {
								$row_t = mysql_fetch_assoc($sql_t);

								//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_2)==$row_t["limit_kon_sum"]) {
								$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_kon) > 0) {
									mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_2' WHERE `username`='$user_name' AND `referer`='2' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}else{
									mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
									VALUES('$user_name','2','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_2')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}
						}
					//}
						### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ II ур. ###

							if($my_referer_3!="") {
								$sql_r_3 = mysql_query("SELECT `reiting` FROM `tb_users` WHERE `username`='$my_referer_2'");
								$row_r_3 = mysql_fetch_array($sql_r_3);
								$reiting_3 = $row_r_3["reiting"];

								$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reiting_3' AND `r_do`>='$reiting_3'");
								if(mysql_num_rows($sql_rang)>0) {
									$row_rang = mysql_fetch_array($sql_rang);
								}else{
									$sql_rang = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
									$row_rang = mysql_fetch_array($sql_rang);
								}
								$ref_proc_task_3 = $row_rang["t_3"];

								$add_money_r_3 = p_floor( ($zdprice * $ref_proc_task_3 / 100), 6);

								mysql_query("UPDATE `tb_users` SET `ref3money`=`ref3money`+'$add_money_r_3', `money`=`money`+'$add_money_r_3' WHERE `username`='$my_referer_3'") or die(mysql_error());
								mysql_query("UPDATE `tb_users` SET `dohod_r_s`=`dohod_r_s`+'$add_money_r_1', `dohod_r_all`=`dohod_r_all`+'$add_money_r_3' WHERE `username`='$my_referer_2'") or die(mysql_error());
								$sql_stat_re = mysql_query("SELECT * FROM `tb_ref_stat` WHERE `username`='$my_referer_3' AND `type`='ref3'");
	                           if(mysql_num_rows($sql_stat_re)>0) {
		                           mysql_query("UPDATE `tb_ref_stat` SET `sum_month`=`sum_month`+'$add_money_r_3', `sum_week`=`sum_week`+'$add_money_r_3', `".strtolower(DATE("D"))."`=`".strtolower(DATE("D"))."`+'$add_money_r_3' WHERE `username`='$my_referer_3' AND `type`='ref3'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				                }else{
					               mysql_query("INSERT INTO `tb_ref_stat` (`username`,`type`,`sum_month`,`sum_week`,`".strtolower(DATE("D"))."`) VALUES('$my_referer_3','ref3','$add_money_r_3','$add_money_r_3','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				            }

								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='1' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'1' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','1')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###

								### РЕФ КОНКУРС III ур. ###
								if($country_targ=="0") {
									$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='4' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1");
									if(mysql_num_rows($sql_t)>0) {
										$row_t = mysql_fetch_array($sql_t);

										if($zdre==0) {$add_ball_kon=5; }else{ $add_ball_kon=3; }

										$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'");
										if(mysql_num_rows($sql_kon) > 0) {
											mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_ball_kon' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(mysql_error());
										}else{
											mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
											VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_ball_kon')") or die(mysql_error());
										}
									}
								}
								### РЕФ КОНКУРС III ур. ###
								### РЕФ-КОНКУРС ЗАРАБОТКА РЕФЕРЕРУ III ур. ###
							if($country_targ=="0") {
								$sql_t = mysql_query("SELECT * FROM `tb_refkonkurs` WHERE `status`='1' AND `username`='$my_referer_3' AND `type_kon`='6' AND `glubina`='1' AND `date_s`<'".time()."' AND `date_e`>'".time()."' ORDER BY `id` DESC LIMIT 1") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								if(mysql_num_rows($sql_t)>0) {
									$row_t = mysql_fetch_assoc($sql_t);

									//if($row_t["limit_kon_sum"]!=0 && ($add_money_r_3)==$row_t["limit_kon_sum"]) {
									$sql_kon = mysql_query("SELECT `id` FROM `tb_refkonkurs_stat` WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									if(mysql_num_rows($sql_kon) > 0) {
										mysql_query("UPDATE `tb_refkonkurs_stat` SET `kolvo`=`kolvo`+'$add_money_r_3' WHERE `username`='$user_name' AND `referer`='3' AND `ident`='".$row_t["id"]."' AND `type`='".$row_t["type_kon"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}else{
										mysql_query("INSERT INTO `tb_refkonkurs_stat` (`username`,`referer`,`ident`,`type`,`kolvo`) 
										VALUES('$user_name','3','".$row_t["id"]."','".$row_t["type_kon"]."','$add_money_r_3')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									}
								}
							}
							//}
							
							
					}
				}
			}
								
								### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
					if($my_referer_1 != false) {
						$sql_r_1 = mysql_query("SELECT `id`,`money_rb` FROM `tb_users` WHERE `username`='$my_referer_1'");
						if(mysql_num_rows($sql_r_1)>0) {
							$row_r_1 = mysql_fetch_assoc($sql_r_1);
							$user_id_ref_1 = $row_r_1["id"];
							$money_ref_1 = $row_r_1["money_rb"];

							$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='4' ORDER BY `id` DESC LIMIT 1");
							if(mysql_num_rows($sql_r_b_1)>0) {
								$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

								$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(mysql_error());
								$comis_sys_bon = mysql_result($sql_b,0,0);

								$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='4' ORDER BY `id` DESC LIMIT 1");
								if(mysql_num_rows($sql_r_b_stat_1)>0) {
									$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

									if(($row_r_b_stat_1["stat_info"]+1)==$row_r_b_1["count_nado"]) {
										$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
										$money_ureferera_nado = round($money_ureferera_nado, 2);

										if($money_ref_1>=$money_ureferera_nado) {
											mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(mysql_error());
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());

											mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(mysql_error());
											mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$user_name','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." выполненных заданий','Зачислено','rashod')") or die(mysql_error());

											mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
											VALUES('1','$my_referer_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $user_name за ".$row_r_b_1["count_nado"]." выполненных заданий','Списано','rashod')") or die(mysql_error());

											if(trim($row_r_b_1["description"])!=false) {
												mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
												VALUES('$user_name','Система','Реф-Бонус от реферера $my_referer_1 за ".$row_r_b_1["count_nado"]." кликов в серфинге','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
											}
										}else{
											mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
										}
									}else{
										mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(mysql_error());
									}
								}else{
									mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
									VALUES('-1','$user_name','1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(mysql_error());
								}
							}
						//}
					//}
					### РЕФБОНУСЫ ЗА ЗАДАНИЯ NEW ###
					
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
					$sql_r_b_1 = mysql_query("SELECT * FROM `tb_refbonus` WHERE `status`='1' AND `username`='$my_referer_1' AND `type_bon`='6' ORDER BY `id` DESC LIMIT 1");
					if(mysql_num_rows($sql_r_b_1)>0) {
						$row_r_b_1 = mysql_fetch_assoc($sql_r_b_1);

						$sql_b = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='comis_sys_bon' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						$comis_sys_bon = mysql_result($sql_b,0,0);

						$sql_r_b_stat_1 = mysql_query("SELECT * FROM `tb_refbonus_stat` WHERE `status`='-1' AND `username`='$user_name' AND `ident`='".$row_r_b_1["id"]."' AND `type`='6' ORDER BY `id` DESC LIMIT 1");
						if(mysql_num_rows($sql_r_b_stat_1)>0) {
							$row_r_b_stat_1 = mysql_fetch_assoc($sql_r_b_stat_1);

							if((number_format($row_r_b_stat_1["stat_info"],2,".","`"))==$row_r_b_1["count_nado"]) {
								$money_ureferera_nado = ($row_r_b_1["bonus"] * ($comis_sys_bon+100)/100);
								$money_ureferera_nado = round($money_ureferera_nado, 2);

								if($money_ref_1>=$money_ureferera_nado) {
									mysql_query("UPDATE `tb_refbonus` SET `count_bon`=`count_bon`+'1' WHERE `id`='".$row_r_b_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("UPDATE `tb_users` SET `money`=`money`+'".$row_r_b_1["bonus"]."' WHERE `username`='$user_name'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
									mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_ureferera_nado' WHERE `username`='$my_referer_1'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$user_name','$user_id','".DATE("d.m.Y H:i")."','".time()."','".$row_r_b_1["bonus"]."','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Зачислено','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									mysql_query("INSERT INTO `tb_history` (`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
									VALUES('$my_referer_1','$user_id_ref_1','".DATE("d.m.Y H:i")."','".time()."','$money_ureferera_nado','Реф-Бонус рефералу $user_name за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','Списано','rashod')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

									if(trim($row_r_b_1["description"])!=false) {
										mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) 
										VALUES('$user_name','Система','Реф-Бонус от реферера $my_referer_1 за заработок ".number_format($row_r_b_1["count_nado"],2,".","`")." руб.','".$row_r_b_1["description"]."','0','".time()."','0.0.0.0')");
									}
								}else{
									mysql_query("UPDATE `tb_refbonus_stat` SET `status`='0', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
								}
							}else{
								mysql_query("UPDATE `tb_refbonus_stat` SET `status`='-1', `stat_info`=`stat_info`+'$add_money_r_1', `date`='".time()."', `money`='".$row_r_b_1["bonus"]."' WHERE `id`='".$row_r_b_stat_1["id"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
							}
						}else{
							mysql_query("INSERT INTO `tb_refbonus_stat` (`status`,`username`,`stat_info`,`ident`,`type`,`date`,`money`) 
							VALUES('-1','$user_name','$add_money_r_1','".$row_r_b_1["id"]."','".$row_r_b_1["type_bon"]."','".time()."','".$row_r_b_1["bonus"]."')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
						}
					}
					### РЕФ-БОНУСЫ ЗА ЗАРАБОТОК РЕФЕРЕРУ NEW ###
				}
			}
/*
					$sql_rek = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$username'");
					$row_rek = mysql_fetch_row($sql_rek);

					$referer_rek = $row_rek["0"];

					if($referer_rek!="") {
						$sql_cr = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_proc_rek' AND `howmany`='1'");
						$ref_proc_rek = mysql_result($sql_cr,0,0);

						$add_balans_rek = round(($zdprice * $ref_proc_rek / 100), 6);

						mysql_query("UPDATE `tb_users` SET `money`=`money`+'$add_balans_rek', `ref_money_rek`=`ref_money_rek`+'$add_balans_rek' WHERE `username`='$referer_rek'") or die(mysql_error());
					}

///уведомления///
//mysql_query("INSERT INTO `tb_ads_task_mess` (`username`,`id_task`,`status`,`user_task`) VALUES('$user_name','$rid','1','$username')") or die(mysql_error());
					
///	
*/

				}elseif($status_t==2) {
					$status = "dorab"; $text_bad="$checkmess[$k]"; $ban_user="0";

					mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
					
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
					VALUES('0', 'dorab', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					$time_dorab=time();
				mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_pay' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());

				}else{

					$status = "bad"; $text_bad="$checkmess[$k]";
					if(isset($banuser[$k])) {
						if($banuser[$k]==1) {$ban_user="1";}else{$ban_user="0";}
					}else{
						$ban_user="0";
					}

					mysql_query("UPDATE `tb_ads_task` SET `totals`=`totals`+'1', `bads`=`bads`+'1', `wait`=`wait`-'1' WHERE `id`='$rid' AND `username`='$username' AND (`totals`>'0' OR `wait`>'0')") or die(mysql_error());
				
				    #### РЕПУТАЦИЯ ###
					if($status_t==3) mysql_query("UPDATE `tb_users` SET `rep_task`=`rep_task`-'1' WHERE `username`='$user_name'") or die(mysql_error());
					#### РЕПУТАЦИЯ ###
					
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
					mysql_query("INSERT INTO `tb_ads_task_notif` (`status`, `type`, `ident`, `rek_name`, `user_name`, `time`, `message`) 
					VALUES('0', 'bad', '$rid', '$username', '$user_name', '".time()."', '$text_bad')") or die(mysql_error());
					### УВЕДОМЛЕНИЕ ИСПОЛНИТЕЛЮ ####
				}

				$time_dorab=time();
				mysql_query("UPDATE `tb_ads_task_pay` SET `date_dorab`='$time_dorab', `status`='$status', `why`='$text_bad', `ban_user`='$ban_user', `pay`='$zdprice' WHERE `id`='$id_pay' AND `rek_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
			}
		}
	}else{
		echo '<span class="msg-error">Ошибка! Необходимо пополнить баланс задания!</span>';
	}
}


function universal_link_bar($page, $count, $pages_count, $show_link) {
	if ($pages_count == 1) return false;
		$sperator = ' &nbsp;';
		$style = 'style="font-weight: bold;"';
		$begin = $page - intval($show_link / 2);
		unset($show_dots);

		if ($pages_count <= $show_link + 1) $show_dots = 'no';
		if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
			echo '<a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s=1> 1 </a> ';
		}

		for ($j = 0; $j < $page; $j++) {
			if (($begin + $show_link - $j > $pages_count) && ($pages_count-$show_link + $j > 0)) {
				$page_link = $pages_count - $show_link + $j;

				if (!isset($show_dots) && ($pages_count-$show_link > 1)) {
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($page_link - 1).'><b>...</b></a> ';
					$show_dots = "no";
				}

				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$page_link.'>'.$page_link.'</a> '.$sperator;
			} else continue;
		}

		for ($j = 0; $j <= $show_link; $j++) {
			$i = $begin + $j;

			if ($i < 1) { $show_link++; continue;}

			if (!isset($show_dots) && $begin > 1) {
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($i-1).'><b>...</b></a> ';
				$show_dots = "no";
			}

			if ($i > $pages_count) break;
			if ($i == $page) {
				echo ' <a '.$style.' ><b style="color:#FF0000; text-decoration:underline;">'.$i.'</b></a> ';
			}else{
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$i.'>'.$i.'</a> ';
			}

			if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
			if (($j == $show_link) && ($i < $pages_count)) {
				echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.($i+1).'><b>...</b></a> ';
		}
	}

	if ($begin + $show_link + 1 < $pages_count) {
		echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiar($_GET["page"]).'&option='.limpiar($_GET["option"]).'&rid='.intval($_GET["rid"]).'&s='.$pages_count.'> '.$pages_count.' </a>';
	}
	return true;
}


$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$perpage = 10;
	if (empty($_GET["s"]) || ($_GET["s"] <= 0)) {
		$page = 1;
	}else{
		$page = intval($_GET["s"]);
	}

	$count = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid'"));
	$pages_count = ceil($count / $perpage);

	if ($page > $pages_count) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos=0;


	$sql_w = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' AND `type`='task' AND `rek_name`='$username' AND `ident`='$rid' ORDER BY `id` ASC LIMIT $start_pos,$perpage");
	$wait_all = mysql_num_rows($sql_w);

	echo '<table class="tables" style="table-layout: fixed; width: 100%">';
		echo '<thead><tr><th align="center" colspan="2" class="top"><b>Форма проверки выполнений заданий №'.$rid.'</b></th></tr></thead>';
		echo '<tr>';
			echo '<td width="200" align="left" height="30px"><b>Заголовок задания:</b></td>';
			echo '<td>&nbsp;'.str_replace("\r\n","<br>", $row["zdname"]).'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="left" height="30px"><b>URL:</b></td>';
			echo '<td><a href="'.$row["zdurl"].'" target="_blank">'.$row["zdurl"].'</a></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="200" align="left" height="30px"><b>Рейтинг:</b></td>';
			echo '<td width="200">&nbsp;'.round($row["reiting"], 2).' (проголосовало '.$row["all_coments"].')</td>';
		echo '</tr>';
        echo '<tr>';
            echo '<td width="200" align="left" height="30px"><b>Статистика выполнений:</b></td>';		
			echo '<td style="border-left: 1px solid #cccccc; padding-left:20px;"><span style="color:green;">'.$row["goods"].'</span> - <span style="color:red;">'.$row["bads"].'</span> - <span style="color: #1E90FF;">'.$row["wait"].'</span></td>';
		echo '</tr>';
		echo '<tr><th align="center" colspan="2" class="top">Описание задания</th></tr>';
		echo '<tr>';
			echo '<td colspan="2">'.$row["zdtext"].'</td>';
		echo '</tr>';
		echo '<tr><th align="center" colspan="2" class="top">Что должен указать исполнитель для проверки выполнения задания:</th></tr>';
		echo '<tr>';
			echo '<td colspan="2">'.$row["zdtext2"].'</td>';
		echo '</tr>';
		echo '<tr><th align="center" colspan="2" class="top">Заявки на выполнение:</th></tr>';
		echo '<tr>';
			//echo '<td colspan="2" align="left">Заявок всего: <b>'.$count.'</b>, показано <b>'.$wait_all.'</b></td>';
			echo '<td colspan="2" align="left">Заявок всего: <b>'.$count.'</b>, показано <b>'.$wait_all.'</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" OnClick="setallyes();"><img src="img/yes.png" title="отметить все положительные" alt="" width="16" height="16" align="absmiddle" border="0" /></a> <a href="javascript:void(0);" OnClick="setallyes();" style="color:green; font-size: 12px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;">отметить все положительные</a></td>';
		echo '</tr>';
        echo '</table>';

	if($wait_all>0) {
		echo '<form action="" method="POST" name="formtask" id="formtask" onSubmit="return saveform();">';
		echo '<table class="tables" style="table-layout: fixed; width: 100%">';

		while($row_w = mysql_fetch_assoc($sql_w)) {
			$record = geoip_record_by_addr($gi, $row_w["ip"]);
			if($record==false) {
				$country_code="";
			}else{
				$country_code = $record->country_code;
			}

			echo '<tr>';
				$sql_u = mysql_query("SELECT `id`,`username`,`avatar` FROM `tb_users` WHERE `username`='".$row_w["user_name"]."'");
				if(mysql_num_rows($sql_u)>0) {
					$row_u = mysql_fetch_array($sql_u);

					echo '<td align="center" width="100"><a href="/wall.php?uid='.$row_u["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row_u["avatar"].'" style="width:80px; height:80px" border="0" title="Перейти на стену пользователя '.$row_u["username"].'" /></a></td>';
					//echo '<td align="center" width="100"><img src="../avatar/'.$row_u["avatar"].'" border="0" alt="" align="middle" class="avatar"/>';
					echo '<td align="left" width="100%">Пользователь: #'.$row_u["id"].' <b>'.$row_u["username"].'</b><br>Начало выполнения задания: '.DATE("d.m.Yг. H:i", $row_w["date_start"]).'<br>Окончание выполнения задания:'.DATE("d.m.Yг. H:i", $row_w["date_end"]).'<br>IP-адрес: '.$row_w["ip"].' ['.$country_code.'] <img src="img/flags/'.strtolower($country_code).'.gif" width="16" height="12" border="0"  align="middle" alt="" /></td>';
				}else{
					echo '<td align="center" width="100"><a href="/wall.php?uid='.$row_u["id"].'" target="_blank"><img class="avatar" src="/avatar/'.$row_u["avatar"].'" style="width:80px; height:80px" border="0" title="Перейти на стену пользователя '.$row_u["username"].'" /></a></td>';
					//echo '<td align="center" width="100"><img src="../avatar/no.png" border="0" alt="" align="middle" />';
					echo '<td align="left" width="100%">Пользователь: #'.$row_w["id"].' <b>'.$row_w["username"].'</b> <span style="color:#FF0000;">Пользователь удален</span><br>Начало выполнения задания: '.DATE("d.m.Yг. H:i", $row_w["date_start"]).'<br>Окончание выполнения задания:'.DATE("d.m.Yг. H:i", $row_w["date_end"]).'<br>IP-адрес: '.$row_w["ip"].' ['.$country_code.'] <img src="img/flags/'.strtolower($country_code).'.gif" width="16" height="12" border="0"  align="middle" alt="" /></td>';
				}
			echo '</tr>';

			echo '<tr>';
				//echo '<td colspan="2"><b>Ответ для проверки выполнения:</b><br><div style="text-wrap:normal; text-align:justify; word-wrap: break-word; width:700px;">'.$row_w["ctext"].'</div></td>';
				echo '<td colspan="2"><b>Ответ для проверки выполнения:</b><br><div style="text-wrap:normal; text-align:justify; word-wrap: break-word;">'.$row_w["ctext"].'</div></td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td colspan="2" style="border-bottom: 2px solid #363636;">
					<input type="radio" name="checktask['.$row_w["id"].']" value="1" id="'.$row_w["id"].'" onChange="document.getElementById(\'bad'.$row_w["id"].'\').style.display=\'none\'; document.getElementById(\'dorab'.$row_w["id"].'\').style.display=\'none\'" taskyes="1"> - <img src="img/yes.png" border="0" alt="" align="middle" title="задание выполнено" /> выполнено &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="checktask['.$row_w["id"].']" value="2" id="'.$row_w["id"].'" onChange="document.getElementById(\'dorab'.$row_w["id"].'\').style.display=\'block\'; document.getElementById(\'bad'.$row_w["id"].'\').style.display=\'none\'" taskdorab="1"> - <img src="img/help.png" border="0" alt="" align="middle" title="отправить на доработку" /> на доработку &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="checktask['.$row_w["id"].']" value="3" id="'.$row_w["id"].'" onChange="document.getElementById(\'bad'.$row_w["id"].'\').style.display=\'block\'; document.getElementById(\'dorab'.$row_w["id"].'\').style.display=\'none\'" taskfalse="1"> - <img src="img/no.png" border="0" alt="" align="middle" title="задание не выполнено" /> не выполнено';

					echo '<div id="bad'.$row_w["id"].'" style="display:none;">
						<b>Причина</b>, почему задание не выполнено: 
						<input type="text" name="checkmess['.$row_w["id"].']" value="" size="80" maxlength="100"><br>
						<input type="checkbox" name="banuser['.$row_w["id"].']" id="'.$row_w["id"].'" value="1"> -  жалоба на пользователя
					</div>';

					echo '<div id="dorab'.$row_w["id"].'" style="display:none;">
						<b>Причина</b>, почему задание отправлено на доработку: 
						<input type="text" name="checkmess['.$row_w["id"].']" value="" maxlength="100" style="width:98%;">
					</div>';

				echo '</td>';
			echo '</tr>';
		}
		echo '<tr align="center">';
			echo '<td colspan="2"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;&nbsp;Сохранить&nbsp;&nbsp;&nbsp;"></td>';
		echo '</tr>';
		echo '</table>';
		echo '</form>';

		if($count>$perpage){
			echo '<div align="center" style="padding-top:10px;"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8); echo '</div>';
		}
	}
	geoip_close($gi);
}else{
	echo '<span class="msg-error">Ошибка! У Вас нет такого задания!</span>';
}

?>