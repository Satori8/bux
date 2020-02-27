<?php
$pagetitle="Приветствие для новых рефералов";
include('header.php');
require_once('.zsecurity.php');

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">Ошибка! Для доступа необходимо авторизоваться!</fieldset>';
	include('footer.php');
	exit();
}elseif($username==false) {
	echo '<fieldset class="errorp">Ошибка! Для доступа необходимо авторизоваться!</fieldset>';
	include('footer.php');
	exit();
}else{
	require('config.php');

	function limpiarez($mensaje){
		$mensaje = htmlspecialchars(trim($mensaje));
		$mensaje = str_replace("'"," ",$mensaje);
		$mensaje = str_replace("`"," ",$mensaje);
		$mensaje = str_replace(";"," ",$mensaje);
		$mensaje = str_replace("$"," ",$mensaje);
		$mensaje = str_replace("<"," ",$mensaje);
		$mensaje = str_replace(">"," ",$mensaje);
		$mensaje = str_replace("&amp amp ","&",$mensaje);
		$mensaje = str_replace("http://http://","http://",$mensaje);
		return $mensaje;
	}


	if(count($_POST)>0 && isset($_POST["welcome_ref"])) {
		$welcome_ref = (isset($_POST["welcome_ref"])) ? limitatexto(limpiarez($_POST["welcome_ref"]),500) : false;

		//if($welcome_ref==false) {
		//	echo '<fieldset class="errorp">Ошибка! Необходимо ввести текст сообщения.</fieldset><br>';
		//	include('footer.php');
		//	exit();
		//}else{
			mysql_query("UPDATE `tb_users` SET `welcome_ref`='$welcome_ref' WHERE `username`='$username'") or die(mysql_error());
		//}
	}

	$sql_u = mysql_query("SELECT `welcome_ref` FROM `tb_users` WHERE `username`='$username'");
	$row_u = mysql_fetch_row($sql_u);
	$welcome_ref = $row_u["0"];
	?>

	<script type="text/javascript" language="JavaScript"> 
		function gebi(id){
			return document.getElementById(id)
		}

		function messchange() {
			var welc = gebi('welc').value;

			if(welc.length > 500) {
				gebi('welc').value = welc.substr(0,500);
			}
			gebi('count').innerHTML = 'Осталось <b>'+(500-welc.length)+'</b> символов';
		}
	</script>

	<?php

	echo '<h5 class="sp" style="padding-top:0px; margin-top:0px;">Информация</h5>';
	echo '<fieldset style="width:auto; text-align:justify; margin:0px 0px 0px 0px; padding:0px 0px 5px 0px; border:0px solid #CCC; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;">';
		echo 'С помощью данной формы Вы можете создать и установить приветственное сообщение для всех ваших потенциальных (новых) рефералов. Сообщение будет отправлено рефералу по внутренней почте автоматически, при его регистрации. Ваши реф-ссылки и баннеры для приглашения рефералов находятся <a href="/reflinks.php">здесь</a>.';
	echo '</fieldset><br>';

	if(trim($welcome_ref)!=false) {
		echo '<table class="tables">';
			echo '<thead><tr><th colspan="2" class="top">Сообщение для рефералов</th></tr></thead>';
			echo '<tr>';
				echo '<td width="130"><b>Тема сообщения:</b></td>';
				echo '<td>Приветствие от реферера '.$username.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Текст сообщения:</b></td>';
				echo '<td>'.$welcome_ref.'</td>';
			echo '</tr>';
		echo '</table><br><br>';
	}

	echo '<form method="post" action="" id="newform">';
	echo '<table class="tables">';
		echo '<thead><tr><th colspan="2" class="top">Изменить приветственное сообщение</th></tr></thead>';
		echo '<tr>';
			echo '<td width="130"><b>Тема сообщения:</b></td>';
			echo '<td><input type="text" readonly="readonly" name="subj" value="Приветствие от реферера '.$username.'" class="ok"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Текст сообщения:</b></td>';
			echo '<td><textarea name="welcome_ref" id="welc" onChange="messchange();" onKeyUp="messchange();" class="ok">'.$welcome_ref.'</textarea><div align="right" id="count" style="float:right; display:block; color:#696969; padding-right:10px;"></div></td>';
		echo '</tr>';
		echo '<tr><td colspan="2" align="center"><input type="submit" value="Сохранить" class="proc-btn" /></td></tr>';
	echo '</table>';
	echo '</form>';
}

?><script language="JavaScript">
	messchange();
</script><?php

include('footer.php');
?>