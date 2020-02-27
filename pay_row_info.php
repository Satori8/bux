<?php
$pagetitle = "���������� � ������� ������ � ����� ������� ������";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!DEFINED("pay_row_file")) DEFINE("pay_row_file", $_SERVER["DOCUMENT_ROOT"].'/cache/cache_pay_row.inc');

if(is_file(pay_row_file) ) {
	$pay_row_array = @unserialize(file_get_contents(pay_row_file));

	if(isset($pay_row_array) && is_array($pay_row_array) && count($pay_row_array)>0) {
		echo '<div align="left" style="padding:8px 7px;">';
			echo '<div style="line-height:18px;">� <b>����� ������:</b> <a href="/redir_pay_row.php?id='.$pay_row_arr[0]["id_pr"].'" target="_blank">'.$pay_row_arr[0]["desc_pr"].'</a></div>';
			echo '<div style="line-height:18px;">� <b>URL ������:</b> <a href="/redir_pay_row.php?id='.$pay_row_arr[0]["id_pr"].'" target="_blank">'.$pay_row_arr[0]["url_pr"].'</a></div>';
			if($pay_row_array[0]["user_pr"] != false) echo '<div style="line-height:18px;">� <b>�������������:</b> <span style="">'.$pay_row_array[0]["user_pr"].'</span></div>';
			echo '<div style="line-height:18px;">� <b>���������:</b> <span style="">'.$pay_row_array[0]["views_pr"].'</span></div>';
			echo '<div style="line-height:18px;">� <b>����� ����������:</b> <span style="">'.DATE("d.m.Y�. � H:i", $pay_row_array[0]["date_pr"]).'</span></div>';
		echo '</div>';
	}else{
		echo '<span class="msg-error">��� ����������!</span>';
	}
}else{
	echo '<span class="msg-error">��� ����������!</span>';
}

include(ROOT_DIR."/footer.php");
?>