<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>���������� ������������� ���������</b></h3>';

$ads = "pay_visits";
//$security_key = "AsDiModI*N^I&uwK(*An#*hg@if%YST630nlkj7p0U?";
$security_key = "AsDiModI*N^I&uwK(*An#*hg@if%YST630nlkj7p0U?";
$token_string = strtolower(md5($user_id.strtolower($username).$_SERVER["HTTP_HOST"]."adv-add".$security_key));

require(ROOT_DIR."/config_mysqli.php");

function SqlConfig($item, $howmany=1, $decimals=false){
	global $mysqli;

	$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='$item' AND `howmany`='$howmany'") or die($mysqli->error);
	$price = $sql->num_rows > 0 ? $sql->fetch_object()->price : die("Error: item['$item'] or howmany['$howmany'] not found in table `tb_config`");
	$sql->free();

	return ($decimals!==false && is_numeric($price)) ? round($price, $decimals) : $price;
}

$pvis_cena_hit = SqlConfig('pvis_cena_hit', 1, 4);
$pvis_cena_hideref = SqlConfig('pvis_cena_hideref', 1, 4);
$pvis_cena_color = SqlConfig('pvis_cena_color', 1, 4);
$pvis_cena_revisit[1] = SqlConfig('pvis_cena_revisit', 1, 4);
$pvis_cena_revisit[2] = SqlConfig('pvis_cena_revisit', 2, 4);
$pvis_cena_uniq_ip[1] = SqlConfig('pvis_cena_uniq_ip', 1, 4);
$pvis_cena_uniq_ip[2] = SqlConfig('pvis_cena_uniq_ip', 2, 4);
$pvis_min_pay = SqlConfig('pvis_min_pay', 1, 2);
$pvis_max_pay = SqlConfig('pvis_max_pay', 1, 2);
$pvis_comis_sys = SqlConfig('pvis_comis_sys', 1, 0);

$reit_user_arr = array();
$sql_s = $mysqli->query("SELECT `id`,`rang`,`r_ot`,`cnt_users` FROM `tb_config_rang` WHERE `id`>'1' ORDER BY `id` ASC") or die($mysqli->error);
if($sql_s->num_rows > 0) {
	$reit_user_arr[0] = "��� ������������ �������";
	while ($row_s = $sql_s->fetch_assoc()) {
		$reit_user_arr[$row_s["id"]] = "� ��������� ".number_format($row_s["r_ot"], 0, ".", " ")." � ����� ������ (".$row_s["rang"]." ~ ".number_format($row_s["cnt_users"], 0, ".", " ")." ���.)";
	}
	$sql_s->free();
}else{
	$reit_user_arr[0] = "��� ������������ �������";
	$sql_s->free();
}

$mysqli->close();
?>

<script>
$(document).ready(function(){
	var hint_ids, hint_txt = [];
	hint_txt[1] = '<b>��������� ������</b> - �������� <b>60</b> ��������.<br>��������� ������ ���� ������� � ��������. ���������� ����������. ��������� ��������� �������� �����������. �� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ����� ��� ������� ��������� ������� ���� �������.';
	hint_txt[2] = '<b>�������� ������</b> - �������� <b>80</b> ��������.<br>����������� ��� ����������. �������� ������ ���� ������� � ��������. ���������� ����������. ��������� ��������� �������� �����������. �� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ����� ��� ������� ��������� ������� ���� �������.';
	hint_txt[3] = '<b>URL-����� �����</b> ������ ���������� � http:// ��� https:// � ��������� �� ����� 300 ��������. �� ����������� HTML-���� � Java-�������.';
	hint_txt[4] = '<b>������ HTTP_REFERER</b> - �� ������ ������ ����� ���-����� � �������� ������ ����������. ��������� ��������, ������ ������, ����� �� ������������ ������ �����.';
	hint_txt[5] = '<b>�������� ������</b> - ���� ������ ����� <b class="text-red">�������� ������� ������</b>, ��� ������� � �������� �� ��������� � ������� ��������.';
	hint_txt[6] = '<b>�������� ��� ���������</b> - �������� ��� ������������ ����� ������������� ���� �������. ����������� ��������� "������ 24 ����" ��������, ��� ���� � ��� �� ������������ ����� ������������� ���� ������� ������ 24 ����, �������� ������� � �������������� ��������� "������ 48 �����" � "1 ��� � �����".';
	hint_txt[7] = '<b>���������� IP</b> - �� ������ ���������� ����� ����� ������� ��� ������ ���������� � ������������� IP ��� ����� �������. ��� ��������� ������ ����� ���� ������� � ������ IP ��� ����� ����� ������� ������ ����������� ������ ���� ���.';
	hint_txt[8] = '<b>�� ���� �����������</b> - �� ������ ���������� ����������� ��������� ������ �� ���� ����������� ������������ �� ����� �������.';
	hint_txt[9] = '<b>�� �������� ������������</b> - �� ������ ���������� ����������� ��������� ������ �� �������� ������������ �� ����� �������.';
	hint_txt[10] = '<b>�� ������� ��������</b> - �� ������ ���������� ��������� ������ ������ �������������� �� �������� �������� �� ����� �������.';
	hint_txt[11] = '<b>�� �������� ��������</b> - �� ������ ���������� ����� ����� ������� �� �������� �������� �������������, �������� ����� ����� ������� ������ ������������� �������� ���� �������� ����.';
	hint_txt[12] = '<b>���������� ������ ���������</b> - �� ������ ���������� ����� ����� �������, �������� ���������� �� ������ ����� ���������.';
	hint_txt[13] = '<b>������� <img src="/img/18_plus.png" alt="18+" width="16" height="16" align="absmiddle" style="margin:0 0 3px; padding:0;"></b> &mdash; ���� �� ����� ����� ������������ ��������� ��� ��������, ����������� ���������� �������, � ��������� ������ ���� ������� ����� ���� ������� �� ������, � ������, ����������� �� �� ����������, ���������� �� �����. ��� �� �������� �����������, ��� ��� �����, ���������� ����������� ������ ��������� ��� �������������� �� ����� �������. ����� ����������� ������������ � <b>������� ������ 3.5.3</b>';
	hint_txt[14] = '<b>������������</b> - ��� ����������� ���������� ��������� ������ ����� �� ���������������� ��������. ������ ����� �������� ��� ��������� ������ ������������� �� ��� �����, ������� �� ��������. �� ��������� �������� �� ������ ��������� ������������� �� ����� �����.';
	hint_txt[16] = '<b>����� ����������</b> - ������� �����, ������� �� ������ ������ � ������ ��������� ��������.';
	hint_txt[17] = '<b>���� ������ ���������</b> - ��������� �� ���� ��������� �����.';
	hint_txt[18] = '<b>���������� ���������</b> - ���������� ������� �����, ������� ������� ��������� ��������';
	hint_ids = Object.keys(hint_txt);

	for (var i = 0; i < hint_ids.length; i++) {
		$("#hint-"+hint_ids[i]).simpletip({fixed: true, position: ["-609", "24"], focus: false, content: hint_txt[hint_ids[i]]});
	}
})

function PlanChange(){
	var rprice = <?=$pvis_cena_hit;?>;

	var hide_httpref = $.trim($("#hide_httpref").val());
	var color = $.trim($("#color").val());
	var revisit = $.trim($("#revisit").val());
	var uniq_ip = $.trim($("#uniq_ip").val());
	var money_add = $.trim($("#money_add").val());

	money_add = money_add.replace(/\,/g, ".");
	money_add = money_add.match(/(\d+(\.)?(\d){0,2})?/);
	money_add = money_add[0] || '';
	if($("#money_add").attr("type")!="number") $("#money_add").val(money_add);

	money_add = number_format_js(money_add, 2, ".", "");

	if(hide_httpref == 1) rprice += <?=$pvis_cena_hideref;?>;
	if(color == 1) rprice += <?=$pvis_cena_color;?>;

	if(revisit == 1) rprice += <?=$pvis_cena_revisit[1];?>;
	else if (revisit == 2) rprice += <?=$pvis_cena_revisit[2];?>;

	if(uniq_ip == 1) rprice += <?=$pvis_cena_uniq_ip[1];?>;
	else if(uniq_ip == 2) rprice += <?=$pvis_cena_uniq_ip[2];?>;

	count_pvis = parseFloat((money_add*10000)/(rprice*10000));

	$("#price_one").html('<span class="text-green"><b>'+number_format_js(rprice, 4, ".", " ")+'</b> ���.</span>');
	$("#count_pvis").html('<span class="text-blue"><b>'+number_format_js(Math.floor(count_pvis), 0, ".", " ")+'</b></span>');
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		$("#subAdv").click();
	}
}
</script>

<?php
echo '<div class="scroll-to"></div>';

echo '<div id="newform" class="form-adv-list ec.c" onKeyPress="CtrlEnter(event);"><form id="form-adv-'.$ads.'" action="" method="POST" onSubmit="FuncAdv(0, \'adv-add\', \''.$ads.'\', $(this).attr(\'id\'), \''.$token_string.'\', \'modal\', false, 550); return false;">';
	echo '<table class="tables" style="margin:0 auto;">';
	echo '<thead><tr><th>��������</th><th colspan="2">��������</th></tr></thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td width="240"><b>��������� ������</b></td>';
			echo '<td><input type="text" id="title" name="title" maxlength="60" value="" class="ok" required="required"></td>';
			echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-1" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>������� �������� ������</b></td>';
			echo '<td><input type="text" id="description" name="description" maxlength="80" value="" class="ok" required="required"></td>';
			echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-2" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>URL �����</b> (������� http:// ��� https://)</td>';
			echo '<td align="center"><input type="url" id="url" name="url" maxlength="300" value="" class="ok" required="required"></td>';
			echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-3" class="hint-quest"></span></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<div id="adv-title-dopset" class="adv-title-open" onClick="SHBlock(\'dopset\');">���������</div>';
	echo '<div id="adv-block-dopset" style="display:block;">';
		echo '<table class="tables" style="margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="240"><b>������ HTTP_REFERER</b></td>';
				echo '<td>';
					echo '<select id="hide_httpref" name="hide_httpref" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">�� '.($pvis_cena_hideref>0 ? "(+$pvis_cena_hideref ���./���������)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-4" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>�������� ������</td>';
				echo '<td>';
					echo '<select id="color" name="color" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">�� '.($pvis_cena_color>0 ? "(+$pvis_cena_color ���./���������)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-5" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>�������� ��� ���������</b></td>';
				echo '<td>';
					echo '<select id="revisit" name="revisit" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">������ 24 ����</option>';
						echo '<option value="1">������ 48 ����� '.($pvis_cena_revisit[1]>0 ? "(+$pvis_cena_revisit[1] ���./���������)" : false).'</option>';
						echo '<option value="2">1 ��� � ����� '.($pvis_cena_revisit[2]>0 ? "(+$pvis_cena_revisit[2] ���./���������)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-6" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>���������� IP</td>';
				echo '<td>';
					echo '<select id="uniq_ip" name="uniq_ip" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">���</option>';
						echo '<option value="1">�� (100% ����������) '.($pvis_cena_uniq_ip[1]>0 ? "&mdash; (+$pvis_cena_uniq_ip[1] ���./���������)" : false).'</option>';
						echo '<option value="2">��������� �� ����� �� 2 ����� (255.255.X.X) '.($pvis_cena_uniq_ip[2]>0 ? "&mdash; (+$pvis_cena_uniq_ip[2] ���./���������)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-7" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>�� ���� �����������</td>';
				echo '<td>';
					echo '<select id="date_reg_user" name="date_reg_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="3">3 ��� � ������� �����������</option>';
						echo '<option value="7">7 ���� � ������� �����������</option>';
						echo '<option value="30">1 ����� � ������� �����������</option>';
						echo '<option value="90">3 ������ � ������� �����������</option>';
						echo '<option value="180">6 ������� � ������� �����������</option>';
						echo '<option value="365">1 ��� � ������� �����������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-8" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>�� �������� ������������</td>';
				echo '<td>';
					echo '<select id="reit_user" name="reit_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						foreach($reit_user_arr as $key => $val) {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16"><span id="hint-9" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>�� ������� ��������</td>';
				echo '<td>';
					echo '<select id="no_ref" name="no_ref" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">������������� ��� �������� �� '.$_SERVER["HTTP_HOST"].'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-10" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>�� �������� ��������</td>';
				echo '<td>';
					echo '<select id="sex_user" name="sex_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">������ �������</option>';
						echo '<option value="2">������ �������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-11" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>���������� ������ ���������</td>';
				echo '<td>';
					echo '<select id="to_ref" name="to_ref" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0">��� ������������ �������</option>';
						echo '<option value="1">��������� 1-�� ������</option>';
						echo '<option value="2">��������� ���� �������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-12" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>������� 18+</td>';
				echo '<td>';
					echo '<div style="float:left;"><input type="checkbox" id="content" name="content" value="1" style="height:16px; width:16px; margin:0px;"></div>';
					echo '<div style="float:left; padding:1px 5px 0;">- �� ���� ����� ������������ ��������� ��� ��������</div>';
				echo '</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-13" class="hint-quest"></span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<div id="adv-title-geotarg" class="adv-title-close" onClick="SHBlock(\'geotarg\');">������������</div>';
	echo '<div id="adv-block-geotarg" style="display:none;">';
		echo '<table class="tables" style="margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td colspan="2" align="center"><a onclick="SetChecked(\'country[]\', \'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
				echo '<td colspan="2" align="center"><a onclick="SetChecked(\'country[]\', \'unpaste\');" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
				echo '<td rowspan="10" style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-14" class="hint-quest"></span></td>';
			echo '</tr>';
			include(ROOT_DIR."/advertise/func_geotarg.php");
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<div id="adv-title-infopay" class="adv-title-open" onclick="SHBlock(\'infopay\');">����������</div>';
	echo '<div id="adv-block-infopay" style="display:block;">';
		echo '<table class="tables" style="margin:0 auto;">';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="240" align="left"><b>����� ����������</b></td>';
				echo '<td align="left"><input type="text" id="money_add" name="money_add" maxlength="10" value="100.00" step="any" min="'.$pvis_min_pay.'" max="'.$pvis_max_pay.'" class="ok12" required="required" autocomplete="off" style="text-align:center;" onKeydowm="PlanChange();" onKeyup="PlanChange();">&nbsp;&nbsp;(������� - '.number_format($pvis_min_pay, 2, ".", " ").' ���.)</td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-16" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td style="height:22px;">���� ������ ���������</td>';
				echo '<td id="price_one"></td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-17" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td style="height:22px;">���������� ���������</td>';
				echo '<td id="count_pvis"></td>';
				echo '<td style="width:16px; text-align:center; background: #EDEDED;"><span id="hint-18" class="hint-quest"></span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<table class="tables" style="margin:0 auto;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="center"><input id="subAdv" type="submit" value="�����" class="sd_sub big green"></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

echo '</form></div>';
echo '<div class="form-adv-order ec.c" style="display:none;"></div>';

?>

<script language="JavaScript">ClearForm();PlanChange();</script>