<?php
require_once('.zsecurity.php');
$pagetitle="������� ������� ��������� �������";
include('header.php');
?>

<script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script>

<?php

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	require('config.php');

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z�-��-�0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
	
	if($username == false) {
		echo '<span class="msg-error">����� ������������ �� ���������!</span>';
		@include('footer.php');
	}else{
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='convert_user_click' AND `howmany`='1'");
		$convert_user_click = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='convert_user_comis' AND `howmany`='1'");
		$convert_user_comis = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='convert_user_comis_min' AND `howmany`='1'");
		$convert_user_comis_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='convert_user_min' AND `howmany`='1'");
		$convert_user_min = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='convert_user_max' AND `howmany`='1'");
		$convert_user_max = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `money`,`money_rb`,`attestat`,`reiting` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_row($sql);

			$money_users = $row["0"];
			$money_form = p_floor($money_users,2);
			$money_form = number_format($money_form,2,".","");
			$money_table = number_format($money_users,4,".","'");
			$money_users_rb = $row["1"];
			$money_form_rb = p_floor($money_users_rb,2);
			$money_form_rb = number_format($money_form_rb,2,".","");
			$money_table_rb = number_format($money_users_rb,4,".","'");

			$my_attestat = $row["2"];
			$my_reiting = $row["3"];
		}else{
			echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
			include('footer.php');
			exit();
		}

		$sql_rang = mysql_query("SELECT `r_ot` FROM `tb_config_rang` WHERE `id`='2'");
		if(mysql_num_rows($sql_rang)>0) {
			$row_rang = mysql_fetch_row($sql_rang);
			$rang = $row_rang["0"];
		}else{
			$rang = false;
		}

		if($my_attestat<110) {
			echo '<span class="msg-error">��� ���������� ��������� ��������� ������� ������� �� ��������!</span>';
			include('footer.php');
			exit();
		}

		if($my_reiting<$rang) {
			echo '<span class="msg-error">��� ����� "�������" ������� ������� �� ��������!</span>';
			include('footer.php');
			exit();
		}

		$sql_b = mysql_query("SELECT * FROM `tb_ban_users` WHERE `name`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql_b)>0) {
			$row_b = mysql_fetch_array($sql_b);
			$prichina = $row_b["why"];
			$kogda = $row_b["date"];

			echo '<span class="msg-error">��� ������� ������������! �� �� ������ ������� ������������ ��������!<br><u>������� ����������</u>: '.$row_b["why"].'<br><u>���� ����������</u>: '.$row_b["date"].'</span>';
			
			include('footer.php');
			exit();
		}


		if(count($_POST)>0) {
			$date_now = strtotime(DATE("d.m.Y"));
			$money_out = (isset($_POST["money_out"])) ? abs(floatval(str_replace(",",".",trim($_POST["money_out"])))) : false;
			$money_out = p_floor($money_out,2);
			$enter_pass_oper = (isset($_POST["pass_oper"]) && preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["pass_oper"]))) ? uc_p($_POST["pass_oper"]) : false;
			$to_users = (isset($_POST["to_users"]) && preg_match("|^[a-zA-Z�-��-�0-9\-_-]{3,255}$|", trim($_POST["to_users"]))) ? uc($_POST["to_users"]) : false;
			$method_pay = (isset($_POST["method_pay"])) ? intval(trim($_POST["method_pay"])) : false;

			$comis = number_format((($money_out * $convert_user_comis) / 100),2,".","");
			if($comis<$convert_user_comis_min) {
				$comis = $convert_user_comis_min;
			}

			$sql_to = mysql_query("SELECT `id` FROM `tb_users` WHERE `username`='$to_users'");

			if($enter_pass_oper==false) {
				echo '<span class="msg-error">��� �������� ������� ���������� ������� ������ ��� ��������!</span>';
			}elseif($enter_pass_oper!=$pass_oper) {
				echo '<span class="msg-error">������ ��� �������� ������ �� �����!</span>';
			}elseif($visits<$convert_user_click){
				echo '<span class="msg-error">������ ������ �� ������� ������ ������������ ������� �������� ������ ����� '.$convert_user_click.' ������.!</span>';
			}elseif($money_out<$convert_user_min) {
				echo '<span class="msg-error">����������� ����� ��� �������� ���������� '.$convert_user_min.' ���.</span>';
			}elseif($money_out>$convert_user_max) {
				echo '<span class="msg-error">������������ ����� ��� �������� ���������� '.$convert_user_max.' ���.</span>';
			}elseif($method_pay==1 && ($money_out+$comis)>$money_form) {
				echo '<span class="msg-error">�� ����� �������� ����� ������������ ������� ��� �������� '.$money_out.' ���. �� ����� �������� ����� ������ ���� �� ����� '.($money_out+$comis).' ���. (� ������ �������� �����)</span>';
			}elseif($method_pay==2 && ($money_out+$comis)>$money_form_rb) {
				echo '<span class="msg-error">�� ����� ��������� ����� ������������ ������� ��� �������� '.$money_out.' ���. �� ����� ��������� ����� ������ ���� �� ����� '.($money_out+$comis).' ���. (� ������ �������� �����)</span>';
			}elseif($to_users==false) {
				echo '<span class="msg-error">������� ���������� �������!</span>';
			}elseif(strtolower($to_users)==strtolower($username)) {
				echo '<span class="msg-error">�� �� ������ ���������� �������� ������ ����!</span>';
			}elseif(mysql_num_rows($sql_to)<1) {
				echo '<span class="msg-error">������������ '.$to_users.' ��� � �������!</span>';
			}elseif($method_pay==1) {

				mysql_query("UPDATE `tb_users` SET `money`=`money`-'".($money_out+$comis)."', `sent_users`=`sent_users`+'$money_out' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `money`=`money`+'$money_out' WHERE `username`='$to_users'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`) VALUES('3','0','$username','','".DATE("d.m.Y H:i",time())."','".time()."','".($money_out+$comis)."','������� ������� ������������ $to_users (� ��������� �� ��������)','�������')") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`) VALUES('4','0','$to_users','','".DATE("d.m.Y H:i",time())."','".time()."','$money_out','������� ������� �� ������������ $username �� �������� ����','���������')") or die(mysql_error());
				
				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('$to_users','�������','��������� ���������','������������ $username �������� ��� ������� � ������� $money_out ���.','0','".time()."','0.0.0.0')") or die(mysql_error());
				
				$sql_mes = mysql_query("SELECT `id`,`email` FROM `tb_users` WHERE `username`='$to_users'");
			if(mysql_num_rows($sql_mes)>0) {
			$row_mes = mysql_fetch_array($sql_mes);

			$email_tab = $row_mes["email"];
			
			//$to = "$email_tab";
			$subj = "��� �������� ������� � ������� $domen";
		$msg = "<html><head><meta http-equiv='Content-Type' content='text/html; windows-1251'>
        
		        <style type='text/css'>
                        html, tbody {
                        display: table-row-group;
                        vertical-align: middle;
                        border-color: inherit;
	                }
                        </style>
                        </head>
                        <table align='center' border='0' cellpadding='5' cellspacing='0' style='width:100%;background-color:#e5e5e5;'>
                        <tbody>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#1b3c71;'>
                        <tbody>        
                        <tr><td align='left' style='background:url(https://".$_SERVER["HTTP_HOST"]."/img/logo/logo.gif) no-repeat bottom left;padding:46px;'></td></tr>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#FFF;'>
                        
                        <tr><td style='background-color:#48a0f7;font-size:16px;line-height:16px;text-align:center;padding:15px;color:#FFF;font-weight:normal;'>��� �������� �������� ������� �� ������� <a style='text-decoration:none;color:#FFF;' class='daria-goto-anchor' target='_blank' rel='noopener noreferrer'><b>".$domen."</b></a></td></tr>
                        <tr><td align='left' style='font-size:12px;font-family:Arial,Helvetica,sans-serif;line-height:20px;padding:20px;'>
                        <u>��� �������� �������� �������</u><br><br>
                        �� ������������: <b>".$username."</b><br>
                        
                        ��������� � ��� ������� ��� ���������! <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a>
                        </td></tr>
                        <tr><td align='left' style='border-top:1px solid #DDD;font-size:12px;padding:10px 20px;'>� ���������, �������������� ������ ����������� <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a></td></tr>
                        </tbody>
                        </table>
                        </td></tr>
                        </tbody>
                        </table>
                        </html>";

                $headers = "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=windows-1251\r\n";
		$headers.= "From: $domen <$domen@$domen>\r\n";
		$headers.= "FromName: $domen\r\n";
                $headers.= "X-Mailer: PHP/".phpversion()."\r\n";
                
                if(mail($email_tab, $subj, $msg, $headers)) {

			//echo '<span class="msg-ok">��������� ������� ���������� ������������ <u>'.$name.'</u>!</span><br>';

			$name = false;
			$username = false;
			$subject = false;
			$message = false;
		}
	}

				

				echo '<span class="msg-ok">������� ������� � ������� '.$money_out.' ���. ������� ���������� ������������ '.$to_users.'</span>';

			}elseif($method_pay==2) {

				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'".($money_out+$comis)."', `sent_users`=`sent_users`+'$money_out' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_out' WHERE `username`='$to_users'") or die(mysql_error());

				mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`) VALUES('3','0','$username','','".DATE("d.m.Y H:i",time())."','".time()."','".($money_out+$comis)."','������� ������� ������������ $to_users (� ���������� �� ��������� ����)','�������')") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`status_pay`,`tran_id`,`user`,`wmr`,`date`,`time`,`amount`,`method`,`status`) VALUES('4','0','$to_users','','".DATE("d.m.Y H:i",time())."','".time()."','$money_out','������� ������� �� ������������ $username �� ��������� ����','���������')") or die(mysql_error());

				mysql_query("INSERT INTO `tb_mail_in` (`namein`,`nameout`,`subject`,`message`,`status`,`date`,`ip`) VALUES('$to_users','�������','��������� ���������','������������ $username �������� ��� ������� � ������� $money_out ���.','0','".time()."','0.0.0.0')") or die(mysql_error());

				$sql_mes = mysql_query("SELECT `id`,`email` FROM `tb_users` WHERE `username`='$to_users'");
			if(mysql_num_rows($sql_mes)>0) {
			$row_mes = mysql_fetch_array($sql_mes);

			$email_tab = $row_mes["email"];
			
			//$to = "$email_tab";
			$subj = "��� �������� ������� � ������� $domen";
		$msg = "<html><head><meta http-equiv='Content-Type' content='text/html; windows-1251'>
        
		        <style type='text/css'>
                        html, tbody {
                        display: table-row-group;
                        vertical-align: middle;
                        border-color: inherit;
	                }
                        </style>
                        </head>
                        <table align='center' border='0' cellpadding='5' cellspacing='0' style='width:100%;background-color:#e5e5e5;'>
                        <tbody>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#1b3c71;'>
                        <tbody>        
                        <tr><td align='left' style='background:url(https://".$_SERVER["HTTP_HOST"]."/img/logo/logo.gif) no-repeat bottom left;padding:46px;'></td></tr>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#FFF;'>
                        
                        <tr><td style='background-color:#48a0f7;font-size:16px;line-height:16px;text-align:center;padding:15px;color:#FFF;font-weight:normal;'>��� �������� �������� ������� �� ������� <a style='text-decoration:none;color:#FFF;' class='daria-goto-anchor' target='_blank' rel='noopener noreferrer'><b>".$domen."</b></a></td></tr>
                        <tr><td align='left' style='font-size:12px;font-family:Arial,Helvetica,sans-serif;line-height:20px;padding:20px;'>
                        <u>��� �������� �������� �������</u><br><br>
                        �� ������������: <b>".$username."</b><br>
                        
                        ��������� � ��� ������� ��� ���������! <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a>
                        </td></tr>
                        <tr><td align='left' style='border-top:1px solid #DDD;font-size:12px;padding:10px 20px;'>� ���������, �������������� ������ ����������� <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a></td></tr>
                        </tbody>
                        </table>
                        </td></tr>
                        </tbody>
                        </table>
                        </html>";

                $headers = "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=windows-1251\r\n";
		$headers.= "From: $domen <$domen@$domen>\r\n";
		$headers.= "FromName: $domen\r\n";
                $headers.= "X-Mailer: PHP/".phpversion()."\r\n";
                
                if(mail($email_tab, $subj, $msg, $headers)) {

			//echo '<span class="msg-ok">��������� ������� ���������� ������������ <u>'.$name.'</u>!</span><br>';

			$name = false;
			$username = false;
			$subject = false;
			$message = false;
		}
	}
	
				echo '<span class="msg-ok">������� ������� � ������� '.$money_out.' ���. ������� ���������� ������������ '.$to_users.'</span>';
			}else{
				echo '<span class="msg-error">�� ����� ������� ����������� ��������</span>';
			}
		}

		$sql = mysql_query("SELECT `money`,`money_rb` FROM `tb_users` WHERE `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_row($sql);

			$money_users = $row["0"];
			$money_form = p_floor($money_users,2);
			$money_form = number_format($money_form,2,".","");
			$money_table = number_format($money_users,4,".","'");
			$money_users_rb = $row["1"];
			$money_form_rb = p_floor($money_users_rb,2);
			$money_form_rb = number_format($money_form_rb,2,".","");
			$money_table_rb = number_format($money_users_rb,4,".","'");
		}
		
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">������� ������� �������������</span>';
echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
echo '<div style="color:#0000ff; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="justify">';
echo '� ���� �� �� ����� ������ ��� �������� ��������, ���� �������� ��� ������ ������� ����?<br>
�������� ��� ������������� �������. �������� ��� ������� ������� - ��� �������� ������ �������� ��� ���� ��������.<br> 
�������� ������� ����� ��������� � ���������� ��� ��������� �����.';
echo '</div>';
echo '</div>';

		echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST" id="newform">';
		echo '<table class="tables">';
			echo '<thead><tr align="center"><th align="center" colspan="2" class="top">������� ������� �������������</th></tr></thead>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>�������� ������ ��������:</b></td>';
				echo '<td width="50%" align="left"><b>'.$money_table.'</b> ���.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>��������� ������ ��������:</b></td>';
				echo '<td width="50%" align="left"><b>'.$money_table_rb.'</b> ���.</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>�������� ����������� ��������:</b></td>';
				echo '<td width="50%" align="left">';
					echo '<select name="method_pay">';
						echo '<option value="1">� ��������� ����� �� �������� ����</option>';
						echo '<option value="2">� ���������� ����� �� ��������� ����</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>������� ����� ��������:</b></td>';
				echo '<td width="50%" align="left"><input type="text" class="ok12" name="money_out" maxlength="7" value="0.00"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>������� ����� ����������:</b></td>';
				echo '<td width="50%" align="left"><input type="text" class="ok12" name="to_users" maxlength="30" value=""></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="left"><b>������ ��� ��������:</b></td>';
				echo '<td width="50%" align="left"><input type="password" class="ok12" name="pass_oper" maxlength="20" value=""></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="center" colspan="2">�������� ����� <b>'.$convert_user_comis.'%</b>, �� �� ����� <b>'.$convert_user_comis_min.' ���.</b></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%" align="center" colspan="2" class="top"><div align="center"><input type="submit" class="sub-blue160" value="���������" style="float:none;"></div></td>';
			echo '</tr>';
		echo '</table>';
		echo '</form>';
		echo '<br><br>';
	}
}

@include('footer.php');?>