<?php

define("kontext_file", $_SERVER["DOCUMENT_ROOT"].'/cache/kontext_link.inc');

if(!isset($kontext_array) && is_file(kontext_file) ) {
	$kontext_array = @unserialize(file_get_contents(kontext_file));
	$cnt = count($kontext_array);
	if($cnt>7) $cnt=7;

	shuffle($kontext_array);

	echo '<table class="tables" style="margin:0 auto; margin-left:0px; width:100%; table-layout: fixed;">';
	for($i=0; $i<$cnt; $i++){
		if($kontext_array[$i]["color_kl"]==1) {$style='style="color:#C80000; border-bottom:1px dotted"';}else{$style='style="color:#006699; border-bottom:1px dotted;"';}
		echo '<tr><td><div style="text-wrap:normal; text-align:left; word-wrap: break-word; font-size:13px;">';
			echo '<a href="/redir_kontext.php?id='.$kontext_array[$i]["id_kl"].'" target="_blank" title="Переходов: '.$kontext_array[$i]["views_kl"].' шт." style="text-decoration:none;">';
			echo '<font '.$style.'>'.$kontext_array[$i]["title_kl"].'</font><font style="display:block; color:#4F4F4F; font-size:85%">'.$kontext_array[$i]["desc_kl"].'</font></a>';
		echo '</div></td></tr>';
	}
	echo '</table>';
}
unset($kontext_array, $cnt);

echo '<div align="center" style="padding:5px 0 0 6px;"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=kontext\'">Разместить ссылку</span></div>';
?>
