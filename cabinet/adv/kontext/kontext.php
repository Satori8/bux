<?php
if(!DEFINED("CABINET")) {die ("Hacking attempt!");}

mysql_query("UPDATE `tb_ads_kontext` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

if(isset($ads) && isset($id) && isset($op) && $ads=="kontext" && $op=="edit" && $id>0) {
	if(!DEFINED("KONTEXT_EDIT")) DEFINE("KONTEXT_EDIT", true);
	include("kontext_edit.php");
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">��� ����������� �������</h5>';

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_min' AND `howmany`='1'");
$cena_kontext_min = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext' AND `howmany`='1'");
$cena_kontext = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_kontext_color' AND `howmany`='1'");
$cena_kontext_color = mysql_result($sql,0,0);

?><script type="text/javascript" language="JavaScript">
function pay_adv(id, type) {
	var plan = $.trim($('#plan'+id).val());
	plan = str_replace(",", ".", plan);
	plan = str_replace("-", "", plan);
	plan = plan.replace(",", ".");
	rplan = parseInt(plan);

	if (plan == '') {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>������� ����������� ���������� ���������</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
	} else if (isNaN(plan)) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>�������� ������ ���� ��������</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
	} else if (plan != rplan) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>�������� ������ ���� ����� ������</span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
	} else if (rplan < <?=$cena_kontext_min;?>) {
		gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>���������� ��������� ������ ���� �� ����� <?=$cena_kontext_min;?></span>";
		gebi("imfo-msg-addmoney"+id).style.display = 'block';
		setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
	} else {

		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: { 'op':'pay_adv', 'type':type, 'id':id, 'plan':plan }, 
			beforeSend: function() { $('#loading').show(); }, 
			success: function(data) { 
				$('#loading').hide();

				if (data == "ERROR1") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>���������� ��������� ������ ���� �� ����� <?=$cena_kontext_min;?></span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
				} else if (data == "ERROR2") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-error'>�� ����� ��������� ����� ������������ �������!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {$("#imfo-msg-addmoney"+id).fadeOut('slow')}, 2000); clearTimeout();
				} else if (data == "OK") {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-ok'>������ ��������� �������� ������� ��������!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
					setTimeout(function() {
						$("#imfo-msg-addmoney"+id).fadeOut('slow'); 
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
					}, 1000); clearTimeout();
				}else {
					gebi("imfo-msg-addmoney"+id).innerHTML = "<span class='msg-w'>�� ������� ���������� ������!</span>";
					gebi("imfo-msg-addmoney"+id).style.display = 'block';
				}
			}
		});
	}

}
</script><?php

$sql = mysql_query("SELECT * FROM `tb_ads_kontext` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	echo '<table class="adv-cab">';

	while ($row = mysql_fetch_array($sql)) {
		$cena_one = number_format(($cena_kontext + $row["color"] * $cena_kontext_color), 3, ".", "");

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td align="center" width="30" class="noborder1">';
				echo '<div id="playpauseimg'.$row["id"].'">';
					if($row["status"]=="0") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="alert_nostart();"></span>';
					}elseif($row["status"]=="1") {
						echo '<span class="adv-pause" title="������������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
					}elseif($row["status"]=="2") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
					}elseif($row["status"]=="3") {
						echo '<span class="adv-play" title="��������� ����� ��������� ��������" onClick="play_pause('.$row["id"].', \'kontext\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" class="noborder2">';
				echo '<a class="adv" href="'.$row["url"].'" target="_blank">'.$row["title"].'<br><span class="desc-text">'.$row["description"].'</span></a><br>';
				echo '<span class="info-text">';
					echo '�:&nbsp;'.$row["id"].'&nbsp;&nbsp;�������:&nbsp;'.$cena_one.'&nbsp;���.&nbsp;&nbsp;���������:&nbsp;<span id="c_stat'.$row["id"].'">'.$row["outside"].'</span>';
				echo '</span>';
				echo '<span class="adv-dell" title="������� ������" onClick="alert_delete('.$row["id"].', \'kontext\');"></span>';
				echo '<span class="adv-erase" title="����� ����������" onClick="clear_stat('.$row["id"].', \'kontext\', '.$row["outside"].');"></span>';
				echo '<a class="adv-edit" href="?ads='.$ads.'&op=edit&id='.$row["id"].'#goto" title="������������� ������"></a>';
			echo '</td>';

			echo '<td align="center" width="60" nowrap="nowrap">';
				if($row["status"]=="0") {
					echo '<a class="add-money-no" title="��������� ��������� ������" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">���������</a>';
				}elseif($row["status"]=="3" && $row["totals"]>0) {
					echo '<a class="add-money" title="��������� ��������� ������" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.$row["totals"].'</a>';
				}elseif($row["status"]=="3" && $row["totals"]<1) {
					echo '<a class="add-money" title="��������� ��������� ������" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">���������</a>';
				}else{
					echo '<a class="add-money" title="��������� ��������� ������" onClick="show_money_add(\'adv_addmoney'.$row["id"].'\');">'.$row["totals"].'</a>';
				}
			echo '</td>';
		echo '</tr>';

		echo '<tr id="adv_addmoney'.$row["id"].'" style="display: none">';

			?><script type="text/javascript" language="JavaScript">
			function obsch<?php echo $row["id"];?>(){
				var plan<?php echo $row["id"];?> = gebi('plan<?php echo $row["id"];?>').value;
				plan<?php echo $row["id"];?> = str_replace(",", ".", plan<?php echo $row["id"];?>);
				plan<?php echo $row["id"];?> = str_replace("-", "", plan<?php echo $row["id"];?>);
				var cena_link<?php echo $row["id"];?> = <?php echo ($cena_kontext + $row["color"] * $cena_kontext_color);?>;

				gebi('price<?php echo $row["id"];?>_1').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_link<?php echo $row["id"];?>), 2, '.', ' ') + ' ���.';
				gebi('price<?php echo $row["id"];?>_2').innerHTML = number_format((plan<?php echo $row["id"];?> * cena_link<?php echo $row["id"];?> * <?php echo ((100-$cab_skidka)/100);?>), 2, '.', ' ') + ' ���.';
			}
			</script><?php

			echo '<td align="center" colspan="3" class="ext-text">';

				echo '������� ���������� ���������, ������� �� ������ ������ � ������ ��������� ��������<br>(������� '.count_text($cena_kontext_min, "���������", "�������", "��������", "").')';
				echo '<input type="text" maxlength="10" id="plan'.$row["id"].'" value="" class="payadv" onChange="obsch'.$row["id"].'();" onKeyUp="obsch'.$row["id"].'();" />';

				echo '���������:';
				echo '<span id="price'.$row["id"].'_1" class="payadvrez">0.00 ���.</span>';
				if($cab_status==1 && $cab_skidka>0) echo '<div style="color:#708090;">(� ������ ����� ������ '.$cab_skidka.'% ��� �������� <span id="price'.$row["id"].'_2">0.00 ���.</span>)</div>';

				echo '<center><span onClick="pay_adv('.$row["id"].', \'kontext\');" class="sub-green" style="float:none;" title="��������� ������ ��������">��������</span></center>';

				echo '<div id="imfo-msg-addmoney'.$row["id"].'" style="display: none"></div>';
			echo '</td>';

		echo '</tr>';
	}

	echo '</table>';
}else{
	echo '<span class="msg-w">� ��� ��� ����� ����������� ����������� ������</span>';
}

echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=kontext\'">���������� ������</span></div>';
?>