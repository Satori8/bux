<?php
$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$zdre = $row["zdre"];
	$rek_name = $row["username"];
	$rek_id = $row["user_id"];
	$zdprice = $row["zdprice"];
	$zdurl = $row["zdurl"];
	$country_targ = $row["country_targ"];

	if($my_rep_task <= -10) {
		echo '<span class="msg-error">Вы не можете выполнять задания! Ваша репутация исполнителя&nbsp;&nbsp;'.$my_rep_task.'</span>';
		include('footer.php');
		exit();
	}
$sql_otc_pro_vv = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='' && ident='$rid'");
	$num_price=$row['totals']-mysql_num_rows($sql_otc_pro_vv);

if($num_price<=0){
			
			echo '<span class="msg-warning">Это задание уже выполняет нужное количество пользователей!</span>';
			include('footer.php');
		exit();
			}

		$idz=$row["id"];
		$time_dist=time()-$row['distrib'];
		$sql_distrib = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `date_start`>'$time_dist' && ident='$idz'");
		if($row['distrib']>0 && mysql_num_rows($sql_distrib)>0){			
			echo '<span class="msg-warning">Это задание временно на паузе!</span>';
			include('footer.php');
		exit();
			}
			
	$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$username'");
	if(mysql_num_rows($check_user)) {
		echo '<span class="msg-error">Вы не можете выполнять задания этого рекламодателя, так как рекламодатель запретил вам выполнять его задания!</span>';
		include('footer.php');
		exit();
	}elseif(strtolower($rek_name)==strtolower($username)) {
		echo '<fieldset class="errorp">Вы не можете выполнять свои задания!<br></fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==2 && strtolower($my_country)!="ua") {
		echo '<fieldset class="errorp">Ошибка! По условиям Гео-Таргетинга задание не дустопно для выполнения и оплаты!&nbsp;&nbsp;</fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==1 && strtolower($my_country)!="ru") {
		echo '<fieldset class="errorp">Ошибка! По условиям Гео-Таргетинга задание не дустопно для выполнения и оплаты!&nbsp;&nbsp;</fieldset>';
		include('footer.php');
		exit();
	}else


	$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);
		if($row_p["status"]=="") {
			echo '<fieldset class="errorp">Ошибка! Вы уже начали выполнять это задание!</fieldset>';
		}else{
			if($row["zdre"]>0) {
				$ost = ($row_p["date_end"] + ($row["zdre"] * 60 * 60) );
				$ost = ( $ost - time() );

				if($ost > 0){
					$d = floor($ost/86400);
					$h = floor( ($ost - ($d * 86400)) / 3600);
					$i = floor( ($ost - ($d * 86400) - ($h * 3600)) / 60 );
					$s = floor($ost - ($d * 86400) - ($h * 3600) - ($i * 60));

					if($d==0)
						$dn="дней";
					elseif($d==1)
						$dn="день";
					elseif($d==2)
						$dn="дня";
					elseif($d==3)
						$dn="дня";
					else{ $dn=""; }

					if($h>=0) {$hn="часов";}else{$hn="";}
					if(($h>1 && $h<5) | $h>20) {$hn="часа";}
					if($h==1 | $h==21) {$hn="час";}

					if($i>0) {$mn="минуты";}else{$mn="";}
					if($i==1 | $i==21 | $i==31 | $i==41 | $i==51) {$mn="минуту";}
					if( ($i<21 && $i>4) | $i==0 | $i===20 | $i==30 | $i==40 | $i==50 | ($i>24 && $i<30) | ($i>34 && $i<40) | ($i>44 && $i<50) | ($i>54 && $i<60)) {$mn="минут";}

					if($s>0) {$sn="секунды";}else{$sn="";}
					if($s==1 | $s==21 | $s==31 | $s==41 | $s==51) {$sn="секунду";}
					if( ($s<21 && $s>4) | $s==0 | $s===20 | $s==30 | $s==40 | $s==50 | ($s>24 && $s<30) | ($s>34 && $s<40) | ($s>44 && $s<50) | ($s>54 && $s<60)) {$sn="секунд";}

					if($s>0) {$date_next = "<b>$s</b> $sn";}
					if($i>0) {$date_next = "<b>$i</b> $mn <b>$s</b> $sn";}
					if($h>0) {$date_next = "<b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
					if($d>0) {$date_next = "<b>$d</b> $dn <b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
					if($h==0 && $i==0 && $s==0) {$date_next = "<b>$d</b> $dn";}

					echo '<fieldset class="errorp">Вы уже выполнили это задание. Повторное выполнение этого задания будет возможно через '.$date_next.'</fieldset>';
				}else{
					mysql_query("INSERT INTO `tb_ads_task_pay` (`status`,`type`,`re`,`rek_name`,`rek_id`,`user_name`,`user_id`,`ident`,`pay`,`date_start`,`date_end`,`ip`) VALUES('','task','$zdre','$rek_name','$rek_id','$username','$partnerid','$rid','$zdprice','".time()."','".time()."','$ip')") or die(mysql_error());
					mysql_query("UPDATE `tb_ads_task` SET `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());

					echo '<script type="text/javascript">location.replace("'.str_replace("&amp;","&", $zdurl).'");</script>';
					echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.str_replace("&amp;","&", $zdurl).'"></noscript>';
				}
			}else{
			}
		}
	}else{
		mysql_query("INSERT INTO `tb_ads_task_pay` (`status`,`type`,`re`,`rek_name`,`rek_id`,`user_name`,`user_id`,`ident`,`pay`,`date_start`,`date_end`,`ip`) VALUES('','task','$zdre','$rek_name','$rek_id','$username','$partnerid','$rid','$zdprice','".time()."','".time()."','$ip')") or die(mysql_error());

		$sql_stat = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='click' AND `user_name`='$username' AND `ident`='$rid' AND `date`='".DATE("d.m.Y")."'");
		if(mysql_num_rows($sql_stat)<1) {
			mysql_query("INSERT INTO `tb_ads_task_stat` (`type`,`rek_name`,`user_name`,`ident`,`date`) VALUES('click','$rek_name','$username','$rid','".DATE("d.m.Y")."')") or die(mysql_error());
			mysql_query("UPDATE `tb_ads_task` SET `clicks`=`clicks`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
		}

		echo '<script type="text/javascript">location.replace("'.str_replace("&amp;","&", $zdurl).'");</script>';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.str_replace("&amp;","&", $zdurl).'"></noscript>';
	}
}else{
	echo '<fieldset class="errorp">Ошибка! Такого задания нет, либо оно не активно!&nbsp;&nbsp;&nbsp;</fieldset>';
	include('footer.php');
	exit();
}
?>