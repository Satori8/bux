<?php
if(!DEFINED("CABINET")) exit("Access denied!");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require(ROOT_DIR."/config_mysqli.php");
$security_key = "CabTMsIiN^&uw(*Fn#*hglkj7p0U@if%YST630n?ad";

$mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='3',`date_end`='".time()."' WHERE `status`='1' AND `balance`<`price_adv`") or die($mysqli->error);
$mysqli->query("UPDATE `tb_ads_pay_vis` SET `status`='1' WHERE `status`='3' AND `balance`>=`price_adv`") or die($mysqli->error);

function SqlConfig($item, $howmany=1, $decimals=false){
	global $mysqli;

	$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='$item' AND `howmany`='$howmany'") or die($mysqli->error);
	$price = $sql->num_rows > 0 ? $sql->fetch_object()->price : die("Error: item['$item'] or howmany['$howmany'] not found in table `tb_config`");
	$sql->free();

	return ($decimals!==false && is_numeric($price)) ? round($price, $decimals) : $price;
}
?>

<script>
var TmMod;
var status_form, status_lp, last_info = false;

function FuncAdvCab(id, op, type, form_id, token, modal, title_win, width_win) {
	if(TmMod) clearTimeout(TmMod);
	var datas = {}; datas['id'] = id || 0; datas['op'] = op || ''; datas['type'] = type || ''; datas['token'] = token || '';
	if(form_id) {
		var data_form = $("#"+form_id).serializeArray();
		$.each(data_form, function(i, field){
			if(field.name=="country[]") datas[field.name] = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();
			else datas[field.name] = field.value;
		});
	}

	if(op == "info-adv" | op == "info-up" | op == "info-bal") {
		if((last_info == op+"-"+id | $("#tr-info-"+id).css("display") == "none")) {
			$(".tr-info").hide();
			if(op+"-"+id == last_info) { last_info = false; return false; }
		}
	}

	if(!status_form){$.ajax({
		type:"POST", cache:false, url:"/cabinet/ajax/ajax_cabinet_new.php", dataType:'json', data:datas, 
		error: function(request, status, errortext) {
			status_form = false; $("#loading").hide();
			ModalStart(request.status==404 ? errortext : "Ошибка Ajax!", StatusMsg("ERROR", request.status!=404 ? request.responseText+" "+errortext : "URL ajax not found.."), 500, true, false, false);
		}, 
		beforeSend: function() { status_form = true; $("input, textarea, select").blur(); $("#loading").show(); }, 
		success: function(data) {
			status_form = false; $("#loading").hide();
			var result = data.result || data;
			var message = data.message || data;
			width_win = width_win || 500;

			if (result == "OK") {
				title_win = title_win || "Информация";

				if(op == "play-pause") {
					$("#adv-status-"+id).html(message);

				} else if(op == "info-adv" | op == "info-up" | op == "info-bal") {
					last_info = op+"-"+id;
					$("#tr-info-"+id).show();
					$("#text-info-"+id).html(message);
					if(($(window).height() + $(window).scrollTop()) < ($("#text-info-"+id).offset().top + $("#text-info-"+id).height())) {
						$("html, body").animate({scrollTop: $("#tr-info-"+id).offset().top-$("#tr-adv-"+id).height()}, 700);
					}

				} else {
					if($("div").is(".box-modal") && message && modal) {
						$(".box-modal-title").html(title_win);
						$(".box-modal-content").html(message);
					} else if(message && modal) {
						ModalStart(title_win, message, width_win, true, false);
					}
				}
			} else { 
				title_win = title_win || "Ошибка";

				if(op == "info-adv" | op == "info-up" | op == "info-bal") {
					last_info = false;
				}

				if($("div").is(".box-modal") && message) {
					$(".box-modal-title").html(title_win);
					$(".box-modal-content").html(StatusMsg(result, message));
					TmMod = setTimeout(function(){$.modalpopup("close");}, 10000);
				} else if(message) {
					ModalStart(title_win, StatusMsg(result, message), width_win, true, false, 10);
				}
			}
		}
	});}
	return false;
}

function FuncLP(){
	if(!status_lp){
		var params = $("#load-pages");
		var datas = {};
		    datas['id'] = params.data("id") || 0;
		    datas['op'] = params.data("op") || '';
		    datas['type'] = params.data("type") || '';
		    datas['elid'] = params.data("elid") || '';
		    datas['page'] = params.data("page") || '';
		    datas['lastid'] = params.data("lastid") || 0;
		    datas['token'] = params.data("token") || '';

		$.ajax({
			type:"POST", cache:false, url:"/cabinet/ajax/ajax_cabinet_new.php", dataType:'json', data:datas, 
			beforeSend: function () { status_lp = true; $("#load-pages").html('<div id="loading-pages"></div>'); }, 
			error: function () { status_lp = false; $("#load-pages").html('Ошибка ajax!'); }, 
			success: function (data) {
				status_lp = false;
				var result = data.result || data;
				var message = data.message || data;

				if (result == "OK") {
					$("#load-pages").html('Показать еще');
					if(message.rows) $("#"+datas['elid']).append(message.rows);
					params.data({"page":parseInt(message.page), "lastid":parseInt(message.lastid), "token":message.token});
					if(!message.lastid | message.lastid == 0) $("#load-pages").remove();
				}else{
					$("#load-pages").html('Ошибка!');
					//$("#load-pages").html('<span class="text-red">'+message+'</span>');
				}
			}
		});
	}
	return false;
}

function isMoney(id_input){
	var in_money = $.trim($(id_input).val());
	in_money = in_money.replace(/\,/g, ".");
	in_money = in_money.match(/(\d+(\.)?(\d){0,2})?/);
	in_money = in_money[0] || '';
	if($(id_input).attr("type")!="number") $(id_input).val(in_money);
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		$("#subAdv").click();
	}
}
</script>

<?php
if(isset($ads, $id, $op) && $ads=="pay_visits" && $op!=false && $id>0) {
	if($op=="edit") {
		echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Посещения, редактирование площадки №'.$id.'</h5>';
		if(!DEFINED("PAY_VISITS_EDIT")) DEFINE("PAY_VISITS_EDIT", true);
		include("pay_visits_edit.php");
	}else{
		echo '<span class="msg-error">Option ['.$op.'] not found...</span>';
	}

	$mysqli->close();
	include(ROOT_DIR."/footer.php");
	exit();
}else{
	echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Мои оплачиваемые посещения</h5>';
}

$pvis_comis_del = SqlConfig('pvis_comis_del', 1, 0);

if($pvis_comis_del > 0) {
	echo '<div style="text-align:center; background:#F0F8FF; border:none; padding:10px; margin-bottom:20px; color:#E32636; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);">';
		echo 'При удалении (возврате средств) система изымает <b>'.$pvis_comis_del.'</b>% от возвращаемой суммы.<br>Суммы менее <b>1.00</b> руб. не возвращаются.';
	echo '</div>';
}

$sql = $mysqli->query("SELECT * FROM `tb_ads_pay_vis` USE INDEX (`username_id`) WHERE `username`='$username' ORDER BY `id` DESC") or die($mysqli->error);
if($sql->num_rows > 0) {
	echo '<table id="adv-tab" class="adv-cab">';
	echo '<tbody>';

	while ($row = $sql->fetch_assoc()) {
		$token_playpause = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-play-pause".$security_key));
		$token_conf_del = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-confirm-del".$security_key));
		$token_edit_adv = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-edit-adv".$security_key));
		$token_conf_erase = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-confirm-erase".$security_key));
		$token_info_up = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-info-up".$security_key));
		$token_info_adv = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-info-adv".$security_key));
		$token_info_bal = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-info-bal".$security_key));
		$token_stat_adv = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."token-stat-adv".$security_key));

		$cnt_totals = floor(bcdiv($row["balance"], $row["price_adv"]));
		$cnt_totals = number_format($cnt_totals, 0, ".", "`");

		if($row["status"]==1) {
			$sql_pos = $mysqli->query("SELECT COUNT(*) as `position` FROM `tb_ads_pay_vis` USE INDEX (`status_date_up`) WHERE `status`='1' AND `date_up`>(SELECT `date_up` FROM `tb_ads_pay_vis` WHERE `id`='".$row["id"]."')") or die($mysqli->error);
			$position = $sql_pos->num_rows > 0 ? ($sql_pos->fetch_object()->position + 1): 1;
			$sql_pos->free();
		}else{
			unset($position);
		}

		echo '<tr id="tr-adv-'.$row["id"].'" class="tr-adv">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="adv-status-'.$row["id"].'">';
					if($row["status"] == 0) {
						echo '<span onClick="FuncAdvCab('.$row["id"].', \'play-pause\', \''.$ads.'\', false, \''.$token_playpause.'\');" class="adv-play" title="Запустить показ рекламной площадки"></span>';
					}elseif($row["status"] == 1) {
						echo '<span onClick="FuncAdvCab('.$row["id"].', \'play-pause\', \''.$ads.'\', false, \''.$token_playpause.'\');" class="adv-pause" title="Приостановить показ рекламной площадки"></span>';
					}elseif($row["status"] == 2) {
						echo '<span onClick="FuncAdvCab('.$row["id"].', \'play-pause\', \''.$ads.'\', false, \''.$token_playpause.'\');" class="adv-play" title="Запустить показ рекламной площадки"></span>';
					}elseif($row["status"] == 3) {
						echo '<span onClick="FuncAdvCab('.$row["id"].', \'play-pause\', \''.$ads.'\', false, \''.$token_playpause.'\');" class="adv-play" title="Запустить показ рекламной площадки"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<div><a class="adv" href="'.$row["url"].'" target="_blank">'.$row["title"].'<br><span class="desc-text">'.$row["description"].'</span></a></div>';

				echo '<div style="display:inline-block;" class="info-text">';
					echo 'ID:<span style="padding:0px 8px 0 3px;">'.$row["id"].'</span>';
					echo 'Оплата:<span style="padding:0px 8px 0 3px;">'.my_num_format($row["price_adv"], 6, ".", "", 2).' руб.</span>';
					echo 'Посещений:<span id="adv-stat-'.$row["id"].'" style="padding:0px 8px 0 3px;">'.$row["cnt_visits_now"].'</span>';
					echo 'Осталось:<span id="adv-totals-'.$row["id"].'" style="padding:0px 8px 0 3px;">'.$cnt_totals.'</span>';
				echo '</div>';

				echo '<div style="display:inline-block; float:right; margin-top:-8px;">';
					echo '<span onClick="FuncAdvCab('.$row["id"].', \'confirm-del\', \''.$ads.'\', false, \''.$token_conf_del.'\', \'modal\');" class="adv-dell" title="Удалить площадку"></span>';
					echo '<span onClick="location.href = \'?ads='.$ads.'&op=edit&id='.$row["id"].'\';" class="adv-edit" title="Редактировать площадку"></span>';
					if($row["cnt_visits_now"]>0) echo '<span id="adv-erase-'.$row["id"].'" onClick="FuncAdvCab('.$row["id"].', \'confirm-erase\', \''.$ads.'\', false, \''.$token_conf_erase.'\', \'modal\');" class="adv-erase" title="Сброс статистики"></span>';
					echo '<span onClick="FuncAdvCab('.$row["id"].', \'stat-adv\', \''.$ads.'\', false, \''.$token_stat_adv.'\', \'modal\', \'История просмотров ссылки ID:'.$row["id"].'\', 550);" class="adv-statistics" title="Статистика просмотров"></span>';
					if(isset($position) && $position>0 && $position<100) {
						echo '<span id="adv-up-'.$row["id"].'" onClick="FuncAdvCab('.$row["id"].', \'info-up\', \''.$ads.'\', false, \''.$token_info_up.'\');" class="adv-down" title="Позиция ссылки в общем списке выдачи: '.$position.'">'.$position.'</span>';
					}else{
						echo '<span id="adv-up-'.$row["id"].'" onClick="FuncAdvCab('.$row["id"].', \'info-up\', \''.$ads.'\', false, \''.$token_info_up.'\');" class="adv-up" title="Поднять в списке">&uarr;</span>';
					}
					echo '<span onClick="FuncAdvCab('.$row["id"].', \'info-adv\', \''.$ads.'\', false, \''.$token_info_adv.'\');"  class="adv-info" title="Посмотреть подробное описание"></span>';
				echo '</div>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				echo '<span id="adv-bal-'.$row["id"].'" onClick="FuncAdvCab('.$row["id"].', \'info-bal\', \''.$ads.'\', false, \''.$token_info_bal.'\');" class="add-money'.(($row["balance"]==0) ? "-no" : false).'" title="Пополнить рекламный бюджет">'.($row["balance"]==0 ? "Пополнить" : my_num_format($row["balance"], 4, ".", "`", 2)).'</span>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="tr-info-'.$row["id"].'" class="tr-info" style="display:none;">';
			echo '<td align="center" colspan="3" id="text-info-'.$row["id"].'" class="ext-text"></td>';
		echo '</tr>';
	}

	echo '</tbody>';
	echo '</table>';
}else{
	echo '<div class="msg-w" style="margin-bottom:0px;">У вас нет своих размещённых оплачиваемых посещений</div>';
}
$sql->free();
$mysqli->close();

echo '<div align="center" style="margin:20px auto 5px;">';
	//echo '<a class="sd_sub big green" href="/advertise.php?ads='.$ads.'" style="width:120px;">Добавить ссылку</a>';
	echo '<span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads='.$ads.'\'">Добавить ссылку</span>';
echo '</div>';

?>