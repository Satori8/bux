<?php
$pagetitle="����������";
include('header.php');
?><script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script><?php

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">����� ���������� �� <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b></span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
//echo '<h3 class="sp" style="text-align:left;">����� ���������� �� <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b></h3>';
echo '<b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b> - ����� �� ������ ��� ���������, �� � ��� ��������������. ������, ������� �� ����� ������� ����� ���������� ����� ����������������� � �� ���� ��� ���! ������� �� �������, �� ����������� �� ������ ����, �� � ������������ ����������, ������� �������� ��� ���������� ����� ���������� �������. ����� ������� � ������ ����, �� ������ ����� � ��� � ������������� �������, ��� ����� ������ ��������� ������������� ������ ��������� � ������ ������������ ������� �� �����.<br>';
echo '</div>';

echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">��� �������� ����������?</span>';
		echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                         echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#fbefdf" align="center"> ';
//echo '<h3 class="sp" style="text-align:left;">��� �������� ����������?</h3>';
echo '��� ��������� ����������, ��� ����� ������� ����������� ���������� ��������<br> ��� �� ������� ����������� ����� �� ���� ����.<br>';
echo '��� ���� ���������� ����� ������������ � ��� �� �����.<br><br>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th colspan="6">���� ����������</th>';
echo '</tr></thead>';

echo '<tr align="center">';
	echo '<td></td>';
	echo '<td><b>������</b></td>';
	echo '<td><b>�������</b></td>';
	echo '<td><b>������</b></td>';
	echo '<td><b>�������</b></td>';
	echo '<td><b>�������</b></td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="width:190px; padding-left:10px;"><b>������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="������"><img src="/img/progress/clicker.png"></div>100 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="������"><img src="/img/progress/clicker.png"></div>1000 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="������"><img src="/img/progress/clicker.png"></div>5000 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="������"><img src="/img/progress/clicker.png"></div>10000 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="������"><img src="/img/progress/clicker.png"></div>20000 ��.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>�������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�������"><img src="/img/progress/tasker.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�������"><img src="/img/progress/tasker.png"></div>300 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�������"><img src="/img/progress/tasker.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�������"><img src="/img/progress/tasker.png"></div>700 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�������"><img src="/img/progress/tasker.png"></div>1000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="������"><img src="/img/progress/mailser.png"></div>100 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="������"><img src="/img/progress/mailser.png"></div>500 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="������"><img src="/img/progress/mailser.png"></div>700 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="������"><img src="/img/progress/mailser.png"></div>1000 ��.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="������"><img src="/img/progress/mailser.png"></div>2000 ��.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>����������� ������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�����"><img src="/img/progress/tester.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�����"><img src="/img/progress/tester.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�����"><img src="/img/progress/tester.png"></div>700 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�����"><img src="/img/progress/tester.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�����"><img src="/img/progress/tester.png"></div>2000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>�������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�������"><img src="/img/progress/refovod.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�������"><img src="/img/progress/refovod.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�������"><img src="/img/progress/refovod.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�������"><img src="/img/progress/refovod.png"></div>3000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�������"><img src="/img/progress/refovod.png"></div>5000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>�������� ���������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�������� ���������"><img src="/img/progress/konkurser.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�������� ���������"><img src="/img/progress/konkurser.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�������� ���������"><img src="/img/progress/konkurser.png"></div>700 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�������� ���������"><img src="/img/progress/konkurser.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�������� ���������"><img src="/img/progress/konkurser.png"></div>5000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>�������� ����������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�������� ����������"><img src="/img/progress/refbirj_prod.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�������� ����������"><img src="/img/progress/refbirj_prod.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�������� ����������"><img src="/img/progress/refbirj_prod.png"></div>2000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�������� ����������"><img src="/img/progress/refbirj_prod.png"></div>3000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�������� ����������"><img src="/img/progress/refbirj_prod.png"></div>5000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>���������� ���������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="���������� ����������"><img src="/img/progress/refbirj_pok.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="���������� ����������"><img src="/img/progress/refbirj_pok.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="���������� ����������"><img src="/img/progress/refbirj_pok.png"></div>2000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="���������� ����������"><img src="/img/progress/refbirj_pok.png"></div>3000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="���������� ����������"><img src="/img/progress/refbirj_pok.png"></div>5000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>�������������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="�������������"><img src="/img/progress/advertisers.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="�������������"><img src="/img/progress/advertisers.png"></div>3000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="�������������"><img src="/img/progress/advertisers.png"></div>5000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="�������������"><img src="/img/progress/advertisers.png"></div>10000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="�������������"><img src="/img/progress/advertisers.png"></div>20000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>����� ���������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="����� ���������"><img src="/img/progress/moneysers.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="����� ���������"><img src="/img/progress/moneysers.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="����� ���������"><img src="/img/progress/moneysers.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="����� ���������"><img src="/img/progress/moneysers.png"></div>5000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="����� ���������"><img src="/img/progress/moneysers.png"></div>10000 ���.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="width:190px; padding-left:10px;"><b>�������� �������� ������:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="forum"><img src="/img/progress/forum.png"></div>100 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="forum"><img src="/img/progress/forum.png"></div>500 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="forum"><img src="/img/progress/forum.png"></div>1000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="forum"><img src="/img/progress/forum.png"></div>3000 ���.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="forum"><img src="/img/progress/forum.png"></div>5000 ���.</td>';
echo '</tr>';


echo '</table>';


include('footer.php');?>