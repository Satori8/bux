<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

if(isset($ads) && isset($id) && isset($op) && $ads=="link" && $op=="edit" && $id>0) {
	if(!DEFINED("link_EDIT")) DEFINE("link_EDIT", true);
	include("link_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">��� ������ � ����� ������� ������</h5>';

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_link' and howmany='1'");
$cena_link = mysql_result($sql,0,0);

$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_color_link' and howmany='1'");
$cena_color_link = mysql_result($sql,0,0);


?><script type="text/javascript" language="JavaScript">
function pay_adv(id, type, sum) {
	if (confirm("�� ������������� ������ ��������� �������� � "+id+" �� ����� "+sum+" ���. (c ������ ������) ?")) {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv', 'type':type, 'id':id }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();

				if (data == "ERROR") {
					alert("�� ����� ��������� ����� ������������ �������!");
					return false;
				} else if (data == "OK") {
					alert("��������� �������� ������� ��������!");
					document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
					return false;
				}else {
					alert("�� ������� ���������� ������!");
					return false;
				}
			}
		});
	}
}
</script><?php

$sql = mysql_query("SELECT * FROM `tb_ads_link` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$sum = number_format((($cena_link + $row["color"] * $cena_color_link) * (100-$cab_skidka)/100), 2, ".", "");

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				if($row["status"]=="0") {
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart_rc();"></span>';
				}elseif($row["status"]=="1") {
					echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="alert_nopause();"></span>';
				}else{
					echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart_rc();"></span>';
				}
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["description"].'</a><br>';
				echo '<span class="info-text">';
					echo '�:&nbsp;'.$row["id"].'&nbsp;&nbsp;����������:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["view"].'</span>';
				echo '</span>';
				echo '<span class="adv-dell" title="������� ������" onClick="alert_delete('.$row["id"].', \'link\');"></span>';
				echo '<span class="adv-erase" title="����� ����������" onClick="clear_stat('.$row["id"].', \'link\', '.$row["view"].');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="������������� ������"></a>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0") {
					echo '<a class="add-money-no" title="�������� ��������� ��������" onClick="pay_adv('.$row["id"].', \'link\', '.$sum.');">��������</a>';
				}else{
					echo '<img src="/img/ok.gif" alt="" title="��������� �������� ��������" border="0" width="32" />';
				}
			echo '</td>';
		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">� ��� ��� ����� ����������� ������ � ����� ������� ������</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=adv_link\'">���������� ������</span></div>';
?>