<?php
$pagetitle = "Свободные пользователи";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}

$page_free_users = (isset($_GET["page"]) && limpiar($_GET["page"])!=false) ? limpiar($_GET["page"]) : false;
?>

<script type="text/javascript" language="JavaScript">
	var tm;
	var Page = getUrlVars()["page"] ? getUrlVars()["page"] : (getCookie("fu_page") ? getCookie("fu_page") : <?php echo ($page_free_users!=false ? $page_free_users : 0);?>);
	var Link = window.location.pathname.replace(/[\/]/g, "");

	function HideMsg(id, timer) {
	        clearTimeout(tm);
		tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
		return false;
	}

	function setCookie(name, value, path, domain, secure, expires) {
		document.cookie= name + "=" + escape(value) + ((expires) ? "; expires=" + expires.toGMTString() : "") + ((path) ? "; path=" + path : "") + ((domain) ? "; domain=" + domain : "") + ((secure) ? "; secure" : "");
	}

	function getCookie(name) {
		var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"))
		return matches ? decodeURIComponent(matches[1]) : undefined 
	}

	function ShowHideBlock(id) {
		if($("#adv-title"+id).attr("class") == "adv-title-open") {
			$("#adv-title"+id).attr("class", "adv-title-close");
			if(id=="-info") setCookie("fu_spoiler", "0", "", location.hostname);
			if(id=="-info-sh") setCookie("fu_spoiler_sh", "0", "", location.hostname);
		} else {
			$("#adv-title"+id).attr("class", "adv-title-open")
			if(id=="-info") setCookie("fu_spoiler", "1", "", location.hostname);
			if(id=="-info-sh") setCookie("fu_spoiler_sh", "1", "", location.hostname);
		}
		$("#adv-block"+id).slideToggle("slow");
	}

	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars ? vars : DefaultPage;
	}

	function SortFU() {
		var sort_param = $.trim($("#sort_param").val());
		setCookie("sort_param", sort_param, "", location.hostname);
		LoadPage(1, false, true);
		window.history.replaceState(null, null, Link+"&page=1");
		return false;
	}

	function LoadPage(page, top, state) {
		$("#info-msg-page").html("").hide();

		$.ajax({
			type: "POST", url: "ajax/ajax_free_users.php", 
			data: {'op':'FreeUsers', 'page':page}, 
			dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").hide();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-page").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
			},
			beforeSend: function() { $("#loading").show(); }, 
			success: function(data) {
				$("#loading").hide();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if(!state) window.history.pushState(null, null, Link+"?page="+page);
				if(state && page && page!=1) window.history.replaceState(null, null, Link+"?page="+page);
				//if(!top) $(window).scrollTop(0);
				//setCookie("fu_page", page, location.hostname);

				if (result == "OK") {
					$("#FreeUsers").html(message).show();
					return false;
				} else {
					$("#FreeUsers").html('<span class="msg-error">'+message+'</span>').show();
					return false;
				}
			}
		});
	}

	function ShowHideList() {
		$.ajax({
			type: "POST", url: "ajax/ajax_free_users.php", 
			data: {'op':'ShowHideList'}, 
			dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").hide();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-page").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
			},
			beforeSend: function() { $("#loading").show(); }, 
			success: function(data) {
				$("#loading").hide();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#InfoSHList").html(message).show();
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}


	$(document).ready(function() {
		LoadPage(Page, false, true);
	})

	window.addEventListener("popstate", function(){
		LoadPage(getUrlVars()["page"], false, true);
	}, false);

</script><?php


echo '<div id="InfoFreeUsers" style="display:block; align:justify; margin-bottom:15px;">';
	echo '<span id="adv-title-info" class="adv-title-'.((isset($_COOKIE["fu_spoiler"]) && filter_var($_COOKIE["fu_spoiler"], FILTER_VALIDATE_INT)) ? "open" : "close").'" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:0px;">Свободные пользователи - что это?</span>';
	echo '<div id="adv-block-info" style="display:'.((isset($_COOKIE["fu_spoiler"]) && filter_var($_COOKIE["fu_spoiler"], FILTER_VALIDATE_INT)) ? "block" : "none").'; padding:5px 7px 10px 7px; text-align:justify; background-color:#F0F8FF;">';
		echo 'Свободный пользователь на <b style="color:#3A5FCD">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; это пользователь который остался без рефера не по своей вине, или не разбирается в системе и затрудняется выбрать себе рефера, но можно помочь ему в этом.<br>';
		echo 'Вы можете написать ему, и попробовать договориться о вступлении в вашу команду на различных условиях. ';
		echo 'Например, можно договорится на добровольное присоединение через вашу стену, или за вознаграждение через задания!<br>';
		echo 'После того как, произойдет процедура присоединения, вы получите системное сообщение по внутренней почте, а так же присоединившийся пользователь появится у вас в списке рефералов первого уровня, соответственно все его рефералы появятся в вашем списке.<br><br>';
		echo '<div style="color:green; font-style:italic;">Убедительная просьба, обращаться к пользователям с большим уважением, не оскорблять, не спамить.</div>';
	echo '</div>';
echo '</div>';
/*echo '<div class="text-justify">';
echo '<img src="/img/ukrah/601351953.gif" alt="" title="" style="float: left;margin-top: 40px; width="140px" />';
echo '</div><br>';*/
echo '<div id="FreeUsers" style="display:none;"></div>';
echo '<div id="info-msg-page" style="display:none;"></div>';

include(ROOT_DIR."/footer.php");
?>