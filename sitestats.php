<?php
$sql = mysql_query("SELECT count(id) FROM `tb_users` WHERE `money_rek`>'0'");
$reklama_all = mysql_result($sql,0,0);

$sql = mysql_query("SELECT count(id) FROM `tb_users` WHERE `lastlogdate2`>='".strtotime(DATE("d.m.Y", time()-1*24*60*60))."'");
$active_users = mysql_result($sql,0,0);
?>
<div id="block-stat" style="padding:6px;">
<div id="block-stat-stats">���������� �������</div>
<div id="block-stat-text"><span><?=site_work($start_time,2);?></span><span class="stat-text">�� ��������</span></div>
<div id="block-stat-text"><span><?php echo number_format($users_all,0,'.','`');?></span><span class="stat-text">�������������</span></div>
<div id="block-stat-text"><span><?php echo number_format($reklama_all,0,'.','`');?></span><span class="stat-text">��������������</span></div>
<div id="block-stat-text"><span><?php echo number_format($users_24h,0,"."," ");?></span><span class="stat-text">����� �� 24 ����</span></div>
<div id="block-stat-text"><span><?php echo number_format($sumpay,2,'.','`');?></span><span class="stat-text">����� ���������</span></div>
<div id="block-stat-text"><span><center>���� � ����� �������<br><?php include('taim.php');?></center></span></div>
</div>