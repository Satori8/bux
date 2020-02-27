<table border="0" align="center" width="100%" align="center">
<tr align="center">
	<td valign="top">
		<div id="avatar1"><a href="/profile.php" title="сменить аватар"><img src="avatar/<?php echo $avatar;?>" class="avatar" width="80" height="80" border="0" title="сменить аватар" align="middle" alt="" /></a>
		<div style="margin-bottom:3px;margin-top:5px;color:#663300;font-size:14px"<h id='place'></h></div></div>
	</td>
	<td valign="top">
		<a href="/status.php" class="user-status-<?=$my_id_rang;?>" title="Рейтинг: <?=p_floor($reiting, 2);?>"><?=p_floor($reiting, 0);?></a>
		<a href="/status.php"><b><?=$my_rang;?></b></a>
		<div style="padding: 8px 0px;"><a class="bg-wall" href="/wall?uid=<?=$partnerid;?>" target="_blank" title="Стена пользователя <?=$username;?>"><?=$wall_my_com;?></a></div>
	</td>
</tr>
<script type='text/javascript'>
<!--
var h=(new Date()).getHours();
if (h > 23 || h < 5) document.getElementById('place').innerHTML='Доброй ночи!';
if (h > 4 && h < 12) document.getElementById('place').innerHTML='Доброе утро!';
if (h > 11 && h < 18) document.getElementById('place').innerHTML='Добрый день!';
if (h > 17 && h < 24) document.getElementById('place').innerHTML='Добрый вечер!';
//-->
</script>
<?php
echo '<table class="tables" border="0" align="center">';
if(DATE("d.m.Y")!=DATE("d.m.Y", $reit_act_usr)) {
	$my_code = "2205837948374";
	$my_code_md = md5($username.$password.$my_code);
	echo '<td valign="top"><span id="rtpls_msg" class="reit_plus" style="margin:0 auto; float:none;" title="Бонус за активность + '.$reit_active.'" onclick="javascript:reiting_plus(\''.$my_code_md.'\', \''.$reit_active.'\');"></span></td>';
}
/*if(DATE("d.m.Y")!=DATE("d.m.Y", $bonus_act_usr)) {
	$my_code = "2205837948374";
	$my_code_md = md5($username.$password.$my_code);
	 if($bonus>0) echo '<td valign="top"><span id="bonus_msg" class="bonus_plus" style="margin:0 auto; float:none;" title="Денежный бонус за активность + '.$bonus.' руб." onclick="javascript:money_plus(\''.$my_code_md.'\', \''.$bonus.'\');"></span></td>'; 
}*/
        echo '</table>';
?>
<div align="center" style="height:20px;margin-top:4px;width:calc(100% - 2px);">
	<div style="color:#ab0606;font-size:12px;font-weight:bold;float:left;">&nbsp;IP-адрес:</div>
	<div class="text-red" style="font-weight:bold;float:right;"><?=$ip;?></div>
</div>
<table class="tables" colspan="3" border="0" style="width:100%">
	<tr><td align="left" style="border-left:none; border-right:none;">Основной счет:</td><td align="right" style="border-left:none; border-right:none;"><b id="my_bal_os" style="color:#FF0000;"><?php echo p_floor($money_users,4,'.','`');?></b></td></tr>
	<tr><td align="left" style="border-left:none; border-right:none;">Рекламный счет:</td><td align="right" style="border-left:none; border-right:none;"><b id="my_bal_rs" style="color:#FF0000;"><?php echo p_floor($money_rb,4,'.','`');?></b></td></tr>
</table>



<?php
$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
$bon_popoln =  mysql_result($sql,0);

echo '<div style="margin:0 auto;text-align:center;/* color:#fff; */padding:2px;/* border:2px solid #EfEfEf; *//* border-radius:7px; *//* background:#4A994A; *//* box-shadow:0 1px 6px rgba(0,0,0,.7); */">';
	echo '<span class="proc-btn" onClick="document.location.href=\'/cash_out\'">Вывести средства</span>';
	if($bon_popoln > 0) {
		echo '<span class="proc-btn-t" onClick="document.location.href=\'/cash_in\'">Пополнить счет</span>';
		echo '<span class="proc-btn-n" onClick="document.location.href=\'/cash_in\'">+'.number_format($bon_popoln, 0, ".", "").' %</span>';
	}else{
		echo '<span class="proc-btn" onClick="document.location.href=\'/cash_in\'">Пополнить счет</span>';
	}
echo '</div>';

echo '<table class="tables" border="0" align="center" style="width:100%; margin:0 auto;" id="newform">';
 ////////////////////
				$sql_bon_pay_status = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_pay_status'");
				$bon_pay_status = (mysql_num_rows($sql_bon_pay_status)>0 && mysql_result($sql_bon_pay_status,0,0)>0 ) ? mysql_result($sql_bon_pay_status,0,0) : 0;
				
				$sql_bon_pay_summa = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_pay_summa'");
				$bon_pay_summa = (mysql_num_rows($sql_bon_pay_summa)>0 && mysql_result($sql_bon_pay_summa,0,0)>0 ) ? mysql_result($sql_bon_pay_summa,0,0) : 0;
				
				$sql_bon_pay_money = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_pay_money'");
				$bon_pay_money = (mysql_num_rows($sql_bon_pay_money)>0 && mysql_result($sql_bon_pay_money,0,0)>0 ) ? mysql_result($sql_bon_pay_money,0,0) : 0;
				
				$sql_bon_pay_reiting = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_pay_reiting'");
				$bon_pay_reiting = (mysql_num_rows($sql_bon_pay_reiting)>0 && mysql_result($sql_bon_pay_reiting,0,0)>0 ) ? mysql_result($sql_bon_pay_reiting,0,0) : 0;
			/////////////////////
			

 if($bon_pay_status==1){
	echo '<div style="margin:7px auto; margin-bottom:10px; background:#F0F8FF; padding:8px; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4); text-align:left; color:black;">';
	echo '<font color="red"><center><b>Бонус!</b></center></font>';
	echo 'Пополни счет на '.$bon_pay_summa.' руб. одним платежом и получай бонус:<br> <font color="red"><b>&bull; +'.$bon_pay_money.' рублей!<br>&bull; +'.$bon_pay_reiting.' рейтинга!</b></font> <br>Бонус будет начислен сразу после пополнения автоматически.';


	echo '</div>';
}
?>
</table>
<table class="tables" border="0" align="center" style="width:100%; margin:0 auto;" id="newform">
<tr align="center">
<td valign="top" style="border-left:none; border-right:none;">
<div align="center"><span style="padding: 3px 3px; margin: 3px auto; display: block;"> Дата и время на сайте </span>
<div align="center"><strong><span id="seconds"><?echo date( 'Y-г m-м d-ч ', time() );?></span></strong>
				<script language="JavaScript">
                   var hours = <?php echo date("H"); ?>;
                    var min = <?php echo date("i"); ?>;
                    var sec = <?php echo date("s"); ?>;
                   	function display() {
                   	sec+=1;
                   	if (sec>=60){
					   min+=1;
					   sec=0;
                   	}
                   	if (min>=60) {
					   hours+=1;
					   min=0;
                   	}
                   	if (hours>=24) hours=0;
                   
                   	if (sec<10) {
                   		sec2display = "0"+sec;
				   	}else{
                   		sec2display = sec;
				   	}
                    
                    if (min<10){
                    	min2display = "0"+min;
					}else{
                    	min2display = min;
					}
					
                    if (hours<10){
                    	hour2display = "0"+hours;
					}else{
                   		hour2display = hours;
					}
					
                    document.getElementById("seconds").innerHTML = hour2display+":"+min2display+":"+sec2display;
                    setTimeout("display();", 1000);
                    }
                    
					display();
				</script>
			</div>

<?php
function rus_date() {
    $translate = array(
    "am" => "дп",
    "pm" => "пп",
    "AM" => "ДП",
    "PM" => "ПП",
    "Monday" => "Понедельник",
    "Mon" => "Пн",
    "Tuesday" => "Вторник",
    "Tue" => "Вт",
    "Wednesday" => "Среда",
    "Wed" => "Ср",
    "Thursday" => "Четверг",
    "Thu" => "Чт",
    "Friday" => "Пятница",
    "Fri" => "Пт",
    "Saturday" => "Суббота",
    "Sat" => "Сб",
    "Sunday" => "Воскресенье",
    "Sun" => "Вс",
    "January" => "Января",
    "Jan" => "Янв",
    "February" => "Февраля",
    "Feb" => "Фев",
    "March" => "Марта",
    "Mar" => "Мар",
    "April" => "Апреля",
    "Apr" => "Апр",
    "May" => "Мая",
    "May" => "Мая",
    "June" => "Июня",
    "Jun" => "Июн",
    "July" => "Июля",
    "Jul" => "Июл",
    "August" => "Августа",
    "Aug" => "Авг",
    "September" => "Сентября",
    "Sep" => "Сен",
    "October" => "Октября",
    "Oct" => "Окт",
    "November" => "Ноября",
    "Nov" => "Ноя",
    "December" => "Декабря",
    "Dec" => "Дек",
    "st" => "ое",
    "nd" => "ое",
    "rd" => "е",
    "th" => "ое"
    );
    
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
} 
 
echo " ". rus_date("l j F Y")." "; 
?>
</table>