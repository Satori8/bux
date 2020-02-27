<?php

define("txt_ob_file", $_SERVER["DOCUMENT_ROOT"].'/cache/txt_links.inc');

echo '<table class="tables" style="margin:0 auto; width:100%;">';
echo '<tr><td align="left" colspan="2" class="td-statlink"><span style="font-weight: bold; color:#ab064e;">Текстовое объявление:</span></td></tr>';

if(!isset($txt_ob_arr) && is_file(txt_ob_file) ) {
	$txt_ob_arr = @unserialize(file_get_contents(txt_ob_file));
	$cnt = count($txt_ob_arr);
	if($cnt>1) $cnt=1;

	if(isset($txt_ob_arr) && $cnt>0) {
		shuffle($txt_ob_arr);

		echo '<tr><td align="center" class="td-statlink">';
			for($i=0; $i<$cnt; $i++){
				echo '<div style="padding:15px;border: 3px solid #ff7f50;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;box-shadow: inset 0 0 20px #893f45;-moz-box-shadow:inset 0 0 20px #855e42;-webkit-box-shadow: inset 0 0 20px #ff7f50;text-wrap:normal;word-wrap:break-word;background-color: #fbf3ef;">';
					echo '<b><img src="/img/forward3.gif" align="left" style="margin:-10px;"><a href="'.$txt_ob_arr[$i]["link"].'" target="_blank" class="block">'.$txt_ob_arr[$i]["text"].'</a></b>';
				echo '</div>';
			}
			//echo '<div align="center" style="margin-top:3px;"><a href="/alltxtob.php" target="_blank" class="slink1">Смотреть все</a> | <a href="/advertise.php?ads=txt_ob" target="_blank" class="slink1">Разместить ссылку</a></div>';
		echo '</td></tr>';

		echo '<tr><td align="right" style="margin-top:3px;"><a href="/alltxtob.php" target="_blank" class="link_adv"">Смотреть все</a> | <a href="/advertise.php?ads=txt_ob" target="_blank" class="link_adv">Разместить объявление</a></td></tr>';
	}else{
		echo '<tr><td align="right" style="margin-top:3px;"><a href="/advertise.php?ads=txt_ob" target="_blank" class="link_adv">Разместить объявление</a></td></tr>';
	}
}else{
	echo '<tr><td align="right" style="margin-top:3px;"><a href="/advertise.php?ads=txt_ob" target="_blank" class="link_adv">Разместить объявление</a></td></tr>';
}

echo '</table>';

unset($txt_ob_arr, $cnt);
?>