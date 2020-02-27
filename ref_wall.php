<?php
$pagetitle = "Реф-Стена";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

function CntTxtRW($count, $text1, $text2, $text3) {
	if($count>0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "<b>$count</b> $text3";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "<b>$count</b> $text1"; break;
				case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
			}
		}
	}
}

?><script type="text/javascript">
var tm;
var LoadBlock = false;
var Link = window.location.pathname.replace(/[\/]/g, "");
if(Link != "ref_wall") window.history.replaceState(null, null, "ref_wall");

function HideMsg(id, timer) {
	clearTimeout(tm);
	tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
	return false;
}

function ShowHideBlock(id) {
	if($("#cat-title-"+id).attr("class") == "cat-title-open") {
		$("#cat-title-"+id).attr("class", "cat-title-close");
	} else {
		$("#cat-title-"+id).attr("class", "cat-title-open")
	}
	$("#cat-block-"+id).slideToggle(400);
}

<?php
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
?>

function LoadForm(id, uid, op, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", cache: false, url: "ajax/ajax_ref_wall.php", data: {'id':id, 'uid':uid, 'op':op, 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('ОШИБКА AJAX: ['+error["statusText"]+':'+error["status"]+'] '+error["rText"]);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false;
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#LoadModal").html(message).show();
					StartModalWall(true, false);
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
	return false;
}

function SetRW(op, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", cache: false, url: "ajax/ajax_ref_wall.php", data: {'op':op, 'comment':$.trim($("#comment").val()), 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('ОШИБКА AJAX: ['+error["statusText"]+':'+error["status"]+'] '+error["rText"]);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false;
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");

					$("#RefWall").html(message).show();

					return false;
				} else {
					$(".box-modal-content").html(message).hide().slideDown(300);
					return false;
				}
			}
		});
	}
	return false;
}

function AddRefRW(id, uid, op, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", cache: false, url: "ajax/ajax_ref_wall.php", data: {'id':id, 'uid':uid, 'op':op, 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('ОШИБКА AJAX: ['+error["statusText"]+':'+error["status"]+'] '+error["rText"]);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false;
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$(".box-modal-content").html(message).hide().slideDown(300);
					if($("div").is(".rw_addsub")) $(".rw_addsub").css("height", "5px").html("");
					setTimeout(function(){
						if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");
					},2000);
					//LoadRW();
					return false;
				} else {
					$(".box-modal-content").html(message).hide().slideDown(300);
					return false;
				}
			}
		});
	}
	return false;
}

function StartModalWall(closeOnEsc, closeOnOverlayClick) {
	$("#LoadModal").modalpopup({
		closeOnEsc: closeOnEsc, closeOnOverlayClick: closeOnOverlayClick, beforeClose: function(data, el) {$("#LoadModal").html('').hide(); return true;}
	});
}
<?php
}
?>

function LoadRW() {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", cache: false, url: "ajax/ajax_ref_wall.php", data: {'op':'LoadRW'}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('ОШИБКА AJAX: ['+error["statusText"]+':'+error["status"]+'] '+error["rText"]);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false;
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#RefWall").html(message).show();
					return false;
				} else {
					$("#RefWall").html('<span class="msg-w">Ошибка загрузки!</span>').show();
					return false;
				}
			}
		});
	}
	return false;
}

$(document).ready(function(){
	LoadRW();
});
</script><?php

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cena' AND `howmany`='1'") or die(mysql_error());
$ref_wall_cena = mysql_num_rows($sql)>0 ? number_format(mysql_result($sql,0,0), 2, ".", "") : 30.00;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cnt_all' AND `howmany`='1'") or die(mysql_error());
$ref_wall_cnt_all = mysql_num_rows($sql)>0 ? number_format(mysql_result($sql,0,0), 0, ".", "") : 15;

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='ref_wall_cnt_reg' AND `howmany`='1'") or die(mysql_error());
$ref_wall_cnt_reg = mysql_num_rows($sql)>0 ? number_format(mysql_result($sql,0,0), 0, ".", "") : 10;

echo '<div id="cat-title-rw" class="cat-title-close" onClick="ShowHideBlock(\'rw\'); return false;" style="color:#C41300; margin-bottom:0px; margin-top:7px;">Стена Рефереров проекта '.strtoupper($_SERVER["HTTP_HOST"]).'</div>';
echo '<div id="cat-block-rw" style="padding: 5px 7px;text-align: justify;font-size: 12px;background-color: rgb(247, 228, 202);border-bottom: 1px solid rgb(171, 6, 6);">';
	echo '<div style="margin-bottom:8px;">';
		echo '<b>Реф-Стена</b> на <span class="text-green" style="font-weight:bold;">'.strtoupper(array_shift(explode(".", $_SERVER["HTTP_HOST"]))).'</span> &mdash; отличная возможность для привлечения пользователей в свою команду, которые не имеют реферера по той или иной причине. ';
		echo 'Всего на Реф-Стене одновременно доступно '.CntTxtRW($ref_wall_cnt_all, "место", "места", "мест").'. После размещения Вы будете находится на первом месте, каждый последующий разместившийся будет сдвигать всех остальных на одну позицию ниже.';
	echo '</div>';
	echo '<div style="margin-bottom:8px;">';
		echo 'Авторизованные пользователи, не имея реферера, смогут присоединиться к Вам кликнув на кнопку "Присоединиться", либо кликнув по аватару перейти на Вашу стену и присоединиться со стены.';
	echo '</div>';
	echo '<div style="margin-bottom:8px;">';
		echo 'Аватары <b>'.$ref_wall_cnt_reg.'</b> рефереров занимающие первые позиции на Реф-Стене выводятся на страницу регистрации нового пользователя, также ниже будет расположена ссылка где будет возможность просмотреть всех пользователей на Реф-Стене.';
	echo '</div>';
	echo '<div style="margin-bottom:8px;">';
		echo 'Пользователю, который регистрируется не по реферальной ссылке, будет предложено выбрать себе реферера. Пользователь кликнувший по Вашей аватарке в форме регистрации будет указан в качестве вашего реферала.';
	echo '</div>';
	echo '<div style="margin-bottom:8px;">';
		echo 'Одновременно вы можете занять неограниченное количество мест. Стоимость размещения аватара на Реф-Стене составляет <b>'.$ref_wall_cena.'</b> руб. Оплата производится с рекламного счета.';
	echo '</div>';
echo '</div>';

echo '<div style="display:block; text-align:center; padding:15px 0px;">';
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$security_key = "Ulnli^&*@if%Ylkj630n(*7p0UFn#*hglkj?";
		$token_setrw = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."F_SetRW".$security_key));
		echo '<span class="sd_sub orange" onClick="LoadForm(false, false, \'FormSetRW\', \''.$token_setrw.'\');">Занять место на реф-стене</span>';
	}
echo '</div>';

echo '<div id="RefWall" class="ref-walls"></div>';

include(ROOT_DIR."/footer.php");
?>