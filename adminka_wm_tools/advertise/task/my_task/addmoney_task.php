<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}


$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

if($rid > 0) {
	echo '<b>���������� ������� ������������� �������:</b><br><br>';
	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$id = $row["id"];
		$zdname = $row["zdname"];
		$zdtext = $row["zdtext"];
		$zdurl = $row["zdurl"];
		$zdtype = $row["zdtype"];
		$zdre = $row["zdre"];
		$zdcheck = $row["zdcheck"];
		$zdprice = $row["zdprice"];
		$zdcountry = $row["country_targ"];

		if(count($_POST) > 0) {
			$add_plan = (isset($_POST["add_plan"])) ? abs(intval(trim($_POST["add_plan"]))) : false;

			if($add_plan>0) {
				mysql_query("UPDATE `tb_ads_task` SET `status`='pay', `plan`=`plan`+'$add_plan', `totals`=`totals`+'$add_plan' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			}

			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view");</script>';
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page=task_view">';

			exit();
		}

		echo '<div id="form">';
		echo '<form id="form" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&amp;page='.limpiar($_GET["page"]).'&amp;rid='.$id.'" method="POST">';
		echo '<table align="center" border="0" width="100%" cellspacing="3" cellpadding="3" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
			echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">�������� ������������� �������</th></tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="200" align="right" height="30px"><b>��������:</b></td>';
				echo '<td>&nbsp;'.$zdname.'</td>';
			echo '</tr>';
			echo '<tr bgcolor="#AFEEEE">';
				echo '<td width="200" align="right" height="30px"><b>��� �������:</b></td>';
				if($zdtype==1)
					echo '<td>�����</td>';
				elseif($zdtype==2)
					echo '<td>����������� ��� ����������</td>';
				elseif($zdtype==3)
					echo '<td>����������� � �����������</td>';
				elseif($zdtype==4)
					echo '<td>������� � �����</td>';
				elseif($zdtype==5)
					echo '<td>������� � �����</td>';
				elseif($zdtype==6)
					echo '<td>�����������</td>';
				elseif($zdtype==7)
					echo '<td>�������� ������</td>';
				elseif($zdtype==9)
					echo '<td>���������� ����</td>';
				elseif($zdtype==10)
					echo '<td>YouTube</td>';
				elseif($zdtype==11)
					echo '<td>������ � ������</td>';
				elseif($zdtype==8)
					echo '<td>������</td>';
				else{
					echo '<td></td>';
				}
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="200" align="right" height="30px"><b>������ �� ����:</b></td>';
				echo '<td>&nbsp;<a href="'.$zdurl.'" target="_blank">'.$zdurl.'</a></td>';
			echo '</tr>';
			echo '<tr bgcolor="#AFEEEE">';
				if($zdre==0) {echo '<td width="200" align="right" height="30px"><b>������ ������ XX �. :</b></td>';}else{echo '<td width="200" align="right" height="30px"><b>������ ������ '.$zdre.' �. :</b></td>';}
				if($zdre==0) {echo '<td>&nbsp;���</td>';}else{echo '<td>&nbsp;��</td>';}
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="200" align="right" height="30px"><b>�������� ��������:</b></td>';
				if($zdcheck==1) {
					echo '<td>&nbsp;������ �����</td>';
				}else{
					echo '<td>&nbsp;�������������� �����</td>';
				}
			echo '</tr>';
			echo '<tr bgcolor="#AFEEEE">';
				echo '<td width="200" align="right" height="30px"><b>��������� ����������:</b></td>';
				echo '<td>&nbsp;'.$zdprice.'&nbsp;���.</td>';
			echo '</tr>';
			echo '<tr bgcolor="#ADD8E6">';
				echo '<td width="200" align="right" height="30px"><b>��������� �� �������:</b></td>';
				if($zdcountry==1)
					echo '<td>&nbsp;������ ������</td>';
				elseif($zdcountry==2)
					echo '<td>&nbsp;������ �������</td>';
				else{
					echo '<td>&nbsp;����� ������</td>';
				}
			echo '</tr>';
		echo '<tr bgcolor="#1E90FF" align="center" height="30px"><th align="center" colspan="2">���������� ������� ������������� �������</th></tr>';
		echo '<tr bgcolor="#ADD8E6">';
			echo '<td width="200" align="right" height="30px"><b>���-�� �������:</b></td>';
			echo '<td>&nbsp;<input type="text" size="10" style="text-align:right;" name="add_plan" id="add_plan" maxlength="10" value="10"></td>';
		echo '</tr>';
		echo '<tr bgcolor="#AFEEEE">';
			echo '<td align="center" colspan="2"><input type="submit" class="submit" value="&nbsp;&nbsp;��������� ������ �������&nbsp;&nbsp;"></td>';
		echo '</tr>';
		echo '</table>';
		echo '</form>';
		echo '</div>';

	}else{
		echo '<fieldset class="errorp">������! � ��� ��� ������ �������!</fieldset>';
		exit();
	}
}else{
	echo '<fieldset class="errorp">������! � ��� ��� ������ �������!</fieldset>';
	exit();
}
?>