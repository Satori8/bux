<?php
require_once('.zsecurity.php');
$pagetitle="��������� ������� ���������� ��������� ���������";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	echo '<span class="msg-w">��������� ������� ����-������� ������ �� ��������!<br>�������� �������<a href="ref_wall.php"><span style="color:#FFFFFF;font-size:18px;"> - <u>���-�����</u></span></a></span>';
/*

	require('config.php');
	$sql_us = mysql_query("SELECT money_rb,autoref,autorefend FROM tb_users WHERE username='$username'");
	$row_us = mysql_fetch_array($sql_us);
	$money = $row_us["money_rb"];
	$autoref = $row_us["autoref"];
	$autorefend = DATE("d.m.Y�. H:i",$row_us["autorefend"]);

	$chek = mysql_query("SELECT `id` FROM `tb_autoref` WHERE `time`>='".time()."'");
	$vsego_autoref = mysql_num_rows($chek);

	$sql_cena = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='autoref' AND `howmany`='1'");
	$cena_autoref = mysql_result($sql_cena,0,0);

	$plan = (isset($_POST["plan"]) && abs(intval(limpiar($_POST["plan"])))>0) ? abs(intval(limpiar($_POST["plan"]))) : "1";
	$summa = round(($plan*$cena_autoref),2);

	if(isset($_GET["page"]) && limpiar($_GET["page"])=="payok") {
		if($autoref=="1" && $row_us["autorefend"]>time()) {
			echo '<span class="msg-error">������� ��� ������������!</span>';
			include('footer.php');
			exit() ;
		}

		if($money < $summa) {
			echo '<span class="msg-error">� ��� ������������ ������� �� ��������� ����� ��� ��������� �������!</span>';
			include('footer.php');
			exit() ;
		}else{
			$date_endactive = (time() + ($plan * 7 * 24 * 60 * 60));

			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$summa', `autoref`='1', `autorefend`='$date_endactive', `last_autoref`='0' WHERE `username`='$username'") or die(mysql_error());
			mysql_query("INSERT INTO `tb_autoref` (`username`,`plan`,`date`,`time`,`ip`,`money`) VALUES ('$username','$plan','".date("d.m.Y",$date_endactive)."','$date_endactive','$laip','$summa')") or die(mysql_error());

			echo '<span class="msg-ok">������� ����������� �� '.date("d.m.Y�. H:i",$date_endactive).'!<br /><a href="'.$_SERVER['PHP_SELF'].'">&lt;&lt; ��������� �����</a></span>';
			include('footer.php');
			exit();
		}
	}

	if(isset($_GET["page"]) && limpiar($_GET["page"])=="on") {
		if($autoref=="1" && $row_us["autorefend"]>time()) {
			echo '<span class="msg-error">������� ��� ������������!</span>';
			include('footer.php');
			exit() ;
		}

		echo '<span class="msg-ok">��� ����� ������ � ������� �������������!</span>';
		echo '<table border="0" style="border-top:1px dotted #ccc; border-bottom:1px dotted #ccc;">';
		echo '<tr><td><b>����� � ������:</b> '.$summa.' ���.</td></tr>';
		echo '<tr><td><b>���������� �������:</b> ��������� ������� ���������� ��������� ��������� �� '.$plan.' ���.</td></tr>';
		echo '</table><br />';
		echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?page=payok"><input type="hidden" name="plan" value="'.$plan.'"><input type="submit" class="submit" value="�����������"></form>';
		include('footer.php');
		exit();
	}

	echo '<fieldset style="width:auto; text-align:justify; margin:0px 0px 0px 0px; padding:5px 10px 5px 10px; border:1px solid #CCC; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px;"><legend>&nbsp;����������&nbsp;</legend>';
		echo '��������� ������ ������� �������� ��� � ������� ������ ������������� �������� ��������� ��������� ������� (������� ���������������� �� �� ����������� ������).<br />';
		echo '���� ������� ������������ ��������� �������, �� �������� ����� ������������ ������� ����� �������������� ������� ������������ �������.<br>��������� ������� �����������: 1 ������ <b>'.$cena_autoref.'</b> ���.<br>';
		echo '<br>';
		echo '�� ������ ������ ������� ������������ <b>'.$vsego_autoref.' �������������.</b>';
	echo '</fieldset>';


	if($autoref=="1" && $row_us["autorefend"]>time()) {
		echo '<span class="msg-ok">������� ������������ �� '.$autorefend.'</span>';
	}else{
		?>
		<script language="JavaScript">
			function gebi(id){
				return document.getElementById(id)
			}

			function obsch(){
				var vcount = gebi('plan').value;
				var price = <?php echo $cena_autoref;?>;
				if(gebi("plan").value >= 1) {
					gebi('text').innerHTML = '<b>� ������:</b>';
					gebi('summa').innerHTML = Math.round((price*vcount*10000))/10000 + ' ���.'; 
				}else{
					gebi('text').innerHTML = '<b style="color:#FF0000; text-decoration:blink;">������� ���-�� ������!</b>';
					gebi('summa').innerHTML = '������� 1 ������'; 
				}
			}
		</script><?php

		echo '<br><br>';
		echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'?page=on" id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr><th class="top" colspan="2">����� ������</th></tr></thead>';
		echo '<tr>';
			echo '<td align="left"><b>������� ���-�� ������:</b></td>';
			echo '<td><input type="text" name="plan" id="plan" maxlength="3" value="1" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" class="ok12" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><span id="text"></span></td>';
			echo '<td><b><span id="summa" style="color:#FF0000"></span></b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="center" colspan="2"><input type="submit" value="����� &gt;&gt;" class="submit" /></td>';
		echo '</tr>';
		echo '</table>';
		echo '</form>';

		?><script language="JavaScript"> obsch() </script><?php
	}
*/
}

include('footer.php');?>