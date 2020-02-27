<?php
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
$pagetitle = "ќплачиваемые задани€";
include(DOC_ROOT."/header.php");
//echo '<div style="margin:7px auto; background:#F0F8FF; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:center; color:#E32636;"><b>–аздел на реконструкции временно закрыт!</b></div>';

require_once(DOC_ROOT."/.zsecurity.php");
require(DOC_ROOT."/merchant/func_mysql.php");
//require(DOC_ROOT."/funciones.php");
require(DOC_ROOT."/config.php");
if(($my_wm_purse!= "" or $my_ym_purse!= "" or $my_qw_purse!= "" or $my_pm_purse!= "" or $my_py_purse!= "" or  $my_mb_purse!= "" or  $my_sb_purse!= "" or  $my_ac_purse!= "" or  $my_me_purse!= "" or  $my_vs_purse!= "" or  $my_ms_purse!= "" or  $my_be_purse!= "" or  $my_mt_purse!= "" or  $my_mg_purse!= "" or $my_tl_purse!= "") and $db_time!="0"){
include(DOC_ROOT."/view_task/task.php");
}else{
echo '<span class="msg-warning">ƒл€ работы вам надо заполнить профиль:<br><span style="color:#1f0a02;"> указать ваше им€ (которое будет отображатс€ на стене)<br> указать дату рождени€ <br> указать хот€-бы один кошелек дл€ выплат!<br>ѕерейдите на страницу "<a href="profile.php" class="ajax-site" style="color: #fff; border-bottom: 1px dotted;">ћои личные данные</a>"</span></span>';
}
include(DOC_ROOT."/footer.php");

?>