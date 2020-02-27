<script type="text/javascript" src="js/jquery_min.js" ></script>
<link rel="stylesheet" href="css/ui.datepicker.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="js/ui.datepicker-ru.js"></script>
<script type="text/javascript">
	var $d = jQuery.noConflict();

	$d(document).ready(function() {
		$d.datepicker.setDefaults($d.datepicker.regional['ru']);

		$d("#startDate").datepicker({
		    yearRange: "<?php echo (DATE("Y")-1).":".(DATE("Y")+1);?>",
		    showOn: "both",
		    buttonImageOnly: true
		});
	});
</script>

<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>Настройки сайта</b></h3>';

if(count($_POST)>0) {

	$site_status = intval(trim($_POST["site_status"]));
	$site_status_txt = (trim($_POST["site_status_txt"]));

	$site_name=$_POST["sitename"];
	$site_wmid=$_POST["sitewmid"];
	$site_wmr=$_POST["sitewmr"];
	$site_email=$_POST["siteemail"];
	$site_isq=$_POST["siteisq"];
	$site_telefon=$_POST["sitetelefon"];
	$site_fio = trim($_POST["site_fio"]);
	$startdate = trim($_POST["startdate"]);
	$startdate = DATE("Y-m-d", strtotime($startdate));
	
	$dll_noact_users_status = intval(abs(trim($_POST["dll_noact_users_status"])));
	$dll_noact_users_count_day = intval(abs(trim($_POST["dll_noact_users_count_day"])));

	if($dll_noact_users_count_day<30) {
		echo '<span class="msg-error">Для удаления неактивных минимум 30 дней неактивности!</span>';
	}else{
		mysql_query("UPDATE `tb_config` SET `howmany`='$dll_noact_users_count_day', `price`='$dll_noact_users_status' WHERE `item`='dll_noact_users'") or die(mysql_error());

	mysql_query("UPDATE `tb_site` SET 
			`site_status`='$site_status', `site_status_txt`='$site_status_txt', `sitename`='$site_name', `sitewmid`='$site_wmid', `sitewmr`='$site_wmr', 
			`siteemail`='$site_email', `siteisq`='$site_isq', `sitetelefon`='$site_telefon', `site_fio`='$site_fio', `startdate`='$startdate' 
	WHERE `id`='1'") or die(mysql_error());

	echo '<span id="info-msg" class="msg-ok">Изменения успешно сохранены!</span>';
	}  
	echo '<script type="text/javascript">
		setTimeout(function() {
			window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'");
		}, 1500);
		HideMsg("info-msg", 1500);
	</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="1;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'"></noscript>';
}

$sql = mysql_query("SELECT * FROM `tb_site` WHERE `id`='1'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

}else{
	echo '<span class="msg-error">Нет данных!</span>';
}

$sql_dll = mysql_query("SELECT * FROM `tb_config` WHERE `item`='dll_noact_users'");
if(mysql_num_rows($sql_dll)>0) {
	$row_dll = mysql_fetch_array($sql_dll);

	$dll_noact_users_count_day = $row_dll["howmany"];
	$dll_noact_users_status = $row_dll["price"];

}else{
	$dll_noact_users_count_day = false;
	$dll_noact_users_status = false;
}


echo '<form method="post" action="" id="newform">';
echo '<table class="tables" style="margin:0px; padding:0px;">';
	echo '<tr align="center"><th width="300">Параметр</th><th width="350">Значение</th><th>Описание</th></tr>';
	echo '<tr><td align="left"><b>Имя сайта:</b></td><td><input type="text" name="sitename" value="'.$row["sitename"].'" class="ok"></td><td>&nbsp;&nbsp;</td></tr>';
	echo '<tr><td align="left"><b>Ф.И.О:</b></td><td><input type="text" name="site_fio" value="'.$row["site_fio"].'" class="ok"></td><td>Если заполнить то будет отображаться в контактах, при подключении сайта к автовыплатам или получения аттестата Продавца надо указать</td></tr>';
	echo '<tr><td align="left"><b>E-mail:</b></td><td><input type="text" name="siteemail" value="'.$row["siteemail"].'" class="ok"></td><td>Если заполнить то будет отображаться в контактах</td></tr>';
	echo '<tr><td align="left"><b>WMID:</b></td><td><input type="text" name="sitewmid" value="'.$row["sitewmid"].'" class="ok12"></td><td>&nbsp;&nbsp;</td></tr>';
	echo '<tr><td align="left"><b>WMR</b> (кошелек)<b>:</b></td><td><input type="text" name="sitewmr" value="'.$row["sitewmr"].'" class="ok12"><td>Кошелек для приема оплаты за рекламу и для выплат</td></tr>';
	echo '<tr><td align="left"><b>ICQ:</b></td><td><input type="text" name="siteisq" value="'.$row["siteisq"].'" class="ok12"></td><td>Если заполнить то будет отображаться в контактах</td></tr>';
	echo '<tr><td align="left"><b>Контактный телефон:</b></td><td><input type="text" name="sitetelefon" value="'.$row["sitetelefon"].'" class="ok12"></td><td>Если заполнить то будет отображаться в контактах</td></tr>';

	echo '<tr><td align="left"><b>Дата открытия сайта:</b></td><td><input type="text" class="ok12" id="startDate" name="startdate" value="'.DATE("d.m.Y", strtotime($row["startdate"])).'" style="text-align:center;"></td><td></td></tr>';

	echo '<tr>';
		echo '<td align="left"><b>Режим работы сайта:</b></td>';
		echo '<td align="left">';
			echo '<select name="site_status" style="width:125px; text-align:center;">';
				echo '<option value="0" '.("0" == $row["site_status"] ? 'selected="selected"' : false).'>Тех работы</option>';
				echo '<option value="1" '.("1" == $row["site_status"] ? 'selected="selected"' : false).'>Рабочий режим</option>';
			echo '</select>';
		echo '</td>';
		echo '<td>&nbsp;&nbsp;</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td><b>Текст для тех. работ:</b></td>';
		echo '<td>';
			echo '<textarea name="site_status_txt" class="ok" style="text-align:center;">'.$row["site_status_txt"].'</textarea>';
		echo '</td>';
		echo '<td>Описание для статуса технических работ</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td align="left"><b>Удаление неактивных пользователей:</b></td>';
		echo '<td align="left">';
			echo '<select name="dll_noact_users_status" style="width:125px; text-align:center;">';
				echo '<option value="0" '.("0" == $dll_noact_users_status ? 'selected="selected"' : false).'>Нет</option>';
				echo '<option value="1" '.("1" == $dll_noact_users_status ? 'selected="selected"' : false).'>Да</option>';
			echo '</select>';
		echo '</td>';
		echo '<td>&nbsp;&nbsp;</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>Количество дней не активности:</b></td>';
		echo '<td><input type="text" name="dll_noact_users_count_day" value="'.$dll_noact_users_count_day.'" class="ok12" style="text-align:center;"></td>';
		echo '<td>количество дней которые пользователь не посещал аккаунт</td>';
	echo '</tr>';

	echo '<tr align="center"><td>&nbsp;&nbsp;</td><td><input type="submit" value="Cохранить изменения" class="sub-blue160"><td>&nbsp;&nbsp;</td></tr>';
echo '</table>';
echo '</form><br><br>';


?>