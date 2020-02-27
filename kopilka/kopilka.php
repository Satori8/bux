<?php
if (!DEFINED("KOPILKA")) {die ("Hacking attempt!");}
echo '<script type="text/javascript" src="kopilka/kopilka.js?v=1.04"></script>';

$kopilka_summa_in = mysql_result(mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='kopilka_summa_in'"),0,0);
$kopilka_summa_in = p_floor($kopilka_summa_in,2);
$kopilka_summa_in = number_format($kopilka_summa_in, 2, ".", "");

$kopilka_summa_out = mysql_result(mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='kopilka_summa_out'"),0,0);
$kopilka_summa_out = p_floor($kopilka_summa_out,2);
$kopilka_summa_out = number_format($kopilka_summa_out, 2, ".", "");

$kopilka_count_out = mysql_result(mysql_query("SELECT `howmany` FROM `tb_config` WHERE `item`='kopilka_summa_out'"),0,0);
$kopilka_count_out = number_format($kopilka_count_out,0,".","'");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='kopilka_cash_out'");
$kopilka_cash_out = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='kopilka_period_out'");
$kopilka_period_out = mysql_result($sql,0,0);

$sql_check_time = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_kopilka_out` WHERE `time`>='".(time()-$kopilka_period_out*60*60)."' AND `username`='$username' ORDER BY `id` DESC LIMIT 1"));

function IN_KOP($summa) {
	switch(strlen($summa)) {
		case(1): $summa = "000000000".$summa; break;
		case(2): $summa = "00000000".$summa; break;
		case(3): $summa = "0000000".$summa; break;
		case(4): $summa = "000000".$summa; break;
		case(5): $summa = "00000".$summa; break;
		case(6): $summa = "0000".$summa; break;
		case(7): $summa = "000".$summa; break;
		case(8): $summa = "00".$summa; break;
		case(9): $summa = "0".$summa; break;
		case(10): $summa = $summa; break;
	}
	return $summa;
}


echo '<table align="center" class="tables" style="margin:0 auto; width:auto;">';
echo '<tr align="center">';
	echo '<td align="center" class="kopilka">';
		echo '<img src="kopilka/img/kopilka.gif" alt="" title="" border="0" style="margin:0; padding:0;" /><br><br>';
		echo '<div style="letter-spacing: 2px; font-size:16px; font-weight:bold; color:#0000FF; text-shadow: 1px 1px 1px #FFF;">';
			echo '<div style="float:right;"><img src="kopilka/img/money_in.png" alt="" title="В копилке" border="0" style="margin:0; padding:0;" />&nbsp;';
			echo '<div style="float:right;" id="kopilka_summa_in">'.IN_KOP($kopilka_summa_in).'</div>';
		echo '</div>';
		echo '<div style="letter-spacing: 2px; font-size:16px; font-weight:bold; color:#0000FF; text-shadow: 1px 1px 1px #FFF;">';
			echo '<div style="float:right;"><img src="kopilka/img/money_out.png" alt="" title="Раздали, количество бонусов: '.$kopilka_count_out.'" border="0" style="margin:0; padding:0;" />&nbsp;';
			echo '<div style="float:right;" id="kopilka_summa_out">'.IN_KOP($kopilka_summa_out).'</div>';
		echo '</div>';
	echo '</td>';
echo '</tr>';
echo '</table>';

echo '<table align="center" class="tables" style="margin:0 auto; width:auto;">';
echo '<tr align="center">';
	echo '<td align="center" class="kopilka">';
		echo '<div id="add_form_kop" style="display:none;">';
			echo '<form name="kopilka_form" onsubmit="return false;" id="newform">';
				echo '<table class="tables" align="center">';
					echo '<tr align="center"><td class="kopilka">Сумма:<br><input type="text" name="moneyadd" value="" class="ok" style="width:97%; text-align:center;" /></td></tr>';
					echo '<tr align="center"><td class="kopilka">Комментарий:<br><textarea name="comment" class="ok" style="width:95%"></textarea></td></tr>';
				
					echo '<tr><td align="center" class="kopilka">';
						echo '<table class="tables" style="margin:0 auto; width:auto;">';
							echo '<tr><td align="right" class="kopilka"><input type="radio" id="radio1" name="methodadd" value="1"></td><td class="kopilka">с основного счета</td></tr>';
							echo '<tr><td align="right" class="kopilka"><input type="radio" id="radio2" name="methodadd" value="1"></td><td class="kopilka">с рекламного счета</td></tr>';
						echo '</table>';
					echo '</td></tr>';
				echo '</table>';
				echo '<span id="info-msg-kop"></span>';
				echo '<a class="kop_add" onclick="javascript:money_add_kopilka();">Пополнить копилку</a>';
			echo '</form>';
		echo '</div>';
	echo '</td>';
echo '</tr>';
echo '</table>';

echo '<table align="center" class="tables" style="width:100%;">';
echo '<tr align="center">';
	echo '<td align="center" class="kopilka" style="width:100%;"><div id="sub_kop" style="display:block;"><a class="kop_add" onClick="javascript:ShowForm();">Пополнить копилку</a></div></td>';
echo '</tr>';
echo '</table>';

echo '<table align="center" class="tables">';
echo '<tr align="center">';
	if($sql_check_time > 0 ) {
		echo '<td align="center" width="50%" class="kopilka"><span class="kop_list" onClick="ViewMoneyIn();">Пополнили</span></td>';
		echo '<td align="center" width="50%" class="kopilka"><span class="kop_list" onClick="InfoKop();">Что это?</span></td>';
	}else{
		echo '<td align="center" width="47%" class="kopilka"><span class="kop_list" onClick="ViewMoneyIn();">Пополнили</span></td>';
		if($kopilka_summa_in>=$kopilka_cash_out) {
			echo '<td align="center" width="6%" class="kopilka"><span class="kop_plus" title="Получить копеечку" onClick="money_plus_us();"><b>+</b></span></td>';
		}else{
			echo '<td align="center" width="6%" class="kopilka"><span class="kop_plus_no" title="В копилке не достаточно средств для получения бонуса"><b>+</b></span></td>';
		}
		echo '<td align="center" width="47%" class="kopilka"><span class="kop_list" onClick="InfoKop();">Что это?</span></td>';
	}
echo '</tr>';
echo '</table>';

?>