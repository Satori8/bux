<?php
$pagetitle="Разместить аватар на доске почета";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}else{
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_add'");
	$cena_board_add = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_comm'");
	$cena_board_comm = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_reit'");
	$cena_board_reit = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_comis'");
	$cena_board_comis = round(number_format(mysql_result($sql,0,0), 2, ".", ""), 2);

	$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`=''");
	$all_users_no_ref = number_format(mysql_num_rows($sql), 0, ".", "`");
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='board_bonus' AND `howmany`='1'");
		$sum_dch = mysql_result($sql,0,0);

	?>
	<script type="text/javascript" src="js/jquery.simpletip-1.3.1.pack.js"></script>
	<script type="text/javascript" language="JavaScript">
		var tm;

		function HideMsg(id, timer) {
		        clearTimeout(tm);
			tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
			return false;
		}

		function ClearFormBoard() {
			$("#stavka").val("");
			$("#comment").val("");
			return false;
		}

		$(document).ready(function(){
			ClearFormBoard();

			$("#hint1").simpletip({
				fixed: true, position: ["-613", "24"], focus: false,
				content: '<b>Ставка</b> - минимум <?php echo $cena_board_add;?> руб.<br>Чем больше Ваша ставка тем больше шанс, что Вы будете больше времени<br>находиться на доске почета, а также больше шанс победить в конкурсе!'
			});
			$("#hint2").simpletip({
				fixed: true, position: ["-613", "24"], focus: false,
				content: '<b>Комментарий</b> - максимальная длинна 30 символов. Ссылки запрещены в комментарии.<br>Комментарий будет отображаться у вас под аватаркой на доске почёта, если не желаете добавлять комментарий то оставьте поле пустым.<br>Cтоимость добавления комментария <?php echo $cena_board_comm;?> руб.'
			});
		})

		function SetBoard() {
			var stavka = $.trim($("#stavka").val());
			var comment = $.trim($("#comment").val());
			$("#info-msg-board").html("").hide();

			$.ajax({
				type: "POST", url: "ajax/ajax_board.php", 
				data: {'op':'SetBoard', 'stavka':stavka, 'comment':comment}, 
				dataType: 'json',
				error: function(request, status, errortext) {
					$("#loading").slideToggle();
					var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
					$("#info-msg-board").html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>').slideToggle();
					//console.log(request, status, errortext);
				},
				beforeSend: function() { $("#loading").slideToggle(); }, 
				success: function(data) {
					$("#loading").slideToggle();
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;

					if (result == "OK") {
						$("#info-msg-board").html('<span class="msg-ok">'+message+'</span>').slideToggle("fast");
						HideMsg("info-msg-board", 1300);
						//window.location = '';
						LoadLiders(); LoadBoard(); ClearFormBoard();
						return false;
					} else {
						$("#info-msg-board").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
						HideMsg("info-msg-board", 5000);
						return false;
					}
				}
			});
		}

		function LoadLiders() {
			$.ajax({
				type: "POST", url: "ajax/ajax_board.php", 
				data: {'op':'LoadLiders'}, 
				dataType: 'json',
				error: function() {alert("Ошибка! Обновите страницу."); return false;}, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if (result == "OK") {
						setTimeout(function(){$("#LidersBoard").html(message).show();}, 50);
						return false;
					}
				}
			});
		}

		function LoadBoard() {
			$.ajax({
				type: "POST", url: "ajax/ajax_board.php", 
				data: {'op':'LoadBoard'}, 
				dataType: 'json',
				error: function() {alert("Ошибка! Обновите страницу."); return false;}, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if (result == "OK") {
						setTimeout(function(){
							$("#UserBoard").html(message);
							$("html, body").animate({scrollTop: $("#UserBoard").offset().top-30}, 700);
						}, 1500);
						return false;
					}else{
						setTimeout(function(){alert(message);}, 500);
						return false;
					}
				}
			});
		}

	</script>
	</script>
	<script src="js/taim.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".eTimer").eTimer({
			etType: 1, etDate: "<?php echo DATE("d.m.Y.H.i", strtotime(DATE("d.m.Y", (time()+24*60*60))));?>", 
			etTitleText: "До завершения конкурса осталось", etTitleSize: 21, etShowSign: 1, 
			etSep: ":", etFontFamily: "Impact", etTextColor: "#f30d6e", 
			etPaddingTB: 9, etPaddingLR: 9, etBackground: "#f7e9d5", 
			etBorderSize: 0, etBorderRadius: 0, etBorderColor: "white", etShadow: " 0px 0px 10px 0px #333333", 
			etLastUnit: 4, etNumberFontFamily: "Impact", etNumberSize: 35, etNumberColor: "#ec1e1e", 
			etNumberPaddingTB: 0, 
			etNumberPaddingLR: 6, 
			etNumberBackground: "#ab0606", 
			etNumberBorderSize: 0, 
			etNumberBorderRadius: 5, 
			etNumberBorderColor: "#ff7f50", 
			etNumberShadow: "inset 0px 0px 10px 0px rgba(0, 0, 0, 0.5)"
		});
			$(".etDays").remove();
			$(".etSep:first").remove();
		});
</script>
	<?php

	echo '<div style="margin:0 auto; background:#f7e9e3; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#107;">';
		echo 'Аватар размещается в правом блоке на всех страницах сайта. Разместив свой аватар на доске почета, Вы получите прекрасную возможность заявить о себе, а так же привлечь в свою команду свободных ';
		if($all_users_no_ref>0) {echo '(всего: <b>'.$all_users_no_ref.'</b>) ';} 
		echo 'рефералов.<br><br>';
		echo 'Кликнув по аватару пользователи попадают на Вашу стену, где узнают о вас больше и возможно присоединятся к вам. Ваш аватар будет размещен до тех пор пока его не сменит другой пользователь, а значит он может провисеть как несколько минут, так и несколько дней.<br><br>';
		echo 'Сделав высокую ставку вас не смогут сменить пока не сделают ставку выше, но ставка каждые 5 минут понижается на одну единицу, пока не достигнет нуля.<br><br>';
		echo 'Стоимость размещения зависит от размера ставки, но не менее <span style="font-weight:bold;">'.$cena_board_add.'</span> руб.<br>';
		echo 'Стоимость добавления комментария не менее <span style="font-weight:bold;">'.$cena_board_comm.'</span> руб.<br>';
		echo 'За каждую ставку начисляется рейтинг: <img src="img/reiting.png" border="0" alt="" title="Рейтинг" align="absmiddle" style="margin:0; padding:0;" />&nbsp;<span style="color:green; font-weight:normal; font-size:16px;">+'.$cena_board_reit.'</span>';
	echo '</div>';
	
echo '<div style="margin:10px auto 15px; background:#f7e9e3; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#107;">';
echo '<h2 class="sp" style="color:#FF0000; font-size:16px; margin-top:0px;"><b>Ежемесячный конкурс</b></h2>';
echo '<b style="font-size:14px;">Условия конкурса:</b>';
echo '<b style="display:block; margin-top:3px;">Кто в течении месяца больше Всех встанет на доску почёта - тот и Победитель!</b>';
echo '<b style="display:block; margin-top:7px;">В случае одинакового счёта, побеждает тот кто сделал последнюю ставку</b>';
echo '<b style="display:block; margin-top:3px;">(приз зачисляется на основной счёт и доступен к выводу)</b>';
echo '<b style="display:block; margin-top:7px;">Денежный приз:</b>';
echo '<b style="display:block; margin-top:1px;><span class=" text-red"="">10% от суммы всех ставок всех участников в течении месяца!</b>';
echo '<b style="display:block; margin-top:5px; margin-bottom:7px; color: #FF0000;">Зачисление призов производится 1-го числа в 00:00 (MSK - Московское время).</b>';
echo '<b style="display:block; margin-top:7px;">Сумма приза на данный момент составляет <span class="text-red" style="font-size:15px;">'.round($sum_dch,2).' руб.</span></b>';

echo '<h5 class="sp">Лидеры месяца '.DATE("m.Y").':</h4>';
		echo '<table class="tables_inv">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th class="top">#</th>';
			echo '<th  class="top" colspan="2">Пользователь</th>';
			echo '<th class="top">Становился на доску почета</th>';
			echo '<th class="top">Дата последней ставки</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		$number = 0;
		$sqla = mysql_query("SELECT * FROM `tb_users` WHERE kon_board_m>0 ORDER BY `kon_board_m` DESC, `data_board` DESC LIMIT 10");
		if(mysql_num_rows($sqla)>0) {
			while ($rowa = mysql_fetch_assoc($sqla)) { 
				$number++;
				$sqls = mysql_query("SELECT * FROM `tb_board` WHERE user_name='".$rowa['username']."' ORDER BY `id` DESC LIMIT 1");
				$row = mysql_fetch_assoc($sqls);
				echo '<tr align="center">';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.$number.'</td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'; border-right:none;">';
					echo '<a href="/wall.php?uid='.$row["user_id"].'" target="_blank"><img class="avatar" src="/avatar/'.$rowa["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену пользователя '.$row["user_name"].'" /></a>';
					echo '<td style="text-align:left; border-left:none; padding-left:0px;">';
					echo '<div class="rw_login" style="text-align:left; border-left:none;"><span style="font-weight:'.($number==1 ? "bold" : "normal").'">'.$row["user_name"].'</span></div></td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'"><span style="color:'.($number==1 ? "#0000ff" : "#FF0000").'">'.$rowa["kon_board_m"].'</span></td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.DATE("d.m.Y H:i:s", $row["time"]).'</td>';
				echo '</tr>';
			}
		}
	echo '<tbody>';
	echo '</table>';

	echo '<h5 class="sp">Завершенные конкурсы:</h4>';
	echo '<table class="tables_inv">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th>Дата</th>';
			echo '<th>Победитель</th>';
			echo '<th>Сумма бонуса</th>';
			echo '<th>Статус</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		$sql_endw = mysql_query("SELECT * FROM `tb_board` WHERE `status_pr`>'0' ORDER BY `id` DESC LIMIT 5");
		if(mysql_num_rows($sql_endw)>0) {
			while ($row_end = mysql_fetch_assoc($sql_endw)) {
				echo '<tr align="center">';
					echo '<td class="left-ref">'.$row_end["date_pr"].'</td>';
					echo '<td><a href="/wall?uid='.$row_end["user_id"].'" target="_blank">'.$row_end["user_name"].'</a></td>';
					echo '<td><b style="color: #FF0000;">'.round($row_end["status_pr"],2).'</b></td>';
					echo '<td><b style="color:green">Зачислен</b></td>';
				echo '</tr>';
			}
		}
	echo '<tbody>';
	echo '</table>';
echo '</div>';

	$d1 = DATE("d.m.Y", (time()+24*60*60));
	$date_ost = strtotime("$d1") - time();

	//echo '<div align="center" style="color: #617E49;">';
	echo '<div style="margin:10px auto 15px; background:#f7e9e3; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#107;">';
		echo '<h2 class="sp" style="color: #FF0000; font-weight:bold; font-size:16px;">Ежедневный конкурс</h2>';
		echo '<b style="font-size:14px;">Условия конкурса:</b>';
		echo '<b style="display:block; margin-top:3px;">Кто в течении суток наберёт больше Всех в сумме ставок - тот и Победитель!</b>';
		echo '<b style="display:block; margin-top:7px;">В случае одинакового счёта, побеждает тот кто сделал последнюю ставку</b>';
		echo '<b style="display:block; margin-top:3px;">(приз зачисляется на основной счёт и доступен к выводу)</b>';
		echo '<b style="display:block; margin-top:7px;">Время проведения конкурса с 00.00 до 00:00 (MSK - Московское время).</b>';
		echo '<b style="display:block; margin-top:7px;">Денежный приз:</b>';
		echo '<b style="display:block; margin-top:1px;><span style="color: #FF0000;">'.$cena_board_comis.'%</span> от суммы всех ставок всех участников в течении суток!</b>';
		echo '<b style="display:block; margin-top:5px; margin-bottom:7px; color: #FF0000;">Зачисление призов производится в 00:00 (MSK - Московское время).</b>';
		//echo '<div style="display:block; margin-top:5px; margin-bottom:10px;">Время на сервере: <b>'.DATE("H:i").'</b>. До завершения конкурса осталось: '.date_ost($date_ost).'</div>';
	echo '</div>';

	echo '<div align="center" style="margin:10px auto;"><div class="eTimer"></div></div>';

	echo '<div class="warning-info" align="justify" style="font-weight:normal;">ВНИМАНИЕ!<br>Администрация не несёт ни какой ответственности при потери Вами средств и времени. Также возможны изменения процента выигрыша в любой момент, если у Вас есть сомнения или Вы не поняли правил работы конкурса, откажитесь от его участия!<br>НЕ ДЕЛАЙТЕ СТАВКИ НА ПОСЛЕДНИХ СЕКУНДАХ...</div><br>';

	echo '<table class="tables_inv" id="newform">';
		echo '<thead><tr align="center"><th align="center" colspan="3">Форма публикации на доске почета</th></tr></thead>';
		echo '<tr>';
			echo '<td align="right" width="40%" style="padding-right:15px;"><b>Укажите сумму ставки</b></td>';
			echo '<td align="left"><input type="text" id="stavka" maxlength="11" value="" class="ok" style="text-align:left;" placeholder="Только целые числа, минимум '.$cena_board_add.' руб." onKeyDown="$(this).attr(\'class\', \'ok\');"></td>';
			echo '<td align="center" width="16"><span id="hint1" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="right" style="padding-right:15px;"><b>Комментарий</b></td>';
			echo '<td align="left"><input type="text" id="comment" maxlength="30" value="" class="ok" style="text-align:left;" placeholder="Не обязательно" ></td>';
			echo '<td align="center" width="16"><span id="hint2" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="3" align="center"><span onclick="SetBoard();" class="proc-btn" style="float:none; width:160px;">Разместить аватар</span></td>';
		echo '</tr>';
	echo '</table>';
	echo '<div id="info-msg-board" style="display:none;"></div>';

	echo '<h5 class="sp">Лидеры сегодня '.DATE("d.m.Y").':</h4>';
	echo '<div id="LidersBoard"><table class="tables_inv">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th class="top">#</th>';
			echo '<th class="top" colspan="2">Пользователь</th>';
			echo '<th class="top">Общая сумма ставок (руб.)</th>';
			echo '<th class="top">Дата последней ставки</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
			$number = 0;
		$sql = mysql_query("SELECT * FROM `tb_board` WHERE `status`='0' AND `date`='".DATE("d.m.Y")."' ORDER BY `count` DESC, `time` DESC LIMIT 10");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_assoc($sql)) {
				$number++;
				$sqla = mysql_query("SELECT avatar, username FROM `tb_users` WHERE `username`='".$row["user_name"]."'");
				$rowa = mysql_fetch_assoc($sqla);
				echo '<tr align="center">';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.$number.'</td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'; border-right:none;">';
					echo '<a href="/wall.php?uid='.$row["user_id"].'" target="_blank"><img class="avatar" src="/avatar/'.$rowa["avatar"].'" style="width:30px; height:30px" border="0" title="Перейти на стену пользователя '.$row["user_name"].'" /></a>';
					echo '<td style="text-align:left; border-left:none; padding-left:0px;">';
					echo '<div class="rw_login" style="text-align:left; border-left:none;"><span style="font-weight:'.($number==1 ? "bold" : "normal").'">'.$row["user_name"].'</span></div></td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'"><span style="color: #FF0000;">'.$row["count"].'</span></td>';
					echo '<td style="font-weight:'.($number==1 ? "bold" : "normal").'">'.DATE("d.m.Y H:i:s", $row["time"]).'</td>';
				echo '</tr>';
			}
		}
	echo '<tbody>';
	echo '</table></div>';

	echo '<h5 class="sp">Завершенные конкурсы:</h4>';
	echo '<table class="tables_inv">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th>Дата</th>';
			echo '<th>Победитель</th>';
			echo '<th>Сумма приза (руб.)</th>';
			echo '<th>Статус</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		$sql_end = mysql_query("SELECT * FROM `tb_board` WHERE `status`='1' ORDER BY `id` DESC LIMIT 10");
		if(mysql_num_rows($sql_end)>0) {
			while ($row_end = mysql_fetch_assoc($sql_end)) {
				echo '<tr align="center">';
					echo '<td class="left-ref">'.$row_end["date"].'</td>';
					echo '<td><a href="/wall.php?uid='.$row_end["user_id"].'" target="_blank">'.$row_end["user_name"].'</a></td>';
					echo '<td><b style="color: #FF0000;">'.$row_end["money"].'</b></td>';
					echo '<td><b style="color:green">Зачислен</b></td>';
				echo '</tr>';
			}
		}
	echo '<tbody>';
	echo '</table>';

}

include(ROOT_DIR."/footer.php");
?>