<?php
$pagetitle="Черный список";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	?>
	<script type="text/javascript" language="JavaScript">
		var tm;

		function HideMsg(id, timer) {
		        clearTimeout(tm);
			tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
			return false;
		}

		function LoadBlock(X) {
			$("#info-msg-bl").html("").hide();
			for (Z=1; Z<=3; Z++) {
				if (elem = $("#LoadForm-"+Z)) {
					$("#LoadForm-"+Z).attr("style", "display: none;");
				}
				if (elem = $("#block-"+Z)) {
					if (Z == X) elem.attr("class", "check-aut-active"); else elem.attr("class", "check-aut");
			        }
			}
			$("#LoadForm-"+X).slideToggle("slow");
		}

		function SetChs(id) {
			var GetClass = $("#chs_"+id).attr("class") == "chs_act_"+id ? "chs_"+id : "chs_act_"+id;
			$("#chs_"+id).attr("class", GetClass);
			return false;
		}

		function EditChs(id, ident) {
			var GetClass = $("#chs_e_"+id+"_"+ident).attr("class") == "chs_act_"+id ? "chs_"+id : "chs_act_"+id;
			$("#chs_e_"+id+"_"+ident).attr("class", GetClass);

			var serf_usr = $("#chs_e_1_"+ident).attr("class") == "chs_act_1" ? 1 : 0;
			var aserf_usr = $("#chs_e_2_"+ident).attr("class") == "chs_act_2" ? 1 : 0;
			var mails_usr = $("#chs_e_3_"+ident).attr("class") == "chs_act_3" ? 1 : 0;
			var tests_usr = $("#chs_e_4_"+ident).attr("class") == "chs_act_4" ? 1 : 0;
			var tasks_usr = $("#chs_e_5_"+ident).attr("class") == "chs_act_5" ? 1 : 0;
			var chat = $("#chs_e_6_"+ident).attr("class") == "chs_act_6" ? 1 : 0;
			var out_mail = $("#chs_e_7_"+ident).attr("class") == "chs_act_7" ? 1 : 0;

			var serf_adv = $("#chs_e_8_"+ident).attr("class") == "chs_act_8" ? 1 : 0;
			var aserf_adv = $("#chs_e_9_"+ident).attr("class") == "chs_act_9" ? 1 : 0;
			var mails_adv = $("#chs_e_10_"+ident).attr("class") == "chs_act_10" ? 1 : 0;
			var tests_adv = $("#chs_e_11_"+ident).attr("class") == "chs_act_11" ? 1 : 0;
			var tasks_adv = $("#chs_e_12_"+ident).attr("class") == "chs_act_12" ? 1 : 0;
			var auction = $("#chs_e_13_"+ident).attr("class") == "chs_act_13" ? 1 : 0;
			var birja = $("#chs_e_14_"+ident).attr("class") == "chs_act_14" ? 1 : 0;

			if (!serf_usr && !aserf_usr && !mails_usr && !tests_usr && !tasks_usr && !chat && !out_mail && !serf_adv && !aserf_adv && !mails_adv && !tests_adv && !tasks_adv && !auction && !birja) {
				alert("Необходимо указать хотя бы один тип блокировки!");
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {
						'op':'EditBL', 'id':ident, 
						'serf_usr':serf_usr, 'aserf_usr':aserf_usr, 'mails_usr':mails_usr, 'tests_usr':tests_usr, 'tasks_usr':tasks_usr, 'chat':chat, 'out_mail':out_mail, 
						'serf_adv':serf_adv, 'aserf_adv':aserf_adv, 'mails_adv':mails_adv, 'tests_adv':tests_adv, 'tasks_adv':tasks_adv, 'auction':auction, 'birja':birja
					}, 
					dataType: 'json',
					error: function(request, status, errortext) {
						$("#loading").slideToggle();
						alert("ОШИБКА AJAX!");
						return false;
					},
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if (result != "OK") { alert(message); return false; }
					}
				});
			}
		}

		function ClearBL() {
			$("#user_bl").val("");
			for (i=1; i<=14; i++) { $("#chs_"+i).attr("class", "chs_"+i); }
			return false;
		}

		function AddBL() {
			$("#info-msg-bl").html("").hide();
			var user_bl = $.trim($("#user_bl").val());

			var serf_usr = $("#chs_1").attr("class") == "chs_act_1" ? 1 : 0;
			var aserf_usr = $("#chs_2").attr("class") == "chs_act_2" ? 1 : 0;
			var mails_usr = $("#chs_3").attr("class") == "chs_act_3" ? 1 : 0;
			var tests_usr = $("#chs_4").attr("class") == "chs_act_4" ? 1 : 0;
			var tasks_usr = $("#chs_5").attr("class") == "chs_act_5" ? 1 : 0;
			var chat = $("#chs_6").attr("class") == "chs_act_6" ? 1 : 0;
			var out_mail = $("#chs_7").attr("class") == "chs_act_7" ? 1 : 0;

			var serf_adv = $("#chs_8").attr("class") == "chs_act_8" ? 1 : 0;
			var aserf_adv = $("#chs_9").attr("class") == "chs_act_9" ? 1 : 0;
			var mails_adv = $("#chs_10").attr("class") == "chs_act_10" ? 1 : 0;
			var tests_adv = $("#chs_11").attr("class") == "chs_act_11" ? 1 : 0;
			var tasks_adv = $("#chs_12").attr("class") == "chs_act_12" ? 1 : 0;
			var auction = $("#chs_13").attr("class") == "chs_act_13" ? 1 : 0;
			var birja = $("#chs_14").attr("class") == "chs_act_14" ? 1 : 0;

			if (user_bl == false) {
				$("#info-msg-bl").html('<span class="msg-error">Необходимо указать логин пользователя!</span>').slideToggle("fast");
				$("#user_bl").focus().attr("class", "err");
				HideMsg("info-msg-bl", 4000);
				return false;

			} else if (!serf_usr && !aserf_usr && !mails_usr && !tests_usr && !tasks_usr && !chat && !out_mail && !serf_adv && !aserf_adv && !mails_adv && !tests_adv && !tasks_adv && !auction && !birja) {
				$("#info-msg-bl").html('<span class="msg-error">Необходимо указать хотя бы один тип блокировки!</span>').slideToggle("fast");
				HideMsg("info-msg-bl", 4000);
				return false;

			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {
						'op':'AddBL', 'user_bl':user_bl, 
						'serf_usr':serf_usr, 'aserf_usr':aserf_usr, 'mails_usr':mails_usr, 'tests_usr':tests_usr, 'tasks_usr':tasks_usr, 'chat':chat, 'out_mail':out_mail, 
						'serf_adv':serf_adv, 'aserf_adv':aserf_adv, 'mails_adv':mails_adv, 'tests_adv':tests_adv, 'tasks_adv':tasks_adv, 'auction':auction, 'birja':birja
					}, 
					dataType: 'json',
					error: function(request, status, errortext) {
						$("#loading").slideToggle();
						var error = [];
						error["rState"] = request.readyState!==false ? request.readyState : false;
						error["rText"]  = request.responseText!=false ? request.responseText : errortext;
						error["status"] = request.status!==false ? request.status : false;
						error["statusText"] = request.statusText!==false ? request.statusText : false;
						$("#info-msg-bl").html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>').slideToggle("fast");
						HideMsg("info-msg-bl", 10000);
						return false;
					},
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						$("#info-msg-bl").html("").hide();

						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						if (result == "OK") {
							ClearBL();
							LoadBL();
							//$("#info-msg-bl").html('<span class="msg-ok" style="font-weight:normal;">'+message+'</span>').slideToggle("fast");
							//HideMsg("info-msg-bl", 3000);
							return false;
						} else {
							$("#info-msg-bl").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
							HideMsg("info-msg-bl", 10000);
							return false;
						}
					}
				});
			}
		}

		function LoadBL() {
			$.ajax({
				type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
				data: {'op':'LoadBL'}, 
				dataType: 'json',
				beforeSend: function() { $("#BodyBL").html('<tr><td align="center" colspan="9" style="background:none; border:none;"><div class="loading-table"></div></td></tr>'); }, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if (result == "OK") {
						setTimeout(function() {$("#BodyBL").html(message);}, 200);
						return false;
					}
				}
			});
		}

		function SearchBL(user_search) {
			var user_search = $.trim($("#user_search").val());

			if (user_search == false) {
				alert("Для поиска укажите логин пользователя!");
				$("#user_search").focus();
				return false;
			} else {
				$("#user_search").blur();
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {'op':'SearchBL', 'user_search':user_search}, 
					dataType: 'json',
					beforeSend: function() { $("#BodyBL").html('<tr><td align="center" colspan="9" style="background:none; border:none;"><div class="loading-table"></div></td></tr>'); }, 
					success: function(data) {
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if (result == "OK") {
							setTimeout(function() {$("#BodyBL").html(message);}, 400);
							$("#ClearSearch").html('<span style="float:right; margin-top:10px;" onClick="ClearSearchBl();" class="workcomp" title="Сбросить поиск"></span>');
							return false;
						}else{
							$("#BodyBL").html('<tr><td align="center" colspan="9"><div class="msg-error">'+message+'</div></td></tr>');
							return false;
						}
					}
				});
			}
		}

		function ClearSearchBl() {
			$("#user_search").val("");
			$("#ClearSearch").html("");
			LoadBL();
			return false;
		}

		function DelBL(id, username) {
			if (confirm("Вы уверены что хотите разблокировать пользователя " + username + " ?")) {
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {'op':'DelBL', 'id':id}, 
					dataType: 'json',
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if (result == "OK") {
							//$("#trdel_a_"+id).remove().slideToggle();
							//$("#trdel_b_"+id).remove().slideToggle();
							LoadBL();
							return false;
						}
					}
				});
			}
		}

		function AddUrlBL() {
			$("#info-msg-urlbl").html("").hide();
			var url_bl = $.trim($("#url_bl").val());

			if (url_bl == false) {
				$("#info-msg-urlbl").html('<span class="msg-error">Необходимо указать URL сайта!</span>').slideToggle("fast");
				$("#url_bl").focus().attr("class", "err");
				HideMsg("info-msg-urlbl", 4000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {
						'op':'AddUrlBL', 'url_bl':url_bl
					}, 
					dataType: 'json',
					error: function(request, status, errortext) {
						$("#loading").slideToggle();
						var error = [];
						error["rState"] = request.readyState!==false ? request.readyState : false;
						error["rText"]  = request.responseText!=false ? request.responseText : errortext;
						error["status"] = request.status!==false ? request.status : false;
						error["statusText"] = request.statusText!==false ? request.statusText : false;
						$("#info-msg-urlbl").html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>').slideToggle("fast");
						HideMsg("info-msg-urlbl", 10000);
						return false;
					},
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						$("#info-msg-urlbl").html("").hide();

						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						if (result == "OK") {
							$("#url_bl").val("");
							LoadUrlBL();
							return false;
						} else {
							$("#info-msg-urlbl").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
							HideMsg("info-msg-urlbl", 10000);
							return false;
						}
					}
				});
			}
		}

		function LoadUrlBL() {
			$.ajax({
				type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
				data: {'op':'LoadUrlBL'}, 
				dataType: 'json',
				beforeSend: function() { $("#BodyUrlBL").html('<tr><td align="center" colspan="2" style="background:none; border:none;"><div class="loading-table"></div></td></tr>'); }, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if (result == "OK") {
						setTimeout(function() {$("#BodyUrlBL").html(message);}, 200);
						return false;
					}
				}
			});
		}

		function SearchUrlBL(url_search) {
			var url_search = $.trim($("#url_search").val());

			if (url_search == false) {
				alert("Для поиска укажите URL сайта!");
				$("#url_search").focus();
				return false;
			} else {
				$("#url_search").blur();
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {'op':'SearchUrlBL', 'url_search':url_search}, 
					dataType: 'json',
					beforeSend: function() { $("#BodyUrlBL").html('<tr><td align="center" colspan="2" style="background:none; border:none;"><div class="loading-table"></div></td></tr>'); }, 
					success: function(data) {
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if (result == "OK") {
							setTimeout(function() {$("#BodyUrlBL").html(message);}, 400);
							$("#ClearSearchUrl").html('<span style="float:right; margin-top:10px;" onClick="ClearSearchUrlBl();" class="workcomp" title="Сбросить поиск"></span>');
							return false;
						}else{
							$("#BodyUrlBL").html('<tr><td align="center" colspan="2"><div class="msg-error">'+message+'</div></td></tr>');
							return false;
						}
					}
				});
			}
		}

		function ClearSearchUrlBl() {
			$("#url_search").val("");
			$("#ClearSearchUrl").html("");
			LoadUrlBL();
			return false;
		}

		function DelUrlBL(id, url) {
			if (confirm("Вы уверены что хотите разблокировать URL сайта " + url + " ?")) {
				$.ajax({
					type: "POST", url: "ajax/ajax_black_list_all.php?rnd="+Math.random(), 
					data: {'op':'DelUrlBL', 'id':id}, 
					dataType: 'json',
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if (result == "OK") {
							//$("#trdel_"+id).remove().slideToggle();
							LoadUrlBL();
							return false;
						}
					}
				});
			}
		}

	</script>
	<?php

	echo '<table class="tables" style="width:100%; margin:0 auto;">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th width="40%"><span id="block-1" class="check-aut-active" onClick="LoadBlock(1);">ЧС пользователей и рекламодателей</span></th>';
			echo '<th width="30%"><span id="block-2" class="check-aut" onClick="LoadBlock(2);">ЧС сайтов для заданий</span></th>';
			echo '<th width="30%"><span id="block-3" class="check-aut" onClick="LoadBlock(3);">Расшифровка значков</span></th>';
		echo '</tr>';
	echo '</thead>';
	echo '</table>';


	echo '<div id="newform">';

		echo '<div id="LoadForm-1" style="display: block;">';

			echo '<table class="tables" style="width:100%; margin:10px auto;">';
			echo '<thead><th width="200">Логин</th><th colspan="7">Тип блокировки</th><th width="180"></th></thead>';
			echo '<tbody>';
			echo '<tr>';
				echo '<td class="chs fleft" rowspan="2"><input class="ok" type="text" id="user_bl" name="user_bl" value="" maxlength="20" placeholder="Логин пользователя" style="padding:2px 5px; margin:0; width:auto;" onKeyDown="$(this).attr(\'class\', \'ok\');" /></td>';
				echo '<td class="chs"><span id="chs_1" class="chs_1" onClick="SetChs(1);" title="Скрыть для пользователя Ваш серфинг"></td>';
				echo '<td class="chs"><span id="chs_2" class="chs_2" onClick="SetChs(2);" title="Скрыть для пользователя Ваш авто-серфинг"></td>';
				echo '<td class="chs"><span id="chs_3" class="chs_3" onClick="SetChs(3);" title="Скрыть для пользователя Ваши письма"></td>';
				echo '<td class="chs"><span id="chs_4" class="chs_4" onClick="SetChs(4);" title="Скрыть для пользователя Ваши тесты"></td>';
				echo '<td class="chs"><span id="chs_5" class="chs_5" onClick="SetChs(5);" title="Скрыть для пользователя Ваши задания"></td>';
				echo '<td class="chs"><span id="chs_6" class="chs_6" onClick="SetChs(6);" title="Скрыть от Вас его сообщения в чате"></td>';
				echo '<td class="chs"><span id="chs_7" class="chs_7" onClick="SetChs(7);" title="Запретить отправку сообщений Вам на внутреннюю почту"></td>';
				echo '<td class="chs" rowspan="2"><span class="proc-btn" onClick="AddBL();" style="float:none; margin: 0 auto;" title="Добавить в ЧС">Добавить в ЧС</span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td class="chs"><span id="chs_8" class="chs_8" onClick="SetChs(8);" title="Скрыть для Вас серфинг от рекламодателя"></td>';
				echo '<td class="chs"><span id="chs_9" class="chs_9" onClick="SetChs(9);" title="Скрыть для Вас авто-серфинг от рекламодателя"></td>';
				echo '<td class="chs"><span id="chs_10" class="chs_10" onClick="SetChs(10);" title="Скрыть для Вас письма от рекламодателя"></td>';
				echo '<td class="chs"><span id="chs_11" class="chs_11" onClick="SetChs(11);" title="Скрыть для Вас тесты от рекламодателя"></td>';
				echo '<td class="chs"><span id="chs_12" class="chs_12" onClick="SetChs(12);" title="Скрыть для Вас задания от рекламодателя"></td>';
				echo '<td class="chs"><span id="chs_13" class="chs_13" onClick="SetChs(13);" title="Скрыть для Вас его рефералов на аукционе"></td>';
				echo '<td class="chs"><span id="chs_14" class="chs_14" onClick="SetChs(14);" title="Скрыть для Вас его рефералов на бирже"></td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '<div id="info-msg-bl" style="display:none;"></div>';

			echo '<div id="block-search-bl" style="height:35px; width:100%; margin:0 auto; padding-top:10px; border-bottom:1px dotted #ff7f50; font:15px Tahoma, Arial, sans-serif; color:#ab0606;">';
			echo '<form onSubmit="SearchBL(); return false;">';
				echo '<div style="float:left; margin-top:15px;">Список заблокированных</div>';
				echo '<div id="ClearSearch" style="width:18px; height:16px; display:block; float:right;"></div>';
				echo '<div style="float:right; margin-top:7.5px;" onClick="SearchBL();" class="but-search" title="Найти"></div>';
				echo '<div style="float:right;"><input type="text" id="user_search" value="" maxlength="20" class="ok12" placeholder="Поиск по логину" autocomplete="off" style="padding:2px 5px; margin:5px 4px; text-align:center;"></div>';
			echo '</form>';
			echo '</div>';

			echo '<table class="tables" style="width:100%; margin: 5px auto 10px auto;">';
			echo '<thead><th width="200">Логин, Дата</th><th colspan="7">Тип блокировки</th><th width="180"></th></thead>';
			echo '<tbody id="BodyBL">';
			$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='usr_adv' ORDER BY `id` DESC");
			if(mysql_num_rows($sql_bl)>0) {
				while ($row_bl = mysql_fetch_assoc($sql_bl)) {
					echo '<tr id="trdel_a_'.$row_bl["id"].'">';
						echo '<td class="chs fleft" rowspan="2"><b>'.$row_bl["user_bl"].'</b><br><br>'.$row_bl["date"].'</td>';
						echo '<td class="chs"><span id="chs_e_1_'.$row_bl["id"].'" class="'.($row_bl["serf_usr"]==1 ? "chs_act_1" : "chs_1").'" onClick="EditChs(1, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш серфинг"></td>';
						echo '<td class="chs"><span id="chs_e_2_'.$row_bl["id"].'" class="'.($row_bl["aserf_usr"]==1 ? "chs_act_2" : "chs_2").'" onClick="EditChs(2, '.$row_bl["id"].');" title="Скрыть для пользователя Ваш авто-серфинг"></td>';
						echo '<td class="chs"><span id="chs_e_3_'.$row_bl["id"].'" class="'.($row_bl["mails_usr"]==1 ? "chs_act_3" : "chs_3").'" onClick="EditChs(3, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши письма"></td>';
						echo '<td class="chs"><span id="chs_e_4_'.$row_bl["id"].'" class="'.($row_bl["tests_usr"]==1 ? "chs_act_4" : "chs_4").'" onClick="EditChs(4, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши тесты"></td>';
						echo '<td class="chs"><span id="chs_e_5_'.$row_bl["id"].'" class="'.($row_bl["tasks_usr"]==1 ? "chs_act_5" : "chs_5").'" onClick="EditChs(5, '.$row_bl["id"].');" title="Скрыть для пользователя Ваши задания"></td>';
						echo '<td class="chs"><span id="chs_e_6_'.$row_bl["id"].'" class="'.($row_bl["chat"]==1 ? "chs_act_6" : "chs_6").'" onClick="EditChs(6, '.$row_bl["id"].');" title="Скрыть от Вас его сообщения в чате"></td>';
						echo '<td class="chs"><span id="chs_e_7_'.$row_bl["id"].'" class="'.($row_bl["out_mail"]==1 ? "chs_act_7" : "chs_7").'" onClick="EditChs(7, '.$row_bl["id"].');" title="Запретить отправку сообщений Вам на внутреннюю почту"></td>';
						echo '<td class="chs" rowspan="2"><span class="sub-blue160" onClick="DelBL(\''.$row_bl["id"].'\', \''.$row_bl["user_bl"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
					echo '</tr>';
					echo '<tr id="trdel_b_'.$row_bl["id"].'">';
						echo '<td class="chs"><span id="chs_e_8_'.$row_bl["id"].'" class="'.($row_bl["serf_adv"]==1 ? "chs_act_8" : "chs_8").'" onClick="EditChs(8, '.$row_bl["id"].');" title="Скрыть для Вас серфинг от рекламодателя"></td>';
						echo '<td class="chs"><span id="chs_e_9_'.$row_bl["id"].'" class="'.($row_bl["aserf_adv"]==1 ? "chs_act_9" : "chs_9").'" onClick="EditChs(9, '.$row_bl["id"].');" title="Скрыть для Вас авто-серфинг от рекламодателя"></td>';
						echo '<td class="chs"><span id="chs_e_10_'.$row_bl["id"].'" class="'.($row_bl["mails_adv"]==1 ? "chs_act_10" : "chs_10").'" onClick="EditChs(10, '.$row_bl["id"].');" title="Скрыть для Вас письма от рекламодателя"></td>';
						echo '<td class="chs"><span id="chs_e_11_'.$row_bl["id"].'" class="'.($row_bl["tests_adv"]==1 ? "chs_act_11" : "chs_11").'" onClick="EditChs(11, '.$row_bl["id"].');" title="Скрыть для Вас тесты от рекламодателя"></td>';
						echo '<td class="chs"><span id="chs_e_12_'.$row_bl["id"].'" class="'.($row_bl["tasks_adv"]==1 ? "chs_act_12" : "chs_12").'" onClick="EditChs(12, '.$row_bl["id"].');" title="Скрыть для Вас задания от рекламодателя"></td>';
						echo '<td class="chs"><span id="chs_e_13_'.$row_bl["id"].'" class="'.($row_bl["auction"]==1 ? "chs_act_13" : "chs_13").'" onClick="EditChs(13, '.$row_bl["id"].');" title="Скрыть для Вас его рефералов на аукционе"></td>';
						echo '<td class="chs"><span id="chs_e_14_'.$row_bl["id"].'" class="'.($row_bl["birja"]==1 ? "chs_act_14" : "chs_14").'" onClick="EditChs(14, '.$row_bl["id"].');" title="Скрыть для Вас его рефералов на бирже"></td>';
					echo '</tr>';
				}
			}else{
				echo '<tr>';
					echo '<td class="chs fleft" colspan="9"><b>Нет данных</b></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';

		echo '</div>';


		echo '<div id="LoadForm-2" style="display: none;">';
		    echo '<br><div style="margin:0 auto; background:#f9e8d1; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:justify; color:#114C5B;">Скрывает из общего списка все задания с добавленным Вами доменом.</div>';
			echo '<table class="tables" style="width:100%; margin:10px auto;">';
			echo '<thead><th>URL сайта</th><th width="180"></th></thead>';
			echo '<tbody>';
			echo '<tr>';
				echo '<td class="chs fleft" style="padding-right:20px;"><input class="ok" type="text" id="url_bl" name="url_bl" value="" maxlength="300" placeholder="Например: site.ru" style="padding:2px 5px; margin:0; width:100%;" onKeyDown="$(this).attr(\'class\', \'ok\');" /></td>';
				echo '<td class="chs"><span class="proc-btn" onClick="AddUrlBL();" style="float:none; margin: 0 auto;" title="Добавить в ЧС">Добавить в ЧС</span></td>';
			echo '</tr>';
			echo '</tbody>';
			echo '</table>';
			echo '<div id="info-msg-urlbl" style="display:none;"></div>';

			echo '<div id="block-search-urlbl" style="height:35px; width:100%; margin:0 auto; padding-top:10px; border-bottom:1px dotted #ff7f50; font:15px Tahoma, Arial, sans-serif; color:#ab0606;">';
			echo '<form onSubmit="SearchUrlBL(); return false;">';
				echo '<div style="float:left; margin-top:15px;">Список заблокированных сайтов</div>';
				echo '<div id="ClearSearchUrl" style="width:18px; height:16px; display:block; float:right;"></div>';
				echo '<div style="float:right; margin-top:7.5px;" onClick="SearchUrlBL();" class="but-search" title="Найти"></div>';
				echo '<div style="float:right;"><input type="text" id="url_search" value="" maxlength="20" class="ok" placeholder="Поиск по URL" autocomplete="off" style="width:200px; padding:2px 5px; margin:5px 4px; text-align:center;"></div>';
			echo '</form>';
			echo '</div>';

			echo '<table class="tables" style="width:100%; margin: 5px auto 10px auto;">';
			echo '<thead><th>URL сайта</th><th width="180"></th></thead>';
			echo '<tbody id="BodyUrlBL">';
			$sql_bl = mysql_query("SELECT * FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='url' ORDER BY `id` DESC");
			if(mysql_num_rows($sql_bl)>0) {
				while ($row_bl = mysql_fetch_assoc($sql_bl)) {
					echo '<tr id="trdel_'.$row_bl["id"].'">';
						echo '<td class="chs fleft" style="text-align:left;"><b>'.$row_bl["url"].'</b></td>';
						echo '<td class="chs"><span class="proc-btn" onClick="DelUrlBL(\''.$row_bl["id"].'\', \''.$row_bl["url"].'\');" style="float:none; margin: 0 auto;" title="Удалить из ЧС">Удалить из ЧС</span></td>';
					echo '</tr>';
				}
			}else{
				echo '<tr>';
					echo '<td class="chs fleft" colspan="2"><b>Нет данных</b></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';


		echo '</div>';
		
		
		echo '<div id="LoadForm-3" style="display: none;">';
			echo '<table class="tables" style="width:100%;margin:10px auto;">';
                echo '<thead>';
	                echo '<tr>';
		                echo '<th><b>Выкл</b></th>';
			            echo '<th><b>Вкл</b></th>';
			            echo '<th colspan="2"><b>Описание</b></th>';
		            echo '<tr>';
	            echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td style="width:40px;"><span class="chs_1"></span></td>';
                        echo '<td style="width:40px;"><span class="chs_act_1"></span></td>';
			            echo '<td>Скрывает от пользователя размещённый Вами серфинг.</td>';
						echo '<td rowspan="5" width="35%">Если ВЫ хотите скрыть для кого-то свою рекламу.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_2"></span></td>';
                        echo '<td><span class="chs_act_2"></span></td>';
			            echo '<td>Скрывает от пользователя размещённый Вами авто-серфинг.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_3"></span></td>';
                        echo '<td><span class="chs_act_3"></span></td>';
			            echo '<td>Скрывает от пользователя размещённые Вами письма.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_4"></span></td>';
                        echo '<td><span class="chs_act_4"></span></td>';
			            echo '<td>Скрывает от пользователя размещённые Вами тесты.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_5"></span></td>';
                        echo '<td><span class="chs_act_5"></span></td>';
			            echo '<td>Скрывает от пользователя размещённые Вами задания.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_6"></span></td>';
                        echo '<td><span class="chs_act_6"></span></td>';
			            echo '<td>Скрывает от Вас сообщения в чате.</td>';
						echo '<td rowspan="2">Если ВЫ не хотите принимать сообщения от пользователя.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_7"></span></td>';
                        echo '<td><span class="chs_act_7"></span></td>';
			            echo '<td>Запретить отправку сообщений Вам на внутреннюю почту.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_8"></span></td>';
                        echo '<td><span class="chs_act_8"></span></td>';
			            echo '<td>Скрывает для Вас серфинг от рекламодателя.</td>';
						echo '<td rowspan="5">Если ВЫ хотите скрыть для себя чью-то рекламу.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_9"></span></td>';
                        echo '<td><span class="chs_act_9"></span></td>';
			            echo '<td>Скрывает для Вас авто-серфинг от рекламодателя.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_10"></span></td>';
                        echo '<td><span class="chs_act_10"></span></td>';
			            echo '<td>Скрывает для Вас письма от рекламодателя.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_11"></span></td>';
                        echo '<td><span class="chs_act_11"></span></td>';
			            echo '<td>Скрывает для Вас тесты от рекламодателя.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_12"></span></td>';
                        echo '<td><span class="chs_act_12"></span></td>';
			            echo '<td>Скрывает для Вас задания от рекламодателя.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_13"></span></td>';
                        echo '<td><span class="chs_act_13"></span></td>';
			            echo '<td>Скрывает для Вас рефералов на аукционе от продавца.</td>';
						echo '<td rowspan="2">Если ВЫ хотите скрыть для себя рефералов от определённых продавцов.</td>';
                    echo '</tr>';
		            echo '<tr>';
                        echo '<td><span class="chs_14"></span></td>';
                        echo '<td><span class="chs_act_14"></span></td>';
			            echo '<td>Скрывает для Вас рефералов на бирже от продавца.</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';


		echo '</div>';

	echo '</div>';
}

include(ROOT_DIR."/footer.php");
?>