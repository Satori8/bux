<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>�������� � �������������� �������� �� e-mail</b></h3>';

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_emails` WHERE `status`='1'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$system_pay[-1] = "�����";
$system_pay[0] = "�������";
$system_pay[1] = "WebMoney";
$system_pay[2] = "RoboKassa";
$system_pay[3] = "Wallet One";
$system_pay[4] = "InterKassa";
$system_pay[5] = "Payeer";
$system_pay[6] = "Qiwi";
$system_pay[7] = "PerfectMoney";
$system_pay[8] = "YandexMoney";
$system_pay[9] = "MegaKassa";
$system_pay[20] = "FreeKassa";
$system_pay[10] = "����. ����";

?>
<script type="text/javascript" language="JavaScript">
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
			type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				new_id = id; s_h = id + op;

				$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);

				$("#load-info"+id).show(); 
				$("#mess-info"+id).html('<span class="msg-error">������ ��������� ������!</span>');
				return false;
			}, 
			success: function(data) { 
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				new_id = id; s_h = id + op;

				$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);

				$("#load-info"+id).show();

				if(result == "OK") {
					$("#mess-info"+id).html(message);
					if(op == "LoadForm") {descchange(1, message, 2000);}
					return false;
				} else {
					$("#mess-info"+id).html('<span class="msg-error">'+message+'</span>');
					return false;
				}
			}
		});
	}
	return false;
}

function SaveAds(id, type, op) {
	var subject = $.trim($("#subject").val());
	var message = $.trim($("#message").val());
	$("#info-msg-cab").html("").hide();

	$.ajax({
		type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
		data: {'op':op, 'type':type, 'id': id, 'subject':subject, 'message':message }, 
		dataType: 'json',
		error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				$("#load-info"+id).hide();
				$("#mess-info"+id).html("");
				$("#sent-title"+id).html(message);

				s_h = false; new_id = false;
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function AdsReq(id, type, op) {
	if (op == "Delete" && !confirm("�� ������� ��� ������ ������� ��������� �������� ID: "+id+" ?")) {
		return false;
	} else if (op == "Start" && !confirm("��������� �������� � ID: "+id+" ?")) {
		return false;
	} else {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#adv_dell"+id).remove();
					$("#load-info"+id).remove();
					$("#hide"+id).remove();

					s_h = false; new_id = false;

					AddRow("newform");
					return false;
				} else {
					alert(message);
					return false;
				}
			}
		});
	}
}

function PlayPause(id, type, op) {
	$.ajax({
		type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
		data: { 'op': op, 'type': type, 'id': id }, 
		dataType: 'json', 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
		success: function(data) { 
			$("#loading").slideToggle();
			if (data.result == "OK") { 
				$("#playpauseimg"+id).html(data.message);
			} else { 
				if(data.message) { alert(data.message); }
				else { alert("������ ��������� ������!"); return false; }
			}
			return false;
		}
	});
}

function ClearStat(id, type, op, count1, count2) {
	if (count1 == 0 && count2 == 0) {
		alert("������� ���� �������� ��� ����� 0");
	} else if (confirm("�������� ������� ��������� �������� ID: "+id+" ?")) {
		$.ajax({
			type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#a_stat"+id).html('0');
					$("#b_stat"+id).html('0');
					alert("������� ���������� ��������� �������� ID: "+id+" ������� �������!");
					return false;
				} else {
					if(data.message) { alert(data.message); }
					else { alert("������ ��������� ������!"); return false; }
				}
			}
		});
	}
}

function AddRow(id){
	var table = document.getElementById(id);
	if(table.rows.length < 1) {
		var newrow = table.insertRow();
		newrow.setAttribute("align", "center");
		var newcell = newrow.insertCell();
		newcell.innerHTML = "<b>�������� �� �������!</b>";
	}
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		$("#Save").click();
	}
}
</script>
<?php

if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");

echo '<table class="adv-cab" id="newform">';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `status`>'0' ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="35">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-play" title="��������� �������� �� e-mail" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="������������� �������� �� e-mail" onClick="PlayPause('.$row["id"].', \'sent_emails\', \'PlayPause\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="��������� �������� �� e-mail" onClick="PlayPause('.$row["id"].', \'sent_emails\', \'PlayPause\');"></span>';
					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="��������� �������� �� e-mail" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="4") {
						echo '<span class="adv-block" title="��������� �������� �������������" onClick="alert(\'��������� �������� �������������. ���������� ������ ���������!\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left">';
				echo '<span id="sent-title'.$row["id"].'" class="title-text">'.$row["subject"].'</span><br>';

				echo '<span class="info-text">';
					echo 'ID:&nbsp;<b>'.$row["id"].'</b>, ����:&nbsp;<b>'.$row["merch_tran_id"].'</b>;&nbsp;&nbsp;���������� �����:&nbsp;<b id="a_stat'.$row["id"].'">'.$row["sent"].'</b>;&nbsp;&nbsp;�� ���������� �����:&nbsp;<b id="b_stat'.$row["id"].'">'.$row["nosent"].'</b><br>';
					echo '������ ������: <b>'.$system_pay[$row["method_pay"]].'</b>;&nbsp;&nbsp;';
					echo '�������������: '.($row["wmid"]!=false ? ($row["username"]!=false ? "WMID:<b>".$row["wmid"]."</b>, �����:<b>".$row["username"]."</b>" : "WMID:<b>".$row["wmid"]."</b>") : ($row["username"]!=false ? "�����:<b>".$row["username"]."</b>" : "<span style=\"color:#CCC;\">�� ����������</span>"));
				echo '</span>';

				echo '<span class="adv-dell" title="������� ��������" onClick="AdsReq('.$row["id"].', \'sent_emails\', \'Delete\');"></span>';
				echo '<span class="adv-edit" title="������������� ��������" onClick="LoadInfo('.$row["id"].', \'sent_emails\', \'LoadForm\');"></span>';
				echo '<span class="adv-erase" title="����� ����������" onClick="ClearStat('.$row["id"].', \'sent_emails\', \'ClearStat\', '.$row["sent"].', '.$row["nosent"].');"></span>';
				echo '<span class="adv-info" title="��������������� �������� ��������" onClick="LoadInfo('.$row["id"].', \'sent_emails\', \'GetInfo\');"></span>';
			echo '</td>';

			echo '<td align="center" width="80" nowrap="nowrap">';
				echo '<a class="add-money-'.($row["status"]=="3" ? "req" : "yes").'" title="��������� ������: '.number_format($row["money"], 2, ".", "`").' ���.">';
					echo number_format($row["money"], 2, ".", "`").' ���.';
				echo '</a>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="hide'.$row["id"].'" style="display: none;"><td align="center" colspan="3"></td></tr>';

		echo '<tr id="load-info'.$row["id"].'" style="display: none;">';
			echo '<td align="center" colspan="3" class="ext-text">';
				echo '<div id="mess-info'.$row["id"].'"></div>';
			echo '</td>';
		echo '</tr>';

	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="3"><b>�������� �� �������!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");

?>