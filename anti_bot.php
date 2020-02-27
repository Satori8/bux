<?php
if(!DEFINED("ANTI_BOT")) {exit("Hacking attempt!");}
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
//require_once(ROOT_DIR."/recaptcha/config_recaptcha.php");
?>
<script type="text/javascript" language="JavaScript">
	var tm;

	function HideMsg(id, timer) {
	        clearTimeout(tm);
		tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
		return false;
	}

	function AntiBot() {
		var captcha_str = $.trim($("#captcha_str").val());
		$("#info-msg-ab").html("").hide();

		if (captcha_str == "") {
			$("#info-msg-ab").html('<span class="msg-error">Необходимо подтвердить, что Вы не робот!</span>').slideToggle("fast");
			HideMsg("info-msg-ab", 2000);
			return false;
		} else {
			$.ajax({
				type: "POST", url: "ajax/ajax_antibot.php?rnd="+Math.random(), data: {'captcha_str':captcha_str}, dataType: 'json', 
				error: function() {reCaptcha("Captcha"); $("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
				beforeSend: function() { $("#loading").slideToggle(); }, 
				success: function(data) {
					$("#loading").slideToggle();
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					reCaptcha("Captcha");

					if(result == "OK") {
						$("#AntiBot").remove();
						location.replace("<?php echo (isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"]);?>");
						return false;
					} else {
						$("#info-msg-ab").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
						HideMsg("info-msg-ab", 5000);
						return false;
					}
				}
			});
		}
	}

	function reCaptcha(id) {
		$.trim($("#captcha_str").val(""));
		$("#"+id).attr("src", "/captcha_new/?<?=session_name();?>=<?=session_id();?>&sid="+Math.random());
		return false;
	}
</script><?php

echo '<div id="AntiBot" align="center" style="display:block;margin:3px;padding-top:10px;color: #ff7f50;background: #fbece7;border: 3px double #ff7f50;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;box-shadow: inset 0 0 15px #1e90ff;-moz-box-shadow:inset 0 0 15px #294a9f;-webkit-box-shadow: inset 0 0 15px #b14922;text-shadow:0 1px 5px rgba(0,0,0,0.156);font-weight:bold;">';
	echo '<h2 class="sp">Для продолжения необходимо подтвердить что Вы не робот!</h2>';
	echo '<div id="newform" align="center" style="height:55px; width:350px; margin:0px auto; padding:10px 0px;">';
		echo '<span style="font-weight:bold; font-size:15px; margin-right:10px;">Решите задачу</span>';
		echo '<div class="block-captcha"><img id="Captcha" onClick="reCaptcha($(this).attr(\'id\')); return false;" src="/captcha_new/?'.session_name().'='.session_id().'&sid='.mt_rand().'" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!" /></div>';
		echo '<img src="/style/icon-serf/icon-ravno.png" alt="равно" title="равно" border="0" align="absmiddle" />';
		echo '<input class="ok12" style="width:50px; height:28px; text-align:center;" type="text" id="captcha_str" value="" maxlength="1"  placeholder="" />';
	echo '</div>';
	echo '<div id="Sub" class="proc-btn" onClick="AntiBot();" style="">Продолжить</div><br /><br />';
	echo '<div id="info-msg-ab" style="display:none; padding-top:10px;"></div>';
echo '</div>';

?>