<?php
if(!DEFINED("YOUTUBE_AJAX")) {die ("Hacking attempt!");}
require(DOC_ROOT."/advertise/func_load_banners.php");

mysql_query("UPDATE `tb_ads_youtube` SET `status`='3', `date`='".time()."' WHERE `status`>'0' AND `status`<'3' AND ( (`totals`<'1' AND `nolimit`='0') OR ( `nolimit`>'0' AND `nolimit`<='".time()."') ) ") or die(mysql_error());

if($option == "pay_adv_up") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `status`='1' AND `type_serf`!='10' AND (`up_list`='0' OR `up_list`<'".(time()-60)."') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_uplist' AND `howmany`='1'");
		$youtube_cena_uplist = mysql_result($sql,0,0);

		if($row["up_list"]>0) {
			$position = mysql_result(mysql_query("SELECT COUNT(*) FROM `tb_ads_youtube` WHERE `status`='1' AND `up_list`>(SELECT `up_list` FROM `tb_ads_youtube` WHERE `id`='$id')" ),0,0)+1;
		}else{
			$position = 0;
		}

		if($money_user_rb>=$youtube_cena_uplist && ($position>1 | $position==0)) {
			mysql_query("UPDATE `tb_ads_youtube` SET `up_list`='".time()."' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$youtube_cena_uplist', `money_rek`=`money_rek`+'$youtube_cena_uplist' WHERE `username`='$username'") or die(mysql_error());
			mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
			VALUES('$username','".DATE("d.m.Y�. H:i")."','$youtube_cena_uplist','�������� ������ � �������� ID:$id','�������','rashod')") or die(mysql_error());

			exit("OK");
		}
	}


}elseif($option == "play_pause") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$nolimit = $row["nolimit"];
		$limit_d_now = $row["limit_d_now"];
		$limit_h_now = $row["limit_h_now"];

		if($status == 0) {
			exit("0");
		}elseif($status == 1) {
			if($nolimit!=0) {
				exit("BEZLIMIT");
			}else{
				mysql_query("UPDATE `tb_ads_youtube` SET `status`='2', `data_pauza`='".time()."' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'youtube\');"></span>';
				exit();
			}
		}elseif($status == 2) {
			if($nolimit!=0) {
				exit("BEZLIMIT");
			}else{
				mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `data_pauza`='0' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'youtube\');"></span>';
				exit();
				}
		}elseif($status == 3) {
			if($nolimit!=0) {
				echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart_rc();"></span>';
				exit();
			}else{
				exit("0");
			}
		}else{
			exit("ERROR");
		}
	}else{
		exit("ERRORNOID");
	}

}elseif($option == "ViewClaims") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$status = $row["status"];

		$message_text = "";
		$message_text.= '<div class="box-modal" id="ModalClaims" style="text-align:justify; width:700px;">';
			$message_text.= '<div class="box-modal-title">�������� ����� �� ��������� �������� �'.$id.'</div>';
			$message_text.= '<div class="box-modal-close modalpopup-close"></div>';
			$message_text.= '<div class="box-modal-content" style="margin:1px; padding:5px 3px; font-size:11px;">';

				$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
				$message_text.= '<thead><tr align="center">';
					$message_text.= '<th width="120" align="center" style="padding:4px 5px;">�����</th><th width="120" align="center" style="padding:4px 5px;">����</th><th width="100" align="center" style="padding:4px 5px;">IP</th><th align="center" style="padding:4px 5px;">����� ������</th>';
				$message_text.= '</thead></tr>';
				$message_text.= '</table>';

				$message_text.= '<div id="table-content" style="overflow:auto;">';
					$message_text.= '<table class="tables" style="margin:0 auto; padding:0;">';
					$sql = mysql_query("SELECT * FROM `tb_ads_claims` WHERE `ident`='$id' AND `type`='serf' ORDER BY `id` DESC");
					if(mysql_num_rows($sql)>0) {
						while ($row = mysql_fetch_assoc($sql)) {
							$message_text.= '<tr id="claims-'.$row["id"].'" align="center">';
								$message_text.= '<td width="120" style="padding:2px 5px;">'.$row["username"].'</td>';
								$message_text.= '<td width="120" style="padding:2px 5px;">'.DATE("d.m.Y�. H:i", $row["time"]).'</td>';
								$message_text.= '<td width="100" style="padding:2px 5px;">'.$row["ip"].'</td>';
								$message_text.= '<td style="padding:2px 5px;">'.$row["claims"].'</td>';
							$message_text.= '</tr>';
						}
					}else{
						$message_text.= '<tr align="center"><td colspan="3"><b>������ �� ����������</b></td></tr>';
					}
					$message_text.= '</table>';
				$message_text.= '</div>';

			$message_text.= '</div>';
		$message_text.= '</div>';

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", $message_text);
		exit(json_encode_cp1251($json_result));

	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "� ��� ��� ��������� �������� � ID - $id");
		exit(json_encode_cp1251($json_result));
	}


}elseif($option == "delete") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			mysql_query("DELETE FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}elseif($status == 1 | $status == 2) {
			exit("�������� �������� ������ ����� ��������� ������.");
		}elseif($status == 3) {
			mysql_query("DELETE FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}else{
			exit("ERROR");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($status == 0) {
			exit("������� ���� �������� ��� ����� 0");
		}else{
			mysql_query("UPDATE `tb_ads_youtube` SET `outside`='0', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
			exit("OK");
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "save") {
	$type_serf = (isset($_POST["type_serf"]) && (intval($_POST["type_serf"])==1 | intval($_POST["type_serf"])==2 | intval($_POST["type_serf"])==3 | intval($_POST["type_serf"])==4 | intval($_POST["type_serf"])==5)) ? intval(trim($_POST["type_serf"])) : "1";
	$nolimit = (isset($_POST["nolimit"])) ? intval(limpiarez($_POST["nolimit"])) : "0";
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 60) : false;
	$timer = ( isset($_POST["timer"]) && preg_match("|^[\d]{1,3}$|", limpiarez($_POST["timer"])) ) ? intval(limpiarez($_POST["timer"])) : false;
	$color = (isset($_POST["color"]) && (intval($_POST["color"])==0 | intval($_POST["color"])==1)) ? intval(limpiarez($_POST["color"])) : "0";
	$active = (isset($_POST["active"]) && (intval($_POST["active"])==0 | intval($_POST["active"])==1)) ? intval(limpiarez($_POST["active"])) : "0";
	$revisit = (isset($_POST["revisit"]) && (intval($_POST["revisit"])==0 | intval($_POST["revisit"])==1 | intval($_POST["revisit"])==2)) ? intval(limpiarez($_POST["revisit"])) : "0";
	$limit_d = ( isset($_POST["limit_d"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["limit_d"])) ) ? intval(limpiarez($_POST["limit_d"])) : false;
	$limit_h = ( isset($_POST["limit_h"]) && preg_match("|^[\d]{1,11}$|", limpiarez($_POST["limit_h"])) ) ? intval(limpiarez($_POST["limit_h"])) : false;
	$black_url = getHost($url);

	$new_users = (isset($_POST["new_users"]) && (intval($_POST["new_users"])==0 | intval($_POST["new_users"])==1)) ? intval($_POST["new_users"]) : "0";
	$unic_ip = (isset($_POST["unic_ip"]) && (intval($_POST["unic_ip"])==0 | intval($_POST["unic_ip"])==1 | intval($_POST["unic_ip"])==2)) ? intval($_POST["unic_ip"]) : "0";
	$no_ref = (isset($_POST["no_ref"]) && (intval($_POST["no_ref"])==0 | intval($_POST["no_ref"])==1)) ? intval($_POST["no_ref"]) : "0";
	$sex_adv = (isset($_POST["sex_adv"]) && (intval($_POST["sex_adv"])==0 | intval($_POST["sex_adv"])==1 | intval($_POST["sex_adv"])==2)) ? intval($_POST["sex_adv"]) : "0";
	$to_ref = (isset($_POST["to_ref"]) && (intval($_POST["to_ref"])==0 | intval($_POST["to_ref"])==1 | intval($_POST["to_ref"])==2)) ? intval($_POST["to_ref"]) : "0";

	$p = explode("youtu.be/", $_POST["url"]);  
	$p_time = explode("?", $p[1]);  
	$img_youtube='https://img.youtube.com/vi/'.$p_time[0].'/1.jpg';  
	
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];

		if($type_serf==-1) $type_serf=1;

		if($status==1 | $status==2) {
			$type_serf = $row["type_serf"];
			$nolimit = $row["nolimit"];

			$timer = $row["timer"];
			$active = $row["active"];
			$revisit = $row["revisit"];
			$color = $row["color"];
			$unic_ip = $row["unic_ip"];
		}

		if($type_serf==2 | $type_serf==4) {
			$color = 0;
			$title = false;
			$description = (isset($_POST["url_banner"])) ? limitatexto(limpiarez($_POST["url_banner"]),300) : false;
			$black_url = @getHost($url);
			$black_url_banner = @getHost($description);
		}else{
			$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
			$black_url = @getHost($url);
			$black_url_banner = false;
		}

		if($type_serf==3 | $type_serf==4) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits_vip' AND `howmany`='1'");
			$youtube_min_hits = mysql_result($sql,0,0);

			$youtube_timer_ot = 20;
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits' AND `howmany`='1'");
			$youtube_min_hits = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_ot' AND `howmany`='1'");
			$youtube_timer_ot = mysql_result($sql,0,0);
		}

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_do' AND `howmany`='1'");
		$youtube_timer_do = mysql_result($sql,0,0);


		$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
		$sql_bl_banner = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url_banner'");

		if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
			$row_bl = mysql_fetch_array($sql_bl);
			echo '���� '.$row_bl["domen"].' ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' �������: '.$row_bl["cause"].'';
			exit();
		}elseif($url==false | $url=="http://" | $url=="https://") {
			echo '�� ������� ������ �� ����!';
			exit();
		}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
			echo '�� ����� ������� ������ �� ����!';
			exit();
		}elseif(is_url($url)!="true") {
			echo '�� ����� ������� ������ �� ����!';
			exit("");
		}elseif(($type_serf==1 | $type_serf==3) && $title==false) {
			echo '�� ��������� ���� ��������� ������.';
			exit("");
		/*}elseif(($type_serf==1 | $type_serf==3) && $description==false) {
			echo '�� ��������� ���� �������� ������.';
			exit("");*/

		}elseif(($type_serf==2 | $type_serf==4) && mysql_num_rows($sql_bl_banner)>0 && $black_url_banner!=false) {
			$row_bl_ban = mysql_fetch_array($sql_bl_banner);
			echo '���� '.$row_bl_ban["domen"].' ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' �������: '.$row_bl_ban["cause"].'';
			exit();
		/*}elseif(($type_serf==2 | $type_serf==4) && ($description==false | $description=="http://" | $description=="https://")) {
			echo '�� ������� ������ �� ������!';
			exit("");
		}elseif(($type_serf==2 | $type_serf==4) && ((substr($description, 0, 7) != "http://" && substr($description, 0, 8) != "https://"))) {
			echo '�� ����� ������� ������ �� ������!';
			exit("");*/
		}elseif(($type_serf==2 | $type_serf==4) && is_url($url)!="true") {
			echo '�� ����� ������� ������ �� ����!';
			exit("");
	/*	}elseif(($type_serf==2 | $type_serf==4) && is_url_img($description, "cabinet")!="true") {
			echo is_url_img($description, "cabinet");
			exit("");
		}elseif(($type_serf==2 | $type_serf==4) && is_img_size("468", "60", $description, "cabinet")!="true") {
			echo is_img_size("468", "60", $description, "cabinet");
			exit("");*/
		}elseif(($type_serf==3 | $type_serf==4) && $timer<20) {
			echo '��� VIP �������� ����� ��������� ������ ���� � �������� �� 20 ���. �� '.$youtube_timer_do.' ���.';
			exit("");
		}elseif($limit_d<0) {
			exit("����������� ���������� ������� � ����� ������ ���� �������������");
		}elseif($limit_d!=0 && $limit_d<$youtube_min_hits) {
			exit("����������� ���������� ������� � ����� ������ ���� �� ����� $youtube_min_hits ���� 0-��� �����������");
		}elseif($limit_h<0) {
			exit("����������� ���������� ������� � ��� ������ ���� �������������");
		}elseif($limit_h!=0 && $limit_h<$youtube_min_hits) {
			exit("����������� ���������� ������� � ��� ������ ���� �� ����� $youtube_min_hits ���� 0-��� �����������");
		}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
			exit(SFB_YANDEX($url));
		}elseif(@getHost($description)!=$_SERVER["HTTP_HOST"] && ($type_serf==2 | $type_serf==4) && SFB_YANDEX($description)!=false) {
			exit(SFB_YANDEX($description));
		}elseif(($type_serf==2 | $type_serf==4) && img_get_save_cab($description)!="true") {
			echo img_get_save_cab($description);
		}else{
			if(($type_serf==2 | $type_serf==4)) {
				$urlbanner_load = img_get_save_cab($description, 1);
			}else{
				$urlbanner_load = false;
			}

			if($status == 0 | $status == 3) {
				if($nolimit>0) {
					$timer = 20;
					$color = 1;
					$active = 0;
					$revisit = 0;
					$unic_ip = 0;
				}

				if($nolimit==1) {
					$nolimitdate = time() + 7*24*60*60;
				}elseif($nolimit==2) {
					$nolimitdate = time() + 14*24*60*60;
				}elseif($nolimit==3) {
					$nolimitdate = time() + 21*24*60*60;
				}elseif($nolimit==4) {
					$nolimitdate = time() + 30*24*60*60;
				}else{
					$nolimit = 0;
					$nolimitdate = 0;
				}

				if($timer<$youtube_timer_ot | $timer>$youtube_timer_do) {
					exit("����� ��������� ������ ���� � �������� �� $youtube_timer_ot �� $youtube_timer_do ���.");
				}

				if($timer<$youtube_timer_ot) {
					$timer = $youtube_timer_ot;
				}elseif($timer>$youtube_timer_do) {
					$timer = $youtube_timer_do;
				}

				mysql_query("UPDATE `tb_ads_youtube` SET `img_youtube`='$img_youtube', `type_serf`='$type_serf', `date`='".time()."',`nolimit`='$nolimitdate',`limit_d`='$limit_d',`limit_h`='$limit_h',`limit_d_now`='$limit_d',`limit_h_now`='$limit_h',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`new_users`='$new_users',`unic_ip`='$unic_ip',`no_ref`='$no_ref',`sex_adv`='$sex_adv',`to_ref`='$to_ref',`url`='$url',`title`='$title',`description`='$description',`urlbanner_load`='$urlbanner_load',`ip`='$laip' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				exit("OK");

			}elseif($status == 1 | $status == 2) {
				$plan = $row["plan"];

				if($timer<$youtube_timer_ot) {
					$timer = $youtube_timer_ot;
				}elseif($timer>$youtube_timer_do) {
					$timer = $youtube_timer_do;
				}

				mysql_query("UPDATE `tb_ads_youtube` SET `img_youtube`='$img_youtube', `type_serf`='$type_serf', `date`='".time()."',`timer`='$timer',`new_users`='$new_users',`unic_ip`='$unic_ip',`no_ref`='$no_ref',`sex_adv`='$sex_adv',`to_ref`='$to_ref',`url`='$url',`url`='$url',`title`='$title',`description`='$description',`urlbanner_load`='$urlbanner_load',`ip`='$laip' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				exit("OK");

			}else{
				exit("ERROR");
			}
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}

}elseif($option == "pay_adv") {
	$sql = mysql_query("SELECT * FROM `tb_ads_youtube` WHERE `id`='$id' AND `username`='$username' AND `type_serf`!='10'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$status = $row["status"];
		$type_serf = $row["type_serf"];
		$nolimit = $row["nolimit"];
		$limit_d = $row["limit_d"];
		$limit_h = $row["limit_h"];
		$merch_tran_id = $row["merch_tran_id"];
		$geo_targ = $row["geo_targ"];
			$new_users = $row["new_users"];
			$no_ref = $row["no_ref"];
			$to_ref = $row["to_ref"];
			$sex_adv = $row["sex_adv"];
			$revisit = $row["revisit"];
			$unic_ip = $row["unic_ip"];

		$plan = (isset($_POST["plan"]) && preg_match("|^[\d]{1,11}$|", abs(intval(limpiarez($_POST["plan"]))))) ? abs(intval(limpiarez($_POST["plan"]))) : false;

		if($type_serf == 2 | $type_serf == 4) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits_bs' AND `howmany`='1'");
			$youtube_cena_hits = mysql_result($sql,0,0);

			$youtube_cena_color = 0;
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_hits' AND `howmany`='1'");
			$youtube_cena_hits = mysql_result($sql,0,0);

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_color' AND `howmany`='1'");
			$youtube_cena_color = mysql_result($sql,0,0);
		}

		if($type_serf == 3 | $type_serf == 4) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits_vip' AND `howmany`='1'");
			$youtube_min_hits = mysql_result($sql,0,0);
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_min_hits' AND `howmany`='1'");
			$youtube_min_hits = mysql_result($sql,0,0);
		}

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_nacenka' AND `howmany`='1'");
		$youtube_nacenka = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_timer_ot' AND `howmany`='1'");
		$youtube_timer_ot = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_timer' AND `howmany`='1'");
		$youtube_cena_timer = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_active' AND `howmany`='1'");
		$youtube_cena_active = mysql_result($sql,0,0);

		$youtube_cena_revisit[0] = 0;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_revisit' AND `howmany`='1'");
		$youtube_cena_revisit[1] = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_revisit' AND `howmany`='2'");
		$youtube_cena_revisit[2] = mysql_result($sql,0,0);

		$youtube_cena_unic_ip[0] = 0;

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_unic_ip' AND `howmany`='1'");
		$youtube_cena_unic_ip[1] = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_unic_ip' AND `howmany`='2'");
		$youtube_cena_unic_ip[2] = mysql_result($sql,0,0);

		#### �������� ####
		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='1'");
		$youtube_cena_nolimit[1] = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='2'");
		$youtube_cena_nolimit[2] = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='3'");
		$youtube_cena_nolimit[3] = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='youtube_cena_nolimit' AND `howmany`='4'");
		$youtube_cena_nolimit[4] = mysql_result($sql,0,0);
		#### �������� ####

		if($row["nolimit"]!=0) {
			if($plan == 1 | $plan == 2 | $plan == 3 | $plan == 4) {
				$cena = $youtube_cena_nolimit[$plan];

				if($plan==1) $plan = "7";
				if($plan==2) $plan = "14";
				if($plan==3) $plan = "21";
				if($plan==4) $plan = "30";
				$nolimitdate = $plan*24*60*60;
			}else{
				exit("ERROR2");
			}
			$plan = 0;
			$timer = 20;
			if($type_serf == 1 | $type_serf == 3 | $type_serf == -1) {$color = 1;}else{$color = 0;}
			$active = 0;
			$revisit = 2;
			$unic_ip = 1;
		}else{
			if($plan<$youtube_min_hits) {
				exit("ERROR1");
			}else{
				$cena = $plan * ($youtube_cena_hits + ($youtube_cena_timer * ($row["timer"]-$youtube_timer_ot)) + ($row["active"] * $youtube_cena_active)) * ($youtube_nacenka + 100)/100 + $plan * ( ($row["color"] * $youtube_cena_color) + $youtube_cena_revisit[$row["revisit"]] + $youtube_cena_unic_ip[$row["unic_ip"]] );
			}
		}
		$cena = ($cena * (100-$cab_skidka)/100);
		$money_pay = number_format($cena, 2, ".", "");

		if($money_pay>$money_user_rb) {
			exit("ERROR3");
		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor($money_pay/10) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor($money_pay/10) * $reit_ref_rek;
			
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_vip'");
				$reit_vip = mysql_result($sql,0,0);
			if($type_serf==3 || $type_serf==4) {
				$reit_add_3 = $reit_vip;
                }else{
				$reit_add_3=0;
				}

			if($type_serf==3) {
				$vip_serf_tab = ", `youtube_serf`=`youtube_serf`+'200'";
			}elseif($type_serf==4) {
				$vip_serf_tab = ", `ban_serf`=`ban_serf`+'200'";
			}else{
				$vip_serf_tab = false;
			}

			if($status=="0") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1'+'$reit_add_3', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' $vip_serf_tab WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `wm_sent`='0', `method_pay`='10',`date`='".time()."',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`nolimit`='".(time() + $nolimitdate)."',`plan`='$plan',`totals`='$plan',`ip`='$laip',`money`='$money_pay' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `wm_sent`='0', `method_pay`='10',`date`='".time()."',`plan`='$plan', `totals`='$plan', `ip`='$laip', `money`='$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� �������� YouTube ID:$id','�������','rashod')") or die(mysql_error());

				stat_pay("youtube", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				if($geo_targ == false && $new_users == 0 && $no_ref == 0 && $to_ref == 0 && $sex_adv == 0 && $revisit== 0 && $unic_ip == 0) {
				konkurs_serf_new($wmid_user, $username, $money_pay);
				}
				invest_stat($money_pay, 1);
				ActionRef(number_format($money_pay,2,".",""), $username);
				ads_date();

				exit("OK");

			}elseif($status=="1" | $status=="2") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1'+'$reit_add_3', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' $vip_serf_tab WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_youtube` SET `wm_sent`='0', `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `nolimit`=`nolimit`+'$nolimitdate', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_youtube` SET `wm_sent`='0', `method_pay`='10', `date`='".time()."', `plan`=`plan`+'$plan', `totals`=`totals`+'$plan', `ip`='$laip', `money`=`money`+'$money_pay'  WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� �������� Youtube ID:$id','�������','rashod')") or die(mysql_error());

				stat_pay("youtube", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				if($geo_targ == false && $new_users == 0 && $no_ref == 0 && $to_ref == 0 && $sex_adv == 0 && $revisit== 0 && $unic_ip == 0) {
				konkurs_serf_new($wmid_user, $username, $money_pay);
				}
				invest_stat($money_pay, 1);
				ActionRef(number_format($money_pay,2,".",""), $username);
				ads_date();

				exit("OK");

			}elseif($status=="3") {
				if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'") or die(mysql_error());}

				mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1'+'$reit_add_3', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay' $vip_serf_tab WHERE `username`='$username'") or die(mysql_error());

				if($row["nolimit"]!=0) {
					mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `wm_sent`='0', `method_pay`='10', `date`='".time()."',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`nolimit`='".(time() + $nolimitdate)."',`plan`='$plan',`totals`='$plan',`members`='0',`outside`='0',`pay_users`='0',`ip`='$laip',`money`='$money_pay' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}else{
					mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `wm_sent`='0', `method_pay`='10', `date`='".time()."',`plan`='$plan',`totals`='$plan',`members`='0',`outside`='0',`pay_users`='0',`ip`='$laip',`money`='$money_pay' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
				}
				mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
				VALUES('$username','".DATE("d.m.Y�. H:i")."','$money_pay','���������� ������� �������� YouTube ID:$id','�������','rashod')") or die(mysql_error());

				stat_pay("youtube", $money_pay);
				ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
				konkurs_ads_new($wmid_user, $username, $money_pay);
				if($geo_targ == false && $new_users == 0 && $no_ref == 0 && $to_ref == 0 && $sex_adv == 0 && $revisit== 0 && $unic_ip == 0) {
				konkurs_serf_new($wmid_user, $username, $money_pay);
				}
				invest_stat($money_pay, 1);
				ActionRef(number_format($money_pay,2,".",""), $username);
				ads_date();

				exit("OK");

			}else{
				exit("������! �� ������� ���������� ������!");
			}
		}
	}else{
		exit("� ��� ��� ��������� �������� � ID - $id");
	}
}

?>