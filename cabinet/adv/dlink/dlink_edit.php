<?php
if(!DEFINED("DLINK_EDIT")) {die ("Hacking attempt!");}
//require(DOC_ROOT."/advertise/func_load_banners.php");

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">�������������� ������ � �������� � '.$id.'</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_dlink` WHERE `id`='$id' AND ytflag='0' AND`username`='$username' AND `type_serf`!='10'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits' AND `howmany`='1'");
	$dlink_cena_hits = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_hits_bs' AND `howmany`='1'");
	$dlink_cena_hits_bs = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_nacenka' AND `howmany`='1'");
	$dlink_nacenka = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_color' AND `howmany`='1'");
	$dlink_cena_color = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_active' AND `howmany`='1'");
	$dlink_cena_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_ot' AND `howmany`='1'");
	$dlink_timer_ot = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_timer_do' AND `howmany`='1'");
	$dlink_timer_do = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_timer' AND `howmany`='1'");
	$dlink_cena_timer = mysql_result($sql,0,0);

	$dlink_cena_revisit[0] = 0;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='1'");
	$dlink_cena_revisit[1] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_revisit' AND `howmany`='2'");
	$dlink_cena_revisit[2] = mysql_result($sql,0,0);

	$dlink_cena_unic_ip[0] = 0;

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='1'");
	$dlink_cena_unic_ip[1] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_unic_ip' AND `howmany`='2'");
	$dlink_cena_unic_ip[2] = mysql_result($sql,0,0);
	
/*	$country = (isset($_POST["country"]) && count($_POST["country"])>0) ? (array_map('mysql_real_escape_string', $_POST["country"])) : false;
	$geo_cod_arr = array(
		1  => 'RU', 2  => 'UA', 3  => 'BY', 4  => 'MD', 
		5  => 'KZ', 6  => 'AM', 7  => 'UZ', 8  => 'LV', 
		9  => 'DE', 10 => 'GE',	11 => 'LT', 12 => 'FR', 
		13 => 'AZ', 14 => 'US', 15 => 'VN', 16 => 'PT', 
		17 => 'GB', 18 => 'BE', 19 => 'ES', 20 => 'CN', 
		21 => 'TJ', 22 => 'EE', 23 => 'IT', 24 => 'KG', 
		25 => 'IL', 26 => 'CA', 27 => 'TM', 28 => 'BG', 
		29 => 'IR', 30 => 'GR', 31 => 'TR', 32 => 'PL', 
		33 => 'FI', 34 => 'EG', 35 => 'SE', 36 => 'RO',
	);
	$geo_name_arr = array(
		1  => '������', 	2  => '�������', 	3  => '����������', 	4  => '��������',
 		5  => '���������', 	6  => '�������', 	7  => '����������', 	8  => '������',
		9  => '��������', 	10 => '������', 	11 => '�����', 		12 => '�������', 
		13 => '�����������', 	14 => '���', 		15 => '�������', 	16 => '����������',
		17 => '������', 	18 => '�������', 	19 => '�������', 	20 => '�����',
		21 => '�����������', 	22 => '�������', 	23 => '������', 	24 => '��������',
		25 => '�������', 	26 => '������', 	27 => '������������', 	28 => '��������',
		29 => '����', 		30 => '������', 	31 => '������', 	32 => '������',
		33 => '���������', 	34 => '������', 	35 => '������', 	36 => '�������',
	);
	if(is_array($country)) {
		foreach($country as $key => $val) {
			if(array_search($val, $geo_cod_arr)) {
				$country_arr[] = $val;
				$country_arr_ru[] = $geo_name_arr[$key+1];
			}
		}
	}
	$country = isset($country_arr) ? trim(strtoupper(implode(', ', $country_arr))) : false;
	$country_to = isset($country_arr_ru) ? trim(strtoupper(implode(', ', $country_arr_ru))) : false;
	if($country_to!=false) {$country_to="$country_to";}else{$country_to="���";}*/

	if($row["type_serf"]==1) {
		$cena_hits = $dlink_cena_hits;
	}elseif($row["type_serf"]==2) {
		$cena_hits = $dlink_cena_hits_bs;
	}elseif($row["type_serf"]==3) {
		$cena_hits = $dlink_cena_hits;
	}elseif($row["type_serf"]==4) {
		$cena_hits = $dlink_cena_hits_bs;
	}elseif($row["type_serf"]==5) {
		$cena_hits = $dlink_cena_hits;
	}else{
		$cena_hits = $dlink_cena_hits;
	}

	#### �������� ####
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='1'");
	$dlink_cena_nolimit[1] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='2'");
	$dlink_cena_nolimit[2] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='3'");
	$dlink_cena_nolimit[3] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='dlink_cena_nolimit' AND `howmany`='4'");
	$dlink_cena_nolimit[4] = mysql_result($sql,0,0);
	#### �������� ####

	?><script type="text/javascript" language="JavaScript">

	function save_ads(id, type, status, bezlimit) {
		$('#info-msg-cab').hide(); 
		var type_serf = $.trim($('#type_serf').val());
		var nolimit = $.trim($('#nolimit').val());
		var url = $.trim($('#url').val());
		var title = $.trim($('#title').val());
		var description = $.trim($('#description').val());
		var url_banner = $.trim($('#url_banner').val());
		var timer = $.trim($('#timer').val());
		var limit_d = $.trim($('#limit_d').val());
		var limit_h = $.trim($('#limit_h').val());
		var color = $.trim($('#color').val());
		var active = $.trim($('#active').val());
		var revisit = $.trim($('#revisit').val());

		var unic_ip = $.trim($('#unic_ip').val());
		var new_users = $.trim($('#new_users').val());
		var no_ref = $.trim($('#no_ref').val());
		var sex_adv = $.trim($('#sex_adv').val());
		var to_ref = $.trim($('#to_ref').val());
		var country = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();
		/*alert(country);*/

		rtimer = parseInt(timer);
		rlimit_d = parseInt(limit_d);
		rlimit_h = parseInt(limit_h);
		timer_ot = <?=$dlink_timer_ot;?>;

		if ((url == '') | (url == 'http://') | (url == 'https://')) {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� URL-����� �����!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("url");
		} else if ((type_serf==1 | type_serf==3) && title == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ��������� ������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("title");
		} else if ((type_serf==1 | type_serf==3) && description == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������� �������� ������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("description");
		} else if ((type_serf==2 | type_serf==4) && ((url_banner == '') | (url_banner == 'http://') | (url_banner == 'https://'))) {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� URL-����� �������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("url_banner");

		} else {
			if (status == 0 | status == 3) {
				if (timer == "" | timer == 0) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">������� ����� ���������!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("timer");
					return false;
				} else if (isNaN(timer)) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">����� ��������� ������ ���� ��������!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("timer");
					return false;
				} else if (timer != rtimer) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">����� ��������� ������ ���� ����� ������!</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("timer");
					return false;
				} else if (rtimer < timer_ot | rtimer > <?=$dlink_timer_do;?>) {
					gebi("info-msg-cab").innerHTML = '<span class="msg-error">����� ��������� ������ ���� �� ' + timer_ot + ' �� <?=$dlink_timer_do;?> ���.</span>';
					gebi("info-msg-cab").style.display = "";
					setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
					focus_bg("timer");
					return false;
				}
			}

			if (isNaN(limit_d) | isNaN(limit_h)) {
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">����������� ���������� ������� ������ ���� ��������!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
				return false;
			} else if (limit_d != rlimit_d | limit_h != rlimit_h) {
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">����������� ���������� ������� � ����� ������ ���� ����� ������!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
				focus_bg("limits");
				return false;
			} else if (rlimit_d<0 | rlimit_h<0) {
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">����������� ���������� ������� ������ ���� �������������!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
				focus_bg("limits");
				return false;
			} else {
				$.ajax({
					type: "POST", url: "/cabinet/ajax/ajax_adv.php", 
					data: {
						'op':'save', 
						'type':type, 
						'id':id, 
						'type_serf':type_serf, 
						'nolimit':nolimit, 
						'url':url, 
						'title':title, 
						'description':description, 
						'url_banner':url_banner, 
						'timer':timer, 
						'limit_d':limit_d, 
						'limit_h':limit_h, 
						'color':color, 
						'active':active, 
						'revisit':revisit, 
						'new_users':new_users, 
						'unic_ip':unic_ip, 
						'no_ref':no_ref, 
						'sex_adv':sex_adv, 
						'to_ref':to_ref,
						'country[]':country
					}, 
					beforeSend: function() { $('#loading').show(); }, 
					success: function(data) { 
						$('#loading').hide(); 
						if (data == "OK") {
							document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";
						} else {
							gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
							gebi("info-msg-cab").style.display = "";
							setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 5000); clearTimeout();
							return false;
						}
					}
				});
			}
		}
	}

	function obsch() {
		var nolimit = $.trim($('#nolimit').val());
		var timer = $.trim($('#timer').val());
		var color = $.trim($('#color').val());
		var type_serf = $.trim($('#type_serf').val());
		var active = $.trim($('#active').val());
		var revisit = $.trim($('#revisit').val());
		var unic_ip = $.trim($('#unic_ip').val());
		//var country = $.trim($('#country').val());
		var country = $.trim($('input[id="country[]"]:checked').val());

		var cena_timer = <?=$dlink_cena_timer;?>;
		var cena_color = <?=$dlink_cena_color;?>;
		var cena_active = <?=$dlink_cena_active;?>;
		var cena_nac = <?=(100+$dlink_nacenka)/100;?>;
		if(type_serf==1 | type_serf==2) {
			$("#type_serf_z").css("display", "table-row");
			$("#type_serf_zx").css("display", "");
			$("#type_serf_r").css("display", "");
		$("#type_serf_t").css("display", "");
		$("#type_serf_y").css("display", "");
		$("#type_serf_u").css("display", "");
		}else{
			$("#type_serf_z").css("display", "none");
			$("#type_serf_zx").css("display", "none");
			$("#type_serf_r").css("display", "none");
		$("#type_serf_t").css("display", "none");
		$("#type_serf_y").css("display", "none");
		$("#type_serf_u").css("display", "none");
		} 
	
		if(type_serf==2 | type_serf==4) {
			var color = 0;
			var cena_hits = <?php echo $dlink_cena_hits_bs;?>;
			gebi('type_serf_a').style.display = 'none';
			gebi('type_serf_b').style.display = 'none';
			gebi('type_serf_c').style.display = '';
			gebi('type_serf_d').style.display = 'none';
		} else { 
			var cena_hits = <?php echo $dlink_cena_hits;?>;
			gebi('type_serf_a').style.display = '';
			gebi('type_serf_b').style.display = '';
			gebi('type_serf_c').style.display = 'none';
			gebi('type_serf_d').style.display = '';
		}

		if(gebi('timer_serf')) {
			if(type_serf==3 | type_serf==4) {
				gebi('timer_serf').innerHTML = '(<?php echo "�� 20 ���. �� $dlink_timer_do ���.";?>)';
			} else {
				gebi('timer_serf').innerHTML = '(<?php echo "�� $dlink_timer_ot ���. �� $dlink_timer_do ���.";?>)';
			}
		}

		if(timer<<?=$dlink_timer_ot;?>) { timer = <?=$dlink_timer_ot;?>;}
		if((type_serf==3 | type_serf==4) && timer<20) { timer = 20;}
		if(timer><?=$dlink_timer_do;?>) { timer = <?=$dlink_timer_do;?>;}
		if(revisit==0) {var cena_revisit = 0;}
		if(revisit==1) {var cena_revisit = <?=$dlink_cena_revisit[1];?>;}
		if(revisit==2) {var cena_revisit = <?=$dlink_cena_revisit[2];?>;}
		if(unic_ip==0) {var cena_unic_ip = 0;}
		if(unic_ip==1) {var cena_unic_ip = <?php echo $dlink_cena_unic_ip[1];?>;}
		if(unic_ip==2) {var cena_unic_ip = <?php echo $dlink_cena_unic_ip[2];?>;}

		if(nolimit==1) {
			var price = <?=$dlink_cena_nolimit[1];?>;
		}else if(nolimit==2) {
			var price = <?=$dlink_cena_nolimit[2];?>;
		}else if(nolimit==3) {
			var price = <?=$dlink_cena_nolimit[3];?>;
		}else if(nolimit==4) {
			var price = <?=$dlink_cena_nolimit[4];?>;
		}else{
			nolimit = 0;
		}

		if(nolimit>0) {
			gebi('bl2').style.display = 'none';
			gebi('bl21').innerHTML = '<b>20</b> ���.';
			gebi('bl3').style.display = 'none';
			gebi('bl31').innerHTML = '<b>��</b>';
			gebi('bl5').style.display = 'none';
			gebi('bl51').innerHTML = '<b>���</b>';
			gebi('bl6').style.display = 'none';
			gebi('bl61').innerHTML = '<b>1 ���</b>';
			gebi('bl7').style.display = 'none';
			gebi('bl71').innerHTML = '<b>��</b>';
			gebi('pricet').innerHTML = '��������� ������';
			gebi('price').innerHTML = '<b style="color:#228B22;">' + number_format(price, 2, '.', ' ') + ' ���.</b>';
		}else{
			gebi('bl2').style.display = '';
			gebi('bl21').innerHTML = '';
			gebi('bl3').style.display = '';
			gebi('bl31').innerHTML = '';
			gebi('bl5').style.display = '';
			gebi('bl51').innerHTML = '';
			gebi('bl6').style.display = '';
			gebi('bl61').innerHTML = '';
			gebi('bl7').style.display = '';
			gebi('bl71').innerHTML = '';
			gebi('pricet').innerHTML = '��������� ������ ���������';
			var price = cena_nac * (cena_hits + (cena_timer * (timer-<?=$dlink_timer_ot;?>)) + (active * cena_active)) + ((color * cena_color) + cena_revisit + cena_unic_ip);
			gebi('price').innerHTML = '<b style="color:#228B22;">' + number_format(price, 6, '.', ' ') + ' ���.</b>';
		}
	}
	</script><?php

	echo '<div id="newform">';
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th class="top">��������</a>';
		echo '<th class="top">��������</a>';
	echo '</thead></tr>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="220"><b>��� �������</b></td>';
			echo '<td>';
				if($row["type_serf"]==5) $row["type_serf"]==1;

				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="type_serf" onChange="obsch();" onClick="obsch();">';
						echo '<option value="1" '.("1"==$row["type_serf"] ? 'selected="selected"' : false).'>������������ �������</option>';
						echo '<option value="2" '.("2"==$row["type_serf"] ? 'selected="selected"' : false).'>��������� �������</option>';
						echo '<option value="3" '.("3"==$row["type_serf"] ? 'selected="selected"' : false).'>������������ ������� - VIP (�� ������� �������� ��� �������)</option>';
						echo '<option value="4" '.("4"==$row["type_serf"] ? 'selected="selected"' : false).'>��������� ������� - VIP (�� ������� �������� ��� �������)</option>';
					echo '</select>';
				}else{
					if($row["type_serf"]==1 | $row["type_serf"]==5) {
						echo '<b>������������ �������</b>';
					}elseif($row["type_serf"]==2) {
						echo '<b>��������� �������</b>';
					}elseif($row["type_serf"]==3) {
						echo '<b>������������ ������� - VIP (�� ������� �������� ��� �������)</b>';
					}elseif($row["type_serf"]==4) {
						echo '<b>��������� ������� - VIP (�� ������� �������� ��� �������)</b>';
					}
					echo '<input type="hidden" id="type_serf" value="'.$row["type_serf"].'" />';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>����������� �������</b></td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="nolimit" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">1&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit[1],2,".","`").' ���.)</option>';
						echo '<option value="2">2&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit[2],2,".","`").' ���.)</option>';
						echo '<option value="3">3&nbsp;������&nbsp;('.number_format($dlink_cena_nolimit[3],2,".","`").' ���.)</option>';
						echo '<option value="4">1&nbsp;�����&nbsp;&nbsp;('.number_format($dlink_cena_nolimit[4],2,".","`").' ���.)</option>';
					echo '</select>';
				}else{
					if($row["nolimit"]!=0) {
						echo '<b>��</b>, �� '.DATE("d.m.Y�. H:i", $row["nolimit"]);
						echo '<input type="hidden" id="nolimit" value="1" />';
					}else{
						echo '<b>���</b>';
						echo '<input type="hidden" id="nolimit" value="0" />';
					}
				}
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td><b>URL �����</b></td>';
			echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr id="type_serf_a">';
			echo '<td><b>��������� ������</b></td>';
			echo '<td><input type="text" id="title" maxlength="60" value="'.$row["title"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr id="type_serf_b">';
			echo '<td><b>�������� ������</b></td>';
			echo '<td><input type="text" id="description" maxlength="60" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr id="type_serf_c" style="display: none;">';
			echo '<td><b>URL ������� 468�60</b></td>';
			echo '<td>';
				echo '<input type="text" id="url_banner" maxlength="300" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" onChange="descchange();" onKeyUp="descchange();">';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td><b>����� ���������</b></td>';
			echo '<td><div id="bl2">';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<input type="text" id="timer" maxlength="3" value="'.$row["timer"].'" class="ok12" style="text-align:right;" onChange="obsch();" onKeyUp="obsch();" onkeydown="this.style.background=\'#FFFFFF\';">';
					echo '<span id="timer_serf"></span>';
				}else{
					echo '<b>'.$row["timer"].'</b> ���.';
					echo '<input type="hidden" id="timer" value="'.$row["timer"].'" />';
				}
			echo '</div><div id="bl21" style="text-align:left;"></div></td>'; 
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-close" onclick="ShowHideBlock(1);">�������������� ���������</span>';
	echo '<div id="adv-block1" style="display:none; margin:0; padding:0;">';

		echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
		echo '<tbody>';
			echo '<tr id="type_serf_d">';
				echo '<td width="220">�������� ������</td>';
				echo '<td style="height:31px;"><div id="bl3">';
					if($row["status"]==0 | $row["status"]==3) {
						echo '<select id="color" onChange="obsch();" onClick="obsch();">';
							echo '<option value="0" '.("0"==$row["color"] ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.("1"==$row["color"] ? 'selected="selected"' : false).'>�� (+'.round(number_format($dlink_cena_color,6,".",""),6).' ���./�����)</option>';
						echo '</select>';
					}elseif($row["color"]==0) {
						echo '<b>���</b>';
						echo '<input type="hidden" id="color" value="'.$row["color"].'" />';
					}elseif($row["color"]==1) {
						echo '<b>��</b>';
						echo '<input type="hidden" id="color" value="'.$row["color"].'" />';
					}
				echo '</div><div id="bl31" style="text-align:left;"></div></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="220">�������� ����</td>';
				echo '<td style="height:31px;"><div id="bl5">';
					if($row["status"]==0 | $row["status"]==3) {
						echo '<select id="active" onChange="obsch();" onClick="obsch();">';
							echo '<option value="0" '.("0"==$row["active"] ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.("1"==$row["active"] ? 'selected="selected"' : false).'>�� (+'.round(number_format(($dlink_cena_active*(100+$nacenka_hits_bs)/100),6,".",""),6).' ���./�����)</option>';
						echo '</select>';
					}elseif($row["active"]==0) {
						echo '<b>���</b>';
						echo '<input type="hidden" id="active" value="'.$row["active"].'" />';
					}elseif($row["active"]==1) {
						echo '<b>��</b>';
						echo '<input type="hidden" id="active" value="'.$row["active"].'" />';
					}
				echo '</div><div id="bl51" style="text-align:left;"></div></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td style="height:31px;">�������� ��� ���������</td>';
				echo '<td><div id="bl6">';
					if($row["status"]==0 | $row["status"]==3) {
						echo '<select id="revisit" onChange="obsch();" onClick="obsch();">';
							echo '<option value="0"'.("0"==$row["revisit"] ? 'selected="selected"' : false).'>������ 24 ����</option>';
							echo '<option value="1"'.("1"==$row["revisit"] ? 'selected="selected"' : false).'>������ 48 ����� (+'.round(number_format($dlink_cena_revisit[1], 6, ".", ""),6).' ���./�����)</option>';
							echo '<option value="2"'.("2"==$row["revisit"] ? 'selected="selected"' : false).'>1 ��� (+'.round(number_format($dlink_cena_revisit[2], 6, ".", ""),6).' ���./�����)</option>';
						echo '</select>';
					}elseif($row["revisit"]==0) {
						echo '<b>������ 24 ����</b>';
						echo '<input type="hidden" id="revisit" value="'.$row["revisit"].'" />';
					}elseif($row["revisit"]==1) {
						echo '<b>������ 48 �����</b>';
						echo '<input type="hidden" id="revisit" value="'.$row["revisit"].'" />';
					}elseif($row["revisit"]==2) {
						echo '<b>1 ���</b>';
						echo '<input type="hidden" id="revisit" value="'.$row["revisit"].'" />';
					}
				echo '</div><div id="bl61" style="text-align:left;"></div></td>';
			echo '</tr>';

			echo '<tr id="type_serf_r">';
				echo '<td align="left">���������� IP</td>';
				echo '<td style="height:31px;"><div id="bl7">';
					if($row["status"]==0 | $row["status"]==3) {
						echo '<select id="unic_ip" onChange="obsch();" onClick="obsch();">';
							echo '<option value="0" '.("".$row["unic_ip"]."" == 0 ? 'selected="selected"' : false).'>���</option>';
							echo '<option value="1" '.("".$row["unic_ip"]."" == 1 ? 'selected="selected"' : false).'>�� (100% ����������) &mdash; (+'.round(number_format($dlink_cena_unic_ip[1], 6, ".", ""),6).' ���./�����)</option>';
							echo '<option value="2" '.("".$row["unic_ip"]."" == 2 ? 'selected="selected"' : false).'>��������� �� ����� �� 2 ����� (255.255.X.X) &mdash; (+'.round(number_format($dlink_cena_unic_ip[1], 6, ".", ""),6).' ���./�����)</option>';
						echo '</select>';
					}elseif($row["unic_ip"]==0) {
						echo '<b>���</b>';
						echo '<input type="hidden" id="unic_ip" value="'.$row["unic_ip"].'" />';
					}elseif($row["unic_ip"]==1) {
						echo '<b>��</b>, 100% ����������</b>';
						echo '<input type="hidden" id="unic_ip" value="'.$row["unic_ip"].'" />';
					}elseif($row["unic_ip"]==2) {
						echo '<b>��������� �� ����� �� 2 �����</b>, (255.255.X.X)';
						echo '<input type="hidden" id="unic_ip" value="'.$row["unic_ip"].'" />';
					}
				echo '</div><div id="bl71" style="text-align:left;"></div></td>';
			echo '</tr>';

			echo '<tr id="type_serf_t">';
				echo '<td align="left">�� ������� ��������</td>';
				echo '<td>';
					echo '<select id="no_ref" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("".$row["no_ref"]."" == 0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.("".$row["no_ref"]."" == 1 ? 'selected="selected"' : false).'>������������� ��� �������� �� '.$_SERVER["HTTP_HOST"].'</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';

			echo '<tr id="type_serf_y">';
				echo '<td align="left">�� �������� ��������</td>';
				echo '<td>';
					echo '<select id="sex_adv" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("".$row["sex_adv"]."" == 0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.("".$row["sex_adv"]."" == 1 ? 'selected="selected"' : false).'>������ �������</option>';
						echo '<option value="2" '.("".$row["sex_adv"]."" == 2 ? 'selected="selected"' : false).'>������ �������</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';

			echo '<tr id="type_serf_z">';
				echo '<td align="left">���������� ������ ���������</td>';
				echo '<td>';
					echo '<select id="to_ref" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("".$row["to_ref"]."" == 0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.("".$row["to_ref"]."" == 1 ? 'selected="selected"' : false).'>��������� 1-�� ������</option>';
						echo '<option value="2" '.("".$row["to_ref"]."" == 2 ? 'selected="selected"' : false).'>��������� ���� �������</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';

			echo '<tr id="type_serf_u">';
				echo '<td align="left">���������� ������ ��������</td>';
				echo '<td>';
					echo '<select id="new_users" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("".$row["new_users"]."" == 0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.("".$row["new_users"]."" == 1 ? 'selected="selected"' : false).'>�� (�� 7 ���� � ������� �����������)</option>';
					echo '</select>';
				echo '</td>';
			echo '</tr>';

		echo '</tbody>';
		echo '</table>';

		echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="220" height="24">����������� ������� � �����</td>';
				echo '<td width="125"><div id="bl12">';
					if($row["nolimit"]!=0) {
						echo '<div align="center"><b>'.$row["limit_d"].'</b></div>';
						echo '<input type="hidden" id="limit_d" value="'.$row["limit_d"].'" />';
					}else{
						echo '<input type="text" id="limit_d" maxlength="11" value="'.$row["limit_d"].'" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok\');">';
					}
				echo '</div><div id="bl12_1" style="text-align:left;"></div></td>';
				echo '<td align="left" rowspan="2" style="padding-left:15px;">[ 0 - ��� ����������� ]</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="220" height="24">����������� ������� � ���</td>';
				echo '<td width="125"><div id="bl13">';
					if($row["nolimit"]!=0) {
						echo '<div align="center"><b>'.$row["limit_h"].'</b></div>';
						echo '<input type="hidden" id="limit_h" value="'.$row["limit_h"].'" />';
					}else{
						echo '<input type="text" id="limit_h" maxlength="11" value="'.$row["limit_h"].'" class="ok12" style="text-align:center;" onChange="PlanChange();" onKeyUp="PlanChange();" onKeyDown="$(this).attr(\'class\', \'ok\');">';
					}
				echo '</div><div id="bl13_1" style="text-align:left;"></div></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';

		echo '<table class="tables" id="type_serf_zx">';
		echo '<tbody>';
			echo '<tr ]>';
				echo '<td colspan="2" align="center"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
				echo '<td colspan="2" align="center"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
			echo '</tr>';
			include(DOC_ROOT."/advertise/func_geotarg_edit.php");
		echo '</tbody>';
		echo '</table>';


	echo '</div>';

	echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
	echo '<tbody>';
		echo '<tr>';
			if($row["status"]==0 | $row["status"]==3) {
				echo '<td id="pricet" width="220"></td>';
				echo '<td id="price"></td>';
			}else{
				if($row["nolimit"]!=0) {
					echo '<td width="220"><b>��������� ������</b></td>';
					echo '<td><b style="color:#228B22;">'.number_format($row["money"], 2, ".", " ").' ���.</b></td>';
				}else{
					echo '<td id="pricet" width="220"></td>';
					echo '<td id="price"></td>';
				}
			}
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div><br>';

	echo '<div id="info-msg-cab"></div>';

	if($row["status"]!=0) {
		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'dlink\', '.$row["status"].', 1);" class="proc-btn" style="float:none; width:160px;">���������</span></div>';
	}else{
		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'dlink\', '.$row["status"].', 0);" class="proc-btn" style="float:none; width:160px;">���������</span></div>';
	}

	?><script language="JavaScript">obsch();</script><?php
}else{
	echo '<span class="msg-error">��������� �������� � '.$id.' � ��� �� �������</span>';
}
?>