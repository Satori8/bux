<?php
require_once('.zsecurity.php');
$pagetitle="Автоматический серфинг YouTube";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">Ошибка! Для доступа к этой странице необходимо авторизоваться!</fieldset>';
}else{
	if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
	require('config.php');
	mysql_query("DELETE FROM `tb_ads_autoyoutube_visits` WHERE `date`<'".(time()-72*60*60)."'") or die(mysql_error());
	mysql_query("UPDATE `tb_ads_autoyoutube` SET `status`='3' WHERE `status`>'0' AND `totals`<'1' ") or die(mysql_error());

	$sql = mysql_query("SELECT `id` FROM `tb_ads_autoyoutube` WHERE `status`='1' AND `totals`>'0' AND `limits_now`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_autoyoutube_visits` WHERE `username`='$username' AND `date`>='".(time()-24*60*60)."') ORDER BY `id` DESC");
	$kolvo_site = mysql_num_rows($sql);

	echo 'Доступно видеороликов для просмотра: <b>'.$kolvo_site.'</b><br><br>';
	if($kolvo_site>0) echo '<a href="https://'.$_SERVER["HTTP_HOST"].'/autoserfgo/auto.php" target="_blank"><input type="submit" value="Начать просмотр" class="proc-btn" style="float:none;" /></a><br><br><br>';
	else echo '<br>';
	}else{
echo '<span class="msg-warning">Для работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше имя (которое будет отображатся на стене)<br> указать дату рождения <br> указать хотя-бы один кошелек для выплат!<br>Перейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">Мои личные данные</a>"</span></span>';
}
}

include('footer.php');?>