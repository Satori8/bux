<?php
$pagetitle="���������� ���������� �����";
include('header.php'); 

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
$bon_popoln =  mysql_result($sql,0);


if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">��� ������� � ���� �������� ���������� ��������������!</span>';
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z�-��-�0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	if(count($_POST)>0 && isset($_POST["id_pay"])) {
		$id_pay = (isset($_POST["id_pay"])) ? intval(($_POST["id_pay"])) : false;

		$sql_id = mysql_query("SELECT `id`,`money` FROM `tb_add_pay` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$money_pay = $row["money"];

			$sql_user = mysql_query("SELECT `wmid`,`wm_purse`,`money` FROM `tb_users` WHERE `username`='$username'");
			$row_user = mysql_fetch_array($sql_user);
			$wmid_user = $row_user["wmid"];
			$wmr_user = $row_user["wm_purse"];
			$money_user = $row_user["money"];

                        

			if($money_user>=$money_pay) {
			
					
				mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`+'$money_pay', `money`=`money`-'$money_pay', `money_in`=`money_in`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_add_pay` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) VALUES('$username', '".DATE("d.m.Y H:i")."', '$money_pay', '���������� �������', '���������', 'popoln')") or die(mysql_error());

				echo '<span class="msg-ok">�������� ������ �������. ��������� ���� �������� �� '.$money_pay.' ���.</span>';
				include('footer.php');
				exit();
			}else{
				echo '<span class="msg-error">�� ����� �������� ����� ������������ ������� ��� ���������� ������� ���������� �����!</span>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<span class="msg-error">���� �� ������!</span>';
			include('footer.php');
			exit();
		}
	}

	if(isset($_POST["money_add"])) {
		$money_add = (isset($_POST["money_add"])) ? floatval(str_replace(",",".",trim($_POST["money_add"]))) : false;
		$money_add = number_format($money_add,2,".","");
		$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(trim($_POST["method_pay"])))) ? intval(trim($_POST["method_pay"])) : false;
		$ip = getRealIP();

		if($username == false) {
			echo '<span class="msg-error">����� ������������ �� ���������!</span><br>';
		}else{
			if($money_add != false && $money_add >= 1.00) {
	
				if($method_pay==7 && $money_add <6) {
					echo '<span class="msg-error">����� ��� ���������� ����� PerfectMoney.com ������ ���� �� ����� 6.00 ���!</span><br>';
					include('footer.php');
					exit();
				}elseif($method_pay == 21 && $money_add < 6) {
		echo '<span class="msg-error">����� ��� ���������� ����� Advanced Cash ������ ���� �� ����� 6.00 ���!</span>';
					include('footer.php');
					exit();
				}elseif($method_pay == 8 && $money_add < 3) {
		echo '<span class="msg-error">����� ��� ���������� ����� YandexMoney ������ ���� �� ����� 3.00 ���!</span>';
					include('footer.php');
					exit();
				}elseif($method_pay==false) {
					echo '<span class="msg-error">�� ������ ������ ������!</span>';
					include('footer.php');
					exit();
				}

				$system_pay[1] = "WebMoney Merchant";
				$system_pay[2] = "RoboKassa.com";
				$system_pay[3] = "Wallet One (������ �������)";
				$system_pay[4] = "Interkassa.com";
				$system_pay[5] = "Payeer.com";
				$system_pay[6] = "Qiwi.com";
				$system_pay[7] = "PerfectMoney.com";
				$system_pay[8] = "YandexMoney";
                $system_pay[9] = "MEGAKASSA.RU";
                $system_pay[20] = "Free-Kassa";
               $system_pay[21] = "AdvCash";
				$system_pay[10] = "����������� � ��������� �����";

				@require_once("".$_SERVER['DOCUMENT_ROOT']."/curs/curs.php");
				$money_add_usd = number_format(round(($money_add/$CURS_USD),2),2,".","");

				require('config.php');
				$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
				$merch_tran_id = mysql_result($sql_tranid,0,0);
				mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());

				mysql_query("DELETE FROM `tb_add_pay` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

				$check = mysql_query("SELECT `id` FROM `tb_add_pay` WHERE `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
				if(mysql_num_rows($check)>0) {
					mysql_query("UPDATE `tb_add_pay` SET `merch_tran_id`='$merch_tran_id', `wmid`='$wmiduser', `date`='".time()."', `ip`='$laip', `money`='$money_add', `money_usd`='$money_add_usd' WHERE `status`='0' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("INSERT INTO `tb_add_pay` (`status`, `merch_tran_id`, `username`, `wmid`, `date`, `ip`, `money`, `money_usd`) VALUES('0', '$merch_tran_id', '$username', '$wmiduser', '".time()."', '$laip', '$money_add', '$money_add_usd')") or die(mysql_error());
				}

				$sql_id = mysql_query("SELECT `id` FROM `tb_add_pay` WHERE `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
				$id_zakaz = mysql_result($sql_id,0,0);

				//echo '<span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
				//echo '<table class="tables">';
					//echo '<tr><td><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
					//echo '<tr><td><b>���������� �������:</b></td><td>���������� ������� �������� '.$username.' (#id '.$partnerid.')</td></tr>';
					//echo '<tr><td><b>������ ������:</b></td><td><b>'.$system_pay[$method_pay].'</b>, ���� ���������� �������� � ������� 1 ����</td></tr>';
					echo '<div id="load-money-add"><span class="msg-ok">��� ����� ������ � ����� �������� ������������� ����� ������.</span>';
					echo '<div class="cash-moneyadd"><div><span style="color:#005E91">���� �:</span>'.$merch_tran_id.'</div>';
					echo '<div><span style="color:#005E91">����������:</span>���������� ���������� ����� �������� '.$username.' (#id '.$partnerid.')</div>';
					echo '<div><span style="color:#005E91">������ ������:</span>'.$system_pay[$method_pay].'</div>';
				
						//echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
				
				

					if($method_pay==8) {
						if(($money_add*0.005)<0.01) {$money_add_ym = $money_add + 0.01;}else{$money_add_ym = number_format(($money_add*1.005),2,".","");}
						//if(($money_add*0.005)<0.01) {$money_add_ym = $money_add + 0.01;}else{$money_add_ym = number_format(($money_add*1.005),2,".","");}

					echo '<div><span style="color:#005E91">����� � ����������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
						echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add_ym,2,".","`").'</b> ���.</div>';
						
					}elseif($method_pay==3) {
				$money_add_w1 = number_format(($money_add * 1.05), 2, ".", "");

				echo '<div><span style="color:#005E91">����� � ����������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
				echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add_w1,2,".","`").'</b> ���.</div>';

					}elseif($method_pay==7) {
						echo '<div><span style="color:#005E91">����� � ����������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
						echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add_usd,2,".","`").'</b> USD.</div>';
						
					}elseif($method_pay==13) {
						echo '<div><span style="color:#005E91">����� � ����������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
						echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add_usd,2,".","`").'</b> USD.</div>';
					}else{
						echo '<div><span style="color:#005E91">����� � ������:</span> <b style="color:#0000ff;"">'.number_format($money_add,2,".","`").'</b> ���.</div>';
					}
					
					echo '</div>';
				echo '</div>';
					
				//echo '</table>';

				$shp_item = "1";
				$inv_desc = "���������� ������� �������� $username (ID:$partnerid)";
				$inv_desc_utf8 = iconv("CP1251", "UTF-8", "���������� ������� �������� $username (ID:$partnerid)");
				$inv_desc_en = "Popolnenie balansa: account $username (ID:$partnerid)";
				$money_add = number_format($money_add,2,".","");

				require_once("".$_SERVER['DOCUMENT_ROOT']."/method_pay/method_pay.php");

			}else{
				echo '<span class="msg-error">����������� ����� ���������� 1.00 ���.!</span><br>';
			}
		}

	}else{
	    echo '<div class="blok" style="text-align:center;">';
  echo '<h1>���������� ���������� �����</h1><br>';
    echo '<div id="load-money-add">';
      echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
        echo '<span class="status" style="font-size:14px;">������� �����</span><br><span class="desctext">����������� ����� ���������� 1 ���.</span>';
        echo '<div class="sum">';
          echo '<input type="text" class="summ" value="1" name="money_add" id="newform1"  size="5" maxlength="6">';
        echo '</div>';
        echo '<span class="status" style="font-size:14px;">�������� ������� ��� ������ ����������</span>';
        echo '<br><br>';
         if($bon_popoln>0) echo '<td colspan="2" align="center"><b><span style="color:#ea1d1d; font-size: 13px;">����� �� ����������  </span></b> <b><span style="color:#fd0505;">'.number_format($bon_popoln, 0, ".", "").' % </span></b><br><b><span style="color:#ea1d1d; font-size: 13px;">����������� ������ ��� ���������� ����� ��������� �������!</b></td>';
        echo '<div style="text-align: center; width: 100%; margin: 10px auto;">';
	      
      echo '<button name="method_pay" value="10" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-os1">';
      echo '<div><div><div><span class="line-green"><span id="result">1</span> ���.</span></div></div></div>';
	  echo '</div> </button>';
    
    if($site_pay_wm!=1) {
        echo '<div class="cash-wm1">';
    	  echo '<div class="cash-wm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="1" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-wm1">';
    echo '<div><div><div><span class="line-green"><span id="result1">1</span>���. (+0.8%)</div></div></div>';
	echo '</div> </button>';
	}
    
     if($site_pay_ym!=1) {
        echo '<div class="cash-yd1">';
    	  echo '<div class="cash-yd1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="8" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-yd1">';
    echo '<div><div><div><span class="line-green"><span id="result2">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	 if($site_pay_robo!=1) {
        echo '<div class="cash-rb1">';
    	  echo '<div class="cash-rb1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="2" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-rb1">';
    echo '<div><div><div><span class="line-green"><span id="result3">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
  	
	if($site_pay_mega!=1) {
    	  echo '<div class="cash-ik1">';
    	  echo '<div class="cash-ik1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
	echo '<button name="method_pay" value="9" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ik1">';
    echo '<div><div><div><span class="line-green"><span id="result4">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}

   if($site_pay_qw!=1) {
    	  echo '<div class="cash-qw1">';
    	  echo '<div class="cash-qw1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="6" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-qw1">';
    echo '<div><div><div><span class="line-green"><span id="result5">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
    
    if($site_pay_payeer!=1) {
    	  echo '<div class="cash-pr1">';
    	  echo '<div class="cash-pr1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="5" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pr1">';
    echo '<div><div><div><span class="line-green"><span id="result6">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
    
  if($site_pay_pm!=1) {
    	  echo '<div class="cash-pm1">';
    	  echo '<div class="cash-pm1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
	}else{
     echo '<button name="method_pay" value="7" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-pm1" >';
     echo '<div><div><div><span class="line-green"><span id="result7">0.02</span> USD</span></div></div></div>';
	 echo '</div> </button>';
	}
	
		if($site_pay_advcash!=1) {
    	  echo '<div class="cash-ah1">';
    	  echo '<div class="cash-ah1">';
    	  echo '<div><div><div<span class="line-red">�������� ��������</span></div></div></div>';
    	  echo '</div>';
    	  echo '</div>';
	}else{
    echo '<button name="method_pay" value="21" style="background-color: #fbf3ef; color: black; border: 2px solid #fbf3ef;"><div class="cash-ah1">';
    echo '<div><div><div><span class="line-green"><span id="result9">1</span> ���.</span></div></div></div>';
	echo '</div> </button>';
	}
	
	
	
       echo '</form>';
     echo '</div>';
 echo '</div>';
  echo '</div>';
 ?>
 <script>
  var inq = document.getElementById('newform1');

  inq.oninput = function() {
    document.getElementById('result').innerHTML = inq.value;
	document.getElementById('result1').innerHTML = inq.value;
	document.getElementById('result2').innerHTML = inq.value;
	document.getElementById('result3').innerHTML = inq.value;
	document.getElementById('result4').innerHTML = inq.value;
	document.getElementById('result5').innerHTML = inq.value;
	document.getElementById('result6').innerHTML = inq.value;
	document.getElementById('result7').innerHTML = Math.round((inq.value)/60 * 100) / 100;
	//document.getElementById('result8').innerHTML = inq.value;
    document.getElementById('result9').innerHTML = inq.value;
    //document.getElementById('result10').innerHTML = inq.value;
    //document.getElementById('result10').innerHTML = inq.value;
  };
</script>
 <?

	}
}

include('footer.php');?>