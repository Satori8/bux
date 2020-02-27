<?php
if(!DEFINED("YOUAUTOSERF_STAT")) {die ("Hacking attempt!");}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Статистика просмотров авто-серфинга <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> № '.$id.' за последние 24 часа</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_autoyoutube` WHERE `id`='$id' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$status = $row["status"];

	require(DOC_ROOT."/navigator/navigator.php");
	$perpage = 30;
	$sql_p = mysql_query("SELECT `id` FROM `tb_ads_autoyoutube_visits` WHERE `ident`='$id' AND `money`>'0'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	$sql = mysql_query("SELECT * FROM `tb_ads_autoyoutube_visits` WHERE `ident`='$id' AND `type`='visit' AND `money`>'0' ORDER BY `date` ASC LIMIT $start_pos,$perpage");
	if(mysql_num_rows($sql)>0) {

		if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=statistics&id=".$id, $perpage, 10, '&page=', "");
		echo '<table class="tables">';
		echo '<thead><tr align="center">';
			echo '<th width="150">Дата</th>';
			echo '<th>Логин</th>';
			echo '<th>IP</th>';
			echo '<th>Страна</th>';
		echo '</tr></thead>';

		while ($row_v = mysql_fetch_array($sql)) {
			echo '<tr align="center">';
				echo '<td nowrap="nowrap" align="center">';
					if( DATE("d.m.Y", $row_v["date"]) == DATE("d.m.Y", time()) ) {
						echo '<span style="color:#006400;">Сегодня</span>, '.DATE("в H:i", $row_v["date"]);
					}elseif( DATE("d.m.Y", $row_v["date"]) == DATE("d.m.Y", (time()-24*60*60)) ) {
						echo '<span style="color:#000080;">Вчера</span>, '.DATE("в H:i", $row_v["date"]);
					}else{
						echo '<span style="color:#363636;">'.DATE("d.m.Y", $row_v["date"]).'</span> '.DATE("H:i", $row_v["date"]);;
					}
				echo '</td>';

				echo '<td>'.$row_v["username"].'</td>';
				echo '<td>'.$row_v["ip"].'</td>';

				$record = @geoip_record_by_addr($gi, $row_v["ip"]);

				if($record==false) {
					$country_code="";
				}else{
					$country_code = @$record->country_code;
				}

				echo '<td><img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.strtolower($country_code).'.gif" alt="'.get_country($country_code).'" title="'.get_country($country_code).'" align="absmiddle" width="16" height="11" style="margin:0; padding:0; border:none;" />&nbsp;'.get_country($country_code).'</td>';
			echo '</tr>';
		}
		echo '</table>';
		if($count>$perpage) universal_link_bar($count, $page, "?ads=".$ads."&op=statistics&id=".$id, $perpage, 10, '&page=', "");
	}

}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>