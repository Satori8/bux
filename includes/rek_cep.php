<?php

$count_rek_cep_max = 5;

if(!defined('rek_cep_file'))
	define("rek_cep_file", $_SERVER["DOCUMENT_ROOT"].'/cache/rek_cep.inc');

if(!isset($rek_cep_array) && is_file(rek_cep_file) ){
	$rek_cep_array = @unserialize(file_get_contents(rek_cep_file));
	$count_rek_cep = count($rek_cep_array);

	echo '<table align="center" cellspacing="6" cellpadding="0" valign="middle" border="0" width="100%">';
	echo '<tr align="center">';
		for($i=0; $i<$count_rek_cep_max; $i++){
			if(($i)<$count_rek_cep) {
				if($rek_cep_array[$i]['color']=="1") {$style_rc='class="block1"';}else{$style_rc="";}
				echo '<td align="center" width="20%" style="background: #fbf3ef;-moz-border-radius: 5px;border-radius: 5px;padding:3px;box-shadow: 0px 0px 2px 2px rgb(255, 127, 80);">';
					echo '<a href="/redir_rek_cep.php?id='.$rek_cep_array[$i]['id'].'" target="_blank" '.$style_rc.' title="'.$rek_cep_array[$i]['url'].'">'.$rek_cep_array[$i]['description'].'</a><br /><small>[&nbsp;Кликов:&nbsp;<b>'.$rek_cep_array[$i]['view'].'</b>&nbsp;]</small>';
				echo '</td>';
			}else{
				echo '<td align="center" width="20%" style="background: #fbf3ef;-moz-border-radius: 5px;border-radius: 5px;padding:3px;box-shadow: 0px 0px 2px 2px rgb(255, 127, 80);">';
					echo '<a href="/advertise.php?ads=rek_cep" target="_blank" title="Разместить ссылку">Место свободно.<br>Разместить ссылку</a>';
				echo '</td>';
			}
			if($i!=($count_rek_cep_max-1)) echo '<td align="center"><a href="/advertise.php?ads=rek_cep"><img src="/img/forward1.gif" width="17" align="middle" border="0" alt="" title="Добавить ссылку" /></a></td>';
		}
	echo '</tr>';
	echo '</table>';
}else{
	echo '<table align="center" cellspacing="6" cellpadding="0" valign="middle" border="0" width="100%">';
	echo '<tr align="center">';
		for($i=0; $i<$count_rek_cep_max; $i++){
			echo '<td align="center" width="20%" style="background: #fbf3ef;-moz-border-radius: 5px;border-radius: 5px;padding:3px;box-shadow: 0px 0px 2px 2px rgb(255, 127, 80);">';
				echo '<a href="/advertise.php?ads=rek_cep" target="_blank" title="Разместить ссылку">Место свободно.<br>Разместить ссылку</a>';
			echo '</td>';
			if($i!=4) echo '<td align="center"><a href="/advertise.php?ads=rek_cep"><img src="/img/forward1.gif" width="17" align="middle" border="0" alt="" title="Разместить ссылку" /></a></td>';
		}
	echo '</tr>';
	echo '</table>';
}

unset($cache_expires_time, $rek_cep_array, $count_rek_cep_max, $count_rek_cep);

?>