<?php
$pagetitle="Контакты";
include('header.php');
?>

<div align="center" style="padding:6px;">
<table class="tables">

<?php
if($site_fio!=false) echo '<thead><tr><th align="center" colspan="2" class="top">Администратор: '.$site_fio.'</th></tr></thead>';
if($site_wmid!=false) echo '<tr><td align="right" width="35%">WMid<img src="img/wmid.ico" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$site_wmid.'</td></tr>';
if($site_wmr!=false) echo '<tr><td align="right" width="35%">WMR<img src="img/wmr.ico" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$site_wmr.'</td></tr>';
if($site_email!=false) echo '<tr><td align="right" width="35%">E-mail <img src="images/message.gif" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left"><a href="mailto:'.$site_email.'">'.str_replace("@"," @ ",$site_email).'</a></td></tr>';
if($site_telefon!=false) echo '<tr><td align="right" width="35%">MegaFon <img src="img/phone.gif" width="16" align="absmiddle" border="0" alt="" /></td><td align="left">'.$site_telefon.'</td></tr>';
if($site_isq!=false) echo '<tr><td align="right" width="35%">ICQ <img src="img/isq.gif" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$site_isq.'</td></tr>'; 
?>
</table>


<?php
/*
$sql_cnt = mysql_query("SELECT * FROM `tb_users` WHERE `user_status`='1' AND `id`!='1' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row_cnt = mysql_fetch_assoc($sql_cnt)) {
		$sql_o = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`='".$row_cnt["username"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_o)>0) {
			$online_moder = '<span class="text-green">OnLine</span>';
		}else{
			$online_moder = '<span class="text-red">OffLine</span>';
		}
		echo '<table class="tables" style="margin:15px auto ;">';
		echo '<thead><tr><th align="center" colspan="2" class="top">Администратор: '.ucfirst($row_cnt["username"]).'</th></tr></thead>';
		echo '<tr><td align="right" width="35%">Статус</td><td align="left">'.$online_moder.'</td></tr>';
		//if($row_cnt["wm_purse"]!=false) echo '<tr><td align="right" width="35%">WMR<img src="img/wmr.ico" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$row_cnt["wm_purse"].'</td></tr>';
		if($row_cnt["email"]!=false) echo '<tr><td align="right" width="35%">E-mail <img src="images/message.gif" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left"><a href="mailto:'.$site_email.'">'.str_replace("@"," @ ",$row_cnt["email"]).'</a></td></tr>';
		if($row_cnt["email"]!=false) echo '<tr><td align="center" colspan="2"><span class="proc-btn" onClick="document.location.href=\'/newmsg.php?name=Znata\'">Написать Администратору</span></td></tr>';
		echo '</table>';
	}
}

$sql_cntt = mysql_query("SELECT * FROM `tb_users` WHERE `user_status`='2' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	while ($row_cnt = mysql_fetch_assoc($sql_cntt)) {
		$sql_o = mysql_query("SELECT `username` FROM `tb_online` WHERE `username`='".$row_cnt["username"]."'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_o)>0) {
			$online_moder = '<span class="text-green">OnLine</span>';
		}else{
			$online_moder = '<span class="text-red">OffLine</span>';
		}

		echo '<table class="tables" style="margin:15px auto ;">';
		echo '<thead><tr><th align="center" colspan="2" class="top">Модератор рекламы: '.ucfirst($row_cnt["username"]).'</th></tr></thead>';
		echo '<tr><td align="right" width="35%">Статус</td><td align="left">'.$online_moder.'</td></tr>';
		//if($row_cnt["wmid"]!=false) echo '<tr><td align="right" width="35%">WMid<img style="margin:0; padding:0;" src="img/wmid.ico" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$row_cnt["wmid"].'</td></tr>';
		//if($row_cnt["wm_purse"]!=false) echo '<tr><td align="right" width="35%">WMR<img style="margin:0; padding:0;" src="img/wmr.ico" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left">'.$row_cnt["wm_purse"].'</td></tr>';
		if($row_cnt["email"]!=false) echo '<tr><td align="right" width="35%">E-mail <img style="margin:0; padding:0;" src="images/message.gif" width="16" align="absmiddle" border="0" height="16" alt="" /></td><td align="left"><a href="mailto:'.$site_email.'">'.str_replace("@"," @ ",$row_cnt["email"]).'</a></td></tr>';
		if($row_cnt["email"]!=false) echo '<tr><td align="center" colspan="2"><span class="proc-btn" onClick="document.location.href=\'/newmsg.php?name='.$row_cnt["username"].'\'">Написать модератору</span></td></tr>';
		echo '</table>';
	}
}
*/
?>

</div>

<?php include('footer.php');?>