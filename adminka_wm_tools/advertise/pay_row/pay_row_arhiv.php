<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>����� ������ � ������� ������</b></h3>';

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

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_ads_pay_row` WHERE `status`='3'");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

?>
<script type="text/javascript" language="JavaScript">
function AdsReq(id, type, op) {
	if (op == "Delete" && !confirm("�� ������� ��� ������ ������� �� ������ ��������� �������� ID: "+id+" ?")) {
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
					$("#adv_dell"+id).remove(); $("#hide"+id).remove();
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

function AddRow(id){
	var table = document.getElementById(id);
	if(table.rows.length < 1) {
		var newrow = table.insertRow();
		newrow.setAttribute("align", "center");
		var newcell = newrow.insertCell();
		newcell.innerHTML = "<b>������ � ������ ���!</b>";
	}
}
</script>
<?php

if($count>$perpage) universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");

echo '<table class="tables" id="newform">';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `status`='3' ORDER BY `id` DESC LIMIT $start_pos, $perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="left">';
				echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-bottom:2px; padding-right:5px;" src="http://www.google.com/s2/favicons?domain='.@gethost($row["url"]).'" align="absmiddle" />';
				echo '<a id="pr-title'.$row["id"].'" class="adv" href="'.$row["url"].'" target="_blank" title="'.$row["url"].'">'.$row["description"].'</a><br>';

				echo '<span class="info-text">';
					echo 'ID:&nbsp;<b>'.$row["id"].'</b>, ����:&nbsp;<b>'.$row["merch_tran_id"].'</b>;&nbsp;&nbsp;���������:&nbsp;<b id="a_stat'.$row["id"].'">'.$row["views"].'</b><br>';
					echo '������ ������: <b>'.$system_pay[$row["method_pay"]].'</b>;&nbsp;&nbsp;';
					echo '�������������: '.($row["wmid"]!=false ? ($row["username"]!=false ? "WMID:<b>".$row["wmid"]."</b>, �����:<b>".$row["username"]."</b>" : "WMID:<b>".$row["wmid"]."</b>") : ($row["username"]!=false ? "�����:<b>".$row["username"]."</b>" : "<span style=\"color:#CCC;\">�� ����������</span>"));
				echo '</span>';

				echo '<span class="adv-dell" title="������� ������" onClick="AdsReq('.$row["id"].', \'pay_row\', \'Delete\');"></span>';
			echo '</td>';

			echo '<td align="center" width="80" nowrap="nowrap">';
				echo '<a class="add-money-req" title="��������� ������: '.number_format($row["money"], 2, ".", "`").' ���.">';
					echo number_format($row["money"], 2, ".", "`").' ���.';
				echo '</a>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="hide'.$row["id"].'" style="display: none;"><td align="center" colspan="2"></td></tr>';
	}
}else{
	echo '<tr align="center">';
		echo '<td colspan="2"><b>������ � ������ ���!</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
if($count>$perpage) {echo ""; universal_link_bar($count, $page, $_SERVER["PHP_SELF"], $perpage, 10, '&page=', "?op=$op");}

?>