<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������������ ������ ������</b></h3>';

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

mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
mysql_query("UPDATE `tb_ads_tests` SET `status`='1', `date_edit`='".time()."' WHERE `status`='3' AND `balance`>=`cena_advs`");

require("navigator/navigator.php");
$perpage = 25;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_tests` WHERE `status`='0'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

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
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				new_id = id; s_h = id + op;

				//$("#maincontent").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);
				$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);

				$("#load-info"+id).show(); 
				$("#mess-info"+id).html('<span class="msg-error">������ ��������� ������!</span>');
				return false;
			}, 
			success: function(data) { 
				$("#loading").slideToggle();

				new_id = id; s_h = id + op;

				//$("#maincontent").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);
				$("html, body").animate({scrollTop: $("#adv_dell"+id).offset().top-3}, 700);

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

function AdsReq(id, type, op) {
	if (op == "Delete" && !confirm("�� ������� ��� ������ ������� ��������� �������� ID: "+id+" ?")) {
		return false;
	} else if (op == "Start" && !confirm("�������� ��������� �������� ID: "+id+" ?")) {
		return false;
	} else {

		$.ajax({
			type: "POST", url: "ajax_admin/ajax_json_adv.php?rnd="+Math.random(), 
			data: { 'op': op, 'type': type, 'id': id }, 
			dataType: 'json', 
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() { $("#loading").slideToggle(); alert("������ ��������� ������ AJAX!"); return false; }, 
			success: function(data) { 
				$("#loading").slideToggle();
				if (data.result == "OK") {
					$("#adv_dell"+id).remove();
					$("#load-info"+id).remove();
					$("#hide"+id).remove();
					AddRow("newform");
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
		newcell.innerHTML = "<b>�� ���������� ������� ������� ���!</b>";
	}
}

</script>
<?php

if($count>$perpage) {echo "<br>"; universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");}
echo '<table class="adv-cab" id="newform">';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `status`='0' ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="35">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="AdsReq('.$row["id"].', \'tests\', \'Start\');"></span>';
				echo '</div>';
			echo '</td>';

			echo '<td align="left">';
				echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-bottom:2px; padding-right:5px;" src="http://www.google.com/s2/favicons?domain='.@gethost($row["url"]).'" align="absmiddle" />';
				echo '<a class="'.($row["color"]==1 ? "adv-red" : "adv").'" href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.$row["title"].'</a><br>';

				echo '<span class="info-text">';
					echo 'ID, ����:&nbsp;<b>'.$row["id"].'</b>, <b>'.$row["merch_tran_id"].'</b>;&nbsp;&nbsp;';
					echo '���� �� ����:&nbsp;<b>'.$row["cena_advs"].'</b>&nbsp;���.;&nbsp;&nbsp;';
					echo '����������:&nbsp;<b>'.number_format(floor(bcdiv($row["money"], $row["cena_advs"])), 0, ".", "`").'</b><br>';
					echo '������ ������: <b>'.$system_pay[$row["method_pay"]].'</b>;&nbsp;&nbsp;';
					echo '�������������: '.($row["wmid"]!=false ? ($row["username"]!=false ? "WMID:<b>".$row["wmid"]."</b>, �����:<b>".$row["username"]."</b>" : "WMID:<b>".$row["wmid"]."</b>") : ($row["username"]!=false ? "�����:<b>".$row["username"]."</b>" : "<span style=\"color:#CCC;\">�� ����������</span>"));
				echo '</span>';

				echo '<span class="adv-dell" title="������� �����" onClick="AdsReq('.$row["id"].', \'tests\', \'Delete\');"></span>';
				echo '<span class="adv-info" title="���������� ��������� ��������" onClick="LoadInfo('.$row["id"].', \'tests\', \'GetInfo\');"></span>';
			echo '</td>';

			echo '<td align="center" width="80" nowrap="nowrap">';
				echo '<a class="add-money-req" title="��������� ������: '.number_format($row["money"], 2, ".", "`").' ���.; ����������: '.number_format(floor(bcdiv($row["money"], $row["cena_advs"])), 0, ".", "`").'">';
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
		echo '<td colspan="3"><b>�� ���������� ������� ������� ���!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) {echo ""; universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");}

?>