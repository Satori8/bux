<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	if($row["plan"] > 0 && $row["totals"] > 0) {
		mysql_query("UPDATE `tb_ads_task` SET `status`='pay', `ip`='$ip' WHERE `id`='$rid' AND `username`='$username'") or die(mysql_error());

		echo '<fieldset class="okp">������� ��������!</fieldset>';

		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
		echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

		include('footer.php');
		exit();
	}else{
		echo '<fieldset class="errorp">������! ���������� ��������� ������ �������!</fieldset>';
	}
}else{
	echo '<fieldset class="errorp">������! � ��� ��� ������ �������!</fieldset>';
	include('footer.php');
	exit();
}
?>