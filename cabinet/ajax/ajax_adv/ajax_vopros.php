<?php
if(!DEFINED("VOPROS_AJAX")) {die ("Hacking attempt!");}

if($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$totals = $row["totals"];
		$plan = $row["plan"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}elseif($status == 1) {
			exit("�������� �������� ������ ����� ��������� ���� �������.");
		}elseif($status == 3) {
			mysql_query("DELETE FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "start") {
	$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$totals = $row["totals"];
		$plan = $row["plan"];
		if($status == 3 && $plan>=$totals && $totals!=0) {		
			mysql_query("UPDATE `tb_ads_questions` SET `status`='1' WHERE `id`='$id' && `status`>'0'") or die(mysql_error());
			exit("������� ���������!");
		}elseif($status == 1 && $plan>=$totals && $totals!=0) {
			exit("��� ���������!");
		}else{
			exit("�� �� ������ ��������� ������ �������!");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}
}elseif($option == "stop") {
	$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$totals = $row["totals"];
		$plan = $row["plan"];
		if($status == 1 && $plan>=$totals) {		
			mysql_query("UPDATE `tb_ads_questions` SET `status`='3' WHERE `id`='$id' &&`status`>'0'") or die(mysql_error());
			exit("������� ������������!");
		}elseif($status == 3 && $plan>=$totals) {
			exit("��� ������������!");
		}else{
			exit("�� �� ������ ���������� ������ �������!");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}
}elseif($option == "popoln") {
	$sql = mysql_query("SELECT * FROM `tb_ads_questions` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$totals = $row["totals"];
		$color = $row["color"];
		$plan = $row["plan"];
		$plan_z = $_POST["plan"];
$sql11 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_min'");
$vopr_min = number_format(mysql_result($sql11,0,0),0,".","");
$sql22 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_color'");
$vopr_color = number_format(mysql_result($sql22,0,0),2,".","");
$sql33 = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='quest_price'");
$vopr_cena = number_format(mysql_result($sql33,0,0),2,".","");
$money_pay=$plan_z*$vopr_cena+$color*$vopr_color;
if($plan_z<$vopr_min){
exit("����������� ���������� ������ ������ ��� �� �������!");
}elseif($money_user_rb<$money_pay){
exit("������������ ����� �� �������!");
}elseif($status == 1 && $plan>$totals) {		
			exit("C������ ���������� ��������� ��������!");
		}elseif($status == 3 || $status == 0) {
		mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'") or die(mysql_error());
		mysql_query("UPDATE `tb_ads_questions` SET `status`='1', `plan`=`plan`+'$plan_z', `totals`=`totals`+'$plan_z',`money`=`money`+'$money_pay' WHERE `id`='$id'") or die(mysql_error());
		mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
		VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� �������� ������� ID:$id','�������','rashod')") or die(mysql_error()); 
			exit("������� ��������!");
		}else{
			exit("ERROR!");
		}	
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}
}
?>