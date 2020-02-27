<script type="text/javascript" language="JavaScript">
function iavtope(val) {
   var frm=document.user_top;
   frm.po.value = val;
   document.cookie="po="+val;
   frm.submit();
}
</script>

<div class="refblock-lines" style="margin-top: 15px;">
<span class="refblock-line<?if($_COOKIE['po']==0){echo '-active';}?>" title="Пользователи в отпуску:" onclick="javascript:iavtope(0);">В отпуске</span>
<span class="refblock-line<?if($_COOKIE['po']==1){echo '-active';}?>" title="Пользователи в платном отпуску:" onclick="javascript:iavtope(1);">В платном от</span>
<br></div>
<?php
echo '<form name="user_top" method="POST" action="/adminka_lilaks_tools/index.php?op=users_vac_list"><input type="hidden" name="po"></form>';

if($_COOKIE['po'] == 0)
{
echo '<h2 align="center" style="color:blue">Пользователи в отпуску:</h2>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top"></th>';
	echo '<th colspan="2" class="top">Логин</th>';
	echo '<th colspan="2" class="top">Реферер</th>';
	echo '<th class="top">Денег на счету</th>';
	echo '<th class="top">Рефералов 1ур.</th>';
	echo '<th class="top">Рефералов 2ур.</th>';
	echo '<th class="top">Рефералов 3ур.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `vac1`>'0'");
	while($stat=mysql_fetch_array($sql)) {
		$i=$i+1;
		$ref=mysql_query("select avatar from tb_users where `username`='".$stat["referer"]."'");
        if(mysql_num_rows($ref) ) {
        $row_r = mysql_fetch_assoc($ref);
        $ref_avatar = $row_r["avatar"];
		}
		echo '<tr align="center">';
		echo '<td width="5%">'.$i.'</td>';
		echo '<td width="10%"><img class="avatar" src="/avatar/'.$stat["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar"></td>';
		echo '<td width="10%">'.$stat["username"].'</td>';
		echo '<td width="10%"><img class="avatar" src="/avatar/'.$ref_avatar.'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar"></td>';
		echo '<td width="10%">'.$stat["referer"].'</td>';
		echo '<td width="20%">'.round($stat["money"],2).' руб.</td>';
		echo '<td width="20%">'.$stat["referals"].'</td>';
		echo '<td width="20%">'.$stat["referals2"].'</td>';
		echo '<td width="20%">'.$stat["referals3"].'</td>';
		echo '</tr>';
	}
echo '</table><br />';
}

if($_COOKIE['po'] == 1)
{
echo '<h2 align="center" style="color:blue">Пользователи в платном отпуску:</h2>';
echo '<table class="tables">';
echo '<thead><tr>';
	echo '<th class="top"></th>';
	echo '<th colspan="2" class="top">Логин</th>';
	echo '<th colspan="2" class="top">Реферер</th>';
	echo '<th class="top">Денег на счету</th>';
	echo '<th class="top">Рефералов 1ур.</th>';
	echo '<th class="top">Рефералов 2ур.</th>';
	echo '<th class="top">Рефералов 3ур.</th>';
echo '</tr></thead>';

	$sql = mysql_query("SELECT * FROM tb_users WHERE `vac2`>'0'");
	while($stat=mysql_fetch_array($sql)) {
		$i=$i+1;
		$ref=mysql_query("select avatar from tb_users where `username`='".$stat["referer"]."'");
        if(mysql_num_rows($ref) ) {
        $row_r = mysql_fetch_assoc($ref);
        $ref_avatar = $row_r["avatar"];
		}
		echo '<tr align="center">';
		echo '<td width="5%">'.$i.'</td>';
		echo '<td width="10%"><img class="avatar" src="/avatar/'.$stat["avatar"].'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar"></td>';
		echo '<td width="10%">'.$stat["username"].'</td>';
		echo '<td width="10%"><img class="avatar" src="/avatar/'.$ref_avatar.'" style="width:30px; height:30px" border="0" alt="avatar" title="avatar"></td>';
		echo '<td width="10%">'.$stat["referer"].'</td>';
		echo '<td width="20%">'.round($stat["money"],2).' руб.</td>';
		echo '<td width="20%">'.$stat["referals"].'</td>';
		echo '<td width="20%">'.$stat["referals2"].'</td>';
		echo '<td width="20%">'.$stat["referals3"].'</td>';
		echo '</tr>';
	}
echo '</table><br />';
}
?>