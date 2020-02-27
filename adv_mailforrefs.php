<?php
$pagetitle="Рассылка сообщения пользователям проекта";
include('header.php');
require_once('.zsecurity.php');
require_once('./merchant/func_mysql.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">Ошибка! Для доступа к этой странице необходимо авторизоваться!</fieldset>';
	include('footer.php');
	exit();
}else{
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
/*
function bbcod($input, $sql=false, $bbc=false) {
    $input = htmlspecialchars($input, ENT_QUOTES,'WINDOWS-1251');
    if(get_magic_quotes_gpc ())
    {
        $input = stripslashes ($input);
    }
    if ($sql)
    {
        $input = mysql_real_escape_string ($input);
    }
    $input = strip_tags($input);

    if ($sql)
    {
        $input=str_replace ("\\r\\n\\r\\n\\r\\n\\r\\n","<br><br>", $input);
        $input=str_replace ("\\r\\n\\r\\n\\r\\n","<br><br>", $input);
        $input=str_replace ("\\r\\n\\r\\n","<br><br>", $input);
        $input=str_replace ("\\r\\n","<br>", $input);
        $input=str_replace ("\\n"," ", $input);
        $input=str_replace ("\\r","", $input);
    }

    if($bbc){
       $find = array ("'\[justify\]'i", "'\[/justify\]'i", "'\[right\]'i", "'\[/right\]'i", "'\[left\]'i", "'\[/left\]'i", "'\[url\]'i", "'\[/url\]'i", "'\[center\]'i", "'\[/center\]'i", "'\[img\]'i", "'\[/img\]'i","'\[s\]'i", "'\[/s\]'i", "'\[b\]'i", "'\[/b\]'i", "'\[i\]'i", "'\[/i\]'i", "'\[u\]'i", "'\[/u\]'i", "'\[quote\]'si", "'\[quote=(.+?)\]'si", "'\[/quote\]'si");
 
	   $replace = array ("<p align='justify'>", "</p>", "<p align='right'>", "</p>", "<p align='left'>", "</p>", "<a href=>", "</a>", "<center>", "</center>", "<img src=", ">", "<s>", "</s>","<b>", "</b>", "<i>", "</i>", "<u>", "</u>", "<div class=\"quote\">", "<div class=\"title_quote\"><b></b> \\1</div><div class=\"quote\">", "</div>");

      $input = preg_replace( $find, $replace, $input );
    }

    return $input;
}
*/
	$username = (isset($_SESSION["userLog"])) ? uc($_SESSION["userLog"]) : false;

	require('config.php');

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_count' AND `howmany`='1'");
	$mail_user_count_1 = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_count' AND `howmany`='2'");
	$mail_user_count_2 = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user' AND `howmany`='1'");
	$cena_mail_user = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_user_sk' AND `howmany`='1'");
	$cena_mail_user_sk = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `id` FROM `tb_users`");
	$all_referals = mysql_num_rows($sql);

	$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-14*24*60*60))."'");
	$active_referals = mysql_num_rows($sql);

	$cena_1 = round(($all_referals*$cena_mail_user*(100-$cena_mail_user_sk)/100),2);
	$cena_2 = round(($active_referals*$cena_mail_user),2);

	if(count($_POST)>0 && isset($_POST["message"])) {
		$types = (isset($_POST["types"]) && preg_match("|^[\d]$|", trim($_POST["types"]))) ? intval(limpiarez(trim($_POST["types"]))) : false;
		$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]),$mail_user_count_1) : false;
		$message = (isset($_POST["message"])) ? limitatexto(limpiarez($_POST["message"]),$mail_user_count_2) : false;

		if($subject==false) $subject="Оплаченная рассылка от пользователя $username";

		if($types==1) {
			$cena=$cena_1;
			$types_txt="всем";

			$sql = mysql_query("SELECT `username` FROM `tb_users`");

		}elseif($types==2) {
			$cena = $cena_2;
			$types_txt="активным";

			$sql = mysql_query("SELECT `username` FROM `tb_users` WHERE `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-14*24*60*60))."'");
		}else{
			echo '<fieldset class="errorp">Ошибка! Необходимо выбрать - кому отправлять сообщения!</fieldset>';
			include('footer.php');
			exit();
		}

		if($message==false) {
			echo '<fieldset class="errorp">Ошибка! Необходимо ввести текст сообщения!</fieldset>';
			include('footer.php');
			exit();
		}elseif($money_rb<$cena) {
			echo '<fieldset class="errorp">Ошибка! Недостаточно средств на рекламном счету для отправки сообщений!</fieldset>';
			include('footer.php');
			exit();
		}elseif(mysql_num_rows($sql)>0) {
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena' WHERE `username`='$username'");
			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$cena',  'Оплата за рассылку ".$types_txt." пользователям','Списано','rashod')") or die(mysql_error());

			stat_pay('mail_user', $cena);
					invest_stat($cena, 7);
					
			while($row = mysql_fetch_array($sql)) {
                        if($row['username']!=$username){
				mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('".$row["username"]."','$username','$subject','$message','0','".time()."','$ip')");
			        }
                         }

			echo '<fieldset class="okp">Рассылка успешно отправлена '.$types_txt.' пользователям!</fieldset>';
		}else{
			echo '<fieldset class="errorp">Ошибка! Запрос не обработан!</fieldset>';
			include('footer.php');
			exit();
		}
	}
	?>

	<script type="text/javascript" language="JavaScript"> 
		function gebi(id){
			return document.getElementById(id)
		}

		function messchange() {
			var subject = gebi('subject').value;
			var mess = gebi('mess').value;

			if(subject.length > <?=$mail_user_count_1;?>) {
				gebi('subject').value = subject.substr(0,<?=$mail_user_count_1;?>);
			}
			if(mess.length > <?=$mail_user_count_2;?>) {
				gebi('mess').value = mess.substr(0,<?=$mail_user_count_2;?>);
			}
			gebi('count1').innerHTML = 'Осталось <b>'+(<?=$mail_user_count_1;?>-subject.length)+'</b> символов';
			gebi('count2').innerHTML = 'Осталось <b>'+(<?=$mail_user_count_2;?>-mess.length)+'</b> символов';
		}
	</script>

<script>
function color(){
 var blo = document.getElementById('color').style.display;
 if(blo == 'none'){
   document.getElementById('color').style.display = 'block';
 }else{
   document.getElementById('color').style.display = 'none';
 }
}

function insert_comm(open, close, no_focus)
{
  msgfield = (document.all) ? document.all.newform : document.forms['newform']['mess'];
  if (document.selection && document.selection.createRange)
  {
    if (no_focus != '1' ) msgfield.focus();
	sel = document.selection.createRange();
	sel.text = open + sel.text + close;
	if (no_focus != '1' ) msgfield.focus();
	}else if (msgfield.selectionStart || msgfield.selectionStart == '0'){
	  var startPos = msgfield.selectionStart;
	  var endPos = msgfield.selectionEnd;
	  msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
	  msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
	  if (no_focus != '1' ) msgfield.focus();
	    }else{
		msgfield.value += open + close;
		if (no_focus != '1' ) msgfield.focus();
		}return;}

var txt_quote="";
function copy_txt() {
   txt_quote="";
  if (window.getSelection) {
     txt_quote = window.getSelection().toString();
  } else if (document.getSelection) {
    txt_quote = document.getSelection();
  } else if (document.selection) {
    txt_quote = document.selection.createRange().text;
  }
}
</script>

	<?php
	echo '<div style="color: #ab0606;font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6);padding:10px;margin:10px;background: #f9ead6;" align="left">';
	echo '<b>Платная рассылка пользователям:</b><br>цена за 1 письмо <b>'.$cena_mail_user.' руб.</b><br>Письмо приходит через внутреннюю почту системы.';
        echo '</div>';

	echo '<form method="post" action="" id="newform">';

	echo '<table class="tables">';
	echo '<thead><tr><th colspan="2" class="top">Форма отправки сообщения</th></tr></thead>';

	echo '<tr><td align="right"><input type="radio" name="types" value="1" checked="checked"></td><td>Всем пользователям ('.$all_referals.'), стоимость '.$cena_mail_user.' - <b>'.$cena_1.' руб.</b></td></tr>';
	echo '<tr><td align="right"><input type="radio" name="types" value="2"></td><td>Пользователям, которые заходили в течение последних двух недель ('.$active_referals.') - стоимость:  - <b>'.$cena_2.' руб.</b></td></tr>';

	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Тема сообщения:</b></td>';
		echo '<td width="100" nowrap="nowrap">Оплаченная рассылка от пользователя '.$username.'</td><div align="right" id="count1" style="float:right; display:block; color:#696969; padding:0px; margin:0px;"></div>';
	echo '</tr>';
	echo '</tr>';
                echo '<tr>';
		echo '<td colspan="2"><b>Текст сообщения &darr;</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="2">';
                                echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:insert_comm(\'[b]\',\'[/b]\',\' message\'); return false;">B</span>';
				echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:insert_comm(\'[i]\',\'[/i]\',\' message\'); return false;">i</span>';
                                echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:insert_comm(\'[u]\',\'[/u]\',\' message\'); return false;">Ч</span>';
				//echo '<span class="bbc-uline" title="Выделить подчёркиванием" onClick="javascript:insert_comm(\'[u]\',\'[/u]\',\' message\'); return false;">U</span>';
                                //echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:insert_comm(\'[s]\',\'[/s]\',\' message\'); return false;">ST</span>';
				echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:insert_comm(\'[left]\',\'[/left]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:insert_comm(\'[center]\',\'[/center]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:insert_comm(\'[right]\',\'[/right]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:insert_comm(\'[justify]\',\'[/justify]\',\' message\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:insert_comm(\'[url]\',\'[/url]\',\' message\'); return false;">URL</span>';
                                echo '<span class="bbc-url" style="float:left;" title="Добавить изображение" onClick="javascript:insert_comm(\'[img]\',\'[/img]\',\' message\'); return false;">IMG</span>';
                                echo '<div align="right" id="count2" style="float:right; display:block; color:#696969; padding:0px; margin:0px; margin-right:10px;"></div>';
                                 echo '<br>';
                               echo '<div style="display: block; clear:both; padding-top:4px">';
		echo '<textarea name="message" id="mess" onChange="messchange();" onKeyUp="messchange();" class="ok"></textarea><div align="right" id="count2" style="float:right; display:block; color:#696969; padding:0px; margin:0px; margin-right:10px;"></div>';
		//echo '<textarea name="message" id="mess" onChange="messchange();" onKeyUp="messchange();" class="ok"></textarea><div align="right" id="count2" style="float:right; display:block; color:#696969; padding:0px; margin:0px;"></div>';
             echo '</div>';
                echo '</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td colspan="2" align="center"><input type="submit" class="proc-btn" style="float:none;" value="Отправить сообщение"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
}
?>

<script language="JavaScript">messchange();</script>

<?php include('footer.php');?>