<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$id = $row["id"];
	$wait = $row["wait"];
	$totals = $row["totals"];
	$zdprice = $row["zdprice"];

	if($row["status"]=="wait" && $row["wait"]<=0 ) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='wait' AND `username`='$username'") or die(mysql_error());
		echo '<fieldset class="okp">������� #'.$id.' ������� �������!</fieldset>';

	}elseif( $row["status"]=="pause" && $row["date_act"] > (time()-7*24*60*60) ) {

		echo '<fieldset class="errorp">������! ����� ������� �������, ��� ���������� ���������� � ��������� 7 ����. ���� �� ������� �� ����� �����, �� ������� ����� ����� �������.</fieldset>';

	}elseif( $row["status"]=="pay" | $row["date_act"] > (time()-7*24*60*60) ) {

		echo '<fieldset class="errorp">������! ����� ������� �������, ��� ���������� ���������� � ��������� 7 ����. ���� �� ������� �� ����� �����, �� ������� ����� ����� �������.</fieldset>';

	}elseif( $row["wait"] > 0 ) {

		echo '<fieldset class="errorp">������! ����� ������� ������� ���������� ��������� �������� ������ �� �������� ���������� �������!</fieldset>';

	}elseif( $row["status"]=="pause" | $row["date_act"] < (time()-7*24*60*60) ) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
		echo '<fieldset class="okp">������� #'.$id.' ������� �������!</fieldset>';

	}elseif( $row["totals"] <= 0 ) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
		echo '<fieldset class="okp">������� #'.$id.' ������� �������!</fieldset>';

	}elseif( $row["status"]=="pause" && $row["totals"] > 0 ) {

		mysql_query("DELETE FROM `tb_ads_task` WHERE `id`='$id' AND `status`='pause' AND `username`='$username'") or die(mysql_error());
		echo '<fieldset class="okp">������� #'.$id.' ������� �������!</fieldset>';

	}else{


	}


	echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view");</script>';
	echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view">';
	exit();
}else{
	echo '<fieldset class="errorp">������! � ��� ��� ������ �������!</fieldset>';
	exit();
}
?>