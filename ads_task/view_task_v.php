<?php
session_start();
include("../config.php");
?>
<style>
.title-popup{background-color:rgba(0, 154, 86, 0.9);color:#FFF;line-height: 40px;padding:0;padding-left:10px;font-size:14px;}
.closed-popup{position: absolute;top:0;right:0;font-size:14px;line-height: 42px;text-align:center;display:inline-block;width:100px;cursor:pointer;color:#FFF;}
.closed-popup:hover{opacity:0.9}
</style>
<style>
#newsmodal2 {position:absolute;top:50%;left:50%;margin-top:-250px;margin-left:-378px;width:450px;}
#newsmodal2-overlay {background-color: #000000;cursor: auto;}
#newsmodal2-container {
	background-color: #FFFFFF;
	border: 2px solid #0087a7;
	color: #545454;
	height: 500px;
	padding: 1px;
	width: 750px;
	top: 60px; 
	position: fixed; 
	display: block;
	opacity: 1;
	}
</style>

<?
if (isset($_POST['is']))
{
if ($_POST['is']){
$is=$_POST['is'];			
?>

<div id="newsmodal2-container" class="newsmodal2-container">
<span class="closed-popup" onclick="closed_popup();">Закрыть</span>
<div class="title-popup">Пользователи, которым оплачены выполнения задания № <?=$is?></div>
<div style="overflow: auto; height: 450px; padding: 5px;">

<?php
		echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top">№</th>';
	echo '<th class="top">Аватар</th>';
	echo '<th class="top">Логин</th>';
	echo '<th class="top">Дата начало<br>Отчет подан</th>';
	echo '<th class="top">Отчёт исполнителя:</th>';
	echo '<th class="top">IP</th>';
echo '</tr></thead>';

$sql = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `ident`='$is' AND `type`='task' AND (`status`='' OR `status`='dorab') ORDER BY `id` DESC");
if(mysql_num_rows($sql)>0) {
	while($row=mysql_fetch_array($sql)) {
		$sql1 = mysql_query("SELECT * FROM tb_users WHERE `username`='".$row["user_name"]."'");
	while($stat=mysql_fetch_array($sql1)) {
		
				$a=$a+1;
		echo '<tr align="center">';
		echo '<td width="3%">'.$a.'</td>';
		echo '<td width="3%"><a href="/wall?uid='.$row["user_id"].'" target="_blank"><img class="avatar" src="/avatar/'.$stat["avatar"].'" style="width:40px; height:40px" border="0" alt="avatar" title="Перейти на стену пользователя '.$row["user_name"].'"></a></td>';
		echo '<td align="left" style="border-left:none;" width="20%">';
		if($stat["country_cod"]!="") {
		echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($stat["country_cod"]).'.gif" alt="" title="'.$stat["country_cod"].'" width="16" height="12" style="margin:0; padding:0;" align="absmiddle" />&nbsp;';
					}
		echo '<a href="newmsg.php?name='.$stat["username"].'" target="_blank" title="Написать рефералу"><b>'.$stat["username"].'</b></a>';
	 echo ' <span style="color: #cd5b45;">ID: '.$stat["id"].'</span>';
				echo '<br><span class="desctext">Рейтинг:&nbsp;<span style="color: #009C31; text-shadow:1px 1px 2px #fff;">+'.round($stat["reiting"], 2).'<span style="cursor: help" title="Оценка активности системой">&nbsp;|&nbsp;Активность:&nbsp;<span style="color: #0079B8; text-shadow:1px 1px 2px #fff;">';
				$activ=$stat["activ"];
								if($activ>='100'){echo '100%';}
							else
								if($activ>='80'&& $activ<='99.9'){echo '80%';}
							else
								if($activ>='70'&& $activ<='79.9'){echo '70%';}
							else
								if($activ>='60'&& $activ<='69.9'){echo '60%';}
							else
								if($activ>='50'&& $activ<='59.9'){echo '50%';}
							else
								if($activ>='30'&& $activ<='49.9'){echo '30%';}
							else
								if($activ>='0'&& $activ<='29.9'){echo '0%';}
				echo '</span></span></span></span>';
				echo '<br><span style="color: #167E48;">Репутация исполнителя <img class="tipi" src="/img/task_16x16.png" border="0" alt="" title="Репутация исполнителя" style="margin:0; padding:0; margin-right:5px;" align="absmiddle">+ '.$stat["rep_task"].'</span>';
		echo '</td>';
		echo '<td width="15%">'.DATE("d.m.Y в H.i",$row["date_start"]).'<br>';

		if($row["status"]=='dorab'){echo'На доработке до <br>'.DATE("d.m.Y в H.i",$row["date_end"]).'';}
		else
		{echo''.DATE("d.m.Y в H.i",$row["date_end"]).'';}
		echo'</td>';
		echo '<td width="15%">'.$row["ctext"].'</td>';
		echo '<td width="3%">'.$row["ip"].'</td>';
		echo '</tr>';
		
	}
	}
	echo '</table>';
}
else
{
	echo'<span class="msg-w" style="text-align:justify;"><center><b>История выполнений пуста</b></center></span>';
}



}
}
mysql_close();
?>