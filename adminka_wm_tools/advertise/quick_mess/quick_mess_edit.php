<?php
if(!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;">�������� � �������������� <b>"������� ���������"</b></h3>';

$system_pay[0] = "�������";
$system_pay[1] = "���. ����";
$system_pay[2] = "����. ����";
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
	var url = $.trim($("#url").val());
	var description = $.trim($("#description").val());
	var color = $.trim($("#color").val());
	$("#info-msg-cab").html("").hide();

	$.ajax({
		type: "POST", url: "ajax_admin/ajax_admin_advertise.php?rnd="+Math.random(), 
		data: {'op':op, 'type':type, 'id': id, 'url':url, 'description':description, 'color': color}, 
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
				$("#adv-"+id).html(message);

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

function AddRow(id){
	var table = document.getElementById(id);
	if(table.rows.length < 1) {
		var newrow = table.insertRow();
		newrow.setAttribute("align", "center");
		var newcell = newrow.insertCell();
		newcell.innerHTML = "<b>������� �� �������!</b>";

		location.href = "";
	}
}
</script>
<?php

echo '<table class="adv-cab" id="newform">';
echo '<tbody>';

$sql = mysql_query("SELECT * FROM `tb_ads_quick_mess` WHERE `status`='1' ORDER BY `id` DESC LIMIT 10");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_assoc($sql)) {

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="left" style="border-right:solid 1px #DDDDDD;">';
				echo '<div id="adv-'.$row["id"].'" style="margin-bottom:4px;">';
					echo '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-bottom:2px; padding-right:5px;" src="http://www.google.com/s2/favicons?domain='.($row["url"]!=false ? @gethost($row["url"]) : $_SERVER["HTTP_HOST"]).'" align="absmiddle" />';
					echo '<a class="adv" href="javascript:void(0);"><span '.($row["color"]==1 ? 'class="text-red"' : false).'>'.$row["description"].'</span></a><br>';
					echo 'URL: '.($row["url"]!=false ? '<a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a>' : '<span style="color:#CCC;">���</span>').'';
				echo '</div>';

				echo '<span class="info-text">';
					echo 'ID:&nbsp;<b>'.$row["id"].'</b>;&nbsp;&nbsp;������ ������: <b>'.$system_pay[$row["method_pay"]].'</b>;&nbsp;&nbsp;';
					echo '�������������: '.($row["username"]!=false ? '<b>'.$row["username"].'</b>' : '<span style="color:#CCC;">�� ����������</span>').'';
				echo '</span>';
		
				echo '<span class="adv-dell" title="������� ��������� ��������" onClick="AdsReq('.$row["id"].', \'quick_mess\', \'Delete\');"></span>';
				echo '<span class="adv-edit" title="������������� ��������� ��������" onClick="LoadInfo('.$row["id"].', \'quick_mess\', \'LoadForm\');"></span>';
			echo '</td>';

			echo '<td align="center" width="80" nowrap="nowrap" style="border-left:solid 2px #FFFFFF;">';
				echo '<a class="add-money-yes" title="��������� ������: '.number_format($row["money"], 2, ".", "`").' ���.">';
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
	echo '<tr align="center"><td colspan="2"><b>������� �� �������!</b></td></tr>';
}
echo '</tbody>';
echo '</table>';

?>