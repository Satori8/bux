<?php
session_start();
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<b style="color:#FF0000; font-size:12px;">������! ��� ������� � ���� �������� ���������� ��������������!</b>';
}else{

	require("../config.php");
	require("../funciones.php");

	$username = uc($_SESSION["userLog"]);

	$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid'");
	if(mysql_num_rows($sql)>0) {
        	$row = mysql_fetch_array($sql);
		$rek_name = $row["username"];

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_array($sql_p);
			if($row_p["status"]!="") {
				if($row_p["coment"]!="" OR $row_p["ocenka"]>0) {
					echo '<b style="color:#FF0000; font-size:12px;">������! �� ��� �������������� ������ �������!</b>';
				}else{
					echo '<form action="/view_task.php?page=task&rid='.$rid.'" method="post" onSubmit="return saveform();" onkeypress="if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))){this.submit();}">';
					echo '<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td valign="top">';
					echo '<input type="hidden" name="rid" value="'.$rid.'">';
					echo '�� ������ �������� ���� ����� � �������, ������� �� ���������. ��� ����� ������� ������ �������������, ����� ������, ����� ��������� ��� �������, ��� ���.<br>';

					echo '<b>������� �������</b>:<br>';
					echo '<select name="ocenka" onChange="" id="ocenka">';
					echo '<option value="no" selected="selected" disabled="disabled">������� �������:</option>';
						echo '<option value="0">0 - ������� ������ ���������</option>';
						echo '<option value="1">1 - ����� ������ �������</option>';
						echo '<option value="2">2 - ������ �������</option>';
						echo '<option value="3">3 - ���������� �������</option>';
						echo '<option value="4">4 - ������� �������</option>';
						echo '<option value="5">5 - �������� �������</option>';
					echo '</select><br><br>';
					echo '<b>��� �����������</b>:<br>';
					echo '<textarea  cols="" rows="5" name="coment" id="coment" style="width: 94%; background-color: #FBFCCC;"></textarea><br>';

					for($i=0; $i<22; $i++){
						echo '<a href="javascript:setsmile(\'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="middle" border="0" style="padding:0; margin:0;"></a> ';
					}

					echo '<span id="spanaddsmile1">[<a href="javascript:viewaddsmile();">���</a>]</span>';
					echo '<span id="spanaddsmile2" style="display:none;">[<a href="javascript:viewaddsmile();">������</a>]</span>';
					echo '<div id="allsmile" style="display:none;">';
						for($i=22; $i<38; $i++){
							echo '<a href="javascript:setsmile(\'smile-'.$i.'\')"><img src="smiles/smile-'.$i.'.gif" alt="" align="middle" border="0" style="padding:0; margin:0;"></a> ';
						}
					echo '</div><br>';

					echo '� ������ ����������� <b>���������</b> ��������� ����� ���������� ���������� ���������, ��������� � �.�.<br><br>';
					echo '<center><input type="submit" name="submit" value="�������� �����" style="background: #2087DB; color: #FFFFFF; padding: 0px; padding: 3px 10px; font-size: 12px; border: 1px solid #5656E4;"></center>';
					echo '</td></tr>';
					echo '</table>';
					echo '</form>';
				}
			}else{
				echo '<b style="color:#FF0000; font-size:12px;">����� ���, ��� ����������������� �������, ���������� ��� ���������!</b>';
			}
		}else{
			echo '<b style="color:#FF0000; font-size:12px;">����� ���, ��� ����������������� �������, ���������� ��� ���������!</b>';
		}
	}else{
		echo '<b style="color:#FF0000; font-size:12px;">������! ������ ������� ���!</b>';
	}
}
?>