<?php
require(ROOT_DIR."/auto_pay_req/wmxml.inc.php");
require(ROOT_DIR."/merchant/payeer/cpayeer.php");
require(ROOT_DIR."/merchant/payeer/payeer_config.php");
require(ROOT_DIR."/merchant/yandexmoney/ym_config.php");
require(ROOT_DIR."/merchant/yandexmoney/ym_outresult.php");
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ����������</b></h3>';

function getDatesByWeek($_week_number, $_year = null) {
        $year = $_year ? $_year : date('Y');
        $week_number = sprintf('%02d', $_week_number);
        $date_base = strtotime($year . 'W' . $week_number . '1 00:00:00');
        $date_limit = strtotime($year . 'W' . $week_number . '7 23:59:59');
        return array($date_base, $date_limit);
}

$type_ads_arr = array(
	'dlink' => '�������', 
	'youtube' => '������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>', 
	'bonus_pay' => '����� ����', 
	'autoserf' => '����-�������', 
	'autoserfyou' => '����-������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>', 
	'pay_visits' => '������������ ���������',
	'mails' => '������', 
	'kontext' => '����������� �������', 
	'statlink' => '����������� ������', 
	'banners' => '�������', 
	'txtob' => '��������� ����������', 
	'frmlink' => '������ �� ������', 
	'psevdo' => '������ ������', 
	'rekcep' => '��������� �������', 
	'task' => '�������', 
	'tests' => '������������ �����', 
	'statkat' => '������� ������', 
	'articles' => '������� ������', 
	'bstroka' => '������� ������', 
	'pay_row' => '������� ������',
	'pay_mes' => '������� ���������',
	'sent_emails' => '�������� �� �-mail', 
	'ref_birj' => '����� ���������',
	'auc' => '�������',
	'board' => '����� ������',
	'mail_user' => '�������� �������������',
	'ref_birj' => '����� ���������',
	'gifts_pay' => '�������',
	'ref_wall' => '���-�����',
	'packet' => '������ �������',
	'money_in' => '���������� ����. �������', 
	'money_invest' => '���������� ������� ���������'
);
$type_ads_key_arr = array_keys($type_ads_arr);
$type_ads_key_arr = implode("','", $type_ads_key_arr);

$time_week_start = getDatesByWeek(DATE("W"));

$day_week_arr_en = array("sun","mon","tue","wed","thu","fri","sat");
$day_week_arr_ru = array("�����������","�����������","�������","�����","�������","�������","�������");
$day_month_arr_ru = array("","������","�������","����","������","���","����","����","������","��������","�������","������","�������");

$date_start = strtotime(DATE("d.m.Y", (time()-6*24*60*60)));
$date_end = strtotime(DATE("d.m.Y"));
$period = (24*60*60);

echo '<table class="tables" style="margin-top:1px;">';
echo '<thead><tr>';
	echo '<th></th>';
	echo '<th style="text-transform:uppercase;">�����</th>';
	echo '<th>�� ���<br>'.DATE("Y").'</th>';
	echo '<th>�� �����<br>'.$day_month_arr_ru[DATE("n", time())].''.DATE(" Y").'</th>';
	echo '<th>�� ������</th>';
	for($i=$date_start; $i<=$date_end; $i=$i+$period) {
		if(DATE("w", $i)==DATE("w")) echo '<th style="width:8%; background: green; text-transform:uppercase;">�������, '.DATE("d.m.Y", $i).'</th>';
		else echo '<th style="width:8%; text-transform:uppercase;">'.$day_week_arr_ru[strtolower(DATE("w", $i))].'<br>'.DATE("d.m.Y", $i).'</th>';
	}
	echo '</tr>';
echo '</thead>';

$ITOGO_ADS["mon"] = 0;
$ITOGO_ADS["tue"] = 0;
$ITOGO_ADS["wed"] = 0;
$ITOGO_ADS["thu"] = 0;
$ITOGO_ADS["fri"] = 0;
$ITOGO_ADS["sat"] = 0;
$ITOGO_ADS["sun"] = 0;
$ITOGO_ADS["all"] = 0;
$ITOGO_ADS["year"] = 0;
$ITOGO_ADS["month"] = 0;
$ITOGO_ADS["week"] = 0;

$sql_stat = mysql_query("SELECT * FROM `tb_ads_stat` WHERE `type` IN ('$type_ads_key_arr') ORDER BY `id` ASC");
if(mysql_num_rows($sql_stat)>0) {

	while ($row_stat = mysql_fetch_array($sql_stat)) {

		$ITOGO_ADS["all"] += $row_stat["sum_all"];
		$ITOGO_ADS["year"] += $row_stat["sum_year"];
		$ITOGO_ADS["month"] += $row_stat["sum_month"];
		$ITOGO_ADS["week"] += $row_stat["sum_week"];

		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background-color:#FFFACD; height:22px;"><b style="color: #27408B; font-size:12px /*text-transform:uppercase;*/">'.$type_ads_arr[$row_stat["type"]].'</b></td>';
			echo '<td align="right" style="background-color:#D1EEEE; height:22px;"><b style="color:black; padding-right:5px">'.($row_stat["sum_all"]==0 ? "-" : number_format($row_stat["sum_all"],2,".","`")).'</b></td>';
			echo '<td align="right" style="background-color:#D1EEEE; height:22px;"><b style="color:black; padding-right:5px">'.($row_stat["sum_year"]==0 ? "-" : number_format($row_stat["sum_year"],2,".","`")).'</b></td>';
			echo '<td align="right" style="background-color:#D1EEEE; height:22px;"><b style="color:black; padding-right:5px">'.($row_stat["sum_month"]==0 ? "-" : number_format($row_stat["sum_month"],2,".","`")).'</b></td>';
			echo '<td align="right" style="background-color:#D1EEEE; height:22px;"><b style="color:black; padding-right:5px">'.($row_stat["sum_week"]==0 ? "-" : number_format($row_stat["sum_week"],2,".","`")).'</b></td>';

			for($i=$date_start; $i<=$date_end; $i=$i+$period) {
				if(DATE("w", $i)==DATE("w")) echo '<td align="right" style="background-color:#E8E8E8; height:22px;"><b style="color:green; padding-right:5px">'.($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]==0 ? "-" : number_format($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]],2,".","`")).'</b></td>';
				else echo '<td align="right" style="height:22px;"><b style="padding-right:5px">'.($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]]==0 ? "-" : number_format($row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]],2,".","`")).'</b></td>';

				$ITOGO_ADS[$day_week_arr_en[strtolower(DATE("w", $i))]] += $row_stat[$day_week_arr_en[strtolower(DATE("w", $i))]];
			}
		echo '</tr>';

		if($row_stat["type"]=="packet") {
			echo '<tr>';
				echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:12px">������� �� �����:</b>&nbsp;&nbsp;</td>';
				echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:14px">'.($ITOGO_ADS["all"]==0 ? "-" : number_format($ITOGO_ADS["all"],2,".","`")).'</b></td>';
				echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:14px">'.($ITOGO_ADS["year"]==0 ? "-" : number_format($ITOGO_ADS["year"],2,".","`")).'</b></td>';
				echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:14px">'.($ITOGO_ADS["month"]==0 ? "-" : number_format($ITOGO_ADS["month"],2,".","`")).'</b></td>';
				echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:14px">'.($ITOGO_ADS["week"]==0 ? "-" : number_format($ITOGO_ADS["week"],2,".","`")).'</b></td>';

				for($i=$date_start; $i<=$date_end; $i=$i+$period) {
					echo '<td align="right" style="background-color:#CDCDB4; height:22px;"><b style="color: #A52A2A; font-size:14px">'.number_format($ITOGO_ADS[$day_week_arr_en[strtolower(DATE("w", $i))]],2,".","`").'</b></td>';
				}
			echo '</tr>';
		}
	}

}
echo '</table>';
$balance_wmr_purse = 0;
$balance_payeer_purse = 0;
$balance_ym_purse = 0;

$WM_X9 = _WMXML9();
$wmr_purses = isset($WM_X9["purses"]) ? $WM_X9["purses"] : array();

foreach($wmr_purses as $key => $val) {
	if(preg_match("|^[R]{1}[\d]{12}$|", trim($key))) $balance_wmr_purse+=$val;
}

$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
$balance_payeer_arr = $payeer->isAuth() ? $payeer->getBalance() : false;
$balance_payeer_purse = isset($balance_payeer_arr["balance"]["RUB"]["DOSTUPNO"]) ? $balance_payeer_arr["balance"]["RUB"]["DOSTUPNO"] : 0;

$YM_API = new ymAPI(CLIENT_ID, REDIRECT_URL);
$YM_INFO = $YM_API->accountInfo(TOKEN_YM);
$balance_ym_purse = isset($YM_INFO["balance"]) ? $YM_INFO["balance"] : 0;

echo '<h3 class="sp" style="margin-top:20px; padding-top:0;"><b>������ ��������� �������</b></h3>';
echo '<table class="tables" style="margin:0 auto;">';
echo '<tbody>';
	echo '<tr>';
		echo '<td align="left"><b>������ WMR ���������</b></td>';
		echo '<td align="right" width="100px"><span class="text-green"><b style="font-size:14px;">'.number_format($balance_wmr_purse, 2, ".", " ").'</b> ���.</span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>������ Payeer ��������</b></td>';
		echo '<td align="right"><span class="text-green"><b style="font-size:14px;">'.number_format($balance_payeer_purse, 2, ".", " ").'</b> ���.</span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>������ ������ ��������</b></td>';
		echo '<td align="right"><span class="text-green"><b style="font-size:14px;">'.number_format($balance_ym_purse, 2, ".", " ").'</b> ���.</span></td>';
	echo '</tr>';
echo '</tbody>';
echo '</table>';

?>