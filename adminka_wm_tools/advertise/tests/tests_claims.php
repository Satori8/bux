<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������� ����� �� ������������ �����</b></h3>';

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `status`='3' AND `balance`>=`cena_advs`");

echo '<div id="LoadModalClaimsTest" style="display:none;"></div>';

?><script type="text/javascript" language="JavaScript">
var s_h = false;
var new_id = false;

function LoadInfo(id, type, op) {
	if(s_h==(id + op)) {
		s_h = false;
		$("#load-info"+id).hide();
		$("#mess-info"+id).html("");
	} else {
		if(s_h && new_id) {
			$("#load-info"+new_id).hide();
			$("#mess-info"+new_id).html("");
		}
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				new_id = id; s_h = id + op;

				$("#load-info"+id).show(); 
				$("#mess-info"+id).html('<span class="msg-error">������ ��������� ������!</span>');
				return false;
			}, 
			success: function(data) { 
				$("#loading").slideToggle();

				//if(op == "GoDel") {
				//	new_id = id; s_h = id;
				//}else{
					new_id = id; s_h = id + op;
				//}

				$("#load-info"+id).show();

				if (data.result == "OK") {
					if(data.message) { $("#mess-info"+id).html(data.message); }
					else { $("#mess-info"+id).html('<span class="msg-error">������ ��������� ������!</span>'); }
				} else {
					if(data.message) { $("#mess-info"+id).html('<span class="msg-error">' + data.message + '</span>'); }
					else { $("#mess-info"+id).html('<span class="msg-error">������ ��������� ������!</span>'); }
				}
			}
		});
	}
	return false;
}

function DelCash(id, type, op){
	var cashback = $("#cashback").prop("checked") == true ? 1 : 0;

	if (confirm("�� ������� ��� ������ ������� �������� �������� ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id, 'cashback':cashback }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#adv_dell"+id).hide(); $("#load-info"+id).hide(); $("#mess-info"+id).html("");
				} else {
					if(data.message) { alert(data.message); }
					else { alert("������ ��������� ������!"); return false; }
				}
			}
		});
	}
}

function DelClaims(id, type, op) {
	if (confirm("������� ������?")) {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json', 
			success: function(data) {
				if (data.result=="OK") {
					$("#claims-"+id).remove();
					if(data.count_claims==0) {
						$("#adv_dell"+data.ident).remove();
						$("#LoadModalClaimsTest").modalpopup('close');
					}else{
						$("#countclaims-"+data.ident).html(data.count_claims);
					}
				}else{
					alert(data.message);
				}
			}
		});
	}
}

function DelAllClaims(id, type, op) {
	if (confirm("������� ��� ������?")) {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json', 
			success: function(data) {
				if (data.result=="OK") {
					$("#adv_dell"+id).remove();
				}else{
					alert(data.message);
				}
			}
		});
	}
}

function ViewClaims(id, type, op) {
	$.ajax({
		type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
		data: { 'op': op, 'type': type, 'id': id }, 
		dataType: 'json', 
		success: function(data) {
			if (data.message) {
				$("#LoadModalClaimsTest").html(data.message).show();
				StartModal();
				WinHeight = Math.ceil($.trim($(window).height()));
				ModalHeight = Math.ceil($.trim($("#table-content").height()));
				if((ModalHeight+100) >= WinHeight) ModalHeight = WinHeight-100;
				$("#table-content").css("height", ModalHeight+"px");
			}
		}
	});
}

function StartModal() {
	$("#LoadModalClaimsTest").modalpopup({
		closeOnEsc: false,
		closeOnOverlayClick: false,
		beforeClose: function(data, el) {
			$("#LoadModalClaimsTest").hide();
			return true;
		}
	});
}

function PlayPause(id, type, op, lock) {
	if (lock && !confirm("�������������� ��������� �������� ID: "+id+" ?")) {
		return false;
	}

	$.ajax({
		type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
		data: { 'op': op, 'type': type, 'id': id }, 
		dataType: 'json', 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
		success: function(data) { 
			$("#loading").slideToggle();
			if (data.result == "OK") { 
				$("#playpauseimg"+id).html(data.status);
				if(data.message) { alert(data.message); }
				if(lock) {
					$("#lock-"+id).attr({class: "adv-lock", title: "������������� ����"});
					$("#load-info"+id).hide();
					$("#mess-info"+id).html("");
					s_h = false; new_id = false;
				}
			} else { 
				if(data.message) { alert(data.message); }
				else { alert("������ ��������� ������!"); return false; }
			}
			return false;
		}
	});
}

function Lock(id, type, op){
	var msg_lock = $.trim($("#msg_lock").val());

	if (!msg_lock | msg_lock == false) {
		$("#msg_lock").focus().attr("class", "err");
		alert("������� ������� ����������!");
	} else {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id, 'msg_lock':msg_lock }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#load-info"+id).hide();
					$("#mess-info"+id).html("");
					$("#playpauseimg"+id).html('<span class="adv-block" title="��������� �������� �������������" onClick="PlayPause('+id+', \'tests\', \'PlayPause\', \'1\');"></span>');
					$("#lock-"+id).attr({class: "adv-unlock", title: "�������� ���������� � ����������"});
					s_h = false; new_id = false;

					//if(data.message) { alert(data.message); }
				} else {
					if(data.message) { alert(data.message); }
					else { alert("������ ��������� ������!"); return false; }
				}
			}
		});
	}
}

</script><?php

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `status`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_claims` WHERE `type`='tests') ");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}

//echo '<table class="tables" style="margin:2px auto; padding:0;">';
echo '<table class="adv-cab" style="margin:1px auto;">';
echo '<tr align="center">';
	echo '<th>������</th>';
	echo '<th>ID | ����</th>';
	echo '<th>����������</th>';
	echo '<th>����������</th>';
	echo '<th>���-�� �����</th>';
	echo '<th>��������</th>';
echo '</tr>';

$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `status`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_claims` WHERE `type`='tests') ORDER BY `id` DESC LIMIT $start_pos,$perpage ");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
		echo '<tr id="adv_dell'.$row["id"].'" align="center">';
		echo '<td align="center" width="30" class="noborder1" style="border-right:solid 1px #DDDDDD;">';
			echo '<div id="playpauseimg'.$row["id"].'">';
				if($row["status"]=="0") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
				}elseif($row["status"]=="1") {
					echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
				}elseif($row["status"]=="2") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
				}elseif($row["status"]=="3") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\');"></span>';
				}elseif($row["status"]=="4") {
					echo '<span class="adv-block" title="��������� �������� �������������" onClick="PlayPause('.$row["id"].', \'tests\', \'PlayPause\', \'1\');"></span>';
				}
			echo '</div>';
		echo '</td>';

		echo '<td style="border-left:solid 2px #FFFFFF;">'.$row["id"].'<br>'.$row["merch_tran_id"].'</td>';

		echo '<td align="left">';
			echo '���������: <b>'.(strlen($row["title"])>40 ? limitatexto($row["title"],40)."...." : $row["title"]).'</b><br>';
			echo 'URL �����: <a href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.(strlen($row["url"])>40 ? limitatexto($row["url"],40)."...." : $row["url"]).'</a><br>';
			echo '�������������: '.($row["wmid"]!=false ? ($row["username"]!=false ? "WMID: <b>".$row["wmid"]."</b>, �����: <b>".$row["username"]."</b>" : "WMID: <b>".$row["wmid"]."</b>") : ($row["username"]!=false ? "�����: <b>".$row["username"]."</b>" : "<span style=\"color:#CCC;\">�� ����������</span>"));
		echo '</td>';

		echo '<td align="left">';
			echo '������:&nbsp;<b>'.number_format($row["balance"], 2, ".", "`").'</b> ���.<br>';
			echo '��������:&nbsp;<b>'.number_format($row["goods"], 0, ".", "`").'</b><br>';
			echo '���������:&nbsp;<b>'.number_format($row["bads"], 0, ".", "`").'</b><br>';
			echo '��������:&nbsp;<b>'.number_format(floor(bcdiv($row["balance"],$row["cena_advs"])), 0, ".", "`").'</b>';
		echo '</td>';

		echo '<td><span id="countclaims-'.$row["id"].'">'.$row["claims"].'</td>';

		echo '<td width="100">';
			echo '<span class="adv-dell" title="������� ����" onClick="LoadInfo('.$row["id"].', \'tests\', \'GoDel\');"></span>';
			echo '<span id="lock-'.$row["id"].'" class="adv-'.($row["status"]=="4" ? "unlock" : "lock").'" title="'.($row["status"]=="4" ? "�������� ���������� � ����������" : "������������� ����").'" onClick="LoadInfo('.$row["id"].', \'tests\', \'GoLock\');"></span>';
			echo '<span class="clear-claims" title="������� ��� ������" onClick="DelAllClaims(\''.$row["id"].'\', \'tests\', \'DelAllClaims\');"></span>';
			echo '<span class="view-claims" title="�������� �����" onClick="ViewClaims(\''.$row["id"].'\', \'tests\', \'ViewClaims\');"></span>';
			echo '<span class="adv-info" title="���������� ��������� ��������" onClick="LoadInfo('.$row["id"].', \'tests\', \'GetInfo\');"></span>';
		echo '</td>';

		echo '</tr>';

		echo '<tr id="hide'.$row["id"].'" style="display: none;"><td align="center" colspan="3"></td></tr>';

		echo '<tr id="load-info'.$row["id"].'" style="display: none;">';
			echo '<td align="center" colspan="6" class="ext-text">';
				echo '<div id="mess-info'.$row["id"].'"></div>';
			echo '</td>';
		echo '</tr>';

	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="6"><b>������� � �������� �� ����������</b></td>';
	echo '</tr>';
}
echo '</table>';

if($count>$perpage) {universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");}
?>