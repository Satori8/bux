<?php
if(!DEFINED("TESTS_AJAX")) {die ("Hacking attempt!");}
if($type_ads != "tests") {die ("Hacking attempt!");}
require_once(ROOT_DIR."/bbcode/bbcode.lib.php");

$sql_p = mysql_query("SELECT `sitewmr` FROM `tb_site` WHERE `id`='1'");
$site_wmr = mysql_result($sql_p,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_hit' AND `howmany`='1'");
$tests_cena_hit = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_nacenka' AND `howmany`='1'");
$tests_nacenka = number_format(mysql_result($sql,0,0), 0, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_min_pay' AND `howmany`='1'");
$tests_min_pay = number_format(mysql_result($sql,0,0), 2, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_quest' AND `howmany`='1'");
$tests_cena_quest = number_format(mysql_result($sql,0,0), 4, ".", "");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_color' AND `howmany`='1'");
$tests_cena_color = number_format(mysql_result($sql,0,0), 4, ".", "");

for($i=1; $i<=4; $i++) {
	$tests_cena_revisit[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_revisit' AND `howmany`='$i'");
	$tests_cena_revisit[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

for($i=1; $i<=2; $i++) {
	$tests_cena_unic_ip[0] = 0;
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_cena_unic_ip' AND `howmany`='$i'");
	$tests_cena_unic_ip[$i] = number_format(mysql_result($sql,0,0), 4, ".", "");
}

$geo_cod_arr = array(
	 1 => 'RU',  2 => 'UA',  3 => 'BY',  4 => 'MD',  5 => 'KZ',  6 => 'AM',  7 => 'UZ',  8 => 'LV',  9 => 'DE', 10 => 'GE', 
	11 => 'LT', 12 => 'FR', 13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 
	21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 29 => 'IR', 30 => 'GR', 
	31 => 'TR', 32 => 'PL', 33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO'
);

$geo_name_arr_ru = array(
	'RU' => '������', 	'UA' => '�������', 	'BY' => '����������', 	'MD' => '��������', 	'KZ' => '���������', 	'AM' => '�������', 
	'UZ' => '����������',	'LV' => '������',	'DE' => '��������', 	'GE' => '������', 	'LT' => '�����', 	'FR' => '�������', 
	'AZ' => '�����������', 	'US' => '���', 		'VN' => '�������', 	'PT' => '����������', 	'GB' => '������', 	'BE' => '�������', 
	'ES' => '�������', 	'CN' => '�����',	'TJ' => '�����������',  'EE' => '�������', 	'IT' => '������', 	'KG' => '��������',
	'IL' => '�������', 	'CA' => '������', 	'TM' => '������������', 'BG' => '��������',	'IR' => '����', 	'GR' => '������', 
	'TR' => '������', 	'PL' => '������',	'FI' => '���������', 	'EG' => '������', 	'SE' => '������', 	'RO' => '�������'
);

$method_pay_to[1] = "WebMoney";
$method_pay_to[2] = "RoboKassa";
$method_pay_to[3] = "Wallet One";
$method_pay_to[4] = "Interkassa";
$method_pay_to[5] = "Payeer";
$method_pay_to[6] = "Qiwi";
$method_pay_to[7] = "PerfectMoney";
$method_pay_to[8] = "YandexMoney";
$method_pay_to[9] = "MegaKassa";
$method_pay_to[20] = "FreeKassa";
$method_pay_to[21] = "AdvCash";
$method_pay_to[10] = "��������� ����";

if($option == "del") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		exit("OK");
	}else{
		exit("ERROR");
	}

}elseif($option == "add") {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 55) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 2000) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	if (get_magic_quotes_gpc()) { $description = stripslashes($description); }
	$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])>=0 && intval($_POST["revisit"])<=4)) ? intval(limpiarez($_POST["revisit"])) : "0";
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
	$unic_ip_user = (isset($_POST["unic_ip_user"]) && (intval($_POST["unic_ip_user"])>=0 && intval($_POST["unic_ip_user"])<=2)) ? intval($_POST["unic_ip_user"]) : "0";
	$date_reg_user = (isset($_POST["date_reg_user"]) && (intval($_POST["date_reg_user"])>=0 && intval($_POST["date_reg_user"])<=4)) ? intval($_POST["date_reg_user"]) : "0";
	$sex_user = ( isset($_POST["sex_user"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["sex_user"])) && intval(limpiarez($_POST["sex_user"]))>=0 && intval(limpiarez($_POST["sex_user"]))<=2 ) ? abs(intval(limpiarez($_POST["sex_user"]))) : 0;
	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$method_pay = (isset($_POST["method_pay"]) && preg_match("|^[\d]{1,2}$|", intval(limpiarez($_POST["method_pay"])))) ? intval(limpiarez($_POST["method_pay"])) : false;
	$black_url = getHost($url);

	//$money_add = ( isset($_POST["money_add"]) && preg_match( "|^[-+]?[\d]*[\.,]?[\d]*$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;
	//$money_add = ( isset($_POST["money_add"]) && preg_match( "|^[-+]?[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;
	  $money_add = ( isset($_POST["money_add"]) && preg_match( "|^[\d]*[\.,]?[\d]{0,2}$|", abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) ) ) ? abs(str_replace(",", ".", limpiarez($_POST["money_add"]))) : false;

	$revisit_tab[0] = "�������� ���� ������ 24 ����";
	$revisit_tab[1] = "�������� ���� ������ 3 ���";
	$revisit_tab[2] = "�������� ���� ������ ������";
	$revisit_tab[3] = "�������� ���� ������ 2 ������";
	$revisit_tab[4] = "�������� ���� ������ �����";

	$color_tab[0] = "���";
	$color_tab[1] = "��";

	$unic_ip_user_tab[0] = "���";
	$unic_ip_user_tab[1] = "��, 100% ����������";
	$unic_ip_user_tab[2] = "��������� �� ����� �� 2 ����� (255.255.X.X)";

	$date_reg_user_tab[0] = "��� ������������ �������";
	$date_reg_user_tab[1] = "�� 7 ���� � ������� �����������";
	$date_reg_user_tab[2] = "�� 7 ���� � ������� �����������";
	$date_reg_user_tab[3] = "�� 30 ���� � ������� �����������";
	$date_reg_user_tab[4] = "�� 90 ���� � ������� �����������";

	$sex_user_tab[0] = "��� ������������ �������";
	$sex_user_tab[1] = "������ �������";
	$sex_user_tab[2] = "������ �������";

	for($i=1; $i<=5; $i++) {
		$quest[$i] = (isset($_POST["quest$i"])) ? limitatexto(limpiarez($_POST["quest$i"]), 300) : false;
	}

	for($i=1; $i<=5; $i++) {
		for($y=1; $y<=3; $y++) {
			$answ[$i][$y] = (isset($_POST["answ$i$y"])) ? limitatexto(limpiarez($_POST["answ$i$y"]), 30) : false;
		}
	}

	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$id_country = array_search($val, $geo_cod_arr);
				$country_arr[] = $val;
				$country_arr_ru[] = $geo_name_arr_ru[$val];
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(", ", $country_arr))) : false;
	$country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;
	if($country_to!=false) {$country_to="$country_to";}else{$country_to="���";}


	if($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") {
		$quest[4] = ""; $answ[4][1] = ""; $answ[4][2] = ""; $answ[4][3] = "";
	}
	if($quest[5]=="" | $answ[5][1]=="" | $answ[5][2]=="" | $answ[5][3]=="") {
		$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
	}
	if( ($quest[5]!="" && $answ[5][1]!="" && $answ[5][2]!="" && $answ[5][3]!="") && ($quest[4]=="" | $answ[4][1]=="" | $answ[4][2]=="" | $answ[4][3]=="") ) {
		$quest[4] = $quest[5]; $answ[4][1] = $answ[5][1]; $answ[4][2] = $answ[5][2]; $answ[4][3] = $answ[5][3];
		$quest[5] = ""; $answ[5][1] = ""; $answ[5][2] = ""; $answ[5][3] = "";
	}

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

	if($title=="") {
		echo "ERROR"; exit("�� �� ������� ��������� �����!");
	}elseif($description=="") {
		echo "ERROR"; exit("�� �� ������� ���������� � ���������� �����!");
	}elseif(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row_bl = mysql_fetch_array($sql_bl);
		echo "ERROR"; exit("���� ".$row_bl["domen"]." ������������ � ������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])." �������: ".$row_bl["cause"]."");
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo "ERROR"; exit("�� �� ������� URL-����� �����!");
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo "ERROR"; exit("�� ������� ������� URL-����� �����!");
	}elseif($quest[1]=="") {
		echo "ERROR"; exit("�� �� ������� ������ ������!");
	}elseif($answ[1][1]=="" | $answ[1][2]=="" | $answ[1][3]=="") {
		echo "ERROR"; exit("�� �� ������� �������� ������ �� ������ ������!");
	}elseif($quest[2]=="") {
		echo "ERROR"; exit("�� �� ������� ������ ������!");
	}elseif($answ[2][1]=="" | $answ[2][2]=="" | $answ[2][3]=="") {
		echo "ERROR"; exit("�� �� ������� �������� ������ �� ������ ������!");
	}elseif($quest[3]=="") {
		echo "ERROR"; exit("�� �� ������� ������ ������!");
	}elseif($answ[3][1]=="" | $answ[3][2]=="" | $answ[3][3]=="") {
		echo "ERROR"; exit("�� �� ������� �������� ������ �� ������ ������!");
	}elseif($money_add=="") {
		echo "ERROR"; exit("C���� ���������� ������� ��������� �������� ������� �� �����");
	}elseif($money_add<$tests_min_pay) {
		echo "ERROR"; exit("����������� ����� ���������� - ".number_format($tests_min_pay,2,".","")." ���.");
	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		echo "ERROR"; exit(SFB_YANDEX($url));
	}else{
		$summa_dd = 0;
		if($quest[4]!="") $summa_dd+= $tests_cena_quest;
		if($quest[5]!="") $summa_dd+= $tests_cena_quest;

		$cena_user = ($tests_cena_hit + $summa_dd) / (($tests_nacenka+100)/100);
		$cena_advs = ($tests_cena_hit + $summa_dd + $tests_cena_revisit[$revisit] + $tests_cena_color * $color + $tests_cena_unic_ip[$unic_ip_user]);

		$cena_user = number_format($cena_user, 4, ".", "");
		$cena_advs = number_format($cena_advs, 4, ".", "");
		$money_add = number_format($money_add, 2, ".", "");

		$count_tests = floor(bcdiv($money_add, $cena_advs));

		if($quest[4]=="") unset($quest[4], $answ[4]);
		if($quest[5]=="") unset($quest[5], $answ[5]);

		$questions = serialize($quest);
		$answers = serialize($answ);

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);

		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());
		mysql_query("DELETE FROM `tb_ads_tests` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_tests` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`wmid`='$wmid_user',`username`='$username',`date`='".time()."',`date_edit`='".time()."',`title`='$title',`description`='$description',`url`='$url',`questions`='$questions',`answers`='$answers',`geo_targ`='$country',`revisit`='$revisit',`color`='$color',`date_reg_user`='$date_reg_user',`unic_ip_user`='$unic_ip_user',`sex_user`='$sex_user',`cena_user`='$cena_user',`cena_advs`='$cena_advs',`money`='$money_add',`balance`='0',`ip`='$laip' WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
			mysql_query("INSERT INTO `tb_ads_tests`(`status`,`session_ident`,`merch_tran_id`,`method_pay`,`wmid`,`username`,`date`,`date_edit`,`title`,`description`,`url`,`questions`,`answers`,`geo_targ`,`revisit`,`color`,`date_reg_user`,`unic_ip_user`,`sex_user`,`cena_user`,`cena_advs`,`money`,`balance`,`ip`) 
			VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$wmid_user','$username','".time()."','".time()."','$title','$description','$url','$questions','$answers','$country','$revisit','$color','$date_reg_user','$unic_ip_user','$sex_user','$cena_user','$cena_advs','$money_add','0','$laip')") or die(mysql_error());
		}

        	$sql_id = mysql_query("SELECT `id`,`description`,`questions`,`answers`,`geo_targ` FROM `tb_ads_tests` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row_id = mysql_fetch_array($sql_id);
		        $id_zakaz = $row_id["id"];
		        $description_to = $row_id["description"];
			$questions_to = $row_id["questions"];
			$answers_to = $row_id["answers"];
			$geo_targ = (isset($row_id["geo_targ"]) && trim($row_id["geo_targ"])!=false) ? explode(", ", $row_id["geo_targ"]) : array();
		}else{
			echo "ERROR"; exit("NO ID");
		}

		$description_to = new bbcode($description_to);
		$description_to = $description_to->get_html();
		$description_to = str_replace("&amp;", "&", $description_to);

		echo "OK";

		echo '<span class="msg-ok" style="margin-bottom:0px;">��� ����� ������ � ����� �������� ������������� ����� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td align="left" width="190">���� � (ID)</td><td align="left">'.number_format($merch_tran_id, 0,".", "").' ('.$id_zakaz.')</td></tr>';
			echo '<tr><td align="left">��������� �����</td><td align="left">'.$title.'</td></tr>';
			echo '<tr><td align="left">���������� ��� ������������</td><td align="left">'.$description_to.'</td></tr>';
			echo '<tr><td align="left">URL �����</td><td align="left"><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';

			for($i=1; $i<=count($quest); $i++){
				echo '<tr><td align="left">������ �'.$i.'</td><td align="left">'.$quest[$i].'</td></tr>';
				echo '<tr>';
					echo '<td align="left">�������� ������</td>';
					echo '<td align="left">';
						for($y=1; $y<=3; $y++){
							echo '<span style="color: '.($y==1 ? "#009125;" : "#FF0000").'">'.$answ[$i][$y].'</span>'.($y!=3 ? "<br>" : "").'';
						}
					echo '</td>';
				echo '</tr>';
			}

			echo '<tr><td align="left">���������� ������������</td><td align="left">'.$revisit_tab[$revisit].'</td></tr>';
			echo '<tr><td align="left">�������� ����</td><td align="left">'.$color_tab[$color].'</td></tr>';
			echo '<tr><td align="left">���������� IP</td><td align="left">'.$unic_ip_user_tab[$unic_ip_user].'</td></tr>';
			echo '<tr><td align="left">�� ���� �����������</td><td align="left">'.$date_reg_user_tab[$date_reg_user].'</td></tr>';
			echo '<tr><td align="left">�� �������� ��������</td><td align="left">'.$sex_user_tab[$sex_user].'</td></tr>';

			echo '<tr>';
				echo '<td align="left">������������</td>';
				echo '<td>';
					if(count($geo_targ)>0) {
						for($i=0; $i<count($geo_targ); $i++){
							echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_targ[$i]).'.gif" alt="'.$geo_name_arr_ru[strtoupper($geo_targ[$i])].'" title="'.$geo_name_arr_ru[strtoupper($geo_targ[$i])].'" align="absmiddle" style="margin:0; padding:0; padding-left:1px;" /> ';
						}
					}else{
						echo '��� ������';
					}
				echo '</td>';
			echo '</tr>';

			echo '<tr><td align="left">���������� ����������</td><td align="left">'.$count_tests.'</td></tr>';
			//if(isset($cab_text)) echo "$cab_text";
			echo '<tr><td align="left">������ ������</td><td align="left"><b>'.$method_pay_to[$method_pay].'</b>, ���� ���������� �������� � ������� 24 �����</td></tr>';

			if($method_pay==8) {
				if(($money_add*0.005)<0.01) {$money_add_ym = $money_add + 0.01;}else{$money_add_ym = number_format(($money_add*1.005),2,".","");}

				echo '<tr><td>��������� ������:</td><td><b style="color:green;">'.number_format($money_add,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td>����� � ������</td><td><b style="color:#FF0000;">'.number_format($money_add_ym,2,".","`").'</b> <b>���.</b></td></tr>';
			}elseif($method_pay==3) {
				$money_add_w1 = number_format(($money_add * 1.05), 2, ".", "");

				echo '<tr><td><b>��������� ������</b></td><td><b style="color:#76B15D;">'.number_format($money_add,2,".","`").'</b> <b>���.</b></td></tr>';
				echo '<tr><td><b>����� � ������</b></td><td><b style="color:#76B15D;">'.number_format($money_add_w1,2,".","`").'</b> <b>���.</b></td></tr>';

			}else{
				echo '<tr><td>����� � ������</td><td><b style="color:#FF0000;">'.number_format($money_add,2,".","`").'</b> <b>���.</b></td></tr>';
			}
		echo '</table>';

		$shp_item = "21";
		$inv_desc = "������ �������: �����, ����:$merch_tran_id";
		$inv_desc_utf8 = iconv("CP1251", "UTF-8", $inv_desc);
		$inv_desc_en = "Pay advertise: tests, order:$merch_tran_id";
		$money_add = number_format($money_add,2,".","");

		echo '<table class="tables" cellspacing="0" cellpadding="0">';
		echo '<tr align="center">';
			echo '<td align="left" width="160" style="border-right:none;">';
				if($method_pay==10 && $username!=false) {
					echo '<span onClick="PayAds(\''.$id_zakaz.'\');" class="proc-btn" style="float:left;">��������</span>';
				}elseif($method_pay==10 && $username==false) {
					echo '';
				}else{
					require_once(ROOT_DIR."/method_pay/method_pay.php");
				}
			echo '</td>';
			echo '<td align="center" style="border-left:none;">';
				echo '<span onClick="DeleteAds(\''.$id_zakaz.'\');" class="sub-red" style="float:right;">�������</span>';
				echo '<span onClick="ChangeAds();" class="sub-green" style="float:right;">��������</span>';
			echo '</td>';
		echo '</tr>';
		echo '</table>';

		if($method_pay==10 && $username==false) {
			echo '<span class="msg-error">��� ������ � ���������� ����� ���������� ��������������!</span>';
		}

		if($method_pay==10 && $username!=false) {
			?><script type="text/javascript" language="JavaScript">
			function PayAds(id) {
				$.ajax({
					type: "POST", url: "/advertise/ajax/ajax_adv_add.php?rnd="+Math.random(), 
					data: {'op':'pay', 'type':'tests', 'id':id}, 
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						if (data == "OK") {
							$("html, body").animate({scrollTop:0}, 700);

							gebi("OrderForm").style.display = "";
							gebi("OrderForm").innerHTML = '<span class="msg-ok">��� ���� ������� ��������!<br>�������, ��� ����������� �������� ������ �������</span>';
							setTimeout(function() {document.location.href = "/cabinet_ads?ads=tests";}, 2000); clearTimeout();
							return false;
						}else{
							gebi("info-msg-pay").style.display = "";
							gebi("info-msg-pay").innerHTML = '<span class="msg-error">' + data + '</span>';
							setTimeout(function() {$("#info-msg-pay").fadeOut("slow")}, 5000); clearTimeout();
							return false;
						}
					}
				});
			}
			</script><?php
		}

		exit();	
	}

}elseif($option == "pay") {
	if($username==false) {
		exit("��� ������ � ���������� ����� ���������� ��������������!");
	}else{
		$sql_id = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {
			$row = mysql_fetch_array($sql_id);
			$money_pay = $row["money"];
			$merch_tran_id = $row["merch_tran_id"];

			if($money_user_rb>=$money_pay) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
				$reit_rek = mysql_result($sql,0,0);

				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
				$reit_ref_rek = mysql_result($sql,0,0);

				$reit_add_1 = floor($money_pay/10) * $reit_rek;
				$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;

				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1',`money_rb`=`money_rb`-'$money_pay',`money_rek`=`money_rek`+'$money_pay' WHERE `username`='$username'") or die(mysql_error());
				mysql_query("UPDATE `tb_ads_tests` SET `status`='1',`date`='".time()."',`wmid`='$wmid_user',`money`='$money_pay',`balance`='$money_pay' WHERE `id`='$id' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
				mysql_query("INSERT INTO `tb_history` (`user`, `date`, `amount`, `method`, `status`, `tipo`) 
				VALUES('$username', '".DATE("d.m.Y H:i")."', '$money_pay', '���������� ������� ��������� �������� (�����, ID:$id)', '��������', 'reklama')") or die(mysql_error());

				stat_pay("tests", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);

				exit("OK");

			}else{
				exit("�� ����� ��������� ����� ������������ ������� ��� ������ �������!");
			}
		}else{
			exit("������ ������� � �$id �� ����������, ���� ����� ��� ��� �������!");
		}
	}
}else{
	exit("ERROR NO OPTION!");
}

?>