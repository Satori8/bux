<? include('checkcookie.php'); ?>
<h3>������ �������� �� ����</h3>
<br>

<?
$res=mysql_query("select price from tb_config where item='ddosmaxhits'");
$res=mysql_fetch_array($res);
$maxhits=$res["price"];

$res=mysql_query("select price from tb_config where item='ddosperiod'");
$res=mysql_fetch_array($res);
$period=$res["price"];

$res=mysql_query("select price from tb_config where item='ddoscrits'");
$res=mysql_fetch_array($res);
$maxcrit=$res["price"];

if($_POST["action"]=="save")
{
	$maxhits=$_POST["maxhits"];
	$period=$_POST["period"];
	$maxcrit=$_POST["maxcrit"];

	mysql_query("update tb_config set price='$maxhits' where item='ddosmaxhits'");
	mysql_query("update tb_config set price='$period' where item='ddosperiod'");
	mysql_query("update tb_config set price='$maxcrit' where item='ddoscrits'");

	echo "<font color=#00cc00><b>��������� ���������</b></font><br>";
}
?>

<fieldset>
<b>����������� ���</b> - ��� ���, ����������� ����� ����� �� 1 ������. ������� ����� ����������� ����� ����� ����������������� � ���, ��� �� ���� ���� 
DDOS �����. ��������� ����� ��������� ��������� ����� ����� ���������� �����������, ����������� ���� � �� ����������� ���, ������� �������� ����� ������� 
����������� ���.
</fieldset>

<form action="" method="post">
<b>�������� ����� �� ���������� �� 1 ������:</b><br>
<input type="text" value="<?=$maxhits?>" name="maxhits"><br><br>

<b>������������ 1 ������� � ��������:</b><br>
<input type="text" value="<?=$period?>" name="period"><br><br>

<b>�������� ����������� ����� ��� ����������:</b><br>
<input type="text" value="<?=$maxcrit?>" name="maxcrit"><br><br>

<input type="hidden" value="save" name="action">

<input type=submit value="���������">
</form>

<br><br>
������ ������ ����������� � �� �������� �� ���� (������ ������������ ������������ ������� ���� ����������� ����!!!):<br>
<table width=100% align=center>
<tr>
	<th>IP �����</th>
	<th>��������� ���������� ����������</th>
	<th>����� � �������</th>
	<th>������� ���������������</th>
	<th>��������, %</th>
	<th>������ ���������� �������</th>
</tr>
<?
$res=mysql_query("select * from tb_online order by id desc");
while($row=mysql_fetch_array($res))
{
	$ip=$row["ip"];
	$lastvisit=date("d.m.Y � H:i",$row["date"]);
	$login=$row["username"];
	$page=$row["page"];
	$loading=$row["id"];
	
	if($row["blocked"]=='0') { $blockstatus='������ ��������'; }else{ $blockstatus='������������'; }

	$cvet='00ff00';
	if($loading>50) $cvet='ffff00';
	if($loading>80) $cvet='ff0000';

	echo "<tr><td align=center bgcolor=#$cvet>$ip</td><td align=center bgcolor=#$cvet>$lastvisit</td><td align=center bgcolor=#$cvet>$login</td>
	<td align=center bgcolor=#$cvet>$page</td><td align=center bgcolor=#$cvet>$loading %</td><td align=center bgcolor=#$cvet>$blockstatus</td></tr>";	
}
?>
</table>