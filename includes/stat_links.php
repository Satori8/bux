<?php

define("stat_link_file", $_SERVER["DOCUMENT_ROOT"].'/cache/stat_link.inc');

echo '<table class="tables" style="margin:0 auto; width:100%;">';
echo '<tr><td align="left" colspan="2" class="td-statlink"><span style="font-weight: bold;color:#ab064e;">Статические ссылки:</span></td></tr>';

if(!isset($stat_link_arr) && is_file(stat_link_file) ) {
	$stat_link_arr = @unserialize(file_get_contents(stat_link_file));
	$cnt = count($stat_link_arr);

	for($i=0; $i<$cnt; $i++){
		if($stat_link_arr[$i]["color_sl"]==1) {$style='class="slink2"';}else{$style='class="slink1"';}
		echo '<tr>';
			echo '<td width="16" align="center" class="td-statlink"><img src="https://www.google.com/s2/favicons?domain='.@gethost($stat_link_arr[$i]["url_sl"]).'" width="16" height="16" border="0" alt="" title="" style="margin:2px 0; padding:0;" align="absmiddle" /></td>';
			echo '<td align="left" class="td-statlink" style="padding-left:0px;"><a href="'.$stat_link_arr[$i]["url_sl"].'" target="_blank" '.$style.'>'.$stat_link_arr[$i]["desc_sl"].'</a></td>';
		echo '</tr>';
	}
}
unset($stat_link_arr, $cnt);

echo '<tr><td align="right" colspan="2"><a href="/advertise.php?ads=stat_links" target="_blank" class="link_adv">Разместить ссылку</a></td></tr>';
echo '</table>';
?>