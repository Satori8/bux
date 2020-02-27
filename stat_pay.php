<?php
$pagetitle="Статистика выплат";
include('header.php');
require("navigator/navigator.php");
require('config.php');

$sql = mysql_query("SELECT `id` FROM `tb_history` WHERE `status_pay`='1' AND `tipo`='0' AND `time`>'".(time()-1*24*60*60)."' ORDER BY `time` DESC");
$all_pay = mysql_num_rows($sql);

$sql = mysql_query("SELECT sum(amount) FROM `tb_history` WHERE `status_pay`='1' AND `tipo`='0' AND `time`>'".(time()-1*24*60*60)."' ORDER BY `time` DESC");
$sum_pay = mysql_result($sql,0,0);

$week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
		$week_arr_ru = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
		$week_ru = array();
		$week_en = array();
		$date_s = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
		$date_e = strtotime(DATE("d.m.Y"));
		$period = (24*60*60);
		$color_arr = array('WebMoney'=>'#00649E', 'YandexMoney'=>'#DE1200', 'Payeer'=>'#3498DB', 'Qiwi'=>'#FC8000', 'AdvCash'=>'#049C64', 'PerfectMoney'=>'#DE1200', 'MTS'=>'#ff0000', 'Beeline'=>'#e0b806', 'Megaphone'=>'#00649e', 'TELE2'=>'#0c0c0c', 'MAESTRO'=>'#ff0000', 'MasterCard'=>'#ff9b00', 'VISA'=>'#1c0cf5');
		$types_arr = array('WebMoney'=>'WebMoney', 'YandexMoney'=>'Яндекс.Деньги', 'Payeer'=>'Payeer', 'Qiwi'=>'Qiwi', 'AdvCash'=>'AdvCash', 'PerfectMoney'=>'PerfectMoney', 'MTS'=>'MTS', 'Beeline'=>'Билайн', 'Megaphone'=>'Мегафон', 'TELE2'=>'TELE2', 'MAESTRO'=>'Maestro', 'MasterCard'=>'MasterCard', 'VISA'=>'VISA');
		$tab_in_arr = array_keys($types_arr);
		$tab_in = implode("', '", $tab_in_arr);
		for($i=$date_s; $i<=$date_e; $i=$i+$period) {
			$week_ru[] = $week_arr_ru[strtolower(DATE("w", $i))];
			$week_en[] = $week_arr_en[strtolower(DATE("w", $i))];
		}
		$data_graff = array();
		 
			
	for($i=0; $i<count($tab_in_arr); $i++) {
				
				$StatUser[$tab_in_arr[$i]] = mysql_fetch_assoc(mysql_query("SELECT * FROM `tb_pay_stat` WHERE `type`='".$tab_in_arr[$i]."'"));
				
				 
		for($y=0; $y<count($week_en); $y++) {
		
					$data_graff[$tab_in_arr[$i]][] = isset($StatUser[$tab_in_arr[$i]][$week_en[$y]]) ? $StatUser[$tab_in_arr[$i]][$week_en[$y]] : 0;
				}
				$data_graff[$tab_in_arr[$i]] = implode(", ", $data_graff[$tab_in_arr[$i]]);
				
			}
			foreach($data_graff as $key=>$val) {
				$series_arr[] = "{name: '".mb_convert_case($types_arr[$key], MB_CASE_TITLE, "CP1251")."', data: [$val], color: '".$color_arr[$key]."'}";
			}
			$categories = isset($week_ru) ? "'".implode("', '", $week_ru)."'" : false;
			$series = isset($series_arr) ? implode(", ", $series_arr) : false;
		
			
echo '<h3 class="sp">График выплат за неделю на платежные системы</h3>';
			echo '<div style="min-width:300px; max-width:712px; height:300px; margin:0 auto 20px;" id="Pay"></div>';
			echo '<script type="text/javascript" src="/js/highcharts.js"></script>';
			echo "<script type=\"text/javascript\">
Highcharts.chart(\"Pay\", {
				chart: 	{ backgroundColor: '#F7F7F7', type: 'column', width: 712, height: 300 }, 
	credits: { enabled: false }, title: { text: '' }, subtitle: { text: '' }, 
	xAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, categories: [$categories] }, 
	yAxis: { tickWidth: 1, gridLineWidth: 1, allowDecimals:false, title: { text: '' } }, 
	legend: { /*enabled: false*/ layout: 'vertical', align: 'right', verticalAlign: 'middle' }, 
series: [$series]
			});

</script>";

echo '<div align="center" style="font-size:14px; color:#666;">';
	echo 'Всего выплачено за 24 часа: <b>'.number_format($sum_pay,2,".","'").'</b> руб.<br>';
	echo 'Всего выплат за 24 часа: <b>'.number_format($all_pay,0,".","'").'</b> шт.<br><br>';
echo '</div>';

$perpage = 30;
$count = $all_pay;
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

echo '<table class="tables">';
echo '<thead>';
	echo '<tr align="center">';
                echo '<th class="top" colspan="2">Пользователь</th>';
		echo '<th class="top">Дата и время</th>';
		echo '<th class="top">Сумма, руб</th>';
		echo '<th class="top">Выплачено</th>';
	echo '</tr>';
echo '</thead>';

$sql = mysql_query("SELECT `user`,`user_id`,`amount`,`time`,`method` FROM `tb_history` WHERE `status_pay`='1' AND `tipo`='0' AND `time`>'".(time()-1*24*60*60)."' ORDER BY `time` DESC LIMIT $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row = mysql_fetch_array($sql)) {
$us=$row["user_id"];
	$r1 = mysql_fetch_array(mysql_query("SELECT `avatar` FROM tb_users WHERE id=$us"));
	
                   echo '<tr align="center">';
                         echo '<td class="left-ref" width="60px" style="border-right:none;"><img class="avatar" src="/avatar/'.$r1["avatar"].'" style="width:60px; height:60px" border="0" alt="avatar" title="avatar" /></td>';
                        echo '<td style="border-left:none;" align="left">ID: '.$row["user_id"].'<br><a href="/wall.php?uid='.$row["user_id"].'"><b>'.$row["user"].'</b></a></td>';
			echo '<td>';
				if(DATE("d.m.Y",$row["time"])==DATE("d.m.Y",time())) {
					echo DATE("Сегодня, H:i",$row["time"]);
				}else{
					echo DATE("d.m.Y H:i",$row["time"]);
				}
           
			echo '</td>';
			echo '<td align="left" style="padding:3px 3px 4px 25px;"><img src="/img/wmr.ico" width="16" height="16" style="margin:0px; padding:2px 5px;" align="absmiddle">'.number_format($row["amount"],2,".","'").'</td>';
			echo '<td align="left" style="padding:3px 3px 4px 25px;">';
				if($row["method"]=="WebMoney") {
					echo '<img src="/img/16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><span style="color:#0084D1">WebMoney</span>';
				}elseif($row["method"]=="Qiwi") {
					echo '<img src="/img/qiwi16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#ff9933"><span style="color:#FC8000;">QIWI кошелек</span></span>';
				}elseif($row["method"]=="AdvCash") {
                    echo '<img src="/img/advcash_18x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span>';
                }elseif($row["method"]=="Payeer") {
					echo '<img src="/img/payeer.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span>';
				}elseif($row["method"]=="YandexMoney") {
					echo '<img src="/img/ym.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><span style="color:#DE1200">Я</span>ндекс.Деньги';
                }elseif($row["method"]=="PerfectMoney") {
					echo '<img src="/img/pm.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle"><span style="color:#DE1200">PerfectMoney</span>';
                }elseif($row["method"]=="MTS") {
					echo '<img src="/img/mt16x16.png" width="20px" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#ff0000; font-weight:bold;">МТС</span>';
                }elseif($row["method"]=="Beeline") {
					echo '<img src="/img/be16x16.png" width="20px" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span>';
                }elseif($row["method"]=="Megaphone") {
					echo '<img src="/img/mg16x16.png" width="20px" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span>';
                }elseif($row["method"]=="TELE2") {
					echo '<img src="/img/tl16x16.png" width="20px" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#0c0c0c">TELE2</span>';
				}elseif($row["method"]=="MAESTRO") {
					echo '<img src="/img/me16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span>';
				}elseif($row["method"]=="VISA") {
					echo '<img src="/img/vs16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#1c0cf5">VISA</span>';
				}elseif($row["method"]=="MasterCard") {
					echo '<img src="/img/ms16x16.png" alt="" style="margin:0px; padding:2px 5px;" align="absmiddle" /><span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span>';
				}else{
					echo $row["method"];
				}
			echo '</td>';
		echo '</tr>';
	}
}else{
	echo '<tr align="center"><td colspan="5">Данных нет</td></tr>';
}
echo '</table>';
if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '?page=', "");

include('footer.php');?>