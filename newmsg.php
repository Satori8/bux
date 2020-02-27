<?php
$pagetitle="Новое сообщение";
include('header.php');
require_once('.zsecurity.php');
?>

<script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

</script>

<?php
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	require('config.php');

	$mail_count_1 = 100;
	$mail_count_2 = 3000;

	//echo '<a href="/inbox.php"><b>Входящие сообщения</b></a> | <a href="/outbox.php"><b>Исходящие сообщения</b></a> | <a href="'.$_SERVER["PHP_SELF"].'" class="block1"><b>Новое сообщение</b></a><br /><br />';
        echo '<div align="center">';
        echo '<a class="filterline" href="/inbox.php">Входящие сообщения</a>'; 
   echo '<a class="filterline" href="/outbox.php">Исходящие сообщения</a>';
  echo '<a class="filterlineactive" href="/newmsg.php">Новое сообщение</a>';
echo '</div>';
  echo '<br/>';
	echo '<span id="adv-title-bl" class="adv-title-open_1" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #ab0606; text-align:left; padding-left:50px; margin-bottom:2px;">Запрещено!</span>';
		echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                         echo '<div style="color:#C80000; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f3e2cb" align="left"> ';
		echo '- Отправлять сообщения содержащие ненормативную лексику;<br>';
		echo '- Оскорблять других пользователей проекта;<br>';
		echo '- Отправлять сообщения рекламного характера;<br>';
		echo '- Массовая отправка сообщений одного содержания.';
	echo '</div>';
echo '</div>';
echo '<br/>';

	function limpiarez($mensaje){
		$mensaje = trim($mensaje);
		$mensaje = str_replace("?","&#063;",$mensaje);
		$mensaje = str_replace(">","&#062;",$mensaje);
		$mensaje = str_replace("<","&#060;",$mensaje);
		$mensaje = str_replace("'","&#039;",$mensaje);
		$mensaje = str_replace("$","&#036;",$mensaje);
		$mensaje = str_replace('"',"&#034;",$mensaje);
		return $mensaje;
	}

	if(isset($_GET["name"]) && $_GET["name"]!="") {
		$name = (isset($_GET["name"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_GET["name"]))) ? uc($_GET["name"]) : false;
	}else{
		$name = (isset($_POST["name"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_POST["name"]))) ? uc($_POST["name"]) : false;
	}
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]),$mail_count_1) : false;
	$message = (isset($_POST["message"])) ? limitatexto(limpiarez($_POST["message"]),$mail_count_2) : false;

	require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');

  $mail_out = new mailPHP();
  
	if(count($_POST)>0) {
		$check_user = mysql_query("SELECT `id`,`email` FROM `tb_users` WHERE `username`='$name'");
		$exist_user = mysql_num_rows($check_user);
		

		if($name==false) {
			echo '<span class="msg-error">Укажите логин получателя сообщения!</span><br>';
		}elseif(strtolower($name)==strtolower($username)) {
			echo '<span class="msg-error">Вы не можете отправлять сообщение самому себе!</span><br>';
		}elseif($exist_user<1) {
			echo '<span class="msg-error">Пользователя <u>'.$name.'</u> нет в системе!</span><br>';
		}elseif($subject==false) {
			echo '<span class="msg-error">Не заполнено поле тема сообщения!</span><br>';
		}elseif($message==false) {
			echo '<span class="msg-error">Не заполнено поле текст сообщения!</span><br>';
		}elseif( isset($_SESSION["lastmesstime"]) && intval(limpiarez($_SESSION["lastmesstime"]))>time() ) {
			echo '<span class="msg-error">Вы только что воспользовались функцией отправки сообщения, подождите '.($_SESSION["lastmesstime"]-time()).' сек.</span><br>';

			$name = false;
			$username = false;
			$subject = false;
			$message = false;
		}else{
			$_SESSION["lastmesstime"] = (time() + 60);

			mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('$name','$username','$subject','$message','0','".time()."','$ip')") or die(mysql_error());

			$sql_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_mail_in`");
			$last_id = mysql_result($sql_id,0,0);

			mysql_query("INSERT INTO tb_mail_out (`ident`,`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('$last_id','$name','$username','$subject','$message','0','".time()."','$ip')") or die(mysql_error());

			$sql_mes = mysql_query("SELECT `id`,`email`,`email_sent` FROM `tb_users` WHERE `username`='$name' AND `email_sent`='1'");
			if(mysql_num_rows($sql_mes)>0) {
			$row_mes = mysql_fetch_array($sql_mes);
			$email_tab = $row_mes["email"];
			$email_sent = $row_mes["email_sent"];
			
			$sqla = mysql_query("SELECT avatar, username FROM `tb_users` WHERE `username`='$username'");
				$rowa = mysql_fetch_assoc($sqla);
				$avatar = $rowa["avatar"];
		
		$var = array('{LOGIN}', '{AVATAR}');
    $zamena = array($username, $avatar);
    $msgtext = str_replace($var, $zamena, $email_temp["mess"]);

		$mail_error = $mail_out->send($email_tab,$name,iconv("CP1251", "UTF-8", 'Информационное сообщение системы '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext));
		}
			echo '<span class="msg-ok">Сообщение успешно отправлено пользователю <u>'.$name.'</u>!</span><br>';			
					
	    
	}
}
	?>

	<script type="text/javascript" language="JavaScript"> 
		function gebi(id){
			return document.getElementById(id)
		}

		function ltrim(str) {
			var ptrn = /\s*((\S+\s*)*)/;
			return str.replace(ptrn, "$1");
		}

		function rtrim(str) {
			var ptrn = /((\s*\S+)*)\s*/;
			return str.replace(ptrn, "$1");
		}

		function trim(str) {
			return ltrim(rtrim(str));
		}

		function SbmFormB() {
		        regexp = /^[a-zA-Z0-9\-_-]{3,25}$/;
		        var users = trim(document.forms["formmls"].name.value);

			arrayElem = document.forms["formmls"];
			var col=0;

			for (var i=0;i<arrayElem.length;i++){
				if ((document.forms["formmls"].name.value == '')) {
					alert('Вы не указали Логин получателя сообщения');
					arrayElem[i+0].style.background = "#FFDBDB";
					arrayElem[i+0].focus();
					return false;
				}else{
					arrayElem[i+0].style.background = "#FFFFFF";
				}
			        if(([regexp.test(users)])=="false") {
					alert('Вы не верно указали Логин получателя сообщения');
					arrayElem[i+0].style.background = "#FFDBDB";
					arrayElem[i+0].focus();
					return false;
				}else{
					arrayElem[i+0].style.background = "#FFFFFF";
				}
				if ((document.forms["formmls"].subject.value == '')) {
					alert('Вы не указали тему сообщения');
					arrayElem[i+1].style.background = "#FFDBDB";
					arrayElem[i+1].focus();
					return false;
				}else{
					arrayElem[i+1].style.background = "#FFFFFF";
				}
				if ((document.forms["formmls"].message.value == '')) {
					alert('Вы не указали текст сообщения');
					arrayElem[i+2].style.background = "#FFDBDB";
					arrayElem[i+2].focus();
					return false;
				}else{
					arrayElem[i+2].style.background = "#FFFFFF";
				}
			}
			document.forms["formmls"].submit();
			return true;
		}

		function messchange() {
			var subject = gebi('subject').value;
			var mess = gebi('mess').value;

			if(subject.length > <?=$mail_count_1;?>) {
				gebi('subject').value = subject.substr(0,<?=$mail_count_1;?>);
			}
			if(mess.length > <?=$mail_count_2;?>) {
				gebi('mess').value = mess.substr(0,<?=$mail_count_2;?>);
			}
			gebi('count1').innerHTML = 'Осталось <b>'+(<?=$mail_count_1;?>-subject.length)+'</b> символов';
			gebi('count2').innerHTML = 'Осталось <b>'+(<?=$mail_count_2;?>-mess.length)+'</b> символов';
		}
		</script>
		
<script type="text/javascript" language="JavaScript"> 		
		function InsertTags(text1, text2, mess) {
	var textarea = $(this).attr(mess);
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

function SetSmile(smile) {
	InsertTags(smile,'');
}
	</script>
<script>
function shSmiles() {
if (document.getElementById('smile_div').style.display=='none') {document.getElementById('smile_div').style.display='block';} else {document.getElementById('smile_div').style.display='none';}
}
</script>

	<?php
	
	$smileskod = array(
	':D',':)',':(',':heap:',':ooi:',':so:',':surp:',':ag:',':ir:',':oops:',':P',':cry:',':rage:',':B',':roll:',':wink:',':yes:',':bot:',':z)',
	':arrow:',':vip:',':Heppy:',':think:',':bye:',':roul:',':pst:',':o:',':closed:',':cens:',':tani:',':appl:',':idnk:',':sing:',':shock:',':tgu:',
	':res:',':alc:',':lam:',':box:',':tom:',':lol:',':vill:',':idea:',':oops:',':E:',':sex:',':horns:',':love:',':poz:',':roza:',':meg:',':dj:',
	':rul:',':offln:',':sp:',':stapp:',':girl:',':heart:',':kiss:',':spam:',':party:',':ser:',':eam:',':gift:',':adore:',':pie:',':egg:',':cnrt:',
	':oftop:',':foo:',':mob:',':hoo:',':tog:',':pnk:',':pati:',':-({|=:',':haaw:',':angel:',':kil:',':died:',':cof:',':fruit:',':tease:',':evil:',
	':exc:',':niah:',':Head:',':gl:',':granat:',':gans:',':user:',':ny:',':mvol:',':boat:',':phone:',':cop:',':smok:',':bic:',':ban:',':bar:'
);

	if( isset($_GET["re"])) {
		$reid = ( isset($_GET["re"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_GET["re"])) && intval(limpiarez(trim($_GET["re"]))) >= 0 ) ? intval(limpiarez(trim($_GET["re"]))) : false;

		if($reid!=false) {
			$sql = mysql_query("SELECT `nameout`,`subject`,`message` FROM `tb_mail_in` WHERE `id`='$reid' AND `namein`='$username'");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_assoc($sql);

				$name = $row["nameout"];
				$subject = $row["subject"];
				$message = $row["message"];
				$message = "\n\n\n----- Original Message -----\n$message";
				$subject = "Re: $subject";
				$subject = str_replace("Re: Re: ","Re: ",$subject);
				$readonly = "readonly";
			}
		}
	}

	echo '<form method="post" action="" id="newform" name="formmls" onsubmit="return SbmFormB(); return false;">';
	//echo '<form action="" method="post">';
	echo '<table class="tables">';
	echo '<thead><tr><th colspan="2" class="top">Форма отправки сообщения</th></tr></thead>';
	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Логин:</b></td>';
		echo '<td><input type="text" name="name" value="'.$name.'" maxlength="25" class="ok"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Тема сообщения:</b></td>';
		echo '<td><input type="text" id="subject" name="subject" value="'.$subject.'" maxlength="'.$mail_count_1.'" onChange="messchange();" onKeyUp="messchange();" class="ok"><div align="right" id="count1" style="float:right; display:block; color:#696969; padding:0px; margin:0px;"></div></td>';
	echo '</tr>';
	echo '<tr>';
							   echo '<td align="left" colspan="2" style="">';
			echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'message\'); return false;">Ж</span>';
			echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'message\'); return false;">К</span>';
			echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'message\'); return false;">Ч</span>';
			echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'message\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'message\'); return false;">URL</span>';
			echo '<span class="bbc-url" style="float:left;" title="Добавить изображение" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'message\'); return false;">IMG</span>';
		    //echo '<span class="bbc-smile" style="float:left;" title="Вставить смайлик" onclick="shSmiles(); return false;">: )</span>';
			echo '<span id="count2" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">Осталось символов: 3000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
			
		echo '<textarea name="message" id="message" class="ok" onKeyup="descchange(\'1\', this, \'3000\');" onKeydown="descchange(\'1\', this, \'3000\');" onClick="descchange(\'1\', this, \'3000\');">'.$message.'</textarea>';
		
		echo '<tr><td class="ct" colspan="2">';

echo '<div name="smile_div" id="smile_div" style="display:none;">';

		for($i=0; $i<79; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

		for($i=80; $i<99; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

echo '</div>';

		echo '</td></tr>';
                echo '</div>';
                echo '</td>';
	echo '</tr>';
	echo '<tr><th colspan="2" class="top" align="center"><input type="submit" class="proc-btn" style="float:none;" value="Отправить"></th></tr>';
	echo '</table>';
	echo '</form>';
}
?>

<script language="JavaScript">messchange();</script>

<?php include('footer.php');?>