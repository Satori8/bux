<?php
if(!DEFINED("CABINET"));
if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {

	if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
	require(DOC_ROOT."/config.php");

	$sql_cab = mysql_query("SELECT * FROM `tb_cabinet` WHERE `id`='1'");
	if(mysql_num_rows($sql_cab)>0) {
		$row_cab = mysql_fetch_assoc($sql_cab);

		$cab_status = $row_cab["status"];
		$cab_start_sum = $row_cab["start_sum"];
		$cab_shag_sum = $row_cab["shag_sum"];
		$cab_start_proc = $row_cab["start_proc"];
		$cab_max_proc = $row_cab["max_proc"];
		$cab_shag_proc = $row_cab["shag_proc"];

		if($cab_status==1) {
		    echo '<div style="color:#0000ff; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="justify"> ';
			//echo '<div align="justify" style="width:95%; margin:0 auto;">';
				echo '������ ������������� �� ������� ����� ���� ������ "������� �������������".<br><br>';
				echo '��� ���������� �������� ���������� ������������������.<br><br>';
				//echo '������������� ������������� ���� ������������������ ������������� �������.<br><br>';
				echo '����� ���������� ������� �� ����� ������� �� ����� ����� ����� <b>'.number_format($cab_start_sum,2,".","").'&nbsp;���.</b>, ������������� �������� ������ <b>'.$cab_start_proc.'%</b>.<br><br>';
				echo '����� ������ ������������� �� <b>'.$cab_shag_proc.'%</b> �� ������ <b>'.number_format($cab_shag_sum,2,".","").'&nbsp;���.</b>, ����������� �� ������ ������� �� �������.<br><br>';
				echo '������������ ������ - <b>'.$cab_max_proc.'%</b>.<br><br>';
				echo '��� ���������� ������ �� �������, ������������� ���������� �������������� �� ������� � ����� ����� ������� � ������.<br>';
			echo '</div>';
		}else{
			exit("<span class=\"msg-w\">� ������ ������ ������ �� ������� �� ���������!</span>");
		}
	}else{
		exit("<span class=\"msg-error\">������ ������ ������!</span>");
	}
}else{
	exit("Hacking attempt!");
}
?>