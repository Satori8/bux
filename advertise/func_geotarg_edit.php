<?php
$geo_codname_arr = array(
	1  => array('RU', '������'),
	2  => array('UA', '�������'),
	3  => array('BY', '����������'),
	4  => array('MD', '��������'),
	5  => array('KZ', '���������'),
	6  => array('AM', '�������'),
	7  => array('UZ', '����������'),
	8  => array('LV', '������'),
	9  => array('DE', '��������'),
	10 => array('GE', '������'),
	11 => array('LT', '�����'),
	12 => array('FR', '�������'),
	13 => array('AZ', '�����������'),
	14 => array('US', '���'),
	15 => array('VN', '�������'),
	16 => array('PT', '����������'),
	17 => array('GB', '������'),
	18 => array('BE', '�������'),
	19 => array('ES', '�������'),
	20 => array('CN', '�����'),
	21 => array('TJ', '�����������'),
	22 => array('EE', '�������'),
	23 => array('IT', '������'),
	24 => array('KG', '��������'),
	25 => array('IL', '�������'),
	26 => array('CA', '������'),
	27 => array('TM', '������������'),
	28 => array('BG', '��������'),
	29 => array('IR', '����'),
	30 => array('GR', '������'),
	31 => array('TR', '������'),
	32 => array('PL', '������'),
	33 => array('FI', '���������'),
	34 => array('EG', '������'),
	35 => array('SE', '������'),
	36 => array('RO', '�������'),
);

//echo '<table width="98%">';
for ($i = 1; $i <= count($geo_codname_arr); $i++) {
	if($i==1 | $i==5 | $i==9 | $i==13 | $i==17 | $i==21 | $i==25 | $i==29 | $i==33) echo '<tr>';
		echo '<td width="25%">';
			echo '<input type="checkbox" id="country[]" name="country[]" value="'.$geo_codname_arr[$i]["0"].'" '.(strpos(strtoupper($row["geo_targ"]), strtoupper($geo_codname_arr[$i]["0"])) !== false ? 'checked="checked"' : false).' />';
			echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($geo_codname_arr[$i]["0"]).'.gif" alt="" align="absmiddle" style="margin:0; padding:0;" />&nbsp;'.$geo_codname_arr[$i]["1"].'';
		echo '</td>';
	if($i==4 | $i==8 | $i==12 | $i==16 | $i==20 | $i==24 | $i==28 | $i==32 | $i==36) echo '</tr>';
}
//echo '</table>';


?>