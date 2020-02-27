<? include('checkcookie.php'); ?>
<h3>Анализ нагрузки на сайт</h3>
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

	echo "<font color=#00cc00><b>Настройки сохранены</b></font><br>";
}
?>

<fieldset>
<b>Критический хит</b> - это хит, превышающий лимит хитов за 1 период. Большое число критических хитов может свидетельствовать о том, что на сайт идет 
DDOS атака. Установка этого параметра позволяет более точно определять посетителей, нагружающих сайт и не блокировать тех, которые случайно могли сделать 
критический хит.
</fieldset>

<form action="" method="post">
<b>Максимум хитов до блокировки за 1 период:</b><br>
<input type="text" value="<?=$maxhits?>" name="maxhits"><br><br>

<b>Длительность 1 периода в секундах:</b><br>
<input type="text" value="<?=$period?>" name="period"><br><br>

<b>Максимум критических хитов для блокировки:</b><br>
<input type="text" value="<?=$maxcrit?>" name="maxcrit"><br><br>

<input type="hidden" value="save" name="action">

<input type=submit value="Сохранить">
</form>

<br><br>
Список онлайн посетителей и из нагрузка на сайт (вверху показываются пользователи сильнее всех нагружающие сайт!!!):<br>
<table width=100% align=center>
<tr>
	<th>IP адрес</th>
	<th>Последнее проявление активности</th>
	<th>Логин в системе</th>
	<th>Текущее местонахождение</th>
	<th>Нагрузка, %</th>
	<th>Статус блокировки доступа</th>
</tr>
<?
$res=mysql_query("select * from tb_online order by id desc");
while($row=mysql_fetch_array($res))
{
	$ip=$row["ip"];
	$lastvisit=date("d.m.Y в H:i",$row["date"]);
	$login=$row["username"];
	$page=$row["page"];
	$loading=$row["id"];
	
	if($row["blocked"]=='0') { $blockstatus='Доступ разрешен'; }else{ $blockstatus='Заблокирован'; }

	$cvet='00ff00';
	if($loading>50) $cvet='ffff00';
	if($loading>80) $cvet='ff0000';

	echo "<tr><td align=center bgcolor=#$cvet>$ip</td><td align=center bgcolor=#$cvet>$lastvisit</td><td align=center bgcolor=#$cvet>$login</td>
	<td align=center bgcolor=#$cvet>$page</td><td align=center bgcolor=#$cvet>$loading %</td><td align=center bgcolor=#$cvet>$blockstatus</td></tr>";	
}
?>
</table>