<?php
$pagetitle = "Текстовые объявления";
include('header.php');
error_reporting (E_ALL);

if(!defined("txt_ob_file")) define("txt_ob_file", $_SERVER["DOCUMENT_ROOT"].'/cache/txt_links.inc');


if(!isset($textlinks_array) && is_file(txt_ob_file)){
	$txt_ob_array = @unserialize(file_get_contents(txt_ob_file));

	$cnt_txt_ob = count($txt_ob_array);

	if($cnt_txt_ob>0) {
		for($i=0; $i<$cnt_txt_ob; $i++){
			echo '<table class="tables" style="margin:0 auto; margin-left:0px; width:100%; table-layout: fixed; border:1px solid green;">';
			echo '<tr>';
				echo '<td align="center"><div style="text-wrap:normal; word-wrap: break-word;">';
					echo '<b><a href="'.$txt_ob_array[$i]["link"].'" target="_blank" class="block">'.$txt_ob_array[$i]["text"].'</a></b>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="center"><div style="text-wrap:normal; word-wrap: break-word;">';
					echo 'Дата размещения: '.DATE("d.m.Y H:i", $txt_ob_array[$i]['dataN']).' | Дата окончания: '.DATE("d.m.Y H:i", $txt_ob_array[$i]['dataO']).'';
				echo '</td>';
			echo '<tr>';
			echo '</table><br>';
		}
	}

}
echo '<br><br><div align="center"><a href="/advertise.php?ads=txt_ob" class="sub-blue160" style="float:none; width:160px;">Разместить объявление</a></div>';

unset($txt_ob_array, $cnt_txt_ob);

include('footer.php');?>