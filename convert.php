<?php
$pagetitle = "Операции со счетом";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
	
}else{
	if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
    echo '<div class="cash-bank">Желаете вывести заработанные средства? Нет проблем! Выберите, на какой счет вы хотите получить деньги. Внимательно проверьте реквизиты своих кошельков. Выплаты в системе <span style="color: #ff7f50; font-weight: bold; text-shadow: 0 1px 0 #FFF, 1px 2px 2px #AAA;">'.strtoupper($_SERVER["HTTP_HOST"]).'</span> производятся согласно <a href="/tos.php">Правилам проекта</a>.</div>';
    $username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Zа-яА-Я0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	if(count($_POST)>0 && isset($_POST["id_pay"])) {
		$id_pay = (isset($_POST["id_pay"])) ? intval(($_POST["id_pay"])) : false;

		$sql_id = mysql_query("SELECT `id`,`money` FROM `tb_add_pay` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$money_pay = $row["money"];

			$sql_user = mysql_query("SELECT `wmid`,`wm_purse`,`money` FROM `tb_users` WHERE `username`='$username'");
			$row_user = mysql_fetch_array($sql_user);
			$wmid_user = $row_user["wmid"];
			$wmr_user = $row_user["wm_purse"];
			$money_user = $row_user["money"];

                        

			if($money_user>=$money_pay) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek_bal'");
				$reit_rek_bal = mysql_result($sql,0,0);
				
				$reit_add_1 = floor($money_pay/10) * $reit_rek_bal;
					
				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`+'$money_pay', `money`=`money`-'$money_pay', `money_in`=`money_in`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_add_pay` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) VALUES('$username', '".DATE("d.m.Y H:i")."', '$money_pay', 'Пополнение баланса', 'Зачислено', 'popoln')") or die(mysql_error());

				echo '<span class="msg-ok">Операция прошла успешно. Рекламный счет пополнен на '.$money_pay.' руб.</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">На вашем основном счету недостаточно средств для пополнения баланса рекламного счета!</span>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<span class="msg-error">Счет не найден!</span>';
			include('footer.php');
			exit();
		}
	}

	if(isset($_POST["money_add"])) {
		$money_add = (isset($_POST["money_add"])) ? floatval(str_replace(",",".",trim($_POST["money_add"]))) : false;
		$money_add = number_format($money_add,2,".","");
		$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(trim($_POST["method_pay"])))) ? intval(trim($_POST["method_pay"])) : false;
		$ip = getRealIP();

		if($username == false) {
			echo '<span class="msg-error">Логин пользователя не определен!</span><br>';
		}else{
			if($money_add != false && $money_add >= 1.00) {
	
			if($method_pay==false) {
					echo '<span class="msg-error">Не выбран способ оплаты!</span>';
					include('footer.php');
					exit();
				}

				$system_pay[22] = "Конвертация с основного счета";

				require('config.php');
				$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
				$merch_tran_id = mysql_result($sql_tranid,0,0);
				mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());

				mysql_query("DELETE FROM `tb_add_pay` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

				$check = mysql_query("SELECT `id` FROM `tb_add_pay` WHERE `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
				if(mysql_num_rows($check)>0) {
				mysql_query("UPDATE `tb_add_pay` SET `merch_tran_id`='$merch_tran_id', `wmid`='$wmiduser', `date`='".time()."', `ip`='$laip', `money`='$money_add' WHERE `status`='0' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("INSERT INTO `tb_add_pay` (`status`, `merch_tran_id`, `username`, `wmid`, `date`, `ip`, `money`) VALUES('0', '$merch_tran_id', '$username', '$wmiduser', '".time()."', '$laip', '$money_add')") or die(mysql_error());
				}

				$sql_id = mysql_query("SELECT `id` FROM `tb_add_pay` WHERE `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
				$id_zakaz = mysql_result($sql_id,0,0);

				echo '<h2 class="sp" style="margin-top:5px auto 15px auto; color:#0000ff; font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">Подтвердите информацию о переводе</h2>';
				echo '<div id="load-money-add"><span class="msg-ok">Ваш заказ принят и будет выполнен автоматически после оплаты.</span>';
					echo '<div class="cash-moneyadd"><div><span style="color:#005E91">Счет №:</span>'.$merch_tran_id.'</div>';
					echo '<div><span style="color:#005E91">Назначение:</span>Пополнение рекламного счета аккаунта '.$username.' (#id '.$partnerid.')</div>';
					echo '<div><span style="color:#005E91">Способ оплаты:</span>'.$system_pay[$method_pay].'</div>';
				
						echo '<div><span style="color:#005E91">Сумма к оплате:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> руб.</div>';
				
				echo '</div>';
				echo '</div>';

				$shp_item = "1";
				$inv_desc = "Пополнение баланса аккаунта $username (ID:$partnerid)";
				$inv_desc_utf8 = iconv("CP1251", "UTF-8", "Пополнение баланса аккаунта $username (ID:$partnerid)");
				$inv_desc_en = "Popolnenie balansa: account $username (ID:$partnerid)";
				$money_add = number_format($money_add,2,".","");

				require_once("".$_SERVER['DOCUMENT_ROOT']."/method_pay/method_pay.php");

			}else{
				echo '<span class="msg-error">Минимальная сумма пополнения 1.00 руб.!</span><br>';
			}
		}

	}else{

	$_ACT_CASH = array();
	$_ARR_EPS = array("wm", "ym", "pe", "qw", "me", "vs", "ms", "be", "mg", "mt", "tl");
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlentities(stripslashes(trim($_SESSION["userLog"]))) : false;

	
	//$sql_h1 = mysql_query("SELECT `time` FROM `tb_history_pay` WHERE `time`>='".(time()-24*60*60)."' AND `user`='$username' ORDER BY `id` DESC LIMIT 1");
	$sql_h1 = mysql_query("SELECT `time` FROM `tb_history` WHERE `status_pay`='1' AND `time`>='".(time()-24*60*60)."' AND `user`='$username' AND `status`='' AND `tipo`='0' ORDER BY `id` DESC LIMIT 1");
	//$sql_h2 = mysql_query("SELECT `time` FROM `tb_history` WHERE `status_pay`='0' AND `user`='$username' AND `status`='' AND `tipo`='0' ORDER BY `id` DESC LIMIT 1");

	if(mysql_num_rows($sql_h1)>0) {
		$row_h1 = mysql_fetch_assoc($sql_h1);
		echo '<span class="msg-w">Вывод денежных средств со счета нашего проекта можно осуществлять один раз в сутки.<br>Последний раз вы выводили средства '.DATE("d.m.Y в H:i", $row_h1["time"]).'<br>Следующий вывод средств возможно заказать не ранее '.DATE("d.m.Y в H:i", ($row_h1["time"]+24*60*60)).'</span>';
		include("footer.php");
		exit();                   

	}
	/*elseif(mysql_num_rows($sql_h2)>0) {
		echo '<span class="msg-w">Ваш предыдущий заказ на вывод средств еще не обработан!</span>';
		include("footer.php");
		exit();                   
	}*/
	?>

<!--<script type="text/javascript">
document.onselectstart = noselect; //не дает выделять
document.ondragstart = noselect; //не дает перетаскивать
document.oncontextmenu = noselect; //блокирует контекстное меню
function noselect() {return false;}
</script>-->

	<script type="text/javascript" language="JavaScript">
		var tm_c;
		
		function HideMsg(id, timer) {
		        clearTimeout(tm_c); tm_c = setTimeout(function() {$("#"+id).html("").slideToggle(700);}, timer); 
			return false;
		}

		function CashOut(op, eps) {
			var sum_amount = $.trim($("#sum_amount").val());
			var cnt_amount = $.trim($("#cnt_amount").val());

			$.ajax({
				type: "POST", url: "ajax/ajax_cash_out.php?rnd="+Math.random(), data: {'op':op, 'eps':eps, 'sum_amount':sum_amount, 'cnt_amount':cnt_amount}, 
				dataType: 'json',
				error: function(request, status, errortext) {
					$("#loading").hide();
					$("#info-msg-cash").html("").hide();
					var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
					$("#info-msg-cash").html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>').slideToggle();
					//console.log(request, status, errortext);
				},
				beforeSend: function() {
					$("#loading").show();
					if(op == "convert") $("#info-msg-cash").html('<span class="cashout-loading"></span>');
				}, 
				success: function(data) {
					$("#loading").hide();
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					$("#info-msg-cash").html("").hide();

					if (result == "OK") {
						$("#CashAct").html(message);
						return false;
					} else if (result == "PROFILE") {
						document.location.href = "/profile.php";
						return false;
					} else if (result == "ERROR35") {
						$("#CashAct").html(message);
						return false;
					} else {
						$("#info-msg-cash").html(message).slideToggle("fast");
						HideMsg("info-msg-cash", 5000);
						return false;
					}
				}
			});
			return false;
		}

		function str_replace(search, replace, subject) {
			return subject.split(search).join(replace);
		}

		function SumChange(){
			var sum_amount = $.trim($("#sum_amount").val());
			sum_amount = str_replace(",", ".", sum_amount);
			sum_amount = sum_amount.match(/(\d+(\.)?(\d){0,2})?/);
			sum_amount = sum_amount[0] ? sum_amount[0] : '';
			$("#sum_amount").val(sum_amount);
			return false;
		}

		$(document).ready(function(){
			SumChange();
		})
	</script>
	<?php

	foreach($_ARR_EPS as $val) {
		$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_status' AND `eps`='$val'") or die(mysql_error());
		$pay_status[$val] = number_format(mysql_result($sql,0,0), 0, ".", "");

		$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_min' AND `eps`='$val'") or die(mysql_error());
		$pay_min[$val] = number_format(mysql_result($sql,0,0), 2, ".", "");

		$cnt_amount = strtolower(md5(strtolower($username.session_id().getRealIP().$_SERVER["HTTP_USER_AGENT"].$_SERVER["HTTP_HOST"]."SP48915022")));
		$_SESSION["cnt_amount"] = $cnt_amount;

		if($pay_status[$val] == 0) {
			$_ACT_CASH[$val] = false;
			$line_text[$val] = '<span class="line-red">Временно недоступно</span>';
		}else{
			if($my_purse[$val] == false) {
				$_ACT_CASH[$val] = 'onClick="document.location.href = \'/profile.php\';"';
				$line_text[$val] = '<span class="line-gray">Указать кошелек</span>';
			}else{
				if($my_money_ob >= $pay_min[$val]) {
					$_ACT_CASH[$val] = 'onClick="CashOut(\'order\', \''.$val.'\');"';
					$line_text[$val] = '<span class="line-'.($pay_status[$val]==1 ? "orange" : "blue").'">'.(($val=="qw") ? "" : false).'Минимум '.$pay_min[$val].'</span>';
				}else{
					$_ACT_CASH[$val] = false;
					$line_text[$val] = '<span class="line-orange">Минимум '.$pay_min[$val].' руб.</span>';
				}
			}
		}
	}

	echo '<div id="CashAct" style="display:block; padding-bottom:30px;">';
		echo '<h2 class="sp" style="margin-top:5px; /*color:#167E48;*/ font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">Укажите сумму для вывода в рублях</h2>';
		echo '<div align="center" class="sum-form">';
			echo '<input type="text" class="sum-out" id="sum_amount" value="'.($my_money_ob > 0 ? p_floor($my_money_ob, 2) : 0).'" onChange="SumChange();" onKeydowm="="SumChange();" onKeyup="SumChange();" maxlength="10" autocomplete="off">';
			echo '<input type="hidden" id="cnt_amount" value="'.$cnt_amount.'">';
		echo '</div>';

		echo '<div id="info-msg-cash" style="display:none;"></div>';

		echo '<h2 class="sp" style="margin-top:0; font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">Выберите куда желаете получить ваши деньги</h2>';
		echo '<div style="text-align: center; width: 90%; margin: 10px auto;">';
			foreach($_ARR_EPS as $val) {
				echo '<div class="cashout-'.$val.'" '.$_ACT_CASH[$val].'><div><div><div>'.$line_text[$val].'</div></div></div></div>';
			}
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek_bal'");
				$reit_rek_bal = mysql_result($sql,0,0);
				
	  echo '<br/>';
	  echo '<br/>';
      echo '<hr>';
      echo '<br/>';
      echo '<center><h2 class="sp" style="margin-top:0; font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">На Рекламный счет<//h2><center>';
      echo '<div id="load-money-add">';
    echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
      echo '<div class="sum">';
      echo '<input type="text" class="summ" value="'.($my_money_ob > 0 ? p_floor($my_money_ob, 2) : 0).'" name="money_add" id="newform" size="5" onkeyup="" maxlength="16">';
      echo '</div>';
      echo '<button name="method_pay" value="22" style="background-color: #eff0f1; color: black; border: 2px solid #eff0f1;"><div class="btn blue">Перевести с Основного счета на Рекламный счет</div></button>';
      echo '<br/>';
      echo '<center><h2 class="sp" style="margin-top:0; font-weight:bold; text-shadow: 0 1px 0 #FFFFFF, 1px 2px 2px #AAAAAA;">За каждые полные 10 рублей <font size="3" color="C80000"><b>+'.$reit_rek_bal.' балл</b></font> к вашему рейтингу</h2></center>';
    echo '</form>';
		echo '</div>';
	echo '</div>';
		echo '</div>';
}
}else{
echo '<span class="msg-warning">Для выплат денежных средств вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
}
}

include(ROOT_DIR."/footer.php");
?>