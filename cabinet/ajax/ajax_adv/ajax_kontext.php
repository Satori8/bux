<?php
if(!DEFINED("KONTEXT_AJAX")) {die ("Hacking attempt!");}

if($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("0");
		}elseif($status == 1) {
			mysql_query("UPDATE `tb_ads_kontext` SET `status`='2' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
			cache_kontext();
			exit();
		}elseif($status == 2) {
			mysql_query("UPDATE `tb_ads_kontext` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
			cache_kontext();
			exit();
		}elseif($status == 3 && $row["totals"]>0) {
			mysql_query("UPDATE `tb_ads_kontext` SET `status`='1' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
			cache_kontext();
			exit();
		}elseif($status == 3 && $row["totals"]<1) {
			exit("0");
		}else{
			exit("ERROR");
		}
	}else{
		exit("ERRORNOID");
	}

}elseif($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_kontext();
			exit("OK");
		}elseif($status == 1 | $status == 2) {
			exit("�������� �������� ������ ����� ��������� ������.");
		}elseif($status == 3 && $row["totals"]>0) {
			exit("�������� �������� ������ ����� ��������� ������.");
		}elseif($status == 3 && $row["totals"]<1) {
			mysql_query("DELETE FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			cache_kontext();
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "save") {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 30) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 60) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$color = (isset($_POST["color"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["color"])))) ? intval(limpiarez($_POST["color"])) : false;
	$black_url = @getHost($url);

	$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$color_tab = $row["color"];

		$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
		if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row_bl = mysql_fetch_array($sql_bl);
			echo '���� '.$row_bl["domen"].' ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' �������: '.$row_bl["cause"].'';
			exit();
		}elseif($url==false | $url=="http://" | $url=="https://") {
			echo '�� ������� ������ �� ����!';
			exit();
		}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
			echo '�� ����� ������� ������ �� ����!';
			exit();
		}elseif(is_url($url)!="true") {
			echo '�� ����� ������� ������ �� ����!';
			exit("");
		}elseif($title==false) {
			echo '�� ��������� ���� ��������� ������.';
			exit("");
		}elseif($description==false) {
			echo '�� ��������� ���� �������� ������.';
			exit("");
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url) != false) {
			exit(SFB_YANDEX($url));
		}else{
			if($status == 0 | $status == 3) {
				if($color=="1") { $color = 1; }else{ $color = 0; }

				mysql_query("UPDATE `tb_ads_kontext` SET `color`='$color', `url`='$url', `title`='$title', `description`='$description', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				cache_kontext();
				exit("OK");

			}elseif($status == 1 | $status == 2) {
				$color = $color_tab;

				mysql_query("UPDATE `tb_ads_kontext` SET `color`='$color', `url`='$url', `title`='$title', `description`='$description', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				cache_kontext();
				exit("OK");

			}else{
				exit("ERROR");
			}
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("������� ���� �������� ��� ����� 0");
		}else{
			mysql_query("UPDATE `tb_ads_kontext` SET `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$color = $row["color"];
		$status = $row["status"];
		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_min' AND `howmany`='1'");
		$cena_kontext_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext' AND `howmany`='1'");
		$cena_kontext = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_color' AND `howmany`='1'");
		$cena_kontext_color = mysql_result($sql,0,0);

		$cena = $plan * ($color * $cena_kontext_color + $cena_kontext) * (100-$cab_skidka)/100;
		$money_pay = number_format($cena, 2, ".", "");

		if($plan<$cena_kontext_min) {
			exit("ERROR1");
		}elseif($money_user_rb<$money_pay) {
			exit("ERROR2");
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor($money_pay/10) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

			if($status=="0") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_kontext` SET `status`='1', `method_pay`='10', `date`='".time()."', `plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� ����������� ������� ID:$id','�������','rashod')") or die(mysql_error());

				@stat_pay('kontext', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				invest_stat($money_pay, 4);
				ActionRef(number_format($money_pay,2,".",""), $username);
				cache_kontext();

				exit("OK");

			}elseif($status=="1" | $status=="2" | $status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_kontext` SET `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� ����������� ������� ID:$id','�������','rashod')") or die(mysql_error());

				@stat_pay('kontext', $money_pay);
				@ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				@konkurs_ads_new($wmid_user, $username, $money_pay);
				ActionRef(number_format($money_pay,2,".",""), $username);
				invest_stat($money_pay, 4);
				cache_kontext();

				exit("OK");

			}else{
				exit("������! �� ������� ���������� ������!");
			}
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}
}

?>