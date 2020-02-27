<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");


//очистка неотправленых отчетов 
$sql_del = mysql_query("SELECT date_start,id,ident,status FROM `tb_ads_task_pay` WHERE `status`!='wait' && `status`!='good_auto' &&`status`!='good' && `status`!='dorab' && `status`!='bad'");
					while ($row_del = mysql_fetch_array($sql_del)) {
					
					$us_del = mysql_fetch_array(mysql_query("SELECT time,id FROM `tb_ads_task` WHERE `id`='".$row_del['ident']."'"));
if($us_del['time']=='1'){
$tm_z=60*60;
}elseif($us_del['time']=='2'){
$tm_z=3*60*60;
}elseif($us_del['time']=='3'){
$tm_z=12*60*60;
}elseif($us_del['time']=='4'){
$tm_z=24*60*60;
}elseif($us_del['time']=='5'){
$tm_z=3*24*60*60;
}elseif($us_del['time']=='6'){
$tm_z=7*24*60*60;
}elseif($us_del['time']=='7'){
$tm_z=14*24*60*60;
}elseif($us_del['time']=='8'){
$tm_z=30*24*60*60;
}else{
$tm_z=60*60;
}
$time_m=time()-$tm_z;
				
					if($time_m>=$row_del['date_start']){
					//echo $row_del['status'].'dd';
					mysql_query("DELETE FROM `tb_ads_task_pay` WHERE `id`='".$row_del['id']."'") or die(mysql_error());
					}	
					}
					
					
//задания 24 часа на доработке очищаются					
$sql_delq = mysql_query("SELECT date_dorab,id,ident FROM `tb_ads_task_pay` WHERE `status`='dorab'");
					while ($row_delq = mysql_fetch_array($sql_delq)) {
$tm_dorab=24*60*60;
$time_dorab=time()-$tm_dorab;
				
					if($time_dorab>=$row_delq['date_dorab']){
					mysql_query("DELETE FROM `tb_ads_task_pay` WHERE `id`='".$row_delq['id']."' and `status`='dorab'") or die(mysql_error());
					}	
					}					
				

?>