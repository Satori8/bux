<?php
$pagetitle="Рассылка сообщения рефералам III уровня";
include('header.php');
require_once('.zsecurity.php');

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

	$username = (isset($_SESSION["userLog"])) ? uc($_SESSION["userLog"]) : false;

	require('config.php');

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_count' AND `howmany`='1'");
	$mail_refs_count_1 = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_count' AND `howmany`='2'");
	$mail_refs_count_2 = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs' AND `howmany`='1'");
	$cena_mail_refs = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mail_refs_sk' AND `howmany`='1'");
	$cena_mail_refs_sk = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'");
	$all_referals = mysql_num_rows($sql);

	$sql = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username' AND `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-14*24*60*60))."'");
	$active_referals = mysql_num_rows($sql);

	$cena_1 = round(($all_referals*$cena_mail_refs*(100-$cena_mail_refs_sk)/100),2);
	$cena_2 = round(($active_referals*$cena_mail_refs),2);

	if(count($_POST)>0 && isset($_POST["message"])) {
		$types = (isset($_POST["types"]) && preg_match("|^[\d]$|", trim($_POST["types"]))) ? intval(limpiarez(trim($_POST["types"]))) : false;
		$subject = (isset($_POST["subject"])) ? limitatexto(limpiarez($_POST["subject"]),$mail_refs_count_1) : false;
		$message = (isset($_POST["message"])) ? limitatexto(limpiarez($_POST["message"]),$mail_refs_count_2) : false;

		if($subject==false) $subject="Сообщение от реферера $username";

		if($types==1) {
			$cena=$cena_1;
			$types_txt="всем";

			$sql = mysql_query("SELECT `username` FROM `tb_users` WHERE `referer3`='$username'");

		}elseif($types==2) {
			$cena = $cena_2;
			$types_txt="активным";

			$sql = mysql_query("SELECT `username` FROM `tb_users` WHERE `referer3`='$username' AND `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-14*24*60*60))."'");
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
			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) VALUES('$username', '".DATE("d.m.Yг. H:i")."', '$cena',  'Оплата за рассылку ".$types_txt." рефералам','Списано','rashod')") or die(mysql_error());

			while($row = mysql_fetch_array($sql)) {
				mysql_query("INSERT INTO `tb_mail_in`(`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('".$row["username"]."','$username','$subject','$message','0','".time()."','$ip')");
			}

			echo '<fieldset class="okp">Рассылка успешно отправлена '.$types_txt.' рефералам!</fieldset>';
		}else{
			echo '<fieldset class="errorp">Ошибка! У вас нет рефералов!</fieldset>';
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

			if(subject.length > <?=$mail_refs_count_1;?>) {
				gebi('subject').value = subject.substr(0,<?=$mail_refs_count_1;?>);
			}
			if(mess.length > <?=$mail_refs_count_2;?>) {
				gebi('mess').value = mess.substr(0,<?=$mail_refs_count_2;?>);
			}
			gebi('count1').innerHTML = 'Осталось <b>'+(<?=$mail_refs_count_1;?>-subject.length)+'</b> символов';
			gebi('count2').innerHTML = 'Осталось <b>'+(<?=$mail_refs_count_2;?>-mess.length)+'</b> символов';
		}
	</script>

	<?php
	echo '<br>';
	echo '<b>Платная рассылка вашим рефералам III уровня:</b><br>цена за 1 письмо <b>'.$cena_mail_refs.' руб.</b><br>Письмо приходит через внутреннюю почту системы.<br><br>';


	echo '<form method="post" action="" id="newform">';

	echo '<table class="tables">';
	echo '<thead><tr><th colspan="2" class="top">Форма отправки сообщения</th></tr></thead>';

	echo '<tr><td align="right"><input type="radio" name="types" value="1" checked="checked"></td><td>Всем рефералам ('.$all_referals.'), стоимость со скидкой ('.(floatval($cena_mail_refs_sk)).'%) - <b>'.$cena_1.' руб.</b></td></tr>';
	echo '<tr><td align="right"><input type="radio" name="types" value="2"></td><td>Рефералам, которые заходили в течение последних двух недель ('.$active_referals.') - стоимость:  - <b>'.$cena_2.' руб.</b></td></tr>';

	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Тема сообщения:</b></td>';
		echo '<td><input type="text" id="subject" name="subject" value="Сообщение от реферера III уровня '.$username.'" maxlength="255" onChange="messchange();" onKeyUp="messchange();" class="ok"><div align="right" id="count1" style="float:right; display:block; color:#696969; padding:0px; margin:0px;"></div></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Текст сообщения:</b></td>';
		echo '<td><textarea name="message" id="mess" onChange="messchange();" onKeyUp="messchange();" class="ok"></textarea><div align="right" id="count2" style="float:right; display:block; color:#696969; padding:0px; margin:0px;"></div></td>';
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