<?php
$pagetitle = "����������� ��������� ��� ����������� �������";
include("header.php");
require_once(".zsecurity.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_max_percent' AND `howmany`='1'");
	$partner_max_percent = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='partner_active' AND `howmany`='1'");
	$partner_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price`,`howmany` FROM `tb_config` WHERE `item`='partner_count_day'");
	if(mysql_num_rows($sql)>0) {
		$row_pd = mysql_fetch_array($sql);
		$partner_count_day = $row_pd["howmany"];
		$partner_count_per = $row_pd["price"];
	}else{
		$partner_count_day = 1;
		$partner_count_per = 1;
	}

	echo '<h1 class="sp" style="color: #ab0606; text-align:center; margin:0; margin-top:20px;">����������� �������������� ���������� �� '.$partner_max_percent.'%</h1>';
	echo '<div align="center">�� ����� ������� �������, ���������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������, ������ ����������</div>';
	echo '<h2 class="sp" style="text-align:left;">����������� �������������� ����������� �� �������:</h2>';
		echo '1. <a href="/advertise.php?ads=stat_links" title="����������� ������">����������� ������</a><br>';
		echo '2. <a href="/advertise.php?ads=txt_ob" title="��������� ����������">��������� ����������</a><br>';
		echo '3. <a href="/advertise.php?ads=banners" title="����������� �������� 468�60">����������� �������� (468�60)</a><br>';
		echo '4. <a href="/advertise.php?ads=banners" title="����������� �������� 200�300">����������� �������� (200�300)</a><br>';
		echo '5. <a href="/advertise.php?ads=banners" title="����������� �������� 100x100">����������� �������� (100�100)</a><br>';
		echo '6. <a href="/advertise.php?ads=frm_links" title="������ �� ������">������ �� ������</a><br>';
		echo '7. <a href="/advertise.php?ads=psevdo" title="������-������������ ������">������-������������ ������</a><br><br>';

	echo '<h5 class="sp">��� ����� ���������� ����������� ���������?</h5>';
	echo '<div style="text-align:justify;">����� ����������� � ����������� ���������, ����� ����� ���� ������������ ��. ��� ��������� ����������� ����� ���� <b>'.$partner_active.' ������ ��������</b> (��������� ����� 1 ��� ��� ������� ���� �������), ��� ���� ���� ����������� �������������� ����� ���������� � 0 �� 1%. ����� ��������� �� ������ �������� ������� ������������ ��������������, �������� ������� �� ����� �����.</div>';
	
	echo '<h5 class="sp">����� ������� � ���� ��������?</h5>';
	echo '<div style="text-align:justify;">������� ������ �������������� �������� ������� �� ������ ����������� ������. <b>'.$partner_count_day.'</b> ��� ������, ����� <b>'.$partner_count_per.'%</b> ����������� (����������) �� ������� ���������, ���������� � ���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������.<br><br>��������: �� ���������� ������� �� <b>'.($partner_count_day*2).'</b> ���� - ��� ����������� ������� ����� <b>'.($partner_count_per*2).'%</b>. �� ���������� ��������� <b>'.($partner_count_per*2).'%</b> �� ����� ����� ������ ����� ���������. (� ��������� ����������� ������ �� ����������, �� �������� ���� �������� �� ���� �������).<br><br>��� ���� ������ ����������� � �������������� ����������� � ������������� ��� ����������� �������. ����� �������, ��� ������ �� ������������, ��� ������� ������� �� ��������� �� ������� ���������. ����������� ��������� �������������� <b>'.$partner_max_percent.'%</b>.</div>';

	echo '<h5 class="sp" style="color:#FF0000;">�����!</h5>';
	echo '<div style="text-align:justify;">��� ��������� ��������� <b>'.$partner_active.' ������ ��������</b> (��������� ����� 1 ��� ��� ������� ���� �������). ��� <b>���������� ������������ ��������������</b> ������� ���������� ���������� � <b>���������� ����� ���� � ������� ��������� ������� �� ��� ������� ��� ����� ������� �������� ����� ����������� �� �������</b>. �������������� ����� ����������� �� ��� �������� ���� � � ����� ������ ����� �������� ��� ������ �� �������.</div>';
	echo '<br><br>';

	if(isset($_GET["active"]) && limpiar($_GET["active"])=="1") {
		if($p_sl>0) {
			echo '<span class="msg-error">��� ����������� ������ ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_sl`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_sl`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="2") {
		if($p_txt>0) {
			echo '<span class="msg-error">��� ��������� ���������� ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_txt`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_txt`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="3") {
		if($p_b468x60>0) {
			echo '<span class="msg-error">��� �������� 468x60 ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b468x60`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b468x60`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="4") {
		if($p_b100x100>0) {
			echo '<span class="msg-error">��� �������� 100x100 ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b100x100`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b100x100`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="5") {
		if($p_b200x300>0) {
			echo '<span class="msg-error">��� �������� 200x300 ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_b200x300`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_b200x300`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
		echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
	}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="6") {
		if($p_frm>0) {
			echo '<span class="msg-error">��� ������ �� ������ ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_frm`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_frm`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� ��� ��������� ������������ ��������������!</span>';
		}

	}elseif(isset($_GET["active"]) && limpiar($_GET["active"])=="7") {
		if($p_psd>0) {
			echo '<span class="msg-error">��� ������-������������ ������ ����������� ��������� ��� ������������!</span>';
		}elseif($my_reiting>$partner_active) {
			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`-'$partner_active' WHERE `username`='$username'") or die(mysql_error());

			$sql_p = mysql_query("SELECT `id` FROM `tb_users_partner` WHERE `username`='$username'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_users_partner` SET `p_psd`='1' WHERE `username`='$username'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_users_partner` (`username`,`p_psd`) VALUES('$username','1')") or die(mysql_error());
			}
		}else{
			echo '<span class="msg-error">� ��� ������������ ������ �������� �� ����� ��� ��������� ������������ ��������������!</span>';
		}
	}

	$sql_p = mysql_query("SELECT * FROM `tb_users_partner` WHERE `username`='$username'");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);

		$p_sl = $row_p["p_sl"];
		$p_txt = $row_p["p_txt"];
		$p_b468x60 = $row_p["p_b468x60"];
		$p_b200x300 = $row_p["p_b200x300"];
		$p_b100x100 = $row_p["p_b100x100"];
		$p_frm = $row_p["p_frm"];
		$p_psd = $row_p["p_psd"];
		$discount_partner = $row_p["discount_partner"];
	}else{
		$p_sl = 0;
		$p_txt = 0;
		$p_b468x60 = 0;
		$p_b200x300 = 0;
		$p_b100x100 = 0;
		$p_frm = 0;
		$p_psd = 0;
		$discount_partner = 0;
	}

	echo '<a name="tab"></a><table class="tables_inv">';
	echo '<thead><tr>';
		echo '<th>����������� ���������</th>';
		echo '<th>������� �� ������� ���������</th>';
		echo '<th></th>';
	echo '</tr></thead>';

	echo '<tbody>';
		echo '<tr>';
			echo '<td>����������� ������</td>';
			if($p_sl>0) {
				echo '<td align="center"><b style="color: green;">'.$p_sl.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=stat_links">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=1">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>��������� ����������</td>';
			if($p_txt>0) {
				echo '<td align="center"><b style="color: green;">'.$p_txt.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=txt_ob">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=2">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
		}
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� 468x60</td>';
			if($p_b468x60>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b468x60.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=3">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� 100�100</td>';
			if($p_b100x100>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b100x100.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=4">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� 200�300</td>';
			if($p_b200x300>0) {
				echo '<td align="center"><b style="color: green;">'.$p_b200x300.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=banners">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=5">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>������ �� ������</td>';
			if($p_frm>0) {
				echo '<td align="center"><b style="color: green;">'.$p_frm.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=frm_links">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=6">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
		echo '<tr>';
			echo '<td>������-������������ ������</td>';
			if($p_psd>0) {
				echo '<td align="center"><b style="color: green;">'.$p_psd.'%</b> �� ������ ���������</td>';
				echo '<td align="center"><a href="/advertise.php?ads=psevdo">��������� %</a></td>';
			}else{
				echo '<td align="center">[0%] �� ������������</td>';
				echo '<td align="center"><a href="?active=7">������������ �� <b>'.$partner_active.'</b> ������ ��������</a></td>';
			}
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

}

include('footer.php');?>